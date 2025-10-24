<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for file uploads including size limits, file types,
    | and other upload-related settings.
    |
    */

    'max_file_size' => env('UPLOAD_MAX_FILE_SIZE', 10240), // 10MB in KB
    'max_total_size' => env('UPLOAD_MAX_TOTAL_SIZE', 51200), // 50MB in KB
    'max_files' => env('UPLOAD_MAX_FILES', 10),
    'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'storage_disk' => env('UPLOAD_STORAGE_DISK', 'public'),
    'storage_path' => env('UPLOAD_STORAGE_PATH', 'products'),
];



