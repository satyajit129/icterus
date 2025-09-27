@extends('backend.layouts.master')

@section('title', 'Lead Expressions Management')

{{-- Permission Check --}}
@php
    $user = auth()->user();
    $canManageExpressions = $user->hasPermission('manage_lead_expressions');
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Expressions</h3>
                    <div class="card-options">
                        @if ($canManageExpressions)
                            <a href="{{ route('adminLeadExpressionCreateOrEdit') }}" class="btn btn-primary btn-sm">
                                <i class="fe fe-plus me-1"></i>Add Expression
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Preview</th>
                                    <th>Color Class</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expressions as $expression)
                                    <tr>
                                        <td>{{ $expression->id }}</td>
                                        <td>{{ $expression->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $expression->color_class }} {{ $expression->text_color }}">
                                                {{ $expression->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <code>{{ $expression->color_class }}</code>
                                        </td>
                                        <td>{{ $expression->description ?? 'N/A' }}</td>
                                        <td>
                                            @if ($expression->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $expression->sort_order }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($canManageExpressions)
                                                    <a href="{{ route('adminLeadExpressionView', $expression->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>

                                                    <a href="{{ route('adminLeadExpressionCreateOrEdit', $expression->id) }}"
                                                        class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <a href="{{ route('adminLeadExpressionToggleStatus', $expression->id) }}"
                                                        class="btn btn-sm btn-outline-{{ $expression->is_active ? 'secondary' : 'success' }}"
                                                        title="{{ $expression->is_active ? 'Deactivate' : 'Activate' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $expression->is_active ? 'deactivate' : 'activate' }} this expression?')">
                                                        <i
                                                            class="fe fe-{{ $expression->is_active ? 'pause' : 'play' }}"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $expression->id }}" title="Delete">
                                                        <i class="fe fe-trash-2"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    @if ($canManageExpressions)
                                        <div class="modal fade" id="deleteModal{{ $expression->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Expression</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete the expression
                                                            <strong>"{{ $expression->name }}"</strong>?
                                                        </p>
                                                        <p class="text-danger">This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form
                                                            action="{{ route('adminLeadExpressionDelete', $expression->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No expressions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($expressions->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $expressions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
