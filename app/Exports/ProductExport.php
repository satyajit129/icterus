<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'SKU',
            'Slug',
            'Price (৳)',
            'Discount Price (৳)',
            'Final Price (৳)',
            'Discount %',
            'Description',
            'Google Drive Link',
            'YouTube Video Link',
            'Banner Image',
            'Product Images Count',
            'Stock',
            'Stock Status',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category,
            $product->sku ?? 'N/A',
            $product->slug ?? 'N/A',
            $product->formatted_price,
            $product->formatted_discount_price ?? 'N/A',
            number_format($product->final_price, 2),
            $product->discount_percentage . '%',
            strip_tags($product->description ?? 'N/A'),
            $product->google_drive_link ?? 'N/A',
            $product->youtube_video_link ?? 'N/A',
            $product->banner_image ?? 'N/A',
            $product->product_images ? count($product->product_images) : 0,
            $product->stock ?? 'Not Tracked',
            $product->stock_status,
            $product->status_text,
            $product->created_at->format('Y-m-d H:i:s'),
            $product->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Name
            'C' => 20,  // Category
            'D' => 15,  // SKU
            'E' => 25,  // Slug
            'F' => 12,  // Price
            'G' => 15,  // Discount Price
            'H' => 12,  // Final Price
            'I' => 12,  // Discount %
            'J' => 40,  // Description
            'K' => 30,  // Google Drive Link
            'L' => 30,  // YouTube Video Link
            'M' => 20,  // Banner Image
            'N' => 18,  // Product Images Count
            'O' => 12,  // Stock
            'P' => 15,  // Stock Status
            'Q' => 10,  // Status
            'R' => 20,  // Created At
            'S' => 20,  // Updated At
        ];
    }
}
