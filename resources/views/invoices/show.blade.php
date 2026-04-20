@extends('layouts.app')
@section('title','Show '.ucfirst(config('settings.invoices_label_singular')))
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.invoices_label_singular'))}}
            <span class="pull-right">
            <a href="{{ route('invoices.index') }}" class="btn btn-default">
                <i class="fa fa-chevron-left" aria-hidden="true"></i> Back
            </a>
              <button onclick="printInvoice()" class="btn btn-default btn-sm">
                <i class="fa fa-print"></i> Print
            </button>
             <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-file-pdf-o"></i> Download PDF
            </a>
        </span>
        </h1>
    </section>
    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#invoice" data-toggle="tab"
                                      aria-expanded="true">{{ucfirst(config('settings.invoices_label_singular'))}}</a>
                </li>
                @can('user manage permission')
                    <li class=""><a href="#tab_permissions" data-toggle="tab"
                                    aria-expanded="false"></a>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="invoice">
                    @include('invoices.show_fields')
                </div>
                @can('user manage permission')
                    <div class="tab-pane" id="tab_permissions">
                      
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
