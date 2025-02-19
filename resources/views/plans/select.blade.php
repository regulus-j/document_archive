<x-app-layout>
    <div x-data="{ billing: 'monthly' }" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900">Plan & Pricing</h2>
                <p class="mt-4 text-lg leading-6 text-gray-600">Choose the plan that fits your needs.</p>
                
                <!-- Billing Toggle -->
                <div class="mt-6 inline-flex rounded-md shadow-sm">
                    <button 
                        @click="billing = 'monthly'" 
                        :class="{ 'bg-blue-500 text-white': billing === 'monthly', 'border border-blue-500 text-blue-500': billing !== 'monthly' }"
                        class="px-4 py-2 text-sm font-medium rounded-l-md"
                    >
                        Monthly
                    </button>
                    <button 
                        @click="billing = 'yearly'" 
                        :class="{ 'bg-blue-500 text-white': billing === 'yearly', 'border border-blue-500 text-blue-500': billing !== 'yearly' }"
                        class="px-4 py-2 text-sm font-medium rounded-r-md"
                    >
                        Yearly
                    </button>
                </div>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
                <!-- Basic Plan -->
                <div class="relative flex flex-col rounded-2xl border border-blue-100 p-8 shadow-sm">
                    <div class="absolute -top-4 right-8">
                        <span class="inline-flex items-center rounded-full bg-blue-500 px-3 py-1 text-xs font-semibold text-white">
                            Popular
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">BASIC</h3>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold tracking-tight text-gray-900" x-text="billing === 'monthly' ? 'P 2,200' : 'P 24,000'"></span>
                            <span class="ml-1 text-sm font-semibold text-gray-500" x-text="'/' + billing"></span>
                        </div>
                    </div>

                    <ul class="mt-8 space-y-4 flex-1">
                        <li class="flex items-center">
                            <div class="rounded-full p-1 bg-blue-500">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-600">Up to 5 users</span>
                        </li>
                        <li class="flex items-center">
                            <div class="rounded-full p-1 bg-blue-500">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-600">10 GB of storage</span>
                        </li>
                        <li class="flex items-center">
                            <div class="rounded-full p-1 bg-blue-500">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-600">Basic tracking and archiving</span>
                        </li>
                    </ul>

                    <a href="{{ route('payments.create', ['plan' => 1, 'billing' => 'monthly']) }}" 
                       x-bind:href="'{{ route('payments.create', ['plan' => 1]) }}?billing=' + billing"
                       class="mt-8 flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600">
                        Start Now
                        <svg class="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <p class="mt-6 text-center text-sm text-gray-500">Best for professionals.</p>
                </div>

                <!-- Premium Plan -->
                <div class="relative flex flex-col rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">PREMIUM</h3>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold tracking-tight text-gray-900" x-text="billing === 'monthly' ? 'P 3,500' : 'P 38,000'"></span>
                            <span class="ml-1 text-sm font-semibold text-gray-500" x-text="'/' + billing"></span>
                        </div>
                    </div>

                    <ul class="mt-8 space-y-4 flex-1">
                        <!-- Premium features list -->
                        <li class="flex items-center">
                            <div class="rounded-full p-1 bg-blue-500">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-600">Up to 20 users</span>
                        </li>
                        <!-- Add other Premium features similarly -->
                    </ul>

                    <a href="{{ route('payments.create', ['plan' => 2, 'billing' => 'monthly']) }}"
                       x-bind:href="'{{ route('payments.create', ['plan' => 2]) }}?billing=' + billing"
                       class="mt-8 flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600">
                        Start Now
                        <svg class="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <p class="mt-6 text-center text-sm text-gray-500">Perfect for special projects.</p>
                </div>

                <!-- Business Plan -->
                <div class="relative flex flex-col rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">BUSINESS</h3>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-5xl font-bold tracking-tight text-gray-900" x-text="billing === 'monthly' ? 'P 8,500' : 'P 90,000'"></span>
                            <span class="ml-1 text-sm font-semibold text-gray-500" x-text="'/' + billing"></span>
                        </div>
                    </div>

                    <ul class="mt-8 space-y-4 flex-1">
                        <!-- Business features list -->
                        <li class="flex items-center">
                            <div class="rounded-full p-1 bg-blue-500">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-600">Unlimited users</span>
                        </li>
                        <!-- Add other Business features similarly -->
                    </ul>

                    <a href="{{ route('payments.create', ['plan' => 3, 'billing' => 'monthly']) }}"
                       x-bind:href="'{{ route('payments.create', ['plan' => 3]) }}?billing=' + billing"
                       class="mt-8 flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600">
                        Start Now
                        <svg class="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <p class="mt-6 text-center text-sm text-gray-500">Ideal for Businesses</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

