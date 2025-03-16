<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Document;
use App\Models\Subscriptions;
use App\Models\Log;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalDocuments = Document::count();
        $activeSubscriptions = Subscriptions::where('status', 'active')->count();
         
        $recentActivities = Log::latest()->take(5)->get();
        // Fetch recent activities
    
        return view('superadmin.dashboard', compact(
            'totalCompanies',
            'totalUsers',
            'totalDocuments',
            'activeSubscriptions',
            'recentActivities'
        ));
    }

}




