<?php
// Hello Comment
namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Team;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        
        $stats = [
            'teams_count' => Team::where('company_id', $company->id)->count(),
            'departments_count' => Department::where('company_id', $company->id)->count(),
            'branches_count' => Branch::where('company_id', $company->id)->where('is_active', true)->count(),
            'staff_count' => User::where('company_id', $company->id)->where('is_active', true)->count(),
        ];

        return view('management.index', compact('stats'));
    }
}
