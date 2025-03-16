<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $plans = Plan::where('is_active', 1)->paginate(6); // Fetch active plans
    
        // Check if companyAccount exists before accessing subscriptions
        $subscription = $user->companyAccount ? $user->companyAccount->subscriptions->first() : null;
    
        return view('plans.index', compact('plans', 'subscription'));
    }
    
    

    
    

    public function register(Plan $plan)
    {
        return view('auth.register', compact('plan'));
    }

    public function subscribe(Request $request, Plan $plan)
    {
        // Subscription logic here
        return redirect()->route('plans.index')->with('success', 'Successfully subscribed to plan!');
    }
}

