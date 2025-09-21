<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Earning;
use App\Models\Employee;
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

        // Calculate totals based on current filters
        $totalPendingAmount = (clone $query)->where('payment_status', 'pending')->sum('amount');
        $totalCompletedAmount = (clone $query)->where('payment_status', 'completed')->sum('amount');
        $totalFailedAmount = (clone $query)->where('payment_status', 'failed')->sum('amount');
        $totalCancelledAmount = (clone $query)->where('payment_status', 'cancelled')->sum('amount');
        $totalAmount = (clone $query)->sum('amount');

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('backend.pages.orders', compact('orders', 'totalPendingAmount', 'totalCompletedAmount', 'totalFailedAmount', 'totalCancelledAmount', 'totalAmount'));
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
                // Create earning record when order is approved
                $this->createEarningFromOrder($order, $request->notes);

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
                $message .= " Approval email has been sent to the customer and earning record has been created.";
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

    /**
     * Delete order and associated earning record
     */
    public function deleteOrder($id): RedirectResponse
    {
        try {
            $order = Order::findOrFail($id);

            // Store order details for logging
            $orderDetails = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'amount' => $order->amount,
                'payment_status' => $order->payment_status,
                'bkash_transaction_id' => $order->bkash_transaction_id
            ];

            // Find and delete associated earning record
            $earningDeleted = false;
            if ($order->bkash_transaction_id) {
                $earning = Earning::where('trnx_id', $order->bkash_transaction_id)->first();
                if ($earning) {
                    $earning->delete();
                    $earningDeleted = true;
                    Log::info('Associated earning record deleted', [
                        'order_id' => $order->id,
                        'earning_id' => $earning->id,
                        'transaction_id' => $order->bkash_transaction_id
                    ]);
                }
            }

            // Delete the order
            $order->delete();

            Log::info('Order deleted successfully', [
                'order_details' => $orderDetails,
                'earning_deleted' => $earningDeleted
            ]);

            $message = "Order deleted successfully.";
            if ($earningDeleted) {
                $message .= " Associated earning record has also been deleted.";
            }

            return redirect()->route('adminOrderList')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to delete order', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    /**
     * Create earning record from approved order
     */
    private function createEarningFromOrder(Order $order, string $notes = null): void
    {
        try {
            // Find employee by ID number (you can modify this logic as needed)
            $employeeIdNumber = 'S-15770'; // Default employee ID number - modify as needed
            $employee = Employee::where('id_number', $employeeIdNumber)->first();

            if (!$employee) {
                Log::warning('Employee not found for earning record', [
                    'order_id' => $order->id,
                    'employee_id_number' => $employeeIdNumber
                ]);
                // Continue with null employee_id if not found
            }

            Earning::create([
                'company_id' => 4, // Always 4 as specified
                'date' => $order->created_at->format('Y-m-d'), // Order created_at date
                'employee_id' => $employee ? $employee->id : null, // Employee ID from lookup or null
                'payment_method' => 'Bkash', // Always Bkash as specified
                'phone_number' => $order->customer_mobile, // Order mobile number
                'paid_amount' => $order->amount, // Order amount
                'trnx_id' => $order->bkash_transaction_id, // Order transaction ID
                'sales_status' => 1, // Always 1 as specified
                'customer_number' => $order->customer_whatsapp, // Order WhatsApp number
                'deals_amount' => $order->amount, // Order amount
                'due_amount' => 0, // Always 0 as specified
                'product_name' => $order->product->name ?? 'N/A', // Order product name
                'details' => $notes ?? 'Order approved: ' . $order->order_number, // Approval notes
            ]);

            Log::info('Earning record created from approved order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $order->amount,
                'transaction_id' => $order->bkash_transaction_id,
                'employee_id' => $employee ? $employee->id : null,
                'employee_id_number' => $employeeIdNumber
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create earning record from order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
