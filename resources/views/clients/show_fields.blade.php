<div class="container-fluid">
    <div class="row">
        <div class="card-header bg-primary text-white" style="padding: 1px; margin-bottom: 15px; border-radius: 10px; border-bottom: 4px solid black;">
            <h4 class="mb-0">Client Details</h4>
        </div>
        <!-- LEFT SIDE : Client Details -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">


                <div class="card-body">
                    <div class="row">

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Firstname:</label>
                            <p class="text-dark">{{ $client->first_name ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Surname:</label>
                            <p class="text-dark">{{ $client->sir_name ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Date Of Birth:</label>
                            <p class="text-dark">{{ $client->dob ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Gender:</label>
                            <p class="text-dark">{{ $client->gender ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Nationality:</label>
                            <p class="text-dark">{{ $client->country ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Address:</label>
                            <p class="text-dark">{{ $client->address ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">City:</label>
                            <p class="text-dark">{{ $client->city ?? 'N/A' }}</p>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Phone:</label>
                            <p class="text-dark">{{ $client->phone ?? 'N/A' }}</p>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Email:</label>
                            <p class="text-dark">{{ $client->email ?? 'N/A' }}</p>
                        </div>

                        <!-- Visa Type -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Visa Type:</label>
                            <p class="text-dark">{{ $client->visa_type ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Visa issue Date:</label>
                            <p class="text-dark">
                                {{ $client->visa_issue_date 
                                    ? \Carbon\Carbon::parse($client->getRawOriginal('visa_issue_date'))->format('d M, Y') 
                                    : 'N/A' 
                                }}
                            </p>
                        </div>

                        <!-- Visa Expiry Date -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Visa Expiry Date:</label>
                            <p class="text-dark">
                                {{ $client->visa_expiry_date 
                                    ? \Carbon\Carbon::parse($client->getRawOriginal('visa_expiry_date'))->format('d M, Y') 
                                    : 'N/A' 
                                }}
                            </p>
                        </div>

                        <!-- Passport Number -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Passport Number:</label>
                            <p class="text-dark">{{ $client->passport_no ?? 'N/A' }}</p>
                        </div>

                        <!-- Reference Number -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Reference Number:</label>
                            <p class="text-dark">{{ $client->ref_number ?? 'N/A' }}</p>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Status:</label>
                            <p>
                                @if ($client->status == 'Active')
                                <span class="badge badge-success">Active</span>
                                @elseif($client->status == 'Closed')
                                <span class="badge badge-danger">Closed</span>
                                @elseif($client->status == 'Pending')
                                <span class="badge badge-warning">Pending</span>
                                @else
                                <span class="badge badge-secondary">{{ $client->status }}</span>
                                @endif
                            </p>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Priority:</label>
                            <p>
                                @switch($client->priority)
                                @case('Urgent')
                                <span class="badge badge-danger">Urgent</span>
                                @break

                                @case('High')
                                <span class="badge badge-warning">High</span>
                                @break

                                @case('Medium')
                                <span class="badge badge-info">Medium</span>
                                @break

                                @case('Low')
                                <span class="badge badge-secondary">Low</span>
                                @break

                                @default
                                <span class="badge badge-light">N/A</span>
                                @endswitch
                            </p>
                        </div>

                        <!-- Court Type -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Court Type:</label>
                            <p class="text-dark">{{ $client->court_type ?? 'N/A' }}</p>
                        </div>

                        <!-- Created At -->
                        <!-- <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Created At:</label>
                            <p class="text-dark">{{ $client->created_at->format('d M, Y h:i A') }}</p>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE : Generate Letters -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-lg mt-4">
                <div class="card-header bg-dark text-white">
                    {{-- <h5 class="mb-0">Generate Immigration Letters</h5> --}}
                    <div class="mt-4 p-3 border rounded bg-light text-center permanent_client_btn" style="border-top: 1px solid #dee2e6; padding-bottom: 7px;">
                        @if($client->is_permanent)

                        <button class="btn btn-success btn-block" disabled>
                            Permanent Client
                        </button>
                        @else
                        <button type="button"
                            class="btn btn-dark btn-block"
                            id="is_permanent">
                            Make It Permanent Client ?
                        </button>
                        <small class="text-muted d-block mt-2">
                            This button to click the client as permanent and generate a reference number.
                        </small>
                        @endif
                    </div>
                    <div class=" text-center" style="border-top: 1px solid #dee2e6; padding-top: 4px;padding-bottom: 15px;">
                        <a href="{{ route('invoices.generateForClient', $client->id) }}"
                            class="btn btn-block btn btn-default mb-3"> Generate Invoice ?
                        </a>
                    </div>
                    <hr>
                </div>

                <div class="card-body">

                    <a href="{{ route('clients.authority-letter', $client->id) }}"
                        class="btn btn-block btn btn-default mb-3" target="_blank">
                        Authority Letter
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-block btn btn-default mb-3"
                        data-toggle="modal"
                        data-target="#initialInstructionModal"
                        data-matter-type="{{$client_matter_type}}"
                        data-template-type="Initial Instruction"> Initial Instruction
                    </a>
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-block btn btn-default mb-3"
                        data-toggle="modal"
                        data-target="#initialInstructionModal"
                        data-matter-type="{{$client_matter_type}}"
                        data-template-type="Client Care"> Client Care Letter
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-block btn btn-default mb-3"
                        data-toggle="modal"
                        data-target="#initialInstructionModal"
                        data-matter-type="{{$client_matter_type}}"
                        data-template-type="Client Closure Letter"> Client Closure Letter
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-block btn btn-default mb-3"
                        data-toggle="modal"
                        data-target="#initialInstructionModal"
                        data-matter-type="{{$client_matter_type}}"
                        data-template-type="Covering Letter"> Covering Letter
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-calculator"></i> Client Ledger</h3>
                <span class="pull-right text-muted">Live account summary</span>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="small-box bg-aqua">
                            <div class="inner"><h3>£{{ number_format($totalInvoiced, 2) }}</h3><p>Total Invoiced</p></div>
                            <div class="icon"><i class="fa fa-file-text-o"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="small-box bg-green">
                            <div class="inner"><h3>£{{ number_format($totalPaid, 2) }}</h3><p>Total Paid</p></div>
                            <div class="icon"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="small-box bg-yellow">
                            <div class="inner"><h3>£{{ number_format($totalRemaining, 2) }}</h3><p>Remaining Balance</p></div>
                            <div class="icon"><i class="fa fa-balance-scale"></i></div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledger as $entry)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                                    <td>
                                        @if($entry['type'] === 'Invoice')
                                            <span class="label label-info">Invoice</span>
                                        @else
                                            <span class="label label-success">Payment</span>
                                        @endif
                                    </td>
                                    <td>{{ $entry['reference'] }}</td>
                                    <td>{{ $entry['description'] }}</td>
                                    <td class="text-right">{{ $entry['debit'] ? '£' . number_format($entry['debit'], 2) : '-' }}</td>
                                    <td class="text-right">{{ $entry['credit'] ? '£' . number_format($entry['credit'], 2) : '-' }}</td>
                                    <td class="text-right"><strong>£{{ number_format($entry['balance'], 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No invoices or payments recorded for this client.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($client->invoices->isNotEmpty())
                    <h4 class="text-muted">Invoice Status</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Invoice</th><th>Date</th><th>Total</th><th>Paid</th><th>Remaining</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                            @foreach($client->invoices as $invoice)
                                @php
                                    $invoiceTotal = (float) ($invoice->total_due ?: $invoice->amount);
                                    $invoicePaid = (float) $invoice->receipts->sum('amount_paid');
                                    $invoiceRemaining = max(0, round($invoiceTotal - $invoicePaid, 2));
                                @endphp
                                <tr>
                                    <td>{{ $invoice->invoice_no }}</td>
                                    <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : 'N/A' }}</td>
                                    <td>£{{ number_format($invoiceTotal, 2) }}</td>
                                    <td class="text-success">£{{ number_format($invoicePaid, 2) }}</td>
                                    <td class="{{ $invoiceRemaining > 0 ? 'text-danger' : 'text-success' }}">£{{ number_format($invoiceRemaining, 2) }}</td>
                                    <td><span class="label label-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'danger') }}">{{ ucfirst($invoice->status) }}</span></td>
                                    <td><a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i> View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Initial Instruction Modal -->
<div class="modal fade" id="initialInstructionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Client Letters</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Step 1: Choice -->
                <div id="choice-step">
                    <!-- <button type="button" class="btn btn-block btn-primary mb-3" id="baseTemplateBtn">
                        <i class="fas fa-file"></i> Continue with Base Template
                    </button> -->
                    <div class="text-center mb-4">
                        <div class="icon-circle bg-soft-primary mb-3">
                            <i class="fa fa-copy fa-2x text-primary"></i>
                        </div>
                        <h4 class="mb-1">Select a Template</h4>
                        <p class="text-muted">Choose a pre-defined letter to begin editing</p>
                    </div>
                    <label for="templateSelect">
                        <i class="fa fa-file-word text-primary"></i> Existing Template
                    </label>

                    <!-- Loading state -->
                    <div id="templatesLoading" class="text-center py-3">
                        <i class="fa fa-spinner fa-spin"></i> Loading templates...
                    </div>

                    <!-- Templates dropdown -->
                    <select id="templateSelect" class="form-control" style="display:none;">
                        <option value="">Chose a template</option>
                    </select>

                    <button type="button"
                        class="btn btn-block btn-outline-primary mt-2"
                        id="loadTemplateBtn"
                        style="display:none;"
                        disabled>
                        <i class="fa fa-eye"></i> Load & Edit Template
                    </button>
                </div>

                <!-- Step 2: Word Editor -->
                <div id="editor-step" style="display:none;">
                    <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-sm btn-secondary" id="backToChoice">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info" id="findReplaceBtn">
                                <i class="fa fa-search"></i> Find & Replace
                            </button>
                            <button type="button" class="btn btn-sm btn-success" id="generateDocxBtn">
                                <i class="fa fa-file-word"></i> Generate DOCX
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="generatePdfBtn">
                                <i class="fa fa-file-pdf"></i> Generate PDF
                            </button>
                        </div>
                    </div>

                    <!-- Find & Replace Panel -->
                    <div id="findReplacePanel" class="card mb-3" style="display:none;">
                        <div class="card-body py-2">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <label class="mb-1">Find:</label>
                                    <input type="text" id="findText" class="form-control form-control-sm" placeholder="Text to find">
                                </div>
                                <div class="col-md-5">
                                    <label class="mb-1">Replace with:</label>
                                    <input type="text" id="replaceText" class="form-control form-control-sm" placeholder="Replacement text">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" id="replaceAllBtn">
                                        Replace All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template title -->
                    <div id="templateTitle" class="alert alert-light border mb-2">
                        <i class="fa fa-file-word text-primary"></i>
                        <strong id="templateTitleText"></strong>
                    </div>

                    <!-- Document Editor -->
                    <div id="docxEditor" class="border bg-white p-4"
                        style="min-height: 500px; max-height: 600px; overflow-y: auto; 
                                box-shadow: 0 0 10px rgba(0,0,0,0.1);">

                        <!-- Loading -->
                        <div id="editorLoading" class="text-center py-5">
                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Loading document...</p>
                        </div>

                        <div id="documentContent" contenteditable="true" style="outline: none; display:none;">
                            <!-- Word content will be rendered here -->
                        </div>
                    </div>

                    <div class="mt-2 alert alert-info py-2">
                        <small>
                            <i class="fa fa-info-circle"></i>
                            Click anywhere to edit • Use Find & Replace for bulk changes
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Initial Instruction Modal -->
<div class="modal fade" id="initialInstructionBaseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" action="{{ route('clients.initial-instruction', $client->id) }}">
            @csrf

            <!-- client id hidden -->
            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Initial Instruction</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Immigration History</strong></label>
                        <textarea name="immigration_history"
                            class="form-control"
                            rows="6"
                            placeholder="Enter immigration history..."
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label><strong>Work and Family Information</strong></label>
                        <textarea name="work_family_info"
                            class="form-control"
                            rows="6"
                            placeholder="Enter Work and Family..."
                            required></textarea>
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('initial_instruction',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- client clouser letter -->
<div class="modal fade" id="clientClouserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" action="{{ route('clients.advice-letter', $client->id) }}">
            @csrf

            <!-- client id hidden -->
            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Client Care Letter</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::bsTextarea('ILR vignette sticker',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('Instructions Received',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('initial instructions to me',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('Advice given',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('mentioned list of documents',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('Care and conduct',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('Complaints procedure',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::bsTextarea('Professional Fees',null,['class'=>'form-control b-wysihtml5-editor']) !!}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>