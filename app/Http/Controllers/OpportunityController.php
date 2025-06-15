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
        // Super Admin sees all opportunities, others see only their company's
        $opportunitiesQuery = Opportunity::query();
        
        if (!auth()->user()->isSuperAdmin()) {
            $opportunitiesQuery->where('company_id', auth()->user()->company_id);
        }

        // Calculate real statistics - Super Admin sees all, others see only company stats
        if (auth()->user()->isSuperAdmin()) {
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
        } else {
            $stats = [
                'opportunities_count' => Opportunity::where('company_id', auth()->user()->company_id)->count(),
                'won_opportunities' => Opportunity::where('company_id', auth()->user()->company_id)->where('status', 'won')->count(),
                'pipeline_value' => Opportunity::where('company_id', auth()->user()->company_id)
                    ->whereIn('status', ['pending', 'negotiation'])
                    ->sum('value'),
                'conversion_rate' => Opportunity::where('company_id', auth()->user()->company_id)->count() > 0 
                    ? round((Opportunity::where('company_id', auth()->user()->company_id)->where('status', 'won')->count() / Opportunity::where('company_id', auth()->user()->company_id)->count()) * 100)
                    : 0,
                'total_value' => Opportunity::where('company_id', auth()->user()->company_id)->sum('value'),
                'pending_opportunities' => Opportunity::where('company_id', auth()->user()->company_id)->where('status', 'pending')->count(),
                'negotiation_opportunities' => Opportunity::where('company_id', auth()->user()->company_id)->where('status', 'negotiation')->count(),
                'lost_opportunities' => Opportunity::where('company_id', auth()->user()->company_id)->where('status', 'lost')->count(),
            ];
        }

        $opportunities = $opportunitiesQuery->with(['lead', 'property', 'assignedTo'])
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
        // Super admin sees all data, others see only their company's data
        if (auth()->user()->isSuperAdmin()) {
            $leads = Lead::select('id', 'first_name', 'last_name')->get();
            $properties = Property::select('id', 'property_name')->get();
            $users = User::select('id', 'name')->get();
        } else {
            $leads = Lead::where('company_id', auth()->user()->company_id)->select('id', 'first_name', 'last_name')->get();
            $properties = Property::where('company_id', auth()->user()->company_id)->select('id', 'property_name')->get();
            $users = User::where('company_id', auth()->user()->company_id)->select('id', 'name')->get();
        }
        
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
        // Authorization check: Super admin can view all opportunities, others can only view their company's opportunities
        if (!auth()->user()->isSuperAdmin() && $opportunity->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to this opportunity.');
        }

        $opportunity->load(['lead', 'property', 'assignedTo', 'activities']);
        return view('opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        // Authorization check: Super admin can edit all opportunities, others can only edit their company's opportunities
        if (!auth()->user()->isSuperAdmin() && $opportunity->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to edit this opportunity.');
        }

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
