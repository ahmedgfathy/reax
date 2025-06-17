<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Appwrite Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to Appwrite database and storage
    |
    */

    'endpoint' => env('APPWRITE_ENDPOINT', 'https://cloud.appwrite.io/v1'),
    'project_id' => env('APPWRITE_PROJECT_ID'),
    'database_id' => env('APPWRITE_DATABASE_ID'),
    'api_key' => env('APPWRITE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Collection IDs
    |--------------------------------------------------------------------------
    */
    'collections' => [
        'users' => env('APPWRITE_USERS_COLLECTION_ID'),
        'leads' => env('APPWRITE_LEADS_COLLECTION_ID'),
        'properties' => env('APPWRITE_PROPERTIES_COLLECTION_ID'),
        'projects' => env('APPWRITE_PROJECTS_COLLECTION_ID'),
        'events' => env('APPWRITE_EVENTS_COLLECTION_ID'),
        'sheets' => env('APPWRITE_SHEETS_COLLECTION_ID'),
        'leads_sheets_calls' => env('APPWRITE_LEADS_SHEETS_CALLS_COLLECTION_ID'),
        'filter_settings' => env('APPWRITE_FILTER_SETTINGS_COLLECTION_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Bucket IDs
    |--------------------------------------------------------------------------
    */
    'buckets' => [
        'leads' => env('APPWRITE_LEADS_BUCKET_ID'),
        'properties' => env('APPWRITE_PROPERTIES_BUCKET_ID'),
        'projects' => env('APPWRITE_PROJECTS_BUCKET_ID'),
        'property_videos' => env('APPWRITE_PROPERTIES_VIDEOS_BUCKET_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Settings
    |--------------------------------------------------------------------------
    */
    'migration' => [
        'batch_size' => 50, // Number of records to process at once
        'delay_between_batches' => 1, // Seconds to wait between batches
        'image_download_timeout' => 30, // Seconds to wait for image download
        'max_image_size' => 10 * 1024 * 1024, // 10MB max image size
    ],
];
