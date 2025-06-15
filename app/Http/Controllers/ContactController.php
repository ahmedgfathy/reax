<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        $stats = [
            'total_contacts' => Contact::count(),
            'active_contacts' => Contact::where('updated_at', '>=', now()->subDays(30))->count(),
            'companies_count' => Company::count(),
            'client_contacts' => Contact::where('type', 'client')->count(),
            'prospect_contacts' => Contact::where('type', 'prospect')->count(),
        ];

        $contacts = Contact::with('company')
            ->when(request('search'), function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . request('search') . '%')
                      ->orWhere('last_name', 'like', '%' . request('search') . '%')
                      ->orWhere('email', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('company_id'), function($query) {
                $query->where('company_id', request('company_id'));
            })
            ->when(request('type'), function($query) {
                $query->where('type', request('type'));
            })
            ->latest()
            ->paginate(10);

        $companies = Company::select('id', 'name')->get();

        return view('contacts.index', compact('contacts', 'stats', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('contacts.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'type' => 'required|in:client,prospect,partner',
            'notes' => 'nullable|string',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully');
    }

    public function show(Contact $contact)
    {
        // Authorization check: Super admin can view all contacts, others can only view their company's contacts
        if (!auth()->user()->isSuperAdmin() && $contact->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to this contact.');
        }

        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        // Authorization check: Super admin can edit all contacts, others can only edit their company's contacts
        if (!auth()->user()->isSuperAdmin() && $contact->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to edit this contact.');
        }

        $companies = Company::all();
        return view('contacts.edit', compact('contact', 'companies'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'type' => 'required|in:client,prospect,partner',
            'notes' => 'nullable|string',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully');
    }
}
