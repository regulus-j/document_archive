<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;    
use App\Models\CompanyAccount;
use App\Models\Subscriptions;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        // Check for admin first
        if (auth()->user()->isAdmin()) {
            return $this->superAdminDashboard();
        }

        // Regular user dashboard logic
        $document = new Document;
        $recentTransactions = $document->transactions()->latest()->paginate(5);
        $totalDocuments = Document::count();
        $recentDocuments = Document::with('user', 'office', 'categories')->latest()->take(5)->get();
        $pendingDocuments = Document::whereHas('status', function ($query) {
            $query->where('status', 'pending');
        })->count();
        $todayDocuments = Document::whereDate('created_at', today())->count();

        return view('dashboard', compact(
            'recentTransactions', 
            'totalDocuments', 
            'recentDocuments', 
            'pendingDocuments', 
            'todayDocuments'
        ));
    }

    private function superAdminDashboard(): View
    {
        $totalCompanies = CompanyAccount::count();
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $activeSubscriptions = Subscriptions::where('status', 'active')->count();
        
        $recentActivities = CompanyAccount::latest()->take(5)->get()->map(function($company) {
            return (object)[
                'company_name' => $company->company_name,
                'action' => 'Company Created',
                'created_at' => $company->created_at
            ];
        });

        return view('admin.dashboard', compact(
            'totalCompanies',
            'totalUsers',
            'totalDocuments',
            'activeSubscriptions',
            'recentActivities'
        ));
    }
}