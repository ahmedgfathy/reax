<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AppwriteService
{
    protected $endpoint;
    protected $projectId;
    protected $databaseId;
    protected $apiKey;

    public function __construct()
    {
        $this->endpoint = config('appwrite.endpoint');
        $this->projectId = config('appwrite.project_id');
        $this->databaseId = config('appwrite.database_id');
        $this->apiKey = config('appwrite.api_key');
    }

    /**
     * Get documents from a collection
     */
    public function getDocuments($collectionId, $queries = [], $limit = 100, $offset = 0)
    {
        try {
            $url = "{$this->endpoint}/databases/{$this->databaseId}/collections/{$collectionId}/documents";
            
            $params = [
                'limit' => $limit,
                'offset' => $offset,
            ];

            if (!empty($queries)) {
                $params['queries'] = $queries;
            }

            $response = Http::withHeaders([
                'X-Appwrite-Project' => $this->projectId,
                'X-Appwrite-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Appwrite API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Appwrite Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all documents from a collection (paginated)
     */
    public function getAllDocuments($collectionId, $queries = [])
    {
        $allDocuments = [];
        $limit = 100;
        $offset = 0;
        $hasMore = true;

        while ($hasMore) {
            $response = $this->getDocuments($collectionId, $queries, $limit, $offset);
            
            if (!$response || !isset($response['documents'])) {
                break;
            }

            $documents = $response['documents'];
            $allDocuments = array_merge($allDocuments, $documents);

            // Check if there are more documents
            $hasMore = count($documents) === $limit;
            $offset += $limit;

            // Add a small delay to avoid rate limiting
            usleep(100000); // 0.1 seconds
        }

        return $allDocuments;
    }

    /**
     * Download a file from Appwrite storage
     */
    public function downloadFile($bucketId, $fileId, $localPath = null)
    {
        try {
            $url = "{$this->endpoint}/storage/buckets/{$bucketId}/files/{$fileId}/view";
            
            $response = Http::withHeaders([
                'X-Appwrite-Project' => $this->projectId,
                'X-Appwrite-Key' => $this->apiKey,
            ])->timeout(config('appwrite.migration.image_download_timeout', 30))
              ->get($url);

            if ($response->successful()) {
                $content = $response->body();
                
                // Check file size
                if (strlen($content) > config('appwrite.migration.max_image_size', 10485760)) {
                    Log::warning("File {$fileId} is too large, skipping");
                    return null;
                }

                if ($localPath) {
                    Storage::disk('public')->put($localPath, $content);
                    return $localPath;
                }

                return $content;
            }

            Log::error("Failed to download file {$fileId}: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Error downloading file {$fileId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get file information
     */
    public function getFile($bucketId, $fileId)
    {
        try {
            $url = "{$this->endpoint}/storage/buckets/{$bucketId}/files/{$fileId}";
            
            $response = Http::withHeaders([
                'X-Appwrite-Project' => $this->projectId,
                'X-Appwrite-Key' => $this->apiKey,
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error getting file info {$fileId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the public URL for a file
     */
    public function getFileUrl($bucketId, $fileId)
    {
        return "{$this->endpoint}/storage/buckets/{$bucketId}/files/{$fileId}/view?project={$this->projectId}";
    }

    /**
     * Test connection to Appwrite
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'X-Appwrite-Project' => $this->projectId,
                'X-Appwrite-Key' => $this->apiKey,
            ])->get("{$this->endpoint}/databases/{$this->databaseId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Appwrite connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get collection schema
     */
    public function getCollectionSchema($collectionId)
    {
        try {
            $url = "{$this->endpoint}/databases/{$this->databaseId}/collections/{$collectionId}";
            
            $response = Http::withHeaders([
                'X-Appwrite-Project' => $this->projectId,
                'X-Appwrite-Key' => $this->apiKey,
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error getting collection schema {$collectionId}: " . $e->getMessage());
            return null;
        }
    }
}
