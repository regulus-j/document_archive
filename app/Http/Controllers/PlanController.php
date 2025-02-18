<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', 1)->get();
        return view('plans.index', compact('plans'));
    }

    public function subscribe(Request $request, Plan $plan)
    {
        // Subscription logic here
        return redirect()->route('plans.index')->with('success', 'Successfully subscribed to plan!');
    }
}