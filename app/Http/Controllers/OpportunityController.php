<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index()
    {
        // Calculate real statistics
        $stats = [
            'opportunities_count' => Opportunity::count(),
            'won_opportunities' => Opportunity::where('status', 'won')->count(),
            'pipeline_value' => Opportunity::whereIn('status', ['pending', 'negotiation'])
                ->sum('value'),
            'conversion_rate' => Opportunity::count() > 0 
                ? round((Opportunity::where('status', 'won')->count() / Opportunity::count()) * 100)
                : 0,
            'total_value' => Opportunity::sum('value'),
            'pending_opportunities' => Opportunity::where('status', 'pending')->count(),
            'negotiation_opportunities' => Opportunity::where('status', 'negotiation')->count(),
            'lost_opportunities' => Opportunity::where('status', 'lost')->count(),
        ];

        $opportunities = Opportunity::with(['lead', 'property', 'assignedTo'])
            ->when(request('search'), function($query) {
                $query->where('title', 'like', '%' . request('search') . '%');
            })
            ->when(request('stage'), function($query) {
                $query->where('stage', request('stage'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('sort'), function($query) {
                switch(request('sort')) {
                    case 'value_high':
                        $query->orderBy('value', 'desc');
                        break;
                    case 'value_low':
                        $query->orderBy('value', 'asc');
                        break;
                    case 'closing_soon':
                        $query->whereNotNull('expected_close_date')
                            ->orderBy('expected_close_date', 'asc');
                        break;
                    default:
                        $query->latest();
                        break;
                }
            })
            ->latest()
            ->paginate(10);

        return view('opportunities.index', compact('opportunities', 'stats'));
    }

    public function create()
    {
        $leads = Lead::select('id', 'first_name', 'last_name')->get();
        $properties = Property::select('id', 'property_name')->get();
        $users = User::select('id', 'name')->get();
        
        return view('opportunities.create', compact('leads', 'properties', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lead_id' => 'required|exists:leads,id',
            'property_id' => 'nullable|exists:properties,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,negotiation,won,lost',
            'value' => 'nullable|numeric',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'stage' => 'required|in:initial,qualified,proposal,negotiation',
            'priority' => 'required|in:low,medium,high',
        ]);

        $opportunity = Opportunity::create($validated + [
            'company_id' => auth()->user()->company_id,
            'last_modified_by' => auth()->id(),
        ]);

        return redirect()->route('opportunities.show', $opportunity)
            ->with('success', 'Opportunity created successfully.');
    }

    public function show(Opportunity $opportunity)
    {
        $opportunity->load(['lead', 'property', 'assignedTo', 'activities']);
        return view('opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        $leads = Lead::select('id', 'first_name', 'last_name')->get();
        $properties = Property::select('id', 'property_name')->get();
        $users = User::select('id', 'name')->get();
        
        return view('opportunities.edit', compact('opportunity', 'leads', 'properties', 'users'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lead_id' => 'required|exists:leads,id',
            'property_id' => 'nullable|exists:properties,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,negotiation,won,lost',
            'value' => 'nullable|numeric',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'stage' => 'required|in:initial,qualified,proposal,negotiation',
            'priority' => 'required|in:low,medium,high',
        ]);

        $opportunity->update($validated + [
            'last_modified_by' => auth()->id(),
        ]);

        return redirect()->route('opportunities.show', $opportunity)
            ->with('success', 'Opportunity updated successfully.');
    }

    public function destroy(Opportunity $opportunity)
    {
        $title = $opportunity->title;
        $opportunity->delete();

        ActivityLog::log(
            null,
            'deleted_opportunity',
            'Opportunity deleted: ' . $title
        );

        return redirect()->route('opportunities.index')
            ->with('success', __('Opportunity deleted successfully'));
    }
}
