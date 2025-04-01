@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ __('Create New User') }}</h1>
                        <p class="text-sm text-gray-500">Add a new user to the system</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back to Users') }}
                    </a>
                </div>
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
                        <h3 class="text-sm font-medium text-red-800">{{ __('Whoops! Something went wrong.') }}</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100 mb-8">
            <div class="bg-white px-6 py-4 border-b border-blue-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-medium text-gray-800">User Information</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="p-6">
                @csrf

                <div class="space-y-8">
                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div class="space-y-2">
                                <label for="first_name"
                                    class="block text-sm font-medium text-gray-700">{{ __('First Name') }}</label>
                                <input id="first_name"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                                    autocomplete="given-name" />
                                @error('first_name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div class="space-y-2">
                                <label for="middle_name"
                                    class="block text-sm font-medium text-gray-700">{{ __('Middle Name') }}</label>
                                <input id="middle_name"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    type="text" name="middle_name" value="{{ old('middle_name') }}"
                                    autocomplete="additional-name" />
                                @error('middle_name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-2">
                                <label for="last_name"
                                    class="block text-sm font-medium text-gray-700">{{ __('Last Name') }}</label>
                                <input id="last_name"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    type="text" name="last_name" value="{{ old('last_name') }}" required
                                    autocomplete="family-name" />
                                @error('last_name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                <input id="email"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    type="email" name="email" value="{{ old('email') }}" required autocomplete="email" />
                                @error('email')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Access & Permissions Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Access & Permissions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Roles -->
                            <div class="space-y-2">
                                <label for="roles" class="block text-sm font-medium text-gray-700">{{ __('Roles') }}</label>
                                <div class="relative">
                                    <select name="roles[]" id="roles"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        multiple>
                                        @foreach ($roles as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('roles')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Select one or more roles for this user</p>
                            </div>

                            <!-- Companies -->
                            <input type="hidden" name="companies" value={{ auth()->user()->company()->first()->id}}>
                            {{-- <div class="space-y-2">
                                <label for="companies"
                                    class="block text-sm font-medium text-gray-700">{{ __('Company') }}</label>
                                <div class="relative">
                                    <input type="text" id="search-company"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all mb-2"
                                        placeholder="Search a company">
                                    <select name="companies" id="companies"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        multiple>
                                        @foreach ($userCompany as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('companies')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Select the company this user belongs to</p>
                            </div> --}}

                            <!-- Offices -->
                            <div class="space-y-2 md:col-span-2">
                                <label for="offices"
                                    class="block text-sm font-medium text-gray-700">{{ __('Offices') }}</label>
                                <div class="relative">
                                    <input type="text" id="search-office"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all mb-2"
                                        placeholder="Search an office">
                                    <select name="offices[]" id="offices"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        multiple>
                                        @foreach ($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('offices')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Select one or more offices this user will have access
                                    to</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end pt-4">
                        <a href="{{ route('users.index') }}"
                            class="text-sm text-gray-700 hover:text-gray-500 mr-4 transition-colors">Cancel</a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            {{ __('Create User') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchOffice = document.getElementById('search-office');
            const officesSelect = document.getElementById('offices');

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

            // Initialize select2 for multiple selects if available
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('#roles, #offices').select2({
                    theme: 'classic',
                    width: '100%'
                });

                // Integrate select2 with the search functionality
                $('#offices').on('select2:open', function () {
                    setTimeout(function () {
                        $('.select2-search__field').on('input', function () {
                            filterOffices();
                        });
                    }, 0);
                });
            } else {
                console.warn('Select2 is not available. Falling back to native select elements.');
            }
        });

        const searchCompany = document.getElementById('search-company');
        const companiesSelect = document.getElementById('companies');

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

        // Initialize select2 for companies if available
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#companies').select2({
                theme: 'classic',
                width: '100%'
            });

            // Integrate select2 with the search functionality
            $('#companies').on('select2:open', function () {
                setTimeout(function () {
                    $('.select2-search__field').on('input', function () {
                        filterCompanies();
                    });
                }, 0);
            });
        }
    </script>
@endsection