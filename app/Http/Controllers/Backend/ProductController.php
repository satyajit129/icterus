<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function adminProductList(Request $request): View
    {
        return $this->productService->renderProductList($request);
    }

    public function adminProductCreateOrEdit($id = null): View
    {
        return $this->productService->renderProductCreateOrEditPage($id);
    }

    public function adminProductSave(Request $request, $id = null): RedirectResponse
    {
        return $this->productService->handleProductSave($request, $id);
    }

    public function adminProductDelete($id): RedirectResponse
    {
        return $this->productService->handleProductDelete($id);
    }

    public function adminProductView($id): View
    {
        return $this->productService->renderProductView($id);
    }

    public function adminProductExport(Request $request): BinaryFileResponse
    {
        return $this->productService->renderProductExport($request);
    }

    public function adminProductDeleteImage(Request $request, $id): RedirectResponse
    {
        return $this->productService->deleteProductImage($request, $id);
    }

    public function adminProductToggleStatus($id): RedirectResponse
    {
        return $this->productService->toggleProductStatus($id);
    }
}
