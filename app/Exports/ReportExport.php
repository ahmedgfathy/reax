<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ReportExport implements FromCollection, WithHeadings
{
    protected $reportData;
    
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }
    
    /**
     * @return Collection
     */
    public function collection()
    {
        // Determine the format based on the report's structure
        if (isset($this->reportData['data'])) {
            // Simple report format
            return new Collection($this->reportData['data']);
        } elseif (isset($this->reportData['leads']) && isset($this->reportData['properties'])) {
            // Combined report format - create a proper export structure
            $data = [];
            
            // Add a section header for leads
            $data[] = ['LEADS DATA'];
            $data[] = []; // Empty row as separator
            
            // Add leads data with headers
            if (!empty($this->reportData['leads']['data'])) {
                $data[] = array_keys(reset($this->reportData['leads']['data']));
                foreach ($this->reportData['leads']['data'] as $lead) {
                    $data[] = $lead;
                }
            }
            
            // Add a separator
            $data[] = [];
            $data[] = ['PROPERTIES DATA'];
            $data[] = []; // Empty row as separator
            
            // Add properties data with headers
            if (!empty($this->reportData['properties']['data'])) {
                $data[] = array_keys(reset($this->reportData['properties']['data']));
                foreach ($this->reportData['properties']['data'] as $property) {
                    $data[] = $property;
                }
            }
            
            return new Collection($data);
        } else {
            // Unknown format, return empty collection
            return new Collection([['No data available']]);
        }
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        // Determine headings based on the report's structure
        if (isset($this->reportData['columns'])) {
            // Use defined columns
            return $this->reportData['columns'];
        } elseif (isset($this->reportData['data']) && !empty($this->reportData['data'])) {
            // Use keys from the first data row
            return array_keys(reset($this->reportData['data']));
        } else {
            // No data, return empty headings
            return [];
        }
    }
}
