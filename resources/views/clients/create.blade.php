@extends('layouts.app')
@section('title','New '.ucfirst(config('settings.clients_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.clients_label_singular'))}}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'clients.store', 'files' => true]) !!}

                        @include('clients.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
