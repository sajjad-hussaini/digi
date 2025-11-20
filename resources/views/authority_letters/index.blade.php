@extends('layouts.app')
@section('title','List '.ucfirst(config('settings.authority_letters_label_plural')))
@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('authority_letters.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

