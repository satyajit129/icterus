@extends('backend.layouts.master')

@section('title', 'Settings')

@section('custom_css')
@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Settings</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">General Settings</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('adminSettingsUpdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Website Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="website_name"
                                        placeholder="Website Name"
                                        value="{{ old('website_name', $settings->website_name ?? '') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Website Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="website_email"
                                        placeholder="Website Email"
                                        value="{{ old('website_email', $settings->website_email ?? '') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Copy Right Text</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="copy_right_text"
                                        placeholder="Website Copy Right Text"
                                        value="{{ old('copy_right_text', $settings->copy_right_text ?? '') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Logo
                                    @if(isset($settings->logo) && $settings->logo)
                                        <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#logoModal" title="View Logo">
                                            <span class="badge bg-primary">View</span>
                                        </a>
                                    @endif

                                </label>
                                <div class="col-md-9">
                                    <input class="form-control" type="file" name="logo">
                                </div>
                            </div>

                            <!-- Favicon Upload Field -->
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">
                                    Favicon
                                    @if(isset($settings->favicon) && $settings->favicon)
                                        <a data-bs-toggle="modal" href="#faviconModal" title="View Favicon">
                                            <span class="badge bg-primary" style="cursor: pointer;">View</span>
                                        </a>
                                    @endif
                                </label>
                                <div class="col-md-9">
                                    <input class="form-control" type="file" name="favicon">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-3 form-label"></label>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-dark float-end">
                                        <i class="fe fe-upload me-2"></i>Submit
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
<!-- Logo Modal -->
@if(isset($settings->logo) && $settings->logo)
<div class="modal effect-scale" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="logoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title" id="logoModalLabel">Logo Preview</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('uploads/' . $settings->logo) }}" alt="Logo" class="img-fluid"
                    style="max-width: 100%; height: auto;">
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Favicon Modal -->
@if(isset($settings->favicon) && $settings->favicon)
<div class="modal effect-scale" id="faviconModal" tabindex="-1" role="dialog" aria-labelledby="faviconModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title" id="faviconModalLabel">Favicon Preview</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('uploads/' . $settings->favicon) }}" alt="Favicon" class="img-fluid" style="max-width: 100px;">
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
