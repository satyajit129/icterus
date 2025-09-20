@extends('backend.layouts.master')

@section('title', 'Product Details')

@section('custom_css')
    <style>
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }

        .product-thumbnail:hover,
        .product-thumbnail.active {
            border-color: #007bff;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .price-display {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .discount-price {
            color: #28a745;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
        }

        .badge-custom {
            font-size: 0.9rem;
            padding: 8px 12px;
        }

        .description-content {
            line-height: 1.6;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .modal-image {
            max-width: 100%;
            max-height: 80vh;
        }
    </style>
@endsection

{{-- Permission --}}
@php
    $user = auth()->user();
    $canEditProduct = $user->hasPermission('manage_product');
    $canDeleteProduct = $user->hasPermission('manage_product');
@endphp

@section('content')
    <div class="page-header">
        <h1 class="page-title">Product Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminProductList') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">{{ $product->name }}</h3>
                    <div>
                        @if ($canEditProduct)
                            <a href="{{ route('adminProductCreateOrEdit', $product->id) }}" class="btn btn-warning btn-sm">
                                <i class="fe fe-edit me-2"></i>Edit Product
                            </a>
                        @endif
                        @if ($canDeleteProduct)
                            <a href="{{ route('adminProductDelete', $product->id) }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="fe fe-trash me-2"></i>Delete Product
                            </a>
                        @endif
                        <a href="{{ route('adminProductList') }}" class="btn btn-secondary btn-sm">
                            <i class="fe fe-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Images -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5 class="mb-3">Product Images</h5>

                                @if ($product->banner_image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                            alt="{{ $product->name }}" class="product-image" id="mainImage">
                                    </div>
                                @endif

                                @if ($product->product_images && count($product->product_images) > 0)
                                    <div class="image-gallery">
                                        @foreach ($product->product_images as $index => $image)
                                            <img src="{{ asset('uploads/products/' . $image) }}"
                                                alt="Product Image {{ $index + 1 }}"
                                                class="product-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                                onclick="changeMainImage(this.src)">
                                        @endforeach
                                    </div>
                                @endif

                                @if (!$product->banner_image && (!$product->product_images || count($product->product_images) === 0))
                                    <div class="text-center text-muted">
                                        <i class="fe fe-image" style="font-size: 3rem;"></i>
                                        <p>No images available</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5 class="mb-3">Product Information</h5>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Name:</strong></div>
                                    <div class="col-sm-8">{{ $product->name }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Category:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge badge-primary">{{ $product->category }}</span>
                                    </div>
                                </div>

                                @if ($product->sku)
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>SKU:</strong></div>
                                        <div class="col-sm-8">{{ $product->sku }}</div>
                                    </div>
                                @endif

                                @if ($product->slug)
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Slug:</strong></div>
                                        <div class="col-sm-8">{{ $product->slug }}</div>
                                    </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8">
                                        <span
                                            class="badge {{ $product->status ? 'badge-success' : 'badge-danger' }} badge-custom">
                                            {{ $product->status_text }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Stock:</strong></div>
                                    <div class="col-sm-8">
                                        @if ($product->stock !== null)
                                            <span
                                                class="badge {{ $product->stock > 0 ? 'badge-success' : 'badge-danger' }} badge-custom">
                                                {{ $product->stock }} units
                                            </span>
                                        @else
                                            <span class="badge badge-secondary badge-custom">Not Tracked</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5 class="mb-3">Pricing</h5>

                                <div class="price-display">
                                    @if ($product->discount_price)
                                        <span class="discount-price">৳{{ $product->formatted_discount_price }}</span>
                                        <span class="original-price ml-2">৳{{ $product->formatted_price }}</span>
                                        <br>
                                        <small class="text-success">Save {{ $product->discount_percentage }}%</small>
                                    @else
                                        <span class="text-primary">৳{{ $product->formatted_price }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <h5 class="mb-3">External Links</h5>

                                @if ($product->google_drive_link)
                                    <div class="mb-2">
                                        <strong>Google Drive:</strong><br>
                                        <a href="{{ $product->google_drive_link }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fe fe-external-link me-1"></i>Open Link
                                        </a>
                                    </div>
                                @endif

                                @if ($product->youtube_video_link)
                                    <div class="mb-2">
                                        <strong>YouTube Video:</strong><br>
                                        <a href="{{ $product->youtube_video_link }}" target="_blank"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="fe fe-youtube me-1"></i>Watch Video
                                        </a>
                                    </div>
                                @endif

                                @if (!$product->google_drive_link && !$product->youtube_video_link)
                                    <p class="text-muted">No external links available</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($product->description)
                        <div class="info-card">
                            <h5 class="mb-3">Description</h5>
                            <div class="description-content">
                                {!! $product->description !!}
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="info-card">
                        <h5 class="mb-3">Timestamps</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i:s') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Image</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="Product Image" class="modal-image" id="modalImage">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        function changeMainImage(src) {
            // Update main image
            $('#mainImage').attr('src', src);

            // Update active thumbnail
            $('.product-thumbnail').removeClass('active');
            event.target.classList.add('active');
        }

        // Image click to open modal
        $(document).on('click', '.product-image, .product-thumbnail', function() {
            const src = $(this).attr('src');
            $('#modalImage').attr('src', src);
            $('#imageModal').modal('show');
        });
    </script>
@endsection
