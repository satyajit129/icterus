<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order {{ $product->name }} - {{ $settings->website_name ?? 'Order Now' }}</title>

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
            --success-color: #27ae60;
            --bkash-color: #e2136e;
            --text-color: #2c3e50;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .order-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .order-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        .order-header {
            background: var(--gradient);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .order-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .order-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .order-body {
            padding: 40px;
        }

        .product-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            object-fit: cover;
            margin-right: 20px;
        }

        .product-details h4 {
            margin: 0 0 10px 0;
            color: var(--primary-color);
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--bkash-color);
        }

        .form-section {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--secondary-color);
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
            border: 2px solid var(--bkash-color);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .payment-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .payment-logo {
            width: 60px;
            height: 60px;
            background: var(--bkash-color);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        .payment-logo i {
            color: white;
            font-size: 2rem;
        }

        .payment-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .amount-highlight {
            background: var(--bkash-color);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-highlight h4 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .payment-steps {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .payment-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            border-left: 4px solid var(--bkash-color);
        }

        .step-number {
            width: 30px;
            height: 30px;
            background: var(--bkash-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .step-content h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: var(--primary-color);
        }

        .step-content p {
            margin: 0;
            color: #666;
            font-size: 0.95rem;
        }

        .merchant-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
        }

        .merchant-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 5px 0;
        }

        .copy-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: #2980b9;
        }

        .transaction-section {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .btn-submit {
            background: var(--bkash-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background: #c10e5a;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(226, 19, 110, 0.3);
        }

        .btn-submit.btn-success {
            background: #28a745;
        }

        .btn-submit.btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }

        .payment-method-selection {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
        }

        .payment-option {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            border-color: var(--bkash-color);
            transform: translateY(-2px);
        }

        .payment-option.active {
            border-color: var(--bkash-color);
            background: #fff5f8;
        }

        .payment-option-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .payment-option-header i {
            font-size: 1.5rem;
            color: var(--bkash-color);
            margin-right: 10px;
        }

        .payment-option-header h6 {
            margin: 0;
            color: var(--primary-color);
        }

        /* Toastr Custom Styling */
        .toast-top-right {
            top: 20px;
            right: 20px;
        }

        .toast-success {
            background-color: #28a745;
        }

        .toast-error {
            background-color: #dc3545;
        }

        .toast-info {
            background-color: #17a2b8;
        }

        .toast-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-back {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: var(--primary-color);
            color: white;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-title {
                font-size: 2rem;
            }

            .order-body {
                padding: 25px;
            }

            .product-image {
                width: 80px;
                height: 80px;
                margin-right: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="order-container">
        <div class="order-card">
            <div class="order-header">
                <h1 class="order-title">Place Your Order</h1>
                <p class="order-subtitle">Complete your purchase with secure Bkash payment</p>
            </div>

            <div class="order-body">
                <!-- Back Button -->
                <a href="{{ route('public.product.details', $product->slug) }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to Product
                </a>

                <!-- Product Information -->
                <div class="product-info">
                    <div class="d-flex align-items-center">
                        @if ($product->banner_image)
                            <img src="{{ asset('uploads/products/' . $product->banner_image) }}"
                                alt="{{ $product->name }}" class="product-image">
                        @else
                            <img src="https://via.placeholder.com/100x100?text=No+Image" alt="{{ $product->name }}"
                                class="product-image">
                        @endif
                        <div class="product-details">
                            <h4>{{ $product->name }}</h4>
                            <p class="text-muted mb-2">{{ $product->category }}</p>
                            <div class="product-price">
                                ৳{{ $product->formatted_discount_price ?? $product->formatted_price }}
                                @if ($product->discount_price)
                                    <small class="text-muted text-decoration-line-through ms-2">
                                        ৳{{ $product->formatted_price }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Form -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Customer Information
                    </h3>

                    <form id="orderForm" method="POST" action="{{ route('public.order') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_mobile" class="form-label">Mobile Number *</label>
                                <input type="tel" class="form-control" id="customer_mobile" name="customer_mobile"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_whatsapp" class="form-label">WhatsApp Number</label>
                                <input type="tel" class="form-control" id="customer_whatsapp"
                                    name="customer_whatsapp">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Any special instructions or requirements..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Payment Instructions -->
                <div class="payment-section">
                    <div class="payment-header">
                        <div class="payment-logo">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="payment-title">Complete Payment with Bkash</h3>
                    </div>

                    <div class="amount-highlight">
                        <h4>Pay ৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</h4>
                        <p class="mb-0">Send this exact amount to complete your order</p>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="payment-method-selection mb-4">
                        <h6 class="mb-3">Choose Payment Method:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="payment-option active" id="appMethod">
                                    <div class="payment-option-header">
                                        <i class="fas fa-mobile-alt"></i>
                                        <h6>Mobile App</h6>
                                    </div>
                                    <p class="small text-muted">বিকাশ মোবাইল অ্যাপ ব্যবহার করুন</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-option" id="manualMethod">
                                    <div class="payment-option-header">
                                        <i class="fas fa-phone"></i>
                                        <h6>Manual Dial (*247#)</h6>
                                    </div>
                                    <p class="small text-muted">*২৪৭# ডায়াল করে ম্যানুয়াল পেমেন্ট করুন</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile App Steps -->
                    <ol class="payment-steps" id="appSteps">
                        <li class="payment-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6>Open Bkash App</h6>
                                <p>Launch your Bkash mobile app on your smartphone</p>
                                <small class="text-muted">আপনার স্মার্টফোনে বিকাশ মোবাইল অ্যাপ খুলুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6>Go to Payment</h6>
                                <p>Tap on "Payment" option from the main menu</p>
                                <small class="text-muted">মূল মেনু থেকে "পেমেন্ট" অপশনে ট্যাপ করুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6>Enter Merchant Number</h6>
                                <p>Payment to:</p>
                                <div class="merchant-info">
                                    <div class="merchant-number" id="merchantNumber">
                                        {{ trim($settings->bkash_merchant_number ?? '01741909808') }}</div>
                                    <button class="copy-btn" onclick="copyMerchantNumber()">
                                        <i class="fas fa-copy me-1"></i>Copy
                                    </button>
                                </div>
                                <small class="text-muted">উপরের নম্বরে পেমেন্ট করুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h6>Enter Amount</h6>
                                <p>Enter the exact amount:
                                    <strong>৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</strong>
                                </p>
                                <small class="text-muted">সঠিক পরিমাণ লিখুন:
                                    ৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">5</div>
                            <div class="step-content">
                                <h6>Complete Payment</h6>
                                <p>Enter your Bkash PIN and complete the transaction</p>
                                <small class="text-muted">আপনার বিকাশ পিন লিখে লেনদেন সম্পন্ন করুন</small>
                            </div>
                        </li>
                    </ol>

                    <!-- Manual Dial Steps -->
                    <ol class="payment-steps" id="manualSteps" style="display: none;">
                        <li class="payment-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6>Dial *247#</h6>
                                <p>Dial *247# from your mobile phone</p>
                                <small class="text-muted">আপনার মোবাইল ফোন থেকে *২৪৭# ডায়াল করুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6>Select Payment</h6>
                                <p>Select "Payment" from the menu options</p>
                                <small class="text-muted">মেনু অপশন থেকে "পেমেন্ট" নির্বাচন করুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6>Enter Merchant Number</h6>
                                <p>Enter the merchant number:</p>
                                <div class="merchant-info">
                                    <div class="merchant-number" id="merchantNumberManual">
                                        {{ trim($settings->bkash_merchant_number ?? '01741909808') }}</div>
                                    <button class="copy-btn" onclick="copyMerchantNumberManual()">
                                        <i class="fas fa-copy me-1"></i>Copy
                                    </button>
                                </div>
                                <small class="text-muted">উপরের নম্বরে পেমেন্ট করুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h6>Enter Amount</h6>
                                <p>Enter the exact amount:
                                    <strong>৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</strong>
                                </p>
                                <small class="text-muted">সঠিক পরিমাণ লিখুন:
                                    ৳{{ $product->formatted_discount_price ?? $product->formatted_price }}</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">5</div>
                            <div class="step-content">
                                <h6>Enter PIN & Confirm</h6>
                                <p>Enter your Bkash PIN and confirm the transaction</p>
                                <small class="text-muted">আপনার বিকাশ পিন লিখে লেনদেন নিশ্চিত করুন</small>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">6</div>
                            <div class="step-content">
                                <h6>Save Transaction ID</h6>
                                <p>Save the transaction ID from the confirmation message</p>
                                <small class="text-muted">নিশ্চিতকরণ বার্তা থেকে ট্রানজেকশন আইডি সংরক্ষণ করুন</small>
                            </div>
                        </li>
                    </ol>
                </div>

                <!-- Transaction Verification -->
                <div class="transaction-section">
                    <h3 class="section-title">
                        <i class="fas fa-check-circle"></i>
                        Verify Your Payment
                    </h3>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Required:</strong> You must complete the Bkash payment first and enter your Transaction
                        ID below before placing the order. After completing the payment, you will receive a confirmation
                        SMS from Bkash with your Transaction ID.
                    </div>

                    <form id="verifyForm" method="POST" action="{{ route('public.order.verify') }}">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id">

                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">
                                <i class="fas fa-credit-card me-1"></i>Transaction ID *
                                <span class="text-danger">(Required)</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="transaction_id"
                                name="transaction_id" placeholder="Enter your Bkash Transaction ID (e.g., 8A7B9C2D1E)"
                                required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                You can find this in your Bkash confirmation SMS. Example: 8A7B9C2D1E
                            </div>
                        </div>

                        {{--  <button type="submit" class="btn-submit" id="verifyBtn" disabled>
                            <i class="fas fa-check me-2"></i>Verify Payment
                        </button> --}}
                    </form>
                </div>

                <!-- Submit Order Button -->
                <div class="text-center">
                    <button type="submit" form="orderForm" class="btn-submit" id="submitOrder">
                        <i class="fas fa-shopping-cart me-2"></i>Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">

    <!-- jQuery (required for toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

    <script>
        let orderId = null;

        // Track ViewContent when page loads
        document.addEventListener('DOMContentLoaded', function() {
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
        });

        // Initialize toastr options
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        });

        // Helper functions for showing messages
        function showSuccessMessage(message) {
            if (typeof toastr !== 'undefined' && toastr.success) {
                toastr.success(message, 'Success');
            } else {
                alert(message);
            }
        }

        function showErrorMessage(message) {
            if (typeof toastr !== 'undefined' && toastr.error) {
                toastr.error(message, 'Error');
            } else {
                alert(message);
            }
        }

        // Copy merchant number
        function copyMerchantNumber() {
            const merchantNumberElement = document.getElementById('merchantNumber');
            const merchantNumber = merchantNumberElement.textContent.trim();

            // Fallback method for older browsers
            function fallbackCopyTextToClipboard(text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textArea);
                    return successful;
                } catch (err) {
                    document.body.removeChild(textArea);
                    return false;
                }
            }

            // Try modern clipboard API first, then fallback
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(merchantNumber).then(() => {
                    showSuccessMessage('Merchant number copied to clipboard!');
                }).catch(() => {
                    // Fallback if clipboard API fails
                    if (fallbackCopyTextToClipboard(merchantNumber)) {
                        showSuccessMessage('Merchant number copied to clipboard!');
                    } else {
                        showErrorMessage('Failed to copy merchant number. Please copy manually: ' + merchantNumber);
                    }
                });
            } else {
                // Use fallback method
                if (fallbackCopyTextToClipboard(merchantNumber)) {
                    showSuccessMessage('Merchant number copied to clipboard!');
                } else {
                    showErrorMessage('Failed to copy merchant number. Please copy manually: ' + merchantNumber);
                }
            }
        }

        // Copy merchant number for manual method
        function copyMerchantNumberManual() {
            const merchantNumberElement = document.getElementById('merchantNumberManual');
            const merchantNumber = merchantNumberElement.textContent.trim();

            // Fallback method for older browsers
            function fallbackCopyTextToClipboard(text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textArea);
                    return successful;
                } catch (err) {
                    document.body.removeChild(textArea);
                    return false;
                }
            }

            // Try modern clipboard API first, then fallback
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(merchantNumber).then(() => {
                    showSuccessMessage('Merchant number copied to clipboard!');
                }).catch(() => {
                    // Fallback if clipboard API fails
                    if (fallbackCopyTextToClipboard(merchantNumber)) {
                        showSuccessMessage('Merchant number copied to clipboard!');
                    } else {
                        showErrorMessage('Failed to copy merchant number. Please copy manually: ' + merchantNumber);
                    }
                });
            } else {
                // Use fallback method
                if (fallbackCopyTextToClipboard(merchantNumber)) {
                    showSuccessMessage('Merchant number copied to clipboard!');
                } else {
                    showErrorMessage('Failed to copy merchant number. Please copy manually: ' + merchantNumber);
                }
            }
        }

        // Payment method selection
        document.getElementById('appMethod').addEventListener('click', function() {
            document.getElementById('appMethod').classList.add('active');
            document.getElementById('manualMethod').classList.remove('active');
            document.getElementById('appSteps').style.display = 'block';
            document.getElementById('manualSteps').style.display = 'none';
        });

        document.getElementById('manualMethod').addEventListener('click', function() {
            document.getElementById('manualMethod').classList.add('active');
            document.getElementById('appMethod').classList.remove('active');
            document.getElementById('manualSteps').style.display = 'block';
            document.getElementById('appSteps').style.display = 'none';
        });

        // Form submission
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Track InitiateCheckout event
            FacebookPixel.trackInitiateCheckout({
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                content_name: '{{ $product->name }}',
                content_category: '{{ $product->category }}',
                value: {{ $product->final_price }},
                currency: 'BDT',
                num_items: 1
            });

            // Track GTM begin_checkout event
            GoogleTagManager.trackBeginCheckout({
                currency: 'BDT',
                value: {{ $product->final_price }},
                items: [{
                    item_id: '{{ $product->id }}',
                    item_name: '{{ $product->name }}',
                    item_category: '{{ $product->category }}',
                    item_brand: '{{ $settings->website_name ?? 'Unknown' }}',
                    price: {{ $product->final_price }},
                    quantity: 1
                }]
            });

            // Get form data
            const formData = new FormData(this);
            const customerName = formData.get('customer_name');
            const customerEmail = formData.get('customer_email');
            const customerMobile = formData.get('customer_mobile');
            const customerWhatsapp = formData.get('customer_whatsapp');
            const transactionId = document.getElementById('transaction_id').value;

            // Add transaction ID to form data
            formData.append('bkash_transaction_id', transactionId);

            // Validation
            let errors = [];

            if (!customerName || customerName.trim() === '') {
                errors.push('Full Name is required');
            }

            if (!customerEmail || customerEmail.trim() === '') {
                errors.push('Email Address is required');
            } else if (!isValidEmail(customerEmail)) {
                errors.push('Please enter a valid email address');
            }

            if (!customerMobile || customerMobile.trim() === '') {
                errors.push('Mobile Number is required');
            }

            if (!transactionId || transactionId.trim() === '') {
                errors.push(
                    'Transaction ID is required. Please complete the payment first and enter your Bkash Transaction ID.'
                );
            }

            // Show validation errors
            if (errors.length > 0) {
                errors.forEach(error => {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(error, 'Validation Error');
                    } else {
                        alert('Error: ' + error);
                    }
                });
                return;
            }

            const submitBtn = document.getElementById('submitOrder');
            const originalText = submitBtn.innerHTML;

            // Show loading
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            submitBtn.disabled = true;

            // Submit form
            fetch('{{ route('public.order') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, return error
                        throw new Error('Server returned non-JSON response. Please check the server logs.');
                    }
                })
                .then(data => {
                    if (data.success) {
                        orderId = data.order_id;
                        document.getElementById('order_id').value = orderId;

                        // Clear the form
                        document.getElementById('orderForm').reset();
                        document.getElementById('transaction_id').value = '';

                        // Update button to Continue Shopping
                        const submitBtn = document.getElementById('submitOrder');
                        submitBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Continue Shopping';
                        submitBtn.className = 'btn-submit btn-success'; // Change to success color
                        submitBtn.disabled = false; // Re-enable the button
                        submitBtn.type = 'button'; // Change from submit to button

                        // Add click handler for Continue Shopping
                        submitBtn.onclick = function(e) {
                            e.preventDefault();
                            window.location.href = '{{ route('landing') }}';
                        };

                        // Show brief success message before redirect
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Order placed successfully! Redirecting...', 'Success', {
                                timeOut: 1000,
                                progressBar: true
                            });
                        }

                        // Redirect to order success page
                        setTimeout(() => {
                            window.location.href = '{{ route('public.order.success', ':orderId') }}'
                                .replace(':orderId', data.order_id);
                        }, 1500);
                    } else {
                        const errorMessage = data.message || 'Something went wrong. Please try again.';
                        const errorType = data.error_type || 'general';

                        if (errorType === 'duplicate_transaction') {
                            if (typeof toastr !== 'undefined') {
                                toastr.warning(errorMessage, 'Transaction Already Processing', {
                                    timeOut: 8000,
                                    extendedTimeOut: 2000
                                });
                            } else {
                                alert('Warning: ' + errorMessage);
                            }
                        } else {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage, 'Order Failed');
                            } else {
                                alert('Error: ' + errorMessage);
                            }
                        }
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Something went wrong. Please try again.';

                    if (error.message.includes('non-JSON response')) {
                        errorMessage =
                            'Server error occurred. Please check your internet connection and try again.';
                    } else if (error.message.includes('Failed to fetch')) {
                        errorMessage = 'Network error. Please check your internet connection and try again.';
                    }

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage, 'Error');
                    } else {
                        alert('Error: ' + errorMessage);
                    }
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Email validation function
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Verify form submission (if needed in future)
        // This is commented out as per user's request
        /*
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const verifyBtn = document.getElementById('verifyBtn');
            const originalText = verifyBtn.innerHTML;

            // Show loading
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
            verifyBtn.disabled = true;

            // Submit form
            this.submit();
        });
        */
    </script>
</body>

</html>
