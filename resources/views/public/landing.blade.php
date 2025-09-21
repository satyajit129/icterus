<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->website_name ?? 'Our Products' }} - Premium Quality Items</title>
    <meta name="description"
        content="Discover our premium collection of products with the best quality and competitive prices.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #fff;
        }

        /* Header */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
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

        /* Hero Section */
        .hero-section {
            background: var(--gradient);
            padding: 100px 0;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,100 0,80"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-hero {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-hero:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: var(--light-bg);
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
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


        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: white;
        }

        .feature-card {
            text-align: center;
            padding: 40px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .feature-description {
            color: #666;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: white;
            padding: 60px 0 30px;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
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
                        <a class="nav-link" href="{{ route('public.products') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">{{ $settings->hero_title ?? 'Discover Amazing Products' }}</h1>
                <p class="hero-subtitle">
                    {{ $settings->hero_subtitle ?? 'Premium quality items at unbeatable prices. Shop with confidence and enjoy fast delivery.' }}
                </p>
                <a href="{{ route('public.products') }}" class="btn-hero">
                    <i class="fas fa-shopping-cart me-2"></i>Shop Now
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-title">
                <h2>{{ $settings->featured_products_title ?? 'Featured Products' }}</h2>
                <p>{{ $settings->featured_products_subtitle ?? 'Handpicked items that our customers love. Quality guaranteed with competitive pricing.' }}
                </p>
            </div>

            @if ($featuredProducts->count() > 0)
                <div class="row">
                    @foreach ($featuredProducts as $product)
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
                                        <p class="product-description">{{ strip_tags($product->description) }}</p>
                                    @endif

                                    <div class="product-actions">
                                        <a href="{{ route('public.product.details', $product->slug) }}"
                                            class="btn-view">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('public.products') }}" class="btn-hero">
                        <i class="fas fa-th-large me-2"></i>View All Products
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h3>No Products Available</h3>
                    <p class="text-muted">Check back soon for amazing products!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h4 class="feature-title">{{ $settings->fast_delivery_title ?? 'Fast Delivery' }}</h4>
                        <p class="feature-description">
                            {{ $settings->fast_delivery_description ?? 'Quick and reliable delivery to your doorstep within 24-48 hours.' }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="feature-title">{{ $settings->quality_guarantee_title ?? 'Quality Guarantee' }}</h4>
                        <p class="feature-description">
                            {{ $settings->quality_guarantee_description ?? '100% authentic products with quality assurance and warranty.' }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="feature-title">{{ $settings->support_title ?? '24/7 Support' }}</h4>
                        <p class="feature-description">
                            {{ $settings->support_description ?? 'Round-the-clock customer support to help you with any queries.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="fas fa-shopping-bag me-2"></i>{{ $settings->website_name ?? 'ProductHub' }}</h5>
                    <p>Your trusted partner for premium quality products. We deliver excellence with every order.</p>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('landing') }}">Home</a></li>
                        <li><a href="{{ route('public.products') }}">Products</a></li>
                        <li><a href="#contact">Contact Us</a></li>
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

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>

</html>
