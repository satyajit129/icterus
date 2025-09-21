<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - {{ $settings->website_name ?? 'Premium Quality Items' }}</title>
    <meta name="description"
        content="Browse our complete collection of premium products with the best quality and competitive prices.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Facebook Pixel -->
    @include('components.facebook-pixel')

    <!-- Google Tag Manager -->
    @include('components.google-tag-manager')

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-bg);
        }

        /* Header */
        .navbar {
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-color) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        /* Page Header */
        .page-header {
            background: var(--gradient);
            padding: 100px 0 60px;
            color: white;
            text-align: center;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Filters */
        .filters-section {
            background: white;
            padding: 30px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-filter {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-clear {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-clear:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .product-content {
            padding: 25px;
        }

        .product-category {
            color: var(--secondary-color);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .current-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .original-price {
            font-size: 1.1rem;
            color: #999;
            text-decoration: line-through;
        }

        .product-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-actions {
            display: block;
        }

        .btn-view {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .btn-view:hover {
            background: #2980b9;
            color: white;
            transform: translateY(-2px);
        }


        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 40px;
        }

        .page-link {
            border-radius: 10px;
            margin: 0 5px;
            border: 2px solid #e9ecef;
            color: var(--text-color);
            font-weight: 500;
        }

        .page-link:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .page-item.active .page-link {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* No Products */
        .no-products {
            text-align: center;
            padding: 80px 20px;
        }

        .no-products i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-products h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .no-products p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 40px;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--secondary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .filter-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                @if ($settings->logo)
                    <img src="{{ asset('uploads/' . $settings->logo) }}"
                        alt="{{ $settings->website_name ?? 'ProductHub' }}" height="40" class="me-2">
                @else
                    <i class="fas fa-shopping-bag me-2"></i>
                @endif
                {{-- {{ $settings->website_name ?? 'ProductHub' }} --}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('public.products') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">All Products</h1>
            <p class="page-subtitle">Discover our complete collection of premium products</p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <form method="GET" action="{{ route('public.products') }}" id="filterForm">
                <div class="row">
                    <div class="col-lg-4 col-md-4 mb-3">
                        <div class="filter-card">
                            <h6 class="filter-title">Search Products</h6>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Search by name, category...">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 mb-3">
                        <div class="filter-card">
                            <h6 class="filter-title">Category</h6>
                            <select class="form-select" name="category">
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
                    <div class="col-lg-4 col-md-4 mb-3">
                        <div class="filter-card">
                            <h6 class="filter-title">Actions</h6>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-filter">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                                <a href="{{ route('public.products') }}" class="btn btn-clear">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            @if ($products->count() > 0)
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="product-card">
                                <div class="product-image">
                                    @if ($product->banner_image)
                                        <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                            alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <img src="https://via.placeholder.com/300x250?text=No+Image"
                                            alt="{{ $product->name }}" loading="lazy">
                                    @endif

                                    @if ($product->discount_price)
                                        <div class="product-badge">
                                            {{ $product->discount_percentage }}% OFF
                                        </div>
                                    @endif
                                </div>

                                <div class="product-content">
                                    <div class="product-category">{{ $product->category }}</div>
                                    <h3 class="product-title">{{ $product->name }}</h3>

                                    <div class="product-price">
                                        <span
                                            class="current-price">৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</span>
                                        @if ($product->discount_price)
                                            <span class="original-price">৳{{ $product->formatted_price }}</span>
                                        @endif
                                    </div>

                                    @if ($product->description)
                                        <div class="product-description">{!! $product->description !!}</div>
                                    @endif

                                    <div class="product-actions">
                                        <a href="{{ route('public.product.details', $product->slug) }}"
                                            class="btn-view"
                                            onclick="
                                                FacebookPixel.trackViewContent({
                                                    content_ids: ['{{ $product->id }}'],
                                                    content_type: 'product',
                                                    content_name: '{{ $product->name }}',
                                                    content_category: '{{ $product->category }}',
                                                    value: {{ $product->final_price }},
                                                    currency: 'BDT'
                                                });
                                                GoogleTagManager.trackViewItem({
                                                    item_id: '{{ $product->id }}',
                                                    item_name: '{{ $product->name }}',
                                                    item_category: '{{ $product->category }}',
                                                    item_brand: '{{ $settings->website_name ?? 'Unknown' }}',
                                                    price: {{ $product->final_price }},
                                                    value: {{ $product->final_price }},
                                                    quantity: 1
                                                });
                                            ">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="no-products">
                    <i class="fas fa-search"></i>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your search criteria or browse all products.</p>
                    <a href="{{ route('public.products') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-th-large me-2"></i>View All Products
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="fas fa-shopping-bag me-2"></i>{{ $settings->website_name ?? 'ProductHub' }}</h5>
                    <p>Your trusted partner for premium quality products. We deliver excellence with every order.</p>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('landing') }}" class="text-light">Home</a></li>
                        <li><a href="{{ route('public.products') }}" class="text-light">Products</a></li>
                        <li><a href="#contact" class="text-light">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Contact Info</h5>
                    @if ($settings->phone)
                        <p><i class="fas fa-phone me-2"></i> {{ $settings->phone }}</p>
                    @endif
                    @if ($settings->whatsapp)
                        <p><i class="fab fa-whatsapp me-2"></i> {{ $settings->whatsapp }}</p>
                    @endif
                    @if ($settings->website_email)
                        <p><i class="fas fa-envelope me-2"></i> {{ $settings->website_email }}</p>
                    @endif
                    @if ($settings->address)
                        <p><i class="fas fa-map-marker-alt me-2"></i> {{ $settings->address }}</p>
                    @endif
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ $settings->website_name ?? 'ProductHub' }}.
                    {{ $settings->copy_right_text ?? 'All rights reserved.' }}</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-submit form on filter change
        document.querySelectorAll('select[name="category"], select[name="stock_status"]').forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
