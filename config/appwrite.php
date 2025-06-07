<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Appwrite Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to remote Appwrite database
    |
    */

    'endpoint' => env('APPWRITE_ENDPOINT', 'https://cloud.appwrite.io/v1'),
    'project_id' => env('APPWRITE_PROJECT_ID'),
    'api_key' => env('APPWRITE_API_KEY'),
    
    // Database and Collection IDs
    'database_id' => env('APPWRITE_DATABASE_ID'),
    'collections' => [
        'properties' => env('APPWRITE_PROPERTIES_COLLECTION_ID'),
        'leads' => env('APPWRITE_LEADS_COLLECTION_ID'),
        'users' => env('APPWRITE_USERS_COLLECTION_ID'),
        'projects' => env('APPWRITE_PROJECTS_COLLECTION_ID'),
        'events' => env('APPWRITE_EVENTS_COLLECTION_ID'),  
        'sheets' => env('APPWRITE_SHEETS_COLLECTION_ID'),
    ],
    
    // Storage Bucket IDs
    'buckets' => [
        'properties' => env('APPWRITE_PROPERTIES_BUCKET_ID'),
        'leads' => env('APPWRITE_LEADS_BUCKET_ID'),
        'projects' => env('APPWRITE_PROJECTS_BUCKET_ID'),
        'properties_videos' => env('APPWRITE_PROPERTIES_VIDEOS_BUCKET_ID'),
        'sheets' => env('APPWRITE_SHEETS_BUCKET_ID'),
    ],
];