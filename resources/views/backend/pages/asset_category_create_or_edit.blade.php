@extends('backend.layouts.master')

@section('title', isset($category->id) ? 'AssetCategory Edit' : 'AssetCategory Create')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($category->id)) AssetCategory Edit @else AssetCategory Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminAssetCategory') }}">Asset Category </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($category->id)) AssetCategory Edit
                @else AssetCategory Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">@if(isset($category->id))  Edit @else AssetCategory Create @endif
                    </h3>
                    <a href="{{ route('adminAssetCategory') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminAssetCategorySave', $category->id ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Asset Category</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Enter Asset Category"
                                            value="{{ old('category', $category->name ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>@if(isset($category->id)) Update @else
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