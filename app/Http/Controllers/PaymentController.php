<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SubscriptionPayment::with(['subscription.company'])
            ->when($request->search, function ($query, $search) {
                $query->where('transaction_reference', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->payment_method, function ($query, $method) {
                $query->where('payment_method', $method);
            })
            ->when($request->date, function ($query, $date) {
                $query->whereDate('payment_date', $date);
            });

        $payments = $query->latest()->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function show(SubscriptionPayment $payment)
    {
        $payment->load('subscription.company');
        return view('payments.show', compact('payment'));
    }
}