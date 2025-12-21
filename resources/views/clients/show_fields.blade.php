<div class="container-fluid">
    <div class="row">
  <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Client Details</h4>
                </div>
        <!-- LEFT SIDE : Client Details -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
              

                <div class="card-body">
                    <div class="row">
                        <!-- Company -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Company:</label>
                            <p class="text-dark">{{ $client->company->company_name ?? 'N/A' }}</p>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Full Name:</label>
                            <p class="text-dark">{{ $client->first_name . ' ' . $client->sir_name ?? 'Mr Sajjad' }}</p>
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

                        <!-- Visa Expiry Date -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Visa Expiry Date:</label>
                            <p class="text-dark">
                                {{ $client->visa_expiry_date ? \Carbon\Carbon::parse($client->visa_expiry_date)->format('d M, Y') : 'N/A' }}
                            </p>
                        </div>

                        <!-- Passport Number -->
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Passport Number:</label>
                            <p class="text-dark">{{ $client->passport_no ?? 'N/A' }}</p>
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
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Created At:</label>
                            <p class="text-dark">{{ $client->created_at->format('d M, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE : Generate Letters -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-lg mt-4">
                <div class="card-header bg-dark text-white">
                    {{-- <h5 class="mb-0">Generate Immigration Letters</h5> --}}
                </div>

                <div class="card-body">
                    <a href="{{ route('clients.authority-letter', $client->id) }}"
                        class="btn btn-block btn btn-default mb-3" target="_blank">
                        Authority Letter
                    </a>


                    <a href="javascript:void(0)"
                        class="btn btn-block btn btn-default mb-3"
                        data-toggle="modal"
                        data-target="#initialInstructionModal">
                        Initial Instruction
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-block btn btn-default mb-3"
                        data-toggle="modal"
                        data-target="#clientClouserModal">
                        Client Closure Letter
                    </a>

                    <a href="{{ route('clients.covering-letter', $client->id) }}"
                        class="btn btn-block btn btn-default mb-3" target="_blank">
                        Covering Letter
                    </a>

                    <a href="{{ route('clients.client-care-letter', $client->id) }}"
                        class="btn btn-block btn btn-default" target="_blank">
                        Client Care Letter
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Initial Instruction Modal -->
<div class="modal fade" id="initialInstructionModal" tabindex="-1" role="dialog">
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
                    <h5 class="modal-title">Client Closure Letter</h5>
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


