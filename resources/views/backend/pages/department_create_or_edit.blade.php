@extends('backend.layouts.master')

@section('title', isset($department->id) ? 'Department Update' : 'Department Create')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($department->id)) Department Update @else Department Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminDepartment') }}">Department </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($department->id)) Department Update
                @else Department Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@if(isset($department->id)) Department Update @else Department Create @endif
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminDepartmentSave', $department->id ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Department</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="department"
                                            placeholder="Enter Department"
                                            value="{{ old('department', $department->department ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>@if(isset($department->id)) Update @else
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

@endsection

@section('custom_js')
@endsection