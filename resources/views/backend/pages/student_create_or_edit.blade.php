@extends('backend.layouts.master')

@section('title', isset($student->id) ? 'Student Edit' : 'Student Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($student->id)) Student Edit @else Student Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminEmployeeList') }}">Student</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($student->id)) Student Edit
                @else Student Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">
                        @if(isset($student->id)) Student Edit @else Student Create @endif
                    </h3>
                    <a href="{{ route('adminStudentList') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminStudentSave', $student->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">

                            <!-- Student Name -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Student Name <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Enter student name"
                                            value="{{ old('name', $student->name ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Email </label>
                                    <div class="col-md-8">
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Enter email address"
                                            value="{{ old('email', $student->email ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Phone <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="phone"
                                            placeholder="Enter phone number"
                                            value="{{ old('phone', $student->phone ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Courses -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Courses <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="courses"
                                            placeholder="Enter course name(s)"
                                            value="{{ old('courses', $student->courses ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Amount <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" name="amount"  step="0.01"
                                            placeholder="Enter amount"
                                            value="{{ old('amount', $student->amount ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Enroll Date -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Enroll Date <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" 
                                            class="form-control fc-datepicker" 
                                            name="enroll_date"
                                            placeholder="Select enroll date"
                                            value="{{ old('enroll_date', isset($student->enroll_date) ? \Carbon\Carbon::parse($student->enroll_date)->format('d-m-Y') : '') }}" 
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Details </label>
                                    <div class="col-md-8">
                                        <textarea type="text" class="form-control" name="details" rows="5"
                                            placeholder="Enter amount">{{ old('details', $student->details ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fe fe-upload me-2"></i>
                                    {{ isset($student->id) ? 'Update' : 'Create' }}
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
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>

@endsection