@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-white to-gray-50 py-12">
    <div class="mx-auto max-w-3xl px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Plan Details</h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('plans.edit', $plan) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Plan
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->plan_name }}</h3>
                        <div class="mt-4 flex items-baseline">
                            <span class="text-4xl font-bold tracking-tight text-gray-900">₱{{ number_format($plan->price, 2) }}</span>
                            <span class="ml-2 text-sm font-medium text-gray-500">/{{ $plan->billing_cycle }}</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-sm rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900">Description</h4>
                    <p class="mt-2 text-gray-600">{{ $plan->description ?: 'No description available.' }}</p>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900">Features</h4>
                    <ul class="mt-4 space-y-4">
                        @foreach($plan->features as $feature)
                            <li class="flex items-center gap-x-3">
                                @if($feature->pivot->enabled)
                                    <svg class="h-5 w-5 flex-shrink-0 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $feature->name }}</span>
                                @else
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-400">{{ $feature->name }}</span>
                                @endif
                                @if($feature->description)
                                    <span class="text-xs text-gray-500 ml-1">({{ $feature->description }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('plans.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Plans
            </a>
        </div>
    </div>
</div>
@endsection
