<?php

namespace App\Http\Controllers;

use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'auto_renew' => 'boolean',
        ]);

        return DB::transaction(function () use ($validated) {
            $subscription = Subscriptions::create([
                ...$validated,
                'status' => 'pending',
            ]);

            // Additional subscription setup logic here

            return response()->json($subscription, 201);
        });
    }

    public function update(Request $request, Subscriptions $subscription)
    {
        $validated = $request->validate([
            'end_date' => 'nullable|date|after:start_date',
            'auto_renew' => 'boolean',
        ]);

        $subscription->update($validated);

        return response()->json($subscription);
    }

    public function cancel(Subscriptions $subscription)
    {
        $subscription->cancel();

        return response()->json(['message' => 'Subscription canceled successfully']);
    }

    public function activate(Subscriptions $subscription)
    {
        $subscription->activate();

        return response()->json(['message' => 'Subscription activated successfully']);
    }
}