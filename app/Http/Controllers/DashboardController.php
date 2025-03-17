<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;    
use App\Models\CompanyAccount;
use App\Models\CompanySubscription;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
{
    // Check if the user is an admin
    if (auth()->user()->isAdmin()) {
        return $this->superAdminDashboard();
    }

    $user = auth()->user();
    $userCompany = $user->company()->first();

    // ** Free Trial ---
    $trialEndDate = DB::table('company_users')
        ->where('user_id', $user->id)
        ->value('trial_ends_at');

    // If trial exists and is still active, allow access
    if ($trialEndDate && now()->lessThan($trialEndDate)) {
        $activeSubscription = (object) ['status' => 'trial', 'ends_at' => $trialEndDate]; 
    } else {
        // If no trial, check for a real subscription
        if (!$userCompany) {
            return redirect()->route('plans.select')
                ->with('info', 'Please set up your company profile first.');
        }

        $activeSubscription = CompanySubscription::where('company_id', $userCompany->id)
            ->where('status', 'active')
            ->first();

        if (!$activeSubscription) {
            return redirect()->route('plans.select')
                ->with('info', 'Please select a plan and complete the payment to continue.');
        }
    }
    // Fetch dashboard data
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
        'todayDocuments',
        'activeSubscription'
    ));
}

    private function superAdminDashboard(): View
    {
        $totalCompanies = CompanyAccount::count();
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $activeSubscriptions = CompanySubscription::where('status', 'active')->count();
        
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