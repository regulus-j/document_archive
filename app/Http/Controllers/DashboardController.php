<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;    
use App\Models\CompanyAccount;
use App\Models\Subscriptions;
use App\Models\Company;
use Illuminate\View\View;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Check for superadmin first
        if (auth()->user()->isSuperAdmin()) {
            return $this->superAdminDashboard();
        }

        // Check for admin
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        // Regular user dashboard logic
        return $this->userDashboard();
    }

    public function superAdminDashboard(): View
    {
        $totalCompanies = Company::count();
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

        return view('superadmin.dashboard', compact(
            'totalCompanies',
            'totalUsers',
            'totalDocuments',
            'activeSubscriptions',
            'recentActivities'
        ));
    }

    public function adminDashboard(): View
    {
        $company = auth()->user()->company;
        $totalUsers = $company->users()->count();
        $totalDocuments = Document::where('company_id', $company->id)->count();

        return view('admin.dashboard', compact('totalUsers', 'totalDocuments'));
    }

    public function userDashboard(): View
    {
        $user = auth()->user();

        $activeSubscription = $user->companySubscriptions()->where('status', 'active')->first();
        $notActiveSubscription = $user->companySubscriptions()->where('status', 'not_active')->first();

        // Allow access to the dashboard even if no plan is selected
        if (!$activeSubscription && !$notActiveSubscription) {
            $notActiveSubscription = (object) [
                'status' => 'not_active',
                'plan' => 'Free Trial'
            ];
        }

        $document = new Document;
        $recentTransactions = $document->transactions()->latest()->paginate(5);
        $totalDocuments = Document::count();
        $recentDocuments = Document::with('user', 'office', 'categories')->latest()->take(5)->get();
        $pendingDocuments = Document::whereHas('status', function ($query) {
            $query->where('status', 'pending');
        })->count();
        $todayDocuments = Document::whereDate('created_at', today())->count();

        // Update the query to correctly reference the company_id column
        $totalUsers = User::where('company_id', $user->company_id)->count();
        $totalCompanies = Company::count();

        return view('dashboard', compact('user', 'recentTransactions', 'totalDocuments', 'recentDocuments', 'pendingDocuments', 'todayDocuments', 'activeSubscription', 'notActiveSubscription', 'totalUsers', 'totalCompanies'));
    }

    public function officeDashboard()
{
    $totalDocuments = Document::count(); 
    return view('office.dashboard', compact('totalDocuments'));// Ensure this Blade file exists
}

}