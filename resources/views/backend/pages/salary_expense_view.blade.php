@extends('backend.layouts.master')

@section('title', 'Salary Expense View')

@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printSection, #printSection * {
                visibility: visible;
            }
            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
@endsection


@section('content')
    <div class="page-header">
        <h1 class="page-title">Salary Expense View</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminSalaryExpense') }}">Salary Expense</a></li>
                <li class="breadcrumb-item active" aria-current="page">Salary Expense View</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Salary Expense Information</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <!-- Profile Image -->
                    <div class="text-center mb-4">
                        <img src="{{ $salary_expense->employee->picture ? asset('uploads/' . $salary_expense->employee->picture) : asset('default-profile.png') }}"
                             alt="Employee Photo"
                             class="rounded-circle shadow"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h4 class="mt-3">{{ $salary_expense->employee->name ?? 'N/A' }}</h4>
                        <p class="text-muted">Employee ID: {{ $salary_expense->employee->id_number ?? 'N/A' }}</p>
                    </div>

                    <!-- Employee Info Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%;">Department</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->department->department ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Designation</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->designation->designation ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Phone Number</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->phone_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Account Number</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->account_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Gross Salary</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->gross_salary ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Blood Group</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->blood_group ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Joining Date</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->joining_date ? \Carbon\Carbon::parse($salary_expense->employee->joining_date)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Status</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->status == 1 ? 'Active' : 'Inactive' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Address</th>
                                    <td style="width: 50%;">{{ $salary_expense->employee->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Salary Month</th>
                                    <td style="width: 50%;">{{ $salary_expense->payable_month ? \App\UtilityFunction::getMonthName($salary_expense->payable_month) : 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 50%;">Salary Year</th>
                                    <td style="width: 50%;">{{ $salary_expense->payable_year ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%">Total Working Days</th>
                                    <td style="width: 50%;">{{ $salary_expense->total_working_days ?? 'N/A' }}</td>
                                </tr>
                                @if(isset($salary_expense->festival_bonus))
                                    <tr>
                                        <th style="width: 50%">Festival Bonus</th>
                                        <td style="width: 50%;">{{ $salary_expense->festival_bonus ?? 'N/A' }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th style="width: 50%;">Actual Payable Amount</th>
                                    <td style="width: 50%;">{{ $salary_expense->actual_payable_amount ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%">Total Salary</th>
                                    <td style="width: 50%;">{{ $salary_expense->payable_amount ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection
