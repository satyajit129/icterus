@extends('backend.layouts.master')

@section('title', isset($employee->id) ? 'Salary Expense Update' : 'Salary Expense ')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($employee->id))
                Salary Expense Update
            @else
                Salary Expense
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminSalaryExpense') }}">Salary Expense Data</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($employee->id))
                        Salary Expense Update
                    @else
                        Salary Expense
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
                        @if (isset($employee->id))
                            Salary Expense Update
                        @else
                            Salary Expense
                        @endif
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminEmployeeSave', $employee->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Employees</label>
                                        <div class="col-md-9">
                                            <select name="employee_id" id="employee_id" class="form-control select2-show-search form-select"
                                                data-placeholder="Choose one">
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
                            </div>
                            <!-- Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="name" id="name" class="form-control"
                                                readonly>
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
                                                readonly>
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
                                                readonly>
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
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Account No -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Festival Bonus</label>
                                        <div class="col-md-9">
                                            <input type="number" name="festival_bonus" id="festival_bonus"
                                                class="form-control" placeholder="e.g. 5000">
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
                                                readonly>
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
                                            <select name="payable_month" class="form-control form-select">
                                                <option value="">Select Month</option>
                                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                    <option value="{{ $month }}">{{ $month }}</option>
                                                @endforeach
                                            </select>
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
                                            <input type="number" name="payable_year" class="form-control"
                                                placeholder="e.g. 2025" min="2000" max="2099">
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
                                            <select name="total_working_day" class="form-control form-select">
                                                <option value="">Select Days</option>
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <label class="col-md-3 form-label">Festival Bonus</label>
                                        <div class="col-md-9">
                                            <input type="number" name="festival_bonus" class="form-control"
                                                placeholder="e.g. 5000">
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
                                            <input type="number" name="payable_amount" class="form-control"
                                                placeholder="e.g. 25000">
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
        $(document).ready(function () {

            // Fetch employee details on selection
            $('#employee_id').on('change', function () {
                var employeeId = $(this).val();
                // alert(employeeId);
                if (employeeId) {
                    $.ajax({
                        url: '{{ route('adminEmployeeData') }}',
                        data:{
                            employee_id: employeeId
                        },
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#name').val(data.data.name);
                            $('#designation').val(data.data.designation?.designation ?? '');
                            $('#department').val(data.data.department?.department ?? '');
                            $('#phone_number').val(data.data.phone_number);
                            $('#gross_salary').val(data.data.gross_salary);
                        }
                    });
                } else {
                    $('#name, #designation, #department, #phone_number, #gross_salary').val('');
                }
            });
        });
    </script>
@endsection
