<div class="col-sm-12">
    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="alert alert-info">
        Replace wording below, or download the current DOCX, edit it in Microsoft Word, and upload the revised file.
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
    <h3>Text replacements</h3>
    <p>Enter your text or key and click Change to see the updated preview. Continue with the next replacement, then save all changes once. Leave Replace with empty to remove text.</p>
    <p class="text-muted">Matches are exact and case-sensitive. All rows match the original document; new replacement text is not replaced again. For overlapping matches, the first matching row takes priority.</p>
    <div id="template-replacements">
        @foreach (old('replacements', [['find' => '', 'replace' => '']]) as $index => $replacement)
            <div class="row replacement-row" style="margin-bottom:12px">
                <div class="col-sm-5">
                    <label for="find-{{ $index }}">Find text / key</label>
                    <input id="find-{{ $index }}" name="replacements[{{ $index }}][find]" value="{{ $replacement['find'] ?? '' }}" class="form-control replacement-find" maxlength="2000">
                </div>
                <div class="col-sm-5">
                    <label for="replace-{{ $index }}">Replace with</label>
                    <input id="replace-{{ $index }}" name="replacements[{{ $index }}][replace]" value="{{ $replacement['replace'] ?? '' }}" class="form-control replacement-value" maxlength="10000">
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-default replacement-remove" style="margin-top:25px">Remove row</button>
                </div>
                <div class="col-sm-12"><small class="replacement-status"></small></div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-primary" id="template-add-replacement">Change</button>
    <p style="margin-top:12px">All replacements run when you save. If you upload a DOCX, they apply to that file. Longer text may change page breaks.</p>
</div>
<div class="col-sm-12">
    @include('templates.client_keys')
    <button type="submit" class="btn btn-success">Save Changes</button>
    <a href="{{ route('templates.index') }}" class="btn btn-default">Cancel</a>
</div>