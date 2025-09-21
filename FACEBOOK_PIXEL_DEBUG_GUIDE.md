# Facebook Pixel Debug Guide

## 🔍 Console Validation & Connection Testing

This guide shows you how to verify your Facebook Pixel setup is working correctly using browser console tools.

## 📊 What You'll See in Console

### ✅ **Successful Setup**

When your pixel is properly configured, you'll see:

```
✅ Facebook Pixel ID validated: 123456789012345
✅ Facebook Pixel initialized successfully with ID: 123456789012345
✅ Facebook Pixel PageView event sent
✅ Facebook Pixel is active and ready
📊 Pixel Status: Connected and tracking
🎯 Available events: ["PageView", "ViewContent", "AddToCart", "InitiateCheckout", "Purchase"]
🛠️ Debug helpers available:
- pixelDebug.status() - Get pixel status
- pixelDebug.testAll() - Test all events
- pixelDebug.testConnection() - Test connection
- pixelDebug.track(event, data) - Track custom event
```

### ❌ **Common Issues**

#### Invalid Pixel ID

```
❌ Facebook Pixel Error: Invalid Pixel ID format. Expected 15-16 digits, got: 123
Please check your Pixel ID in Settings > Facebook Pixel Settings
```

#### Connection Failed

```
❌ Facebook Pixel failed to initialize properly
❌ Facebook Pixel connection test failed
```

#### Pixel Disabled

```
⚠️ Facebook Pixel is disabled
To enable: Go to Settings > Facebook Pixel Settings
```

## 🛠️ Debug Commands

Open your browser console (F12) and use these commands:

### 1. Check Pixel Status

```javascript
pixelDebug.status();
```

**Returns:**

```javascript
{
    pixelId: "123456789012345",
    isEnabled: true,
    isConnected: true,
    enabledEvents: ["PageView", "ViewContent", "AddToCart", "InitiateCheckout", "Purchase"],
    fbqAvailable: true,
    timestamp: "2025-01-21T10:30:00.000Z"
}
```

### 2. Test Connection

```javascript
pixelDebug.testConnection();
```

**Returns:** `true` if connected, `false` if not

### 3. Test All Events

```javascript
pixelDebug.testAll();
```

**Sends test events for all enabled tracking**

### 4. Track Custom Event

```javascript
pixelDebug.track("ViewContent", {
    content_ids: ["test_123"],
    content_type: "product",
    content_name: "Test Product",
    value: 99.99,
    currency: "BDT",
});
```

## 🔧 Troubleshooting Steps

### Step 1: Check Pixel ID Format

-   Must be 15-16 digits only
-   No letters or special characters
-   Example: `123456789012345`

### Step 2: Verify Settings

1. Go to **Admin Panel > Settings**
2. Scroll to **Facebook Pixel Settings**
3. Ensure **Enable Facebook Pixel** is checked
4. Verify **Facebook Pixel ID** is correct
5. Check **Tracked Events** are selected

### Step 3: Test Connection

```javascript
// In browser console
pixelDebug.testConnection();
```

### Step 4: Check Network Tab

1. Open **Developer Tools > Network Tab**
2. Look for requests to:
    - `connect.facebook.net/en_US/fbevents.js`
    - `facebook.com/tr?id=YOUR_PIXEL_ID`

### Step 5: Use Facebook Pixel Helper

1. Install [Facebook Pixel Helper](https://chrome.google.com/webstore/detail/facebook-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc) Chrome extension
2. Visit your website
3. Click the extension icon
4. Check for pixel activity

## 📈 Event Tracking Verification

### PageView Events

-   **Automatic**: Fires on every page load
-   **Console**: Look for `✅ Facebook Pixel PageView event sent`

### ViewContent Events

-   **Trigger**: When users click "View Details" on products
-   **Console**: Look for `🎯 Facebook Pixel Event: ViewContent`

### AddToCart Events

-   **Trigger**: When users click "Order Now"
-   **Console**: Look for `🎯 Facebook Pixel Event: AddToCart`

### InitiateCheckout Events

-   **Trigger**: When users submit order form
-   **Console**: Look for `🎯 Facebook Pixel Event: InitiateCheckout`

### Purchase Events

-   **Trigger**: When order is completed successfully
-   **Console**: Look for `🎯 Facebook Pixel Event: Purchase`

## 🚨 Common Error Messages

### "Invalid Pixel ID format"

-   **Cause**: Pixel ID is not 15-16 digits
-   **Fix**: Check your Pixel ID in settings

### "Facebook Pixel disabled"

-   **Cause**: Pixel is not enabled in settings
-   **Fix**: Enable pixel in Admin Panel > Settings

### "Cannot track event - Pixel not connected"

-   **Cause**: Network issues or invalid pixel ID
-   **Fix**: Check internet connection and pixel ID

### "Event not enabled"

-   **Cause**: Event is not selected in settings
-   **Fix**: Enable the event in settings

## 🔍 Advanced Debugging

### Check Facebook Events Manager

1. Go to [Facebook Events Manager](https://business.facebook.com/events_manager)
2. Select your pixel
3. Check **Test Events** tab
4. Look for incoming events

### Network Request Analysis

1. Open **Developer Tools > Network Tab**
2. Filter by "facebook" or "fbq"
3. Look for successful requests
4. Check response status codes

### Console Error Analysis

Look for these specific error patterns:

-   `fbq is not defined` - Pixel script not loaded
-   `Invalid pixel ID` - Wrong ID format
-   `Network error` - Connection issues
-   `Event validation failed` - Event data issues

## 📱 Mobile Testing

### iOS Safari

1. Enable **Web Inspector** in Settings
2. Connect to Mac and use Safari Developer Tools
3. Check console for pixel messages

### Android Chrome

1. Enable **USB Debugging**
2. Use Chrome DevTools
3. Check console for pixel activity

## 🎯 Best Practices

### 1. Always Test First

-   Test on staging environment
-   Use test events before going live
-   Verify all events are firing

### 2. Monitor Regularly

-   Check Events Manager daily
-   Monitor console for errors
-   Test after any changes

### 3. Keep Updated

-   Update pixel code when Facebook releases updates
-   Test after any website changes
-   Monitor for deprecated features

## 📞 Support Resources

### Facebook Resources

-   [Facebook Pixel Documentation](https://developers.facebook.com/docs/facebook-pixel/)
-   [Facebook Business Help Center](https://www.facebook.com/business/help)
-   [Facebook Developer Community](https://developers.facebook.com/community/)

### Browser Tools

-   [Facebook Pixel Helper](https://chrome.google.com/webstore/detail/facebook-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc)
-   [Facebook Pixel Tester](https://business.facebook.com/events_manager/test_events)

### Debug Commands Reference

```javascript
// Quick status check
pixelDebug.status();

// Test connection
pixelDebug.testConnection();

// Test all events
pixelDebug.testAll();

// Track custom event
pixelDebug.track("EventName", { data: "value" });

// Get pixel object
window.FacebookPixel;

// Check if fbq is available
typeof fbq !== "undefined";
```

---

**Remember**: Always test your pixel implementation before going live to ensure proper tracking and data collection! 🚀
