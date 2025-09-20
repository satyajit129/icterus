<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductService
{
    public function renderProductList(Request $request): View
    {
        $query = Product::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply stock filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->inStock();
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->stock_status === 'not_tracked') {
                $query->whereNull('stock');
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Product::getCategories();

        return view('backend.pages.products', compact('products', 'categories'));
    }

    public function renderProductCreateOrEditPage($id = null): View
    {
        $product = null;
        if ($id) {
            $product = Product::findOrFail($id);
        }
        $categories = Product::getCategories();

        return view('backend.pages.product_create_or_edit', compact('product', 'categories'));
    }

    public function handleProductSave(Request $request, $id = null): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'sku' => 'nullable|string|max:100|unique:products,sku,' . $id,
                'price' => 'required|numeric|min:0',
                'discount_price' => 'nullable|numeric|min:0|lt:price',
                'description' => 'nullable|string',
                'google_drive_link' => 'nullable|url|max:500',
                'youtube_video_link' => 'nullable|url|max:500',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'stock' => 'nullable|integer|min:0',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->except(['banner_image', 'product_images', '_token']);

            // Handle banner image upload
            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image');
                $bannerImageName = 'banner_' . time() . '.' . $bannerImage->getClientOriginalExtension();
                $bannerImage->move(public_path('uploads/products'), $bannerImageName);
                $data['banner_image'] = $bannerImageName;

                // Delete old banner image if updating
                if ($id) {
                    $oldProduct = Product::find($id);
                    if ($oldProduct && $oldProduct->banner_image) {
                        $oldImagePath = public_path('uploads/products/' . $oldProduct->banner_image);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }

            // Handle multiple product images upload
            if ($request->hasFile('product_images')) {
                $productImages = [];
                foreach ($request->file('product_images') as $image) {
                    $imageName = 'product_' . time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/products'), $imageName);
                    $productImages[] = $imageName;
                }
                $data['product_images'] = $productImages;

                // Delete old product images if updating
                if ($id) {
                    $oldProduct = Product::find($id);
                    if ($oldProduct && $oldProduct->product_images) {
                        foreach ($oldProduct->product_images as $oldImage) {
                            $oldImagePath = public_path('uploads/products/' . $oldImage);
                            if (file_exists($oldImagePath)) {
                                unlink($oldImagePath);
                            }
                        }
                    }
                }
            }

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            if ($id) {
                $product = Product::findOrFail($id);
                $product->update($data);
                $message = 'Product updated successfully!';
            } else {
                $product = Product::create($data);
                $message = 'Product created successfully!';
            }

            return redirect()->route('adminProductList')
                ->with('success', $message);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function handleProductDelete($id): RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);

            // Delete banner image
            if ($product->banner_image) {
                $bannerImagePath = public_path('uploads/products/' . $product->banner_image);
                if (file_exists($bannerImagePath)) {
                    unlink($bannerImagePath);
                }
            }

            // Delete product images
            if ($product->product_images) {
                foreach ($product->product_images as $image) {
                    $imagePath = public_path('uploads/products/' . $image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            $product->delete();

            return redirect()->route('adminProductList')
                ->with('success', 'Product deleted successfully!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function renderProductView($id): View
    {
        $product = Product::findOrFail($id);
        return view('backend.pages.product_view', compact('product'));
    }

    public function renderProductExport(Request $request): BinaryFileResponse
    {
        $query = Product::query();

        // Apply same filters as list view
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->inStock();
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->stock_status === 'not_tracked') {
                $query->whereNull('stock');
            }
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new ProductExport($products), 'products_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function deleteProductImage(Request $request, $id): RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);
            $imageName = $request->input('image_name');

            if ($product->product_images && in_array($imageName, $product->product_images)) {
                // Delete file from public folder
                $imagePath = public_path('uploads/products/' . $imageName);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Remove from product_images array
                $updatedImages = array_values(array_filter($product->product_images, function ($img) use ($imageName) {
                    return $img !== $imageName;
                }));

                $product->update(['product_images' => $updatedImages]);

                return redirect()->back()
                    ->with('success', 'Image deleted successfully!');
            }

            return redirect()->back()
                ->with('error', 'Image not found!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function toggleProductStatus($id): RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);
            $product->update(['status' => !$product->status]);

            $status = $product->status ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Product {$status} successfully!");

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
