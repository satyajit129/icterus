@extends('backend.layouts.master')

@section('title', 'Custom Role Access')

@section('custom_css')
<style>
    .listbox-wrapper {
        display: flex;
        gap: 30px;
        justify-content: center;
        margin-top: 20px;
    }

    .listbox-buttons {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 15px;
    }

    select {
        width: 300px;
        height: 300px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Roles</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Roles</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title">Assign Permissions to Role</h3>
            </div>

            <div class="card-body">
                <form action="" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Select Role</label>
                        <select name="role_id" id="role_id" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="listbox-wrapper">
                        <select multiple id="availablePermissions" class="form-control">
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>

                        <div class="listbox-buttons">
                            <button type="button" id="addPermission" class="btn btn-primary">&gt;&gt;</button>
                            <button type="button" id="removePermission" class="btn btn-danger">&lt;&lt;</button>
                        </div>

                        <select multiple id="assignedPermissions" name="permissions[]" class="form-control">
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function () {
        $('#addPermission').click(function () {
            $('#availablePermissions option:selected').each(function () {
                $(this).remove().appendTo('#assignedPermissions');
            });
        });

        $('#removePermission').click(function () {
            $('#assignedPermissions option:selected').each(function () {
                $(this).remove().appendTo('#availablePermissions');
            });
        });

        // Optional: double-click to move
        $('#availablePermissions').on('dblclick', 'option', function () {
            $(this).remove().appendTo('#assignedPermissions');
        });

        $('#assignedPermissions').on('dblclick', 'option', function () {
            $(this).remove().appendTo('#availablePermissions');
        });
    });
</script>
@endsection
