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
}