<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $document = new Document;
        $recentTransactions = $document->transactions()->latest()->paginate(5);
        $totalDocuments = Document::count();
        $recentDocuments = Document::with('user', 'office', 'categories')->latest()->take(5)->get();
        $pendingDocuments = Document::whereHas('status', function ($query) {
            $query->where('status', 'pending');
        })->count();
        $todayDocuments = Document::whereDate('created_at', today())->count();

        return view('dashboard', compact('recentTransactions', 'totalDocuments', 'recentDocuments', 'pendingDocuments', 'todayDocuments'));
    }
}
