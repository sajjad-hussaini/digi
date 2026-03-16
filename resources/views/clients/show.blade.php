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

<script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>

<script>
let selectedTemplateId = null;
let selectedTemplateTitle = '';

$(document).ready(function() {

// 1. Modal khulne par abhi kuch load mat karo
$('#initialInstructionModal').on('show.bs.modal', function() {
    // Optional: reset dropdowns to initial state
    $('#templates_load').val('');
    
    $('#loadTemplateBtn').hide();
    $('#templatesLoading').hide();
});

// 2. Jab user type select karega tab templates load karo
$('#templates_load').on('change', function() {
    const selectedType = $(this).val().trim();

    // Agar koi type nahi chuna to reset kar do
    if (!selectedType) {
        $('#loadTemplateBtn').hide();
        $('#templatesLoading').hide();
        return;
    }

    // Type select hua hai → templates fetch karo
    loadTemplatesByType(selectedType);
});

function loadTemplatesByType(type) {
    $('#templatesLoading').show();
    $('#loadTemplateBtn').hide();

    $.ajax({
        url: "/admin/templates/list",
        type: 'GET',
        data: { type: type },   // ← yeh sabse important change
        success: function(templates) {
            $('#templateSelect').empty();
            console.log(templates);
            if (templates.length === 0) {
                $('#templateSelect').append(
                    '<option value="">No templates found for this type</option>'
                );
            } else {
                $('#templateSelect')
                    .append('<option value="">Choose a template</option>');
                
                templates.forEach(function(template) {
                    $('#templateSelect').append(
                        `<option value="${template.id}">${template.title}</option>`
                    );
                });
            }

            $('#templateSelect').show();
            $('#templatesLoading').hide();
            $('#loadTemplateBtn').show();
        },
        error: function() {
            $('#templateSelect').empty().append(
                '<option value="">Error loading templates</option>'
            );
            $('#templatesLoading').html('<span class="text-danger">Error</span>');
            $('#loadTemplateBtn').hide();
        }
    });
}

    // Base Template
    $('#baseTemplateBtn').click(function() {
        window.open("{{ route('client.initial.instruction.base', $client->id) }}", '_blank');
    });

    // Template select change
    $('#templateSelect').change(function() {
        let val = $(this).val();
        if (val) {
            selectedTemplateId = val;
            selectedTemplateTitle = $(this).find('option:selected').text();
            $('#loadTemplateBtn').prop('disabled', false);
        } else {
            selectedTemplateId = null;
            $('#loadTemplateBtn').prop('disabled', true);
        }
    });

    // Load Template button
    $('#loadTemplateBtn').click(function() {
        if (!selectedTemplateId) return;
        loadTemplateContent(selectedTemplateId);
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
        let findText = $('#findText').val().trim();
        let replaceText = $('#replaceText').val();

        if (!findText) {
            alert('Please enter text to find');
            return;
        }

        // innerHTML mein replace karo
        let content = $('#documentContent').html();
        let regex = new RegExp(findText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
        let count = (content.match(regex) || []).length;
        
        if (count === 0) {
            alert('Text not found');
            return;
        }

        $('#documentContent').html(content.replace(regex, replaceText));
        alert(`Replaced ${count} occurrence(s)`);
    });

    // Generate DOCX
    $('#generateDocxBtn').click(function() {
        generateDocument('docx');
    });

    // Generate PDF
    $('#generatePdfBtn').click(function() {
        generateDocument('pdf');
    });
});


// Load template content from DB
function loadTemplateContent(templateId) {
    $('#choice-step').hide();
    $('#editor-step').show();
    $('#editorLoading').show();
    $('#documentContent').hide();
    $('#templateTitleText').text(selectedTemplateTitle);

    $.ajax({
        url: '/admin/templates/' + templateId + '/content',
        type: 'GET',
        success: function(response) {
            // Base64 to ArrayBuffer convert karo
            let binaryStr = atob(response.content);
            let bytes = new Uint8Array(binaryStr.length);
            for (let i = 0; i < binaryStr.length; i++) {
                bytes[i] = binaryStr.charCodeAt(i);
            }

            // Mammoth se DOCX to HTML convert karo
            mammoth.convertToHtml({arrayBuffer: bytes.buffer})
                .then(function(result) {
                    let html = result.value;
                    
                    // Auto replace client placeholders
                    html = autoReplaceClientData(html);
                    
                    $('#documentContent').html(html);
                    $('#editorLoading').hide();
                    $('#documentContent').show();
                })
                .catch(function(err) {
                    console.error('Mammoth error:', err);
                    $('#editorLoading').html('<span class="text-danger">Error rendering document</span>');
                });
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            $('#editorLoading').html('<span class="text-danger">Error loading template</span>');
        }
    });
}

// Auto replace client placeholders
function autoReplaceClientData(html) {
    let replacements = {
        '[CLIENT_NAME]'       : '{{ $client->first_name }} {{ $client->sir_name }}',
        '[CLIENT_EMAIL]'      : '{{ $client->email ?? "" }}',
        '[CLIENT_PHONE]'      : '{{ $client->phone ?? "" }}',
        '[CLIENT_ADDRESS]'      : '{{ $client->address ?? "" }}',
        '[CLIENT_DOB]'      : '{{ $client->date_of_birth ?? "" }}',
        '[DATE]'              : '{{ now()->format("jS F Y") }}'
    };

    Object.keys(replacements).forEach(function(key) {
        let regex = new RegExp(key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
        html = html.replace(regex, replacements[key]);
    });

    return html;
}

// Generate document
function generateDocument(format) {
    let htmlContent = $('#documentContent').html();
    let btnId = format === 'docx' ? '#generateDocxBtn' : '#generatePdfBtn';
    let icon = format === 'docx' ? 'fa-file-word' : 'fa-file-pdf';
    
    $(btnId).prop('disabled', true)
            .html(`<i class="fas fa-spinner fa-spin"></i> Generating...`);

    let formData = new FormData();
    formData.append('template_id', selectedTemplateId);
    formData.append('edited_html', htmlContent);
    formData.append('client_id', '{{ $client->id }}');
    formData.append('format', format);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "{{ route('client.initial.instruction.generate', $client->id) }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: { responseType: 'blob' },
        success: function(blob) {
            let ext = format === 'docx' ? '.docx' : '.pdf';
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'Initial_Instruction_{{ $client->first_name }}' + ext;
            link.click();

            $(btnId).prop('disabled', false)
                    .html(`<i class="fas ${icon}"></i> Generate ${format.toUpperCase()}`);
        },
        error: function(xhr) {
            alert('Error generating document');
            $(btnId).prop('disabled', false)
                    .html(`<i class="fas ${icon}"></i> Generate ${format.toUpperCase()}`);
        }
    });
}

// Reset editor
function resetEditor() {
    $('#editor-step').hide();
    $('#choice-step').show();
    $('#documentContent').empty().hide();
    $('#editorLoading').show();
    $('#findReplacePanel').hide();
    $('#templateSelect').val('');
    $('#loadTemplateBtn').prop('disabled', true);
    selectedTemplateId = null;
}
</script>

<style>
#documentContent {
    font-family: 'Calibri', Arial, sans-serif;
    font-size: 11pt;
    line-height: 1.6;
    color: #000;
    min-height: 400px;
}
#documentContent p { margin: 0 0 8px 0; }
#documentContent table { border-collapse: collapse; width: 100%; margin: 10px 0; }
#documentContent td, #documentContent th { border: 1px solid #ddd; padding: 6px; }
</style>
@endsection
