@extends('backend.layouts.master')
@section('title', 'Assign Leads')
@section('custom_css')
    <style>
        .assignment-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .lead-info {
            font-size: 0.9rem;
        }

        .form-name {
            font-weight: 500;
            color: #6c757d;
        }

        .lead-name {
            font-weight: 600;
            color: #212529;
        }

        .select2-container {
            width: 100% !important;
        }

        .date-range-picker {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
        }

        .bulk-assign-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .bulk-assign-section h6 {
            margin-bottom: 0.75rem;
            color: #495057;
        }

        .lead-checkbox {
            margin-right: 0.5rem;
        }

        .assigned-user {
            font-size: 0.8rem;
            color: #28a745;
            font-weight: 500;
        }

        .unassigned {
            color: #6c757d;
            font-style: italic;
        }

        .bulk-actions {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1rem;
        }

        .pagination-info {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Assign Leads</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Assign Leads</li>
            </ol>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-gradient-primary text-dark">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fe fe-bar-chart-2 me-2"></i>Lead Assignment Summary
                    </h5>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border-end border-light">
                                <h2 class="text-dark mb-1">{{ number_format($totalLeads) }}</h2>
                                <small class="text-dark-50">Total Leads</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end border-light">
                                <h2 class="text-success mb-1">{{ number_format($assignedLeads) }}</h2>
                                <small class="text-dark-50">Leads Assigned</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <h2 class="text-warning mb-1">{{ number_format($unassignedLeads) }}</h2>
                                <small class="text-dark-50">Leads Not Assigned Yet</small>
                            </div>
                        </div>
                    </div>
                    @if ($totalLeads > 0)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ ($assignedLeads / $totalLeads) * 100 }}%"
                                        aria-valuenow="{{ $assignedLeads }}" aria-valuemin="0"
                                        aria-valuemax="{{ $totalLeads }}">
                                    </div>
                                </div>
                                <small class="text-white-50 mt-1 d-block">
                                    {{ number_format(($assignedLeads / $totalLeads) * 100, 1) }}% of leads are assigned
                                </small>
                            </div>
                        </div>
                    @endif
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
                    <form method="GET" action="{{ route('adminAssignLeads') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Form</label>
                                    <select name="form_id" class="form-control select2-show-search form-select">
                                        <option value="">All Forms</option>
                                        @foreach ($forms as $form)
                                            <option value="{{ $form->form_id }}"
                                                {{ request('form_id') == $form->form_id ? 'selected' : '' }}>
                                                {{ $form->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Page</label>
                                    <select name="page_id" class="form-control select2-show-search form-select">
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <select name="location" class="form-control select2-show-search form-select">
                                        <option value="">All Locations</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location }}"
                                                {{ request('location') == $location ? 'selected' : '' }}>
                                                {{ $location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Assignment Status</label>
                                    <select name="assignment_status" class="form-control">
                                        <option value="">All</option>
                                        <option value="assigned"
                                            {{ request('assignment_status') == 'assigned' ? 'selected' : '' }}>
                                            Assigned</option>
                                        <option value="unassigned"
                                            {{ request('assignment_status') == 'unassigned' ? 'selected' : '' }}>
                                            Unassigned</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Date Range</label>
                                   
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="date" name="start_date" class="form-control"
                                                    value="{{ request('start_date') }}" placeholder="Start Date">
                                            </div>
                                            <div class="col-6">
                                                <input type="date" name="end_date"
                                                    class="form-control"
                                                    value="{{ request('end_date') }}" placeholder="End Date">
                                            </div>
                                        </div>
                                  
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_processed" class="form-control">
                                        <option value="">All</option>
                                        <option value="1" {{ request('is_processed') == '1' ? 'selected' : '' }}>
                                            Processed</option>
                                        <option value="0" {{ request('is_processed') == '0' ? 'selected' : '' }}>
                                            Unprocessed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control"
                                        value="{{ request('search') }}" placeholder="Search leads...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">Filter</button>
                                        <a href="{{ route('adminAssignLeads') }}" class="btn btn-secondary" style="width: 100%;">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Assignment Section -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="bulk-assign-section">
                <h6><i class="fe fe-users me-2"></i>Bulk Assignment Actions</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Select Student Advisor</label>
                            <select id="bulkAssignTo" class="form-control select2-show-search form-select">
                                <option value="">Choose Student Advisor (Optional)</option>
                                @foreach ($studentAdvisors as $advisor)
                                    <option value="{{ $advisor->id }}">{{ $advisor->name }} ({{ $advisor->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Assignment Type</label>
                            <select id="assignmentType" class="form-control select2-show-search form-select">
                                <option value="bulk">Bulk Assignment</option>
                                <option value="individual">Individual Assignment</option>
                            </select>
                        </div>
                    </div>
                    <d style="width: 100%;"iv class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success" id="bulkAssignBtn" disabled style="width: 100%;">
                                    <i class="fe fe-user-plus me-2"></i>Assign Selected Leads
                                </button>
                                <button type="button" class="btn btn-warning" id="unassignBtn" disabled style="width: 100%;">
                                    <i class="fe fe-user-minus me-2"></i>Unassign Selected
                                </button>
                            </div>
                        </div>
                    </d>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                    <i class="fe fe-check-square me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">
                                    <i class="fe fe-square me-1"></i>Deselect All
                                </button>
                            </div>
                            <div class="pagination-info">
                                <span id="selectedCount">0</span> leads selected
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leads ({{ $leads->total() }} total)</h3>
                </div>
                <div class="card-body">
                    @if ($leads->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllCheckbox" class="form-check">
                                        </th>
                                        <th>Lead ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                        <th>Form</th>
                                        <th>Page</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check lead-checkbox"
                                                    value="{{ $lead->id }}" data-lead-id="{{ $lead->id }}">
                                            </td>
                                            <td>
                                                <code>{{ $lead->lead_id }}</code>
                                            </td>
                                            <td>
                                                <span class="lead-name">{{ $lead->getFieldValue('name') ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="lead-info">{{ $lead->getFieldValue('phone') ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="lead-info">{{ $lead->getFieldValue('location') ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="form-name">{{ $lead->facebookLeadgenForm->name ?? 'Unknown Form' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="form-name">{{ $lead->facebookPage->name ?? 'Unknown Page' }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $lead->formatted_created_time }}</small>
                                                <br>
                                                <small class="text-muted">{{ $lead->lead_age }}</small>
                                            </td>
                                            <td>
                                                @if ($lead->is_processed)
                                                    <span class="badge bg-success assignment-status">
                                                        <i class="fe fe-check me-1"></i>Processed
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning assignment-status">
                                                        <i class="fe fe-clock me-1"></i>Unprocessed
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($lead->leadAssignment)
                                                    <span class="assigned-user">
                                                        <i class="fe fe-user me-1"></i>
                                                        {{ $lead->leadAssignment->assignedTo->name ?? 'Unknown' }}
                                                    </span>
                                                @else
                                                    <span class="unassigned">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="assignIndividual({{ $lead->id }})"
                                                        title="Assign Lead">
                                                        <i class="fe fe-user-plus"></i>
                                                    </button>
                                                    @if ($lead->leadAssignment)
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="unassignIndividual({{ $lead->id }})"
                                                            title="Unassign Lead">
                                                            <i class="fe fe-user-minus"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $leads->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fe fe-users" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No Leads Found</h4>
                            <p class="text-muted">No leads match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Assignment Modal -->
    <div class="modal fade" id="individualAssignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="individualAssignForm">
                    <div class="modal-body">
                        <input type="hidden" id="individualLeadId" name="lead_id">
                        <div class="mb-3">
                            <label class="form-label">Select Student Advisor <span class="text-danger">*</span></label>
                            <select name="assigned_to" id="individualAssignedTo" class="form-control" required>
                                <option value="">Choose Student Advisor</option>
                                @foreach ($studentAdvisors as $advisor)
                                    <option value="{{ $advisor->id }}">{{ $advisor->name }} ({{ $advisor->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" id="individualNotes" class="form-control" rows="3"
                                placeholder="Add any notes about this assignment..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            // Handle select all checkbox
            $('#selectAllCheckbox').on('change', function() {
                $('.lead-checkbox').prop('checked', this.checked);
                updateSelectedCount();
                updateBulkButtons();
            });

            // Handle individual checkboxes
            $('.lead-checkbox').on('change', function() {
                updateSelectedCount();
                updateBulkButtons();

                // Update select all checkbox state
                const totalCheckboxes = $('.lead-checkbox').length;
                const checkedCheckboxes = $('.lead-checkbox:checked').length;
                $('#selectAllCheckbox').prop('checked', totalCheckboxes === checkedCheckboxes);
            });

            // Select all button
            $('#selectAllBtn').on('click', function() {
                $('.lead-checkbox').prop('checked', true);
                $('#selectAllCheckbox').prop('checked', true);
                updateSelectedCount();
                updateBulkButtons();
            });

            // Deselect all button
            $('#deselectAllBtn').on('click', function() {
                $('.lead-checkbox').prop('checked', false);
                $('#selectAllCheckbox').prop('checked', false);
                updateSelectedCount();
                updateBulkButtons();
            });

            // Bulk assign button
            $('#bulkAssignBtn').on('click', function() {
                const selectedLeads = getSelectedLeads();
                if (selectedLeads.length === 0) {
                    toastr.error('Please select at least one lead to assign.');
                    return;
                }

                const assignedTo = $('#bulkAssignTo').val();
                const assignmentType = $('#assignmentType').val();

                if (!assignedTo && assignmentType === 'individual') {
                    toastr.error('Please select a Student Advisor for individual assignment.');
                    return;
                }

                // Confirm assignment
                let confirmMessage = `Are you sure you want to assign ${selectedLeads.length} leads?`;
                if (assignedTo) {
                    const advisorName = $('#bulkAssignTo option:selected').text();
                    confirmMessage =
                        `Are you sure you want to assign ${selectedLeads.length} leads to ${advisorName}?`;
                } else {
                    confirmMessage =
                        `Are you sure you want to distribute ${selectedLeads.length} leads equally among all Student Advisors?`;
                }

                if (!confirm(confirmMessage)) {
                    return;
                }

                // Submit bulk assignment
                $.ajax({
                    url: '{{ route('adminAssignLeadsBulk') }}',
                    type: 'POST',
                    data: {
                        lead_ids: selectedLeads,
                        assigned_to: assignedTo,
                        assignment_type: assignmentType,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Bulk assign response:', response);
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log('Bulk assign error:', xhr);
                        const response = xhr.responseJSON;
                        toastr.error(response?.message ||
                            'An error occurred while assigning leads.');
                    }
                });
            });

            // Unassign button
            $('#unassignBtn').on('click', function() {
                const selectedLeads = getSelectedLeads();
                if (selectedLeads.length === 0) {
                    toastr.error('Please select at least one lead to unassign.');
                    return;
                }

                if (!confirm(`Are you sure you want to unassign ${selectedLeads.length} leads?`)) {
                    return;
                }

                // Submit unassign
                $.ajax({
                    url: '{{ route('adminAssignLeadsUnassign') }}',
                    type: 'POST',
                    data: {
                        lead_ids: selectedLeads,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Unassign response:', response);
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log('Unassign error:', xhr);
                        const response = xhr.responseJSON;
                        toastr.error(response?.message ||
                            'An error occurred while unassigning leads.');
                    }
                });
            });

            // Individual assignment form
            $('#individualAssignForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('adminAssignLeadsIndividual') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Individual assign response:', response);
                        if (response.success) {
                            toastr.success(response.message);
                            $('#individualAssignModal').modal('hide');
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log('Individual assign error:', xhr);
                        const response = xhr.responseJSON;
                        toastr.error(response?.message ||
                            'An error occurred while assigning lead.');
                    }
                });
            });

            function getSelectedLeads() {
                return $('.lead-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            function updateSelectedCount() {
                const count = $('.lead-checkbox:checked').length;
                $('#selectedCount').text(count);
            }

            function updateBulkButtons() {
                const count = $('.lead-checkbox:checked').length;
                const hasSelection = count > 0;
                $('#bulkAssignBtn').prop('disabled', !hasSelection);
                $('#unassignBtn').prop('disabled', !hasSelection);
            }

            // Initialize
            updateSelectedCount();
            updateBulkButtons();
        });

        function assignIndividual(leadId) {
            $('#individualLeadId').val(leadId);
            $('#individualAssignedTo').val('');
            $('#individualNotes').val('');
            $('#individualAssignModal').modal('show');
        }

        function unassignIndividual(leadId) {
            if (!confirm('Are you sure you want to unassign this lead?')) {
                return;
            }

            $.ajax({
                url: '{{ route('adminAssignLeadsUnassign') }}',
                type: 'POST',
                data: {
                    lead_ids: [leadId],
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Individual unassign response:', response);
                    if (response.success) {
                        toastr.success(response.message);
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    console.log('Individual unassign error:', xhr);
                    const response = xhr.responseJSON;
                    toastr.error(response?.message || 'An error occurred while unassigning lead.');
                }
            });
        }
    </script>
@endsection
