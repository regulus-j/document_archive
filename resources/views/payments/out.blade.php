<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Awaiting Payment
        </h2>
    </x-slot>

    @php
        // Retrieve and decode payment data from the JSON response
        $decoded = json_decode($responseData, true);
        $paymentData = $decoded['data'] ?? null;
    @endphp

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($paymentData)
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-2xl font-bold mb-4">Payment Details</h3>
                    
                    <div class="mb-4">
                        <span class="font-semibold">Reference Number:</span>
                        <span>{{ $paymentData['attributes']['reference_number'] ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="font-semibold">Amount:</span>
                        <span>{{ number_format($paymentData['attributes']['amount'] / 100, 2) }} {{ $paymentData['attributes']['currency'] ?? '' }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="font-semibold">Description:</span>
                        <span>{{ $paymentData['attributes']['description'] ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="font-semibold">Remarks:</span>
                        <span>{{ $paymentData['attributes']['remarks'] ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="font-semibold">Status:</span>
                        <span class="text-yellow-600">{{ $paymentData['attributes']['status'] ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-6">
                        <span class="font-semibold">Checkout URL:</span>
                        <a href="{{ $paymentData['attributes']['checkout_url'] ?? '#' }}" target="_blank" class="text-blue-500 hover:underline">
                            {{ $paymentData['attributes']['checkout_url'] ?? 'N/A' }}
                        </a>
                    </div>

                    <div>
                        <p>Please click the link above to complete your payment. Once your payment is confirmed, you will receive a confirmation message.</p>
                    </div>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow">
                    <p>No payment details available. Please try generating the payment link again.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">Processing Payment</h2>
                    <div id="payment-status" class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                        <p class="mt-4">Please wait while we process your payment...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function pollPaymentStatus() {
            // Parse data properly
            const decoded = JSON.parse(@json($responseData));
            const referenceNumber = decoded.data.attributes.reference_number;
            
            if (!referenceNumber) {
                console.error('No reference number available');
                return;
            }

            let pollingAttempts = 0;
            const maxAttempts = 30; // 5 minutes with 10-second intervals

            const checkStatus = async () => {
                try {
                    console.log('Checking payment status for:', referenceNumber);
                    const response = await fetch(`{{ url('/payment/check-status') }}/${referenceNumber}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`Network response error: ${response.status}`);
                    }
                    
                    const status = await response.text();
                    console.log('Payment status:', status);
                    
                    if (status === 'successful') {
                        window.location.href = `{{ route('payment.success') }}?reference=${referenceNumber}`;
                        return;
                    } 
                    
                    // Important: Continue polling even on 'error' responses
                    // This allows recovery if there's a temporary error
                    pollingAttempts++;
                    if (pollingAttempts >= maxAttempts) {
                        document.getElementById('payment-status').innerHTML = `
                            <div class="text-yellow-500">
                                <p>Your payment may still be processing.</p>
                                <p>Please check your email for confirmation or contact support.</p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Show error message but continue polling
                    if (status === 'error') {
                        document.getElementById('payment-status').innerHTML = `
                            <div class="text-orange-500">
                                <p>We're still waiting for payment confirmation.</p>
                                <p>This page will update automatically when your payment is confirmed.</p>
                            </div>
                        `;
                    }
                    
                    // Continue polling regardless of status
                    setTimeout(checkStatus, 10000); // Check every 10 seconds
                } catch (error) {
                    console.error('Error checking payment status:', error);
                    pollingAttempts++;
                    if (pollingAttempts < maxAttempts) {
                        setTimeout(checkStatus, 10000);
                    }
                }
            };

            // Start polling
            checkStatus();
        }

        // Ensure the DOM is fully loaded before starting
        document.addEventListener('DOMContentLoaded', pollPaymentStatus);
    </script>
    @endpush
</x-app-layout>