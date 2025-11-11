<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h4 class="card-title mb-3 text-primary">
            <i class="fa fa-building"></i> Company Details
        </h4>

        <!-- Company Logo -->
        @if(!empty($company->company_logo))
            <div class="form-group text-center mb-4">
                <label class="font-weight-bold d-block">Company Logo:</label>
                <img src="{{ asset('storage/'.$company->company_logo) }}" 
                     alt="Company Logo" 
                     class="img-fluid rounded shadow-sm" 
                     style="max-height: 150px; object-fit: contain;">
            </div>
        @endif

        <!-- Accreditor Logos -->
        @if(!empty($company->accreditor_logos))
            <div class="form-group mb-4">
                <label class="font-weight-bold d-block">Accreditor Logos:</label>
                <div class="d-flex flex-wrap align-items-center">
                    @foreach($company->accreditor_logos as $logo)
                        <div class="m-2">
                            <img src="{{ asset('storage/'.$logo) }}" 
                                 alt="Accreditor Logo" 
                                 class="img-thumbnail shadow-sm" 
                                 style="width: 80px; height: 80px; object-fit: contain;">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Company Info -->
        <div class="form-group">
            {!! Form::label('company_name', 'Company Name:') !!}
            <p class="text-muted">{{ $company->company_name }}</p>
        </div>

        <div class="form-group">
            {!! Form::label('company_address', 'Company Address:') !!}
            <p class="text-muted">{{ $company->company_address }}</p>
        </div>

        <div class="form-group">
            {!! Form::label('contact_number', 'Contact Number:') !!}
            <p class="text-muted">{{ $company->contact_number }}</p>
        </div>

        <div class="form-group">
            {!! Form::label('email_address', 'Email Address:') !!}
            <p class="text-muted">{{ $company->email_address }}</p>
        </div>

        <div class="form-group">
            {!! Form::label('solicitor_name', 'Solicitor Name:') !!}
            <p class="text-muted">{{ $company->solicitor_name }}</p>
        </div>

        <div class="form-group">
            {!! Form::label('regulated_by', 'Regulated By:') !!}
            <p class="text-muted">{{ $company->regulated_by }}</p>
        </div>

        <div class="form-group">
            {!! Form::label('company_reg_number', 'Company Reg. Number:') !!}
            <p class="text-muted">{{ $company->company_reg_number }}</p>
        </div>

        <!-- Custom Fields -->
        @foreach ($company->custom_fields ?? [] as $custom_field_name => $custom_field_value)
            <div class="form-group">
                {!! Form::label($custom_field_name, Str::title(str_replace('_',' ',$custom_field_name)).":") !!}
                <p class="text-muted">{{ $custom_field_value }}</p>
            </div>
        @endforeach

        <hr>

        <!-- Created / Updated Info -->
        <div class="row">
            <div class="col-md-6">
                <p><strong>Created By:</strong> {{ $company->createdBy->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Created At:</strong> {{ $company->created_at->format('d M, Y') }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Updated At:</strong> {{ $company->updated_at->format('d M, Y') }}</p>
            </div>
        </div>
    </div>
</div>
