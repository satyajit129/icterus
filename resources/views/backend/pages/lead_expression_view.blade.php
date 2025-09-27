@extends('backend.layouts.master')

@section('title', 'Lead Expression Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Expression Details</h3>
                    <div class="card-options">
                        <a href="{{ route('adminLeadExpressionList') }}" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left me-1"></i>Back to List
                        </a>
                        @if (auth()->user()->hasPermission('manage_lead_expressions'))
                            <a href="{{ route('adminLeadExpressionCreateOrEdit', $expression->id) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fe fe-edit me-1"></i>Edit
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">ID:</th>
                                    <td>{{ $expression->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $expression->name }}</td>
                                </tr>
                                <tr>
                                    <th>Preview:</th>
                                    <td>
                                        <span class="badge {{ $expression->color_class }} {{ $expression->text_color }}">
                                            {{ $expression->name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Color Class:</th>
                                    <td>
                                        <code>{{ $expression->color_class }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Text Color:</th>
                                    <td>
                                        <code>{{ $expression->text_color }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $expression->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($expression->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $expression->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $expression->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $expression->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Badge Preview</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="badge {{ $expression->color_class }} {{ $expression->text_color }}"
                                            style="font-size: 1.2em;">
                                            {{ $expression->name }}
                                        </span>
                                    </div>
                                    <p class="text-muted">This is how the expression will appear in the lead list.</p>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    @if (auth()->user()->hasPermission('manage_lead_expressions'))
                                        <a href="{{ route('adminLeadExpressionCreateOrEdit', $expression->id) }}"
                                            class="btn btn-warning btn-sm w-100 mb-2">
                                            <i class="fe fe-edit me-1"></i>Edit Expression
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasPermission('manage_lead_expressions'))
                                        <a href="{{ route('adminLeadExpressionToggleStatus', $expression->id) }}"
                                            class="btn btn-{{ $expression->is_active ? 'secondary' : 'success' }} btn-sm w-100 mb-2"
                                            onclick="return confirm('Are you sure you want to {{ $expression->is_active ? 'deactivate' : 'activate' }} this expression?')">
                                            <i class="fe fe-{{ $expression->is_active ? 'pause' : 'play' }} me-1"></i>
                                            {{ $expression->is_active ? 'Deactivate' : 'Activate' }}
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasPermission('manage_lead_expressions'))
                                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="fe fe-trash-2 me-1"></i>Delete Expression
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @if (auth()->user()->hasPermission('manage_lead_expressions'))
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Expression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the expression <strong>"{{ $expression->name }}"</strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('adminLeadExpressionDelete', $expression->id) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
