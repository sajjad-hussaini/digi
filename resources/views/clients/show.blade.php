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
                                    aria-expanded="false"></a>
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
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.26.24/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.26.24/dist/sweetalert2.min.css
" rel="stylesheet">

<script>
let selectedTemplateId = null;
let selectedTemplateTitle = '';
let currentMatterType = null;   // NEW
let currentTargetType = null;   // NEW

$(document).ready(function() {

    $('#initialInstructionModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const targetType = button.data('template-type'); // Get 'Initial Instruction' or others
        const matterType = button.data('matter-type'); // Get 'Initial Instruction' or others
        
        currentTargetType = targetType;
        currentMatterType = matterType;
        console.log(currentTargetType);
        // Reset UI
        resetEditor();

        $('#templateSelect').show();
        $('#templatesLoading').hide();
        $('#loadTemplateBtn').show();

        if (targetType) {
            // 1. Set the hidden/visible dropdown to the correct type
            $('#templates_load').val(targetType);
            
            // 2. Hide the "Templates Type" label and select 
            // so the user only sees "Existing Templates"
            $('label[for="templates_load"]').hide();
            $('#templates_load').hide();

            // 3. Automatically trigger the AJAX load for this type
            loadTemplatesByType(targetType, matterType);
        } else {
            // If opened without a specific type, show the selection dropdown
            $('label[for="templates_load"]').show();
            $('#templates_load').show();
        }
    });

    function loadTemplatesByType(type, matter_type) {
        $('#templatesLoading').show();
        $('#loadTemplateBtn').hide();

        $.ajax({
            url: "/admin/templates/list",
            type: 'GET',
            data: { type: type, matter_type: matter_type},   // ← yeh sabse important change
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
            // 3. IMMEDIATELY LOAD THE CONTENT
            loadTemplateContent(selectedTemplateId);
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

    $(document).on('click', '.format-command', function() {
        $('#documentContent').focus();
        document.execCommand($(this).data('command'), false, null);
    });

    $('#documentFont').change(function() {
        $('#documentContent').focus();
        document.execCommand('fontName', false, $(this).val());
    });

    $('#documentFontSize').change(function() {
        $('#documentContent').focus();
        document.execCommand('fontSize', false, $(this).val());
    });

    $('#documentTextColor').change(function() {
        $('#documentContent').focus();
        document.execCommand('foreColor', false, $(this).val());
    });

    $('#documentLineSpacing').change(function() {
        const selection = window.getSelection();
        let node = selection.rangeCount ? selection.getRangeAt(0).commonAncestorContainer : $('#documentContent')[0];
        node = node.nodeType === 3 ? node.parentElement : node;
        const block = $(node).closest('p, div, li, td, th, h1, h2, h3, h4, h5, h6', $('#documentContent')[0])[0] || $('#documentContent')[0];
        $(block).css('line-height', $(this).val());
    });

    let selectedDocumentImage = null;
    $(document).on('click', '#documentContent img', function(event) {
        event.stopPropagation();
        selectedDocumentImage = this;
        $('#documentContent img').removeClass('selected-document-image');
        $(this).addClass('selected-document-image');
    });

    $(document).on('click', '.image-command', function() {
        if (!selectedDocumentImage) {
            alert('Click a logo or image first.');
            return;
        }

        const position = $(this).data('position');
        selectedDocumentImage.style.display = 'block';
        selectedDocumentImage.style.marginLeft = position === 'right' ? 'auto' : '0';
        selectedDocumentImage.style.marginRight = position === 'left' ? 'auto' : '0';
        selectedDocumentImage.style.float = position === 'center' ? 'none' : position;
        selectedDocumentImage.parentElement.style.textAlign = position;
    });

    $(document).on('click', '.image-size-command', function() {
        if (!selectedDocumentImage) {
            alert('Click a logo or image first.');
            return;
        }

        const size = $(this).data('size') + 'px';
        selectedDocumentImage.style.width = size;
        selectedDocumentImage.style.maxWidth = size;
        selectedDocumentImage.removeAttribute('width');
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
        generateDocument('docx', currentTargetType);
    });

    // Generate PDF
    $('#generatePdfBtn').click(function() {
        generateDocument('pdf', currentTargetType);
    });
});

$(document).on('click', '#is_permanent', function () {
   
    // if (checkbox.is(':checked')) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to make this client permanent?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Make Permanent',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('clients.make-permanent', $client->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('.permanent_client_btn').html('<button class="btn btn-success btn-block" disabled>Permanent Client</button>');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        });
                    },
                    error: function () {
                        checkbox.prop('checked', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong'
                        });
                    }
                });
            } else {
                checkbox.prop('checked', false);
            }
        });
    // }
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

                    // Remove logos embedded in the uploaded template. Branding
                    // is added once by the generated header and footer below.
                    const templateContent = $('<div>').html(html);
                    templateContent.find('img').remove();
                    html = templateContent.html();
                    
                    // Auto replace client placeholders
                    html = autoReplaceClientData(html);
                    html = ensureDocumentHeader(html);
                    html = ensureDocumentFooter(html);
                    
                    $('#documentContent').html(html);
                    formatTemplateImages();
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

