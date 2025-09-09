@extends('backend.layouts.master')

@section('title', isset($admin_user->id) ? 'Admin User Edit' : 'Admin User Create')
@section('custom_css')
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">@if(isset($admin_user->id)) Admin User Edit @else Admin User Create @endif </h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('adminUserList') }}">Admin Users </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@if(isset($admin_user->id)) Admin User Update
                @else Admin User Create @endif </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">@if(isset($admin_user->id)) Admin User Edit @else Admin User Create @endif
                    </h3>
                    <a href="{{ route('adminUserList') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminUserSave', $admin_user->id ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                {{-- Name --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Name<span style="color: red;">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" placeholder="Enter Name"
                                            value="{{ old('name', $admin_user->name ?? '') }}">
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Email<span style="color: red;">*</span></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email"
                                            value="{{ old('email', $admin_user->email ?? '') }}" autocomplete="off">
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Phone</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone" placeholder="Enter Phone"
                                            value="{{ old('phone', $admin_user->phone ?? '') }}">
                                    </div>
                                </div>
                                 {{-- Password --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="password" placeholder="Enter password" autocomplete="off">
                                    </div>
                                </div>


                                {{-- Admin Role --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Admin Role<span style="color: red;">*</span></label>
                                    <div class="col-md-9">
                                        <select name="admin_role_id" class="form-control">
                                            <option value="">-- Select Role --</option>
                                            @foreach ($admin_roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('admin_role_id', $admin_user->admin_role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>
                                            @if(isset($admin_user->id)) Update @else Create @endif
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