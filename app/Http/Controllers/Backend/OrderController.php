<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function adminOrderList(Request $request): View
    {
        return $this->orderService->renderOrderList($request);
    }

    public function adminOrderView($id): View
    {
        return $this->orderService->renderOrderView($id);
    }

    public function adminOrderUpdateStatus(Request $request, $id): RedirectResponse
    {
        return $this->orderService->updateOrderStatus($request, $id);
    }

    public function adminOrderExport(Request $request): BinaryFileResponse
    {
        return $this->orderService->exportOrders($request);
    }
}
