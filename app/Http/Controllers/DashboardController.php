<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;    
use App\Models\CompanyAccount;
use App\Models\CompanySubscription;
use App\Models\DocumentWorkflow;
use App\Models\Office;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $userCompany = auth()->user()->companies()->first();
    
    // Check if user is a super admin
    if ($user->isAdmin()) {
        return $this->superAdminDashboard();
    }

    // ** Free Trial Check **
    $trialEndDate = DB::table('company_users')
        ->where('user_id', $user->id)
        ->value('trial_ends_at');

    if ($trialEndDate && now()->lessThan($trialEndDate)) {
        $activeSubscription = (object) ['status' => 'trial', 'ends_at' => $trialEndDate];
    } else {
        if (!$userCompany) {
            return view('dashboard-office-user')
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
    $incomingDocuments = DocumentWorkflow::where('recipient_id', $user->id)
        ->whereIn('status', ['pending', 'appeal_requested'])
        ->count();

    // Ensure userCompany is not null before accessing employees
    $countCompanyUsers = $userCompany ? $userCompany->employees()->count() : 0;

    $document = new Document;
    $recentTransactions = $document->transactions()->latest()->paginate(5);

    $totalDocuments = Document::whereIn(
        'uploader',
        $userCompany ? $userCompany->employees->pluck('id')->push($user->id)->unique() : collect()
    )->count();

    $recentDocuments = Document::with('user', 'office', 'categories')->latest()->take(5)->get();

    $pendingDocuments = DocumentWorkflow::where('status', 'pending')
        ->whereIn('document_id', function($query) use ($userCompany, $user) {
            $query->select('id')
                ->from('documents')
                ->whereIn('uploader', $userCompany ? $userCompany->employees->pluck('id')->push($user->id)->unique() : collect());
        })
        ->count();

    $todayDocuments = Document::whereDate('created_at', today())->count();
    $countPendingDocs = $pendingDocuments;
    $countRecentDocs = $recentDocuments->count();
    $countOffices = $userCompany ? Office::where('company_id', $userCompany->id)->count() : "No Offices Found";

    $processedDocuments = DocumentWorkflow::where('recipient_id', $user->id)
        ->whereIn('status', ['approved', 'rejected'])
        ->whereNotIn('document_id', function($query) use ($user) {
            $query->select('document_id')
                ->from('document_workflows')
                ->where('sender_id', $user->id);
        })
        ->count();
    
        // **🚀 Correct Role Check for Admin**
        if ($user->hasRole('Admin') && $userCompany) {
            return view('dashboard', compact(
                'recentTransactions',
                'totalDocuments',
                'recentDocuments',
                'pendingDocuments',
                'todayDocuments',
                'activeSubscription',
                'countPendingDocs',
                'countRecentDocs',
                'countCompanyUsers',
                'incomingDocuments',
                'countOffices',
            ));
        } else {
            return view('dashboard-office-user', compact(
                'totalDocuments',
                'recentDocuments',
                'pendingDocuments',
                'todayDocuments',
                'activeSubscription',
                'countPendingDocs',
                'countRecentDocs',
                'countCompanyUsers',
                'incomingDocuments',
                'countOffices',
            ));
        }
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