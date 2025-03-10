<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdministrationController extends Controller
{
    public function index()
    {
        try {
            // For now, only show employee stats since other models aren't created yet
            $stats = [
                'employees' => [
                    'total' => User::count(),
                    'active' => User::where('is_active', true)->count(),
                    'recent' => User::latest()->take(5)->get()
                ]
            ];

            return view('administration.index', compact('stats'));

        } catch (\Exception $e) {
            \Log::error('Administration Dashboard Error: ' . $e->getMessage());
            return back()->with('error', __('Error loading dashboard data'));
        }
    }
}
