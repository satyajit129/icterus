@extends('backend.layouts.master')

@section('title', 'Company Deals')

@section('custom_css')
    <style>
        .table td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Company Deals</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company Deals</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Company Deals Data</h3>
                    <a href="{{ route('adminCompanyDealsCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add
                            Company Deals</button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">#</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Company Name</th>
                                            <th class="wd-15p border-bottom-0">Deals</th>
                                            <th class="wd-15p border-bottom-0">Contract Duration</th>
                                            <th class="wd-15p border-bottom-0">Deals Amount</th>
                                            <th class="wd-15p border-bottom-0">Payment Frequency</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($company_deals as $index => $company_deal)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($company_deal->date)->format('d F Y')}}</td>
                                                <td>{{ $company_deal->companies->name }}</td>
                                                <td>{{ $company_deal->deals }}</td>
                                                <td>{{ $company_deal->contract_duration }}</td>
                                                <td>{{ $company_deal->deals_amount }}</td>
                                                <td>{{ $company_deal->payment_frequency }}</td>
                                                <td>
                                                    <a href="{{ route('adminCompanyDealsCreateOrEdit', $company_deal->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminCompanyDealsDelete', ['id' => $company_deal->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                    <a href="{{ route('adminCompanyDealsView', $company_deal->id) }}"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center text-muted">No Deals found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
                    <p>Are you sure you want to delete this Deals?</p>
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
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
@endsection
