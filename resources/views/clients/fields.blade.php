<div class="row">

<!-- First Name -->
<div class="form-group col-sm-6 {{ $errors->has('first_name') ? 'has-error' :'' }}">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => true, 'minlength' => 2, 'maxlength' => 100, 'autocomplete' => 'given-name']) !!}
    {!! $errors->first('first_name','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Last Name -->
<div class="form-group col-sm-6 {{ $errors->has('sir_name') ? 'has-error' :'' }}">
    {!! Form::label('sir_name', 'Surname:') !!}
    {!! Form::text('sir_name', null, ['class' => 'form-control', 'required' => true, 'minlength' => 2, 'maxlength' => 100, 'autocomplete' => 'family-name']) !!}
    {!! $errors->first('sir_name','<span class="help-blocks">:message</span>') !!}
</div>

<!-- DOB -->
<div class="form-group col-sm-6 {{ $errors->has('dob') ? 'has-error' :'' }}">
    {!! Form::label('dob', 'Date of Birth:') !!}
    {!! Form::text('dob', null, [
        'class' => 'form-control datepicker',
        'placeholder' => 'DD/MM/YYYY', 'required' => true
    ]) !!}
</div>

<!-- Gender -->
<div class="form-group col-sm-6 {{ $errors->has('gender') ? 'has-error' :'' }}">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::select('gender', [
        'Male' => 'Male',
        'Female' => 'Female',
        'Other' => 'Other'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Gender', 'required' => true]) !!}
     {!! $errors->first('gender','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Post Code -->
<div class="form-group col-sm-6 {{ $errors->has('post_code') ? 'has-error' :'' }}">
    {!! Form::label('post_code', 'Post Code:') !!}
    {!! Form::text('post_code', null, ['class' => 'form-control', 'maxlength' => 20, 'autocomplete' => 'postal-code']) !!}
</div>


<!-- Nationality -->
<div class="form-group col-sm-6 {{ $errors->has('country') ? 'has-error' :'' }}">
    {!! Form::label('country', 'Country:') !!}
    {!! Form::select('country', $countries, null, [
        'class' => 'form-control select2',
        'placeholder' => 'Select Country', 'required' => true
    ]) !!}
</div>

<!-- Address -->
<div class="form-group col-sm-6 {{ $errors->has('address') ? 'has-error' :'' }}">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control', 'maxlength' => 255, 'autocomplete' => 'street-address']) !!}
</div>

<!-- City -->
<div class="form-group col-sm-6 {{ $errors->has('city') ? 'has-error' :'' }}">
    {!! Form::label('city', 'City:') !!}
    {!! Form::text('city', null, ['class' => 'form-control', 'maxlength' => 100, 'autocomplete' => 'address-level2']) !!}
</div>

<!-- Phone -->
<div class="form-group col-sm-6 {{ $errors->has('phone') ? 'has-error' :'' }}">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control', 'required' => true, 'maxlength' => 25, 'pattern' => '[0-9+()\\-\\s]{7,25}', 'autocomplete' => 'tel']) !!}
     {!! $errors->first('phone','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Email -->
<div class="form-group col-sm-6 {{ $errors->has('email') ? 'has-error' :'' }}">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required' => true, 'maxlength' => 255, 'autocomplete' => 'email']) !!}
     {!! $errors->first('email','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Passport Number -->
<div class="form-group col-sm-6 {{ $errors->has('passport_no') ? 'has-error' :'' }}">
    {!! Form::label('passport_no', 'Passport Number:') !!}
    {!! Form::text('passport_no', null, ['class' => 'form-control', 'maxlength' => 50, 'pattern' => '[A-Za-z0-9\\-/]+']) !!}
</div>

<!-- Matter Type -->
<div class="form-group col-sm-6 {{ $errors->has('visa_type') ? 'has-error' :'' }}">
    {!! Form::label('visa_type', 'Visa Type:') !!}
    {!! Form::select('visa_type', [
        'Appeal' => 'Appeal',
        'Work Visa' => 'Work Visa',
        'Student Visa' => 'Student Visa',
        'Spouse Visa' => 'Spouse Visa',
        'Visitor Visa' => 'Visitor Visa',
        'Settlement Visa' => 'Settlement Visa'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Type', 'required' => true]) !!}
    {!! $errors->first('visa_type','<span class="help-blocks">:message</span>') !!}
</div>

<!-- Visa Issue Date -->
<div class="form-group col-sm-6 {{ $errors->has('visa_issue_date') ? 'has-error' :'' }}">
    {!! Form::label('visa_issue_date', 'Visa Issue Date:') !!}
    {!! Form::text('visa_issue_date', null, [
        'class' => 'form-control datepicker',
        'placeholder' => 'DD/MM/YYYY', 'required' => true
    ]) !!}
</div>

<!-- Visa Expiry Date -->
<div class="form-group col-sm-6 {{ $errors->has('visa_expiry_date') ? 'has-error' :'' }}">
    {!! Form::label('visa_expiry_date', 'Visa Expiry Date:') !!}
    {!! Form::text('visa_expiry_date', null, [
        'class' => 'form-control datepicker',
        'placeholder' => 'DD/MM/YYYY', 'required' => true
    ]) !!}
</div>

<!-- Priority -->
<div class="form-group col-sm-6 {{ $errors->has('priority') ? 'has-error' :'' }}">
    {!! Form::label('priority', 'Priority:') !!}
    {!! Form::select('priority', [
        'Urgent' => 'Urgent',
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low'
    ], null, ['class' => 'form-control', 'required' => true]) !!}
</div>

<!-- Status -->
<div class="form-group col-sm-6 {{ $errors->has('status') ? 'has-error' :'' }}">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [
        'Active' => 'Active',
        'Closed' => 'Closed',
        'Pending' => 'Pending',
        'Archived' => 'Archived'
    ], null, ['class' => 'form-control', 'required' => true]) !!}
</div>

<!-- Court Type -->
<div class="form-group col-sm-6 {{ $errors->has('court_type') ? 'has-error' :'' }}" id="court_type_div">
    {!! Form::label('court_type', 'Court Type:') !!}
    {!! Form::select('court_type', [
        'Magistrate' => 'Magistrate',
        'Crown' => 'Crown',
        'High Court' => 'High Court',
        'Tribunal' => 'Tribunal',
    ], null, ['class' => 'form-control']) !!}
</div>

<!-- Submit -->
<div class="form-group col-sm-12 mt-3">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>

</div>
