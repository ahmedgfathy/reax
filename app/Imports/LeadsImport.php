<?php

namespace App\Imports;

use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeadsImport
{
    protected $rows = [];
    protected $failures = [];
    
    /**
     * Import leads from a file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return void
     */
    public function import($file)
    {
        // Start with debugging info
        Log::info('Starting CSV import', ['file' => $file->getClientOriginalName()]);
        
        // Fallback to manual CSV parsing
        return $this->importCSV($file);
    }
    
    /**
     * Import CSV file manually
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return void
     */
    protected function importCSV($file)
    {
        // Get the file path
        $path = $file->getRealPath();
        
        // Open the file
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \Exception("Cannot open file: $path");
        }
        
        // Try to detect delimiter (auto-detect)
        $sampleData = file_get_contents($path, false, null, 0, 1024);
        rewind($handle);
        
        $delimiters = [',', ';', "\t", '|'];
        $delimiter = ','; // Default delimiter
        $delimiterCounts = [];
        
        foreach ($delimiters as $testDelimiter) {
            $delimiterCounts[$testDelimiter] = substr_count($sampleData, $testDelimiter);
        }
        
        if (max($delimiterCounts) > 0) {
            $delimiter = array_keys($delimiterCounts, max($delimiterCounts))[0];
        }
        
        Log::info('Detected delimiter', ['delimiter' => $delimiter]);
        
        // Read headers
        $headers = fgetcsv($handle, 0, $delimiter);
        if ($headers === false) {
            fclose($handle);
            throw new \Exception("File is empty or not properly formatted");
        }
        
        // Normalize header names
        $headers = array_map([$this, 'normalizeFieldName'], $headers);
        Log::info('CSV headers', ['headers' => $headers]);
        
        // Process rows
        $rowNumber = 1; // Start from 1 because row 0 is header
        $importedCount = 0;
        $errorCount = 0;
        
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;
            
            // Skip empty rows
            if (count(array_filter($data)) === 0) {
                continue;
            }
            
            // Create associative array from row data
            $row = [];
            foreach ($headers as $index => $header) {
                if (isset($data[$index])) {
                    $row[$header] = $data[$index];
                }
            }
            
            $this->rows[] = $row;
            
            try {
                // Map fields from the CSV to your database columns
                $leadData = $this->mapFields($row);
                
                // Validate the data
                $validator = Validator::make($leadData, $this->rules());
                
                if ($validator->fails()) {
                    $this->failures[] = [
                        'row' => $rowNumber,
                        'errors' => $validator->errors()->all()
                    ];
                    $errorCount++;
                    continue;
                }
                
                // Create the lead
                Lead::create($leadData);
                $importedCount++;
                
            } catch (\Exception $e) {
                $this->failures[] = [
                    'row' => $rowNumber,
                    'errors' => [$e->getMessage()]
                ];
                $errorCount++;
                
                Log::error('Lead import error', [
                    'row' => $rowNumber,
                    'data' => $row,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        fclose($handle);
        
        Log::info('Import completed', [
            'imported' => $importedCount,
            'errors' => $errorCount
        ]);
    }
    
    /**
     * Map CSV fields to database fields
     *
     * @param array $row
     * @return array
     */
    protected function mapFields($row)
    {
        // First, log the entire row for debugging
        Log::debug('CSV Row data:', $row);
        
        // Special case handling for common CSV exports from various systems
        // Microsoft Excel/Google Sheets tend to have different formats
        
        // Check if we have any actual content in the row
        if (empty(array_filter($row))) {
            Log::warning('Empty row detected:', $row);
            return [
                'first_name' => 'Imported',
                'last_name' => 'Lead',
                'status' => 'new'
            ];
        }

        // Special case: check if the CSV has numbered headers (1,2,3...) or no real headers
        $hasNumericHeaders = true;
        foreach (array_keys($row) as $header) {
            if (!is_numeric($header) && !preg_match('/^column\d+$/i', $header)) {
                $hasNumericHeaders = false;
                break;
            }
        }

        if ($hasNumericHeaders) {
            Log::info('Numeric headers detected, using positional mapping');
            // For numeric headers, try to detect based on position and content patterns
            $values = array_values($row);
            
            // Log the values
            Log::debug('Values for positional mapping:', $values);
            
            // Try to detect email and phone
            $email = null;
            $phone = null;
            $firstName = null;
            $lastName = null;
            
            foreach ($values as $value) {
                // Skip empty values
                if (empty($value)) continue;
                
                // Email detection
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $email = $value;
                    continue;
                }
                
                // Phone detection - looks for strings with mostly digits
                if (preg_match('/^[+\d\s\(\)\-\.]{5,}$/', $value) && 
                    (strlen(preg_replace('/[^\d]/', '', $value)) >= 5)) {
                    $phone = $value;
                    continue;
                }
                
                // If we haven't found a name yet, this might be it
                if (!$firstName && !is_numeric($value) && strlen($value) < 50) {
                    $nameParts = explode(' ', $value, 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';
                }
            }
            
            // Return a best-guess mapping based on position
            return [
                'first_name' => $firstName ?: 'Imported',
                'last_name' => $lastName ?: 'Lead',
                'email' => $email,
                'phone' => $phone,
                'status' => 'new'
            ];
        }
        
        // If we have actual field names, use our standard mapping
        // ... (rest of your existing mapping code)
        $result = $this->mapWithKnownFields($row);
        
        // If failed to extract meaningful data with known fields, try direct field names
        if (empty($result['first_name']) && empty($result['last_name']) && empty($result['email']) && empty($result['phone'])) {
            Log::warning('Failed to map with known fields, trying direct fields');
            
            // Try direct field matching
            foreach ($row as $key => $value) {
                // Skip empty values
                if (empty($value)) continue;
                
                // Direct field mapping based on what we find in the row
                if (stripos($key, 'name') !== false) {
                    if (empty($result['first_name']) && empty($result['last_name'])) {
                        $nameParts = explode(' ', $value, 2);
                        $result['first_name'] = $nameParts[0] ?? '';
                        $result['last_name'] = $nameParts[1] ?? '';
                    }
                } elseif (stripos($key, 'mail') !== false || filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $result['email'] = $value;
                } elseif (stripos($key, 'phone') !== false || stripos($key, 'mobile') !== false || 
                          stripos($key, 'tel') !== false || preg_match('/^[+\d\s\(\)\-\.]{5,}$/', $value)) {
                    $result['phone'] = $value;
                }
            }
        }
        
        // Ensure we have at least a first name or last name
        if (empty($result['first_name']) && empty($result['last_name'])) {
            Log::warning('No name found, using default');
            $result['first_name'] = 'Imported';
            $result['last_name'] = 'Lead';
        }
        
        // Make sure we have a status
        if (empty($result['status'])) {
            $result['status'] = 'new';
        }
        
        Log::info('Final mapped result:', $result);
        return $result;
    }

    /**
     * Map with our known field patterns
     */
    private function mapWithKnownFields($row)
    {
        // Create a more flexible field mapping system with multiple possible column names
        $fieldMappings = [
            'first_name' => ['first_name', 'firstname', 'fname', 'first', 'name_first', 'first name', 'forename', 'givenname', 'given name'],
            'last_name' => ['last_name', 'lastname', 'lname', 'last', 'name_last', 'surname', 'last name', 'familyname', 'family name'],
            'email' => ['email', 'email_address', 'e_mail', 'mail', 'email_id', 'e-mail', 'email address', 'emailaddress'],
            'phone' => ['phone', 'mobile', 'telephone', 'tel', 'contact', 'contact_number', 'phone_number', 'mobile_number', 'cell', 'cellphone'],
            'status' => ['status', 'lead_status', 'state', 'stage', 'lead state', 'leadstatus'],
            'source' => ['source', 'lead_source', 'referral', 'channel', 'platform', 'lead source', 'leadsource'],
            'budget' => ['budget', 'price', 'max_budget', 'price_range', 'amount', 'value'],
            'notes' => ['notes', 'comments', 'description', 'additional_info', 'additional_information', 'remarks', 'comment']
        ];
        
        // Map the fields using our mapping system
        $result = [];
        
        foreach ($fieldMappings as $dbField => $possibleFieldNames) {
            $result[$dbField] = null;
            
            // Try each possible field name
            foreach ($possibleFieldNames as $fieldName) {
                $normalizedFieldName = $this->normalizeFieldName($fieldName);
                
                // Debug log to see what we're looking for
                if ($dbField == 'first_name') {
                    Log::debug("Looking for '$normalizedFieldName' in keys: " . implode(", ", array_keys($row)));
                }
                
                // Try exact match first
                if (isset($row[$normalizedFieldName]) && !empty($row[$normalizedFieldName])) {
                    $result[$dbField] = $row[$normalizedFieldName];
                    break;
                }
                
                // Try case-insensitive match (important for CSV exports)
                foreach ($row as $key => $value) {
                    if (strtolower($key) === $normalizedFieldName && !empty($value)) {
                        $result[$dbField] = $value;
                        break 2;
                    }
                }
                
                // Try partial match - this is useful for CSV files with prefix/suffix in column names
                foreach ($row as $key => $value) {
                    if (strpos(strtolower($key), $normalizedFieldName) !== false && !empty($value)) {
                        $result[$dbField] = $value;
                        break 2; // Break both loops
                    }
                }
            }
        }
        
        // Process full name if first/last name are missing
        $fullNameFields = ['full_name', 'fullname', 'name', 'full', 'complete_name', 'completename'];
        
        if (empty($result['first_name']) && empty($result['last_name'])) {
            foreach ($fullNameFields as $fieldName) {
                $normalizedFieldName = $this->normalizeFieldName($fieldName);
                
                // Try exact match first
                if (isset($row[$normalizedFieldName]) && !empty($row[$normalizedFieldName])) {
                    $fullName = $row[$normalizedFieldName];
                    $nameParts = explode(' ', $fullName, 2);
                    
                    $result['first_name'] = $nameParts[0] ?? '';
                    $result['last_name'] = $nameParts[1] ?? '';
                    break;
                }
                
                // Try case-insensitive and partial match
                foreach ($row as $key => $value) {
                    if ((strtolower($key) === $normalizedFieldName || 
                        strpos(strtolower($key), $normalizedFieldName) !== false) && 
                        !empty($value)) {
                        
                        $fullName = $value;
                        $nameParts = explode(' ', $fullName, 2);
                        
                        $result['first_name'] = $nameParts[0] ?? '';
                        $result['last_name'] = $nameParts[1] ?? '';
                        break 2;
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Normalize field name (convert to lowercase, replace spaces/special chars with underscores)
     */
    protected function normalizeFieldName($fieldName)
    {
        // Convert to lowercase
        $fieldName = strtolower($fieldName);
        
        // Replace spaces and other separators with underscores
        $fieldName = preg_replace('/[\s\-\.\/\\\\]+/', '_', $fieldName);
        
        // Remove any remaining non-alphanumeric characters
        $fieldName = preg_replace('/[^a-z0-9_]/', '', $fieldName);
        
        return $fieldName;
    }
    
    /**
     * Map status from import value to valid system value
     */
    protected function mapStatus($status)
    {
        $status = strtolower(trim($status));
        $validStatuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
        
        if (empty($status) || !in_array($status, $validStatuses)) {
            return 'new'; // Default to 'new' if empty or invalid
        }
        
        return $status;
    }
    
    /**
     * Parse numeric values from various formats
     */
    protected function parseNumber($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Remove any non-numeric characters except decimal point
        $value = preg_replace('/[^0-9.]/', '', $value);
        
        // If the result is empty or not numeric, return null
        if (empty($value) || !is_numeric($value)) {
            return null;
        }
        
        return (float) $value;
    }
    
    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'budget' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ];
    }
    
    /**
     * Get all rows
     */
    public function getRows()
    {
        return $this->rows;
    }
    
    /**
     * Get all failures
     */
    public function failures()
    {
        return $this->failures;
    }
    
    /**
     * Get success count - number of successfully imported leads
     * 
     * @return int
     */
    public function getSuccessCount()
    {
        return count($this->rows) - count($this->failures);
    }
}
