<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - {{ $settings->website_name ?? 'Payment Instructions' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
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

        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }

        .success-header {
            background: var(--gradient);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .success-body {
            padding: 40px;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .info-value {
            color: #666;
        }

        .payment-section {
            background: white;
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
            background: #f8f9fa;
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

        .amount-highlight {
            background: var(--bkash-color);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-highlight h4 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .contact-info {
            background: #e3f2fd;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
        }

        .contact-info h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .contact-item i {
            color: var(--secondary-color);
            margin-right: 10px;
            width: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-primary {
            background: var(--secondary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        .whatsapp-btn {
            background: #25d366;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .whatsapp-btn:hover {
            background: #1da851;
            color: white;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .success-title {
                font-size: 2rem;
            }

            .success-body {
                padding: 25px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .info-item {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="success-title">Order Placed Successfully!</h1>
                <p class="success-subtitle">Your order has been received and is being processed</p>
            </div>

            <div class="success-body">
                <!-- Order Information -->
                <div class="order-info">
                    <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Order Information</h5>
                    <div class="info-item">
                        <span class="info-label">Order Number:</span>
                        <span class="info-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Product:</span>
                        <span class="info-value">{{ $order->product->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Customer:</span>
                        <span class="info-value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $order->customer_email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Mobile:</span>
                        <span class="info-value">{{ $order->customer_mobile }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Date:</span>
                        <span class="info-value">{{ $order->created_at->format('M d, Y H:i A') }}</span>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="payment-section">
                    <div class="payment-header">
                        <div class="payment-logo">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="payment-title">Complete Your Payment with Bkash</h4>
                    </div>

                    <div class="amount-highlight">
                        <h4>Pay ৳{{ $order->formatted_amount }}</h4>
                        <p class="mb-0">Send this exact amount to complete your order</p>
                    </div>

                    <ol class="payment-steps">
                        <li class="payment-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6>Open Bkash App</h6>
                                <p>Launch your Bkash mobile app on your smartphone</p>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6>Go to Send Money</h6>
                                <p>Tap on "Send Money" option from the main menu</p>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6>Enter Merchant Number</h6>
                                <p>Send money to: <strong>+880 1234 567890</strong></p>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h6>Enter Amount</h6>
                                <p>Enter the exact amount: <strong>৳{{ $order->formatted_amount }}</strong></p>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">5</div>
                            <div class="step-content">
                                <h6>Add Reference</h6>
                                <p>Use this as reference: <strong>{{ $order->order_number }}</strong></p>
                            </div>
                        </li>
                        <li class="payment-step">
                            <div class="step-number">6</div>
                            <div class="step-content">
                                <h6>Complete Payment</h6>
                                <p>Enter your Bkash PIN and complete the transaction</p>
                            </div>
                        </li>
                    </ol>
                </div>

                <!-- Google Drive Link Information -->
                @if ($order->product->google_drive_link)
                    <div class="payment-section">
                        <div class="payment-header">
                            <div class="payment-logo">
                                <i class="fab fa-google-drive"></i>
                            </div>
                            <h4 class="payment-title">Product Files Access</h4>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Important:</strong> Your product files will be shared via WhatsApp after payment
                            confirmation.
                            Please keep your WhatsApp number ready: <strong>{{ $order->customer_whatsapp }}</strong>
                        </div>
                    </div>
                @endif

                <!-- Contact Information -->
                <div class="contact-info">
                    <h6><i class="fas fa-headset me-2"></i>Need Help?</h6>
                    @if ($settings->phone)
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>Call us: {{ $settings->phone }}</span>
                        </div>
                    @endif
                    @if ($settings->whatsapp)
                        <div class="contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp: {{ $settings->whatsapp }}</span>
                        </div>
                    @endif
                    @if ($settings->website_email)
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>Email: {{ $settings->website_email }}</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('public.products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
                    <a href="{{ route('landing') }}" class="btn-outline">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                    @if ($settings->whatsapp)
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $settings->whatsapp) }}?text=Hi, I just placed order {{ $order->order_number }}. Please confirm my payment."
                            target="_blank" class="whatsapp-btn">
                            <i class="fab fa-whatsapp me-2"></i>Contact WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-refresh page every 30 seconds to check payment status
        setTimeout(() => {
            location.reload();
        }, 30000);

        // Copy order number to clipboard
        function copyOrderNumber() {
            navigator.clipboard.writeText('{{ $order->order_number }}').then(() => {
                alert('Order number copied to clipboard!');
            });
        }

        // Print order details
        function printOrder() {
            window.print();
        }
    </script>
</body>

</html>
