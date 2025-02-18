<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
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