@extends('backend.layouts.master')

@section('title', 'Incentive Expense')

@section('custom_css')
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Incentive Expense</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Incentive Expense</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Incentive Expense Data</h3>
                    <a href="{{ route('adminIncentiveExpenseCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add Incentive Expense</button>
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
                                            <th class="wd-15p border-bottom-0">ID Number</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Month</th>
                                            <th class="wd-15p border-bottom-0">Sales Count</th>
                                            <th class="wd-15p border-bottom-0">Sales Amount</th>
                                            <th class="wd-15p border-bottom-0">Incentive Amount</th>
                                            <th class="wd-15p border-bottom-0">Payable Amount</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($incentive_expenses as $incentive_expense)
                                            <tr>
                                                <td>{{ $incentive_expenses->firstItem() + $loop->index }}</td>
                                                <td>{{ $incentive_expense->employee->id_number }}</td>
                                                <td>{{ $incentive_expense->employee->name }}</td>
                                               <td>{{ \Carbon\Carbon::parse($incentive_expense->payable_month)->format('M - Y') }}</td>
                                                <td>{{ $incentive_expense->sales_count }}</td>
                                                <td>{{ ceil($incentive_expense->sales_amount) }}</td>
                                                <td>{{ ceil($incentive_expense->incentive_amount) }}</td>
                                                <td>{{ ceil($incentive_expense->payable_amount) }}</td>
                                                <td>
                                                    <a href="{{ route('adminIncentiveExpenseCreateOrEdit', $incentive_expense->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="fe fe-edit"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminIncentiveExpenseDelete', ['id' => $incentive_expense->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fe fe-trash"></i>
                                                    </a>
                                                    <a href="{{ route('adminIncentiveExpenseView', $incentive_expense->id) }}"
                                                        class="btn btn-sm btn-info"><i class="fe fe-eye"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="13">No Data Found</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                 {{ $incentive_expenses->links() }}
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
                    <p>Are you sure you want to delete this Incentive Expense?</p>
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
