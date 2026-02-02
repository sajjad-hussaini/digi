@extends('layouts.app')
@section('title','Home')
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Dashboard</h1>
    </section>
    <section class="content" style="margin-top: 20px;">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-tags"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ucfirst('Clients')}}</span>
                        <span class="info-box-number">{{$clientCounts}}</span>
                        <span class="progress-description">
                    Total {{ucfirst(config('settings.tags_label_plural'))}} in system
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-folder"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ucfirst(config('settings.document_label_plural'))}}</span>
                        <span class="info-box-number">{{$documentCounts}}</span>
                        <span class="progress-description">
                    Containing {{$filesCounts}} {{ucfirst(config('settings.file_label_plural'))}}
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    </section>
@endsection
