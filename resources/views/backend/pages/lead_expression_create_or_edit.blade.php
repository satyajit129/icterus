@extends('backend.layouts.master')

@section('title', $expression ? 'Edit Lead Expression' : 'Create Lead Expression')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $expression ? 'Edit Lead Expression' : 'Create New Lead Expression' }}
                    </h3>
                    <div class="card-options">
                        <a href="{{ route('adminLeadExpressionList') }}" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left me-1"></i>Back to List
                        </a>
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

                    <form action="{{ route('adminLeadExpressionSave', $expression ? $expression->id : '') }}"
                        method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Expression Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $expression->name ?? '') }}"
                                        placeholder="e.g., Interested, Not Interested, Converted" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color_class" class="form-label">Color Class <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('color_class') is-invalid @enderror" id="color_class"
                                        name="color_class" required>
                                        <option value="">Select a color</option>
                                        @foreach ($colorClasses as $class => $label)
                                            <option value="{{ $class }}"
                                                {{ old('color_class', $expression->color_class ?? '') == $class ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_class')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="text_color" class="form-label">Text Color</label>
                                    <select class="form-select @error('text_color') is-invalid @enderror" id="text_color"
                                        name="text_color">
                                        <option value="text-white"
                                            {{ old('text_color', $expression->text_color ?? 'text-white') == 'text-white' ? 'selected' : '' }}>
                                            White</option>
                                        <option value="text-dark"
                                            {{ old('text_color', $expression->text_color ?? '') == 'text-dark' ? 'selected' : '' }}>
                                            Dark</option>
                                        <option value="text-light"
                                            {{ old('text_color', $expression->text_color ?? '') == 'text-light' ? 'selected' : '' }}>
                                            Light</option>
                                    </select>
                                    @error('text_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                        id="sort_order" name="sort_order"
                                        value="{{ old('sort_order', $expression->sort_order ?? 0) }}" min="0"
                                        placeholder="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Optional description for this expression">{{ old('description', $expression->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1"
                                    {{ old('is_active', $expression->is_active ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Expression will be available for use)
                                </label>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-4">
                            <label class="form-label">Preview</label>
                            <div class="p-3 border rounded bg-light">
                                <span id="preview-badge" class="badge">Preview will appear here</span>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-save me-1"></i>
                                {{ $expression ? 'Update Expression' : 'Create Expression' }}
                            </button>
                            <a href="{{ route('adminLeadExpressionList') }}" class="btn btn-secondary">
                                <i class="fe fe-x me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Live preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const colorSelect = document.getElementById('color_class');
            const textColorSelect = document.getElementById('text_color');
            const previewBadge = document.getElementById('preview-badge');

            function updatePreview() {
                const name = nameInput.value || 'Expression Name';
                const colorClass = colorSelect.value || 'bg-secondary';
                const textColor = textColorSelect.value || 'text-white';

                previewBadge.className = `badge ${colorClass} ${textColor}`;
                previewBadge.textContent = name;
            }

            nameInput.addEventListener('input', updatePreview);
            colorSelect.addEventListener('change', updatePreview);
            textColorSelect.addEventListener('change', updatePreview);

            // Initial preview
            updatePreview();
        });
    </script>
@endsection
