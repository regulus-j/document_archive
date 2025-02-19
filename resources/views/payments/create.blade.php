<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Complete Your Payment</h2>
                        <p class="mt-2 text-sm text-gray-600">Selected plan details:</p>
                    </div>

                    <!-- Plan Summary -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $plan->plan_name }}</h3>
                                <p class="text-sm text-gray-600">Billing cycle: {{ ucfirst($billing) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold">₱{{ number_format($price, 2) }}</p>
                                <p class="text-sm text-gray-600">/{{ $billing }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('payments.store', ['plan' => $plan->id, 'billing' => $billing]) }}"
                        method="POST">
                        @csrf

                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                                Method</label>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Credit Card -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                    <input type="radio" name="payment_method" value="credit_card" class="sr-only"
                                        aria-labelledby="payment-method-0-label">
                                    <div class="flex flex-1">
                                        <div class="flex flex-col">
                                            <span id="payment-method-0-label"
                                                class="block text-sm font-medium text-gray-900">Credit Card</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">Pay with credit
                                                card</span>
                                        </div>
                                    </div>
                                    <div class="pointer-events-none absolute -inset-px rounded-lg border-2"
                                        aria-hidden="true"></div>
                                </label>

                                <!-- GCash -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                    <input type="radio" name="payment_method" value="gcash" class="sr-only"
                                        aria-labelledby="payment-method-1-label">
                                    <div class="flex flex-1">
                                        <div class="flex flex-col">
                                            <span id="payment-method-1-label"
                                                class="block text-sm font-medium text-gray-900">GCash</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">Pay with
                                                GCash</span>
                                        </div>
                                    </div>
                                    <div class="pointer-events-none absolute -inset-px rounded-lg border-2"
                                        aria-hidden="true"></div>
                                </label>

                                <!-- Bank Transfer -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="sr-only"
                                        aria-labelledby="payment-method-2-label">
                                    <div class="flex flex-1">
                                        <div class="flex flex-col">
                                            <span id="payment-method-2-label"
                                                class="block text-sm font-medium text-gray-900">Bank Transfer</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">Pay via bank
                                                transfer</span>
                                        </div>
                                    </div>
                                    <div class="pointer-events-none absolute -inset-px rounded-lg border-2"
                                        aria-hidden="true"></div>
                                </label>

                                <!-- PayPal -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                    <input type="radio" name="payment_method" value="paypal" class="sr-only"
                                        aria-labelledby="payment-method-3-label">
                                    <div class="flex flex-1">
                                        <div class="flex flex-col">
                                            <span id="payment-method-3-label"
                                                class="block text-sm font-medium text-gray-900">PayPal</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">Pay with
                                                PayPal</span>
                                        </div>
                                    </div>
                                    <div class="pointer-events-none absolute -inset-px rounded-lg border-2"
                                        aria-hidden="true"></div>
                                </label>
                            </div>

                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Pay ₱{{ number_format($price, 2) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>