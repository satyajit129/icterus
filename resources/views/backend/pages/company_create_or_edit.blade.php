@extends('backend.layouts.master')

@section('title', isset($company->id) ? 'Company Update' : 'Company Create')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($company->id)) Company Update @else Company Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminDepartment') }}">Department</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($company->id)) Company Update
                @else Company Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@if(isset($company->id)) Company Update @else Company Create @endif
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminCompanySave', $company->id ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Enter Name of the Company"
                                            value="{{ old('name', $company->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Logo @if(isset($company->logo) && $company->logo)
                                        <span class="badge bg-primary" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#logoModal">
                                            View
                                        </span>
                                    @endif</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="logo"
                                            placeholder="Enter Logo"
                                            value="{{ old('logo', $company->logo ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">CEO Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="ceo_name"
                                            placeholder="Enter CEO Name"
                                            value="{{ old('ceo_name', $company->ceo_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">CEO Phone</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="ceo_phone"
                                            placeholder="Enter CEO Phone"
                                            value="{{ old('ceo_phone', $company->ceo_phone ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">CEO Picture @if(isset($company->ceo_picture) && $company->ceo_picture)
                                        <span class="badge bg-primary" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#ceoPictureModal">
                                            View
                                        </span>
                                    @endif</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="ceo_picture"
                                            placeholder="Enter CEO Picture"
                                            value="{{ old('ceo_picture', $company->ceo_picture ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Address</label>
                                    <div class="col-md-9">
                                        <textarea type="text" class="form-control" name="address"
                                            placeholder="Enter Address">{{ old('address', $company->address ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Categories</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="categories"
                                            placeholder="Enter Categories"
                                            value="{{ old('categories', $company->categories ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>@if(isset($company->id)) Update @else
                                            Create @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@if(isset($company->logo) && $company->logo)
    <div class="modal effect-scale" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="logoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title" id="logoModalLabel">Company Logo</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('uploads/'. $company->logo) }}" alt="Company Logo" class="img-fluid" style="max-width: 100%; height: auto;">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if(isset($company->ceo_picture) && $company->ceo_picture)
    <div class="modal effect-scale" id="ceoPictureModal" tabindex="-1" role="dialog" aria-labelledby="ceoPictureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title" id="ceoPictureModalLabel">CEO Picture</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('uploads/'. $company->ceo_picture) }}" alt="CEO Picture" class="img-fluid" style="max-width: 100%; height: auto;">
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
@endsection