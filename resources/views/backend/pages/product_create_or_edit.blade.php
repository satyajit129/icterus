@extends('backend.layouts.master')

@section('title', $product ? 'Edit Product' : 'Add Product')

@section('custom_css')
    <style>
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin: 5px;
            border: 2px solid #e9ecef;
        }

        .image-preview:hover {
            border-color: #007bff;
        }

        .image-container {
            position: relative;
            display: inline-block;
        }

        .remove-image {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .upload-area.dragover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .file-input {
            display: none;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $product ? 'Edit Product' : 'Add Product' }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminProductList') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product ? 'Edit' : 'Add' }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $product ? 'Edit Product' : 'Add New Product' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminProductSave', $product ? $product->id : '') }}" method="POST"
                        enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Product Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $product->name ?? '') }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror"
                                        id="category" name="category"
                                        value="{{ old('category', $product->category ?? '') }}" list="categories" required>
                                    <datalist id="categories">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                    </datalist>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                        id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">Price <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                            id="price" name="price" value="{{ old('price', $product->price ?? '') }}"
                                            step="0.01" min="0" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="discount_price" class="form-label">Discount Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number"
                                            class="form-control @error('discount_price') is-invalid @enderror"
                                            id="discount_price" name="discount_price"
                                            value="{{ old('discount_price', $product->discount_price ?? '') }}"
                                            step="0.01" min="0">
                                    </div>
                                    @error('discount_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stock" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                        id="stock" name="stock" value="{{ old('stock', $product->stock ?? '') }}"
                                        min="0">
                                    <small class="form-text text-muted">Leave empty if not tracking stock</small>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="1"
                                            {{ old('status', $product->status ?? '1') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $product->status ?? '1') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Links -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="google_drive_link" class="form-label">Google Drive Link</label>
                                    <input type="url"
                                        class="form-control @error('google_drive_link') is-invalid @enderror"
                                        id="google_drive_link" name="google_drive_link"
                                        value="{{ old('google_drive_link', $product->google_drive_link ?? '') }}">
                                    @error('google_drive_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="youtube_video_link" class="form-label">YouTube Video Link</label>
                                    <input type="url"
                                        class="form-control @error('youtube_video_link') is-invalid @enderror"
                                        id="youtube_video_link" name="youtube_video_link"
                                        value="{{ old('youtube_video_link', $product->youtube_video_link ?? '') }}">
                                    @error('youtube_video_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="10">{{ old('description', $product->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Banner Image -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="banner_image" class="form-label">Banner Image</label>
                                    <input type="file"
                                        class="form-control @error('banner_image') is-invalid @enderror"
                                        id="banner_image" name="banner_image" accept="image/*">
                                    @error('banner_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if ($product && $product->banner_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                                alt="Current Banner" class="image-preview">
                                            <small class="form-text text-muted">Current banner image</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Images -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_images" class="form-label">Product Images</label>
                                    <input type="file"
                                        class="form-control @error('product_images.*') is-invalid @enderror"
                                        id="product_images" name="product_images[]" accept="image/*" multiple>
                                    @error('product_images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">You can select multiple images</small>

                                    @if ($product && $product->product_images)
                                        <div class="preview-container mt-2">
                                            @foreach ($product->product_images as $image)
                                                <div class="image-container">
                                                    <img src="{{ asset('uploads/products/' . $image) }}"
                                                        alt="Product Image" class="image-preview">
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="form-text text-muted">Current product images</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-end">
                            <a href="{{ route('adminProductList') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ $product ? 'Update Product' : 'Create Product' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#description').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        // Handle image upload if needed
                        console.log('Image upload:', files);
                    }
                }
            });

            // Auto-generate slug from name
            $('#name').on('input', function() {
                const name = $(this).val();
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('#slug').val(slug);
            });

            // Image preview for banner
            $('#banner_image').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove existing preview
                        $('.banner-preview').remove();

                        // Add new preview
                        const preview = $('<div class="banner-preview mt-2">' +
                            '<img src="' + e.target.result +
                            '" alt="Banner Preview" class="image-preview">' +
                            '<small class="form-text text-muted">New banner image preview</small>' +
                            '</div>');
                        $(this).closest('.form-group').append(preview);
                    }.bind(this);
                    reader.readAsDataURL(file);
                }
            });

            // Image preview for product images
            $('#product_images').on('change', function(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    // Remove existing preview
                    $('.product-images-preview').remove();

                    const previewContainer = $(
                        '<div class="product-images-preview preview-container mt-2"></div>');

                    Array.from(files).forEach(function(file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = $('<div class="image-container">' +
                                '<img src="' + e.target.result +
                                '" alt="Product Image Preview" class="image-preview">' +
                                '</div>');
                            previewContainer.append(preview);
                        };
                        reader.readAsDataURL(file);
                    });

                    $(this).closest('.form-group').append(previewContainer);
                    $(this).closest('.form-group').append(
                        '<small class="form-text text-muted">New product images preview</small>');
                }
            });

            // Form validation
            $('#productForm').on('submit', function(e) {
                const price = parseFloat($('#price').val());
                const discountPrice = parseFloat($('#discount_price').val());

                if (discountPrice && discountPrice >= price) {
                    e.preventDefault();
                    alert('Discount price must be less than regular price.');
                    return false;
                }
            });
        });
    </script>
@endsection
