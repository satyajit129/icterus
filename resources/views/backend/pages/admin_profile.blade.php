@extends('backend.layouts.master')

@section('title', $user->name . "'s Profile")
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Profile Update</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile Update</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile Update</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminProfileUpdate') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Name --}}
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                        value="{{ old('name', $user->name ?? '') }}">
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Email</label>
                                <div class="col-md-9">
                                    <input type="email" name="email" class="form-control" placeholder="Enter Email"
                                        value="{{ old('email', $user->email ?? '') }}">
                                </div>
                            </div>

                            {{-- Password (optional to change) --}}
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Password</label>
                                <div class="col-md-9">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter new password (leave blank if no change)" autocomplete="off">
                                </div>
                            </div>

                            {{-- Picture Upload --}}
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Profile Picture
                                    @if(isset($user->picture) && $user->picture)
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#pictureModal" title="View Picture">
                                                <span class="badge bg-primary">View</span>
                                            </a>
                                        @endif
                                </label>
                                <div class="col-md-9">
                                    <input type="file" name="picture" class="form-control" accept="image/*">
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="row mb-4">
                                <label class="col-md-3 form-label"></label>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-dark float-end">
                                        <i class="fe fe-upload me-2"></i>
                                        @if(isset($user->id)) Update @else Create @endif
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($user->picture) && $user->picture)
        <div class="modal effect-scale" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="pictureModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title" id="pictureModalLabel">{{ $user->name }}'s Picture</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('uploads/' . $user->picture) }}" alt="Employee Picture"
                            class="img-fluid" style="max-width: 100%; height: auto;">
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