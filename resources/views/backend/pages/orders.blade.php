@extends('backend.layouts.master')

@section('title', 'Order Management')

{{-- Permission Check --}}
@php
    $user = auth()->user();
    $canViewOrder = $user->hasPermission('view_order');
    $canApproveOrder = $user->hasPermission('approve_order');
    $canRejectOrder = $user->hasPermission('reject_order');
    $canDeleteOrder = $user->hasPermission('delete_order');
    $canExportOrder = $user->hasPermission('export_order');
@endphp

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
        <h1 class="page-title">Order Management</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </div>
    </div>

    <!-- Order Totals Summary -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-warning text-white rounded">
                                <i class="fe fe-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Pending Orders</h6>
                            <h4 class="mb-0 text-warning">৳{{ number_format($totalPendingAmount, 2) }}</h4>
                            <small class="text-muted">{{ $orders->where('payment_status', 'pending')->count() }}
                                orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-success text-white rounded">
                                <i class="fe fe-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Completed Orders</h6>
                            <h4 class="mb-0 text-success">৳{{ number_format($totalCompletedAmount, 2) }}</h4>
                            <small class="text-muted">{{ $orders->where('payment_status', 'completed')->count() }}
                                orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-danger text-white rounded">
                                <i class="fe fe-x-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Failed Orders</h6>
                            <h4 class="mb-0 text-danger">৳{{ number_format($totalFailedAmount, 2) }}</h4>
                            <small class="text-muted">{{ $orders->where('payment_status', 'failed')->count() }}
                                orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-primary text-white rounded">
                                <i class="fe fe-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Total Amount</h6>
                            <h4 class="mb-0 text-primary">৳{{ number_format($totalAmount, 2) }}</h4>
                            <small class="text-muted">{{ $orders->total() }} total orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order List</h3>
                    <div class="card-options">
                        @if ($canExportOrder)
                            <a href="{{ route('adminOrderExport', request()->query()) }}" class="btn btn-primary btn-sm">
                                <i class="fe fe-download me-1"></i>Export
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('adminOrderList') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search orders..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="payment_status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="completed"
                                        {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                    <option value="cancelled"
                                        {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="date_from"
                                    value="{{ request('date_from') }}" placeholder="From Date">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}"
                                    placeholder="To Date">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-search me-1"></i>Search
                                </button>
                                <a href="{{ route('adminOrderList') }}" class="btn btn-secondary">
                                    <i class="fe fe-x me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($order->product && $order->product->banner_image)
                                                    <img src="{{ asset('uploads/products/' . $order->product->banner_image) }}"
                                                        alt="{{ $order->product->name }}" class="me-2"
                                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $order->product->name ?? 'N/A' }}</div>
                                                    <small
                                                        class="text-muted">{{ $order->product->category ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $order->customer_name }}</div>
                                                <small class="text-muted">{{ $order->customer_email }}</small>
                                                <br>
                                                <small class="text-muted">{{ $order->customer_mobile }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-primary">৳{{ number_format($order->amount, 2) }}</strong>
                                        </td>
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
                                        <td>
                                            @if ($order->bkash_transaction_id)
                                                <div class="d-flex align-items-center">
                                                    <code class="me-2">{{ $order->bkash_transaction_id }}</code>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary copy-transaction-btn"
                                                        data-transaction-id="{{ $order->bkash_transaction_id }}"
                                                        onclick="copyTransactionId(this)" title="Copy Transaction ID">
                                                        <i class="fe fe-copy"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($canViewOrder)
                                                    <a href="{{ route('adminOrderView', $order->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                @endif

                                                @if ($order->payment_status == 'pending' && $canApproveOrder)
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#statusModal{{ $order->id }}"
                                                        title="Approve Order">
                                                        <i class="fe fe-check"></i>
                                                    </button>
                                                @endif

                                                @if ($order->payment_status == 'pending' && $canRejectOrder)
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $order->id }}"
                                                        title="Reject Order">
                                                        <i class="fe fe-x"></i>
                                                    </button>
                                                @endif

                                                @if ($canDeleteOrder)
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $order->id }}"
                                                        title="Delete Order">
                                                        <i class="fe fe-trash-2"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Status Update Modal -->
                                    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Order</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('adminOrderUpdateStatus', $order->id) }}"
                                                    method="POST">
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
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Approve
                                                            Order</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Order</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('adminOrderUpdateStatus', $order->id) }}"
                                                    method="POST">
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
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject
                                                            Order</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    @if ($canDeleteOrder)
                                        <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-danger">
                                                            <i class="fe fe-trash-2 me-2"></i>Delete Order
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-warning">
                                                            <i class="fe fe-alert-triangle me-2"></i>
                                                            <strong>Warning:</strong> This action cannot be undone!
                                                        </div>
                                                        <p>Are you sure you want to delete this order?</p>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title">Order Details:</h6>
                                                                <ul class="list-unstyled mb-0">
                                                                    <li><strong>Order #:</strong>
                                                                        {{ $order->order_number }}</li>
                                                                    <li><strong>Customer:</strong>
                                                                        {{ $order->customer_name }}</li>
                                                                    <li><strong>Amount:</strong>
                                                                        ৳{{ number_format($order->amount, 2) }}</li>
                                                                    <li><strong>Status:</strong>
                                                                        {{ ucfirst($order->payment_status) }}</li>
                                                                    @if ($order->bkash_transaction_id)
                                                                        <li><strong>Transaction ID:</strong>
                                                                            {{ $order->bkash_transaction_id }}</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <p class="text-muted small">
                                                                <i class="fe fe-info me-1"></i>
                                                                This will also delete any associated earning records.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="fe fe-x me-1"></i>Cancel
                                                        </button>
                                                        <form action="{{ route('adminOrderDelete', $order->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fe fe-trash-2 me-1"></i>Delete Order
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fe fe-package" style="font-size: 48px;"></i>
                                                <p class="mt-2">No orders found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
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

        /* Order Totals Cards */
        .avatar {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-primary {
            background-color: #007bff !important;
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

            console.log('Copy button script loaded');
            console.log('Found copy buttons:', $('.copy-transaction-btn').length);
            console.log('copyTransactionId function available:', typeof window.copyTransactionId);
        });
    </script>
@endpush
