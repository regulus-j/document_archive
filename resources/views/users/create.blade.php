@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-user-plus text-[#4285F4] mr-2"></i>{{ __('Create New User') }}
        </h2>
        <a href="{{ route('users.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-[#4285F4] text-white rounded-md font-semibold uppercase tracking-widest hover:bg-[#4285F4]/90 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Users') }}
        </a>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p class="font-bold">{{ __('Whoops! Something went wrong.') }}</p>
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('users.store') }}" class="bg-white shadow-lg rounded-xl p-8 max-w-4xl mx-auto">
        @csrf

        <div class="space-y-8">
            <!-- Personal Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input id="first_name" type="text" name="first_name" class="w-full" :value="old('first_name')" required />
                        <x-input-error :messages="$errors->get('first_name')" />
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <x-input-label for="middle_name" :value="__('Middle Name')" />
                        <x-text-input id="middle_name" type="text" name="middle_name" class="w-full" :value="old('middle_name')" />
                        <x-input-error :messages="$errors->get('middle_name')" />
                    </div>

                    <!-- Last Name -->
                    <div>
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input id="last_name" type="text" name="last_name" class="w-full" :value="old('last_name')" required />
                        <x-input-error :messages="$errors->get('last_name')" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" class="w-full" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password" class="w-full" required />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="w-full" required />
                    </div>

                    <!-- Hidden Full Name Field (Auto-filled with JS) -->
                    <input type="hidden" id="name" name="name" value="{{ old('name') }}">
                </div>
            </div>

            <!-- Access & Permissions -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Access & Permissions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Roles -->
                    <div>
                        <x-input-label for="roles" :value="__('Roles')" />
                        <select name="roles[]" id="roles" class="w-full rounded-lg border-gray-300" multiple required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('roles')" />
                    </div>

                    <!-- Companies -->
                    <div>
                        <x-input-label for="companies" :value="__('Company')" />
                        <input type="text" id="search-company" class="w-full border-gray-300 rounded-lg mb-2" placeholder="Search a company">
                        <select name="companies[]" id="companies" class="w-full rounded-lg border-gray-300" multiple>
                            @foreach ($userCompany as $company)
                                <option value="{{ $company->id }}" {{ in_array($company->id, old('companies', [])) ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('companies')" />
                    </div>

                     <!-- Offices -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Offices</h3>
                <select name="offices[]" id="offices" class="w-full rounded-lg border-gray-300" multiple>
                    <option value="">{{ __('No Office Assigned') }}</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}" {{ in_array($office->id, old('offices', [])) ? 'selected' : '' }}>
                            {{ $office->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('offices')" />
            </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <x-primary-button class="px-6 py-3 bg-[#4285F4] hover:bg-[#4285F4]/90">
                    <i class="fas fa-save mr-2"></i>{{ __('Create User') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function filterDropdown(searchInput, dropdown) {
            const filter = searchInput.value.toLowerCase();
            Array.from(dropdown.options).forEach(option => {
                option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
            });
        }

        // Auto-fill hidden name field
        const firstName = document.getElementById('first_name');
        const middleName = document.getElementById('middle_name');
        const lastName = document.getElementById('last_name');
        const nameField = document.getElementById('name');

        function updateFullName() {
            nameField.value = `${firstName.value} ${middleName.value} ${lastName.value}`.trim();
        }

        firstName.addEventListener('input', updateFullName);
        middleName.addEventListener('input', updateFullName);
        lastName.addEventListener('input', updateFullName);

        // Company search filter
        const searchCompany = document.getElementById('search-company');
        const companiesSelect = document.getElementById('companies');
        searchCompany.addEventListener('input', () => filterDropdown(searchCompany, companiesSelect));

        // Initialize select2 if available
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#roles, #companies, #offices').select2({
                theme: 'classic',
                width: '100%'
            });
        }
    });
</script>
@endsection
