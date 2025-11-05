@extends('layouts.app')
@section('title','Edit '.ucfirst(config('settings.companies_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.companies_label_singular'))}}
        </h1>
   </section>
   <div class="content">
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tag, ['route' => ['companies.update', $tag->id], 'method' => 'patch']) !!}

                        @include('companies.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
