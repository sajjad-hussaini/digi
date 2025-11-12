<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Client Details</h4>
        </div>

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
                    <p class="text-dark">{{ $client->name }}</p>
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
                        @if($client->status == 'Active')
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

        <div class="card-footer text-right">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
</div>