@extends('backend.layouts.master')

@section('title', 'Salary Expense ')

@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Salary Expense </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Salary Expense </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Filter Data</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminSalaryExpense') }}" method="GET">
                        @csrf
                        <div class="row">
                            {{-- First Row --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Month</label>
                                    <select id="payable_month" name="payable_month"
                                        class="form-control select2-show-search form-select" required>
                                        <option selected disabled>Select Month</option>
                                        @foreach ([
                                                'January' => 1,
                                                'February' => 2,
                                                'March' => 3,
                                                'April' => 4,
                                                'May' => 5,
                                                'June' => 6,
                                                'July' => 7,
                                                'August' => 8,
                                                'September' => 9,
                                                'October' => 10,
                                                'November' => 11,
                                                'December' => 12,
                                            ] as $name => $num)
                                            <option value="{{ $num }}" {{ request('payable_month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Year</label>
                                    <input type="text" class="form-control" name="payable_year"
                                        value="{{ request('payable_year') }}" placeholder="Enter Year">
                                </div>
                            </div>

                            {{-- Second Row --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Employee Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}"
                                        autocomplete="off" placeholder="Enter Employee Name">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Employee ID</label>
                                    <input type="text" name="employee_id" class="form-control"
                                        value="{{ request('employee_id') }}" autocomplete="off"
                                        placeholder="Enter Employee ID">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 float-end">
                            <a href="{{ route('adminSalaryExpense') }}" class="btn btn-secondary btn-sm">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Salary Expense Data</h3>
                    <a href="{{ route('adminSalaryExpenseCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add Salary
                            Expense</button>
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
                                            <th class="wd-15p border-bottom-0">P. Month</th>
                                            <th class="wd-15p border-bottom-0">P. Year</th>

                                            <th class="wd-15p border-bottom-0">Working Days</th>
                                            <th class="wd-15p border-bottom-0">G. Salary</th>
                                            <th class="wd-15p border-bottom-0">Bonus</th>
                                            <th class="wd-15p border-bottom-0">P .Amount</th>
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($salary_expenses as $salary_expense)
                                            <tr>
                                                <td>{{ $salary_expenses->firstItem() + $loop->index }}</td>
                                                <td>{{ $salary_expense->employee->id_number }}</td>
                                                <td>{{ $salary_expense->employee->name }}</td>
                                                <td>{{ App\UtilityFunction::getMonthName($salary_expense->payable_month) }}
                                                </td>
                                                <td>{{ $salary_expense->payable_year }}</td>
                                                <td>{{ $salary_expense->total_working_day }}</td>
                                                <td>{{ $salary_expense->employee->gross_salary }}</td>
                                                <td>{{ $salary_expense->festival_bonus ?? '-----' }}</td>
                                                <td>{{ $salary_expense->payable_amount }}</td>
                                                <td>
                                                    @if ($salary_expense->salary_status)
                                                        <button type="button"
                                                            class="btn btn-sm {{ $salary_expense->salary_status->badgeClass() }} open-status-modal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#salaryStatusModal"
                                                            data-id="{{ $salary_expense->id }}"
                                                            data-name="{{ $salary_expense->employee->name }}">
                                                            <i class="{{ $salary_expense->salary_status->icon() }}"></i>
                                                            {{ $salary_expense->salary_status->label() }}
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('adminSalaryExpenseCreateOrEdit', $salary_expense->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="fe fe-edit"></i></a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminSalaryExpenseDelete', ['id' => $salary_expense->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                    <a href="{{ route('adminSalaryExpenseView', $salary_expense->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="13">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $salary_expenses->links() }}
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
                    <p>Are you sure you want to delete this Salary Expense?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Status Modal -->
<div class="modal effect-scale" id="salaryStatusModal" tabindex="-1" aria-labelledby="salaryStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <form method="POST" action="{{ route('adminSalaryExpenseStatusUpdate') }}">
            @csrf
            <input type="hidden" name="salary_expense_id" id="salaryExpenseId">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Salary Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p id="modalEmployeeName" class="mb-3"></p>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="1" id="approveOption">
                        <label class="form-check-label" for="approveOption">Approve</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="2" id="disapproveOption">
                        <label class="form-check-label" for="disapproveOption">Disapprove</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
@section('custom_js')
    <!-- Moment.js -->
    <!-- jQuery UI Datepicker -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>

    <script>
        $(document).on('click', '.open-status-modal', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#salaryExpenseId').val(id);
            $('#modalEmployeeName').text("Change status for: " + name);
        });
    </script>

@endsection
