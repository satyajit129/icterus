<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AssetService
{
    public function renderAssetCategory(): View
    {
        $asset_categories = AssetCategory::all();
        return  view('backend.pages.asset_categories', compact('asset_categories'));
    }
    public function renderAssetCategoryCreateOrEdit($id = null): View
    {
        $category = $id ? AssetCategory::findOrFail($id) : new AssetCategory();
        return view('backend.pages.asset_category_create_or_edit', compact('category'));
    }
    public function handleAssetCategorySave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:asset_categories,name,' . $id,
            ]);

            $category = $id ? AssetCategory::findOrFail($id) : new AssetCategory();
            $category->name = $request->name;
            $category->save();
            return redirect()->route('adminAssetCategory')->with('success', 'Category Saved Successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function handleAssetCategoryDelete($id): RedirectResponse
    {
        $category = AssetCategory::findOrFail($id);
        $category->assets()->delete();
        $category->delete();

        return redirect()->route('adminAssetCategory')->with('success', 'Category and related assets deleted successfully');
    }
    public function renderAssetList(): View
    {
        $assets = Asset::with('category')->get();
        return view('backend.pages.assets', compact('assets'));
    }
    public function renderAssetCreateOrEdit($id = null): View
    {
        $asset = $id ? Asset::findOrFail($id) : new Asset();
        $asset_categories = AssetCategory::all();
        return view('backend.pages.asset_create_or_edit', compact('asset', 'asset_categories'));
    }
    public function handleAssetSave($request, $id = null): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name'          => 'required|string|max:150',
                'description'   => 'nullable|string',
                'category_id'   => 'required|exists:asset_categories,id',
                'cost'          => 'required|numeric|min:0',
                'purchase_date' => 'required|date',
            ]);

            $asset = $id ? Asset::findOrFail($id) : new Asset();

            // ✅ Fill data
            $asset->name          = $validated['name'];
            $asset->description   = $validated['description'] ?? null;
            $asset->category_id   = $validated['category_id'];
            $asset->cost          = $validated['cost'];
            $asset->purchase_date = Carbon::parse($validated['purchase_date'])->format('Y-m-d');

            $asset->save();

            return redirect()
                ->route('adminAssetList')
                ->with('success', $id ? 'Asset updated successfully!' : 'Asset created successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Validation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function handleAssetDelete($id): RedirectResponse
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return redirect()
            ->route('adminAssetList')
            ->with('success', 'Asset Deleted successfully!');
    }
}
