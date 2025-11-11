<!-- Company Name Field -->
<div class="form-group col-sm-6 {{ $errors->has('company_name') ? 'has-error' :'' }}">
    {!! Form::label('company_name', 'Company Name:') !!}
    {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
</div>

<!-- Company Address Field -->
<div class="form-group col-sm-6 {{ $errors->has('company_address') ? 'has-error' :'' }}">
    {!! Form::label('company_address', 'Company Address:') !!}
    {!! Form::text('company_address', null, ['class' => 'form-control']) !!}
    {!! $errors->first('company_address','<span class="help-block">:message</span>') !!}
</div>

<!-- Contact Number Field -->
<div class="form-group col-sm-6 {{ $errors->has('contact_number') ? 'has-error' :'' }}">
    {!! Form::label('contact_number', 'Contact Number:') !!}
    {!! Form::text('contact_number', null, ['class' => 'form-control']) !!}
    {!! $errors->first('contact_number','<span class="help-block">:message</span>') !!}
</div>

<!-- Email Address Field -->
<div class="form-group col-sm-6 {{ $errors->has('email_address') ? 'has-error' :'' }}">
    {!! Form::label('email_address', 'Email Address:') !!}
    {!! Form::email('email_address', null, ['class' => 'form-control']) !!}
    {!! $errors->first('email_address','<span class="help-block">:message</span>') !!}
</div>

<!-- Solicitor Name Field -->
<div class="form-group col-sm-6 {{ $errors->has('solicitor_name') ? 'has-error' :'' }}">
    {!! Form::label('solicitor_name', 'Solicitor Name:') !!}
    {!! Form::text('solicitor_name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('solicitor_name','<span class="help-block">:message</span>') !!}
</div>

<!-- Regulated By Field -->
<div class="form-group col-sm-6 {{ $errors->has('regulated_by') ? 'has-error' :'' }}">
    {!! Form::label('regulated_by', 'Regulated By:') !!}
    {!! Form::text('regulated_by', null, ['class' => 'form-control']) !!}
    {!! $errors->first('regulated_by','<span class="help-block">:message</span>') !!}
</div>

<!-- Company Registration Number Field -->
<div class="form-group col-sm-6 {{ $errors->has('company_reg_number') ? 'has-error' :'' }}">
    {!! Form::label('company_reg_number', 'Company Registration Number:') !!}
    {!! Form::text('company_reg_number', null, ['class' => 'form-control']) !!}
    {!! $errors->first('company_reg_number','<span class="help-block">:message</span>') !!}
</div>

<!-- Company Logo Field -->
<div class="form-group col-sm-6 {{ $errors->has('company_logo') ? 'has-error' :'' }}">
    {!! Form::label('company_logo', 'Company Logo:') !!}
    {!! Form::file('company_logo', ['class' => 'form-control']) !!}
    {!! $errors->first('company_logo','<span class="help-block">:message</span>') !!}
</div>

<!-- Accreditor Logos Field -->
<div class="form-group col-sm-6 {{ $errors->has('accreditor_logos') ? 'has-error' :'' }}">
    {!! Form::label('accreditor_logos', 'Accreditor Logos:') !!}
    {!! Form::file('accreditor_logos[]', ['class' => 'form-control', 'multiple' => true]) !!}
    {!! $errors->first('accreditor_logos','<span class="help-block">:message</span>') !!}
</div>

{{-- additional custom fields --}}
@foreach ($customFields as $customField)
    <div class="form-group col-sm-6 {{ $errors->has("custom_fields.$customField->name") ? 'has-error' :'' }}">
        {!! Form::label("custom_fields[$customField->name]", Str::title(str_replace('_',' ',$customField->name)).":") !!}
        {!! Form::text("custom_fields[$customField->name]", null, [
            'class' => 'form-control typeahead',
            'data-source' => json_encode($customField->suggestions),
            'autocomplete' => is_array($customField->suggestions) ? 'off' : 'on'
        ]) !!}
        {!! $errors->first("custom_fields.$customField->name",'<span class="help-block">:message</span>') !!}
    </div>
@endforeach
{{-- end custom fields --}}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.index') !!}" class="btn btn-default">Cancel</a>
</div>
