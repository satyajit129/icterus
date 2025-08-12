@extends('backend.layouts.master')

@section('title', isset($payment_info->id) ? 'Student Payment Edit' : 'Student Payment Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($payment_info->id)) Student Payment Edit @else Student Payment Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminEmployeeList') }}">Student</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($payment_info->id)) Student Payment Edit
                @else Student Payment Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">
                        @if(isset($payment_info->id)) Student Payment Edit @else Student Payment Create @endif
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminStudentPaymentSave', $payment_info->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Student Name</label>
                                    <div class="col-md-8">
                                        <input class="form-control" value="{{ $student_info->name }}" readonly>
                                        <input type="hidden" name="student_id" value="{{ $student_info->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Payment Method <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <select name="trnx_method" class="form-control" required>
                                            <option value="" selected disabled>Select Payment Method</option>
                                            @foreach(\App\Enum\TrnxMethod::labels() as $key => $label)
                                                <option value="{{ $key }}" {{ old('trnx_method', $payment_info->trnx_method ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction ID -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Transaction ID <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" name="trnx_id" class="form-control"
                                            placeholder="Enter transaction ID"
                                            value="{{ old('trnx_id', $payment_info->trnx_id ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Amount <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="number" name="amount" class="form-control" step="0.01"
                                            placeholder="Enter amount"
                                            value="{{ old('amount', $payment_info->amount ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Date <span style="color: red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" name="date" class="form-control fc-datepicker" placeholder="Enter Date"
                                            value="{{ old('date', isset($payment_info->date) ? \Carbon\Carbon::parse($payment_info->date)->format('d-m-Y') : '') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fe fe-upload me-2"></i>
                                    {{ isset($payment_info->id) ? 'Update' : 'Create' }}
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