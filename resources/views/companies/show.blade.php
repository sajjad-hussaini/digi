@extends('layouts.app')
@section('title','Show '.ucfirst(config('settings.companies_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.companies_label_singular'))}}
            <span class="pull-right">
            <a href="{{ route('companies.index') }}" class="btn btn-default">
                <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
            </a>
            <a href="{{ route('companies.edit',$company->id) }}" class="btn btn-primary">
                <i class="fa fa-edit" aria-hidden="true"></i> Edit
            </a>
            {!! Form::open(['route' => ['companies.destroy', $company->id], 'method' => 'delete','style'=>'display:inline']) !!}
                {!! Form::button('<i class="fa fa-trash"></i> Delete', [
                'type' => 'submit',
                'title' => 'Delete',
                'class' => 'btn btn-danger',
                'onclick' => "return conformDel(this,event)",
                ]) !!}
                {!! Form::close() !!}
        </span>
        </h1>
    </section>
    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#company" data-toggle="tab"
                                      aria-expanded="true">{{ucfirst(config('settings.companies_label_singular'))}}</a>
                </li>
                @can('user manage permission')
                    <li class=""><a href="#tab_permissions" data-toggle="tab"
                                    aria-expanded="false">Permission</a>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="company">
                    @include('companies.show_fields')
                </div>
                @can('user manage permission')
                    <div class="tab-pane" id="tab_permissions">
                      
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
