@extends('backend.layouts.master')

@section('title', 'Products')

@section('custom_css')
    <style>
        .table td {
            vertical-align: middle !important;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .badge-status {
            font-size: 0.75rem;
        }

        .search-filters {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
@endsection

{{-- Permission --}}
@php
    $user = auth()->user();
    $canAddProduct = $user->hasPermission('manage_product');
    $canEditProduct = $user->hasPermission('manage_product');
    $canDeleteProduct = $user->hasPermission('manage_product');
    $canViewProduct = $user->hasPermission('manage_product');
    $canDownloadProduct = $user->hasPermission('manage_product');
@endphp

@section('content')
    <div class="page-header">
        <h1 class="page-title">Products</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <!-- Search and Filters -->
            <div class="search-filters">
                <form method="GET" action="{{ route('adminProductList') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Search by name, category, SKU...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat }}"
                                            {{ request('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="stock_status">Stock Status</label>
                                <select class="form-control" id="stock_status" name="stock_status">
                                    <option value="">All Stock</option>
                                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                        In Stock</option>
                                    <option value="out_of_stock"
                                        {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                                    </option>
                                    <option value="not_tracked"
                                        {{ request('stock_status') == 'not_tracked' ? 'selected' : '' }}>Not Tracked
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="min_price">Min Price</label>
                                <input type="number" class="form-control" id="min_price" name="min_price"
                                    value="{{ request('min_price') }}" placeholder="Min Price" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">
                                    <i class="fe fe-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="max_price">Max Price</label>
                                <input type="number" class="form-control" id="max_price" name="max_price"
                                    value="{{ request('max_price') }}" placeholder="Max Price" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a href="{{ route('adminProductList') }}" class="btn btn-secondary form-control">
                                    <i class="fe fe-refresh-cw"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title">Products Data</h3>
                    <div>
                        @if ($canDownloadProduct)
                            <a href="{{ route('adminProductExport', request()->query()) }}" class="btn btn-primary btn-sm">
                                <i class="fe fe-download me-2"></i>Download Data
                            </a>
                        @endif

                        @if ($canAddProduct)
                            <a href="{{ route('adminProductCreateOrEdit') }}">
                                <button type="button" class="btn btn-primary btn-sm"><i class="fe fe-plus me-2"></i>Add
                                    Product</button>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">Image</th>
                                    <th class="wd-20p border-bottom-0">Name</th>
                                    <th class="wd-15p border-bottom-0">Category</th>
                                    <th class="wd-10p border-bottom-0">SKU</th>
                                    <th class="wd-10p border-bottom-0">Price</th>
                                    <th class="wd-10p border-bottom-0">Stock</th>
                                    <th class="wd-10p border-bottom-0">Status</th>
                                    <th class="wd-10p border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @if ($product->banner_image)
                                                <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                                    alt="{{ $product->name }}" class="product-image">
                                            @else
                                                <div
                                                    class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fe fe-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                @if ($product->discount_price)
                                                    <br><small class="text-success">Discount:
                                                        {{ $product->discount_percentage }}%</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $product->category }}</td>
                                        <td>{{ $product->sku ?? 'N/A' }}</td>
                                        <td>
                                            <div>
                                                <strong>৳{{ $product->formatted_price }}</strong>
                                                @if ($product->discount_price)
                                                    <br><small
                                                        class="text-success">৳{{ $product->formatted_discount_price }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($product->stock !== null)
                                                <span
                                                    class="badge {{ $product->stock > 0 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $product->stock }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Not Tracked</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $product->status ? 'badge-success' : 'badge-danger' }} badge-status">
                                                {{ $product->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($canViewProduct)
                                                    <a href="{{ route('adminProductView', $product->id) }}"
                                                        class="btn btn-sm btn-info" title="View">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                @endif
                                                @if ($canEditProduct)
                                                    <a href="{{ route('adminProductCreateOrEdit', $product->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                @endif
                                                @if ($canDeleteProduct)
                                                    <a href="{{ route('adminProductDelete', $product->id) }}"
                                                        class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="fe fe-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            // Auto-submit form on filter change
            $('#category, #status, #stock_status').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>
@endsection
