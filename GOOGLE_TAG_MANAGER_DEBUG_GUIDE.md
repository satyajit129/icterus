# Google Tag Manager Debug Guide

## 🔍 Console Validation & Connection Testing

This guide shows you how to verify your Google Tag Manager (GTM) setup is working correctly using browser console tools.

## 📊 What You'll See in Console

### ✅ **Successful Setup**

When your GTM is properly configured, you'll see:

```
✅ GTM ID validated: GTM-XXXXXXX
✅ GTM connection test passed
✅ GTM is active and ready
📊 GTM Status: Connected and tracking
🎯 Available events: ["page_view", "view_item", "add_to_cart", "begin_checkout", "purchase"]
🛠️ GTM Debug helpers available:
- gtmDebug.status() - Get GTM status
- gtmDebug.testAll() - Test all events
- gtmDebug.testConnection() - Test connection
- gtmDebug.push(event, data) - Push custom event
- gtmDebug.clear() - Clear dataLayer
- gtmDebug.viewItem(data) - Track view item
- gtmDebug.addToCart(data) - Track add to cart
- gtmDebug.beginCheckout(data) - Track begin checkout
- gtmDebug.purchase(data) - Track purchase
- gtmDebug.search(data) - Track search
```

### ❌ **Common Issues**

#### Invalid GTM ID

```
❌ GTM Error: Invalid GTM ID format. Expected GTM-XXXXXXX, got: 123
Please check your GTM ID in Settings > Google Tag Manager Settings
```

#### Connection Failed

```
❌ GTM connection test failed - dataLayer not found
❌ GTM connection test error: [error details]
```

#### GTM Disabled

```
⚠️ Google Tag Manager is disabled
To enable: Go to Settings > Google Tag Manager Settings
```

## 🛠️ Debug Commands

Open your browser console (F12) and use these commands:

### 1. Check GTM Status

```javascript
gtmDebug.status();
```

**Returns:**

```javascript
{
    gtmId: "GTM-XXXXXXX",
    isEnabled: true,
    isConnected: true,
    enabledEvents: ["page_view", "view_item", "add_to_cart", "begin_checkout", "purchase"],
    customDimensions: {"user_type": "premium"},
    ecommerceSettings: {"currency": "BDT", "country": "BD"},
    dataLayerAvailable: true,
    dataLayerLength: 5,
    timestamp: "2025-01-21T10:30:00.000Z"
}
```

### 2. Test Connection

```javascript
gtmDebug.testConnection();
```

**Returns:** `true` if connected, `false` if not

### 3. Test All Events

```javascript
gtmDebug.testAll();
```

**Sends test events for all enabled tracking**

### 4. Push Custom Event

```javascript
gtmDebug.push("custom_event", {
    event_category: "engagement",
    event_action: "click",
    event_label: "test_button",
});
```

### 5. Track E-commerce Events

```javascript
// View Item
gtmDebug.viewItem({
    item_id: "123",
    item_name: "Test Product",
    item_category: "Electronics",
    price: 99.99,
    value: 99.99,
    quantity: 1,
});

// Add to Cart
gtmDebug.addToCart({
    item_id: "123",
    item_name: "Test Product",
    item_category: "Electronics",
    price: 99.99,
    value: 99.99,
    quantity: 1,
});

// Begin Checkout
gtmDebug.beginCheckout({
    currency: "BDT",
    value: 99.99,
    items: [
        {
            item_id: "123",
            item_name: "Test Product",
            price: 99.99,
            quantity: 1,
        },
    ],
});

// Purchase
gtmDebug.purchase({
    transaction_id: "ORDER-123",
    currency: "BDT",
    value: 99.99,
    items: [
        {
            item_id: "123",
            item_name: "Test Product",
            price: 99.99,
            quantity: 1,
        },
    ],
});

// Search
gtmDebug.search({
    search_term: "electronics",
});
```

### 6. Clear DataLayer

