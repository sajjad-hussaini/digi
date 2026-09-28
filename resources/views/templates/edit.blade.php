@extends('layouts.app')
@section('title', 'Edit ' . ucfirst(config('settings.templates_label_singular')))
@section('css')
    <link rel="stylesheet" href="{{ asset('css/template-client-keys.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            {{ ucfirst(config('settings.templates_label_singular')) }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($template, [
                        'route' => ['templates.update', $template->id],
                        'method' => 'patch',
                        'files' => true,
                    ]) !!}

                    @include('templates.fields')

                    {!! Form::close() !!}

                    @include('templates.preview')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/template-client-keys.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.6/dist/docx-preview.min.js"></script>
    <script src="{{ asset('js/template-preview.js') }}"></script>
@endsection
