@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header Box -->
            <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
                <div class="bg-white p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit User') }}</h1>
                            <p class="text-sm text-gray-500">Update user information and permissions</p>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        {{ __('Back to Users') }}
                    </a>
                </div>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-white border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                <strong>{{ __('Whoops! Something went wrong.') }}</strong></p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100">
                <form method="POST" action="{{ route('users.update', $user->id) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')"
                                class="block text-sm font-medium text-gray-700 mb-1" />
                            <x-text-input id="first_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                type="text" name="first_name" :value="old('first_name', $user->first_name)" required
                                autofocus autocomplete="given-name" />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')"
                                class="block text-sm font-medium text-gray-700 mb-1" />
                            <x-text-input id="last_name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                type="text" name="last_name" :value="old('last_name', $user->last_name)" required
                                autocomplete="family-name" />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')"
                                class="block text-sm font-medium text-gray-700 mb-1" />
                            <x-text-input id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                type="email" name="email" :value="old('email', $user->email)" required
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Roles -->
                        <div>
                            <x-input-label for="roles" :value="__('Roles')"
                                class="block text-sm font-medium text-gray-700 mb-1" />
                            <div class="mt-1 relative">
                                <select name="roles[]" id="roles"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    multiple>
                                    @foreach ($roles as $value => $label)
                                        <option value="{{ $value }}" {{ in_array($value, $userRoles) ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Hold Ctrl (or Cmd) to select multiple roles</p>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>

                        <!-- Companies -->
                        @if(auth()->user()->isAdmin() || auth()->user()->hasRole('Admin'))
                            <div class="space-y-2">
                                <x-input-label for="companies" :value="__('Company')"
                                    class="block text-sm font-medium text-gray-700 mb-1" />
                                <div class="relative">
                                    <input type="text" id="search-company"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mb-2"
                                        placeholder="Search a company">
                                    <select name="companies[]" id="companies"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                        multiple>
                                        @foreach ($userCompany as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Hold Ctrl (or Cmd) to select multiple companies</p>
                                <x-input-error :messages="$errors->get('companies')" class="mt-2" />
                            </div>
                        @endif

                        <!-- Offices -->
                        @if (!$user->hasRole('Admin'))
                            <div>
                                <x-input-label for="offices" :value="__('Offices')"
                                    class="block text-sm font-medium text-gray-700 mb-1" />
                                <div class="mt-1 relative">
                                    <input type="text" id="search-office"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 mb-2"
                                        placeholder="Search an office">
                                    <select name="offices[]" id="offices"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                        multiple>
                                        @foreach ($offices as $value => $label)
                                            <option value="{{ $value }}" {{ in_array($value, $userOffices) ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Hold Ctrl (or Cmd) to select multiple offices</p>
                                <x-input-error :messages="$errors->get('offices')" class="mt-2" />
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Office search functionality
                const searchOffice = document.getElementById('search-office');
                const officesSelect = document.getElementById('offices');

                if (searchOffice && officesSelect) {
                    // Function to filter offices
                    function filterOffices() {
                        const filter = searchOffice.value.toLowerCase();
                        Array.from(officesSelect.options).forEach(option => {
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(filter) ? '' : 'none';
                        });
                    }

                    // Add event listener to search input
                    searchOffice.addEventListener('input', filterOffices);
                }

                // Company search functionality
                const searchCompany = document.getElementById('search-company');
                const companiesSelect = document.getElementById('companies');

                if (searchCompany && companiesSelect) {
                    // Function to filter companies
                    function filterCompanies() {
                        const filter = searchCompany.value.toLowerCase();
                        Array.from(companiesSelect.options).forEach(option => {
                            const text = option.text.toLowerCase();
                            option.style.display = text.includes(filter) ? '' : 'none';
                        });
                    }

                    // Add event listener to search input
                    searchCompany.addEventListener('input', filterCompanies);
                }
            });
        </script>
    @endpush
@endsection