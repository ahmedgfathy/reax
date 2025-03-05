<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Lead;
use App\Models\Property;
use App\Models\ReportShare;
use App\Models\ReportSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Services\ReportGenerator;
use App\Jobs\SendReportEmail;
use App\Jobs\SendReportWhatsapp;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::accessibleBy(auth()->user())
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($request->source, function ($query, $source) {
                return $query->where('data_source', $source);
            })
            ->when($request->filter === 'my-reports', function ($query) {
                return $query->where('created_by', auth()->id());
            })
            ->when($request->filter === 'public', function ($query) {
                return $query->where('is_public', true);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        // Get available columns for leads and properties
        $leadColumns = $this->getLeadColumns();
        $propertyColumns = $this->getPropertyColumns();

        return view('reports.create', compact('leadColumns', 'propertyColumns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'data_source' => 'required|in:leads,properties,both',
            'columns' => 'required|array',
            'filters' => 'nullable|array',
            'visualization' => 'nullable|array',
            'is_public' => 'boolean',
            'access_level' => 'required|in:private,team,public',
        ]);

        $report = new Report($validated);
        $report->created_by = auth()->id();
        $report->save();

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Report created successfully.');
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);

        // Get report data using ReportGenerator service
        $generator = new ReportGenerator($report);
        $reportData = $generator->generate();

        // Check if any scheduled distributions exist
        $schedules = $report->schedules;

        return view('reports.show', compact('report', 'reportData', 'schedules'));
    }

    public function edit(Report $report)
    {
        $this->authorize('update', $report);

        $leadColumns = $this->getLeadColumns();
        $propertyColumns = $this->getPropertyColumns();

        return view('reports.edit', compact('report', 'leadColumns', 'propertyColumns'));
    }

    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'data_source' => 'required|in:leads,properties,both',
            'columns' => 'required|array',
            'filters' => 'nullable|array',
            'visualization' => 'nullable|array',
            'is_public' => 'boolean',
            'access_level' => 'required|in:private,team,public',
        ]);

        $report->update($validated);

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Report updated successfully.');
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'data_source' => 'required|in:leads,properties,both',
            'columns' => 'required|array',
            'filters' => 'nullable|array',
            'visualization' => 'nullable|array',
        ]);

        // Create temporary report object
        $tempReport = new Report([
            'data_source' => $validated['data_source'],
            'columns' => $validated['columns'],
            'filters' => $validated['filters'] ?? [],
            'visualization' => $validated['visualization'] ?? ['type' => 'table'],
        ]);

        // Generate report data
        $generator = new ReportGenerator($tempReport);
        $reportData = $generator->generate();

        return response()->json([
            'success' => true,
            'data' => $reportData,
        ]);
    }

    public function export(Report $report, Request $request)
    {
        $this->authorize('view', $report);

        $format = $request->format ?? 'xlsx';
        
        // Generate report data
        $generator = new ReportGenerator($report);
        $reportData = $generator->generate();
        
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($report, $reportData);
            case 'csv':
                return Excel::download(new ReportExport($reportData), Str::slug($report->name) . '.csv', \Maatwebsite\Excel\Excel::CSV);
            case 'xlsx':
            default:
                return Excel::download(new ReportExport($reportData), Str::slug($report->name) . '.xlsx');
        }
    }

    public function share(Report $report, Request $request)
    {
        $this->authorize('share', $report);

        $validated = $request->validate([
            'share_type' => 'required|in:email,whatsapp,pdf,excel',
            'recipient' => 'required_if:share_type,email,whatsapp',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'scheduled' => 'boolean',
            'frequency' => 'required_if:scheduled,1|nullable|in:daily,weekly,monthly',
        ]);

        $share = new ReportShare($validated);
        $share->report_id = $report->id;
        
        // Set next send date if scheduled
        if ($validated['scheduled'] ?? false) {
            $share->next_send_at = $this->calculateNextSendDate($validated['frequency']);
        }
        
        $share->save();

        // Process immediate sending if not scheduled
        if (!($validated['scheduled'] ?? false)) {
            switch ($validated['share_type']) {
                case 'email':
                    SendReportEmail::dispatch($share);
                    break;
                case 'whatsapp':
                    SendReportWhatsapp::dispatch($share);
                    break;
                case 'pdf':
                    return $this->exportToPdf($report);
                case 'excel':
                    return Excel::download(
                        new ReportExport((new ReportGenerator($report))->generate()),
                        Str::slug($report->name) . '.xlsx'
                    );
            }
        }

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Report shared successfully.');
    }

    public function schedule(Report $report, Request $request)
    {
        $this->authorize('share', $report);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'time' => 'required|date_format:H:i',
            'days_of_week' => 'required_if:frequency,weekly|array',
            'day_of_month' => 'required_if:frequency,monthly,quarterly|nullable|numeric|min:1|max:31',
        ]);

        $schedule = new ReportSchedule([
            'report_id' => $report->id,
            'name' => $validated['name'],
            'frequency' => $validated['frequency'],
            'recipients' => $validated['recipients'],
            'time' => $validated['time'],
            'days_of_week' => $validated['days_of_week'] ?? null,
            'day_of_month' => $validated['day_of_month'] ?? null,
        ]);
        
        $schedule->save();

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Report schedule created successfully.');
    }

    private function getLeadColumns()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'status' => 'Status',
            'source' => 'Source',
            'lead_status' => 'Lead Status',
            'lead_source' => 'Lead Source',
            'budget' => 'Budget',
            'created_at' => 'Created Date',
            'updated_at' => 'Last Updated',
            'property_interest' => 'Property Interest',
            'assigned_to' => 'Assigned To',
            'lead_class' => 'Lead Class',
            'type_of_request' => 'Type of Request',
            'last_follow_up' => 'Last Follow Up',
            'agent_follow_up' => 'Agent Follow Up',
        ];
    }

    private function getPropertyColumns()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'unit_for' => 'For (Sale/Rent)',
            'price' => 'Price',
            'area' => 'Area',
            'unit_area' => 'Unit Area',
            'location' => 'Location',
            'rooms' => 'Rooms',
            'bedrooms' => 'Bedrooms',
            'bathrooms' => 'Bathrooms',
            'owner_name' => 'Owner Name',
            'owner_mobile' => 'Owner Mobile',
            'owner_email' => 'Owner Email',
            'is_featured' => 'Featured',
            'status' => 'Status',
            'created_at' => 'Created Date',
            'updated_at' => 'Last Updated',
        ];
    }

    private function calculateNextSendDate($frequency)
    {
        switch ($frequency) {
            case 'daily':
                return now()->addDay();
            case 'weekly':
                return now()->addWeek();
            case 'monthly':
                return now()->addMonth();
            default:
                return now()->addDay();
        }
    }

    private function exportToPdf(Report $report, $reportData = null)
    {
        if (!$reportData) {
            $generator = new ReportGenerator($report);
            $reportData = $generator->generate();
        }

        $pdf = \PDF::loadView('reports.pdf', [
            'report' => $report,
            'reportData' => $reportData
        ]);

        return $pdf->download(Str::slug($report->name) . '.pdf');
    }
}
