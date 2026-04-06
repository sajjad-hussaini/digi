<div class="row">

<!-- First Name -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name -->
<div class="form-group col-sm-6">
    {!! Form::label('sir_name', 'Surname:') !!}
    {!! Form::text('sir_name', null, ['class' => 'form-control']) !!}
</div>

<!-- DOB -->
<div class="form-group col-sm-6">
    {!! Form::label('dob', 'Date of Birth:') !!}
    {!! Form::date('dob', null, ['class' => 'form-control']) !!}
</div>

<!-- Gender -->
<div class="form-group col-sm-6">
    {!! Form::label('gender', 'Gender:') !!}
    {!! Form::select('gender', [
        'Male' => 'Male',
        'Female' => 'Female',
        'Other' => 'Other'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Gender']) !!}
</div>

<!-- Nationality -->
<div class="form-group col-sm-6">
    {!! Form::label('country', 'Nationality:') !!}
    {!! Form::text('country', null, ['class' => 'form-control']) !!}
</div>

<!-- Address -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- City -->
<div class="form-group col-sm-6">
    {!! Form::label('city', 'City:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Email -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Passport Number -->
<div class="form-group col-sm-6">
    {!! Form::label('passport_no', 'Passport Number:') !!}
    {!! Form::text('passport_no', null, ['class' => 'form-control']) !!}
</div>

<!-- Matter Type -->
<div class="form-group col-sm-6">
    {!! Form::label('visa_type', 'Matter Type:') !!}
    {!! Form::select('visa_type', [
        'Work Visa' => 'Work Visa',
        'Student Visa' => 'Student Visa',
        'Spouse Visa' => 'Spouse Visa',
        'Visitor Visa' => 'Visitor Visa',
        'Settlement Visa' => 'Settlement Visa'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Type']) !!}
</div>

<!-- Visa Issue Date -->
<div class="form-group col-sm-6">
    {!! Form::label('visa_issue_date', 'Visa Issue Date:') !!}
    {!! Form::date('visa_issue_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Visa Expiry Date -->
<div class="form-group col-sm-6">
    {!! Form::label('visa_expiry_date', 'Visa Expiry Date:') !!}
    {!! Form::date('visa_expiry_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Priority -->
<div class="form-group col-sm-6">
    {!! Form::label('priority', 'Priority:') !!}
    {!! Form::select('priority', [
        'Urgent' => 'Urgent',
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low'
    ], null, ['class' => 'form-control']) !!}
</div>

<!-- Status -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [
        'Active' => 'Active',
        'Closed' => 'Closed',
        'Pending' => 'Pending',
        'Archived' => 'Archived'
    ], null, ['class' => 'form-control']) !!}
</div>

<!-- Court Type -->
<div class="form-group col-sm-6">
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