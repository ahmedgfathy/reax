<?php

namespace App\Http\Controllers;

use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;

class LeadImportExportController extends Controller
{
    /**
     * Import leads from file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB limit
        ]);
        
        try {
            $file = $request->file('file');
            
            // Use the LeadsImport class
            $import = new LeadsImport();
            $import->import($file);
            
            // Calculate success and failure counts
            $successCount = count($import->getRows()) - count($import->failures());
            $failureCount = count($import->failures());
            
            // If there were failures, show them
            if ($failureCount > 0) {
                $errors = [];
                foreach ($import->failures() as $failure) {
                    $rowNumber = $failure['row'] ?? '?';
                    $errors[] = "Row {$rowNumber}: " . implode(', ', $failure['errors'] ?? ['Unknown error']);
                }
                
                return redirect()->route('leads.index')
                    ->with('warning', __(':success_count leads imported successfully, :error_count failed.', [
                        'success_count' => $successCount,
                        'error_count' => $failureCount
                    ]))
                    ->with('import_errors', $errors);
            }
            
            return redirect()->route('leads.index')
                ->with('success', __(':count leads imported successfully!', [
                    'count' => $successCount
                ]));
        } catch (\Exception $e) {
            Log::error('Lead import error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('leads.index')
                ->with('error', __('Error importing leads: ') . $e->getMessage());
        }
    }

    /**
     * Export leads to file
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        // Implementation of export functionality will go here
        // For now, return a message indicating feature is coming soon
        return redirect()->route('leads.index')
            ->with('info', __('Export functionality is coming soon.'));
    }
}
