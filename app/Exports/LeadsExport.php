<?php

namespace App\Exports;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromQuery, WithMapping, WithHeadings, WithStyles
{
    protected $query;
    
    public function __construct($query = null)
    {
        $this->query = $query ?? Lead::query();
    }
    
    /**
     * Return the query that should be used for the export
     */
    public function query()
    {
        return $this->query->with(['assignedUser', 'interestedProperty']);
    }
    
    /**
     * Define the headings for the export
     */
    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Status',
            'Source',
            'Property Interest',
            'Budget',
            'Assigned To',
            'Notes',
            'Created At',
            'Updated At',
        ];
    }
    
    /**
     * Map each lead to a row in the export
     */
    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->first_name,
            $lead->last_name,
            $lead->email,
            $lead->phone,
            $lead->status,
            $lead->source,
            $lead->interestedProperty ? $lead->interestedProperty->name : null,
            $lead->budget,
            $lead->assignedUser ? $lead->assignedUser->name : null,
            $lead->notes,
            $lead->created_at->format('Y-m-d H:i:s'),
            $lead->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * Style the export
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}
