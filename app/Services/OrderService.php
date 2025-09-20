<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderService
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function renderOrderList(Request $request): View
    {
        $query = Order::with('product');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('bkash_transaction_id', 'like', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('backend.pages.orders', compact('orders'));
    }

    public function renderOrderView($id): View
    {
        $order = Order::with('product')->findOrFail($id);
        return view('backend.pages.order_view', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,completed,failed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $order = Order::findOrFail($id);
            $oldStatus = $order->payment_status;
            $order->payment_status = $request->payment_status;
            $order->notes = $request->notes;
            $order->save();

            // Send email if order is approved (status changed to completed)
            if ($oldStatus !== 'completed' && $request->payment_status === 'completed') {
                $emailSent = $this->emailService->sendOrderApprovalEmail($order);

                if ($emailSent) {
                    Log::info('Order approval email sent', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_email' => $order->customer_email
                    ]);
                } else {
                    Log::warning('Failed to send order approval email', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_email' => $order->customer_email
                    ]);
                }
            }

            $statusText = ucfirst($request->payment_status);
            $message = "Order status updated to {$statusText} successfully.";

            // Add email notification info if order was approved
            if ($oldStatus !== 'completed' && $request->payment_status === 'completed') {
                $message .= " Approval email has been sent to the customer.";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to update order status', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function exportOrders(Request $request): BinaryFileResponse
    {
        $query = Order::with('product');

        // Apply same filters as list
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('bkash_transaction_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new OrderExport($orders), 'orders_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
