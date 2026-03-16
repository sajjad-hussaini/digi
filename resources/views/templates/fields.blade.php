<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Template: {{ $template->title }}</h4>
                </div>
                <p>[CLIENT_NAME] => For Client Name</p>
                <p>[CLIENT_EMAIL] => For Client Email</p>
                <p>[CLIENT_PHONE] => For Client Phone</p>
                <p>[CLIENT_ADDRESS] => For Client Address</p>
                <p>[CLIENT_DOB] => For Client Date of Birth</p>
                <p>[DATE] => For Current Date</p>
                <div class="card-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#editOnline">
                                <i class="fa fa-edit"></i> Edit Online
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#uploadNew">
                                <i class="fa fa-upload"></i> Upload New File
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Edit Online Tab -->
                        <div id="editOnline" class="tab-pane fade show active">
                            {!! Form::model($template, [
                                'route' => ['templates.update', $template->id],
                                'method' => 'PUT',
                                'id' => 'editOnlineForm',
                            ]) !!}

                            <!-- Title & Find Replace Row -->
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        {!! Form::label('title', 'Title:') !!}
                                        {!! Form::text('title', null, [
                                            'class' => 'form-control',
                                            'required' => true,
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        {!! Form::label('type', 'Template Type:') !!}
                                        {!! Form::select('type', [
                                            'Authority Letter' => 'Authority Letter',
                                            'Initial Instruction' => 'Initial Instruction',
                                            'Client Care' => 'Client Care',
                                            'Client Closure Letter' => 'Client Closure Letter',
                                            'Covering Letter' => 'Covering Letter',
                                        ], null, ['class' => 'form-control', 'placeholder' => 'Select Template Type']) !!}
                                        {!! $errors->first('type','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6 text-right d-flex align-items-end">
                                    <button type="button" class="btn btn-info btn-sm ml-auto" id="findReplaceBtn">
                                        <i class="fa fa-search"></i> Find & Replace
                                    </button>
                                </div>
                            </div>

                            <!-- Find & Replace Panel -->
                            <div id="findReplacePanel" class="card mb-2" style="display:none;">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="mb-1 small">Find:</label>
                                            <input type="text" id="findText" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="mb-1 small">Replace with:</label>
                                            <input type="text" id="replaceText" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary btn-sm btn-block"
                                                id="replaceAllBtn">
                                                Replace All
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Editor -->
                            <div class="border bg-white p-3 mb-2"
                                style="min-height: 500px; max-height: 600px; overflow-y: auto;">
                                <!-- Loading -->
                                <div id="editorLoading" class="text-center py-5">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Loading document...</p>
                                </div>

                                <!-- Editable Content -->
                                <div id="documentContent" contenteditable="true" style="outline: none; display:none;">
                                    <!-- Content will load here -->
                                </div>
                            </div>

                            <!-- Hidden field for edited HTML -->
                            <input type="hidden" name="edited_html" id="editedHtml">

                            <!-- Submit Buttons -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-success" id="saveBtn">
                                    <i class="fa fa-save"></i> Save Changes
                                </button>
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                            {!! Form::close() !!}
                        </div>

                        <!-- Upload New File Tab -->
                        <div id="uploadNew" class="tab-pane fade">
                            {!! Form::model($template, [
                                'route' => ['templates.update', $template->id],
                                'method' => 'PUT',
                                'files' => true,
                            ]) !!}

                            <!-- Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('title', 'Title:') !!}
                                    {!! Form::text('title', null, [
                                        'class' => 'form-control',
                                        'required' => true,
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    {!! Form::label('type', 'Template Type:') !!}
                                    {!! Form::select('type', [
                                        'Authority Letter' => 'Authority Letter',
                                        'Initial Instruction' => 'Initial Instruction',
                                        'Client Care' => 'Client Care',
                                        'Client Closure Letter' => 'Client Closure Letter',
                                        'Covering Letter' => 'Covering Letter',
                                    ], null, ['class' => 'form-control', 'placeholder' => 'Select Template Type']) !!}
                                    {!! $errors->first('type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <!-- Upload File -->
                            <div class="form-group">
                                {!! Form::label('doc_file', 'Upload New Document:') !!}
                                {!! Form::file('doc_file', [
                                    'class' => 'form-control',
                                    'accept' => '.docx',
                                ]) !!}
                                <small class="form-text text-muted">Upload a new .docx file to replace the current
                                    template</small>
                            </div>

                            <!-- Submit -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-upload"></i> Upload & Save
                                </button>
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
