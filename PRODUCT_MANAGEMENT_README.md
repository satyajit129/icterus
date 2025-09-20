# Product Management System

## Overview

A comprehensive Product Management CRUD system integrated into the HRMS application with advanced features including image management, rich text editing, and search capabilities.

## Features

### Core CRUD Operations

-   ✅ Create new products
-   ✅ View product details
-   ✅ Edit existing products
-   ✅ Delete products
-   ✅ List all products with pagination

### Advanced Features

-   ✅ **Multiple Image Upload**: Support for multiple product images with preview
-   ✅ **Banner Image**: Single banner image for each product
-   ✅ **Rich Text Editor**: Summernote integration for product descriptions
-   ✅ **Advanced Search**: Search by name, category, SKU, description
-   ✅ **Filtering**: Filter by category, status, stock status, price range
-   ✅ **Export Functionality**: Excel export with comprehensive product data
-   ✅ **Image Preview**: Real-time image preview during upload
-   ✅ **Stock Management**: Optional stock tracking with status indicators
-   ✅ **Pricing**: Regular price and discount price with percentage calculation
-   ✅ **External Links**: Google Drive and YouTube video link support
-   ✅ **SEO Friendly**: Auto-generated slugs from product names

## Database Schema

### Products Table

```sql
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    sku VARCHAR(100) UNIQUE,                     -- optional, unique product code
    slug VARCHAR(255) UNIQUE,                    -- optional, SEO-friendly URL
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) DEFAULT NULL,
    description TEXT,
    google_drive_link VARCHAR(500) DEFAULT NULL,
    youtube_video_link VARCHAR(500) DEFAULT NULL, -- NEW FIELD
    banner_image VARCHAR(255) DEFAULT NULL,
    product_images JSON,                         -- multiple images as JSON array
    stock INT DEFAULT 0,                          -- optional stock tracking
    status TINYINT(1) DEFAULT 1,                 -- 1 = active, 0 = inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## File Structure

### Models

-   `app/Models/Product.php` - Product model with relationships and accessors

### Controllers

-   `app/Http/Controllers/Backend/ProductController.php` - Main controller for product operations

### Services

-   `app/Services/ProductService.php` - Business logic for product management

### Views

-   `resources/views/backend/pages/products.blade.php` - Product listing with search/filters
-   `resources/views/backend/pages/product_create_or_edit.blade.php` - Create/Edit form
-   `resources/views/backend/pages/product_view.blade.php` - Product details view

### Exports

-   `app/Exports/ProductExport.php` - Excel export functionality

### Routes

```php
Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'adminProductList'])->name('adminProductList');
    Route::get('/create-or-edit/{id?}', [ProductController::class, 'adminProductCreateOrEdit'])->name('adminProductCreateOrEdit');
    Route::post('/save/{id?}', [ProductController::class, 'adminProductSave'])->name('adminProductSave');
    Route::get('/delete/{id}', [ProductController::class, 'adminProductDelete'])->name('adminProductDelete');
    Route::get('/view/{id}', [ProductController::class, 'adminProductView'])->name('adminProductView');
    Route::get('/export', [ProductController::class, 'adminProductExport'])->name('adminProductExport');
    Route::post('/delete-image/{id}', [ProductController::class, 'adminProductDeleteImage'])->name('adminProductDeleteImage');
    Route::get('/toggle-status/{id}', [ProductController::class, 'adminProductToggleStatus'])->name('adminProductToggleStatus');
});
```

## Permissions Required

The system uses the following permissions for access control:

-   `manage_product` - General product management access
-   `add_product` - Create new products
-   `edit_product` - Edit existing products
-   `delete_product` - Delete products
-   `view_product` - View product details
-   `download_product` - Export product data

## Key Features Implementation

### 1. Image Management

-   **Banner Image**: Single image per product stored in `uploads/products/`
-   **Multiple Product Images**: Stored as JSON array in database
-   **Image Preview**: Real-time preview during upload
-   **Image Deletion**: Individual image removal capability

### 2. Rich Text Editor

-   **Summernote Integration**: Full-featured WYSIWYG editor
-   **Toolbar Features**: Bold, italic, lists, links, images, tables
-   **Image Upload Support**: Built-in image upload functionality

### 3. Search & Filtering

-   **Text Search**: Search across name, category, SKU, description
-   **Category Filter**: Dropdown with all available categories
-   **Status Filter**: Active/Inactive products
-   **Stock Filter**: In stock, out of stock, not tracked
-   **Price Range**: Min/max price filtering

### 4. Stock Management

-   **Optional Tracking**: Stock field is nullable
-   **Status Indicators**: Visual badges for stock status
-   **Stock Validation**: Prevents negative stock values

### 5. Pricing System

-   **Regular Price**: Required field
-   **Discount Price**: Optional, must be less than regular price
-   **Auto-calculation**: Discount percentage calculation
-   **Price Display**: Formatted price display with currency

### 6. External Links

-   **Google Drive**: File sharing links
-   **YouTube Videos**: Video content links
-   **URL Validation**: Proper URL format validation

## Usage Instructions

### 1. Setup

1. Run the migration: `php artisan migrate`
2. Seed permissions: `php artisan db:seed --class=ProductPermissionSeeder`
3. Assign permissions to roles as needed

### 2. Creating Products

1. Navigate to Products section in sidebar
2. Click "Add Product"
3. Fill in required fields (Name, Category, Price)
4. Upload banner image and product images
5. Add description using Summernote editor
6. Set stock quantity (optional)
7. Add external links if needed
8. Save product

### 3. Managing Products

-   **View**: Click eye icon to view product details
-   **Edit**: Click edit icon to modify product
-   **Delete**: Click delete icon to remove product
-   **Export**: Use download button to export data

### 4. Search & Filter

-   Use the search bar for text-based searches
-   Apply filters using dropdown menus
-   Clear filters using the "Clear" button

## Technical Implementation

### Model Features

-   **Accessors**: Formatted prices, stock status, discount percentage
-   **Scopes**: Active products, in stock, search, category filter
-   **Casts**: JSON for product images, decimal for prices
-   **Mutators**: Auto-slug generation from name

### Service Layer

-   **Image Handling**: Upload, storage, deletion
-   **Validation**: Comprehensive form validation
-   **Export**: Excel export with formatting
-   **Search**: Advanced search and filtering logic

### Frontend Features

-   **Responsive Design**: Mobile-friendly interface
-   **Image Preview**: Real-time upload preview
-   **Form Validation**: Client-side validation
-   **AJAX Operations**: Smooth user experience

## Security Features

-   **Permission-based Access**: Role-based access control
-   **File Upload Security**: Image type and size validation
-   **CSRF Protection**: Laravel CSRF tokens
-   **Input Validation**: Server-side validation
-   **File Storage**: Secure file storage in public directory

## Performance Optimizations

-   **Database Indexes**: Optimized queries with proper indexing
-   **Image Optimization**: Efficient image storage and display
-   **Pagination**: Large dataset handling
-   **Lazy Loading**: Efficient data loading

## Future Enhancements

-   Product variants and options
-   Inventory tracking
-   Product categories hierarchy
-   Bulk operations
-   Product reviews and ratings
-   Advanced reporting and analytics

## Troubleshooting

### Common Issues

1. **Image Upload Fails**: Check file permissions and storage directory
2. **Summernote Not Loading**: Verify CDN links and jQuery
3. **Export Issues**: Ensure Maatwebsite Excel package is installed
4. **Permission Errors**: Check user roles and permissions

### File Permissions

Ensure the following directories are writable:

-   `storage/app/public/uploads/products/`
-   `public/uploads/products/`

This Product Management system provides a complete solution for managing products within the HRMS application with modern features and user-friendly interface.
