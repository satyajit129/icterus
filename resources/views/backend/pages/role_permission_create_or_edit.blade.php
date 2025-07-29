@extends('backend.layouts.master')

@section('title', 'Role Access')

@section('custom_css')
    <style>
        .listbox-wrapper {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 20px;
        }
        @media (max-width: 576px) {
            .listbox-wrapper {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
        }

        .listbox-buttons {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 15px;
        }

        .multiple-permission {
            height: 300px;
            cursor: pointer;
        }

        .multiple-permission option {
            padding: 5px 10px;
            font-weight: bold;
            color: #333;
            background-color: #f9f9f9;
        }

        .multiple-permission option:hover {
            background-color: #e2e6ea;
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
                    
                    <a href="{{ route('adminRoleAccess') }}" class="btn btn-primary btn-sm"><i class="fe fe-arrow-left me-1"></i> Back to List</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('adminRoleAccessSave', $role->id ?? '') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Role Name"
                                value="{{ old('name', $role->name ?? '') }}">
                        </div>

                        <div class="listbox-wrapper">
                            {{-- Available Permissions --}}
                            <select multiple id="availablePermissions" class="form-control multiple-permission">
                                @foreach ($permissions as $permission)
                                    @if (!isset($assigned_permissions) || !in_array($permission->id, $assigned_permissions))
                                        <option value="{{ $permission->id }}">{{ $permission->bangla_code }}</option>
                                    @endif
                                @endforeach
                            </select>

                            <div class="listbox-buttons">
                                <button type="button" id="addPermission" class="btn btn-primary">&gt;&gt;</button>
                                <button type="button" id="removePermission" class="btn btn-danger">&lt;&lt;</button>
                            </div>

                            {{-- Assigned Permissions --}}
                            <select multiple id="assignedPermissions" name="permissions[]"
                                class="form-control multiple-permission">
                                @foreach ($permissions as $permission)
                                    @if (isset($assigned_permissions) && in_array($permission->id, $assigned_permissions))
                                        <option value="{{ $permission->id }}">{{ $permission->bangla_code }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>


                        <div class="float-end mt-4">
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

            // ⭐ Ensure all assigned permissions are selected before form submit
            $('form').on('submit', function () {
                $('#assignedPermissions option').prop('selected', true);
            });
        });
    </script>

@endsection