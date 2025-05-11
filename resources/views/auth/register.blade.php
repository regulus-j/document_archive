<x-guest-layout>
    <div class="text-center mb-8">
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png" sizes="32x32">
        <h1 class="text-2xl font-semibold mb-2">DocTrack</h1>
        <p class="text-gray-500 text-sm">
            Create your account to get started
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registrationForm">
        @csrf

        @if(isset($plan))
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-lg">Selected Plan: {{ $plan->plan_name }}</h3>
                <p class="text-sm text-gray-600">₱{{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }}</p>
            </div>
        @endif

        <!-- Display all validation errors at the top of the form -->
        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-50 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-600 text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="validation-errors" class="mt-4 p-3 bg-red-50 rounded-lg hidden"></div>

        <!-- Combined Personal Information and Security Verification -->
        <div class="space-y-4">
            <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
            
            <div>
                <x-input-label for="first_name" :value="__('First Name')" class="text-gray-700" />
                <x-text-input id="first_name"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150 @error('first_name') border-red-500 @enderror"
                    type="text" name="first_name" :value="old('first_name')" required autofocus
                    autocomplete="first_name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" class="text-gray-700" />
                <x-text-input id="middle_name"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
                    type="text" name="middle_name" :value="old('middle_name')" autocomplete="middle_name" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" class="text-gray-700" />
                <x-text-input id="last_name"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
                    type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
                <x-text-input id="email"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
                    type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
                <x-text-input id="password"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
                    type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700" />
                <x-text-input id="password_confirmation"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
                    type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="company_name" :value="__('Company Name')" class="text-gray-700" />
                <x-text-input id="company_name"
                    class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150 @error('company_name') border-red-500 @enderror"
                    type="text" name="company_name" :value="old('company_name')" required 
                    autocomplete="company_name" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>
            
            <!-- Hidden fields with default values for company data -->
            <input type="hidden" name="registered_name" value="Default" />
            <input type="hidden" name="company_email" value="{{ old('email') }}" />
            <input type="hidden" name="company_phone" value="00000000000" />
            <input type="hidden" name="address" value="Default Address" />
            <input type="hidden" name="city" value="Default City" />
            <input type="hidden" name="state" value="Default State" />
            <input type="hidden" name="zip_code" value="00000" />
            <input type="hidden" name="country" value="Default Country" />
            
            <h2 class="text-xl font-semibold mb-4 mt-6">Security Verification</h2>
            <div class="flex flex-col items-center mb-4">
                <div class="g-recaptcha mb-2" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                <div class="w-full">
                    @error('g-recaptcha-response')
                        <p class="text-red-600 text-sm text-center mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="text-sm text-gray-600 text-center">
                <p>By registering, you agree to our <a href="#" class="text-blue-500 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a>.</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-blue-500 hover:text-blue-600" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <button type="submit" id="submitBtn"
                class="py-3 px-6 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                Register
            </button>
        </div>
    </form>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Email validation helper
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Form submission validation
            document.getElementById('registrationForm').addEventListener('submit', function(e) {
                let isValid = true;
                const errorMessages = [];
                
                // Validate personal information fields
                const requiredFields = ['first_name', 'last_name', 'email', 'password', 'password_confirmation', 'company_name'];
                
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        isValid = false;
                        errorMessages.push(`${field.replace('_', ' ')} is required`);
                        input.classList.add('border-red-500');
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });
                
                // Validate email format
                const email = document.getElementById('email');
                if (email.value.trim() && !isValidEmail(email.value.trim())) {
                    isValid = false;
                    errorMessages.push('Please enter a valid email address');
                    email.classList.add('border-red-500');
                }
                
                // Validate password match
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');
                if (password.value && passwordConfirmation.value && password.value !== passwordConfirmation.value) {
                    isValid = false;
                    errorMessages.push('Passwords do not match');
                    passwordConfirmation.classList.add('border-red-500');
                }
                
                // Validate reCAPTCHA
                const recaptchaResponse = grecaptcha.getResponse();
                if (!recaptchaResponse) {
                    isValid = false;
                    errorMessages.push('Please complete the reCAPTCHA verification');
                }
                
                // Set company email to match personal email if provided
                if (email.value.trim()) {
                    document.querySelector('input[name="company_email"]').value = email.value.trim();
                }
                
                // Display error messages if any
                const errorContainer = document.getElementById('validation-errors');
                errorContainer.innerHTML = '';
                
                if (!isValid) {
                    e.preventDefault();
                    
                    errorMessages.forEach(message => {
                        const errorElement = document.createElement('p');
                        errorElement.className = 'text-red-500 text-sm mt-1';
                        errorElement.textContent = message;
                        errorContainer.appendChild(errorElement);
                    });
                    errorContainer.classList.remove('hidden');
                    
                    // Scroll to the top where errors are displayed
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    
                    return false;
                } else {
                    errorContainer.classList.add('hidden');
                }
            });
        });
    </script>
</x-guest-layout>
