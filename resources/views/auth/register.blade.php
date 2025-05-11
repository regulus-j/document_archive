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

        <!-- Step 1: Personal Information -->
        <div id="step1" class="step-content">
            <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
            <div class="space-y-4">
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" class="text-gray-700" />
                    <x-text-input id="first_name"
                        class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
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
            </div>
        </div>

        <!-- Step 2: Verification Code -->
        <div id="step2" class="step-content hidden">
            <h2 class="text-xl font-semibold mb-4">Email Verification</h2>
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg text-center">
                    <p class="mb-3">We've sent a verification code to your email.</p>
                    <p class="text-sm text-gray-600">Please check your inbox and enter the code below.</p>
                </div>
                
                <div>
                    <x-input-label for="verification_code" :value="__('Verification Code')" class="text-gray-700" />
                    <x-text-input id="verification_code"
                        class="mt-2 block w-full p-3 rounded-md border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150"
                        type="text" name="verification_code" required />
                    <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
                </div>
                
                <div class="text-center">
                    <button type="button" id="resendCode" class="text-blue-500 hover:underline text-sm">
                        Resend verification code
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Captcha -->
        <div id="step3" class="step-content hidden">
            <h2 class="text-xl font-semibold mb-4">Security Verification</h2>
            <div class="space-y-4">
                <div class="flex justify-center mb-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
                </div>
                
                <div class="text-sm text-gray-600 text-center">
                    <p>By registering, you agree to our <a href="#" class="text-blue-500 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a>.</p>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between mt-6">
            <button type="button" id="prevBtn"
                class="hidden py-3 px-6 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition duration-150">
                Previous
            </button>
            <a class="text-sm text-blue-500 hover:text-blue-600" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <div>
                <button type="button" id="nextBtn"
                    class="py-3 px-6 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Next
                </button>
                <button type="submit" id="submitBtn"
                    class="hidden py-3 px-6 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Register
                </button>
            </div>
        </div>
    </form>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 3;
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Show the specified step
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Show the current step
        document.getElementById('step' + step).classList.remove('hidden');
        
        // Update buttons
        prevBtn.classList.toggle('hidden', step === 1);
        nextBtn.classList.toggle('hidden', step === totalSteps);
        submitBtn.classList.toggle('hidden', step !== totalSteps);
    }

    // Validate the current step
    function validateStep(step) {
        let isValid = true;
        const errorMessages = [];
        
        if (step === 1) {
            // Validate personal information fields
            const requiredFields = ['first_name', 'last_name', 'email', 'password', 'password_confirmation'];
            
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
        } else if (step === 2) {
            // Validate verification code
            const verificationCode = document.getElementById('verification_code');
            if (!verificationCode.value.trim()) {
                isValid = false;
                errorMessages.push('Verification code is required');
                verificationCode.classList.add('border-red-500');
            } else {
                verificationCode.classList.remove('border-red-500');
            }
        }
        
        // Display error messages
        const errorContainer = document.getElementById('validation-errors');
        if (errorContainer) {
            errorContainer.innerHTML = '';
            
            if (!isValid) {
                errorMessages.forEach(message => {
                    const errorElement = document.createElement('p');
                    errorElement.className = 'text-red-500 text-sm mt-1';
                    errorElement.textContent = message;
                    errorContainer.appendChild(errorElement);
                });
                errorContainer.classList.remove('hidden');
            } else {
                errorContainer.classList.add('hidden');
            }
        }
        
        return isValid;
    }
    
    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Next button click
    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep) && currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
            document.getElementById('validation-errors').classList.add('hidden');
        }
    });

    // Previous button click
    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            document.getElementById('validation-errors').classList.add('hidden');
        }
    });

    // Resend code functionality
    document.getElementById('resendCode')?.addEventListener('click', function() {
        alert('Verification code resent. Please check your email.');
        // Here you would typically make an AJAX call to resend the code
    });

    // Form submission validation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        if (!validateStep(currentStep)) {
            e.preventDefault();
        }
    });

    // Initialize form
    showStep(currentStep);
    
    // Add validation error container if it doesn't exist
    if (!document.getElementById('validation-errors')) {
        const errorContainer = document.createElement('div');
        errorContainer.id = 'validation-errors';
        errorContainer.className = 'mt-4 p-3 bg-red-50 rounded-lg hidden';
        const form = document.getElementById('registrationForm');
        form.insertBefore(errorContainer, form.querySelector('.flex.items-center.justify-between'));
    }
});
    </script>
</x-guest-layout>