@extends('backend.layouts.master')

@section('title', 'Office Expense')

@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@php
    $user = auth()->user();
    $canAddOfficeExpense    =  $user->hasPermission('add_office_expense');
    $canEditOfficeExpense   =  $user->hasPermission('edit_office_expense');
    $canDeleteOfficeExpense =  $user->hasPermission('delete_office_expense');
    $canViewOfficeExpense   =  $user->hasPermission('view_office_expense');
    $canDownloadOfficeExpense =  $user->hasPermission('download_office_expense');
@endphp


@section('content')

    <div class="page-header">
        <h1 class="page-title">Office Expense</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Office Expense</li>
            </ol>
        </div>
    </div>
        <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Filter Data</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminOfficeExpense') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <label class="form-label">Expense Category</label>
                                    <select name="category_id" class="form-control select2-show-search form-select">
                                        <option value="">Choose one</option>
                                        @foreach ($expense_categories as $expense_category)
                                            <option value="{{ $expense_category->id }}"
                                                {{ request('category_id') == $expense_category->id ? 'selected' : '' }}>
                                                {{ $expense_category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <label class="form-label">Date Range</label>
                                    <input type="text" name="date" class="form-control date-range-picker"
                                        value="{{ request('date') }}" autocomplete="off"
                                        placeholder="DD/MM/YYYY - DD/MM/YYYY">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <label class="form-label">Purpose</label>
                                    <input type="text" name="purpose" class="form-control"
                                        value="{{ request('purpose') }}" autocomplete="off"
                                        placeholder="Enter purpose">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 float-end">
                            <a href="{{ route('adminOfficeExpense') }}" class="btn btn-secondary btn-sm">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <h5 class="fw-bold">
                            Total Amount: 
                            <span class="text-success">{{ number_format($totalAmount, 2) }}</span>
                        </h5>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Office Expense Data</h3>
                    <div>
                        @if ($canDownloadOfficeExpense)
                            <a href="{{ route('adminOfficeExpenseExport', request()->query()) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="fe fe-download me-1"></i> Download Data
                            </a>
                        @endif
                        
                        @if ($canAddOfficeExpense)
                            <a href="{{ route('adminOfficeExpenseCreateOrEdit') }}">
                                <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add Office Expense</button>
                            </a>
                        @endif
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom ">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">#</th>
                                             <th class="wd-15p border-bottom-0">Category</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Purpose</th>
                                            <th class="wd-15p border-bottom-0">Quantity</th>
                                            <th class="wd-15p border-bottom-0">Details</th>
                                            <th class="wd-15p border-bottom-0">Amount</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($office_expenses as $office_expense)
                                            <tr>
                                                <td>{{ $office_expenses->firstItem() + $loop->index }}</td>
                                                <td>{{ $office_expense->category->name ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($office_expense->date)->format('d/m/Y') }}</td>
                                                <td>{{ $office_expense->purpose }}</td>
                                                <td>{{ isset($office_expense->quantity) ? $office_expense->quantity : '----' }}</td>
                                                <td>{{ $office_expense->details }}</td>
                                                <td>{{ number_format($office_expense->amount, 2) }}</td>
                                                <td>
                                                    @if ($canEditOfficeExpense || $canDeleteOfficeExpense || $canViewOfficeExpense)
                                                        @if ($canEditOfficeExpense)
                                                            <a href="{{ route('adminOfficeExpenseCreateOrEdit', ['id' => $office_expense->id, 'page' => request('page')]) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="fe fe-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if ($canDeleteOfficeExpense)
                                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                                data-url="{{ route('adminOfficeExpenseDelete', ['id' => $office_expense->id, 'page' => request('page')]) }}"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                                    <i class="fe fe-trash"></i>
                                                            </a>
                                                        @endif
                                                        @if ($canViewOfficeExpense)
                                                            <a href="{{ route('adminOfficeExpenseView', $office_expense->id) }}"
                                                                class="btn btn-sm btn-info"><i class="fe fe-eye"></i></a>
                                                        @endif
                                                    @else
                                                            <span class="text-muted fst-italic">No actions available</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="8">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $office_expenses->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title" id="deleteModalLabel">Delete Confirmation</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Office Expense?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom_js')
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
        <script>
        $(function() {
            $('.date-range-picker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                },
                autoUpdateInput: false
            });
            $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });
            $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endsection
