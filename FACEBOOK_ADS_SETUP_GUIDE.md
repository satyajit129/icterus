# Facebook Ads API Setup Guide

## Overview

This guide will help you set up the Facebook Marketing API to sync ads from your Facebook ad accounts.

## Prerequisites

-   Facebook Business Manager account
-   Facebook Ad Account
-   Admin access to your Facebook Business Manager

## Step 1: Create a Facebook App

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click "My Apps" → "Create App"
3. Choose "Business" as the app type
4. Fill in the required information:
    - App Name: Your app name (e.g., "HRMS Ads Manager")
    - App Contact Email: Your email
    - Business Manager Account: Select your business account
5. Click "Create App"

## Step 2: Add Marketing API Product

1. In your app dashboard, go to "Products" in the left sidebar
2. Find "Marketing API" and click "Set Up"
3. Click "Create App" if prompted
4. You should see "Marketing API" in your products list

## Step 3: Configure App Permissions

1. Go to "App Review" → "Permissions and Features"
2. Request the following permissions:
    - `ads_read` - Read ads data
    - `ads_management` - Manage ads (optional, for future features)
    - `pages_read_engagement` - Read page data
    - `pages_show_list` - List pages

## Step 4: Add Ad Account to App

1. Go to "Business Settings" in your Business Manager
2. Navigate to "Ad Accounts" under "Accounts"
3. Find your ad account and click on it
4. Go to "Ad Account Settings"
5. Under "Ad Account Access", click "Add People"
6. Add your Facebook App as a partner
7. Grant "Advertiser" or "Admin" access

## Step 5: Generate Access Token

### Option A: Using Graph API Explorer (Recommended for testing)

1. Go to [Graph API Explorer](https://developers.facebook.com/tools/explorer/)
2. Select your app from the dropdown
3. Add the required permissions:
    - `ads_read`
    - `pages_read_engagement`
    - `pages_show_list`
4. Click "Generate Access Token"
5. Copy the generated token

### Option B: Using App Access Token

1. In your app dashboard, go to "Settings" → "Basic"
2. Copy your "App ID" and "App Secret"
3. Generate an app access token using:
    ```
    https://graph.facebook.com/oauth/access_token?client_id={app-id}&client_secret={app-secret}&grant_type=client_credentials
    ```

## Step 6: Configure in HRMS System

1. Go to your HRMS admin panel
2. Navigate to "Facebook Credentials"
3. Fill in the following:
    - **App ID**: Your Facebook App ID
    - **App Secret**: Your Facebook App Secret
    - **User Access Token**: The token generated in Step 5
    - **API URL**: `https://graph.facebook.com/v23.0/`
    - **Version**: `v23.0`
4. Save the credentials

## Step 7: Test the Connection

1. Go to "Facebook Ads" in your admin panel
2. Select any page from the dropdown
3. Click "Test Connection"
4. You should see a success message with the number of ads found

## Troubleshooting

### Common Issues

#### 1. "Invalid Access Token" Error

-   **Cause**: Token expired or incorrect permissions
-   **Solution**: Generate a new token with proper permissions

#### 2. "Ad Account Not Found" Error

-   **Cause**: Ad account not assigned to the app
-   **Solution**: Follow Step 4 to add your ad account to the app

#### 3. "Insufficient Permissions" Error

-   **Cause**: Missing required permissions
-   **Solution**: Request `ads_read` permission in App Review

#### 4. "API Version Deprecated" Error

-   **Cause**: Using old API version
-   **Solution**: Update API URL to `https://graph.facebook.com/v23.0/`

### Token Types

#### User Access Token (Recommended)

-   Generated through Graph API Explorer
-   Has user-level permissions
-   Can access user's ad accounts
-   Expires and needs refresh

#### App Access Token

-   Generated using App ID and Secret
-   Has app-level permissions
-   More stable but limited scope
-   Good for basic operations

## API Endpoints Used

### Get User's Ad Accounts

```
GET /v23.0/me/adaccounts
Fields: id, name, account_status, currency, timezone_name
```

### Get Ads from Ad Account

```
GET /v23.0/act_{ad-account-id}/ads
Fields: id, name, status, effective_status, campaign_id, adset_id, creative, targeting
```

### Get Ad Insights

```
GET /v23.0/{ad-id}/insights
Fields: impressions, clicks, spend, reach, frequency, cpm, cpc, ctr, conversions
```

## Security Best Practices

1. **Never commit tokens to version control**
2. **Use environment variables for sensitive data**
3. **Regularly rotate access tokens**
4. **Monitor API usage and limits**
5. **Use HTTPS for all API calls**

## Rate Limits

Facebook Marketing API has rate limits:

-   **Ad Account Level**: 4,800 calls per hour
-   **App Level**: 200 calls per hour per user
-   **User Level**: 200 calls per hour

The system includes automatic rate limit handling with delays.

## Support

If you encounter issues:

1. Check Facebook's [API Documentation](https://developers.facebook.com/docs/marketing-api/)
2. Verify your app permissions in Facebook Developer Console
3. Test your token using Graph API Explorer
4. Check the Laravel logs for detailed error messages

## Additional Resources

-   [Facebook Marketing API Documentation](https://developers.facebook.com/docs/marketing-api/)
-   [Graph API Explorer](https://developers.facebook.com/tools/explorer/)
-   [Facebook Business Help Center](https://www.facebook.com/business/help)
-   [Marketing API Changelog](https://developers.facebook.com/docs/marketing-api/changelog)
