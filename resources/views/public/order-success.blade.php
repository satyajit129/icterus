<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎉 Order Success - {{ $settings->website_name ?? 'Thank You!' }}</title>
    <meta name="description" content="Your order has been placed successfully! Thank you for your purchase.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <!-- Facebook Pixel -->
    @include('components.facebook-pixel')

    <!-- Google Tag Manager -->
    @include('components.google-tag-manager')

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #e17055;
            --bkash-color: #e2136e;
            --text-color: #2d3436;
            --light-bg: #f8f9fa;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            --gradient-celebration: linear-gradient(135deg, #ff6b6b 0%, #feca57 50%, #48dbfb 100%);
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-strong: 0 20px 60px rgba(0, 0, 0, 0.15);
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
            background: var(--gradient-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Confetti Animation */
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        }

        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--gradient-celebration);
            animation: confetti-fall 3s linear infinite;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(2n) {
            animation-delay: -2s;
        }

        .floating-element:nth-child(3n) {
            animation-delay: -4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        .success-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: var(--shadow-strong);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            position: relative;
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-header {
            background: var(--gradient-success);
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .success-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        .company-logo {
            position: absolute;
            top: 20px;
            right: 30px;
            z-index: 3;
            animation: slideInRight 1s ease-out 0.3s both;
        }

        .logo-image {
            max-width: 80px;
            max-height: 80px;
            width: auto;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .logo-image:hover {
            transform: scale(1.1);
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 4rem;
            animation: bounceIn 1s ease-out 0.5s both;
            position: relative;
            z-index: 2;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            animation: fadeInUp 1s ease-out 0.7s both;
            position: relative;
            z-index: 2;
        }

        .success-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease-out 0.9s both;
            position: relative;
            z-index: 2;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-body {
            padding: 50px 40px;
        }

        .order-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: slideInLeft 0.8s ease-out 1.1s both;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .info-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--success-color);
        }

        .info-header i {
            font-size: 1.5rem;
            color: var(--success-color);
            margin-right: 15px;
        }

        .info-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--text-color);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .info-label {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            color: var(--text-color);
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .transaction-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: var(--bkash-color);
            background: rgba(226, 19, 110, 0.1);
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid rgba(226, 19, 110, 0.3);
        }

        .copy-transaction-btn {
            background: var(--bkash-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 8px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .copy-transaction-btn:hover {
            background: #c4125a;
            transform: scale(1.05);
        }

        .copy-transaction-btn:active {
            transform: scale(0.95);
        }

        .payment-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .payment-status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .payment-status-completed {
            background: rgba(40, 167, 69, 0.2);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .payment-status-failed {
            background: rgba(220, 53, 69, 0.2);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .payment-status-cancelled {
            background: rgba(108, 117, 125, 0.2);
            color: #495057;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .payment-section {
            background: white;
            border: 2px solid var(--bkash-color);
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.8s ease-out 1.3s both;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .payment-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-celebration);
        }

        .payment-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .payment-logo {
            width: 70px;
            height: 70px;
            background: var(--bkash-color);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            animation: pulse 2s ease-in-out infinite;
        }

        .payment-logo i {
            color: white;
            font-size: 2.5rem;
        }

        .payment-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        .amount-highlight {
            background: var(--gradient-success);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin: 25px 0;
            position: relative;
            overflow: hidden;
        }

        .amount-highlight::before {
            content: '💰';
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 2rem;
            opacity: 0.3;
        }

        .amount-highlight h4 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .amount-highlight p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .payment-steps {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .payment-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border-left: 5px solid var(--bkash-color);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out both;
        }

        .payment-step:nth-child(1) {
            animation-delay: 1.5s;
        }

        .payment-step:nth-child(2) {
            animation-delay: 1.7s;
        }

        .payment-step:nth-child(3) {
            animation-delay: 1.9s;
        }

        .payment-step:nth-child(4) {
            animation-delay: 2.1s;
        }

        .payment-step:nth-child(5) {
            animation-delay: 2.3s;
        }

        .payment-step:nth-child(6) {
            animation-delay: 2.5s;
        }

        .payment-step:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: var(--bkash-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 20px;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .step-content h6 {
            margin: 0 0 8px 0;
            font-weight: 600;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .step-content p {
            margin: 0;
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
        }

        .contact-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            animation: slideInUp 0.8s ease-out 2.7s both;
        }

        .contact-info h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .contact-item:hover {
            transform: scale(1.05);
        }

        .contact-item i {
            color: var(--primary-color);
            margin-right: 15px;
            width: 25px;
            font-size: 1.2rem;
        }

        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 2.9s both;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        .whatsapp-btn {
            background: #25d366;
            color: white;
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4);
        }

        .whatsapp-btn:hover {
            background: #1da851;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.6);
        }

        .celebration-text {
            background: var(--gradient-celebration);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            text-align: center;
            margin: 20px 0;
            font-size: 1.5rem;
            animation: bounce 2s ease-in-out infinite;
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

        /* Print Styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }

            .confetti,
            .floating-element,
            .action-buttons,
            .contact-info,
            .payment-section .payment-steps,
            .payment-section .amount-highlight {
                display: none !important;
            }

            .success-card {
                box-shadow: none !important;
                border: 2px solid #000 !important;
            }

            .success-header {
                background: #f0f0f0 !important;
                color: black !important;
                border-bottom: 2px solid #000 !important;
            }

            .success-icon {
                background: #e0e0e0 !important;
                color: black !important;
            }

            .company-logo {
                position: static !important;
                margin: 0 auto 20px auto !important;
                display: flex !important;
                justify-content: center !important;
            }

            .logo-image {
                max-width: 80px !important;
                max-height: 80px !important;
                border: 2px solid #000 !important;
                background: white !important;
                padding: 5px !important;
            }

            .logo-placeholder {
                width: 80px !important;
                height: 80px !important;
                background: #f0f0f0 !important;
                border: 2px solid #000 !important;
                color: #666 !important;
            }

            .order-info {
                background: white !important;
                border: 1px solid #000 !important;
            }

            .payment-section {
                border: 1px solid #000 !important;
                background: white !important;
            }

            .payment-header {
                border-bottom: 1px solid #000 !important;
                margin-bottom: 20px !important;
            }

            .info-item {
                border-bottom: 1px solid #ccc !important;
                background: white !important;
            }

            .success-container {
                padding: 0 !important;
            }

            .success-card {
                margin: 0 !important;
                max-width: none !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .success-title {
                font-size: 2.2rem;
            }

            .success-body {
                padding: 30px 20px;
            }

            .company-logo {
                position: static;
                margin: 0 auto 20px auto;
                display: flex;
                justify-content: center;
            }

            .logo-image {
                max-width: 60px;
                max-height: 60px;
            }

            .logo-placeholder {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .payment-header {
                flex-direction: column;
                text-align: center;
            }

            .payment-logo {
                margin: 0 0 15px 0;
            }

            .print-logo-section {
                flex-direction: column;
                gap: 15px;
            }

            .print-logo {
                max-width: 80px;
                max-height: 80px;
            }

            .print-logo-placeholder {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading-dots {
            display: inline-block;
        }

        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {

            0%,
            20% {
                content: '';
            }

            40% {
                content: '.';
            }

            60% {
                content: '..';
            }

            80%,
            100% {
                content: '...';
            }
        }
    </style>
</head>

<body>
    <!-- Confetti Animation -->
    <div class="confetti" id="confetti"></div>

    <!-- Floating Elements -->
    <div class="floating-element" style="top: 10%; left: 10%; font-size: 2rem; color: rgba(255, 255, 255, 0.3);">🎉
    </div>
    <div class="floating-element" style="top: 20%; right: 15%; font-size: 1.5rem; color: rgba(255, 255, 255, 0.3);">✨
    </div>
    <div class="floating-element" style="bottom: 30%; left: 20%; font-size: 2.5rem; color: rgba(255, 255, 255, 0.3);">🎊
    </div>
    <div class="floating-element" style="bottom: 20%; right: 10%; font-size: 1.8rem; color: rgba(255, 255, 255, 0.3);">
        🌟</div>

    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <!-- Company Logo -->
                <div class="company-logo">
                    @if ($settings->logo)
                        <img src="{{ asset('uploads/' . $settings->logo) }}"
                            alt="{{ $settings->website_name ?? 'Company Logo' }}" class="logo-image">
                    @else
                        <div class="logo-placeholder">
                            <i class="fas fa-store"></i>
                        </div>
                    @endif
                </div>

                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="success-title">🎉 Congratulations! 🎉</h1>
                <p class="success-subtitle">Your order has been placed successfully!</p>
                <div class="celebration-text">
                    Thank you for choosing us! 🙏
                </div>
            </div>

            <div class="success-body">
                <!-- Order Information -->
                <div class="order-info">
                    <div class="info-header">
                        <i class="fas fa-receipt"></i>
                        <h5>Order Details</h5>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Order Number</div>
                            <div class="info-value">{{ $order->order_number }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Product Name</div>
                            <div class="info-value">{{ $order->product->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Customer Name</div>
                            <div class="info-value">{{ $order->customer_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $order->customer_email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Mobile Number</div>
                            <div class="info-value">{{ $order->customer_mobile }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Order Date</div>
                            <div class="info-value">{{ $order->created_at->format('M d, Y H:i A') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">
                                <span class="payment-status payment-status-{{ $order->payment_status }}">
                                    <i
                                        class="fas fa-{{ $order->payment_status === 'completed' ? 'check-circle' : ($order->payment_status === 'pending' ? 'clock' : 'exclamation-circle') }}"></i>
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                        @if ($order->bkash_transaction_id)
                            <div class="info-item">
                                <div class="info-label">bKash Transaction ID</div>
                                <div class="info-value">
                                    <span class="transaction-id">{{ $order->bkash_transaction_id }}</span>
                                    <button onclick="copyTransactionId()" class="copy-transaction-btn"
                                        title="Copy Transaction ID">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h6><i class="fas fa-headset me-2"></i>Need Help? We're Here for You!</h6>
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
                    <button onclick="printOrder()" class="btn btn-primary">
                        <i class="fas fa-print"></i>
                        Print Order
                    </button>
                    {{-- <button onclick="printOrderSimple()" class="btn btn-outline">
                        <i class="fas fa-file-pdf"></i>
                        Print (Simple)
                    </button> --}}
                    <a href="{{ route('public.products') }}" class="btn btn-outline">
                        <i class="fas fa-shopping-bag"></i>
                        Continue Shopping
                    </a>
                    <a href="{{ route('landing') }}" class="btn btn-outline">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                    @if ($settings->whatsapp)
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $settings->whatsapp) }}?text=Hi, I just placed order {{ $order->order_number }}. Please confirm my payment."
                            target="_blank" class="btn whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            Contact WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Confetti Animation
        function createConfetti() {
            const confettiContainer = document.getElementById('confetti');
            const colors = ['#ff6b6b', '#feca57', '#48dbfb', '#ff9ff3', '#54a0ff', '#5f27cd'];

            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti-piece';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                confettiContainer.appendChild(confetti);
            }
        }

        // Create confetti on page load
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();

            // Track Purchase event
            FacebookPixel.trackPurchase({
                content_ids: ['{{ $order->product->id }}'],
                content_type: 'product',
                content_name: '{{ $order->product->name }}',
                content_category: '{{ $order->product->category }}',
                value: {{ $order->amount }},
                currency: 'BDT',
                num_items: 1
            });

            // Track GTM purchase event
            GoogleTagManager.trackPurchase({
                transaction_id: '{{ $order->order_number }}',
                currency: 'BDT',
                value: {{ $order->amount }},
                items: [{
                    item_id: '{{ $order->product->id }}',
                    item_name: '{{ $order->product->name }}',
                    item_category: '{{ $order->product->category }}',
                    item_brand: '{{ $settings->website_name ?? 'Unknown' }}',
                    price: {{ $order->amount }},
                    quantity: 1
                }]
            });

            // Add some interactive effects
            const successCard = document.querySelector('.success-card');
            successCard.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
            });

            successCard.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });

            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Copy order number to clipboard
        function copyOrderNumber() {
            navigator.clipboard.writeText('{{ $order->order_number }}').then(() => {
                // Create a temporary toast notification
                const toast = document.createElement('div');
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--success-color);
                    color: white;
                    padding: 15px 25px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease-out;
                `;
                toast.textContent = 'Order number copied to clipboard!';
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }).catch(() => {
                alert('Order number: {{ $order->order_number }}');
            });
        }

        // Copy transaction ID to clipboard
        function copyTransactionId() {
            const transactionId = '{{ $order->bkash_transaction_id }}';

            if (!transactionId) {
                alert('No transaction ID available');
                return;
            }

            navigator.clipboard.writeText(transactionId).then(() => {
                // Create a temporary toast notification
                const toast = document.createElement('div');
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--bkash-color);
                    color: white;
                    padding: 15px 25px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease-out;
                `;
                toast.textContent = 'Transaction ID copied to clipboard!';
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }).catch(() => {
                alert('Transaction ID: ' + transactionId);
            });
        }

        // Add ripple effect CSS
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        // Print order function
        function printOrder() {
            try {
                // Create a new window for printing
                const printWindow = window.open('', '_blank', 'width=800,height=600');

                if (!printWindow) {
                    alert('Please allow popups for this site to print the order.');
                    return;
                }

                // Create print-friendly HTML
                const printHTML = `<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
            line-height: 1.4;
        }
        .print-header {
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .print-logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        .print-logo {
            max-width: 100px;
            max-height: 100px;
            width: auto;
            height: auto;
            border-radius: 10px;
            border: 2px solid #000;
        }

        .print-logo-placeholder {
            width: 100px;
            height: 100px;
            background: #f0f0f0;
            border: 2px solid #000;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #666;
        }

        .print-company-info {
            text-align: center;
        }

        .company-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin: 5px 0 0 0;
        }
        .print-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            color: #000;
        }
        .print-subtitle {
            font-size: 1.2rem;
            margin: 10px 0 0 0;
            color: #666;
        }
        .order-info {
            background: #f9f9f9;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .info-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #000;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-item {
            background: white;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            color: #000;
            font-size: 1.1rem;
        }

        .transaction-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #000;
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        .payment-section {
            border: 2px solid #000;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .payment-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #000;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .amount-highlight {
            background: #000;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .amount-highlight h4 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        .amount-highlight p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
        }
        .merchant-info {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: center;
        }
        .merchant-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            margin: 10px 0;
        }
        .print-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #000;
            font-size: 0.9rem;
            color: #666;
        }
        @media print {
            body { margin: 0; padding: 10px; }
            .print-header { page-break-after: avoid; }
        }
    </style>
</head>
                <body>
                    <div class="print-header">
                        <div class="print-logo-section">
                            @if ($settings->logo)
                                <img src="{{ asset('uploads/' . $settings->logo) }}" alt="{{ $settings->website_name ?? 'Company Logo' }}" class="print-logo">
                            @else
                                <div class="print-logo-placeholder">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            <div class="print-company-info">
                                <h1 class="print-title">🎉 Order Confirmation 🎉</h1>
                                <p class="print-subtitle">Thank you for your purchase!</p>
                                @if ($settings->website_name)
                                    <p class="company-name">{{ $settings->website_name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

    <div class="order-info">
        <div class="info-header">📋 Order Details</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Order Number</div>
                <div class="info-value">{{ $order->order_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Product Name</div>
                <div class="info-value">{{ $order->product->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Customer Name</div>
                <div class="info-value">{{ $order->customer_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ $order->customer_email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Mobile Number</div>
                <div class="info-value">{{ $order->customer_mobile }}</div>
            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">{{ $order->created_at->format('M d, Y H:i A') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Payment Status</div>
                                <div class="info-value">{{ ucfirst($order->payment_status) }}</div>
                            </div>
                            @if ($order->bkash_transaction_id)
                            <div class="info-item">
                                <div class="info-label">bKash Transaction ID</div>
                                <div class="info-value">{{ $order->bkash_transaction_id }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

    <div class="print-footer">
        <p><strong>Thank you for choosing us!</strong></p>
        <p>For any queries, please contact us at: {{ $settings->phone ?? 'N/A' }}</p>
        <p>Order placed on: {{ $order->created_at->format('F d, Y \\a\\t H:i A') }}</p>
    </div>
</body>
</html>`;

                // Write content to print window
                printWindow.document.write(printHTML);
                printWindow.document.close();

                // Wait for content to load, then print
                setTimeout(() => {
                    printWindow.focus();
                    printWindow.print();
                    // Don't close immediately, let user see the print dialog
                    setTimeout(() => {
                        printWindow.close();
                    }, 1000);
                }, 500);

            } catch (error) {
                alert('Error generating print preview. Please try again.');
            }
        }

        // Simple print function (fallback)
        function printOrderSimple() {
            // Hide elements that shouldn't be printed
            const elementsToHide = document.querySelectorAll(
                '.confetti, .floating-element, .action-buttons, .contact-info');
            elementsToHide.forEach(el => el.style.display = 'none');

            // Print the current page
            window.print();

            // Show elements again after printing
            elementsToHide.forEach(el => el.style.display = '');
        }

        // Auto-refresh page every 60 seconds to check payment status
        setTimeout(() => {
            location.reload();
        }, 60000);
    </script>
</body>

</html>
