<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Facebook Leads Collection Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for Facebook leads collection
    | including memory limits, timeouts, and optimization settings.
    |
    */

    'collection' => [
        // Memory limit for large datasets (default: 512M)
        'memory_limit' => env('FACEBOOK_LEADS_MEMORY_LIMIT', '512M'),

        // Maximum execution time in seconds (0 = unlimited)
        'max_execution_time' => env('FACEBOOK_LEADS_MAX_EXECUTION_TIME', 0),

        // Maximum number of pages to process (safety limit)
        'max_pages' => env('FACEBOOK_LEADS_MAX_PAGES', 100),

        // Batch size for database inserts
        'batch_size' => env('FACEBOOK_LEADS_BATCH_SIZE', 50),

        // API timeout in seconds
        'api_timeout' => env('FACEBOOK_LEADS_API_TIMEOUT', 120),

        // Delay between requests in microseconds (200ms default)
        'request_delay' => env('FACEBOOK_LEADS_REQUEST_DELAY', 200000),
    ],

    'optimization' => [
        // Enable progress logging
        'progress_logging' => env('FACEBOOK_LEADS_PROGRESS_LOGGING', true),

        // Enable token refresh
        'token_refresh' => env('FACEBOOK_LEADS_TOKEN_REFRESH', true),

        // Enable fallback to user token
        'user_token_fallback' => env('FACEBOOK_LEADS_USER_TOKEN_FALLBACK', true),
    ],
];
