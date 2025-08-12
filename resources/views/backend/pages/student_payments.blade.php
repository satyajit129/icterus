@extends('backend.layouts.master')

@section('title', 'Student Payment')

@section('custom_css')
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Student Payment</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminStudentList') }}">Student List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student Payment</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Student Payment Data</h3>
                    <div>
                        <a href="{{ route('adminStudentPaymentCreateOrEdit', ['student_id' => $student_id]) }}">
                            <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add Payment</button>
                        </a>
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
                                            <th class="wd-15p border-bottom-0">Trnx Method</th>
                                            <th class="wd-15p border-bottom-0">Trnx Id</th>
                                            <th class="wd-15p border-bottom-0">Amount</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($payments as $index => $payment)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $payment->student->name ?? 'N/A' }}</td>
                                                <td>{{ \App\Enum\TrnxMethod::label($payment->trnx_method) }}</td>
                                                <td>{{ $payment->trnx_id }}</td>
                                                <td>{{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <a href="{{ route('adminStudentPaymentCreateOrEdit', ['student_id' => $student_id, 'payment_id' => $payment->id]) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No Data found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
                    <p>Are you sure you want to delete this Data?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom_js')
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
@endsection
