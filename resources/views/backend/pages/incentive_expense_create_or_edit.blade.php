@extends('backend.layouts.master')

@section('title', isset($incentive_expense->id) ? 'Incentive Expense Edit' : 'Incentive Expense ')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($incentive_expense->id))
                Incentive Expense Edit
            @else
                Incentive Expense Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminIncentiveExpense') }}">Incentive Expense Data</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($incentive_expense->id))
                        Incentive Expense Edit
                    @else
                        Incentive Expense Create
                    @endif
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if (isset($incentive_expense->id))
                            Incentive Expense Edit
                        @else
                            Incentive Expense Create
                        @endif
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminIncentiveExpenseSave', $incentive_expense->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Employees</label>
                                        <div class="col-md-9">
                                            <select name="employee_id" id="employee_id"
                                                class="form-control select2-show-search form-select"
                                                data-placeholder="Choose one" required>
                                                <option label="Choose one"></option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->name }} ({{ $employee->id_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="selected_employee_id" id="selected_employee_id" value="{{ $incentive_expense->employee_id ?? '' }}">
                            </div>
                            <!-- Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="name" id="name" class="form-control"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Designation -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Designation</label>
                                        <div class="col-md-9">
                                            <input type="text" name="designation" id="designation" class="form-control"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Department -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Department</label>
                                        <div class="col-md-9">
                                            <input type="text" name="department" id="department" class="form-control"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Phone Number -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Phone Number</label>
                                        <div class="col-md-9">
                                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Account No -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Account Number</label>
                                        <div class="col-md-9">
                                            <input type="number" name="account_no" id="account_no" class="form-control"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payable Month -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Payable Month</label>
                                        <div class="col-md-9">
                                            <input type="month" class="form-control" name="payable_month" required
                                                value="{{ isset($incentive_expense->payable_month) ? \Carbon\Carbon::parse($incentive_expense->payable_month)->format('Y-m') : '' }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sell Count -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Sales Count</label>
                                        <div class="col-md-9">
                                            <input type="text" id="sales_count" name="sales_count"
                                                class="form-control" placeholder="e.g. 105" required
                                                value="{{ isset($incentive_expense->sales_count) ? ceil($incentive_expense->sales_count) : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Sales Amount</label>
                                        <div class="col-md-9">
                                            <input type="text" id="sales_amount" name="sales_amount"
                                                class="form-control" placeholder="e.g. 1000000" required
                                                value="{{ isset($incentive_expense->sales_amount) ? ceil($incentive_expense->sales_amount) : '' }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Incentive Amount</label>
                                        <div class="col-md-9">
                                            <input type="text" id="incentive_amount" name="incentive_amount"
                                                class="form-control" placeholder="e.g. 10000" required
                                                value="{{ isset($incentive_expense->incentive_amount) ? ceil($incentive_expense->incentive_amount) : '' }}">


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Payable Amount</label>
                                        <div class="col-md-9">
                                            <input type="text" id="payable_amount" name="payable_amount"
                                                class="form-control" placeholder="e.g. 1054536" required
                                                value="{{ isset($incentive_expense->payable_amount) ? ceil($incentive_expense->payable_amount) : '' }}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fe fe-upload me-2"></i>
                                    {{ isset($salary_expense->id) ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('custom_js')
    <!-- jQuery UI Datepicker -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            function loadEmployeeData(employeeId) {
                if (!employeeId) {
                    $('#name, #designation, #department, #phone_number, #gross_salary, #account_no').val('');
                    updatePayableAmount();
                    return;
                }

                $.ajax({
                    url: '{{ route('adminEmployeeData') }}',
                    data: {
                        employee_id: employeeId
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#name').val(data.data.name);
                        $('#designation').val(data.data.designation?.designation ?? '');
                        $('#department').val(data.data.department?.department ?? '');
                        $('#phone_number').val(data.data.phone_number);
                        $('#gross_salary').val(data.data.gross_salary);
                        $('#account_no').val(data.data.account_no);
                        updatePayableAmount();
                    }
                });
            }
            $('#employee_id').on('change', function() {
                const employeeId = $(this).val();
                loadEmployeeData(employeeId);
            });

            const selectedEmployeeId = $('#selected_employee_id').val();
            if (selectedEmployeeId) {
                $('#employee_id').val(selectedEmployeeId).trigger('change');
            }
        });
    </script>

@endsection
