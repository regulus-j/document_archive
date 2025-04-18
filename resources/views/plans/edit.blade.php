@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-white to-gray-50 py-12">
    <div class="mx-auto max-w-3xl px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Edit Plan</h2>
        </div>

        @if($errors->any())
            <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ route('plans.update', $plan) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="plan_name" class="block text-sm font-medium text-gray-700">Plan Name</label>
                        <input type="text" name="plan_name" id="plan_name" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('plan_name', $plan->plan_name) }}" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $plan->description) }}</textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="price" id="price" step="0.01" min="0"
                                   class="block w-full rounded-md border-gray-300 pl-7 focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('price', $plan->price) }}" required>
                        </div>
                    </div>

                    <div>
                        <label for="billing_cycle" class="block text-sm font-medium text-gray-700">Billing Cycle</label>
                        <select name="billing_cycle" id="billing_cycle" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="custom" {{ old('billing_cycle', $plan->billing_cycle) == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" 
                                    value="1"
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active Plan</label>
                        </div>

                        @foreach(['feature_1', 'feature_2', 'feature_3'] as $feature)
                            <div class="flex items-center">
                                <input type="checkbox" name="{{ $feature }}" id="{{ $feature }}"
                                        value="1"
                                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       {{ old($feature, $plan->$feature) ? 'checked' : '' }}>
                                <label for="{{ $feature }}" class="ml-2 block text-sm text-gray-700">
                                    {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('plans.show', $plan) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
