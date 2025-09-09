<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Facebook Leads Collection Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Facebook leads collection to handle large
    | datasets and prevent timeouts.
    |
    */

    'collection' => [
        // Maximum number of pages to process (safety limit)
        'max_pages' => env('FACEBOOK_LEADS_MAX_PAGES', 100),

        // Batch size for database inserts
        'batch_size' => env('FACEBOOK_LEADS_BATCH_SIZE', 50),

        // API timeout in seconds
        'api_timeout' => env('FACEBOOK_LEADS_API_TIMEOUT', 120),

        // Delay between API requests in microseconds (0.2 seconds)
        'request_delay' => env('FACEBOOK_LEADS_REQUEST_DELAY', 200000),

        // Memory limit for lead collection
        'memory_limit' => env('FACEBOOK_LEADS_MEMORY_LIMIT', '512M'),

        // Maximum execution time for lead collection
        'max_execution_time' => env('FACEBOOK_LEADS_MAX_EXECUTION_TIME', 0), // 0 = no limit
    ],

    'optimization' => [
        // Enable batch processing
        'batch_processing' => env('FACEBOOK_LEADS_BATCH_PROCESSING', true),

        // Enable memory optimization
        'memory_optimization' => env('FACEBOOK_LEADS_MEMORY_OPTIMIZATION', true),

        // Enable progress logging
        'progress_logging' => env('FACEBOOK_LEADS_PROGRESS_LOGGING', true),
    ],
];
