@extends('backend.layouts.master')

@section('title', 'Employee Info')

@section('custom_css')
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
        <h1 class="page-title">Employee Info</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminEmployeeList') }}">Employees Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Employee Info</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card" id="employeeInfoCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Employee Information</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()" title="Print">
                        <i class="fe fe-printer me-1"></i> Print
                    </button>
                </div>

                <div class="card-body" id="printSection">
                    <!-- Profile Image -->
                    <div class="text-center mb-4">
                        <img src="{{ $employee->picture ? asset('uploads/' . $employee->picture) : asset('default-profile.png') }}"
                             alt="Employee Photo"
                             class="rounded-circle shadow"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h4 class="mt-3">{{ $employee->name ?? 'N/A' }}</h4>
                        <p class="text-muted">Employee ID: {{ $employee->id_number ?? 'N/A' }}</p>
                    </div>

                    <!-- Employee Info Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%;">Department</th>
                                    <td style="width: 50%;">{{ $employee->department->department ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Designation</th>
                                    <td style="width: 50%;">{{ $employee->designation->designation ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Phone Number</th>
                                    <td style="width: 50%;">{{ $employee->phone_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Account Number</th>
                                    <td style="width: 50%;">{{ $employee->account_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Gross Salary</th>
                                    <td style="width: 50%;">{{ $employee->gross_salary ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Blood Group</th>
                                    <td style="width: 50%;">{{ $employee->blood_group ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Joining Date</th>
                                    <td style="width: 50%;">{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Status</th>
                                    <td style="width: 50%;">{{ $employee->status == 1 ? 'Active' : 'Inactive' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Address</th>
                                    <td style="width: 50%;">{{ $employee->address ?? 'N/A' }}</td>
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
