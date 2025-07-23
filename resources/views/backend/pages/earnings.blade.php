@extends('backend.layouts.master')

@section('title', 'Earning')

@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Earning</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Earning</li>
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
                    <form action="{{ route('adminEarningList') }}" method="GET">
                        @csrf
                        <div class="row">
                            {{-- Company  --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Company</label>
                                    <select name="company_id" id="company_id"
                                        class="form-control select2-show-search form-select" data-placeholder="Choose one">
                                        <option value="">Choose one</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Date Range</label>
                                    <input type="text" name="date" class="form-control date-range-picker"
                                        value="{{ request('date') }}" autocomplete="off"
                                        placeholder="DD/MM/YYYY - DD/MM/YYYY">
                                </div>
                            </div>

                            {{-- Row 2: Employee Name & Sales Status --}}
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Employee Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ request('name') }}" autocomplete="off"
                                        placeholder="Enter Employee Name">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="mb-4">
                                    <label class="form-label">Sales Status</label>
                                    <select name="sales_status" class="form-control">
                                        <option value="">Choose one</option>
                                        @foreach ($sales_statuses as $sales_status)
                                            <option value="{{ $sales_status->id }}"
                                                {{ request('sales_status') == $sales_status->id ? 'selected' : '' }}>
                                                {{ $sales_status->status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 float-end">
                            <a href="{{ route('adminEarningList') }}" class="btn btn-secondary btn-sm">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Earning Data</h3>
                    <a href="{{ route('adminEarningCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add
                            Earning</button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="wd-5p">#</th>
                                            <th class="wd-10p">Company</th>
                                            <th class="wd-10p">Date</th>
                                            <th class="wd-10p">Employee</th>
                                            <th class="wd-5p">Sales Status</th>
                                            <th class="wd-10p">Paid Amount</th>
                                            <th class="wd-10p">Deals Amount</th>
                                            <th class="wd-10p">Due Amount</th>
                                            <th class="wd-10p">Product Name</th>
                                            <th class="wd-15p">Details</th>
                                            <th class="wd-5p">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($earnings as $key => $earning)
                                            <tr>
                                                <td>{{ $earnings->firstItem() + $loop->index }}</td>
                                                <td>{{ $earning->companies->name ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($earning->date)->format('d/m/Y') }}</td>
                                                <td>{{ $earning->employee->name ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                        $sales_status = \App\Models\SalesStatus::find(
                                                            $earning->sales_status,
                                                        );
                                                    @endphp
                                                    <span
                                                        class="badge bg-primary badge-sm  me-1 mb-1 mt-1">{{ $sales_status?->status ?? 'N/A' }}</span>
                                                </td>

                                                <td>{{ $earning->paid_amount }}</td>
                                                <td>{{ $earning->deals_amount }}</td>
                                                <td>{{ $earning->due_amount }}</td>
                                                <td>{{ $earning->product_name }}</td>
                                                <td>{{ $earning->details }}</td>
                                                <td>
                                                    <a href="{{ route('adminEarningCreateOrEdit', $earning->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminEarningDelete', ['id' => $earning->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                    <a href="{{ route('adminEarningView', $earning->id) }}"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="18" class="text-center">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $earnings->links() }}
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
                    <p>Are you sure you want to delete this Data?</p>
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

    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
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
