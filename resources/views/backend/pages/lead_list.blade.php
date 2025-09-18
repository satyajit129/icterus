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
    <style>
    .highlight-expression {
        background-color: #aecf9e !important; /* light yellow, change as needed */
        color: #333; /* optional text color */
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
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                        <th>Created</th>
                                        <th>Taken Action</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr @if($lead->expression !== null) class="highlight-expression" @endif>
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
                                                <small class="text-muted">{{ $lead->formatted_created_time }}</small>
                                                <br>
                                                <small class="text-muted">{{ $lead->lead_age }}</small>
                                            </td>
                                            <td>
                                            @php
                                                $expressions = [
                                                    1 => ['text' => 'Interested', 'class' => 'bg-success'],
                                                    2 => ['text' => 'Not Interested', 'class' => 'bg-danger'],
                                                    3 => ['text' => 'Converted', 'class' => 'bg-primary'],
                                                ];
                                                $expression = $lead->expression;
                                            @endphp

                                            @if($expression && isset($expressions[$expression]))
                                                <span class="badge {{ $expressions[$expression]['class'] }}">
                                                    {{ $expressions[$expression]['text'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>


                                            <td>
                                                <div class="btn-group" role="group" style="gap: 5px;">
                                                    <a href="{{ route('leadView', $lead->id) }}"
                                                        class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('leadEdit', $lead->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                        <i class="fe fe-edit"></i>
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
    <script>
        $(document).ready(function() {
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
