@extends('backend.layouts.master')
@section('title', 'Facebook Lead Generation Forms')
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

        .form-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .locale-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        .page-name {
            font-weight: 500;
            color: #6c757d;
        }

        .form-name {
            font-weight: 600;
            color: #212529;
        }

        .sync-page-btn {
            position: relative;
        }

        .sync-page-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .sync-page-btn.loading::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
        }

        .dropdown-menu .sync-page-btn {
            width: 100%;
            text-align: left;
            border: none;
            background: none;
            padding: 0.5rem 1rem;
            color: #212529;
        }

        .dropdown-menu .sync-page-btn:hover {
            background-color: #f8f9fa;
            color: #212529;
        }

        .dropdown-menu .sync-page-btn:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Lead Generation Forms</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Lead Generation Forms</li>
            </ol>
        </div>
    </div>

    <!-- Sync Buttons -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Sync Lead Generation Forms</h5>
                            <p class="text-muted mb-0">Fetch and sync lead generation forms from your Facebook pages</p>
                        </div>
                        <div class="d-flex gap-2">
                            @if ($pages->count() > 0)
                                <form action="{{ route('adminFacebookLeadgenFormsSync') }}" method="POST" id="syncAllForm"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary sync-btn" id="syncAllBtn">
                                        <i class="fe fe-refresh-cw me-2"></i>Sync All Pages
                                    </button>
                                </form>

                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="syncPageDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fe fe-target me-2"></i>Sync Specific Page
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="syncPageDropdown">
                                        @foreach ($pages as $page)
                                            <li>
                                                <button type="button" class="dropdown-item sync-page-btn"
                                                    data-page-id="{{ $page->page_id }}"
                                                    data-page-name="{{ $page->name }}">
                                                    <i class="fe fe-refresh-cw me-2"></i>{{ $page->name }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fe fe-alert-triangle me-2"></i>
                                    No Facebook pages found. Please sync Facebook pages first.
                                </div>
                            @endif
                        </div>
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
                    <form method="GET" action="{{ route('adminFacebookLeadgenForms') }}">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Form ID</label>
                                    <input type="text" name="form_id" class="form-control"
                                        value="{{ request('form_id') }}" placeholder="Search by Form ID">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Form Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}"
                                        placeholder="Search by Name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Page</label>
                                    <select name="page_id" class="form-control">
                                        <option value="">All Pages</option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page->page_id }}"
                                                {{ request('page_id') == $page->page_id ? 'selected' : '' }}>
                                                {{ $page->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}"
                                                {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst(strtolower($status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Locale</label>
                                    <select name="locale" class="form-control">
                                        <option value="">All Locales</option>
                                        @foreach ($locales as $locale)
                                            <option value="{{ $locale }}"
                                                {{ request('locale') == $locale ? 'selected' : '' }}>
                                                {{ $locale }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Active Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="">All</option>
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('adminFacebookLeadgenForms') }}" class="btn btn-secondary me-2">Reset</a>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Generation Forms ({{ $forms->total() }} total)</h3>
                </div>
                <div class="card-body">
                    @if ($forms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Form ID</th>
                                        <th>Form Name</th>
                                        <th>Page</th>
                                        <th>Locale</th>
                                        <th>Status</th>
                                        <th>Active</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forms as $form)
                                        <tr>
                                            <td>
                                                <code>{{ $form->form_id }}</code>
                                            </td>
                                            <td>
                                                <span class="form-name">{{ $form->name }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="page-name">{{ $form->facebookPage->name ?? 'Unknown Page' }}</span>
                                                <br>
                                                <small class="text-muted">{{ $form->page_id }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info locale-badge">{{ $form->locale }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $form->status_badge }} form-status">{{ $form->status_text }}</span>
                                            </td>
                                            <td>
                                                @if ($form->is_active)
                                                    <span class="badge bg-success form-status">Active</span>
                                                @else
                                                    <span class="badge bg-danger form-status">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ $form->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('adminFacebookLeadgenFormView', $form->id) }}"
                                                        class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-success sync-page-btn"
                                                        title="Sync Forms for This Page"
                                                        data-page-id="{{ $form->page_id }}"
                                                        data-page-name="{{ $form->facebookPage->name ?? 'Unknown Page' }}">
                                                        <i class="fe fe-refresh-cw"></i>
                                                    </button>
                                                    <a href="{{ route('adminFacebookLeadgenFormToggleStatus', $form->id) }}"
                                                        class="btn btn-sm {{ $form->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $form->is_active ? 'Deactivate' : 'Activate' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $form->is_active ? 'deactivate' : 'activate' }} this form?')">
                                                        <i class="fe fe-{{ $form->is_active ? 'pause' : 'play' }}"></i>
                                                    </a>
                                                    <a href="{{ route('adminFacebookLeadgenFormDelete', $form->id) }}"
                                                        class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this form? This action cannot be undone.')">
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
                            {{ $forms->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fe fe-file-text" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No Lead Generation Forms Found</h4>
                            <p class="text-muted">Click the "Sync All Pages" button above to fetch lead generation forms
                                from your Facebook pages.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            // Handle sync all button loading state
            $('#syncAllForm').on('submit', function() {
                const syncBtn = $('#syncAllBtn');
                syncBtn.addClass('loading');
                syncBtn.prop('disabled', true);
                syncBtn.html('<i class="fe fe-refresh-cw me-2"></i>Syncing All...');
            });

            // Handle individual page sync button loading state
            $('.sync-page-btn').on('click', function(e) {
                e.preventDefault();

                const pageId = $(this).data('page-id');
                const pageName = $(this).data('page-name');
                const btn = $(this);

                console.log('Sync button clicked for page:', pageId, pageName);

                // Add loading state to the specific button
                btn.addClass('loading');
                btn.prop('disabled', true);
                btn.html('<i class="fe fe-refresh-cw me-2"></i>Syncing...');

                // If it's a dropdown button, show loading state on dropdown button
                if (btn.hasClass('dropdown-item')) {
                    const dropdownBtn = $('#syncPageDropdown');
                    dropdownBtn.addClass('loading');
                    dropdownBtn.prop('disabled', true);
                    dropdownBtn.html('<i class="fe fe-refresh-cw me-2"></i>Syncing ' + pageName + '...');
                }

                // Create and submit form programmatically
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('adminFacebookLeadgenFormsSyncPage', ':pageId') }}'
                        .replace(':pageId', pageId)
                });

                // Add CSRF token
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));

                console.log('Submitting form to:', form.attr('action'));

                // Append form to body and submit
                $('body').append(form);
                form.submit();
            });

            // Auto-submit form on page load if there are query parameters
            @if (request()->hasAny(['form_id', 'name', 'page_id', 'status', 'locale', 'is_active']))
                // Form is already submitted, no need to do anything
            @endif
        });
    </script>
@endsection
