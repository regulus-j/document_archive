<div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">Pricing</h2>
            <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                Choose the right plan for you
            </p>
        </div>
        <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            @foreach ($plans as $plan)
                <div class="relative flex flex-col rounded-2xl border border-gray-200 p-8 shadow-sm">
                    @if($plan->is_active)
                        <span
                            class="absolute -top-4 right-8 inline-flex items-center rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">
                            Active
                        </span>
                    @endif

                    <div class="mb-5">
                        <h3 class="text-lg font-semibold leading-8 text-gray-900">
                            {{ $plan->plan_name }}
                        </h3>
                        <p class="mt-4 text-sm leading-6 text-gray-600">
                            {{ $plan->description }}
                        </p>
                    </div>

                    <div class="mt-2 flex items-baseline">
                        <span
                            class="text-4xl font-bold tracking-tight text-gray-900">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-sm font-semibold leading-6 text-gray-600">/{{ $plan->billing_cycle }}</span>
                    </div>

                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                        @if($plan->feature_1)
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                        clip-rule="evenodd" />
                                </svg>
                                Feature 1
                            </li>
                        @endif
                        @if($plan->feature_2)
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                        clip-rule="evenodd" />
                                </svg>
                                Feature 2
                            </li>
                        @endif
                        @if($plan->feature_3)
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                        clip-rule="evenodd" />
                                </svg>
                                Feature 3
                            </li>
                        @endif
                    </ul>

                    <a href="{{ route('plans.subscribe', $plan->id) }}"
                        class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Get started
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>