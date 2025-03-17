<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', 1)->get();

        if (auth()->check() && auth()->user()->isAdmin()) {
            return view('admin.plans-index', compact('plans'));
        }
        return view('plans.index', compact('plans'));
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

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        // Dump the request data to see what's being received
        \Log::info('Plan creation request:', $request->all());
    
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,custom',
            'is_active' => 'nullable|boolean',  // Changed from 'boolean'
            'feature_1' => 'nullable|boolean',  // Changed from 'boolean'
            'feature_2' => 'nullable|boolean',  // Changed from 'boolean'
            'feature_3' => 'nullable|boolean',  // Changed from 'boolean'
        ]);
    
        // Make sure boolean fields have default values when not present
        $plan = Plan::create([
            'plan_name' => $validated['plan_name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'billing_cycle' => $validated['billing_cycle'],
            'is_active' => isset($validated['is_active']),
            'feature_1' => isset($validated['feature_1']),
            'feature_2' => isset($validated['feature_2']),
            'feature_3' => isset($validated['feature_3']),
        ]);
    
        return redirect()->route('plans.index')
            ->with('success', 'Plan created successfully');
    }
}

