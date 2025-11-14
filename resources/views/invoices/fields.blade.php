<!-- Client Field -->
<div class="form-group col-sm-6 {{ $errors->has('client_id') ? 'has-error' : '' }}">
    {!! Form::label('client_id', 'Client:') !!}
    {!! Form::select('client_id', $clients->pluck('name', 'id'), null, [
        'class' => 'form-control select2',
        'placeholder' => 'Select Client'
    ]) !!}
    {!! $errors->first('client_id','<span class="help-block">:message</span>') !!}
</div>

<!-- Invoice No -->
<div class="form-group col-sm-6 {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
    {!! Form::label('invoice_no', 'Invoice Number:') !!}
    {!! Form::text('invoice_no', $invoiceNo ?? null, [
        'class' => 'form-control',
        'placeholder' => 'INV-001'
    ]) !!}
    {!! $errors->first('invoice_no','<span class="help-block">:message</span>') !!}
</div>

<!-- Amount -->
<div class="form-group col-sm-6 {{ $errors->has('amount') ? 'has-error' : '' }}">
    {!! Form::label('amount', 'Amount (£):') !!}
    {!! Form::number('amount', null, [
        'class' => 'form-control',
        'step' => '0.01',
        'placeholder' => 'Enter Amount'
    ]) !!}
    {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
</div>

<!-- Invoice Date -->
<div class="form-group col-sm-6 {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
    {!! Form::label('invoice_date', 'Invoice Date:') !!}
    {!! Form::date('invoice_date', now(), ['class' => 'form-control']) !!}
    {!! $errors->first('invoice_date','<span class="help-block">:message</span>') !!}
</div>

<!-- Status -->
<div class="form-group col-sm-6 {{ $errors->has('status') ? 'has-error' : '' }}">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [
        'paid' => 'Paid',
        'unpaid' => 'Unpaid'
    ], null, ['class' => 'form-control', 'placeholder' => 'Select Status']) !!}
    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
</div>

<!-- Description -->
<div class="form-group col-sm-12 {{ $errors->has('description') ? 'has-error' : '' }}">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'rows' => 3,
        'placeholder' => 'Enter invoice description...'
    ]) !!}
    {!! $errors->first('description','<span class="help-block">:message</span>') !!}
</div>

<!-- Submit -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save Invoice', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('invoices.index') }}" class="btn btn-default">Cancel</a>
</div>
