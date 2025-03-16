@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-4xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create New Company') }}</h1>

        <!-- Validation Errors -->
        @if(session('errors'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">{{ __('Whoops! Something went wrong.') }}</p>
        <ul class="mt-3 list-disc list-inside text-sm">
            @foreach (session('errors')->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        <!-- Form -->
        <form method="POST" action="{{ route('companies.store') }}" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company Name -->
            <div class="space-y-2">
            <label for="company_name" class="text-sm font-medium text-gray-700">Company Name</label>
            <input id="company_name" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4]"
            type="text" name="company_name" value="{{ old('company_name') }}" required>
            </div>

            <!-- Registered Name -->
            <div class="space-y-2">
            <label for="registered_name" class="text-sm font-medium text-gray-700">Registered Name</label>
            <input id="registered_name" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4]"
            type="text" name="registered_name" value="{{ old('registered_name') }}" required>
            </div>

            <!-- Company Email -->
            <div class="space-y-2">
            <label for="company_email" class="text-sm font-medium text-gray-700">Company Email</label>
            <input id="company_email" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4]"
            type="email" name="company_email" value="{{ old('company_email') }}" required>
            </div>

            <!-- Company Phone -->
            <div class="space-y-2">
            <label for="company_phone" class="text-sm font-medium text-gray-700">Company Phone</label>
            <input id="company_phone" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4]"
            type="text" name="company_phone" value="{{ old('company_phone') }}" required>
            </div>


                <!-- Address -->
                <div class="space-y-2">
                    <x-input-label for="address" :value="__('Address')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="address" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="text" name="address" :value="old('address')" required autocomplete="address" />
                    <x-input-error :messages="$errors->get('address')" class="text-sm" />
                </div>

                <!-- City -->
                <div class="space-y-2">
                    <x-input-label for="city" :value="__('City')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="city" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="text" name="city" :value="old('city')" required autocomplete="city" />
                    <x-input-error :messages="$errors->get('city')" class="text-sm" />
                </div>

                <!-- State -->
                <div class="space-y-2">
                    <x-input-label for="state" :value="__('State')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="state" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="text" name="state" :value="old('state')" required autocomplete="state" />
                    <x-input-error :messages="$errors->get('state')" class="text-sm" />
                </div>

                <!-- Zip Code -->
                <div class="space-y-2">
                    <x-input-label for="zip_code" :value="__('Zip Code')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="zip_code" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="text" name="zip_code" :value="old('zip_code')" required autocomplete="zip_code" />
                    <x-input-error :messages="$errors->get('zip_code')" class="text-sm" />
                </div>

                <!-- Country -->
                <div class="space-y-2">
                    <x-input-label for="country" :value="__('Country')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="country" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="text" name="country" :value="old('country')" required autocomplete="country" />
                    <x-input-error :messages="$errors->get('country')" class="text-sm" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <x-primary-button class="px-6 py-3 bg-[#4285F4] hover:bg-[#4285F4]/90 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>{{ __('Create Company') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection