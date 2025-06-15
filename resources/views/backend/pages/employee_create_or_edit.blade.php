@extends('backend.layouts.master')

@section('title', isset($employee->id) ? 'Employee Update' : 'Employee Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($employee->id)) Employee Update @else Employee Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminEmployeeList') }}">Employee Data</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($employee->id)) Employee Update
                @else Employee Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if(isset($employee->id)) Employee Update @else Employee Create @endif
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminEmployeeSave', $employee->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <!-- Employee ID -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Employee ID Number</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="id_number"
                                            placeholder="Enter Employee ID"
                                            value="{{ old('id_number', $employee->id_number ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Employee Name -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Employee Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $employee->name ?? '') }}" required>

                                    </div>
                                </div>
                            </div>

                            <!-- Designation -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Designation</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="designation_id" required>
                                            <option value="" disabled selected>Select Designation</option>
                                            @forelse ($designations as $designation)
                                                <option value="{{ $designation->id }}" {{ old('designation_id', $employee->designation_id ?? '') == $designation->id ? 'selected' : '' }}>
                                                    {{ $designation->designation }}
                                                </option>


                                            @empty
                                                <p>No data Found</p>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Department</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="department_id" required>
                                            <option value="" disabled selected>Select Department</option>
                                            @forelse ($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->department }}
                                                </option>

                                            @empty
                                                <p>No data Found</p>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Phone Number</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="phone_number"
                                            placeholder="Enter Phone Number"
                                            value="{{ old('phone_number', $employee->phone_number ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Number -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Account Number</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="account_no"
                                            placeholder="Enter Account Number"
                                            value="{{ old('account_no', $employee->account_no ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Gross Salary -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Gross Salary</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="gross_salary"
                                            placeholder="Enter Gross Salary"
                                            value="{{ old('gross_salary', $employee->gross_salary ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Blood Group -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Blood Group</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="blood_group" required>
                                            <option value="" disabled {{ old('blood_group', $employee->blood_group ?? '') == '' ? 'selected' : '' }}>
                                                Select Blood Group
                                            </option>
                                            <option value="A+" {{ old('blood_group', $employee->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('blood_group', $employee->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('blood_group', $employee->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('blood_group', $employee->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="O+" {{ old('blood_group', $employee->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('blood_group', $employee->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                                            <option value="AB+" {{ old('blood_group', $employee->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('blood_group', $employee->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Address</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="address" placeholder="Enter Address"
                                            value="{{ old('address', $employee->address ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Picture -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">
                                        Picture
                                        @if(isset($employee->picture) && $employee->picture)
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#pictureModal" title="View Picture">
                                                <span class="badge bg-primary">View</span>
                                            </a>
                                        @endif

                                    </label>
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" name="picture">
                                    </div>
                                </div>
                            </div>


                            <!-- Joining Date -->
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Joining Date</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            @php
                                                $joiningDate = old('joining_date', isset($employee->joining_date) ? \Carbon\Carbon::parse($employee->joining_date)->format('d/m/Y') : '');
                                            @endphp

                                            <input type="text" class="form-control fc-datepicker" name="joining_date"
                                                placeholder="DD/MM/YYYY"
                                                value="{{ $joiningDate }}" required>
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
                                    {{ isset($employee->id) ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    @if(isset($employee->picture) && $employee->picture)
        <div class="modal effect-scale" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="pictureModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title" id="pictureModalLabel">Employee Picture</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('uploads/' . $employee->picture) }}" alt="Employee Picture"
                            class="img-fluid" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('custom_js')
    <!-- jQuery UI Datepicker -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>

@endsection