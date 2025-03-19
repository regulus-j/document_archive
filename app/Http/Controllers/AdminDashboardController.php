<?php

namespace App\Http\Controllers;

use App\Models\CompanyAccount;
use App\Models\Document;
use App\Models\CompanySubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with real data.
     */
    public function index()
    {
        // Get counts for dashboard stats
        $totalCompanies = CompanyAccount::count();
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $activeSubscriptions = CompanySubscription::where('status', 'active')->count();

        // Calculate growth rates compared to last month
        $lastMonth = Carbon::now()->subMonth();
        
        $companiesLastMonth = CompanyAccount::where('created_at', '<', $lastMonth)->count();
        $usersLastMonth = User::where('created_at', '<', $lastMonth)->count();
        $documentsLastMonth = Document::where('created_at', '<', $lastMonth)->count();
        $subscriptionsLastMonth = CompanySubscription::where('status', 'active')
            ->where('created_at', '<', $lastMonth)
            ->count();

        // Calculate growth percentages
        $companyGrowth = $companiesLastMonth > 0 
            ? round((($totalCompanies - $companiesLastMonth) / $companiesLastMonth) * 100, 1) 
            : 0;
        
        $userGrowth = $usersLastMonth > 0 
            ? round((($totalUsers - $usersLastMonth) / $usersLastMonth) * 100, 1) 
            : 0;
        
        $documentGrowth = $documentsLastMonth > 0 
            ? round((($totalDocuments - $documentsLastMonth) / $documentsLastMonth) * 100, 1) 
            : 0;
        
        $subscriptionGrowth = $subscriptionsLastMonth > 0 
            ? round((($activeSubscriptions - $subscriptionsLastMonth) / $subscriptionsLastMonth) * 100, 1) 
            : 0;

        // Get today's stats
        $today = Carbon::today();
        $newUsersToday = User::whereDate('created_at', $today)->count();
        $newDocumentsToday = Document::whereDate('created_at', $today)->count();

        // Calculate target for daily progress (based on average of last 30 days)
        $last30DaysUsers = User::whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->count();
        $last30DaysDocs = Document::whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->count();

        $dailyUserTarget = ceil($last30DaysUsers / 30) * 2; // Double the average for an aspirational target
        $dailyDocTarget = ceil($last30DaysDocs / 30) * 2;

        // Calculate progress percentages
        $userProgress = $dailyUserTarget > 0 ? min(100, round(($newUsersToday / $dailyUserTarget) * 100)) : 0;
        $docProgress = $dailyDocTarget > 0 ? min(100, round(($newDocumentsToday / $dailyDocTarget) * 100)) : 0;

        // Get latest companies
        $latestCompanies = CompanyAccount::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch recent activities
        $recentUserActivities = User::with('company')->latest()->take(5)->get()->map(function($user) {
            $companyName = $user->company ? $user->company->company_name : 'No Company';
            $companyId = $user->company ? $user->company->id : null;
            
            return [
                'id' => $user->id,
                'company_name' => $companyName,
                'company_id' => $companyId,
                'action' => 'User registered',
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'created_at' => $user->created_at,
                'type' => 'user'
            ];
        });
        
        $recentDocumentActivities = Document::with('user.company')->latest()->take(5)->get()->map(function($document) {
            $user = $document->user;
            $companyName = $user && $user->company ? $user->company->company_name : 'No Company';
            $companyId = $user && $user->company ? $user->company->id : null;
            
            return [
                'id' => $document->id,
                'company_name' => $companyName,
                'company_id' => $companyId,
                'action' => 'Document uploaded',
                'user_name' => $user ? ($user->first_name . ' ' . $user->last_name) : 'Unknown',
                'created_at' => $document->created_at,
                'type' => 'document'
            ];
        });

        // Combine activities and convert to collection for proper sorting and Blade compatibility
        $combinedActivities = array_merge($recentUserActivities->toArray(), $recentDocumentActivities->toArray());
        $recentActivities = collect($combinedActivities)->sortByDesc('created_at')->take(10);

        // Get monthly statistics for the growth chart
        $monthlyStats = $this->getMonthlyStats();

        return view('admin.dashboard', compact(
            'totalCompanies', 
            'totalUsers', 
            'totalDocuments', 
            'activeSubscriptions',
            'companyGrowth',
            'userGrowth',
            'documentGrowth',
            'subscriptionGrowth',
            'newUsersToday',
            'newDocumentsToday',
            'userProgress',
            'docProgress',
            'dailyUserTarget',
            'dailyDocTarget',
            'latestCompanies',
            'monthlyStats',
            'recentActivities'
        ));
    }

    /**
     * Get monthly statistics for the growth chart.
     */
    private function getMonthlyStats()
    {
        $months = [];
        $companiesData = [];
        $usersData = [];
        $documentsData = [];
        $subscriptionsData = [];

        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');
            
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            // Companies created in this month
            $companiesData[] = CompanyAccount::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            
            // Users created in this month
            $usersData[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            
            // Documents created in this month
            $documentsData[] = Document::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            
            // Subscriptions created in this month
            $subscriptionsData[] = CompanySubscription::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }

        return [
            'months' => $months,
            'companiesData' => $companiesData,
            'usersData' => $usersData,
            'documentsData' => $documentsData,
            'subscriptionsData' => $subscriptionsData
        ];
    }
}
