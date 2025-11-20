<div class="container">

    <h3>Create Authority Letter for: {{ $client->name }}</h3>
    <hr>

    {!! Form::hidden('client_id', $client->id) !!}

    <div class="row">

        <div class="form-group col-sm-6">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('date_of_birth', 'Date of Birth:') !!}
            {!! Form::date('date_of_birth', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('nationality', 'Nationality:') !!}
            {!! Form::text('nationality', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('client_address', 'Client Address:') !!}
            {!! Form::text('client_address', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('purpose', 'Purpose:') !!}
            {!! Form::text('purpose', $client->visa_type, ['class' => 'form-control']) !!}
        </div>

    </div>

    <div class="form-group col-sm-12">
        {!! Form::submit('Generate Authority Letter', ['class' => 'btn btn-primary']) !!}
    </div>

</div>