function formatTemplateImages() {
    $('#documentContent img').not('.document-header img, .document-footer img').each(function() {
        $(this).css({
            display: 'block',
            width: '85px',
            maxWidth: '85px',
            height: 'auto',
            marginLeft: '8px',
            marginRight: '0',
            float: 'right'
        });

        $(this).parent().css('text-align', 'left');
    });
}

function ensureDocumentHeader(html) {
    if (html.toLowerCase().indexOf('document-header') !== -1) {
        return html;
    }

    return `
        <table class="document-header" style="width:100%; margin:0 0 18px; border-collapse:collapse;">
            <tr><td style="text-align:right; border:0; padding:0;">
                <img src="{{ asset('images/logo_imigration_law.png') }}" alt="UK Immigration Law" style="width:159px; max-width:159px; height:auto;">
            </td></tr>
        </table>` + html;
}

// Auto replace client placeholders
function autoReplaceClientData(html) {
    let replacements = {
        '[CLIENT_FIRST_NAME]'       : '{{ $client->first_name }}',
        '[CLIENT_SURNAME]'      : '{{ $client->sir_name }}',
        '[CLIENT_GENDER]'      : '{{ $client->gender ?? "" }}',
        '[CLIENT_PASSPORT_NO]'      : '{{ $client->passport_no ?? "" }}',
        '[CITY]'              : '{{ $client->city ?? "" }}',
        '[CLIENT_EMAIL]'      : '{{ $client->email ?? "" }}',
        '[CLIENT_PHONE]'      : '{{ $client->phone ?? "" }}',
        '[CLIENT_DOB]'      : '{{ $client->dob ?? "" }}',
        '[DATE]'              : '{{ now()->format("jS F Y") }}',
        '[ADDRESS_1]'      : '{{ $client->address1 ?? "" }}',
        '[ADDRESS_2]'      : '{{ $client->color ?? "" }}',
        '[NATIONALITY]'      : '{{ $client->country ?? "" }}',
        '[COUNTRY]'      : '{{ $client->national ?? "" }}',
        '[REFERENCE_NUMBER]' : '{{ $client->ref_number ?? "" }}',
        '[SALUTATION]' : '{{ strtolower((string) ($client->gender ?? "")) === "female" ? "Mrs" : (strtolower((string) ($client->gender ?? "")) === "male" ? "Mr" : "") }}',
        
    };

    Object.keys(replacements).forEach(function(key) {
        let regex = new RegExp(key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
        html = html.replace(regex, replacements[key]);
    });

    return html;
}

function ensureDocumentFooter(html) {
    if (html.toLowerCase().indexOf('qureshisalim@yahoo.com') !== -1) {
        return html;
    }

    return html + `
        <div class="document-footer">
            <div class="document-footer-rules"></div>
            <div class="document-footer-firm">UK Immigration Law</div>
            <div class="document-footer-address">1st floor, 236 ST. Helens Road, Bolton BL3 4EB, Ph. 07777328028, Email: qureshisalim@yahoo.com</div>
            <div class="document-footer-logo">
                <img src="{{ asset('images/footer.jpg') }}" alt="Immigration Advice Authority">
            </div>
        </div>`;
}

// Generate document
function generateDocument(format, currentTargetType) {
    let htmlContent = $('#documentContent').html();
    let btnId = format === 'docx' ? '#generateDocxBtn' : '#generatePdfBtn';
    let icon = format === 'docx' ? 'fa-file-word' : 'fa-file-pdf';
    
    $(btnId).prop('disabled', true)
            .html(`<i class="fa fa-spinner fa-spin"></i> Generating...`);

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
            // link.download = 'Initial_Instruction_{{ $client->first_name }}' + ext;
             // Download filename
            link.download = `${currentTargetType}_${'{{ $client->first_name }}'}${ext}`;
            link.click();

            $(btnId).prop('disabled', false)
                    .html(`<i class="fa ${icon}"></i> Generate ${format.toUpperCase()}`);
        },
        error: function(xhr) {
            if (xhr.response instanceof Blob) {
                const reader = new FileReader();
                reader.onload = function() {
                    let message = 'Error generating document';
                    try {
                        const response = JSON.parse(reader.result);
                        message = response.error || message;
                    } catch (error) {
                        // Keep the generic message when the server response is not JSON.
                    }
                    alert(message);
                };
                reader.readAsText(xhr.response);
            } else {
                alert(xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Error generating document');
            }
            $(btnId).prop('disabled', false)
                    .html(`<i class="fa ${icon}"></i> Generate ${format.toUpperCase()}`);
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
    line-height: 1.15;
    color: #000;
    min-height: 250mm;
    width: 210mm;
    max-width: 100%;
    box-sizing: border-box;
    padding: 18mm 18mm 24mm;
    margin: 0 auto;
    background: #fff;
    overflow-wrap: break-word;
    position: relative;
}
#documentContent h1,
#documentContent h2,
#documentContent h3,
#documentContent h4,
#documentContent h5,
#documentContent h6,
#documentContent p {
    font-family: Arial, sans-serif;
    font-size: 11pt;
    line-height: 1.15;
    margin: 0 0 8px;
}
#documentContent h1,
#documentContent h2,
#documentContent h3,
#documentContent h4,
#documentContent h5,
#documentContent h6 {
    font-weight: bold;
}
#documentContent img { max-width: 100%; height: auto; }
#documentContent .document-footer {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    width: auto !important;
    margin: 0;
    background: #fff;
    z-index: 10;
    text-align: center;
}
#documentContent .document-footer-rules {
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
    height: 1px;
    margin: 0 0 2px;
}
#documentContent .document-footer-firm {
    font-family: 'Times New Roman', Times, serif;
    font-size: 14px;
    font-weight: bold;
    line-height: 14px;
}
#documentContent .document-footer-address {
    margin-top: 1px;
    font-family: Arial, sans-serif;
    font-size: 12px;
    line-height: 14px;
    white-space: nowrap;
}
#documentContent .document-footer-logo {
    position: absolute;
    top: -28px;
    right: 0;
    width: 70px;
    text-align: right;
}
#documentContent .document-footer-logo img {
    width: 69.6px;
    height: 58px;
    display: block;
}
#documentContent .document-header,
#documentContent .document-footer { width: 100% !important; }
#documentContent .document-header td,
#documentContent .document-footer td { border: 0; }
.document-toolbar { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; padding: 8px; background: #f5f6f8; border: 1px solid #ddd; }
.document-toolbar-select { width: auto; min-width: 120px; }
.document-color { width: 34px; height: 30px; padding: 2px; border: 1px solid #ccc; }
.selected-document-image { outline: 2px solid #337ab7; outline-offset: 3px; }
.document-modal-dialog { width: calc(100vw - 30px); max-width: 1400px; margin: 15px auto; }
.document-modal-dialog .modal-content { max-height: calc(100vh - 30px); display: flex; flex-direction: column; }
.document-modal-dialog .modal-header { flex: 0 0 auto; }
.document-modal-dialog .modal-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; }
.document-editor { background: #e9ecef !important; padding: 18px; overflow: visible !important; }
.document-editor #documentContent { flex: 0 0 210mm; }
@media (min-width: 992px) {
    .document-modal-dialog { width: calc(100vw - 60px); }
}
@media (max-width: 768px) {
    .document-modal-dialog { width: calc(100vw - 16px); margin: 8px auto; }
    #documentContent { width: 100%; padding: 12mm 8mm; }
    .document-editor { padding: 8px; }
}
@media print {
    @page { size: A4 portrait; margin: 0; }
    #documentContent { width: 210mm; min-height: 297mm; padding: 18mm; }
}

/* Modern Gradient Header */
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

/* Modal Styling */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

/* Icon Circle for Step 1 */
.icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.bg-soft-primary { background-color: rgba(78, 115, 223, 0.1); }

/* The "Paper" Effect */
.paper-container {
    min-height: 500px;
    max-height: 650px;
    overflow-y: auto;
    border-radius: 4px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.paper-content {
    padding: 50px 60px; /* Real letter margins */
    line-height: 1.6;
    font-family: 'Georgia', serif; /* Classic document font */
    color: #2e2e2e;
    outline: none !important;
}

/* Custom Scrollbar for the Editor */
.paper-container::-webkit-scrollbar {
    width: 8px;
}
.paper-container::-webkit-scrollbar-track {
    background: #f8f9fc;
}
.paper-container::-webkit-scrollbar-thumb {
    background: #d1d3e2;
    border-radius: 10px;
}

/* Highlight border on focus */
#documentContent:focus {
    background-color: #fdfdfd;
}

/* Simple Animations */
.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Form refinement */
.border-primary-soft {
    border: 2px solid #eaecf4;
    border-radius: 8px;
}
.border-primary-soft:focus {
    border-color: #bac8f3;
    box-shadow: none;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
#is_permanent:checked ~ label {
    color: #28a745;
}
</style>
@endsection
