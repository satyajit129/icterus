# Facebook Pixel Implementation Guide

## 🎯 Overview

This implementation provides comprehensive Facebook Pixel tracking across all public pages of your HRMS e-commerce system. The pixel tracks user interactions, conversions, and provides valuable data for Facebook advertising optimization.

## 📁 Files Created/Modified

### 1. Database Migration

-   **File**: `database/migrations/2025_01_21_120000_add_facebook_pixel_settings_to_settings_table.php`
-   **Purpose**: Adds Facebook Pixel configuration fields to the settings table
-   **Fields Added**:
    -   `facebook_pixel_id` (string, nullable)
    -   `facebook_pixel_enabled` (boolean, default: false)
    -   `facebook_pixel_events` (text, nullable) - JSON array of enabled events

### 2. Reusable Component

-   **File**: `resources/views/components/facebook-pixel.blade.php`
-   **Purpose**: Centralized Facebook Pixel code with dynamic configuration
-   **Features**:
    -   Automatic pixel initialization
    -   Event tracking helper functions
    -   Conditional loading based on settings
    -   Console logging for debugging

### 3. Public Pages Updated

All public pages now include Facebook Pixel tracking:

#### Landing Page (`resources/views/public/landing.blade.php`)

-   **Events Tracked**:
    -   `PageView` - Automatic on page load
    -   `ViewContent` - When users click "View Details" on products
    -   `Search` - When users click "Shop Now" or "View All Products"

#### Products Page (`resources/views/public/products.blade.php`)

-   **Events Tracked**:
    -   `PageView` - Automatic on page load
    -   `ViewContent` - When users click "View Details" on products

#### Product Details Page (`resources/views/public/product-details.blade.php`)

-   **Events Tracked**:
    -   `PageView` - Automatic on page load
    -   `ViewContent` - When page loads (product view)
    -   `AddToCart` - When users click "Order Now"

#### Order Page (`resources/views/public/order-page.blade.php`)

-   **Events Tracked**:
    -   `PageView` - Automatic on page load
    -   `ViewContent` - When page loads (product view)
    -   `InitiateCheckout` - When form is submitted

#### Order Success Page (`resources/views/public/order-success.blade.php`)

-   **Events Tracked**:
    -   `PageView` - Automatic on page load
    -   `Purchase` - When page loads (order completed)

### 4. Settings Management

-   **File**: `resources/views/backend/pages/settings.blade.php`
-   **Features**:

    -   Enable/disable pixel toggle
    -   Pixel ID configuration
    -   Event selection checkboxes
    -   Help text and validation

-   **File**: `app/Services/SettingService.php`
-   **Features**:
    -   Validation for pixel fields
    -   JSON encoding of selected events
    -   Boolean handling for enable/disable

## 🚀 Setup Instructions

### 1. Run Migration

```bash
php artisan migrate
```

### 2. Configure Pixel Settings

1. Go to **Admin Panel > Settings**
2. Scroll to **Facebook Pixel Settings** section
3. Enable Facebook Pixel toggle
4. Enter your Facebook Pixel ID
5. Select which events to track
6. Save settings

### 3. Get Your Facebook Pixel ID

1. Go to [Facebook Events Manager](https://business.facebook.com/events_manager)
2. Select your business account
3. Click on "Data Sources" > "Pixels"
4. Copy your Pixel ID (usually 15-16 digits)

## 📊 Tracked Events

### Standard E-commerce Events

-   **PageView**: Automatic on all page loads
-   **ViewContent**: When users view product details
-   **AddToCart**: When users click "Order Now"
-   **InitiateCheckout**: When users submit order form
-   **Purchase**: When order is completed successfully

### Additional Events

-   **Search**: When users browse products
-   **Lead**: Available for future use
-   **Contact**: Available for future use

## 🔧 Event Data Structure

### ViewContent Event

```javascript
{
    content_ids: ['product_id'],
    content_type: 'product',
    content_name: 'Product Name',
    content_category: 'Product Category',
    value: 99.99,
    currency: 'BDT'
}
```

### AddToCart Event

```javascript
{
    content_ids: ['product_id'],
    content_type: 'product',
    content_name: 'Product Name',
    content_category: 'Product Category',
    value: 99.99,
    currency: 'BDT',
    num_items: 1
}
```

### InitiateCheckout Event

```javascript
{
    content_ids: ['product_id'],
    content_type: 'product',
    content_name: 'Product Name',
    content_category: 'Product Category',
    value: 99.99,
    currency: 'BDT',
    num_items: 1
}
```

### Purchase Event

```javascript
{
    content_ids: ['product_id'],
    content_type: 'product',
    content_name: 'Product Name',
    content_category: 'Product Category',
    value: 99.99,
    currency: 'BDT',
    num_items: 1
}
```

## 🛠️ Customization

### Adding New Events

1. Update the `facebook-pixel.blade.php` component
2. Add new tracking functions
3. Update settings page with new event options
4. Add validation in `SettingService.php`

### Modifying Event Data

Edit the event tracking calls in the respective view files to include additional parameters.

### Debugging

-   Check browser console for pixel event logs
-   Use Facebook Pixel Helper browser extension
-   Verify events in Facebook Events Manager

## 🔒 Security Features

-   **Permission-based**: Only users with proper permissions can configure pixel settings
-   **Validation**: All pixel data is validated before saving
-   **Conditional Loading**: Pixel only loads when enabled in settings
-   **Error Handling**: Graceful fallbacks when pixel fails to load

## 📈 Benefits

1. **Conversion Tracking**: Track complete purchase funnel
2. **Audience Building**: Create custom audiences based on behavior
3. **Retargeting**: Show ads to users who viewed products
4. **Optimization**: Optimize ads for specific events
5. **Analytics**: Detailed insights into user behavior
6. **ROI Measurement**: Track return on ad spend accurately

## 🚨 Important Notes

1. **GDPR Compliance**: Ensure you have proper consent mechanisms
2. **Testing**: Always test pixel implementation before going live
3. **Privacy**: Respect user privacy and data protection laws
4. **Performance**: Pixel loading is optimized to not impact page speed
5. **Updates**: Keep pixel code updated with Facebook's latest requirements

## 🔍 Troubleshooting

### Pixel Not Loading

-   Check if pixel is enabled in settings
-   Verify pixel ID is correct
-   Check browser console for errors

### Events Not Firing

-   Verify event is enabled in settings
-   Check browser console for event logs
-   Use Facebook Pixel Helper extension

### Data Not Appearing in Facebook

-   Wait 20-30 minutes for data to appear
-   Check if pixel is properly installed
-   Verify events are firing correctly

## 📞 Support

For technical support or questions about this implementation, please refer to:

-   Facebook Pixel Documentation
-   Facebook Business Help Center
-   Your development team

---

**Implementation Date**: January 21, 2025
**Version**: 1.0
**Compatibility**: Laravel 12, PHP 8.2+
