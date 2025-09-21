@extends('backend.layouts.master')
@section('title', 'Facebook Lead Generation Form Details')
@section('custom_css')
    <style>
        .form-header-card {
            background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%);
            color: white;
        }

        .info-card {
            border-left: 4px solid #1877f2;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .locale-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .form-id {
            font-family: monospace;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Lead Generation Form Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminFacebookLeadgenForms') }}">Lead Generation Forms</a></li>
                <li class="breadcrumb-item active">{{ $form->name }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Form Header Info -->
        <div class="col-md-12 mb-4">
            <div class="card form-header-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title text-white mb-1">{{ $form->name }}</h2>
                            <p class="text-white-50 mb-0">Form ID: {{ $form->form_id }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if ($form->is_active)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-danger fs-6">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Details -->
        <div class="col-md-8">
            <div class="card info-card">
                <div class="card-header">
                    <h3 class="card-title">Form Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Form ID</label>
                                <p class="form-control-plaintext">
                                    <span class="form-id">{{ $form->form_id }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Form Name</label>
                                <p class="form-control-plaintext">{{ $form->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $form->facebookPage->name ?? 'Unknown Page' }}</strong>
                                    <br>
                                    <small class="text-muted">Page ID: {{ $form->page_id }}</small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Locale</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info locale-badge">{{ $form->locale }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span
                                        class="badge bg-{{ $form->status_badge }} status-badge">{{ $form->status_text }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Active Status</label>
                                <p class="form-control-plaintext">
                                    @if ($form->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($form->description)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <p class="form-control-plaintext">{{ $form->description }}</p>
                        </div>
                    @endif

                    @if ($form->form_data && count($form->form_data) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Form Data</label>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code>{{ json_encode($form->form_data, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
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
                        <a href="{{ route('adminFacebookLeadgenFormToggleStatus', $form->id) }}"
                            class="btn {{ $form->is_active ? 'btn-warning' : 'btn-success' }}"
                            onclick="return confirm('Are you sure you want to {{ $form->is_active ? 'deactivate' : 'activate' }} this form?')">
                            <i class="fe fe-{{ $form->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $form->is_active ? 'Deactivate' : 'Activate' }} Form
                        </a>

                        <a href="{{ route('adminFacebookLeadgenFormDelete', $form->id) }}" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this form? This action cannot be undone.')">
                            <i class="fe fe-trash-2 me-2"></i>
                            Delete Form
                        </a>

                        <a href="{{ route('adminFacebookLeadgenForms') }}" class="btn btn-secondary">
                            <i class="fe fe-arrow-left me-2"></i>
                            Back to Forms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Page Information -->
            @if ($form->facebookPage)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Page Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Page Name</label>
                            <p class="form-control-plaintext">{{ $form->facebookPage->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Page ID</label>
                            <p class="form-control-plaintext">
                                <span class="form-id">{{ $form->facebookPage->page_id }}</span>
                            </p>
                        </div>
                        @if ($form->facebookPage->category)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page Category</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ $form->facebookPage->category }}</span>
                                </p>
                            </div>
                        @endif
                        <a href="{{ route('adminFacebookPageView', $form->facebookPage->id) }}"
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
                                <p class="form-control-plaintext">{{ $form->created_at->format('F d, Y \a\t H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="form-control-plaintext">{{ $form->updated_at->format('F d, Y \a\t H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
<script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        // Any additional JavaScript can be added here
    </script>
@endsection
