<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\CompanyAccount;
use App\Models\DocumentAudit;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //
        return view('reports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    public function analytics(Request $request): View
    {
        $startDate = $request->input('start_date') ?: now()->subMonth()->format('Y-m-d');
        $endDate = $request->input('end_date') ?: now()->format('Y-m-d');
        $userId = $request->input('user_id');
        $officeId = $request->input('office_id');
    
        $averageTimeToReceive = DocumentWorkflow::selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, received_at)) as avg_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('recipient_id', $userId))
            ->when($officeId, fn($q) => $q->whereHas('recipientOffice', fn($o) => $o->where('office_id', $officeId)))
            ->value('avg_time');
    
        $averageTimeToReview = DocumentWorkflow::selectRaw('AVG(TIMESTAMPDIFF(MINUTE, received_at, updated_at)) as avg_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('recipient_id', $userId))
            ->when($officeId, fn($q) => $q->whereHas('recipientOffice', fn($o) => $o->where('office_id', $officeId)))
            ->value('avg_time');
    
        $averageDocsForwarded = DocumentWorkflow::whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('sender_id', $userId))
            ->count();
    
        $documentsUploaded = Document::whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('user_id', $userId)) 
            ->count();
            
        // Get collections for dropdowns
        $company = CompanyAccount::where('user_id', auth()->id())->first();

        $users = $company ? $company->employees()->paginate(5) : collect();
        $offices = Office::where('company_id', $company->id)->get();
    
        return view('reports.analytics', compact(
            'averageTimeToReceive',
            'averageTimeToReview',
            'averageDocsForwarded',
            'documentsUploaded',
            'startDate',
            'endDate',
            'userId',
            'officeId',
            'users',
            'offices'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $reportType = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Initialize data to avoid undefined variable error
        $data = collect();

        // Generate report based on type
        switch ($reportType) {
            case 'audit_history':
                $data = DocumentAudit::whereBetween('created_at', [$startDate, $endDate])->get();
                break;
            case 'company_performance':
                $data = DocumentWorkflow::whereBetween('created_at', [$startDate, $endDate])->get();
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type selected.');
        }

        // Return view with generated data
        return view('reports.index', compact('data', 'reportType', 'startDate', 'endDate'));
    }
}
