@extends('backend.layouts.master')

@section('title', isset($sales_status->id) ? 'Sales Status Edit' : 'Sales Status Create')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($sales_status->id))
                Sales Status Edit
            @else
                Sales Status Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminEmployeeList') }}">Employee </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($sales_status->id))
                        Sales Status Edit
                    @else
                        Sales Status Create
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
                        @if (isset($sales_status->id))
                            Sales Status Edit
                        @else
                            Sales Status Create
                        @endif
                    </h3>
                     <a href="{{ route('adminSalesStatus') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminSalesStatusSave', $sales_status->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">

                                <div class="row mb-2">
                                    <label class="col-md-4 form-label">Status <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="status"
                                            placeholder="Enter Status" value="{{ old('status', $sales_status->status ?? '') }}"
                                            required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fe fe-upload me-2"></i>
                                    {{ isset($sales_status->id) ? 'Update' : 'Create' }}
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
@endsection
