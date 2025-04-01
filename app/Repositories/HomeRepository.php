<?php

namespace App\Repositories;

use App\Models\Property;
use App\Models\User;
use App\Models\Company;

class HomeRepository
{
    public function getFeaturedProperties($limit = 4)
    {
        return Property::with(['mediaFiles', 'company'])
            ->where('is_featured', true)
            ->where('is_published', true)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getSuperDealProperties($limit = 12)
    {
        return Property::with(['mediaFiles', 'company'])
            ->where('has_installments', true)
            ->where('is_published', true)
            ->orderBy('price', 'asc')
            ->take($limit)
            ->get();
    }

    public function getTopAgents($limit = 4)
    {
        return User::withCount('properties')
            ->where('is_agent', true)
            ->where('is_active', true)
            ->orderBy('properties_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopCompanies($limit = 4)
    {
        return Company::withCount('properties')
            ->where('is_active', true)
            ->orderBy('properties_count', 'desc')
            ->take($limit)
            ->get();
    }
}
