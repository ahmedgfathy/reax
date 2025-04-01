<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        // Get super deals - properties with installment plans
        $superDeals = Property::with(['media', 'company'])
            ->where('is_published', true)
            ->where('has_installments', true)
            ->latest()
            ->take(12)
            ->get();

        $featuredProperties = Property::with(['media', 'company'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        $topAgents = User::withCount('properties')
            ->where('is_active', true)
            ->has('properties')
            ->orderByDesc('properties_count')
            ->take(4)
            ->get();

        // Updated query with error handling
        $topCompanies = Company::query()
            ->when(Schema::hasColumn('properties', 'company_id'), function($query) {
                return $query->withCount('properties')
                            ->has('properties');
            })
            ->where('is_active', true)
            ->orderBy(
                Schema::hasColumn('properties', 'company_id') ? 'properties_count' : 'created_at',
                'desc'
            )
            ->take(4)
            ->get();

        return view('home', compact(
            'superDeals',
            'featuredProperties',
            'topAgents',
            'topCompanies'
        ));
    }
}