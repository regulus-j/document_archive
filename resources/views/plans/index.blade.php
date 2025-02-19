<div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Plan & Pricing</h2>
            <p class="mt-2 text-lg leading-8 text-gray-600">Choose the plan that fits your needs.</p>

            <!-- Billing Toggle -->
            <div class="mt-8 flex justify-center gap-4">
                <button class="px-4 py-2 rounded-md border border-blue-600 text-blue-600">Monthly</button>
                <button class="px-4 py-2 rounded-md bg-blue-600 text-white">Yearly</button>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            @foreach ($plans as $plan)
                <div
                    class="relative flex flex-col rounded-xl border {{ $plan->is_active ? 'border-blue-600' : 'border-gray-200' }} p-8">
                    @if($plan->is_active)
                        <span
                            class="absolute -top-3 right-8 inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">
                            Popular
                        </span>
                    @endif

                    <div class="mb-5">
                        <h3 class="text-lg font-semibold leading-8 text-gray-900">{{ $plan->plan_name }}</h3>

                        <div class="mt-4 flex items-baseline">
                            <span class="text-4xl font-bold tracking-tight text-gray-900">₱
                                {{ number_format($plan->price, 2) }}</span>
                            <span class="text-sm font-semibold leading-6 text-gray-600">/{{ $plan->billing_cycle }}</span>
                        </div>
                    </div>

                    <ul role="list" class="mt-8 space-y-3 flex-grow">
                        @if($plan->feature_1)
                            <li class="flex gap-x-3 items-center">
                                <svg class="h-5 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm leading-6 text-gray-600">Feature 1</span>
                            </li>
                        @endif
                        @if($plan->feature_2)
                            <li class="flex gap-x-3 items-center">
                                <svg class="h-5 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm leading-6 text-gray-600">Feature 2</span>
                            </li>
                        @endif
                        @if($plan->feature_3)
                            <li class="flex gap-x-3 items-center">
                                <svg class="h-5 w-5 flex-none text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm leading-6 text-gray-600">Feature 3</span>
                            </li>
                        @endif
                    </ul>

                    <a href="{{ route('plans.register', $plan) }}"
                        class="mt-8 block rounded-md bg-blue-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Start Now
                        <span class="ml-2 inline-block">→</span>
                    </a>

                    <p class="mt-6 text-center text-sm text-gray-500">{{ $plan->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>