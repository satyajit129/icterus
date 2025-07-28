@extends('backend.layouts.master')

@section('title', isset($office_expense->id) ? 'Office Expense Edit' : 'Office Expense Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($office_expense->id))
                Office Expense Edit
            @else
                Office Expense Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminEmployeeList') }}">Employee </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($office_expense->id))
                        Office Expense Edit
                    @else
                        Office Expense Create
                    @endif
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">
                        @if (isset($office_expense->id))
                            Office Expense Edit
                        @else
                            Office Expense Create
                        @endif
                    </h3>
                    <a href="{{ route('adminOfficeExpense') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminOfficeExpenseSave', $office_expense->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Expense Category <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <select name="category_id" class="form-control">
                                            <option selected disabled>Select an Option</option>
                                            @forelse ($expense_categories as $expense_category)
                                                <option value="{{ $expense_category->id }}"
                                                    {{ isset($office_expense) && $expense_category->id == $office_expense->category_id ? 'selected' : '' }}>
                                                    {{ $expense_category->name }}
                                                </option>

                                            @empty
                                                <p>No Data Found</p>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <!-- Date -->
                                @php
                                    $formattedDate = old('date', isset($office_expense->date) ? \Carbon\Carbon::parse($office_expense->date)->format('d/m/Y') : '');
                                @endphp

                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Date <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control fc-datepicker" name="date"
                                            placeholder="DD/MM/YYYY" value="{{ $formattedDate }}"
                                            required autocomplete="off">
                                    </div>
                                </div>


                                <!-- Purpose -->
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Purpose <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="purpose"
                                            placeholder="Enter purpose"
                                            value="{{ old('purpose', $office_expense->purpose ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Quantity</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" name="quantity"
                                            placeholder="Enter quantity"
                                            value="{{ old('quantity', $office_expense->quantity ?? '') }}" >
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Details <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="details" rows="3" placeholder="Enter details" required>{{ old('details', $office_expense->details ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="number" step="0.01" class="form-control" name="amount"
                                            placeholder="Enter amount"
                                            value="{{ old('amount', $office_expense->amount ?? '') }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fe fe-upload me-2"></i>
                                    {{ isset($office_expense->id) ? 'Update' : 'Create' }}
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
        $(function() {
            $(".fc-datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>

@endsection
