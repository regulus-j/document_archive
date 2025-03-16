@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-white to-gray-50 py-24 sm:py-32">
    <!-- Current Plan Section -->
    <!-- <div class="mt-16 mx-auto max-w-4xl text-center">
        @if(auth()->user()->companyAccount && auth()->user()->companyAccount->subscriptions->first())
            <div class="bg-white p-8 rounded-2xl border border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Your Current Plan</h3>
                <div class="mt-4">
                    <p class="text-lg text-gray-600">You are currently on the <span class="font-semibold text-blue-600">{{ auth()->user()->companyAccount->subscriptions->first()->plan->plan_name }}</span> plan</p>
                    <p class="mt-2 text-sm text-gray-500">Billing cycle: {{ auth()->user()->companyAccount->subscriptions->first()->billing_cycle }}</p>
                </div>
            </div>
        @endif -->

        @if(isset($subscription) && $subscription)
    <div class="bg-white p-8 rounded-2xl border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-900">Your Current Plan</h3>
        <div class="mt-4">
            <p class="text-lg text-gray-600">
                You are currently on the <span class="font-semibold text-blue-600">{{ $subscription->plan->plan_name }}</span> plan
            </p>
            <p class="mt-2 text-sm text-gray-500">Billing cycle: {{ $subscription->billing_cycle }}</p>
        </div>
    </div>
@endif


    </div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Plan & Pricing</h2>
            <p class="mt-4 text-lg leading-8 text-gray-600">Choose the plan that best fits your needs.</p>

            <!-- Billing Toggle -->
            <div class="mt-8 flex justify-center gap-4">
                <button id="monthly-btn" class="px-6 py-2 rounded-full transition-all duration-200 focus:outline-none">Monthly</button>
                <button id="yearly-btn" class="px-6 py-2 rounded-full transition-all duration-200 focus:outline-none">Yearly</button>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            @foreach ($plans as $plan)
                <div class="relative flex flex-col rounded-2xl transition-all duration-300 hover:scale-105
                    {{ $plan->is_active 
                        ? 'border-2 border-blue-600 shadow-lg' 
                        : 'border border-gray-200 hover:border-blue-400 hover:shadow-md' }} 
                    p-8 bg-white">
                    @if($plan->is_active)
                        <span class="absolute -top-4 left-1/2 transform -translate-x-1/2 inline-flex items-center rounded-full bg-blue-600 px-4 py-1 text-sm font-semibold text-white shadow-sm">
                            Most Popular
                        </span>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900">{{ $plan->plan_name }}</h3>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold tracking-tight text-gray-900">₱{{ number_format($plan->price, 2) }}</span>
                            <span class="ml-2 text-sm font-medium text-gray-500">/{{ $plan->billing_cycle }}</span>
                        </div>
                    </div>

                    <ul role="list" class="mt-8 space-y-4 flex-grow">
                        @foreach(['feature_1', 'feature_2', 'feature_3'] as $feature)
                            @if($plan->$feature)
                                <li class="flex items-center gap-x-3">
                                    <svg class="h-5 w-5 flex-shrink-0 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $plan->$feature }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <a href="{{ route('plans.register', $plan) }}"
                        class="mt-8 block rounded-lg bg-blue-600 px-5 py-3 text-center text-sm font-semibold text-white transition-colors hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        Get Started
                        <span class="ml-2 inline-block transition-transform group-hover:translate-x-1">→</span>
                    </a>

                    <p class="mt-6 text-center text-sm text-gray-500">{{ $plan->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyBtn = document.getElementById('monthly-btn');
    const yearlyBtn = document.getElementById('yearly-btn');
    
    function updateToggle(activeBtn, inactiveBtn) {
        activeBtn.classList.add('bg-blue-600', 'text-white');
        activeBtn.classList.remove('text-blue-600', 'border-blue-600', 'border');
        
        inactiveBtn.classList.remove('bg-blue-600', 'text-white');
        inactiveBtn.classList.add('text-blue-600', 'border-blue-600', 'border');
    }

    // Initialize with monthly selected
    updateToggle(monthlyBtn, yearlyBtn);

    monthlyBtn.addEventListener('click', () => updateToggle(monthlyBtn, yearlyBtn));
    yearlyBtn.addEventListener('click', () => updateToggle(yearlyBtn, monthlyBtn));
});
</script>
@endsection