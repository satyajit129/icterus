@extends('backend.layouts.master')

@section('title', 'Loan List')

@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection
@php
    $user = auth()->user();
    $canAddLoan    =  $user->hasPermission('add_loan');
    $canEditLoan   =  $user->hasPermission('edit_loan');
    $canDeleteLoan =  $user->hasPermission('delete_loan');
    $canViewLoanDetails   =  $user->hasPermission('view_loan_details');
    $canAddLoanPayment =  $user->hasPermission('add_loan_payment');
@endphp
@section('content')
    <div class="page-header">
        <h1 class="page-title">Loan List</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Loan List</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Loan List</h3>
                    @if ($canAddLoan)
                        <a href="{{ route('adminLoanCreateOrEdit') }}">
                            <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add New
                                Loan</button>
                        </a>
                    @endif
                    
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee Id</th>
                                            <th>Employee Name</th>
                                            <th>Amount</th>
                                            <th>Paid / Due</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th>Add Payment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($loans as $loan)
                                            <tr>
                                                <td>{{ $loans->firstItem() + $loop->index }}</td>
                                                <td>{{ $loan->employee->id_number }}</td>
                                                <td>{{ $loan->employee->name }}</td>
                                                <td>{{ $loan->amount }}</td>
                                                @php
                                                    $paid = $loan->loanPayment->sum('amount');
                                                    $remaining = $loan->amount - $paid;
                                                @endphp

                                                <td>Paid: {{ number_format($paid, 2) }} <br> Due:
                                                    {{ number_format($remaining, 2) }}</td>

                                                <td>{{ \Carbon\Carbon::parse($loan->date)->format('d/m/Y') }}</td>
                                                <td>
                                                    @if ($canViewLoanDetails)
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-sm btn-info view-payments-btn"
                                                            data-loan-id="{{ $loan->id }}" title="View Payment">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted fst-italic">No actions</span>
                                                    @endif
                                                    
                                                </td>

                                                <td>
                                                    @if ($canViewLoanDetails)
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-sm btn-info add-payment-btn" title="Add payment"
                                                            data-bs-toggle="modal" data-bs-target="#paymentModal"
                                                            data-loan-id="{{ $loan->id }}"
                                                            data-employee-name="{{ $loan->employee->name }}">
                                                            <i class="fe fe-plus"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted fst-italic">No actions</span>
                                                    @endif
                                                    
                                                </td>

                                                <td>
                                                    @if ($loan->status == 1)
                                                        <span class="badge bg-danger badge-sm  me-1 mb-1 mt-1">Due</span>
                                                    @elseif ($loan->status == 2)
                                                        <span class="badge bg-primary badge-sm  me-1 mb-1 mt-1">Paid</span>
                                                    @else
                                                        <span class="badge bg-secondary">Unknown</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($canEditLoan || $canDeleteLoan)
                                                        @if ($canEditLoan)
                                                            <a href="{{ route('adminLoanCreateOrEdit', $loan->id) }}"
                                                                class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fe fe-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if ($canDeleteLoan)
                                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                                data-url="{{ route('adminLoanDelete', ['id' => $loan->id]) }}"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                                <i class="fe fe-trash-2"></i>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="text-muted fst-italic">No actions available</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="8">No data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $loans->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title" id="deleteModalLabel">Delete Confirmation</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Employee?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal effect-scale" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('adminLoanMakePayment') }}" method="POST">
                @csrf
                <input type="hidden" name="loan_id" id="modal_loan_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Add Loan Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Employee</label>
                            <input type="text" class="form-control" id="modal_employee_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="text" name="payment_date" class="form-control fc-datepicker"
                                placeholder="DD/MM/YYYY" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loan Payment Details Modal -->
    <div class="modal effect-scale" id="loanPaymentDetailsModal" tabindex="-1"
        aria-labelledby="loanPaymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loan Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody id="payment-details-body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="loanPaymentDetailsModalEdit" tabindex="-1"
        aria-labelledby="loanPaymentDetailsModalLabelEdit" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loan Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="payment-details-body-edit">
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('custom_js')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
    <script>
        $(document).on('click', '.add-payment-btn', function() {
            let loanId = $(this).data('loan-id');
            let employeeName = $(this).data('employee-name');

            $('#modal_loan_id').val(loanId);
            $('#modal_employee_name').val(employeeName);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.view-payments-btn').on('click', function() {
                const loanId = $(this).data('loan-id');

                $.ajax({
                    url: "{{ route('adminLoanPaymentDetails') }}",
                    data: {
                        loan_id: loanId,
                    },
                    type: 'GET',
                    success: function(response) {
                        $('#payment-details-body').html(response);
                        $('#loanPaymentDetailsModal').modal('show');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('click', '.edit-payment-btn', function () {
            const paymentId = $(this).data('id');
            console.log(paymentId);

            $.ajax({
                url: "{{ route('adminLoanPaymentFetch') }}",
                type: 'GET',
                data: {
                    id: paymentId
                },
                success: function (response) {
                    $('#payment-details-body-edit').html(response);
                    $('#loanPaymentDetailsModalEdit').modal('show');
                },
                error: function (xhr) {
                    console.error("Error fetching payment details:", xhr.responseText);
                }
            });
        });
    </script>

@endsection
