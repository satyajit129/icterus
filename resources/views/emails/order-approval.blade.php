<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Approved - {{ $settings->website_name ?? 'Your Store' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 30px 20px;
        }

        .success-badge {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .order-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .order-details h3 {
            color: #495057;
            margin-top: 0;
            font-size: 18px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }

        .detail-value {
            color: #495057;
        }

        .product-section {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
            float: left;
        }

        .product-info {
            overflow: hidden;
        }

        .product-name {
            font-size: 20px;
            font-weight: 600;
            color: #212529;
            margin: 0 0 10px 0;
        }

        .product-price {
            font-size: 18px;
            color: #28a745;
            font-weight: 600;
            margin: 0;
        }

        .google-drive-section {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .google-drive-section h3 {
            margin: 0 0 15px 0;
            font-size: 20px;
        }

        .google-drive-section p {
            margin: 0 0 15px 0;
            font-size: 16px;
        }

        .download-btn {
            display: inline-block;
            background-color: white;
            color: #ee5a24;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }

        .contact-info {
            margin-top: 15px;
        }

        .contact-info a {
            color: #007bff;
            text-decoration: none;
        }

        .whatsapp-section {
            background-color: #25d366;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .whatsapp-section a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }

            .content {
                padding: 20px 15px;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if ($settings->logo)
                <img src="{{ asset('uploads/' . $settings->logo) }}" alt="{{ $settings->website_name ?? 'Logo' }}"
                    style="max-height: 50px; margin-bottom: 15px;">
            @endif
            <h1>🎉 Order Approved!</h1>
            <p>Your order has been successfully approved and is ready for delivery</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="success-badge">
                ✅ Order Status: Approved
            </div>

            <p>Dear <strong>{{ $order->customer_name }}</strong>,</p>

            <p>Great news! Your order has been approved and is now being processed. We're excited to get your product to
                you as soon as possible.</p>

            <!-- Order Details -->
            <div class="order-details">
                <h3>📋 Order Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Order Number:</span>
                    <span class="detail-value"><strong>{{ $order->order_number }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value">
                        <span style="color: #28a745; font-weight: 600;">✅ {{ ucfirst($order->payment_status) }}</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value"><code>{{ $order->bkash_transaction_id }}</code></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value"><strong>৳{{ number_format($order->amount, 2) }}</strong></span>
                </div>
            </div>

            <!-- Product Details -->
            <div class="product-section">
                <h3>🛍️ Product Details</h3>
                <div class="clearfix">
                    @if ($product->banner_image)
                        <img src="{{ asset('uploads/products/' . $product->banner_image) }}" alt="{{ $product->name }}"
                            class="product-image">
                    @endif
                    <div class="product-info">
                        <h4 class="product-name">{{ $product->name }}</h4>
                        <p><strong>Category:</strong> {{ $product->category }}</p>
                        @if ($product->sku)
                            <p><strong>SKU:</strong> {{ $product->sku }}</p>
                        @endif
                        <p class="product-price">
                            @if ($product->discount_price)
                                ৳{{ number_format($product->discount_price, 2) }}
                                <span
                                    style="text-decoration: line-through; color: #6c757d; font-size: 14px; margin-left: 10px;">
                                    ৳{{ number_format($product->price, 2) }}
                                </span>
                                <span style="color: #28a745; font-size: 14px; margin-left: 10px;">
                                    (Save {{ $product->discount_percentage }}%)
                                </span>
                            @else
                                ৳{{ number_format($product->price, 2) }}
                            @endif
                        </p>
                    </div>
                </div>

                @if ($product->description)
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                        <h5>Product Description:</h5>
                        <div style="color: #495057; line-height: 1.6;">
                            {!! $product->description !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Google Drive Link -->
            @if ($product->google_drive_link)
                <div class="google-drive-section">
                    <h3>📁 Download Your Product</h3>
                    <p>Your product files are ready for download. Click the button below to access your files.</p>
                    <a href="{{ $product->google_drive_link }}" target="_blank" class="download-btn">
                        📥 Download Now
                    </a>
                </div>
            @endif

            <!-- WhatsApp Support -->
            <div class="whatsapp-section">
                <h4 style="margin: 0 0 10px 0;">💬 Need Help?</h4>
                <p style="margin: 0 0 10px 0;">Contact us on WhatsApp for any questions or support</p>
                @if ($settings->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp) }}" target="_blank">
                        📱 Chat on WhatsApp
                    </a>
                @endif
            </div>

            <p style="margin-top: 30px;">
                Thank you for choosing <strong>{{ $settings->website_name ?? 'our store' }}</strong>!
                We appreciate your business and look forward to serving you again.
            </p>

            <p>
                Best regards,<br>
                <strong>{{ $settings->website_name ?? 'The Team' }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if ($settings->website_name)
                <p><strong>{{ $settings->website_name }}</strong></p>
            @endif
            @if ($settings->website_email)
                <p>Email: <a href="mailto:{{ $settings->website_email }}">{{ $settings->website_email }}</a></p>
            @endif
            @if ($settings->phone)
                <p>Phone: <a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a></p>
            @endif
            @if ($settings->address)
                <p>{{ $settings->address }}</p>
            @endif
            @if ($settings->copy_right_text)
                <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
                    {{ $settings->copy_right_text }}
                </p>
            @endif
        </div>
    </div>
</body>

</html>
