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
           {{-- ── BUTTON (opens modal) ─────────────────────────────────────── --}}
            <button type="button"
                    class="btn btn-success btn-sm"
                    data-toggle="modal"
                    data-target="#markPaidModal">
                <i class="fa fa-file-text-o"></i> Create Receipt
            </button>
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
    {{-- ── MODAL ────────────────────────────────────────────────────── --}}
<div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
 
            <div class="modal-header">
                <h5 class="modal-title">Mark Invoice as Paid</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
 
            <form action="{{ route('invoices.markPaid', $invoice->id) }}"
                  method="POST">
                @csrf
                @method('PATCH')
 
                <div class="modal-body">
 
                    <div class="form-group">
                        <label><strong>Payment Method</strong></label>
                        <select name="payment_method" class="form-control" required
                                id="paymentMethodSelect">
                            <option value="">-- Select Payment Method --</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="bacs">BACS</option>
                            <option value="money_order">Money Order</option>
                        </select>
                    </div>
 
                    {{-- Cheque number field (hidden by default) --}}
                    <div class="form-group" id="chequeNumberGroup" style="display:none;">
                        <label>Cheque Number</label>
                        <input type="text"
                               name="cheque_number"
                               class="form-control"
                               placeholder="Enter cheque number">
                    </div>
 
                    <div class="alert alert-info" style="font-size:13px; margin-bottom:0;">
                        <strong>Invoice:</strong> #{{ str_pad($invoice->invoice_no, 4, '0', STR_PAD_LEFT) }}<br>
                        <strong>Client:</strong> {{ $invoice->client->first_name }}<br>
                        <strong>Amount:</strong> £{{ number_format($invoice->total_due, 2) }}
                    </div>
 
                </div>
 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Confirm & Generate Receipt
                    </button>
                </div>
 
            </form>
 
        </div>
    </div>
</div>
 
{{-- ── JS: show/hide cheque field ─────────────────────────────── --}}
<script>
document.getElementById('paymentMethodSelect').addEventListener('change', function () {
    var chequeGroup = document.getElementById('chequeNumberGroup');
    chequeGroup.style.display = this.value === 'cheque' ? 'block' : 'none';
});
</script>

{{-- ── JS: print invoice ─────────────────────────────────────── --}}
@endsection
