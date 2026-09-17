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

            $('#documentContent').on('input', function() {
                $('#editedHtml').val($(this).html());
            });

            $('#editOnlineForm').on('submit', function() {
                $('#editedHtml').val($('#documentContent').html());
            });

        function loadTemplateContent() {
            $('#editorLoading').show();
            $('#documentContent').hide();

            $.ajax({
                url: "{{ route('templates.content', $template->id) }}",
                type: 'GET',
                success: function(response) {
                    const binaryString = atob(response.content);
                    const bytes = new Uint8Array(binaryString.length);

                    for (let index = 0; index < binaryString.length; index++) {
                        bytes[index] = binaryString.charCodeAt(index);
                    }

                    mammoth.convertToHtml({ arrayBuffer: bytes.buffer })
                        .then(function(result) {
                            $('#documentContent').html(result.value);
                            $('#editedHtml').val(result.value);
                            $('#editorLoading').hide();
                            $('#documentContent').show();
                            $('#documentFooterPreview').show();
                        })
                        .catch(function(error) {
                            console.error('Mammoth error:', error);
                            $('#editorLoading').html(
                                '<span class="text-danger">Error loading document</span>'
                            );
                        });
                },
                error: function(xhr) {
                    console.error('Template content error:', xhr);
                    $('#editorLoading').html(
                        '<span class="text-danger">Error loading template</span>'
                    );
                }
            });
        }

        });
    </script>

    <style>
        #documentContent {
            color: #000;
            min-height: 400px;
        }

        #documentContent h1,
        #documentContent h2,
        #documentContent h3,
        #documentContent h4,
        #documentContent h5,
        #documentContent h6 {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.15;
            margin: 0 0 8px;
            font-weight: bold;
        }

        #documentContent p {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.15;
            margin: 0 0 8px;
        }
        #documentFooterPreview {
            margin-top: 24px;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }

        .document-footer-line {
            border-top: 1px solid #999;
            margin-bottom: 6px;
        }

        .document-footer-content {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            text-align: center;
        }

        .document-footer-content > div {
            flex: 1;
        }

        .document-footer-content img {
            width: 55px;
            height: auto;
            margin-left: 12px;
        }
    </style>
@endsection
