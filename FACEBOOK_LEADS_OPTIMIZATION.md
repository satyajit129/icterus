# Facebook Leads Collection Optimization

## Overview

This document explains the optimization features implemented to handle large Facebook leads datasets (1000+ leads) efficiently without timeouts.

## Problem Solved

-   **Facebook API Pagination**: Facebook returns only 100 leads per request
-   **Timeout Issues**: Large datasets cause PHP timeout errors
-   **Memory Issues**: Processing thousands of leads can exhaust server memory
-   **Performance**: Individual database inserts are slow for large datasets

## Solutions Implemented

### 1. Optimized Collection Method

**File**: `app/Services/FacebookLeadService.php` - `handleCollectLeadsOptimized()`

**Features**:

-   **Pagination Handling**: Automatically follows Facebook's pagination links
-   **Batch Processing**: Processes leads in batches of 50 (configurable)
-   **Memory Optimization**: Uses `array_chunk()` to process data in smaller chunks
-   **Progress Logging**: Logs progress for monitoring large collections
-   **Error Resilience**: Continues processing even if individual leads fail

### 2. Configuration System

**File**: `config/facebook_leads.php`

**Configurable Settings**:

```php
'collection' => [
    'max_pages' => 100,           // Maximum pages to process
    'batch_size' => 50,           // Database insert batch size
    'api_timeout' => 120,         // API request timeout (seconds)
    'request_delay' => 200000,    // Delay between requests (microseconds)
    'memory_limit' => '512M',     // PHP memory limit
    'max_execution_time' => 0,    // PHP execution time limit (0 = no limit)
],
```

### 3. PHP Settings Optimization

**Automatic Settings**:

-   **Memory Limit**: Increased to 512M (configurable)
-   **Execution Time**: Removed limit for large collections
-   **Request Timeout**: Increased to 120 seconds

### 4. Database Optimization

**Batch Inserts**:

-   Uses `FacebookLead::insert()` for bulk inserts
-   Processes data in chunks to avoid memory issues
-   Significantly faster than individual `create()` calls

### 5. Rate Limiting Protection

**Features**:

-   **Request Delays**: 0.2-second delay between API requests
-   **Error Handling**: Continues processing on individual request failures
-   **Access Token Validation**: Stops collection on token errors

## Usage

### 1. Web Interface

**Standard Collection**:

-   Use "Collect Leads" button for small datasets (< 500 leads)
-   Suitable for quick collections and testing

**Optimized Collection**:

-   Use "Collect All (Optimized)" button for large datasets
-   Handles 1000+ leads efficiently
-   Shows confirmation dialog before starting

### 2. Command Line

**Basic Collection**:

```bash
php artisan facebook:collect-leads {form_id}
```

**Optimized Collection**:

```bash
php artisan facebook:collect-leads {form_id} --optimized
```

**With Date Range**:

```bash
php artisan facebook:collect-leads {form_id} --optimized --start-date=2024-01-01 --end-date=2024-12-31
```

### 3. Background Processing

**Queue Integration** (Future Enhancement):

```php
// Dispatch to queue for background processing
dispatch(new CollectFacebookLeadsJob($formId, $startDate, $endDate));
```

## Performance Metrics

### Before Optimization

-   **Max Leads**: ~100 (single page)
-   **Timeout**: After 30 seconds
-   **Memory**: High usage with individual inserts
-   **Error Handling**: Stops on first error

### After Optimization

-   **Max Leads**: 10,000+ (100+ pages)
-   **Timeout**: No limit (configurable)
-   **Memory**: Optimized with batch processing
-   **Error Handling**: Continues on individual errors

## Monitoring

### 1. Progress Logging

**Log File**: `storage/logs/laravel.log`

**Sample Log Entries**:

```
[2024-01-01 10:00:00] local.INFO: Facebook Leads Collection Progress - Page 1: 100 leads collected so far
[2024-01-01 10:00:05] local.INFO: Facebook Leads Collection Progress - Page 2: 200 leads collected so far
```

### 2. Error Tracking

**Error Types Logged**:

-   API request failures
-   Individual lead processing errors
-   Database insert errors
-   Access token issues

### 3. Performance Metrics

**Tracked Metrics**:

-   Total leads collected
-   Leads skipped (already exist)
-   Errors encountered
-   Processing time per page

## Configuration Examples

### For Small Servers (1GB RAM)

```php
'memory_limit' => '256M',
'batch_size' => 25,
'max_pages' => 50,
```

### For Large Servers (8GB+ RAM)

```php
'memory_limit' => '2G',
'batch_size' => 100,
'max_pages' => 500,
```

### For Production with Rate Limits

```php
'request_delay' => 500000, // 0.5 seconds
'api_timeout' => 60,
```

## Troubleshooting

### Common Issues

1. **Memory Exhausted**

    - Increase `memory_limit` in config
    - Reduce `batch_size`

2. **API Timeouts**

    - Increase `api_timeout` setting
    - Check network connectivity

3. **Rate Limiting**

    - Increase `request_delay`
    - Check Facebook API limits

4. **Database Locks**
    - Reduce `batch_size`
    - Check database performance

### Debug Mode

Enable detailed logging:

```php
'optimization' => [
    'progress_logging' => true,
    'debug_mode' => true,
],
```

## Future Enhancements

1. **Queue Integration**: Background processing for very large datasets
2. **Progress Tracking**: Real-time progress updates via WebSocket
3. **Resume Capability**: Resume interrupted collections
4. **Parallel Processing**: Multiple forms simultaneously
5. **Caching**: Cache API responses for faster re-collection

## Best Practices

1. **Test First**: Always test with small date ranges first
2. **Monitor Resources**: Watch memory and CPU usage
3. **Schedule Off-Peak**: Run large collections during low-traffic hours
4. **Backup Data**: Ensure database backups before large collections
5. **Error Monitoring**: Set up alerts for collection failures

## Support

For issues or questions:

1. Check the logs in `storage/logs/laravel.log`
2. Verify Facebook API credentials
3. Test with smaller date ranges
4. Check server resources (memory, CPU, disk space)
