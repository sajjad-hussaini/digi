<!-- resources/views/clients/fields.blade.php -->

<!-- Company Field -->
<div class="form-group col-sm-6 {{ $errors->has('company_id') ? 'has-error' :'' }}">
    {!! Form::label('company_id', 'Company:') !!}
    {!! Form::select('company_id', $companies->pluck('company_name', 'id'), null, [
        'class' => 'form-control',
        'placeholder' => 'Select Company'
    ]) !!}
    {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}
</div>

<!-- Color Field -->
<div class="form-group col-sm-6 {{ $errors->has('color') ? 'has-error' :'' }}">
    {!! Form::label('color', 'Color:') !!}
    {!! Form::color('color', null, ['class' => 'form-control']) !!}
    {!! $errors->first('color','<span class="help-block">:message</span>') !!}
</div>

<!-- Client Name Field -->
<div class="form-group col-sm-6 {{ $errors->has('name') ? 'has-error' :'' }}">
    {!! Form::label('name', 'Full Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
</div>

<!-- Contact Number Field -->
<div class="form-group col-sm-6 {{ $errors->has('phone') ? 'has-error' :'' }}">
    {!! Form::label('phone', 'Phone Number:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
</div>

<!-- Email Address Field -->
<div class="form-group col-sm-6 {{ $errors->has('email_address') ? 'has-error' :'' }}">
    {!! Form::label('email_address', 'Email Address:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
    {!! $errors->first('email_address','<span class="help-block">:message</span>') !!}
</div>

<!-- Visa Type Field -->
<div class="form-group col-sm-6 {{ $errors->has('visa_type') ? 'has-error' :'' }}">
    {!! Form::label('visa_type', 'Visa Type:') !!}
    {!! Form::select('visa_type', [
        'Work Visa' => 'Work Visa',
        'Student Visa' => 'Student Visa',
        'Spouse Visa' => 'Spouse Visa',
        'Visitor Visa' => 'Visitor Visa',
        'Settlement Visa' => 'Settlement Visa'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Visa Type']) !!}
    {!! $errors->first('visa_type','<span class="help-block">:message</span>') !!}
</div>

<!-- Visa Expiry Date Field -->
<div class="form-group col-sm-6 {{ $errors->has('visa_expiry_date') ? 'has-error' :'' }}">
    {!! Form::label('visa_expiry_date', 'Visa Expiry Date:') !!}
    {!! Form::date('visa_expiry_date', null, ['class' => 'form-control']) !!}
    {!! $errors->first('visa_expiry_date','<span class="help-block">:message</span>') !!}
</div>

<!-- Passport Number Field -->
<div class="form-group col-sm-6 {{ $errors->has('passport_number') ? 'has-error' :'' }}">
    {!! Form::label('passport_number', 'Passport Number:') !!}
    {!! Form::text('passport_no', null, ['class' => 'form-control']) !!}
    {!! $errors->first('passport_number','<span class="help-block">:message</span>') !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6 {{ $errors->has('status') ? 'has-error' :'' }}">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [
        'Active' => 'Active',
        'Closed' => 'Closed',
        'Pending' => 'Pending',
        'Archived' => 'Archived'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Status']) !!}
    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
</div>

<!-- priority Field -->
<div class="form-group col-sm-6 {{ $errors->has('priority') ? 'has-error' :'' }}">
    {!! Form::label('priority', 'Priority:') !!}
    {!! Form::select('priority', [
        'Urgent' => 'Urgent',
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select priority']) !!}
    {!! $errors->first('priority','<span class="help-block">:message</span>') !!}
</div>

<!-- court_type Field -->
<div class="form-group col-sm-6 {{ $errors->has('court_type') ? 'has-error' :'' }}">
    {!! Form::label('court_type', 'Court Type:') !!}
    {!! Form::select('court_type', [
        'Megistrate' => 'Megistrate',
        'Crown' => 'Crown',
        'High court' => 'High court',
        'Tribunal' => 'Tribunal',
    ], null, ['class' => 'form-control', 'placeholder' => 'Select court_type']) !!}
    {!! $errors->first('court_type','<span class="help-block">:message</span>') !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('clients.index') !!}" class="btn btn-default">Cancel</a>
</div>
