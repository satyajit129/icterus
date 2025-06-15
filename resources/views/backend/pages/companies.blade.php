@extends('backend.layouts.master')

@section('title', 'Company')

@section('custom_css')
@endsection


@section('content')
    <div class="page-header">
        <h1 class="page-title">Company</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Company</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Company Data</h3>
                    <a href="{{ route('adminCompanyCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add
                            Company</button>
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
                                            <th class="wd-15p border-bottom-0">Logo</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">CEO</th>
                                            <th class="wd-15p border-bottom-0">Address</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($company_lists as $company_list)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{  asset('uploads/'. $company_list->logo) }}" alt="Picture"
                                                            width="50" height="50">
                                                </td>
                                                <td>{{ $company_list->name }}</td>
                                                <td>{{ $company_list->ceo_name }}</td>
                                                <td>{{ $company_list->address }}</td>
                                                <td>
                                                    <a href="{{ route('adminCompanyCreateOrEdit', $company_list->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminCompanyDelete', ['id' => $company_list->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                    <a href="{{ route('adminCompanyView', $company_list->id) }}" class="btn btn-sm btn-info" title="View">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">No data Found</td>
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
                    <p>Are you sure you want to delete this Company?</p>
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
        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
@endsection