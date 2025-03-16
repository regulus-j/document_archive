@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Company Details</h1>

        <!-- Company Basic Info -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Company Name:</label>
            <p class="text-gray-900">{{ $company->company_name }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Registered Name:</label>
            <p class="text-gray-900">{{ $company->registered_name }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Email:</label>
            <p class="text-gray-900">{{ $company->company_email }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Phone:</label>
            <p class="text-gray-900">{{ $company->company_phone }}</p>
        </div>

        @if($company->industry)
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Industry:</label>
            <p class="text-gray-900">{{ $company->industry }}</p>
        </div>
        @endif

        @if($company->company_size)
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Company Size:</label>
            <p class="text-gray-900">{{ $company->company_size }}</p>
        </div>
        @endif

        <!-- Single Address -->
        @if($company->address)
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Address</h2>
                <p class="text-gray-900">{{ $company->address->address }}</p>
                <p class="text-gray-900">{{ $company->address->city }}, {{ $company->address->state }}</p>
                <p class="text-gray-900">{{ $company->address->country }} {{ $company->address->zip_code }}</p>
            </div>
        @else
            <div class="mb-4">
                <p class="text-gray-500">No address provided.</p>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('companies.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-300">
                Back to Companies
            </a>
        </div>
    </div>
</div>
@endsection
