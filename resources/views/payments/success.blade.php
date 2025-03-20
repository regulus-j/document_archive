<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Payment Successful
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-8">
                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <h2 class="mt-4 text-2xl font-bold text-gray-900">{{ $message }}</h2>
                        <p class="mt-2 text-gray-600">Your transaction has been processed successfully.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Transaction Reference</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->transaction_reference }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                                <dd class="mt-1 text-sm text-gray-900">₱{{ number_format($payment->amount, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Details</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Plan Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->plan_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Subscription Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>