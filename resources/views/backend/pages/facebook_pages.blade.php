@extends('backend.layouts.master')
@section('title', 'Facebook Pages')
@section('custom_css')
    <style>
        .sync-btn {
            position: relative;
        }

        .sync-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .sync-btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            0% {
                transform: translateY(-50%) rotate(0deg);
            }

            100% {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        .page-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .category-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        .tasks-list {
            font-size: 0.7rem;
            max-height: 60px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Pages</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Facebook Pages</li>
            </ol>
        </div>
    </div>

    <!-- Sync Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Sync from Facebook API</h5>
                            <p class="text-muted mb-0">Fetch and sync all your Facebook pages from the Graph API</p>
                        </div>
                        <form action="{{ route('adminFacebookPagesSync') }}" method="POST" id="syncForm">
                            @csrf
                            <button type="submit" class="btn btn-primary sync-btn" id="syncBtn">
                                <i class="fe fe-refresh-cw me-2"></i>Sync Pages
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filters</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('adminFacebookPages') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Page ID</label>
                                    <input type="text" name="page_id" class="form-control"
                                        value="{{ request('page_id') }}" placeholder="Search by Page ID">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Page Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}"
                                        placeholder="Search by Page Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-control select2-show-search form-select">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('adminFacebookPages') }}" class="btn btn-secondary me-2">Reset</a>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pages List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Facebook Pages ({{ $pages->total() }} total)</h3>
                </div>
                <div class="card-body">
                    @if ($pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Page ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Tasks</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pages as $page)
                                        <tr>
                                            <td>
                                                <strong>{{ $page->page_id }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $page->name }}</strong>
                                            </td>
                                            <td>
                                                @if ($page->category)
                                                    <span class="badge bg-info category-badge">{{ $page->category }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($page->tasks && count($page->tasks) > 0)
                                                    <div class="tasks-list">
                                                        @foreach ($page->tasks as $task)
                                                            <span
                                                                class="badge bg-secondary me-1 mb-1">{{ $task }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($page->is_active)
                                                    <span class="badge bg-success page-status">Active</span>
                                                @else
                                                    <span class="badge bg-danger page-status">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ $page->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" style="gap: 5px;">
                                                    <a href="{{ route('adminFacebookPageView', $page->id) }}"
                                                        class="btn btn-sm btn-info mr-1" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('adminFacebookPageToggleStatus', $page->id) }}"
                                                        class="btn mr-1 btn-sm {{ $page->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $page->is_active ? 'Deactivate' : 'Activate' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $page->is_active ? 'deactivate' : 'activate' }} this page?')">
                                                        <i class="fe fe-{{ $page->is_active ? 'pause' : 'play' }}"></i>
                                                    </a>
                                                    <a href="{{ route('adminFacebookPageDelete', $page->id) }}"
                                                        class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $pages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fe fe-facebook" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No Facebook Pages Found</h4>
                            <p class="text-muted">Click the "Sync Pages" button above to fetch your Facebook pages from the
                                API.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
<script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Handle sync button loading state
            $('#syncForm').on('submit', function() {
                const syncBtn = $('#syncBtn');
                syncBtn.addClass('loading');
                syncBtn.prop('disabled', true);
                syncBtn.html('<i class="fe fe-refresh-cw me-2"></i>Syncing...');
            });

            // Auto-submit form on page load if there are query parameters
            @if (request()->hasAny(['page_id', 'name', 'category', 'is_active']))
                // Form is already submitted, no need to do anything
            @endif
        });
    </script>
@endsection
