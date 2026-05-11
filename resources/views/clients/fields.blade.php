<div class="row">

<!-- First Name -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('first_name','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Last Name -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('sir_name', 'Surname:') !!}
    {!! Form::text('sir_name', null, ['class' => 'form-control']) !!}
    {!! $errors->first('sir_name','<span class="help-blocks">:message</span>') !!}
</div>

<!-- DOB -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('dob', 'Date of Birth:') !!}
    {!! Form::text('dob', null, [
        'class' => 'form-control datepicker',
        'placeholder' => 'DD/MM/YYYY'
    ]) !!}
</div>

<!-- Gender -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::select('gender', [
        'Male' => 'Male',
        'Female' => 'Female',
        'Other' => 'Other'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Gender']) !!}
     {!! $errors->first('gender','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Nationality -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('country', 'Country:') !!}
    {!! Form::select('country', $countries, null, [
        'class' => 'form-control select2',
        'placeholder' => 'Select Country'
    ]) !!}
</div>

<!-- Address -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- City -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('city', 'City:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
     {!! $errors->first('phone','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Email -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
     {!! $errors->first('email','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Passport Number -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('passport_no', 'Passport Number:') !!}
    {!! Form::text('passport_no', null, ['class' => 'form-control']) !!}
</div>

<!-- Matter Type -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('visa_type', 'Visa Type:') !!}
    {!! Form::select('visa_type', [
        'Appeal' => 'Appeal',
        'Work Visa' => 'Work Visa',
        'Student Visa' => 'Student Visa',
        'Spouse Visa' => 'Spouse Visa',
        'Visitor Visa' => 'Visitor Visa',
        'Settlement Visa' => 'Settlement Visa'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Type']) !!}
    {!! $errors->first('visa_type','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Visa Issue Date -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('visa_issue_date', 'Visa Issue Date:') !!}
    {!! Form::text('visa_issue_date', null, [
        'class' => 'form-control datepicker',
        'placeholder' => 'DD/MM/YYYY'
    ]) !!}
</div>

<!-- Visa Expiry Date -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('visa_expiry_date', 'Visa Expiry Date:') !!}
    {!! Form::text('visa_expiry_date', null, [
        'class' => 'form-control datepicker',
        'placeholder' => 'DD/MM/YYYY'
    ]) !!}
</div>

<!-- Priority -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('priority', 'Priority:') !!}
    {!! Form::select('priority', [
        'Urgent' => 'Urgent',
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low'
    ], null, ['class' => 'form-control']) !!}
</div>

<!-- Status -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [
        'Active' => 'Active',
        'Closed' => 'Closed',
        'Pending' => 'Pending',
        'Archived' => 'Archived'
    ], null, ['class' => 'form-control']) !!}
</div>

<!-- Court Type -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}" id="court_type_div">
    {!! Form::label('court_type', 'Court Type:') !!}
    {!! Form::select('court_type', [
        'Magistrate' => 'Magistrate',
        'Crown' => 'Crown',
        'High Court' => 'High Court',
        'Tribunal' => 'Tribunal',
    ], null, ['class' => 'form-control']) !!}
</div>
<!-- Post Code -->
<div class="form-group col-sm-6 {{ $errors->has('type') ? 'has-error' :'' }}">
    {!! Form::label('post_code', 'Post Code:') !!}
    {!! Form::text('post_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit -->
<div class="form-group col-sm-12 mt-3">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

</div>