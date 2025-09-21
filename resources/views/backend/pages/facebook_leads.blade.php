@extends('backend.layouts.master')
@section('title', 'Facebook Leads')
@section('custom_css')
    <style>
        .collect-btn {
            position: relative;
        }

        .collect-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .collect-btn.loading::after {
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

        .lead-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .field-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            margin: 0.1rem;
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

        /* Full page loader styles */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .page-loader.show {
            display: flex !important;
        }

        .loader-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            width: 90%;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        .loader-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            margin: 0;
        }

        .loader-subtext {
            font-size: 0.9rem;
            color: #666;
            margin: 0.5rem 0 0;
        }
    </style>
@endsection

@section('content')
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <p class="loader-text">Collecting Leads...</p>
            <p class="loader-subtext">Please wait while we fetch all leads from Facebook</p>
        </div>
    </div>

    <div class="page-header">
        <h1 class="page-title">Facebook Leads</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Facebook Leads</li>
            </ol>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-gradient-primary text-dark">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fe fe-bar-chart-2 me-2 text-dark"></i>Lead Assignment Summary
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

    <!-- Collect Leads Section -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Collect Leads from Forms</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminFacebookLeadsCollect') }}" method="POST" id="collectLeadsForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Form <span class="text-danger">*</span></label>
                                    <select name="form_id" id="formSelect" class="form-control" required>
                                        <option value="">Choose a form...</option>
                                        @foreach ($forms as $form)
                                            @php
                                                $leadCount = \App\Models\FacebookLead::where(
                                                    'form_id',
                                                    $form->form_id,
                                                )->count();
                                            @endphp
                                            <option value="{{ $form->form_id }}"
                                                data-page-name="{{ $form->facebookPage->name ?? 'Unknown Page' }}"
                                                data-lead-count="{{ $leadCount }}">
                                                {{ $form->name }} ({{ $form->facebookPage->name ?? 'Unknown Page' }}) -
                                                {{ $leadCount }} leads
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary collect-btn w-100" id="collectBtn"
                                        onclick="handleCollectLeads();">
                                        <i class="fe fe-download me-2"></i>Collect Leads
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info mb-0">
                                    <i class="fe fe-info me-2"></i>
                                    <strong>Note:</strong> Leave date fields empty to collect all leads from the selected
                                    form.
                                </div>
                            </div>
                        </div>

                        <!-- Lead Count Display -->
                        <div class="row mt-3" id="leadStatsRow" style="display: none;">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">Current Lead Statistics</h6>
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="border-end">
                                                    <h4 class="text-primary mb-1" id="totalLeads">0</h4>
                                                    <small class="text-muted">Total Leads</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border-end">
                                                    <h4 class="text-success mb-1" id="processedLeads">0</h4>
                                                    <small class="text-muted">Processed</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border-end">
                                                    <h4 class="text-warning mb-1" id="unprocessedLeads">0</h4>
                                                    <small class="text-muted">Unprocessed</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div>
                                                    <h4 class="text-info mb-1" id="formName">-</h4>
                                                    <small class="text-muted">Selected Form</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                    <form method="GET" action="{{ route('adminFacebookLeads') }}">
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date Range</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="date" name="start_date"
                                                    class="form-control "
                                                    value="{{ request('start_date') }}" placeholder="Start Date">
                                            </div>
                                            <div class="col-6">
                                                <input type="date" name="end_date"
                                                    class="form-control "
                                                    value="{{ request('end_date') }}" placeholder="End Date">
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">Filter</button>
                                        <a href="{{ route('adminFacebookLeads') }}" class="btn btn-secondary" style="width: 100%;">Reset</a>
                                        <a href="{{ route('adminFacebookLeadsExport', request()->query()) }}"
                                            class="btn btn-success" style="width: 100%;">
                                            <i class="fe fe-download me-1"></i>Export CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                                        <th>Lead ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                        <th>Form</th>
                                        <th>Page</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr>
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
                                                    <span class="badge bg-success lead-status">Processed</span>
                                                @else
                                                    <span class="badge bg-warning lead-status">Unprocessed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" style="gap: 5px;">
                                                    <a href="{{ route('adminFacebookLeadView', $lead->id) }}"
                                                        class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('adminFacebookLeadToggleStatus', $lead->id) }}"
                                                        class="btn btn-sm {{ $lead->is_processed ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $lead->is_processed ? 'Mark as Unprocessed' : 'Mark as Processed' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $lead->is_processed ? 'mark as unprocessed' : 'mark as processed' }} this lead?')">
                                                        <i
                                                            class="fe fe-{{ $lead->is_processed ? 'refresh-cw' : 'check' }}"></i>
                                                    </a>
                                                    <a href="{{ route('adminFacebookLeadDelete', $lead->id) }}"
                                                        class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this lead? This action cannot be undone.')">
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
                            {{ $leads->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fe fe-users" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No Leads Found</h4>
                            <p class="text-muted">Use the "Collect Leads" section above to fetch leads from your Facebook
                                forms.</p>
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
            // Hide loader on page load (in case of refresh or navigation)
            $('#pageLoader').removeClass('show');

            // Initialize Select2 for form selection
            $('#formSelect').select2({
                placeholder: 'Choose a form...',
                allowClear: true,
                width: '100%'
            });


            // Handle optimized collection button
            $('#collectOptimizedBtn').on('click', function(e) {
                e.preventDefault();

                const formId = $('#formSelect').val();
                if (!formId) {
                    toastr.error('Please select a form first');
                    return;
                }

                // Confirm action
                if (!confirm(
                        'This will collect ALL leads from the selected form. This may take several minutes for large datasets. Continue?'
                    )) {
                    return;
                }

                // Show loading state
                $(this).prop('disabled', true).html('<i class="fe fe-loader me-2"></i>Collecting All...');

                // Create form data
                const formData = new FormData();
                formData.append('form_id', formId);
                formData.append('start_date', $('input[name="start_date"]').val());
                formData.append('end_date', $('input[name="end_date"]').val());
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Submit optimized collection
                $.ajax({
                    url: '{{ route('adminFacebookLeadsCollectOptimized') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Reload page to show results
                        window.location.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Error collecting leads: ' + (xhr.responseJSON?.message ||
                            'Unknown error'));
                        $('#collectOptimizedBtn').prop('disabled', false).html(
                            '<i class="fe fe-rocket me-2"></i>Collect All (Optimized)');
                    }
                });
            });

            // Handle field name change to load field values
            $('#fieldNameSelect').on('change', function() {
                const fieldName = $(this).val();
                const fieldValueInput = $('input[name="field_value"]');

                if (fieldName) {
                    // You can implement AJAX call here to load field values
                    // For now, just clear the input
                    fieldValueInput.val('');
                }
            });

            // Handle form selection change to show lead statistics
            $('#formSelect').on('change', function() {
                const formId = $(this).val();
                const selectedOption = $(this).find('option:selected');
                const formName = selectedOption.text().split(' - ')[0];
                const leadCount = selectedOption.data('lead-count') || 0;

                if (formId) {
                    // Show the stats row
                    $('#leadStatsRow').show();
                    $('#formName').text(formName);
                    $('#totalLeads').text(leadCount);

                    // Fetch detailed stats via AJAX
                    $.get('{{ route('adminFacebookLeadsFormStats', ':formId') }}'.replace(':formId',
                            formId))
                        .done(function(data) {
                            $('#totalLeads').text(data.total);
                            $('#processedLeads').text(data.processed);
                            $('#unprocessedLeads').text(data.unprocessed);
                        })
                        .fail(function() {
                            console.log('Failed to fetch lead statistics');
                        });
                } else {
                    // Hide the stats row
                    $('#leadStatsRow').hide();
                }
            });

            // Auto-submit form on page load if there are query parameters
            @if (request()->hasAny([
                    'form_id',
                    'page_id',
                    'field_name',
                    'field_value',
                    'is_processed',
                    'search',
                    'start_date',
                    'end_date',
                ]))
                // Form is already submitted, no need to do anything
            @endif
        });

        // Global function for collect leads
        window.handleCollectLeads = function() {
            // Validate form
            const formId = document.getElementById('formSelect').value;
            if (!formId) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Please select a form first');
                } else {
                    alert('Please select a form first');
                }
                return;
            }

            const collectBtn = document.getElementById('collectBtn');
            collectBtn.classList.add('loading');
            collectBtn.disabled = true;
            collectBtn.innerHTML = '<i class="fe fe-download me-2"></i>Collecting...';

            // Show full page loader immediately
            const pageLoader = document.getElementById('pageLoader');
            pageLoader.classList.add('show');

            // Add a small delay to ensure loader is visible, then submit
            setTimeout(function() {
                document.getElementById('collectLeadsForm').submit();
            }, 100);
        };
    </script>
@endsection
