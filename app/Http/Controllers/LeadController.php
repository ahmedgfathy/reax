<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Property;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LeadController extends Controller
{
    /**
     * Display a listing of the leads with search and filters.
     */
    public function index(Request $request)
    {
        $query = Lead::with(['assignedUser', 'interestedProperty']);
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Source filter
        if ($request->has('source') && $request->source != '') {
            $query->where('source', $request->source);
        }
        
        // Order by
        $orderBy = $request->order_by ?? 'created_at';
        $orderDirection = $request->order_direction ?? 'desc';
        $query->orderBy($orderBy, $orderDirection);
        
        // Determine number of leads per page
        $perPage = $request->per_page ? (int) $request->per_page : 25;
        // Ensure per_page is one of the allowed values
        if (!in_array($perPage, [25, 50, 100])) {
            $perPage = 25;
        }
        
        // Get paginated results
        $leads = $query->paginate($perPage)->withQueryString();
        
        // Get unique sources for the filter dropdown
        $sources = Lead::distinct()->pluck('source')->filter()->values();
        
        // Get all users for the transfer dropdown
        $users = User::all();
        
        // Store the filters in session for export functionality
        $filters = $request->only(['search', 'status', 'source', 'order_by', 'order_direction']);
        session(['lead_filters' => $filters]);

        // Calculate stats
        $stats = [
            'active' => Lead::whereIn('status', ['new', 'contacted', 'qualified', 'negotiation'])->count(),
            'won' => Lead::where('status', 'won')->count(),
            'pipeline_value' => Lead::whereIn('status', ['new', 'contacted', 'qualified', 'negotiation'])
                               ->sum('budget'),
        ];
        
        return view('leads.index', compact('leads', 'sources', 'users', 'perPage', 'stats'));
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create()
    {
        $users = User::all();
        $properties = Property::all();
        return view('leads.create', compact('users', 'properties'));
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'status' => 'required|string',
            'lead_status' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'lead_source' => 'nullable|string|max:255',
            'property_interest' => 'nullable|exists:properties,id',
            'budget' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'last_follow_up' => 'nullable|date',
            'agent_follow_up' => 'nullable|boolean',
            'lead_class' => 'nullable|string|max:50',
            'type_of_request' => 'nullable|string|max:100',
        ]);

        // Set the last_modified_by to the current user
        $validatedData['last_modified_by'] = auth()->id();

        $lead = Lead::create($validatedData);
        
        // Log this activity
        ActivityLog::log(
            $lead->id, 
            'created_lead', 
            'Lead created: ' . $lead->first_name . ' ' . $lead->last_name
        );

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified lead.
     */
    public function show(Lead $lead)
    {
        // Load relationships
        $lead->load(['assignedUser', 'interestedProperty', 'events' => function($q) {
            $q->orderBy('event_date', 'desc');
        }, 'activityLogs.user']);
        
        // Get users for assigning events
        $users = User::all();
        
        return view('leads.show', compact('lead', 'users'));
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Lead $lead)
    {
        $users = User::all();
        $properties = Property::all();
        return view('leads.edit', compact('lead', 'users', 'properties'));
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'status' => 'required|string',
            'lead_status' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'lead_source' => 'nullable|string|max:255',
            'property_interest' => 'nullable|exists:properties,id',
            'budget' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'last_follow_up' => 'nullable|date',
            'agent_follow_up' => 'nullable|boolean',
            'lead_class' => 'nullable|string|max:50',
            'type_of_request' => 'nullable|string|max:100',
        ]);
        
        // Set the last_modified_by to the current user
        $validatedData['last_modified_by'] = auth()->id();
        
        // Track changes for activity log with more detailed information
        $changes = [];
        $changeDescription = [];
        
        foreach ($validatedData as $key => $value) {
            $oldValue = $lead->{$key};
            
            if ($oldValue !== $value) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $value
                ];
                
                // Create human-readable change descriptions
                switch ($key) {
                    case 'first_name':
                        $changeDescription[] = "First name changed from '{$oldValue}' to '{$value}'";
                        break;
                    case 'last_name':
                        $changeDescription[] = "Last name changed from '{$oldValue}' to '{$value}'";
                        break;
                    case 'email':
                        $oldDisplay = $oldValue ?: 'none';
                        $newDisplay = $value ?: 'none';
                        $changeDescription[] = "Email changed from '{$oldDisplay}' to '{$newDisplay}'";
                        break;
                    case 'phone':
                        $oldDisplay = $oldValue ?: 'none';
                        $newDisplay = $value ?: 'none';
                        $changeDescription[] = "Phone changed from '{$oldDisplay}' to '{$newDisplay}'";
                        break;
                    case 'status':
                        $changeDescription[] = "Status changed from '{$oldValue}' to '{$value}'";
                        break;
                    case 'source':
                        $oldDisplay = $oldValue ?: 'none';
                        $newDisplay = $value ?: 'none';
                        $changeDescription[] = "Source changed from '{$oldDisplay}' to '{$newDisplay}'";
                        break;
                    case 'property_interest':
                        $oldProperty = $oldValue ? Property::find($oldValue)?->name ?? 'Unknown' : 'None';
                        $newProperty = $value ? Property::find($value)?->name ?? 'Unknown' : 'None';
                        $changeDescription[] = "Interested property changed from '{$oldProperty}' to '{$newProperty}'";
                        break;
                    case 'budget':
                        $oldBudget = $oldValue ? number_format($oldValue, 2) : 'Not specified';
                        $newBudget = $value ? number_format($value, 2) : 'Not specified';
                        $changeDescription[] = "Budget changed from '{$oldBudget}' to '{$newBudget}'";
                        break;
                    case 'notes':
                        if (empty($oldValue) && !empty($value)) {
                            $changeDescription[] = "Notes were added";
                        } elseif (!empty($oldValue) && empty($value)) {
                            $changeDescription[] = "Notes were removed";
                        } else {
                            $changeDescription[] = "Notes were updated";
                        }
                        break;
                    case 'assigned_to':
                        $oldUser = $oldValue ? User::find($oldValue)?->name ?? 'Unknown' : 'Not assigned';
                        $newUser = $value ? User::find($value)?->name ?? 'Unknown' : 'Not assigned';
                        $changeDescription[] = "Assigned user changed from '{$oldUser}' to '{$newUser}'";
                        break;
                }
            }
        }

        // Update the lead with validated data
        $lead->update($validatedData);
        
        // Log this activity if there are changes with detailed information
        if (!empty($changes)) {
            // Log a summary entry
            ActivityLog::log(
                $lead->id, 
                'updated_lead', 
                'Lead updated: ' . implode(', ', $changeDescription),
                ['changes' => $changes]
            );
            
            // Log individual field changes for more detailed history
            foreach ($changes as $field => $change) {
                $fieldDescription = '';
                
                switch ($field) {
                    case 'property_interest':
                        $oldProperty = $change['old'] ? Property::find($change['old'])?->name ?? 'Unknown' : 'None';
                        $newProperty = $change['new'] ? Property::find($change['new'])?->name ?? 'Unknown' : 'None';
                        $fieldDescription = "Changed interested property from '{$oldProperty}' to '{$newProperty}'";
                        break;
                    case 'assigned_to':
                        $oldUser = $change['old'] ? User::find($change['old'])?->name ?? 'Unknown' : 'Not assigned';
                        $newUser = $change['new'] ? User::find($change['new'])?->name ?? 'Unknown' : 'Not assigned';
                        $fieldDescription = "Changed assigned user from '{$oldUser}' to '{$newUser}'";
                        break;
                    default:
                        $oldValue = $change['old'] ? $change['old'] : 'empty';
                        $newValue = $change['new'] ? $change['new'] : 'empty';
                        $fieldDescription = "Changed {$field} from '{$oldValue}' to '{$newValue}'";
                }
                
                ActivityLog::log(
                    $lead->id,
                    'updated_field_' . $field,
                    $fieldDescription,
                    ['field' => $field, 'old_value' => $change['old'], 'new_value' => $change['new']]
                );
            }
        }

        return redirect()->route('leads.show', $lead->id)->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead)
    {
        $leadName = $lead->first_name . ' ' . $lead->last_name;
        $leadId = $lead->id;
        
        $lead->delete();
        
        // Since the lead is deleted, we can't associate the log with it
        // but we'll log it for audit purposes with user ID only
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_lead',
            'description' => 'Lead deleted: ' . $leadName,
            'details' => ['lead_id' => $leadId]
        ]);

        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
    
    /**
     * Generate a printable view of the lead
     */
    public function print(Lead $lead)
    {
        $lead->load(['assignedUser', 'interestedProperty', 'events', 'activityLogs.user']);
        
        return view('leads.print', compact('lead'));
    }
    
    /**
     * Add a note to a lead
     */
    public function addNote(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'note' => 'required|string'
        ]);
        
        // Append to existing notes or create new
        $notes = $lead->notes ? $lead->notes . "\n\n" . $validated['note'] : $validated['note'];
        $lead->update(['notes' => $notes]);
        
        // Log this activity
        ActivityLog::log(
            $lead->id, 
            'added_note', 
            'Added a note to lead',
            ['note' => $validated['note']]
        );
        
        return redirect()->back()->with('success', 'Note added successfully.');
    }

    /**
     * Process bulk actions on leads (transfer or delete)
     */
    public function bulkAction(Request $request)
    {
        // Add extensive logging for debugging
        \Log::debug('Bulk action request received', [
            'all_data' => $request->all(),
            'action' => $request->input('action'),
            'selected_leads' => $request->input('selected_leads')
        ]);
        
        try {
            $validated = $request->validate([
                'action' => 'required|in:transfer,delete',
                'selected_leads' => 'required|array',
                'selected_leads.*' => 'exists:leads,id',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $selectedLeads = $validated['selected_leads'];
            $count = count($selectedLeads);
            
            \Log::info('Bulk action validated', [
                'action' => $validated['action'],
                'count' => $count
            ]);
            
            if ($validated['action'] === 'transfer') {
                // Make sure we have a user to transfer to
                if (empty($validated['assigned_to'])) {
                    return back()->with('error', 'Please select a user to transfer leads to.');
                }
                
                $user = User::find($validated['assigned_to']);
                
                // Update all selected leads
                Lead::whereIn('id', $selectedLeads)->update(['assigned_to' => $validated['assigned_to']]);
                
                // Log each transfer
                foreach ($selectedLeads as $leadId) {
                    $lead = Lead::find($leadId);
                    
                    if ($lead) {
                        ActivityLog::log(
                            $leadId,
                            'transferred_lead',
                            "Lead transferred to {$user->name}",
                            [
                                'transferred_to' => $validated['assigned_to'],
                                'transferred_by' => auth()->id(),
                                'user_name' => $user->name
                            ]
                        );
                    }
                }
                
                return redirect()->route('leads.index')->with('success', $count . ' ' . __('leads transferred successfully to') . ' ' . $user->name);
            }
            
            if ($validated['action'] === 'delete') {
                try {
                    // Use DB transaction for data integrity
                    \DB::beginTransaction();
                    
                    // Delete leads in chunks for better performance
                    foreach (array_chunk($selectedLeads, 100) as $chunk) {
                        Lead::whereIn('id', $chunk)->delete();
                    }
                    
                    \DB::commit();
                    
                    // Log the action
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'bulk_deleted_leads',
                        'description' => "Bulk deleted {$count} leads",
                    ]);
                    
                    return redirect()->route('leads.index')
                        ->with('success', __(':count leads deleted successfully', ['count' => $count]));
                        
                } catch (\Exception $e) {
                    \DB::rollBack();
                    \Log::error('Bulk delete error', ['error' => $e->getMessage()]);
                    
                    return redirect()->route('leads.index')
                        ->with('error', __('Error deleting leads: :message', ['message' => $e->getMessage()]));
                }
            }
            
            return redirect()->route('leads.index')
                ->with('error', __('Invalid action specified'));
            
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error in bulk action', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->route('leads.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Step 1: Upload and analyze the CSV file
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB limit
        ]);
        
        try {
            $file = $request->file('file');
            
            // Store the file temporarily
            $tempPath = $file->storeAs('temp/imports', 'leads_import_' . time() . '.csv');
            
            // Analyze the file and show mapping interface
            return $this->analyzeImportFile($tempPath);
            
        } catch (\Exception $e) {
            return redirect()->route('leads.index')
                ->with('error', __('Error analyzing import file: ') . $e->getMessage());
        }
    }

    /**
     * Analyze import file and show mapping interface
     */
    private function analyzeImportFile($tempFile)
    {
        try {
            $fullPath = storage_path('app/' . $tempFile);
            
            // Read the first few lines of the CSV file
            $handle = fopen($fullPath, 'r');
            if (!$handle) {
                throw new \Exception('Could not open file: ' . $tempFile);
            }
            
            // Detect delimiter
            $sampleContent = file_get_contents($fullPath, false, null, 0, 1024);
            $delimiters = [',', ';', "\t", '|'];
            $delimiterCounts = [];
            
            foreach ($delimiters as $delimiter) {
                $delimiterCounts[$delimiter] = substr_count($sampleContent, $delimiter);
            }
            
            $delimiter = array_keys($delimiterCounts, max($delimiterCounts))[0];
            
            // Get headers
            $headers = fgetcsv($handle, 0, $delimiter);
            if (!$headers) {
                throw new \Exception('Could not read headers from CSV file');
            }
            
            // Get sample data (first row after header)
            $sampleData = fgetcsv($handle, 0, $delimiter);
            fclose($handle);
            
            // Suggest mappings based on header names
            $suggestedMapping = $this->suggestFieldMappings($headers);
            
            // Debug log
            \Log::debug('CSV Analysis', [
                'file' => $tempFile,
                'delimiter' => $delimiter,
                'headers' => $headers,
                'sample_data' => $sampleData,
                'suggested_mapping' => $suggestedMapping
            ]);
            
            return view('leads.map-import-fields', [
                'tempFile' => $tempFile,
                'headers' => $headers,
                'sampleData' => $sampleData,
                'suggestedMapping' => $suggestedMapping,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error analyzing import file', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('leads.index')
                ->with('error', __('Error analyzing file: ') . $e->getMessage());
        }
    }

    /**
     * Suggest field mappings based on header names
     */
    private function suggestFieldMappings($headers)
    {
        $fieldPatterns = [
            'first_name' => ['first_name', 'firstname', 'fname', 'first', 'name_first', 'first name'],
            'last_name' => ['last_name', 'lastname', 'lname', 'last', 'name_last', 'surname', 'last name'],
            'email' => ['email', 'email_address', 'e_mail', 'mail', 'email_id', 'e-mail', 'email address'],
            'phone' => ['phone', 'mobile', 'telephone', 'tel', 'contact', 'contact_number', 'phone_number', 'mobile_number'],
            'status' => ['status', 'lead_status', 'state', 'stage', 'lead state'],
            'source' => ['source', 'lead_source', 'referral', 'channel', 'platform', 'lead source'],
            'budget' => ['budget', 'price', 'max_budget', 'price_range', 'amount', 'value'],
            'notes' => ['notes', 'comments', 'description', 'additional_info', 'additional_information', 'remarks', 'comment'],
            'property_interest' => ['property', 'property_interest', 'property_id', 'property interest', 'interested in']
        ];
        
        $mappings = [];
        
        foreach ($headers as $index => $header) {
            $normalized = strtolower(trim(str_replace([' ', '_', '-'], '', $header)));
            $mapping = '';
            
            foreach ($fieldPatterns as $field => $patterns) {
                foreach ($patterns as $pattern) {
                    $normalizedPattern = strtolower(str_replace([' ', '_', '-'], '', $pattern));
                    if ($normalized === $normalizedPattern || strpos($normalized, $normalizedPattern) !== false) {
                        $mapping = $field;
                        break 2;
                    }
                }
            }
            
            // Special case for full name
            if (!$mapping && preg_match('/(full)?name/', $normalized)) {
                $mapping = 'first_name'; // We'll split it later
            }
            
            $mappings[$index] = $mapping;
        }
        
        return $mappings;
    }

    /**
     * Step 2: Process the import with mapped fields
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'temp_file' => 'required|string',
            'field_mapping' => 'required|array',
        ]);
        
        try {
            $tempFile = $request->input('temp_file');
            $fieldMapping = $request->input('field_mapping');
            
            $fullPath = storage_path('app/' . $tempFile);
            if (!file_exists($fullPath)) {
                throw new \Exception('Import file not found');
            }
            
            // Process the import with the user-defined field mapping
            $results = $this->processImportWithMapping($fullPath, $fieldMapping);
            
            // Delete the temporary file
            unlink($fullPath);
            
            // Redirect with results
            return redirect()->route('leads.index')
                ->with('success', __(':success_count leads imported successfully!', [
                    'success_count' => $results['success_count'],
                ]));
            
        } catch (\Exception $e) {
            return redirect()->route('leads.index')
                ->with('error', __('Error importing leads: ') . $e->getMessage());
        }
    }

    /**
     * Process import with the field mapping provided by the user
     */
    private function processImportWithMapping($filePath, $fieldMapping)
    {
        // Detect delimiter
        $sampleContent = file_get_contents($filePath, false, null, 0, 1024);
        $delimiters = [',', ';', "\t", '|'];
        $delimiterCounts = [];
        
        foreach ($delimiters as $delimiter) {
            $delimiterCounts[$delimiter] = substr_count($sampleContent, $delimiter);
        }
        
        $delimiter = array_keys($delimiterCounts, max($delimiterCounts))[0];
        
        // Open file
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception('Could not open file');
        }
        
        // Skip header row
        fgetcsv($handle, 0, $delimiter);
        
        $results = [
            'success_count' => 0,
            'error_count' => 0,
            'errors' => [],
        ];
        
        // Begin transaction
        \DB::beginTransaction();
        
        try {
            $rowNumber = 1;
            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }
                
                try {
                    $leadData = [];
                    
                    // Map the data according to user-defined mapping
                    foreach ($fieldMapping as $index => $field) {
                        if (!empty($field) && isset($data[$index])) {
                            // Special case for full name field marked as first_name
                            if ($field === 'first_name' && strpos($data[$index], ' ') !== false) {
                                $nameParts = explode(' ', $data[$index], 2);
                                $leadData['first_name'] = $nameParts[0] ?? '';
                                $leadData['last_name'] = $nameParts[1] ?? '';
                            } else {
                                $leadData[$field] = $data[$index];
                            }
                        }
                    }
                    
                    // IMPORTANT FIX: Handle cases where only last_name is provided
                    if (empty($leadData['first_name']) && !empty($leadData['last_name'])) {
                        // Extract first name from last name if it contains spaces
                        if (strpos($leadData['last_name'], ' ') !== false) {
                            $nameParts = explode(' ', $leadData['last_name'], 2);
                            $leadData['first_name'] = $nameParts[0];
                            $leadData['last_name'] = $nameParts[1];
                        } else {
                            // If last_name has no spaces, use it as first_name too
                            $leadData['first_name'] = $leadData['last_name'];
                        }
                    }
                    
                    // If we still don't have a first_name, set a default one
                    if (empty($leadData['first_name'])) {
                        $leadData['first_name'] = 'Imported';
                    }
                    
                    // If we don't have a last_name, set a default one
                    if (empty($leadData['last_name'])) {
                        $leadData['last_name'] = 'Lead';
                    }
                    
                    // Apply transformations and validations for other fields
                    if (isset($leadData['budget'])) {
                        $leadData['budget'] = preg_replace('/[^0-9.]/', '', $leadData['budget']);
                        $leadData['budget'] = !empty($leadData['budget']) ? (float)$leadData['budget'] : null;
                    }
                    
                    if (isset($leadData['email'])) {
                        $leadData['email'] = strtolower(trim($leadData['email']));
                        if (!filter_var($leadData['email'], FILTER_VALIDATE_EMAIL)) {
                            $leadData['email'] = null;
                        }
                    }
                    
                    // Ensure required fields have defaults
                    if (empty($leadData['status'])) {
                        $leadData['status'] = 'new';
                    }
                    
                    // Create the lead
                    \App\Models\Lead::create($leadData);
                    $results['success_count']++;
                    
                } catch (\Exception $e) {
                    $results['error_count']++;
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                    // Log the exact data that caused the error
                    \Log::error("Import error at row {$rowNumber}", [
                        'error' => $e->getMessage(),
                        'data' => $data,
                        'mapped_data' => $leadData ?? [],
                        'mapping' => $fieldMapping
                    ]);
                }
            }
            
            fclose($handle);
            \DB::commit();
            
            return $results;
            
        } catch (\Exception $e) {
            \DB::rollBack();
            fclose($handle);
            throw $e;
        }
    }
}