```javascript
gtmDebug.clear();
```

**Clears the dataLayer for testing**

## 🔧 Troubleshooting Steps

### Step 1: Check GTM ID Format

-   Must start with "GTM-" followed by alphanumeric characters
-   Example: `GTM-XXXXXXX`
-   No spaces or special characters

### Step 2: Verify Settings

1. Go to **Admin Panel > Settings**
2. Scroll to **Google Tag Manager Settings**
3. Ensure **Enable Google Tag Manager** is checked
4. Verify **GTM Container ID** is correct
5. Check **Tracked Events** are selected

### Step 3: Test Connection

```javascript
// In browser console
gtmDebug.testConnection();
```

### Step 4: Check Network Tab

1. Open **Developer Tools > Network Tab**
2. Look for requests to:
    - `googletagmanager.com/gtm.js?id=GTM-XXXXXXX`
    - `googletagmanager.com/ns.html?id=GTM-XXXXXXX`

### Step 5: Use GTM Preview Mode

1. Go to [Google Tag Manager](https://tagmanager.google.com)
2. Select your container
3. Click **Preview** button
4. Enter your website URL
5. Check for tag firing

## 📈 Event Tracking Verification

### Page View Events

-   **Automatic**: Fires on every page load
-   **Console**: Look for `✅ GTM Event: page_view`

### View Item Events

-   **Trigger**: When users click "View Details" on products
-   **Console**: Look for `🎯 GTM Event: view_item`

### Add to Cart Events

-   **Trigger**: When users click "Order Now"
-   **Console**: Look for `🎯 GTM Event: add_to_cart`

### Begin Checkout Events

-   **Trigger**: When users submit order form
-   **Console**: Look for `🎯 GTM Event: begin_checkout`

### Purchase Events

-   **Trigger**: When order is completed successfully
-   **Console**: Look for `🎯 GTM Event: purchase`

### Search Events

-   **Trigger**: When users click "Shop Now" or "View All Products"
-   **Console**: Look for `🎯 GTM Event: search`

## 🚨 Common Error Messages

### "Invalid GTM ID format"

-   **Cause**: GTM ID is not in correct format
-   **Fix**: Check your GTM ID in settings

### "Google Tag Manager is disabled"

-   **Cause**: GTM is not enabled in settings
-   **Fix**: Enable GTM in Admin Panel > Settings

### "Cannot push event - GTM not connected"

-   **Cause**: Network issues or invalid GTM ID
-   **Fix**: Check internet connection and GTM ID

### "Event not enabled"

-   **Cause**: Event is not selected in settings
-   **Fix**: Enable the event in settings

## 🔍 Advanced Debugging

### Check Google Tag Manager

1. Go to [Google Tag Manager](https://tagmanager.google.com)
2. Select your container
3. Check **Tags** tab for configured tags
4. Use **Preview** mode to test

### Network Request Analysis

1. Open **Developer Tools > Network Tab**
2. Filter by "googletagmanager" or "gtm"
3. Look for successful requests
4. Check response status codes

### DataLayer Inspection

```javascript
// Check dataLayer contents
console.log(dataLayer);

// Check dataLayer length
console.log("DataLayer length:", dataLayer.length);

// Check specific events
dataLayer.filter((item) => item.event === "purchase");
```

### Console Error Analysis

Look for these specific error patterns:

-   `dataLayer is not defined` - GTM script not loaded
-   `Invalid GTM ID` - Wrong ID format
-   `Network error` - Connection issues
-   `Event validation failed` - Event data issues

## 📱 Mobile Testing

### iOS Safari

1. Enable **Web Inspector** in Settings
2. Connect to Mac and use Safari Developer Tools
3. Check console for GTM messages

### Android Chrome

1. Enable **USB Debugging**
2. Use Chrome DevTools
3. Check console for GTM activity

## 🎯 Best Practices

### 1. Always Test First

-   Test on staging environment
-   Use test events before going live
-   Verify all events are firing

### 2. Monitor Regularly

-   Check GTM dashboard daily
-   Monitor console for errors
-   Test after any changes

### 3. Keep Updated

-   Update GTM code when Google releases updates
-   Test after any website changes
-   Monitor for deprecated features

## 📞 Support Resources

### Google Resources

-   [Google Tag Manager Documentation](https://developers.google.com/tag-manager)
-   [Google Analytics Help Center](https://support.google.com/analytics)
-   [Google Tag Manager Community](https://support.google.com/tagmanager/community)

### Browser Tools

-   [Google Tag Assistant](https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejbdjndbnbjgmefkgdddjlbokphdefk)
-   [DataLayer Inspector](https://chrome.google.com/webstore/detail/datalayer-inspector/ffnidfmdmblgidgmkjfngjfcdjfcdjf)

### Debug Commands Reference

```javascript
// Quick status check
gtmDebug.status();

// Test connection
gtmDebug.testConnection();

// Test all events
gtmDebug.testAll();

// Push custom event
gtmDebug.push("event_name", { data: "value" });

// Clear dataLayer
gtmDebug.clear();

// E-commerce events
gtmDebug.viewItem({ item_id: "123", item_name: "Product" });
gtmDebug.addToCart({ item_id: "123", item_name: "Product" });
gtmDebug.beginCheckout({ currency: "BDT", value: 99.99 });
gtmDebug.purchase({ transaction_id: "123", value: 99.99 });
gtmDebug.search({ search_term: "electronics" });

// Get GTM object
window.GoogleTagManager;

// Check if dataLayer is available
typeof dataLayer !== "undefined";
```

## 🔧 GTM Container Setup

### 1. Create GTM Container

1. Go to [Google Tag Manager](https://tagmanager.google.com)
2. Click **Create Account** or **Create Container**
3. Enter container name and select **Web**
4. Copy the Container ID (GTM-XXXXXXX)

### 2. Configure Tags

1. **Google Analytics 4**: For page views and e-commerce
2. **Google Ads Conversion**: For conversion tracking
3. **Custom HTML**: For custom tracking code
4. **Custom Event**: For custom events

### 3. Set Up Triggers

1. **Page View**: All pages
2. **Click**: Specific buttons/links
3. **Form Submission**: Order forms
4. **Custom Event**: Custom triggers

### 4. Configure Variables

1. **Data Layer Variables**: For dynamic data
2. **Built-in Variables**: For page info
3. **Custom Variables**: For specific needs

## 📊 E-commerce Tracking

### Enhanced E-commerce Events

```javascript
// View Item List
gtmDebug.push("view_item_list", {
    item_list_id: "featured_products",
    item_list_name: "Featured Products",
    items: [
        {
            item_id: "123",
            item_name: "Product 1",
            item_category: "Electronics",
            price: 99.99,
            quantity: 1,
        },
    ],
});

// Remove from Cart
gtmDebug.push("remove_from_cart", {
    currency: "BDT",
    value: 99.99,
    items: [
        {
            item_id: "123",
            item_name: "Product 1",
            price: 99.99,
            quantity: 1,
        },
    ],
});

// View Cart
gtmDebug.push("view_cart", {
    currency: "BDT",
    value: 199.98,
    items: [
        {
            item_id: "123",
            item_name: "Product 1",
            price: 99.99,
            quantity: 2,
        },
    ],
});
```

## 🎯 Custom Dimensions

### Setting Up Custom Dimensions

1. In GTM, go to **Variables**
2. Click **New** > **Data Layer Variable**
3. Set **Data Layer Variable Name** to your dimension key
4. Use in tags and triggers

### Example Custom Dimensions

```javascript
// In settings, add custom dimensions JSON:
{
    "user_type": "premium",
    "page_category": "electronics",
    "experiment_group": "A"
}
```

---

**Remember**: Always test your GTM implementation before going live to ensure proper tracking and data collection! 🚀
