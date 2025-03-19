<?php

namespace App\Http\Controllers;

use App\Models\CompanySubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = CompanySubscription::with(['company', 'plan'])->get();
        return response()->json($subscriptions);
    }

    public function indexAdmin()
    {
        $subscriptions = CompanySubscription::with(['company', 'plan'])->get();
        return view('admin.subscriptions-index', compact('subscriptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:company_accounts,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'auto_renew' => 'boolean',
        ]);

        return DB::transaction(function () use ($validated) {
            $subscription = CompanySubscription::create([
                ...$validated,
                'status' => 'pending',
            ]);

            // Additional subscription setup logic here

            return response()->json($subscription, 201);
        });
    }

    public function update(Request $request, CompanySubscription $subscription)
    {
        $validated = $request->validate([
            'end_date' => 'nullable|date|after:start_date',
            'auto_renew' => 'boolean',
        ]);

        $subscription->update($validated);

        return response()->json($subscription);
    }

    public function cancel(CompanySubscription $subscription)
    {
        $subscription->update(['status' => 'canceled']);

        return response()->json(['message' => 'Subscription canceled successfully']);
    }

    public function activate(CompanySubscription $subscription)
    {
        $subscription->update(['status' => 'active']);

        return response()->json(['message' => 'Subscription activated successfully']);
    }
    
    /**
     * Show form to assign subscription to a company admin
     */
    public function assignForm()
    {
        // Check if user is super admin
        if (!auth()->user()->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $companies = \App\Models\CompanyAccount::all();
        $plans = \App\Models\Plan::all();
        
        return view('admin.subscriptions.assign', compact('companies', 'plans'));
    }
    
    /**
     * Assign subscription to a company admin
     */
    public function assign(Request $request)
    {
        // Check if user is super admin
        if (!auth()->user()->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'company_id' => 'required|exists:company_accounts,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'auto_renew' => 'boolean',
            'status' => 'required|in:active,pending,canceled'
        ]);
        
        DB::transaction(function () use ($validated) {
            // Check if company already has an active subscription
            $existingSubscription = CompanySubscription::where('company_id', $validated['company_id'])
                ->where('status', 'active')
                ->first();
                
            if ($existingSubscription) {
                // Update existing subscription end date if requested
                if ($validated['status'] == 'active') {
                    $existingSubscription->update([
                        'end_date' => $validated['end_date'],
                        'auto_renew' => $validated['auto_renew'] ?? false
                    ]);
                    return redirect()->route('admin.subscriptions.index')
                        ->with('success', 'Subscription updated successfully.');
                } else {
                    // Cancel existing subscription if new one should be active
                    $existingSubscription->update(['status' => 'canceled']);
                }
            }
            
            // Create new subscription
            CompanySubscription::create($validated);
        });
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription assigned successfully.');
    }
    
    /**
     * Renew an existing subscription
     */
    public function renew(Request $request, CompanySubscription $subscription)
    {
        // Check if user is super admin
        if (!auth()->user()->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'end_date' => 'required|date|after:today',
            'auto_renew' => 'boolean',
        ]);
        
        // Extend the subscription
        $subscription->update([
            'end_date' => $validated['end_date'],
            'auto_renew' => $validated['auto_renew'] ?? $subscription->auto_renew,
            'status' => 'active'
        ]);
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription renewed successfully until ' . $validated['end_date']);
    }
    
    /**
     * Handle automated subscription renewals
     * This should be called via a scheduled command
     */
    public function processAutoRenewals()
    {
        // Find subscriptions due to expire in the next 7 days that have auto-renew enabled
        $subscriptions = CompanySubscription::where('status', 'active')
            ->where('auto_renew', true)
            ->where('end_date', '<=', now()->addDays(7))
            ->where('end_date', '>=', now())
            ->get();
            
        foreach ($subscriptions as $subscription) {
            // Extend by the original duration
            $originalDuration = $subscription->original_duration ?? 365; // Default to 1 year
            
            $subscription->update([
                'end_date' => Carbon::parse($subscription->end_date)->addDays($originalDuration),
                'renewal_count' => ($subscription->renewal_count ?? 0) + 1,
                // You could add payment processing logic here
            ]);
            
            // Notify company admin about renewal
            // Add notification logic here
        }
        
        return response()->json(['message' => count($subscriptions) . ' subscriptions renewed successfully']);
    }
}
