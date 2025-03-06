<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportGenerator
{
    protected $report;
    
    public function __construct(Report $report)
    {
        $this->report = $report;
    }
    
    public function generate()
    {
        switch ($this->report->data_source) {
            case 'leads':
                return $this->generateLeadsReport();
            case 'properties':
                return $this->generatePropertiesReport();
            case 'both':
                return $this->generateCombinedReport();
            default:
                throw new \InvalidArgumentException('Invalid data source specified');
        }
    }
    
    protected function generateLeadsReport()
    {
        $columns = $this->report->columns;
        
        $query = Lead::query();
        
        // Apply filters if any
        if (!empty($this->report->filters)) {
            $query = $this->applyLeadFilters($query, $this->report->filters);
        }
        
        // Include relationships if needed
        if (in_array('assigned_to', $columns) || in_array('assignedUser.name', $columns)) {
            $query->with('assignedUser');
        }
        
        if (in_array('property_interest', $columns) || in_array('interestedProperty.name', $columns)) {
            $query->with('interestedProperty');
        }
        
        // Get the data
        $leads = $query->get();
        
        // Format the data according to visualization needs
        $data = $this->formatLeadsData($leads, $columns);
        
        return [
            'columns' => $columns,
            'data' => $data,
            'visualization' => $this->report->visualization ?? ['type' => 'table'],
            'summary' => $this->generateLeadsSummary($leads),
        ];
    }
    
    protected function generatePropertiesReport()
    {
        $columns = $this->report->columns;
        
        $query = Property::query();
        
        // Apply filters if any
        if (!empty($this->report->filters)) {
            $query = $this->applyPropertyFilters($query, $this->report->filters);
        }
        
        // Get the data
        $properties = $query->get();
        
        // Format the data according to visualization needs
        $data = $this->formatPropertiesData($properties, $columns);
        
        return [
            'columns' => $columns,
            'data' => $data,
            'visualization' => $this->report->visualization ?? ['type' => 'table'],
            'summary' => $this->generatePropertiesSummary($properties),
        ];
    }
    
    protected function generateCombinedReport()
    {
        // This is a more complex report combining leads and properties data
        // For example, leads per property, conversion rates, etc.
        
        $leadsData = $this->generateLeadsReport();
        $propertiesData = $this->generatePropertiesReport();
        
        // Generate combined report based on the visualization type
        $visualization = $this->report->visualization ?? ['type' => 'table'];
        
        switch ($visualization['type']) {
            case 'comparison':
                return $this->generateComparisonReport($leadsData, $propertiesData);
            case 'correlation':
                return $this->generateCorrelationReport($leadsData, $propertiesData);
            default:
                // For simple table display, just return both datasets
                return [
                    'leads' => $leadsData,
                    'properties' => $propertiesData,
                    'visualization' => $visualization,
                ];
        }
    }
    
    protected function applyLeadFilters(Builder $query, array $filters)
    {
        foreach ($filters as $filter) {
            $field = $filter['field'] ?? null;
            $operator = $filter['operator'] ?? '=';
            $value = $filter['value'] ?? null;
            
            if ($field && $value !== null) {
                // Handle special operators
                switch ($operator) {
                    case 'contains':
                        $query->where($field, 'like', '%' . $value . '%');
                        break;
                    case 'starts_with':
                        $query->where($field, 'like', $value . '%');
                        break;
                    case 'ends_with':
                        $query->where($field, 'like', '%' . $value);
                        break;
                    case 'in':
                        $query->whereIn($field, is_array($value) ? $value : explode(',', $value));
                        break;
                    case 'not_in':
                        $query->whereNotIn($field, is_array($value) ? $value : explode(',', $value));
                        break;
                    case 'between':
                        if (is_array($value) && count($value) >= 2) {
                            $query->whereBetween($field, [$value[0], $value[1]]);
                        }
                        break;
                    case 'date_range':
                        if (is_array($value) && count($value) >= 2) {
                            $query->whereDate($field, '>=', $value[0])
                                 ->whereDate($field, '<=', $value[1]);
                        }
                        break;
                    default:
                        $query->where($field, $operator, $value);
                        break;
                }
            }
        }
        
        return $query;
    }
    
    protected function applyPropertyFilters(Builder $query, array $filters)
    {
        // Similar to applyLeadFilters but for properties
        return $this->applyLeadFilters($query, $filters); // Reuse the same logic for now
    }
    
    protected function formatLeadsData($leads, array $columns)
    {
        return $leads->map(function ($lead) use ($columns) {
            $row = [];
            
            foreach ($columns as $column) {
                switch ($column) {
                    case 'assigned_to':
                        $row[$column] = $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned';
                        break;
                    case 'property_interest':
                        $row[$column] = $lead->interestedProperty ? $lead->interestedProperty->name : 'None';
                        break;
                    case 'created_at':
                    case 'updated_at':
                    case 'last_follow_up':
                        $row[$column] = $lead->{$column} ? $lead->{$column}->format('Y-m-d H:i') : '';
                        break;
                    case 'budget':
                        $row[$column] = $lead->{$column} ? number_format($lead->{$column}, 2) : '0.00';
                        break;
                    case 'agent_follow_up':
                        $row[$column] = $lead->{$column} ? 'Yes' : 'No';
                        break;
                    default:
                        $row[$column] = $lead->{$column} ?? '';
                        break;
                }
            }
            
            return $row;
        })->toArray();
    }
    
    protected function formatPropertiesData($properties, array $columns)
    {
        return $properties->map(function ($property) use ($columns) {
            $row = [];
            
            foreach ($columns as $column) {
                switch ($column) {
                    case 'created_at':
                    case 'updated_at':
                        $row[$column] = $property->{$column} ? $property->{$column}->format('Y-m-d H:i') : '';
                        break;
                    case 'price':
                        $row[$column] = $property->{$column} ? number_format($property->{$column}, 2) : '0.00';
                        break;
                    case 'is_featured':
                        $row[$column] = $property->{$column} ? 'Yes' : 'No';
                        break;
                    default:
                        $row[$column] = $property->{$column} ?? '';
                        break;
                }
            }
            
            return $row;
        })->toArray();
    }
    
    protected function generateLeadsSummary($leads)
    {
        return [
            'total_count' => $leads->count(),
            'status_distribution' => $leads->groupBy('status')
                ->map(fn($items) => $items->count())
                ->toArray(),
            'source_distribution' => $leads->groupBy('source')
                ->map(fn($items) => $items->count())
                ->toArray(),
            'average_budget' => $leads->avg('budget'),
            'total_budget' => $leads->sum('budget'),
            'oldest_lead' => $leads->min('created_at'),
            'newest_lead' => $leads->max('created_at'),
        ];
    }
    
    protected function generatePropertiesSummary($properties)
    {
        return [
            'total_count' => $properties->count(),
            'type_distribution' => $properties->groupBy('type')
                ->map(fn($items) => $items->count())
                ->toArray(),
            'unit_for_distribution' => $properties->groupBy('unit_for')
                ->map(fn($items) => $items->count())
                ->toArray(),
            'average_price' => $properties->avg('price'),
            'total_value' => $properties->sum('price'),
            'average_area' => $properties->avg('unit_area'),
            'featured_count' => $properties->where('is_featured', true)->count(),
        ];
    }
    
    protected function generateComparisonReport($leadsData, $propertiesData)
    {
        // Generate a report comparing leads and properties data
        // For example: leads per property type, etc.
        return [
            'leads' => $leadsData,
            'properties' => $propertiesData,
            'comparison' => [
                'leads_per_property_type' => $this->calculateLeadsPerPropertyType(),
                'average_budget_per_property_type' => $this->calculateAvgBudgetPerPropertyType(),
            ],
            'visualization' => $this->report->visualization,
        ];
    }
    
    protected function generateCorrelationReport($leadsData, $propertiesData)
    {
        // Generate a report showing correlations between leads and properties
        // For example: conversion rates, etc.
        return [
            'leads' => $leadsData,
            'properties' => $propertiesData,
            'correlation' => [
                'interest_to_property_ratio' => $this->calculateInterestToPropertyRatio(),
                'budget_to_price_correlation' => $this->calculateBudgetToPriceCorrelation(),
            ],
            'visualization' => $this->report->visualization,
        ];
    }
    
    protected function calculateLeadsPerPropertyType()
    {
        // Example calculation of leads per property type
        return DB::table('leads')
            ->join('properties', 'leads.property_interest', '=', 'properties.id')
            ->groupBy('properties.type')
            ->select('properties.type', DB::raw('count(leads.id) as lead_count'))
            ->get()
            ->pluck('lead_count', 'type')
            ->toArray();
    }
    
    protected function calculateAvgBudgetPerPropertyType()
    {
        // Example calculation of average budget per property type
        return DB::table('leads')
            ->join('properties', 'leads.property_interest', '=', 'properties.id')
            ->groupBy('properties.type')
            ->select('properties.type', DB::raw('avg(leads.budget) as avg_budget'))
            ->get()
            ->pluck('avg_budget', 'type')
            ->toArray();
    }
    
    protected function calculateInterestToPropertyRatio()
    {
        // Example calculation of interest-to-property ratio
        $propertyTypeCount = DB::table('properties')
            ->groupBy('type')
            ->select('type', DB::raw('count(*) as count'))
            ->get()
            ->pluck('count', 'type')
            ->toArray();
        
        $leadInterestCount = $this->calculateLeadsPerPropertyType();
        
        $ratio = [];
        foreach ($propertyTypeCount as $type => $count) {
            if ($count > 0 && isset($leadInterestCount[$type])) {
                $ratio[$type] = $leadInterestCount[$type] / $count;
            } else {
                $ratio[$type] = 0;
            }
        }
        
        return $ratio;
    }
    
    protected function calculateBudgetToPriceCorrelation()
    {
        // Example calculation of correlation between lead budgets and property prices
        $propertyPrices = DB::table('properties')
            ->groupBy('type')
            ->select('type', DB::raw('avg(price) as avg_price'))
            ->get()
            ->pluck('avg_price', 'type')
            ->toArray();
        
        $leadBudgets = $this->calculateAvgBudgetPerPropertyType();
        
        $correlation = [];
        foreach ($propertyPrices as $type => $avgPrice) {
            if ($avgPrice > 0 && isset($leadBudgets[$type]) && $leadBudgets[$type] > 0) {
                $correlation[$type] = [
                    'avg_property_price' => $avgPrice,
                    'avg_lead_budget' => $leadBudgets[$type],
                    'ratio' => $leadBudgets[$type] / $avgPrice,
                ];
            }
        }
        
        return $correlation;
    }
}