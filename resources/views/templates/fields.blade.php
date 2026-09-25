<div class="col-sm-12">
    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="alert alert-info">
        Download the current DOCX, edit its wording or keys in Microsoft Word, then upload the revised file.
        Each template keeps its own page size, fonts, logos, headers and footers.
        Saving title or category changes keeps the document unchanged.
    </div>
    <a class="btn btn-default mb-3" href="{{ route('templates.download', $template->id) }}">Download Current DOCX</a>
    <p>If an older online edit changed the layout, upload your original DOCX again.</p>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'required' => true]) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Template Type:') !!}
    {!! Form::select('type', array_combine($types = ['Authority Letter', 'Initial Instruction', 'Client Care', 'Client Closure Letter', 'Covering Letter'], $types), null, ['class' => 'form-control', 'required' => true]) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('matter_type', 'Visa Type:') !!}
    {!! Form::select('matter_type', array_combine($matters = ['Appeal', 'Work Visa', 'Student Visa', 'Spouse Visa', 'Visitor Visa', 'Settlement Visa'], $matters), null, ['class' => 'form-control', 'required' => true]) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('doc_file', 'Replace Document (optional):') !!}
    {!! Form::file('doc_file', ['class' => 'form-control', 'accept' => '.docx']) !!}
    <small>Upload an original Word DOCX with client keys. Maximum 10 MB.</small>
</div>
<div class="col-sm-12">
    <details class="mb-3"><summary>Supported client keys</summary>
        <p>Insert these keys in Word wherever the corresponding client value should appear. Other text stays as written.</p>
        <p><code>[CLIENT_FIRST_NAME]</code> <code>[CLIENT_SURNAME]</code> <code>[SALUTATION]</code>
        <code>[REFERENCE_NUMBER]</code> <code>[ADDRESS_1]</code> <code>[ADDRESS_2]</code>
        <code>[CITY]</code> <code>[CLIENT_EMAIL]</code> <code>[CLIENT_PHONE]</code>
        <code>[CLIENT_DOB]</code> <code>[CLIENT_GENDER]</code> <code>[CLIENT_PASSPORT_NO]</code>
        <code>[NATIONALITY]</code> <code>[COUNTRY]</code> <code>[DATE]</code></p>
    </details>
    <button type="submit" class="btn btn-success">Save Changes</button>
    <a href="{{ route('templates.index') }}" class="btn btn-default">Cancel</a>
</div>