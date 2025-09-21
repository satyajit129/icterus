<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Services\SettingService;
use App\Services\EncryptionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function landingPage(): View
    {
        // Get featured products (active products with images)
        $featuredProducts = Product::active()
            ->whereNotNull('banner_image')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get categories for filtering
        $categories = Product::getCategories();

        // Get settings
        $settings = $this->settingService->getAllSettings();

        return view('public.landing', compact('featuredProducts', 'categories', 'settings'));
    }

    public function products(Request $request): View
    {
        $query = Product::active();

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Product::getCategories();
        $settings = $this->settingService->getAllSettings();

        return view('public.products', compact('products', 'categories', 'settings'));
    }

    public function productDetails($slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Get related products (same category, excluding current product)
        $relatedProducts = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $settings = $this->settingService->getAllSettings();

        return view('public.product-details', compact('product', 'relatedProducts', 'settings'));
    }

    public function orderPage($encryptedId): View
    {
        try {
            // Decrypt the product ID
            $productId = EncryptionService::decryptProductId($encryptedId);

            if (!$productId) {
                \Log::error('Failed to decrypt product ID', ['encrypted_id' => $encryptedId]);
                abort(404, 'Invalid order link');
            }

            $product = Product::where('id', $productId)
                ->where('status', 1)
                ->firstOrFail();

            $settings = $this->settingService->getAllSettings();
            return view('public.order-page', compact('product', 'settings'));
        } catch (\Exception $e) {
            \Log::error('Order page error: ' . $e->getMessage(), [
                'encrypted_id' => $encryptedId,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'Invalid order link');
        }
    }

    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_mobile' => 'required|string|max:20',
            'customer_whatsapp' => 'nullable|string|max:20',
            'bkash_transaction_id' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Log the request data for debugging
            \Log::info('Order creation request', [
                'product_id' => $request->product_id,
                'customer_name' => $request->customer_name,
                'bkash_transaction_id' => $request->bkash_transaction_id,
                'all_data' => $request->all()
            ]);

            // Check for duplicate transaction ID
            $existingOrder = Order::where('bkash_transaction_id', $request->bkash_transaction_id)->first();
            if ($existingOrder) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This transaction ID is already being processed. Please wait for verification or contact support if you believe this is an error.',
                        'error_type' => 'duplicate_transaction'
                    ], 409);
                }
                return redirect()->back()
                    ->with('error', 'This transaction ID is already being processed. Please wait for verification or contact support if you believe this is an error.')
                    ->withInput();
            }

            $product = Product::findOrFail($request->product_id);

            // Calculate amount (use discount price if available, otherwise regular price)
            $amount = $product->final_price;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'product_id' => $product->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_mobile' => $request->customer_mobile,
                'customer_whatsapp' => $request->customer_whatsapp,
                'amount' => $amount,
                'payment_method' => 'bkash',
                'bkash_transaction_id' => $request->bkash_transaction_id,
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Log successful order creation
            \Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'bkash_transaction_id' => $order->bkash_transaction_id,
                'amount' => $order->amount
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully! We will verify your payment and update the status.',
                    'order_id' => $order->id
                ]);
            }

            return redirect()->route('public.order.success', $order)
                ->with('success', 'Order placed successfully! We will verify your payment and update the status.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Order creation error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again.',
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    public function verifyPayment(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'transaction_id' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $order = Order::findOrFail($request->order_id);
            $order->bkash_transaction_id = $request->transaction_id;
            $order->payment_status = 'pending'; // Admin will verify and change status
            $order->save();

            return redirect()->route('public.order.success', $order)
                ->with('success', 'Transaction ID submitted successfully! We will verify your payment and update the status.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    public function orderSuccess(Order $order): View
    {
        $settings = $this->settingService->getAllSettings();
        return view('public.order-success', compact('order', 'settings'));
    }
}
