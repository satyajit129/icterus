@extends('backend.layouts.master')

@section('title', 'Employee')

@section('custom_css')
    <style>
        .table td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">Employee</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Employee</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Employees Data</h3>
                    <a href="{{ route('adminEmployeeCreateOrEdit') }}">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add
                            Employee</button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">#</th>
                                            <th class="wd-15p border-bottom-0">Picture</th>
                                            <th class="wd-15p border-bottom-0">ID</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Designation</th>
                                            <th class="wd-15p border-bottom-0">Department</th>
                                            {{-- <th class="wd-15p border-bottom-0">Phone</th>
                                            <th class="wd-15p border-bottom-0">AC No</th>
                                            <th class="wd-15p border-bottom-0">G. Salery</th> --}}
                                            {{-- <th class="wd-15p border-bottom-0">B. Group</th> --}}
                                            <th class="wd-15p border-bottom-0">Address</th>
                                            <th class="wd-15p border-bottom-0">J. Date</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employees as $index => $employee)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if ($employee->picture)
                                                        <img src="{{ asset('uploads/' . $employee->picture) }}" alt="Picture"
                                                            width="50" height="50">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td>{{ $employee->id_number }}</td>
                                                <td>{{ $employee->name }}</td>
                                                <td>{{ $employee->designation->designation ?? '-' }}</td>
                                                <td>{{ $employee->department->department ?? '-' }}</td>
                                                {{-- <td>{{ $employee->phone_number }}</td> --}}
                                                {{-- <td>{{ $employee->account_no }}</td> --}}
                                                {{-- <td>{{ $employee->gross_salary }}</td> --}}
                                                {{-- <td>{{ $employee->blood_group }}</td> --}}
                                                <td>{{ $employee->address }}</td>
                                                <td>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('adminEmployeeCreateOrEdit', $employee->id) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn"
                                                        data-url="{{ route('adminEmployeeDelete', ['id' => $employee->id]) }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>
                                                    <a href="{{ route('adminEmployeeView', $employee->id) }}" class="btn btn-sm btn-info" title="View">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center text-muted">No employees found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title" id="deleteModalLabel">Delete Confirmation</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Employee?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom_js')
    <script>
        $(document).ready(function () {
            $('.delete-btn').on('click', function () {
                var deleteUrl = $(this).data('url');
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>

@endsection