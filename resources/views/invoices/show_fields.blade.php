<div class="container">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Invoice Details</h3>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>

    <!-- Invoice Summary Card -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Invoice #{{ $invoice->invoice_no }}</strong>
        </div>

        <div class="card-body row">

            <div class="col-md-6">
                <h5 class="mb-2">Client Information</h5>
                <p><strong>Name:</strong> {{ $invoice->client->name }}</p>
                <p><strong>Phone:</strong> {{ $invoice->client->phone }}</p>
                <p><strong>Email:</strong> {{ $invoice->client->email_address }}</p>
                <p><strong>Company:</strong> {{ $invoice->client->company->name ?? '—' }}</p>
            </div>

            <div class="col-md-6">
                <h5 class="mb-2">Invoice Information</h5>

                <p><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
                <p><strong>Amount:</strong> £{{ number_format($invoice->amount, 2) }}</p>

                <p><strong>Status:</strong>
                    @if($invoice->status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-danger">Unpaid</span>
                    @endif
                </p>

                <p><strong>Invoice Date:</strong>
                    {{ $invoice->invoice_date ? $invoice->invoice_date : '—' }}
                </p>

                <p><strong>Created At:</strong>
                    {{ $invoice->created_at->format('d M, Y H:i') }}
                </p>

            </div>

        </div>
    </div>

    <!-- Description -->
    @if($invoice->description)
    <div class="card mb-4">
        <div class="card-header">
            <strong>Description</strong>
        </div>
        <div class="card-body">
            {!! nl2br(e($invoice->description)) !!}
        </div>
    </div>
    @endif


    <!-- Attachments (If using documents in your system) -->
    @if(isset($invoice->documents) && $invoice->documents->count())
    <div class="card mb-4">
        <div class="card-header">
            <strong>Attached Documents</strong>
        </div>
        <div class="card-body">

            <ul>
                @foreach($invoice->documents as $doc)
                <li>
                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank">
                        {{ $doc->document_type->name ?? 'Document' }}
                    </a>
                </li>
                @endforeach
            </ul>

        </div>
    </div>
    @endif

</div>