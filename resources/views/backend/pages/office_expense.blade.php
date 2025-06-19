@extends('backend.layouts.master')

@section('title', 'Office Expense')

@section('custom_css')
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Office Expense</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Office Expense</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Office Expense Data</h3>
                    <a href="{{ route('adminOfficeExpenseCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add Office Expense</button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">#</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Purpose</th>
                                            <th class="wd-15p border-bottom-0">Quantity</th>
                                            <th class="wd-15p border-bottom-0">Details</th>
                                            <th class="wd-15p border-bottom-0">Amount</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($office_expenses as $office_expense)
                                            <tr>
                                                <td>{{ $office_expenses->firstItem() + $loop->index }}</td>
                                                <td>{{ \Carbon\Carbon::parse($office_expense->date)->format('d-m-Y') }}</td>
                                                <td>{{ $office_expense->purpose }}</td>
                                                <td>{{ isset($office_expense->quantity) ? $office_expense->quantity : '----' }}</td>
                                                <td>{{ $office_expense->details }}</td>
                                                <td>{{ number_format($office_expense->amount, 2) }}</td>
                                                <td>
                                                   <a href="{{ route('adminOfficeExpenseCreateOrEdit', $office_expense->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="fe fe-edit"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminOfficeExpenseDelete', ['id' => $office_expense->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fe fe-trash"></i>
                                                    </a>
                                                    <a href="{{ route('adminOfficeExpenseView', $office_expense->id) }}"
                                                        class="btn btn-sm btn-info"><i class="fe fe-eye"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $office_expenses->links() }}
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
                    <p>Are you sure you want to delete this Office Expense?</p>
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
        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
@endsection
