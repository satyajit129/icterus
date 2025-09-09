@extends('backend.layouts.master')

@section('title', isset($asset->id) ? 'Asset Edit' : 'Asset Create')
@section('custom_css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            @if (isset($asset->id))
                Asset Edit
            @else
                Asset Create
            @endif
        </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminAssetList') }}">Asset </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (isset($asset->id))
                        Asset Edit
                    @else
                        Asset Create
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
                        @if (isset($asset->id))
                            Edit
                        @else
                            Asset Create
                        @endif
                    </h3>
                    <a href="{{ route('adminAssetList') }}" class="btn btn-primary btn-sm"><i
                            class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminAssetSave', $asset->id ?? '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                <!-- Asset Name -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Asset</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="name" rows="2" placeholder="Enter Asset">{{ old('name', $asset->name ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description" rows="3" placeholder="Enter Description">{{ old('description', $asset->description ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Category</label>
                                    <div class="col-md-9">
                                        <select name="category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($asset_categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $asset->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Cost -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Cost</label>
                                    <div class="col-md-9">
                                        <input type="number" step="0.01" class="form-control" name="cost"
                                            placeholder="Enter Cost" value="{{ old('cost', $asset->cost ?? '') }}">
                                    </div>
                                </div>

                                 <!-- Cost -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Code</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="code"
                                            placeholder="Enter Code" value="{{ old('code', $asset->code ?? '') }}">
                                    </div>
                                </div>

                                <!-- Purchase Date -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Purchase Date</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control fc-datepicker" name="purchase_date" placeholder="DD/MM/YYYY"
                                            value="{{ old('purchase_date', isset($asset->purchase_date) ? \Carbon\Carbon::parse($asset->purchase_date)->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>
                                            @if (isset($asset->id))
                                                Update
                                            @else
                                                Create
                                            @endif
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
