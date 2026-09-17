

<!-- title -->
<div class="form-group col-sm-6 {{ $errors->has('title') ? 'has-error' : '' }}">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', $title ?? null, [
        'class' => 'form-control',
        'placeholder' => 'Title',
        'required' => true
    ]) !!}
    {!! $errors->first('title','<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
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

<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}" >
    {!! Form::label('visa_type', 'Visa Type:') !!}
    {!! Form::select('visa_type', [
        'Appeal' => 'Appeal',
        'Work Visa' => 'Work Visa',
        'Student Visa' => 'Student Visa',
        'Spouse Visa' => 'Spouse Visa',
        'Visitor Visa' => 'Visitor Visa',
        'Settlement Visa' => 'Settlement Visa'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Type']) !!}
    {!! $errors->first('visa_type','<span class="help-block">:message</span>') !!}
</div>

<!-- doc file -->
<div class="form-group col-sm-12 {{ $errors->has('doc_file') ? 'has-error' : '' }}">
    <small class="form-text text-muted mb-2">Use <strong>[REFERENCE_NUMBER]</strong> for the client's permanent reference number or <strong>[SALUTATION]</strong> for Mr/Mrs based on gender.</small>
    {!! Form::label('doc_file', 'Upload Document:') !!}
    {!! Form::file('doc_file', [
        'class' => 'form-control',
        'accept' => '.docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'required' => true,
    ]) !!}
    {!! $errors->first('doc_file','<span class="help-block">:message</span>') !!}
</div>

<!-- Submit -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('templates.index') }}" class="btn btn-default">Cancel</a>
</div>
