@extends('layouts.app')
@section('title','Edit '.ucfirst(config('settings.receipts_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.receipts_label_singular'))}}
        </h1>
   </section>
   <div class="content">
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($receipt, ['route' => ['receipts.update', $receipt->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('receipts.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
