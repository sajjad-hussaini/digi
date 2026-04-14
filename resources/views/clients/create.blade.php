@extends('layouts.app')
@section('title','New '.ucfirst(config('settings.clients_label_singular')))
@section('css')
<style>
    #court_type_div {
        display: none;
    }
    .help-blocks {
        font-size: 12px;
        color: #e24b4a;
    }
</style>
@endsection
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
@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Select Country",
            allowClear: true
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        $('.datepicker').inputmask('99/99/9999');
    });

    $(document).ready(function () {

        function toggleCourtType() {
            let type = $('#visa_type').val();

            if (type === 'Appeal') {
                $('#court_type_div').show();
            } else {
                $('#court_type_div').hide();
                $('#court_type').val(''); // reset value
            }
        }

        // change event
        $('#visa_type').change(function () {
            toggleCourtType();
        });

        // page load par bhi check
        toggleCourtType();
    });

    $(document).on('keydown', 'input, select, textarea', function(e) {
    if (e.key === "Enter") {
        e.preventDefault(); 

        let inputs = $(this).closest('form').find('input, select, textarea');
        let index = inputs.index(this);

        if (index > -1 && index + 1 < inputs.length) {
            inputs.eq(index + 1).focus(); // next field focus
        }
    }
});
</script>
@endsection

