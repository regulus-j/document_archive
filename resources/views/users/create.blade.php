@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0">
            <i class="fas fa-user-plus text-[#4285F4] mr-2"></i>{{ __('Create New User') }}
        </h2>
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center px-4 py-2 bg-[#4285F4] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4285F4]/90 focus:outline-none focus:ring-2 focus:ring-[#4285F4] focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Users') }}
        </a>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">{{ __('Whoops! Something went wrong.') }}</p>
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('users.store') }}" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')"
                    class="block text-sm font-medium text-gray-700" />
                <x-text-input id="first_name"
                    class="mt-1 block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                    type="text" name="first_name" :value="old('first_name')" required autofocus
                    autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Middle Name -->
            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')"
                    class="block text-sm font-medium text-gray-700" />
                <x-text-input id="middle_name"
                    class="mt-1 block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                    type="text" name="middle_name" :value="old('middle_name')" autocomplete="additional-name" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')"
                    class="block text-sm font-medium text-gray-700" />
                <x-text-input id="last_name"
                    class="mt-1 block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                    type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700" />
                <x-text-input id="email"
                    class="mt-1 block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                    type="email" name="email" :value="old('email')" required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Roles -->
            <div>
                <x-input-label for="roles" :value="__('Roles')" class="block text-sm font-medium text-gray-700" />
                <div class="mt-1 relative">
                    <select name="roles[]" id="roles"
                        class="block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                        multiple>
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('roles')" class="mt-2" />
            </div>

            <!-- Offices -->
            <div>
                <x-input-label for="offices" :value="__('Offices')" class="block text-sm font-medium text-gray-700" />
                <div class="mt-1 relative">
                    <input type="text" id="search-office"
                        class="block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50 mb-2"
                        placeholder="Search an office">
                    <select name="offices[]" id="offices"
                        class="block w-full rounded-md border-[#4285F4] shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                        multiple>
                        @foreach ($offices as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('offices')" class="mt-2" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <x-primary-button class="ml-3 bg-[#4285F4] hover:bg-[#4285F4]/90">
                <i class="fas fa-save mr-2"></i>{{ __('Create User') }}
            </x-primary-button>
        </div>
    </form>
</div>

@push('scripts')
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
    </script>
@endpush

@endsection