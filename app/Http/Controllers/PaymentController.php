<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\CompanySubscription;
use App\Models\CompanyAccount;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function linkCreate($plan)
    {
        $client = new \GuzzleHttp\Client();

        $plan = Plan::findOrFail($plan);

        session(['selected_plan' => $plan]);

        $price = $plan->price * 100;
        $body = [
            'data' => [
                'attributes' => [
                    'amount' => $price * 100,
                    'description' => "Payment for {$plan->name} plan",
                    'remarks' => "Subscription payment"
                ]
            ]
        ];
        
        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
            'json' => $body,
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
                $user = auth()->user();
                $company = $user->companies()->first();
    
                if ($company) {
                    $subscription = $company->subscriptions()->where('company_id', $company->id)->first();
    
                    if (!$subscription) {
                        if (!session('selected_plan')) {
                            \Log::error('Selected plan not found in session.');
                            return 'error';
                        }
    
                        $subscription = CompanySubscription::create([
                            'company_id' => $company->id,
                            'plan_id'    => session('selected_plan')->id,
                            'start_date' => now(),
                            'end_date'   => now()->addMonth(), // Adjust as needed
                            'status'     => 'active',
                            'auto_renew' => true,
                        ]);
                    }

                    $status = 'successful';
    
                    SubscriptionPayment::create([
                        'company_subscription_id' => $subscription->id,
                        'payment_date'            => now(),
                        'amount'                  => $result['data'][0]['attributes']['amount'],
                        'payment_method'          => 'other',
                        'status'                  => $status,
                        'transaction_reference'   => $result['data'][0]['attributes']['reference_number'],
                        'notes'                   => $result['data'][0]['attributes']['remarks'],
                    ]);
    
                    session()->forget('selected_plan');
    
                    return $status;
                } else {
                    \Log::error('No company associated with the authenticated user.');
                    return 'error';
                }
            }
            
            return 'pending';
        } catch (\Exception $e) {
            \Log::error('PayMongo Error: ' . $e->getMessage());
            return 'error';
        }
    }

    public function success(Request $request)
    {
        $referenceNumber = $request->query('reference');
        $payment = SubscriptionPayment::with(['subscription.plan'])
            ->where('transaction_reference', $referenceNumber)
            ->firstOrFail();
    
        return view('payments.success', [
            'message' => 'Payment completed successfully!',
            'payment' => $payment,
            'subscription' => $payment->subscription,
            'plan' => $payment->subscription->plan
        ]);
    }

    public function create(Request $request, Plan $plan)
    {
        $billing = $request->query('billing', 'monthly');
        // Calculate price (price is expected in dollars/amount unit)
        $price = $this->calculatePrice($plan->price, $billing);
        // Convert price to the smallest currency unit (e.g. cents)
        $amount = $price * 100;

        // Prepare the request body for PayMongo
        $body = [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'description' => 'Payment for subscription plan: ' . $plan->name,
                    'remarks' => ucfirst($billing) . ' subscription'
                ]
            ]
        ];

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
                'json' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => 'Basic ' . base64_encode(config('services.paymongo.secret_key') . ':'),
                    'content-type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            // Extract the checkout URL from the response
            $paymentLink = $responseData['data']['attributes']['redirect']['checkout_url'] ?? null;

            if (!$paymentLink) {
                \Log::error('PayMongo Response did not include a checkout_url:', $responseData);
                return view('payments.create', [
                    'plan'       => $plan,
                    'billing'    => $billing,
                    'price'      => $price,
                    'paymentLink'=> null,
                    'error'      => 'Unable to generate payment link. Please try again later or contact support.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("PayMongo Error: " . $e->getMessage());
            return view('payments.create', [
                'plan'       => $plan,
                'billing'    => $billing,
                'price'      => $price,
                'paymentLink'=> null,
                'error'      => 'Unable to generate payment link. Please try again later or contact support.'
            ]);
        }

        return view('payments.create', compact('plan', 'billing', 'price', 'paymentLink'));
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

