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

    @push('scripts')
    <script>
        function pollPaymentStatus() {
            const referenceNumber = "{{ $paymentData['attributes']['reference_number'] ?? '' }}";
            
            if (!referenceNumber) return;
    
            const checkStatus = async () => {
                try {
                    const url = "{{ route('payment.check-status', ['reference' => ':reference']) }}".replace(':reference', referenceNumber);
                    const response = await fetch(url, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Payment status:', data.status);
                    
                    if (data.status === 'paid') {
                        window.location.href = "{{ route('payment.success') }}";
                        return;
                    }
                    
                    setTimeout(checkStatus, 5000);
                } catch (error) {
                    console.error('Error checking payment status:', error);
                    setTimeout(checkStatus, 5000); // Continue polling even if there's an error
                }
            };
    
            checkStatus();
        }
    
        document.addEventListener('DOMContentLoaded', pollPaymentStatus);
    </script>
    @endpush
</x-app-layout>