<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanSelectionController extends Controller
{
    public function select()
    {
        $plans = Plan::where('is_active', true)->take(3)->get();
        return view('plans.select', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        return redirect()->route('payments.create', $plan);
    }
}

