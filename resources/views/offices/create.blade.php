@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8 bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-plus-circle text-[#4285F4] mr-2"></i>{{ __('Create New Office') }}
            </h2>
            <a href="{{ route('offices.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#0066FF] hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF]">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Offices') }}
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6 space-y-6">
                <form method="POST" action="{{ route('offices.store') }}">
                    @csrf

                    <!-- Office Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            {{ __('Office Name') }}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50"
                            placeholder="{{ __('Enter office name') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Office -->
                    <!-- Parent Office -->
                    <div class="space-y-2">
                        <label for="parent_office_id" class="block text-sm font-medium text-gray-700">
                            {{ __('Parent Office') }}
                        </label>
                        <select id="parent_office_id" name="parent_office_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50 @error('parent_office_id') border-red-500 @enderror">
                            <option value="">{{ __('None') }}</option>
                            @foreach($offices as $id => $name)
                                <option value="{{ $id }}" {{ old('parent_office_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_office_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="user_id" class="block text-sm font-medium text-gray-700">Assign User</label>
<select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
    <option value="">No user assigned</option>
    @foreach($users as $user)
        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
    @endforeach
</select>


                  

                                        <!-- Company (Hidden for Admins under a Company) -->
                    @if(is_null($adminCompanyId)) 
                        <div class="space-y-2">
                            <label for="company_id" class="block text-sm font-medium text-gray-700">
                                {{ __('Company') }}
                            </label>
                            <select id="company_id" name="company_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50 @error('company_id') border-red-500 @enderror">
                                <option value="">{{ __('Select Company') }}</option>
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="company_id" value="{{ $adminCompanyId }}">
                    @endif



                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#0066FF] hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF]">
                            <i class="fas fa-save mr-2"></i>{{ __('Create Office') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection