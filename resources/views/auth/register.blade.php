<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        @if(isset($plan))
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-lg">Selected Plan: {{ $plan->plan_name }}</h3>
                <p class="text-sm text-gray-600">₱{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}</p>
            </div>
        @endif

        <!-- Existing form fields -->
        <div class="">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name"
                    :value="old('first_name')" required autofocus autocomplete="first_name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="middle_name" :value="__('Middle Name')" />
                <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name"
                    :value="old('middle_name')" autocomplete="middle_name" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                    :value="old('last_name')" required autocomplete="last_name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Company Details -->
        <div class="mt-4">
            <h2 class="text-lg font-semibold">Company Information</h2>
            <div class="mt-2">
                <x-input-label for="company_name" :value="__('Company Name')" />
                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" required />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="registered_name" :value="__('Registered Name')" />
                <x-text-input id="registered_name" class="block mt-1 w-full" type="text" name="registered_name" :value="old('registered_name')" required />
                <x-input-error :messages="$errors->get('registered_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="company_email" :value="__('Company Email')" />
                <x-text-input id="company_email" class="block mt-1 w-full" type="email" name="company_email" :value="old('company_email')" required />
                <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="company_phone" :value="__('Company Phone')" />
                <x-text-input id="company_phone" class="block mt-1 w-full" type="text" name="company_phone" :value="old('company_phone')" required />
                <x-input-error :messages="$errors->get('company_phone')" class="mt-2" />
            </div>
        </div>

        <!-- Company Address -->
        <div class="mt-4">
            <h2 class="text-lg font-semibold">Company Address</h2>
            <div class="mt-2">
                <x-input-label for="address" :value="__('Street Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="state" :value="__('State')" />
                <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" required />
                <x-input-error :messages="$errors->get('state')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="zip_code" :value="__('ZIP Code')" />
                <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code')" required />
                <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="country" :value="__('Country')" />
                <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" required />
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>