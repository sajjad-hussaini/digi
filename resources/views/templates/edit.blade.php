@extends('layouts.app')
@section('title', 'Edit ' . ucfirst(config('settings.templates_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ ucfirst(config('settings.templates_label_singular')) }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($template, [
                        'route' => ['templates.update', $template->id],
                        'method' => 'patch',
                        'files' => true,
                    ]) !!}

                    @include('templates.fields')

                    {!! Form::close() !!}
                </div>
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
        $(document).ready(function() {
            // Load template content on page load
            loadTemplateContent();

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


            // Switch 'change' to 'input'
            $('#documentContent').on('input', function() {
                let htmlContent = $(this).html();
                $('#editedHtml').val(htmlContent);
            });

        });

        // Load template content from DB
        function loadTemplateContent() {
            $('#editorLoading').show();
            $('#documentContent').hide();

            $.ajax({
                url: "{{ route('templates.content', $template->id) }}",
                type: 'GET',
                success: function(response) {
                    // Base64 to ArrayBuffer
                    let binaryStr = atob(response.content);
                    let bytes = new Uint8Array(binaryStr.length);

                    for (let i = 0; i < binaryStr.length; i++) {
                        bytes[i] = binaryStr.charCodeAt(i);
                    }

                    // Mammoth: DOCX to HTML
                    mammoth.convertToHtml({
                            arrayBuffer: bytes.buffer
                        })
                        .then(function(result) {
                            $('#documentContent').html(result.value);
                            $('#editorLoading').hide();
                            $('#documentContent').show();
                            
                        })
                        .catch(function(err) {
                            console.error('Error:', err);
                            $('#editorLoading').html(
                                '<span class="text-danger">Error loading document</span>');
                        });
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    $('#editorLoading').html('<span class="text-danger">Error loading template</span>');
                }
            });
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

        #documentContent p {
            margin: 0 0 8px 0;
        }

        #documentContent table {
            border-collapse: collapse;
            width: 100%;
            margin: 10px 0;
        }

        #documentContent td,
        #documentContent th {
            border: 1px solid #ddd;
            padding: 6px;
        }
        
    </style>
@endsection
