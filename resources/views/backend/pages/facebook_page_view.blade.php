@extends('backend.layouts.master')
@section('title', 'Facebook Page Details')
@section('custom_css')
    <style>
        .page-header-card {
            background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%);
            color: white;
        }

        .info-card {
            border-left: 4px solid #1877f2;
        }

        .category-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .task-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            margin: 0.1rem;
        }

        .access-token {
            font-family: monospace;
            font-size: 0.8rem;
            word-break: break-all;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Page Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminFacebookPages') }}">Facebook Pages</a></li>
                <li class="breadcrumb-item active">{{ $page->name }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Page Header Info -->
        <div class="col-md-12 mb-4">
            <div class="card page-header-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title text-white mb-1">{{ $page->name }}</h2>
                            <p class="text-white-50 mb-0">Page ID: {{ $page->page_id }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if ($page->is_active)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-danger fs-6">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Details -->
        <div class="col-md-8">
            <div class="card info-card">
                <div class="card-header">
                    <h3 class="card-title">Page Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page ID</label>
                                <p class="form-control-plaintext">{{ $page->page_id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Page Name</label>
                                <p class="form-control-plaintext">{{ $page->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <p class="form-control-plaintext">
                                    @if ($page->category)
                                        <span class="badge bg-info category-badge">{{ $page->category }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    @if ($page->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($page->category_list && count($page->category_list) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category List</label>
                            <div>
                                @foreach ($page->category_list as $category)
                                    <span class="badge bg-secondary me-1 mb-1">{{ $category['name'] }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($page->tasks && count($page->tasks) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Available Tasks</label>
                            <div>
                                @foreach ($page->tasks as $task)
                                    <span class="badge bg-primary task-badge">{{ $task }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Access Token & Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Access Token</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Page Access Token</label>
                        <div class="input-group">
                            <input type="password" class="form-control access-token" id="accessToken"
                                value="{{ $page->access_token }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleTokenVisibility()">
                                <i class="fe fe-eye" id="tokenIcon"></i>
                            </button>
                            <button class="btn btn-outline-primary" type="button" onclick="copyToken()">
                                <i class="fe fe-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('adminFacebookPageToggleStatus', $page->id) }}"
                            class="btn {{ $page->is_active ? 'btn-warning' : 'btn-success' }}"
                            onclick="return confirm('Are you sure you want to {{ $page->is_active ? 'deactivate' : 'activate' }} this page?')">
                            <i class="fe fe-{{ $page->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $page->is_active ? 'Deactivate' : 'Activate' }} Page
                        </a>

                        <a href="{{ route('adminFacebookPageDelete', $page->id) }}" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">
                            <i class="fe fe-trash-2 me-2"></i>
                            Delete Page
                        </a>

                        <a href="{{ route('adminFacebookPages') }}" class="btn btn-secondary">
                            <i class="fe fe-arrow-left me-2"></i>
                            Back to Pages
                        </a>
                    </div>
                </div>
            </div>
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
                                <p class="form-control-plaintext">{{ $page->created_at->format('F d, Y \a\t H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="form-control-plaintext">{{ $page->updated_at->format('F d, Y \a\t H:i:s') }}</p>
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
        function toggleTokenVisibility() {
            const tokenInput = document.getElementById('accessToken');
            const tokenIcon = document.getElementById('tokenIcon');

            if (tokenInput.type === 'password') {
                tokenInput.type = 'text';
                tokenIcon.classList.remove('fe-eye');
                tokenIcon.classList.add('fe-eye-off');
            } else {
                tokenInput.type = 'password';
                tokenIcon.classList.remove('fe-eye-off');
                tokenIcon.classList.add('fe-eye');
            }
        }

        function copyToken() {
            const tokenInput = document.getElementById('accessToken');

            // Select the text in the input field
            tokenInput.select();
            tokenInput.setSelectionRange(0, 99999); // For mobile devices

            try {
                // Copy the text to clipboard
                document.execCommand('copy');
                showToast('success', 'Access token copied to clipboard!');
            } catch (err) {
                // Fallback for modern browsers
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(tokenInput.value).then(() => {
                        showToast('success', 'Access token copied to clipboard!');
                    }).catch(() => {
                        showToast('error', 'Failed to copy to clipboard');
                    });
                } else {
                    showToast('error', 'Copy not supported in this browser');
                }
            }
        }
    </script>
@endsection
