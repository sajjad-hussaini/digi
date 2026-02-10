@extends('layouts.app')
@section('title','Show '.ucfirst(config('settings.clients_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.clients_label_singular'))}}
            <span class="pull-right">
            <a href="{{ route('clients.index') }}" class="btn btn-default">
                <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
            </a>
            <a href="{{ route('clients.edit',$client->id) }}" class="btn btn-primary">
                <i class="fa fa-edit" aria-hidden="true"></i> Edit
            </a>
            {!! Form::open(['route' => ['clients.destroy', $client->id], 'method' => 'delete','style'=>'display:inline']) !!}
                {!! Form::button('<i class="fa fa-trash"></i> Delete', [
                'type' => 'submit',
                'title' => 'Delete',
                'class' => 'btn btn-danger',
                'onclick' => "return conformDel(this,event)",
                ]) !!}
                {!! Form::close() !!}
        </span>
        </h1>
    </section>
    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#client" data-toggle="tab"
                                      aria-expanded="true">{{ucfirst(config('settings.clients_label_singular'))}}</a>
                </li>
                @can('user manage permission')
                    <li class=""><a href="#tab_permissions" data-toggle="tab"
                                    aria-expanded="false">Permission</a>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="client">
                    @include('clients.show_fields')
                </div>
                @can('user manage permission')
                    <div class="tab-pane" id="tab_permissions">
                      
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Mammoth.js for DOCX to HTML conversion -->
<script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>

<script>
let uploadedDocx = null;
let originalHtml = '';
let documentData = null;

$(document).ready(function() {
    
    // Base Template
    $('#baseTemplateBtn').click(function() {
        window.open("{{ route('client.initial.instruction.base', $client->id) }}", '_blank');
    });

    // DOCX Upload
    $('#templateSelect').change(function(e) {
        uploadedDocx = e.target.files[0];
        
        if (uploadedDocx && uploadedDocx.name.endsWith('.docx')) {
            loadDocx(uploadedDocx);
            $('#choice-step').hide();
            $('#editor-step').show();
        } else {
            alert('Please select a valid .docx file');
        }
    });

    // Back button
    $('#backToChoice').click(function() {
        resetEditor();
    });

    // Find & Replace toggle
    $('#findReplaceBtn').click(function() {
        $('#findReplacePanel').slideToggle();
    });

    // Replace All
    $('#replaceAllBtn').click(function() {
        let findText = $('#findText').val();
        let replaceText = $('#replaceText').val();
        
        if (!findText) {
            alert('Please enter text to find');
            return;
        }
        
        let content = $('#documentContent').html();
        let regex = new RegExp(escapeRegExp(findText), 'g');
        let newContent = content.replace(regex, replaceText);
        
        $('#documentContent').html(newContent);
        
        let count = (content.match(regex) || []).length;
        alert(`Replaced ${count} occurrence(s)`);
    });

    // Generate DOCX
    $('#generateDocxBtn').click(function() {
        generateDocx();
    });

    // Generate PDF
    $('#generatePdfBtn').click(function() {
        generatePdf();
    });
});

// Load DOCX file
function loadDocx(file) {
    console.log('Loading DOCX file...');
    
    let reader = new FileReader();
    
    reader.onload = function(e) {
        let arrayBuffer = e.target.result;
        
        // Convert DOCX to HTML using Mammoth
        mammoth.convertToHtml({arrayBuffer: arrayBuffer})
            .then(function(result) {
                originalHtml = result.value;
                $('#documentContent').html(result.value);
                
                console.log('DOCX loaded successfully');
                
                // Auto-replace placeholders with client data
                autoReplaceClientData();
            })
            .catch(function(err) {
                console.error('Error loading DOCX:', err);
                alert('Error loading document');
            });
    };
    
    reader.readAsArrayBuffer(file);
}

// Auto-replace client data
function autoReplaceClientData() {
    let content = $('#documentContent').html();
    
    // Replace common placeholders
    let replacements = {
        '[CLIENT_NAME]': '{{ $client->first_name }} {{ $client->last_name }}',
        '[CLIENT_FIRST_NAME]': '{{ $client->first_name }}',
        '[CLIENT_LAST_NAME]': '{{ $client->last_name }}',
        '[CLIENT_EMAIL]': '{{ $client->email ?? "" }}',
        '[CLIENT_PHONE]': '{{ $client->phone ?? "" }}',
        '[TODAY_DATE]': '{{ now()->format("jS F Y") }}',
        '[CURRENT_DATE]': '{{ now()->format("jS F Y") }}'
    };
    
    Object.keys(replacements).forEach(function(placeholder) {
        let regex = new RegExp(escapeRegExp(placeholder), 'g');
        content = content.replace(regex, replacements[placeholder]);
    });
    
    $('#documentContent').html(content);
}

// Escape RegExp special characters
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Generate DOCX
function generateDocx() {
    console.log('Generating DOCX...');
    
    let htmlContent = $('#documentContent').html();
    
    // Send to server
    let formData = new FormData();
    formData.append('original_docx', uploadedDocx);
    formData.append('edited_html', htmlContent);
    formData.append('client_id', '{{ $client->id }}');
    formData.append('format', 'docx');
    formData.append('_token', '{{ csrf_token() }}');
    
    $('#generateDocxBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
    
    $.ajax({
        url: "{{ route('client.initial.instruction.generate', $client->id) }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: {
            responseType: 'blob'
        },
        success: function(blob) {
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'Initial_Instruction_{{ $client->first_name }}.docx';
            link.click();
            
            alert('DOCX generated successfully!');
            $('#generateDocxBtn').prop('disabled', false).html('<i class="fas fa-file-word"></i> Generate DOCX');
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            alert('Error generating DOCX');
            $('#generateDocxBtn').prop('disabled', false).html('<i class="fas fa-file-word"></i> Generate DOCX');
        }
    });
}

// Generate PDF
function generatePdf() {
    console.log('Generating PDF...');
    
    let htmlContent = $('#documentContent').html();
    
    let formData = new FormData();
    formData.append('original_docx', uploadedDocx);
    formData.append('edited_html', htmlContent);
    formData.append('client_id', '{{ $client->id }}');
    formData.append('format', 'pdf');
    formData.append('_token', '{{ csrf_token() }}');
    
    $('#generatePdfBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
    
    $.ajax({
        url: "{{ route('client.initial.instruction.generate', $client->id) }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: {
            responseType: 'blob'
        },
        success: function(blob) {
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'Initial_Instruction_{{ $client->first_name }}.pdf';
            link.click();
            
            alert('PDF generated successfully!');
            $('#generatePdfBtn').prop('disabled', false).html('<i class="fas fa-file-pdf"></i> Generate PDF');
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            alert('Error generating PDF');
            $('#generatePdfBtn').prop('disabled', false).html('<i class="fas fa-file-pdf"></i> Generate PDF');
        }
    });
}

// Reset editor
function resetEditor() {
    $('#editor-step').hide();
    $('#choice-step').show();
    $('#templateSelect').val('');
    $('#documentContent').empty();
    $('#findReplacePanel').hide();
    uploadedDocx = null;
}
</script>

<style>
#documentContent {
    font-family: 'Calibri', 'Arial', sans-serif;
    font-size: 11pt;
    line-height: 1.5;
    color: #000;
}

#documentContent p {
    margin: 0 0 10px 0;
}

#documentContent h1, #documentContent h2, #documentContent h3 {
    margin-top: 10px;
    margin-bottom: 10px;
}

#docxEditor {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
@endsection
