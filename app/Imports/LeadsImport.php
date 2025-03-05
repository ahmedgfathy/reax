<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\User;
use App\Models\Property;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class LeadsImport implements ToModel, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;
    
    protected $hasHeader;
    protected $successCount = 0;
    protected $errorCount = 0;
    
    public function __construct($hasHeader = true)
    {
        $this->hasHeader = $hasHeader;
    }
    
    /**
    * Configure the import to use the header row if present
    */
    public function model(array $row)
    {
        if ($this->hasHeader) {
            $lead = new Lead([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'status' => $row['status'] ?? 'new',
                'source' => $row['source'] ?? null,
                'budget' => $row['budget'] ?? null,
                'notes' => $row['notes'] ?? null,
            ]);
        } else {
            // If no header row, assume a specific order for columns
            $lead = new Lead([
                'first_name' => $row[0],
                'last_name' => $row[1],
                'email' => $row[2] ?? null,
                'phone' => $row[3] ?? null,
                'status' => $row[4] ?? 'new',
                'source' => $row[5] ?? null,
                'budget' => $row[6] ?? null,
                'notes' => $row[7] ?? null,
            ]);
        }
        
        // Handle property interest by name
        if (isset($row['property']) && !empty($row['property'])) {
            $property = Property::where('name', $row['property'])->first();
            if ($property) {
                $lead->property_interest = $property->id;
            }
        }
        
        // Handle assigned user by email
        if (isset($row['assigned_to_email']) && !empty($row['assigned_to_email'])) {
            $user = User::where('email', $row['assigned_to_email'])->first();
            if ($user) {
                $lead->assigned_to = $user->id;
            }
        }
        
        $this->successCount++;
        return $lead;
    }
    
    /**
     * Validation rules for imported data
     */
    public function rules(): array
    {
        return [
            '*.first_name' => 'required|string|max:255',
            '*.last_name' => 'required|string|max:255',
            '*.email' => 'nullable|email|max:255',
            '*.phone' => 'nullable|string|max:20',
            '*.status' => 'nullable|string|in:new,contacted,qualified,proposal,negotiation,won,lost',
            '*.source' => 'nullable|string|max:255',
            '*.budget' => 'nullable|numeric',
            '*.notes' => 'nullable|string',
        ];
    }
    
    /**
     * Count errors
     */
    public function onError(\Throwable $e)
    {
        $this->errorCount++;
        parent::onError($e);
    }
    
    /**
     * Get count of successful imports
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }
    
    /**
     * Get count of errors
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }
    
    /**
     * Configure batch size for better performance
     */
    public function batchSize(): int
    {
        return 500;
    }
    
    /**
     * Configure chunk size for better memory usage
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
