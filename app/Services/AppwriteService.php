<?php

namespace App\Services;

use Appwrite\Client;
use Appwrite\Services\Databases;
use Appwrite\Services\Storage;
use Appwrite\Query;
use Illuminate\Support\Facades\Log;
use Exception;

class AppwriteService
{
    private $client;
    private $databases;
    private $storage;
    
    public function __construct()
    {
        // Only initialize if all required Appwrite config values are present
        $projectId = config('appwrite.project_id');
        $apiKey = config('appwrite.api_key');
        $endpoint = config('appwrite.endpoint');
        
        if ($projectId && $apiKey && $endpoint) {
            $this->client = new Client();
            $this->client
                ->setEndpoint($endpoint)
                ->setProject($projectId)
                ->setKey($apiKey);
                
            $this->databases = new Databases($this->client);
            $this->storage = new Storage($this->client);
        } else {
            // Log warning but don't fail - useful for build processes
            Log::warning('Appwrite configuration incomplete. Service will not be functional.');
        }
    }
    
    /**
     * Check if the service is properly configured
     */
    private function isConfigured(): bool
    {
        return $this->client !== null && $this->databases !== null;
    }
    
    /**
     * Get all properties from Appwrite
     */
    public function getAllProperties($limit = 100, $offset = 0)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Returning empty result.');
            return ['documents' => [], 'total' => 0];
        }
        
        try {
            $response = $this->databases->listDocuments(
                config('appwrite.database_id'),
                config('appwrite.collections.properties'),
                [
                    Query::limit($limit),
                    Query::offset($offset),
                    Query::orderDesc('$createdAt')
                ]
            );
            
            return [
                'documents' => $response['documents'],
                'total' => $response['total']
            ];
        } catch (Exception $e) {
            Log::error('Error fetching properties from Appwrite: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get all leads from Appwrite
     */
    public function getAllLeads($limit = 100, $offset = 0)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Returning empty result.');
            return ['documents' => [], 'total' => 0];
        }
        
        try {
            $response = $this->databases->listDocuments(
                config('appwrite.database_id'),
                config('appwrite.collections.leads'),
                [
                    Query::limit($limit),
                    Query::offset($offset),
                    Query::orderDesc('$createdAt')
                ]
            );
            
            return [
                'documents' => $response['documents'],
                'total' => $response['total']
            ];
        } catch (Exception $e) {
            Log::error('Error fetching leads from Appwrite: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get all users from Appwrite
     */
    public function getAllUsers($limit = 100, $offset = 0)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Returning empty result.');
            return ['documents' => [], 'total' => 0];
        }
        
        try {
            $response = $this->databases->listDocuments(
                config('appwrite.database_id'),
                config('appwrite.collections.users'),
                [
                    Query::limit($limit),
                    Query::offset($offset),
                    Query::orderDesc('$createdAt')
                ]
            );
            
            return [
                'documents' => $response['documents'],
                'total' => $response['total']
            ];
        } catch (Exception $e) {
            Log::error('Error fetching users from Appwrite: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get all projects from Appwrite
     */
    public function getAllProjects($limit = 100, $offset = 0)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Returning empty result.');
            return ['documents' => [], 'total' => 0];
        }
        
        try {
            $response = $this->databases->listDocuments(
                config('appwrite.database_id'),
                config('appwrite.collections.projects'),
                [
                    Query::limit($limit),
                    Query::offset($offset),
                    Query::orderDesc('$createdAt')
                ]
            );
            
            return [
                'documents' => $response['documents'],
                'total' => $response['total']
            ];
        } catch (Exception $e) {
            Log::error('Error fetching projects from Appwrite: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get all events from Appwrite
     */
    public function getAllEvents($limit = 100, $offset = 0)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Returning empty result.');
            return ['documents' => [], 'total' => 0];
        }
        
        try {
            $response = $this->databases->listDocuments(
                config('appwrite.database_id'),
                config('appwrite.collections.events'),
                [
                    Query::limit($limit),
                    Query::offset($offset),
                    Query::orderDesc('$createdAt')
                ]
            );
            
            return [
                'documents' => $response['documents'],
                'total' => $response['total']
            ];
        } catch (Exception $e) {
            Log::error('Error fetching events from Appwrite: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Download file from Appwrite storage
     */
    public function downloadFile($bucketId, $fileId)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Cannot download file.');
            return null;
        }
        
        try {
            return $this->storage->getFileDownload($bucketId, $fileId);
        } catch (Exception $e) {
            Log::error("Error downloading file {$fileId} from bucket {$bucketId}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get file view URL from Appwrite storage
     */
    public function getFileViewUrl($bucketId, $fileId)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Cannot get file view URL.');
            return null;
        }
        
        try {
            return $this->storage->getFileView($bucketId, $fileId);
        } catch (Exception $e) {
            Log::error("Error getting file view URL for {$fileId} from bucket {$bucketId}: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get file metadata
     */
    public function getFileMetadata($bucketId, $fileId)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Cannot get file metadata.');
            return null;
        }
        
        try {
            return $this->storage->getFile($bucketId, $fileId);
        } catch (Exception $e) {
            Log::error("Error getting file metadata for {$fileId} from bucket {$bucketId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a single property by ID from Appwrite
     */
    public function getProperty($propertyId)
    {
        if (!$this->isConfigured()) {
            Log::warning('Appwrite service not configured. Cannot get property.');
            return null;
        }
        
        try {
            $response = $this->databases->getDocument(
                config('appwrite.database_id'),
                config('appwrite.collections.properties'),
                $propertyId
            );
            
            return $response;
        } catch (Exception $e) {
            Log::error("Error fetching property {$propertyId} from Appwrite: " . $e->getMessage());
            return null;
        }
    }
}