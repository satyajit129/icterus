@extends('backend.layouts.master')

@section('title', isset($salary_expense->id) ? 'Salary Expense Edit' : 'Salary Expense ')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($salary_expense->id))
                Salary Expense Edit
            @else
                Salary Expense Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminSalaryExpense') }}">Salary Expense Data</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($salary_expense->id))
                        Salary Expense Edit
                    @else
                        Salary Expense Create
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
                        @if (isset($salary_expense->id))
                            Salary Expense Edit
                        @else
                            Salary Expense Create
                        @endif
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminSalaryExpenseSave', $salary_expense->id ?? '') }}" method="POST"
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
                                <input type="hidden" name="selected_employee_id" id="selected_employee_id" value="{{ $salary_expense->employee_id ?? '' }}">
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


                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Gross Salary</label>
                                        <div class="col-md-9">
                                            <input type="text" name="gross_salary" id="gross_salary" class="form-control"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Payable Year -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Payable Year</label>
                                        <div class="col-md-9">
                                            <select id="payable_year" name="payable_year" class="form-control" required>
                                                <option value="">Select Year</option>
                                                @php
                                                    $currentYear = date('Y');
                                                @endphp
                                                @for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++)
                                                    <option value="{{ $year }}"
                                                        {{ (isset($salary_expense) ? ($salary_expense->payable_year == $year) : ($year == $currentYear)) ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endfor
                                            </select>
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
                                            <select id="payable_month" name="payable_month"
                                                class="form-control select2-show-search form-select" required>
                                                <option value="">Select Month</option>
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
                                                   <option value="{{ $num }}" {{ isset($salary_expense) && $salary_expense->payable_month == $num ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Working Days -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Working Days</label>
                                        <div class="col-md-9">
                                            <select id="total_working_day" name="total_working_day"
                                                class="form-control select2-show-search form-select" required>
                                                <option value="">Select Days</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Input for Total Days in Month -->
                            <input type="hidden" id="total_days_in_month" name="total_days_in_month" value="">
                            <input type="hidden" id="selected_working_days" name="selected_working_days" value="{{ $salary_expense->total_working_day ?? '' }}">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Festival Bonus</label>
                                        <div class="col-md-9">
                                            <input type="number" id="festival_bonus" name="festival_bonus"
                                            class="form-control" placeholder="e.g. 5000"
                                            value="{{ isset($salary_expense->festival_bonus) ? $salary_expense->festival_bonus : '' }}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Extra Charge</label>
                                        <div class="col-md-9">
                                            <input type="text" id="extra_charge" name="extra_charge"
                                                class="form-control" placeholder="e.g. 2500"  
                                                value="{{ isset($salary_expense->extra_charge) ? $salary_expense->extra_charge : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Payable Amount -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Payable Amount</label>
                                        <div class="col-md-9">
                                            <input type="text" id="payable_amount" name="payable_amount"
                                                class="form-control" placeholder="e.g. 25000" required readonly>
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
            function isLeapYear(year) {
                return ((year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0));
            }

            function updateDays() {
                const month = parseInt($('#payable_month').val());
                const year = parseInt($('#payable_year').val());

                if (!month || !year) {
                    $('#total_working_day').empty().append('<option value="">Select Days</option>');
                    $('#total_days_in_month').val('');
                    return;
                }

                let daysInMonth = 31;
                switch (month) {
                    case 2:
                        daysInMonth = isLeapYear(year) ? 29 : 28;
                        break;
                    case 4:
                    case 6:
                    case 9:
                    case 11:
                        daysInMonth = 30;
                        break;
                }

                $('#total_days_in_month').val(daysInMonth);
                const selectedWorkingDays = $('#selected_working_days').val();
                const $workingDaysSelect = $('#total_working_day');
                $workingDaysSelect.empty().append('<option value="">Select Days</option>');

                for (let i = 0; i <= daysInMonth; i++) {
                    const selected = (selectedWorkingDays !== "" && selectedWorkingDays !== null && parseInt(selectedWorkingDays) === i)
                        ? 'selected'
                        : '';
                    $workingDaysSelect.append(`<option value="${i}" ${selected}>${i}</option>`);
                }

            }

            function calculatePayableAmount(grossSalary, totalWorkingDay, festivalBonus, extraCharge, totalDaysInMonth) {
                grossSalary = parseFloat(grossSalary) || 0;
                totalWorkingDay = parseInt(totalWorkingDay) || 0;
                festivalBonus = parseFloat(festivalBonus) || 0;
                extraCharge = parseFloat(extraCharge) || 0;
                totalDaysInMonth = parseInt(totalDaysInMonth) || 0;

                if (grossSalary <= 0 || totalDaysInMonth <= 0) return 0;
                const dailySalary = grossSalary / totalDaysInMonth;
                const payableAmount = (dailySalary * totalWorkingDay) + festivalBonus + extraCharge;
                return payableAmount.toFixed(2);
            }

            function updatePayableAmount() {
                const grossSalary = $('#gross_salary').val();
                const totalWorkingDay = $('#total_working_day').val();
                const festivalBonus = $('#festival_bonus').val();
                const extraCharge = $('#extra_charge').val();
                const totalDaysInMonth = $('#total_days_in_month').val();
                const payable = calculatePayableAmount(grossSalary, totalWorkingDay, festivalBonus, extraCharge,
                    totalDaysInMonth);
                $('#payable_amount').val(payable);
            }

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

            // Event bindings
            $('#payable_month, #payable_year').on('change', function() {
                $('#total_working_day').val('');
                updateDays();
                updatePayableAmount();
            });

            $('#total_working_day, #festival_bonus, #extra_charge').on('change keyup', updatePayableAmount);

            $('#employee_id').on('change', function() {
                const employeeId = $(this).val();
                loadEmployeeData(employeeId);
            });

            // Initial triggers
            updateDays();
            updatePayableAmount();

            const selectedEmployeeId = $('#selected_employee_id').val();
            if (selectedEmployeeId) {
                $('#employee_id').val(selectedEmployeeId).trigger('change');
            }
        });
    </script>

@endsection
