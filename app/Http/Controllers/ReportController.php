<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\CompanyAccount;
use App\Models\DocumentAudit;
use App\Models\Office;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->user, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->date, function ($query, $date) {
                $query->whereDate('generated_at', $date);
            });

        $reports = $query->latest('generated_at')->paginate(10);
        $users = User::all();

        return view('reports.index', compact('reports', 'users'));
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
        return view('reports.show', compact('report'));
    }

    public function download(Report $report, $format = 'pdf')
    {
        $this->authorize('view', $report);

        $filename = Str::slug($report->name) . '_' . $report->generated_at->format('Y-m-d');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf', compact('report'));
            return $pdf->download($filename . '.pdf');
        } elseif ($format === 'word') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText($report->name, ['bold' => true, 'size' => 16]);
            $section->addText('Generated at: ' . $report->generated_at->format('Y-m-d H:i:s'));
            $section->addText('Type: ' . ucfirst($report->type));
            $section->addText('Description: ' . $report->description);
            $section->addText('Report Data:');
            $section->addText(json_encode($report->data, JSON_PRETTY_PRINT));

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            return response()->streamDownload(function () use ($objWriter) {
                $objWriter->save('php://output');
            }, $filename . '.docx');
        }

        return back()->with('error', 'Invalid download format');
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        $report->delete();

        // Log the deletion
        Log::create([
            'action' => 'delete',
            'description' => "Deleted report: {$report->name}",
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Report deleted successfully');
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
            ->when($officeId, fn($q) =>
                $q->whereHas('recipient.offices', fn($o) =>
                    $o->where('offices.id', $officeId)
                )
            )
            ->value('avg_time');

        $averageTimeToReview = DocumentWorkflow::selectRaw('AVG(TIMESTAMPDIFF(MINUTE, received_at, updated_at)) as avg_time')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('recipient_id', $userId))
            ->when($officeId, fn($q) =>
                $q->whereHas('recipient.offices', fn($o) =>
                    $o->where('offices.id', $officeId)
                )
            )
            ->value('avg_time');

        $averageDocsForwarded = DocumentWorkflow::whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('sender_id', $userId))
            ->when($officeId, fn($q) =>
                $q->whereHas('sender.offices', fn($o) =>
                    $o->where('offices.id', $officeId)
                )
            )
            ->count();

        $documentsUploaded = Document::whereBetween('created_at', [$startDate, $endDate])
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->count();

        // Get collections for dropdowns
        $company = CompanyAccount::where('user_id', auth()->id())->first();
        if (!$company) {
            \Log::warning('No company found for user: ' . auth()->id());
            // Handle the case where no company exists
        }

        $users = $company ? $company->employees()->paginate(5) : collect();
        $offices = $company ? Office::where('company_id', $company->id)->get() : collect();

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
        $userId = $request->input('user_id');
        $officeId = $request->input('office_id');

        // Ensure only one of userId or officeId is set
        if ($userId && $officeId) {
            return redirect()->back()->with('error', 'Please select either a user or an office, not both.');
        }

        // Initialize data to avoid undefined variable error
        $data = collect();

        // Generate report based on type
        switch ($reportType) {
            case 'audit_history':
                $data = DocumentAudit::whereBetween('created_at', [$startDate, $endDate])
                    ->when($userId, fn($q) => $q->where('user_id', $userId))
                    ->when($officeId, fn($q) =>
                        $q->whereHas('user.offices', fn($o) =>
                            $o->where('offices.id', $officeId)
                        )
                    )
                    ->get();
                break;
            case 'company_performance':
                $data = DocumentWorkflow::whereBetween('created_at', [$startDate, $endDate])
                    ->when($userId, fn($q) => $q->where('recipient_id', $userId))
                    ->when($officeId, fn($q) =>
                        $q->whereHas('recipient.offices', fn($o) =>
                            $o->where('offices.id', $officeId)
                        )
                    )
                    ->get();
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type selected.');
        }

        // Return view with generated data
        return view('reports.index', compact('data', 'reportType', 'startDate', 'endDate'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:financial,analytics,performance',
            'data' => 'required|json',
        ]);

        $report = Report::create([
            ...$validated,
            'user_id' => auth()->id(),
            'generated_at' => now(),
        ]);

        // Log the creation
        Log::create([
            'action' => 'create',
            'description' => "Created report: {$report->name}",
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('success', 'Report generated successfully');
    }
}