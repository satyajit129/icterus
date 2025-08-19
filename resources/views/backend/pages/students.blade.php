@extends('backend.layouts.master')

@section('title', 'Students')

@section('custom_css')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .table td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@php
    $user = auth()->user();
    $canAddStudent    =  $user->hasPermission('add_student');
    $canEditStudent   =  $user->hasPermission('edit_student');
    $canDeleteStudent =  $user->hasPermission('delete_student');
    $canViewStudentPayment   =  $user->hasPermission('view_student_payment');
@endphp
@section('content')

    <div class="page-header">
        <h1 class="page-title">Students</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Students Data</h3>
                    <div>
                        @if ($canAddStudent)
                            <a href="{{ route('adminStudentCreateOrEdit') }}">
                                <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add Student</button>
                            </a>
                        @endif
                    </div>
                    
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">#</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Email</th>
                                            <th class="wd-15p border-bottom-0">Phone</th>
                                            <th class="wd-15p border-bottom-0">Courses</th>
                                            <th class="wd-15p border-bottom-0">Amount</th>
                                            <th class="wd-15p border-bottom-0">Paid / Due</th>
                                            <th class="wd-15p border-bottom-0">Payment</th>
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">E. Date</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $index => $student)
                                            <tr>
                                                <td>{{ $students->firstItem() + $loop->index }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->phone }}</td>
                                                <td>{{ $student->courses }}</td>
                                                <td>{{ $student->amount }}</td>
                                                @php
                                                    $paid = $student->payments->sum('amount');
                                                    $remaining = $student->amount - $paid;
                                                @endphp

                                                <td>Paid: {{ number_format($paid, 2) }} <br> Due:
                                                    {{ number_format($remaining, 2) }}</td>
                                                <td>
                                                    @if ($canViewStudentPayment)
                                                        <a href="{{ route('adminStudentPaymentList', $student->id) }}"
                                                            class="btn btn-sm btn-info" title="Payment List">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted fst-italic">No actions</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($student->status == 1)
                                                        <span class="badge bg-danger badge-sm  me-1 mb-1 mt-1">Due</span>
                                                    @elseif ($student->status == 2)
                                                        <span class="badge bg-primary badge-sm  me-1 mb-1 mt-1">Paid</span>
                                                    @else
                                                        <span class="badge bg-secondary">Unknown</span>
                                                    @endif
                                                </td>
                                                
                                                <td>{{ \Carbon\Carbon::parse($student->enroll_date)->format('d/m/Y') }}</td>
                                                <td>
                                                    @if ($canEditStudent || $canDeleteStudent)
                                                        <a href="{{ route('adminStudentCreateOrEdit', $student->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminStudentDelete', ['id' => $student->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                    @else
                                                        <span class="text-muted fst-italic">No actions available</span>
                                                    @endif
                                                    
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-muted">No Student found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $students->links() }}
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
                    <p>Are you sure you want to delete this Student?</p>
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
                <input type="hidden" name="loan_id" id="modal_student_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Add Course Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Student</label>
                            <input type="text" class="form-control" id="modal_student_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="text" name="payment_date" class="form-control fc-datepicker"
                                placeholder="DD-MM-YYYY" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('custom_js')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
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
            let studentId = $(this).data('student-id');
            let studentName = $(this).data('student-name');

            $('#modal_student_id').val(studentId);
            $('#modal_student_name').val(studentName);
        });
    </script>
        <script>
        $(function() {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
@endsection
