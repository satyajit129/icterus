<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = [];

    protected $casts = [
        'product_images' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'boolean',
    ];

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    // Accessor for formatted discount price
    public function getFormattedDiscountPriceAttribute()
    {
        return $this->discount_price ? number_format($this->discount_price, 2) : null;
    }

    // Accessor for final price (discount applied if available)
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ? $this->discount_price : $this->price;
    }

    // Accessor for discount percentage
    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100, 2);
        }
        return 0;
    }

    // Accessor for stock status
    public function getStockStatusAttribute()
    {
        if ($this->stock === null) {
            return 'Not Tracked';
        }
        return $this->stock > 0 ? 'In Stock' : 'Out of Stock';
    }

    // Accessor for status text
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    // Mutator for slug generation
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope for in stock products
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('stock')->orWhere('stock', '>', 0);
        });
    }

    // Scope for search
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('category', 'like', '%' . $search . '%')
              ->orWhere('sku', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    // Scope for category filter
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for price range
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    // Get all unique categories
    public static function getCategories()
    {
        return self::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    // Get banner image URL
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('uploads/products/' . $this->banner_image) : null;
    }

    // Get product images URLs
    public function getProductImagesUrlsAttribute()
    {
        if (!$this->product_images) {
            return [];
        }

        return collect($this->product_images)->map(function ($image) {
            return asset('uploads/products/' . $image);
        })->toArray();
    }

    // Get first product image URL
    public function getFirstProductImageUrlAttribute()
    {
        $images = $this->product_images_urls;
        return !empty($images) ? $images[0] : null;
    }
}
