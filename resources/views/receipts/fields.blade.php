<div class="form-group col-sm-6 {{ $errors->has('client_id') ? 'has-error' : '' }}">
    {!! Form::label('client_id', 'Client:') !!}
    <select name="client_id" id="client_id" class="form-control" required>
        <option value="">Select Client</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                {{ trim($client->first_name . ' ' . $client->sir_name) }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('client_id', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-6 {{ $errors->has('invoice_id') ? 'has-error' : '' }}">
    {!! Form::label('invoice_id', 'Invoice:') !!}
    <select name="invoice_id" id="invoice_id" class="form-control" required>
        <option value="">Select Invoice</option>
        @foreach($invoices as $invoice)
            <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                {{ $invoice->invoice_no }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('invoice_id', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-6 {{ $errors->has('amount_paid') ? 'has-error' : '' }}">
    {!! Form::label('amount_paid', 'Amount Paid:') !!}
    {!! Form::number('amount_paid', old('amount_paid'), ['class' => 'form-control', 'required' => true, 'min' => '0.01', 'max' => '99999999.99', 'step' => '0.01']) !!}
    {!! $errors->first('amount_paid', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-6 {{ $errors->has('payment_method') ? 'has-error' : '' }}">
    {!! Form::label('payment_method', 'Payment Method:') !!}
    {!! Form::select('payment_method', ['cash' => 'Cash', 'cheque' => 'Cheque', 'bacs' => 'BACS', 'money_order' => 'Money Order'], old('payment_method'), ['class' => 'form-control', 'placeholder' => 'Select payment method', 'required' => true]) !!}
    {!! $errors->first('payment_method', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-6 {{ $errors->has('cheque_number') ? 'has-error' : '' }}">
    {!! Form::label('cheque_number', 'Cheque Number:') !!}
    {!! Form::text('cheque_number', old('cheque_number'), ['class' => 'form-control', 'maxlength' => 50]) !!}
    {!! $errors->first('cheque_number', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-6 {{ $errors->has('payment_date') ? 'has-error' : '' }}">
    {!! Form::label('payment_date', 'Payment Date:') !!}
    {!! Form::date('payment_date', old('payment_date', now()->toDateString()), ['class' => 'form-control', 'required' => true]) !!}
    {!! $errors->first('payment_date', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-12 {{ $errors->has('payment_for') ? 'has-error' : '' }}">
    {!! Form::label('payment_for', 'Payment For:') !!}
    {!! Form::text('payment_for', old('payment_for'), ['class' => 'form-control', 'required' => true, 'maxlength' => 500]) !!}
    {!! $errors->first('payment_for', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::submit('Save Receipt', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('receipts.index') }}" class="btn btn-default">Cancel</a>
</div>
