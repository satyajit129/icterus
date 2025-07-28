@extends('backend.layouts.master')

@section('title', isset($designation->id) ? 'Designation Edit' : 'Designation Create')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($designation->id)) Designation Edit @else Designation Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminDesignation') }}">Designation </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($designation->id)) Designation Edit
                @else Designation Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">@if(isset($designation->id)) Designation Edit @else Designation Create @endif
                    </h3>
                    <a href="{{ route('adminDesignation') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminDesignationSave', $designation->id ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Designation</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="designation"
                                            placeholder="Enter Designation"
                                            value="{{ old('designation', $designation->designation ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>@if(isset($designation->id)) Update @else
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