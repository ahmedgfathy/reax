<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LeadsImport;
use App\Exports\LeadsExport;

class LeadImportExportController extends Controller
{
    /**
     * Import leads from a CSV or Excel file
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt,xls,xlsx|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->route('leads.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $hasHeader = $request->has('header_row');
            $import = new LeadsImport($hasHeader);
            $importedRows = Excel::import($import, $request->file('file'));
            
            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            
            // Log the import activity - this is a system-level activity, not tied to a specific lead
            ActivityLog::create([
                'user_id' => auth()->id(),
                'lead_id' => null, // Set to null for system-level activities
                'action' => 'imported_leads',
                'description' => "Imported {$successCount} leads from file",
                'details' => [
                    'file_name' => $request->file('file')->getClientOriginalName(),
                    'success_count' => $successCount,
                    'error_count' => $errorCount,
                ]
            ]);
            
            if ($errorCount > 0) {
                return redirect()->route('leads.index')
                    ->with('warning', "Imported {$successCount} leads with {$errorCount} errors. Check the log for details.");
            } else {
                return redirect()->route('leads.index')
                    ->with('success', "Successfully imported {$successCount} leads.");
            }
        } catch (\Exception $e) {
            return redirect()->route('leads.index')
                ->with('error', 'Error importing leads: ' . $e->getMessage());
        }
    }
    
    /**
     * Export leads based on selected format and scope
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        $scope = $request->input('export_scope', 'all');
        
        // Determine which leads to export
        $query = Lead::with(['assignedUser', 'interestedProperty']);
        
        if ($scope === 'filtered') {
            // Apply the same filters as in the index method
            // This assumes the filters are stored in the session
            $filters = session('lead_filters', []);
            
            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];
                $query->where(function($q) use ($searchTerm) {
                    $q->where('first_name', 'like', "%{$searchTerm}%")
                      ->orWhere('last_name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%")
                      ->orWhere('phone', 'like', "%{$searchTerm}%");
                });
            }
            
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            if (!empty($filters['source'])) {
                $query->where('source', $filters['source']);
            }
        } elseif ($scope === 'selected') {
            $selectedLeads = explode(',', $request->input('selected_leads'));
            $query->whereIn('id', $selectedLeads);
        }
        
        // Create filename with timestamp
        $filename = 'leads_export_' . now()->format('Y_m_d_His');
        
        // Log the export activity
        $exportCount = $query->count();
        
        // This is a system-level activity, not tied to a specific lead
        ActivityLog::create([
            'user_id' => auth()->id(),
            'lead_id' => null, // Set to null for system-level activities
            'action' => 'exported_leads',
            'description' => "Exported {$exportCount} leads to {$format}",
            'details' => [
                'format' => $format,
                'scope' => $scope,
                'count' => $exportCount,
            ]
        ]);
        
        // Export to the selected format
        return Excel::download(new LeadsExport($query), $filename . '.' . $format);
    }
}
