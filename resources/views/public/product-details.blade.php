<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $settings->website_name ?? 'Product Details' }}</title>
    <meta name="description" content="{{ strip_tags($product->description) }}">

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
            --success-color: #27ae60;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bkash-color: #e2136e;
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

        /* Breadcrumb */
        .breadcrumb-section {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--text-color);
        }

        /* Product Details */
        .product-details {
            padding: 40px 0;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .product-image-main {
            height: 500px;
            overflow: hidden;
            position: relative;
        }

        .product-image-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .product-thumbnail.active {
            border-color: var(--secondary-color);
        }

        .product-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 40px;
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
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .original-price {
            font-size: 1.5rem;
            color: #999;
            text-decoration: line-through;
        }

        .discount-badge {
            background: var(--accent-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .product-description {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .product-features {
            margin-bottom: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #666;
        }

        .feature-item i {
            color: var(--success-color);
            margin-right: 10px;
            width: 20px;
        }

        .product-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn-order {
            background: var(--bkash-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-order:hover {
            background: #c10e5a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(226, 19, 110, 0.3);
        }


        /* Order Modal */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: var(--gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .payment-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method.selected {
            border-color: var(--bkash-color);
            background: rgba(226, 19, 110, 0.05);
        }

        .payment-method input[type="radio"] {
            margin-right: 15px;
        }

        .payment-logo {
            width: 50px;
            height: 50px;
            background: var(--bkash-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .payment-logo i {
            color: white;
            font-size: 1.5rem;
        }

        .payment-info h6 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
        }

        .payment-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .order-summary {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-total {
            border-top: 2px solid #e9ecef;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        /* Related Products */
        .related-products {
            padding: 60px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .related-product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .related-product-image {
            height: 200px;
            overflow: hidden;
        }

        .related-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-product-content {
            padding: 20px;
        }

        .related-product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .related-product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .product-title {
                font-size: 1.8rem;
            }

            .current-price {
                font-size: 2rem;
            }

            .product-actions {
                flex-direction: column;
            }

            .product-info {
                padding: 25px;
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
                        <a class="nav-link" href="{{ route('public.products') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('landing') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.products') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Details -->
    <section class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-card">
                        <div class="product-image-main">
                            @if ($product->banner_image)
                                <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                    alt="{{ $product->name }}" id="mainImage">
                            @else
                                <img src="https://via.placeholder.com/500x500?text=No+Image" alt="{{ $product->name }}"
                                    id="mainImage">
                            @endif
                        </div>

                        @if ($product->product_images && count($product->product_images) > 0)
                            <div class="product-thumbnails">
                                @if ($product->banner_image)
                                    <div class="product-thumbnail active"
                                        onclick="changeMainImage('{{ asset('uploads/products/' . $product->banner_image) }}')">
                                        <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                            alt="Main Image">
                                    </div>
                                @endif
                                @foreach ($product->product_images as $index => $image)
                                    <div class="product-thumbnail {{ $index === 0 && !$product->banner_image ? 'active' : '' }}"
                                        onclick="changeMainImage('{{ asset('uploads/products/' . $image) }}')">
                                        <img src="{{ asset('uploads/products/' . $image) }}"
                                            alt="Product Image {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product-info">
                        <div class="product-category">{{ $product->category }}</div>
                        <h1 class="product-title">{{ $product->name }}</h1>

                        <div class="product-price">
                            <span
                                class="current-price">৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</span>
                            @if ($product->discount_price)
                                <span class="original-price">৳{{ $product->formatted_price }}</span>
                                <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                        </div>

                        @if ($product->description)
                            <div class="product-description">
                                {!! $product->description !!}
                            </div>
                        @endif

                        <div class="product-features">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Premium Quality Materials</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Fast & Secure Delivery</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>30-Day Return Policy</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>24/7 Customer Support</span>
                            </div>
                        </div>

                        <div class="product-actions">
                            <a href="{{ route('public.order.page', \App\Services\EncryptionService::encryptProductId($product->id)) }}"
                                class="btn btn-order">
                                <i class="fas fa-shopping-cart me-2"></i>Order Now
                            </a>
                        </div>

                        @if ($product->youtube_video_link)
                            <div class="mt-4">
                                <h6>Additional Resources:</h6>
                                <a href="{{ $product->youtube_video_link }}" target="_blank"
                                    class="btn btn-outline-danger">
                                    <i class="fab fa-youtube me-1"></i>Watch Video
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
        <section class="related-products">
            <div class="container">
                <div class="section-title">
                    <h3>Related Products</h3>
                    <p>You might also like these products</p>
                </div>

                <div class="row">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="col-lg-3 col-md-6">
                            <div class="related-product-card">
                                <div class="related-product-image">
                                    @if ($relatedProduct->banner_image)
                                        <img src="{{ asset('uploads/products/' . $relatedProduct->banner_image) }}"
                                            alt="{{ $relatedProduct->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200?text=No+Image"
                                            alt="{{ $relatedProduct->name }}">
                                    @endif
                                </div>
                                <div class="related-product-content">
                                    <h5 class="related-product-title">{{ $relatedProduct->name }}</h5>
                                    <div class="related-product-price">
                                        ৳{{ $relatedProduct->formatted_discount_price ?? $relatedProduct->formatted_price }}
                                        @if ($relatedProduct->discount_price)
                                            <small class="text-muted text-decoration-line-through ms-2">
                                                ৳{{ $relatedProduct->formatted_price }}
                                            </small>
                                        @endif
                                    </div>
                                    <a href="{{ route('public.product.details', $relatedProduct->slug) }}"
                                        class="btn btn-outline-primary btn-sm mt-2 w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
        // Change main image
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;

            // Update active thumbnail
            document.querySelectorAll('.product-thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            event.target.closest('.product-thumbnail').classList.add('active');
        }



        // Smooth scrolling
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
