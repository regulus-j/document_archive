<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Office;
use App\Models\DocumentWorkflow;
use App\Models\Document;
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

    public function generate(Report $report)
    {
        //
        return view('reports.index');
    }

    public function analytics(Request $request): View
    {
        $startDate = $request->input('start_date') ?: now()->subMonth()->format('Y-m-d');
        $endDate = $request->input('end_date') ?: now()->format('Y-m-d');
        $userId = $request->input('user_id');
        $officeId = $request->input('office_id');
    
        $averageTimeToReceive = DocumentWorkflow::selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, received_date)) as avg_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('recipient_id', $userId))
            ->when($officeId, fn($q) => $q->whereHas('recipientOffice', fn($o) => $o->where('office_id', $officeId)))
            ->value('avg_time');
    
        $averageTimeToReview = DocumentWorkflow::selectRaw('AVG(TIMESTAMPDIFF(MINUTE, received_date, updated_at)) as avg_time')
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
        $users = User::all();
        $offices = Office::all();
    
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
}
