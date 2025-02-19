<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\CompanySubscription;
use App\Models\CompanyAccount;
use Illuminate\Support\Facades\DB;

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

    public function linkCreate()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
            'body' => '{"data":{"attributes":{"amount":100000,"description":"string","remarks":"string"}}}',
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . base64_encode(config('services.paymongo.secret_key') . ':'),
                'content-type' => 'application/json',
            ],
        ]);

        $responseData = $response->getBody()->getContents();

        return view('payments.out')->with('responseData', $responseData);
    }
    public function checkPaymentStatus($referenceNumber)
    {
        $client = new \GuzzleHttp\Client();
        $baseUrl = 'https://api.paymongo.com/v1/links';

        try {
            $response = $client->request('GET', $baseUrl . '?reference_number=' . $referenceNumber, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => 'Basic ' . base64_encode(config('services.paymongo.secret_key') . ':'),
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            \Log::debug('PayMongo Response:', $result);

            if (!empty($result['data'][0])) {
                return $result['data'][0]['attributes']['status'];
            }

            return 'pending';
        } catch (\Exception $e) {
            \Log::error('PayMongo Error: ' . $e->getMessage());
            return 'error';
        }
    }
    public function success()
    {
        return view('payments.success', [
            'message' => 'Payment completed successfully!'
        ]);
    }

    public function create(Request $request, Plan $plan)
    {
        $billing = $request->query('billing', 'monthly');
        $price = $this->calculatePrice($plan->price, $billing);

        return view('payments.create', compact('plan', 'billing', 'price'));
    }

    public function store(Request $request, Plan $plan)
    {
        $request->validate([
            'payment_method' => ['required', 'in:credit_card,paypal,bank_transfer,gcash'],
        ]);

        $user = auth()->user();
        $billing = $request->query('billing', 'monthly');
        $price = $this->calculatePrice($plan->price, $billing);

        try {
            DB::beginTransaction();

            $company = CompanyAccount::where('user_id', $user->id)->firstOrFail();

            // Create subscription
            $subscription = CompanySubscription::create([
                'company_id' => $company->id,
                'plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => $billing === 'yearly' ? now()->addYear() : now()->addMonth(),
                'status' => 'pending',
                'auto_renew' => true,
            ]);

            // Create payment record
            $payment = SubscriptionPayment::create([
                'company_subscription_id' => $subscription->id,
                'payment_date' => now(),
                'amount' => $price,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'transaction_reference' => $this->generateTransactionReference(),
            ]);

            // Here you would integrate with your payment gateway
            // For this example, we'll simulate a successful payment
            $payment->update(['status' => 'successful']);
            $subscription->update(['status' => 'active']);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Payment successful! Your subscription is now active.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    private function calculatePrice($basePrice, $billing)
    {
        return $billing === 'yearly' ? $basePrice * 12 : $basePrice;
    }

    private function generateTransactionReference()
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }
}

