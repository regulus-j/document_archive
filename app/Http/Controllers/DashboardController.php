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
        
        // First check if user is a super-admin and redirect accordingly
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        $userCompany = $user->companies()->first();
        
        // If user is a company-admin and has an active subscription, redirect to company dashboard
        if ($user->hasRole('company-admin')) {
            $hasActiveSubscription = false;

            // Check for active subscription or trial
            $trialEndDate = DB::table('company_users')
                ->where('user_id', $user->id)
                ->value('trial_ends_at');
            
            if (($trialEndDate && now()->lessThan($trialEndDate)) || 
                ($userCompany && CompanySubscription::active()->where('company_id', $userCompany->id)->exists())) {
                return redirect()->route('reports.company-dashboard');
            }
        }
        
        // Check if user is an office lead
        $isOfficeLead = Office::where('office_lead', $user->id)->exists();
        $ledOffice = null;
        $officeMembers = collect();
        $officeDocuments = collect();
        $officeDocumentCount = 0;
        $officeDocumentsTodayCount = 0;
        $officePendingWorkflowsCount = 0;
        
        if ($isOfficeLead) {
            $ledOffice = Office::where('office_lead', $user->id)->first();
            if ($ledOffice) {
                // Get office members
                $officeMembers = $ledOffice->users()->get();
                
                // Get office document statistics - Using the correct column (from_office) instead of office_id
                $officeDocuments = Document::whereIn('uploader', $officeMembers->pluck('id'))
                    ->orWhereHas('transaction', function($query) use ($ledOffice) {
                        $query->where('from_office', $ledOffice->id);
                    })
                    ->with('user', 'categories')
                    ->latest()
                    ->take(10)
                    ->get();
                    
                $officeDocumentCount = Document::whereIn('uploader', $officeMembers->pluck('id'))
                    ->orWhereHas('transaction', function($query) use ($ledOffice) {
                        $query->where('from_office', $ledOffice->id);
                    })
                    ->count();
                    
                $officeDocumentsTodayCount = Document::whereIn('uploader', $officeMembers->pluck('id'))
                    ->orWhereHas('transaction', function($query) use ($ledOffice) {
                        $query->where('from_office', $ledOffice->id);
                    })
                    ->whereDate('created_at', today())
                    ->count();
                    
                $officePendingWorkflowsCount = DocumentWorkflow::whereIn('sender_id', $officeMembers->pluck('id'))
                    ->orWhereIn('recipient_id', $officeMembers->pluck('id'))
                    ->where('status', 'pending')
                    ->count();
            }
        }

        // Set up subscription information for view
        $activeSubscription = null;
        $needsSubscription = false;
        
        // ** Free Trial Check **
        $trialEndDate = DB::table('company_users')
            ->where('user_id', $user->id)
            ->value('trial_ends_at');

        if ($trialEndDate && now()->lessThan($trialEndDate)) {
            $activeSubscription = (object) ['status' => 'trial', 'ends_at' => $trialEndDate];
        } else if ($userCompany) {
            $activeSubscription = CompanySubscription::active()
                ->where('company_id', $userCompany->id)
                ->first();
                
            // Set flag for showing subscription banner to company admins
            if (!$activeSubscription && $user->hasRole('company-admin')) {
                $needsSubscription = true;
            }
        }

        // Initialize variables for dashboard views instead of redirecting
        if (!$userCompany) {
            // Initialize all variables needed by dashboard-office-user.blade.php
            $totalDocuments = 0;
            $recentDocuments = collect();
            $pendingDocuments = 0;
            $todayDocuments = 0;
            $countPendingDocs = 0;
            $countRecentDocs = 0;
            $countCompanyUsers = 0;
            $incomingDocuments = 0; 
            $countOffices = "No Offices Found";
            
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
                'isOfficeLead',
                'needsSubscription'
            ))->with('info', 'Please set up your company profile first.');
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
            ->whereIn('document_id', function ($query) use ($userCompany, $user) {
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
            ->whereNotIn('document_id', function ($query) use ($user) {
                $query->select('document_id')
                    ->from('document_workflows')
                    ->where('sender_id', $user->id);
            })
            ->count();

        // For company admins show the admin dashboard
        if ($user->hasRole('company-admin')) {
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
                'totalDocuments',
                'isOfficeLead',
                'ledOffice',
                'officeMembers',
                'officeDocuments',
                'officeDocumentCount',
                'officeDocumentsTodayCount',
                'officePendingWorkflowsCount',
                'needsSubscription'
            ));
        } 
        
        // For regular company users, use the office user dashboard
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
            'isOfficeLead',
            'ledOffice',
            'officeMembers',
            'officeDocuments',
            'officeDocumentCount',
            'officeDocumentsTodayCount',
            'officePendingWorkflowsCount',
            'needsSubscription'
        ));
    }
}