@extends('backend.layouts.master')

@section('title', 'Order Details')

@section('content')
    <!-- Copy Function Definition - Must be at the top -->
    <script>
        // Define copyTransactionId function immediately
        function copyTransactionId(button) {
            console.log('copyTransactionId called with button:', button);

            const transactionId = button.getAttribute('data-transaction-id');
            const icon = button.querySelector('i');

            console.log('Transaction ID:', transactionId);

            if (!transactionId) {
                alert('No transaction ID to copy');
                return;
            }

            try {
                // Create temporary textarea
                const textArea = document.createElement('textarea');
                textArea.value = transactionId;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);

                // Select and copy
                textArea.select();
                textArea.setSelectionRange(0, 99999); // For mobile devices
                const successful = document.execCommand('copy');

                console.log('Copy successful:', successful);

                if (successful) {
                    // Visual feedback
                    icon.classList.remove('fe-copy');
                    icon.classList.add('fe-check');
                    button.classList.add('copied');

                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Transaction ID copied to clipboard!', 'Copied');
                    } else {
                        alert('Transaction ID copied to clipboard!');
                    }

                    // Reset after 2 seconds
                    setTimeout(() => {
                        icon.classList.remove('fe-check');
                        icon.classList.add('fe-copy');
                        button.classList.remove('copied');
                    }, 2000);
                } else {
                    throw new Error('Copy command failed');
                }

                // Clean up
                document.body.removeChild(textArea);

            } catch (err) {
                console.error('Failed to copy text: ', err);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to copy transaction ID', 'Error');
                } else {
                    alert('Failed to copy transaction ID');
                }
            }
        }

        // Also assign to window for extra safety
        window.copyTransactionId = copyTransactionId;

        console.log('copyTransactionId function defined at page top');
    </script>

    <div class="page-header">
        <h1 class="page-title">Order Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('adminOrderList') }}">Orders</a></li>
                <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($order->payment_method) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        @php
                                            $statusClass = match ($order->payment_status) {
                                                'completed' => 'success',
                                                'failed' => 'danger',
                                                'cancelled' => 'secondary',
                                                default => 'warning',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($order->bkash_transaction_id)
                                    <tr>
                                        <td><strong>Transaction ID:</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <code class="me-2">{{ $order->bkash_transaction_id }}</code>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary copy-transaction-btn"
                                                    data-transaction-id="{{ $order->bkash_transaction_id }}"
                                                    onclick="copyTransactionId(this)" title="Copy Transaction ID">
                                                    <i class="fe fe-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td class="text-primary fw-bold">৳{{ number_format($order->amount, 2) }}</td>
                                </tr>
                                @if ($order->notes)
                                    <tr>
                                        <td><strong>Admin Notes:</strong></td>
                                        <td>{{ $order->notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Information</h3>
                </div>
                <div class="card-body">
                    @if ($order->product)
                        <div class="row">
                            <div class="col-md-4">
                                @if ($order->product->banner_image)
                                    <img src="{{ asset('uploads/products/' . $order->product->banner_image) }}"
                                        alt="{{ $order->product->name }}" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <span class="text-muted">No Image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $order->product->name }}</h4>
                                <p class="text-muted mb-2">{{ $order->product->category }}</p>

                                <div class="mb-3">
                                    <strong>Price: </strong>
                                    <span class="text-primary">৳{{ $order->product->formatted_price }}</span>
                                    @if ($order->product->discount_price)
                                        <span
                                            class="text-success ms-2">৳{{ $order->product->formatted_discount_price }}</span>
                                        <small class="text-muted">({{ $order->product->discount_percentage }}% off)</small>
                                    @endif
                                </div>

                                @if ($order->product->description)
                                    <div class="mb-3">
                                        <strong>Description:</strong>
                                        <div class="mt-2">{!! $order->product->description !!}</div>
                                    </div>
                                @endif

                                @if ($order->product->youtube_video_link)
                                    <div class="mb-3">
                                        <strong>Video Link:</strong>
                                        <a href="{{ $order->product->youtube_video_link }}" target="_blank"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="fab fa-youtube me-1"></i>Watch Video
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fe fe-alert-triangle me-2"></i>
                            Product information not available. The product may have been deleted.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Information</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong><br>
                        {{ $order->customer_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a>
                    </div>
                    <div class="mb-3">
                        <strong>Mobile:</strong><br>
                        <a href="tel:{{ $order->customer_mobile }}">{{ $order->customer_mobile }}</a>
                    </div>
                    <div class="mb-3">
                        <strong>WhatsApp:</strong><br>
                        <a href="https://wa.me/{{ $order->customer_whatsapp }}" target="_blank"
                            class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp me-1"></i>{{ $order->customer_whatsapp }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Actions</h3>
                </div>
                <div class="card-body">
                    @if ($order->payment_status == 'pending')
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#approveModal">
                                <i class="fe fe-check me-2"></i>Approve Order
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fe fe-x me-2"></i>Reject Order
                            </button>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fe fe-info me-2"></i>
                            Order status: <strong>{{ ucfirst($order->payment_status) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('adminOrderUpdateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="payment_status" value="completed">
                        <p>Are you sure you want to approve this order?</p>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('adminOrderUpdateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="payment_status" value="failed">
                        <p>Are you sure you want to reject this order?</p>
                        <div class="mb-3">
                            <label class="form-label">Reason for Rejection *</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Please provide a reason for rejection..."
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-borderless td {
            padding: 0.5rem 0;
            border: none;
        }

        .card {
            margin-bottom: 1.5rem;
        }

        .btn-group .btn {
            margin-right: 0.5rem;
        }

        .copy-transaction-btn {
            padding: 2px 6px;
            font-size: 0.75rem;
        }

        .copy-transaction-btn:hover {
            background-color: #007bff;
            color: white;
        }

        .copy-transaction-btn.copied {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <!-- jQuery (required for toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Toastr
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "3000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
            }

            console.log('Copy button script loaded (order view)');
            console.log('Found copy buttons:', $('.copy-transaction-btn').length);
            console.log('copyTransactionId function available:', typeof window.copyTransactionId);
        });
    </script>
@endpush
