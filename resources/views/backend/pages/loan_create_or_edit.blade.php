@extends('backend.layouts.master')

@section('title', isset($loan->id) ? 'Loan Edit' : 'Loan Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($loan->id))
                Loan Edit
            @else
                Loan Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($loan->id))
                        Loan Edit
                    @else
                        Loan Create
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
                        {{ isset($loan->id) ? 'Loan Edit' : 'Loan Create' }}
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminLoanSave', $loan->id ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">

                                {{-- Employee Select --}}
                                <div class="row mb-3">
                                    <label class="col-md-3 form-label">Employees <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <select name="employee_id" id="employee_id" class="form-control select2-show-search form-select" data-placeholder="Choose one" required>
                                            <option label="Choose one"></option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id', $loan->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }} ({{ $employee->id_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Date Input --}}
                                @php
                                    $formattedDate = old('date', isset($loan->date) ? \Carbon\Carbon::parse($loan->date)->format('d/m/Y') : '');
                                @endphp
                                <div class="row mb-3">
                                    <label class="col-md-3 form-label">Date <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" name="date" class="form-control fc-datepicker" placeholder="DD/MM/YYYY" value="{{ $formattedDate }}" required autocomplete="off">
                                    </div>
                                </div>

                                {{-- Amount Input --}}
                                <div class="row mb-3">
                                    <label class="col-md-3 form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" name="amount" class="form-control" placeholder="Enter Amount" value="{{ old('amount', $loan->amount ?? '') }}" required autocomplete="off">
                                    </div>
                                </div>

                                {{-- Status Select --}}
                                

                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="row mb-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fe fe-upload me-2"></i>
                                    {{ isset($loan->id) ? 'Update' : 'Create' }}
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
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <!-- jQuery UI Datepicker -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>

@endsection
