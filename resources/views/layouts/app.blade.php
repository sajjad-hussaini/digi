<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | {{config('settings.system_title')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Bootstrap Toggle -->
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{asset('css/lte/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/lte/skins/skin-blue-light.min.css')}}">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Wysihtml5 & Tags Input -->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-wysihtml5/css/bootstrap3-wysihtml5.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}">

    <!-- =====================================================
         MASTER CUSTOM CSS — Sabhi pages ka style isi file mein
         Location: public/css/digidocu-custom.css
    ====================================================== -->
    <link rel="stylesheet" href="{{asset('css/digidocu-custom.css')}}">

    <!-- Page-specific CSS (har page apna CSS yahan inject kar sakta hai) -->
    @yield('css')
</head>

<body class="skin-blue-light sidebar-mini">

@if (!Auth::guest())

    <div class="wrapper">

        {{-- ===== MAIN HEADER ===== --}}
        <header class="main-header">

            {{-- Logo --}}
            <a href="{{route('admin.dashboard')}}" class="hidden-xs logo">
                <span class="logo-mini"><b>{{config('settings.system_title')[0]}}</b></span>
                <span class="logo-lg"><b>{{config('settings.system_title')}}</b></span>
            </a>

            {{-- Header Navbar --}}
            <nav class="navbar navbar-static-top" role="navigation">

                {{-- Sidebar toggle --}}
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                {{-- Mobile Title --}}
                <span style="display:inline-block; width:71vw; text-align:center; font-size:18px; line-height:50px; color:white; font-family:'Playfair Display',serif; font-weight:600;"
                      class="visible-xs-inline-block">
                    {{config('settings.system_title')}}
                </span>

                {{-- Right Side Menu --}}
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        {{-- User Account Dropdown --}}
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{asset(config('settings.system_logo'))}}"
                                     class="user-image" alt="User Image" />
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{asset(config('settings.system_logo'))}}"
                                         class="img-circle" alt="User Image" />
                                    <p>
                                        {!! Auth::user()->name !!}
                                        <small>Member since {!! Auth::user()->created_at->format('M. Y') !!}</small>
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{route('profile.manage')}}" class="btn btn-default btn-flat">
                                            <i class="fa fa-user-o mr-1"></i> Profile
                                        </a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out mr-1"></i> Sign out
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display:none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        {{-- ===== SIDEBAR ===== --}}
        @include('layouts.sidebar')

        {{-- ===== CONTENT WRAPPER ===== --}}
        <div class="content-wrapper">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div style="padding: 16px 20px 0;">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div style="padding: 16px 20px 0;">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-times-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div style="padding: 16px 20px 0;">
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fa fa-exclamation-triangle"></i>
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')

        </div>
        {{-- /.content-wrapper --}}

    </div>
    {{-- /.wrapper --}}

@else

    {{-- ===== GUEST NAVBAR ===== --}}
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed"
                        data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{!! url('/') !!}" style="font-family:'Playfair Display',serif;">
                    {{config('settings.system_title')}}
                </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

@endif

{{-- ===== SCRIPTS ===== --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="{{asset('vendor/bootstrap-typeahead/js/bootstrap3-typeahead.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-wysihtml5/js/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.4.2/handlebars.min.js"></script>
<script src="{{asset('js/handlebar-helpers.js')}}"></script>
<script src="{{asset('js/digidocu-custom.js')}}"></script>

{{-- Page-specific scripts --}}
@yield('scripts')

</body>
</html>