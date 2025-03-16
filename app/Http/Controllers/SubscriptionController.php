<?php

namespace App\Http\Controllers;


use App\Models\Subscriptions;
use App\Models\Plan;

use App\Models\CompanySubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
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

    public function index()
{
    $user = auth()->user();

    // Ensure user has a companyAccount before accessing subscriptions
    $subscriptions = optional($user->companyAccount)->subscriptions ?? collect();

    $plans = Plan::paginate(10); // Fetch plans with pagination

    return view('subscriptions.index', compact('subscriptions', 'plans'));
}


}

