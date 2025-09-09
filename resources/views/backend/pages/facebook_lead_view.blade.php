@extends('backend.layouts.master')
@section('title', 'Facebook Lead Details')
@section('custom_css')
    <style>
        .lead-header-card {
            background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%);
            color: white;
        }

        .info-card {
            border-left: 4px solid #1877f2;
        }

        .field-item {
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 0;
        }

        .field-item:last-child {
            border-bottom: none;
        }

        .field-name {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }

        .field-value {
            color: #212529;
            font-size: 0.95rem;
        }

        .lead-id {
            font-family: monospace;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .field-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            margin: 0.1rem;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Lead Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminFacebookLeads') }}">Facebook Leads</a></li>
                <li class="breadcrumb-item active">{{ $lead->lead_id }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Lead Header Info -->
        <div class="col-md-12 mb-4">
            <div class="card lead-header-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title text-white mb-1">
                                {{ $lead->getFieldValue('name') ?? 'Unknown Lead' }}
                            </h2>
                            <p class="text-white-50 mb-0">Lead ID: {{ $lead->lead_id }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if ($lead->is_processed)
                                <span class="badge bg-success fs-6">Processed</span>
                            @else
                                <span class="badge bg-warning fs-6">Unprocessed</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lead Details -->
        <div class="col-md-8">
            <div class="card info-card">
                <div class="card-header">
                    <h3 class="card-title">Lead Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lead ID</label>
                                <p class="form-control-plaintext">
                                    <span class="lead-id">{{ $lead->lead_id }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created Time</label>
                                <p class="form-control-plaintext">{{ $lead->formatted_created_time }}</p>
                                <small class="text-muted">{{ $lead->lead_age }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Form</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $lead->facebookLeadgenForm->name ?? 'Unknown Form' }}</strong>
                                    <br>
                                    <small class="text-muted">Form ID: {{ $lead->form_id }}</small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $lead->facebookPage->name ?? 'Unknown Page' }}</strong>
                                    <br>
                                    <small class="text-muted">Page ID: {{ $lead->page_id }}</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    @if ($lead->is_processed)
                                        <span class="badge bg-success status-badge">Processed</span>
                                    @else
                                        <span class="badge bg-warning status-badge">Unprocessed</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Common Fields</label>
                                <div class="mt-2">
                                    @if ($lead->getFieldValue('name'))
                                        <span class="badge bg-primary field-badge">Name:
                                            {{ $lead->getFieldValue('name') }}</span>
                                    @endif
                                    @if ($lead->getFieldValue('phone'))
                                        <span class="badge bg-info field-badge">Phone:
                                            {{ $lead->getFieldValue('phone') }}</span>
                                    @endif
                                    @if ($lead->getFieldValue('email'))
                                        <span class="badge bg-success field-badge">Email:
                                            {{ $lead->getFieldValue('email') }}</span>
                                    @endif
                                    @if ($lead->getFieldValue('location'))
                                        <span class="badge bg-warning field-badge">Location:
                                            {{ $lead->getFieldValue('location') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($lead->notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes</label>
                            <p class="form-control-plaintext">{{ $lead->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Fields Data -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Form Fields Data</h3>
                </div>
                <div class="card-body">
                    @if ($lead->field_data && count($lead->field_data) > 0)
                        @foreach ($lead->field_data as $field)
                            <div class="field-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="field-name">{{ $field['name'] }}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="field-value">
                                            @if (isset($field['values']) && is_array($field['values']) && count($field['values']) > 0)
                                                {{ implode(', ', $field['values']) }}
                                            @elseif (isset($field['values']) && !is_array($field['values']))
                                                {{ $field['values'] }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No field data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('adminFacebookLeadToggleStatus', $lead->id) }}"
                            class="btn {{ $lead->is_processed ? 'btn-warning' : 'btn-success' }}"
                            onclick="return confirm('Are you sure you want to {{ $lead->is_processed ? 'mark as unprocessed' : 'mark as processed' }} this lead?')">
                            <i class="fe fe-{{ $lead->is_processed ? 'refresh-cw' : 'check' }} me-2"></i>
                            {{ $lead->is_processed ? 'Mark as Unprocessed' : 'Mark as Processed' }}
                        </a>

                        <a href="{{ route('adminFacebookLeadDelete', $lead->id) }}" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this lead? This action cannot be undone.')">
                            <i class="fe fe-trash-2 me-2"></i>
                            Delete Lead
                        </a>

                        <a href="{{ route('adminFacebookLeads') }}" class="btn btn-secondary">
                            <i class="fe fe-arrow-left me-2"></i>
                            Back to Leads
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Information -->
            @if ($lead->facebookLeadgenForm)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Form Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Form Name</label>
                            <p class="form-control-plaintext">{{ $lead->facebookLeadgenForm->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Form ID</label>
                            <p class="form-control-plaintext">
                                <span class="lead-id">{{ $lead->facebookLeadgenForm->form_id }}</span>
                            </p>
                        </div>
                        @if ($lead->facebookLeadgenForm->status)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Form Status</label>
                                <p class="form-control-plaintext">
                                    <span
                                        class="badge bg-{{ $lead->facebookLeadgenForm->status_badge }}">{{ $lead->facebookLeadgenForm->status_text }}</span>
                                </p>
                            </div>
                        @endif
                        <a href="{{ route('adminFacebookLeadgenFormView', $lead->facebookLeadgenForm->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-external-link me-1"></i>
                            View Form Details
                        </a>
                    </div>
                </div>
            @endif

            <!-- Page Information -->
            @if ($lead->facebookPage)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Page Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Page Name</label>
                            <p class="form-control-plaintext">{{ $lead->facebookPage->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Page ID</label>
                            <p class="form-control-plaintext">
                                <span class="lead-id">{{ $lead->facebookPage->page_id }}</span>
                            </p>
                        </div>
                        @if ($lead->facebookPage->category)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page Category</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ $lead->facebookPage->category }}</span>
                                </p>
                            </div>
                        @endif
                        <a href="{{ route('adminFacebookPageView', $lead->facebookPage->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-external-link me-1"></i>
                            View Page Details
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Timestamps -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timestamps</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created At</label>
                                <p class="form-control-plaintext">{{ $lead->created_at->format('F d, Y \a\t H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="form-control-plaintext">{{ $lead->updated_at->format('F d, Y \a\t H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        // Any additional JavaScript can be added here
    </script>
@endsection
