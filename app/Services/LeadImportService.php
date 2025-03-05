<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;

class LeadImportService
{
    /**
     * Import leads from a CSV file
     *
     * @param string $filePath Path to the CSV file
     * @param array $options Import options (has_header, etc)
     * @return array Import results (success count, error count, errors)
     */
    public function importFromCsv($filePath, array $options = [])
    {
        // Default options
        $options = array_merge([
            'has_header' => true,
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
        ], $options);

        $results = [
            'success_count' => 0,
            'error_count' => 0,
            'errors' => [],
        ];

        try {
            // Create CSV reader
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setDelimiter($options['delimiter']);
            $csv->setEnclosure($options['enclosure']);
            $csv->setEscape($options['escape']);

            // Determine if there's a header row
            if ($options['has_header']) {
                $csv->setHeaderOffset(0);
            }

            // Get the field mapping
            $fieldMapping = $this->getFieldMapping($csv);
            
            // Begin database transaction
            DB::beginTransaction();
            
            // Process records
            $statement = Statement::create();
            $records = $statement->process($csv);

            foreach ($records as $index => $record) {
                try {
                    // Map CSV fields to database fields
                    $leadData = $this->mapFields($record, $fieldMapping);
                    
                    // Validate the data
                    $validator = Validator::make($leadData, $this->getValidationRules());
                    
                    if ($validator->fails()) {
                        $rowNumber = $options['has_header'] ? $index + 2 : $index + 1;
                        $results['errors'][] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                        $results['error_count']++;
                        continue;
                    }
                    
                    // Create the lead
                    Lead::create($leadData);
                    $results['success_count']++;
                } catch (\Exception $e) {
                    $rowNumber = $options['has_header'] ? $index + 2 : $index + 1;
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                    $results['error_count']++;
                    Log::error('Lead import error', [
                        'row' => $index,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            // Commit the transaction if everything is successful
            DB::commit();
            
        } catch (\Exception $e) {
            // Roll back the transaction if something failed
            DB::rollback();
            $results['errors'][] = "Import failed: " . $e->getMessage();
            Log::error('Lead import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return $results;
    }
    
    /**
     * Map CSV fields to database fields
     *
     * @param array $record The CSV record
     * @param array $fieldMapping The field mapping
     * @return array Mapped database fields
     */
    protected function mapFields(array $record, array $fieldMapping)
    {
        $result = [];
        
        foreach ($fieldMapping as $csvField => $dbField) {
            if ($dbField && isset($record[$csvField])) {
                // Apply any necessary transformations based on field type
                $value = $record[$csvField];
                
                switch ($dbField) {
                    case 'budget':
                        // Convert currency/numeric fields
                        $value = preg_replace('/[^0-9.]/', '', $value);
                        $value = $value !== '' ? (float) $value : null;
                        break;
                    
                    case 'email':
                        // Trim and lowercase emails
                        $value = strtolower(trim($value));
                        break;
                    
                    case 'phone':
                        // Format phone numbers (strip non-numeric except + for intl prefix)
                        $value = preg_replace('/[^0-9+]/', '', $value);
                        break;
                        
                    case 'status':
                        // Convert status to lowercase and map to valid statuses
                        $value = strtolower(trim($value));
                        $validStatuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
                        if (!in_array($value, $validStatuses)) {
                            $value = 'new';  // Default to 'new' if invalid status
                        }
                        break;
                    
                    case 'created_at':
                    case 'updated_at':
                    case 'last_contact_date':
                        // Try to convert date fields
                        try {
                            $value = $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
                        } catch (\Exception $e) {
                            $value = null;
                        }
                        break;
                }
                
                $result[$dbField] = $value !== '' ? $value : null;
            }
        }
        
        // Set defaults for required fields if missing
        if (!isset($result['status'])) {
            $result['status'] = 'new';
        }
        
        // Add created_at timestamp if not provided
        if (!isset($result['created_at'])) {
            $result['created_at'] = now();
        }
        
        return $result;
    }
    
    /**
     * Get field mapping from CSV headers to database fields
     * 
     * @param Reader $csv The CSV reader
     * @return array The field mapping
     */
    protected function getFieldMapping(Reader $csv)
    {
        // Default mapping based on exact column name matches
        $defaultMapping = [
            // Common field variations to database field
            'first name' => 'first_name',
            'firstname' => 'first_name',
            'fname' => 'first_name',
            'first_name' => 'first_name',
            
            'last name' => 'last_name',
            'lastname' => 'last_name',
            'lname' => 'last_name',
            'last_name' => 'last_name',
            
            'full name' => 'full_name', // Special case: will be split into first and last name
            'fullname' => 'full_name',
            'name' => 'full_name',
            
            'email' => 'email',
            'email address' => 'email',
            'e-mail' => 'email',
            'mail' => 'email',
            
            'phone' => 'phone',
            'mobile' => 'phone',
            'phone number' => 'phone',
            'mobile number' => 'phone',
            'telephone' => 'phone',
            'tel' => 'phone',
            'contact' => 'phone',
            
            'budget' => 'budget',
            'price range' => 'budget',
            'price' => 'budget',
            'max budget' => 'budget',
            'max price' => 'budget',
            
            'source' => 'source',
            'lead source' => 'source',
            'referral' => 'source',
            'channel' => 'source',
            
            'status' => 'status',
            'lead status' => 'status',
            'stage' => 'status',
            
            'notes' => 'notes',
            'comments' => 'notes',
            'additional info' => 'notes',
            'additional information' => 'notes',
            'description' => 'notes',
            
            'property interest' => 'property_interest',
            'property type' => 'property_interest',
            'preferred property' => 'property_interest',
            
            'assigned to' => 'assigned_to',
            'agent' => 'assigned_to',
            'assigned agent' => 'assigned_to',
            
            'date' => 'created_at',
            'created' => 'created_at',
            'created at' => 'created_at',
            'date added' => 'created_at',
            'creation date' => 'created_at',
        ];

        $mapping = [];
        
        if ($csv->getHeaderOffset() !== null) {
            $headers = $csv->getHeader();
            
            foreach ($headers as $header) {
                $normalizedHeader = strtolower(trim($header));
                $mapping[$header] = $defaultMapping[$normalizedHeader] ?? null;
            }
        }
        
        return $mapping;
    }
    
    /**
     * Get validation rules for lead data
     * 
     * @return array Validation rules
     */
    protected function getValidationRules()
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'budget' => 'nullable|numeric',
            'source' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'property_interest' => 'nullable|integer',
            'assigned_to' => 'nullable|integer',
            'created_at' => 'nullable|date',
        ];
    }
    
    /**
     * Process special case fields like full_name
     * 
     * @param array $leadData The lead data
     * @return array Updated lead data
     */
    protected function processSpecialFields(array $leadData)
    {
        // Split full name into first and last name if provided
        if (isset($leadData['full_name']) && !empty($leadData['full_name'])) {
            $nameParts = explode(' ', $leadData['full_name'], 2);
            
            if (!isset($leadData['first_name']) || empty($leadData['first_name'])) {
                $leadData['first_name'] = $nameParts[0] ?? '';
            }
            
            if (!isset($leadData['last_name']) || empty($leadData['last_name'])) {
                $leadData['last_name'] = $nameParts[1] ?? '';
            }
            
            // Remove the full_name field as it's not in the database
            unset($leadData['full_name']);
        }
        
        return $leadData;
    }
}
