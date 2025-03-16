@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8 bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-edit text-[#4285F4] mr-2"></i>{{ __('Edit Office') }}
            </h2>
            <a href="{{ route('offices.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Offices') }}
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6 space-y-6">
                <form action="{{ route('offices.update', $office->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Office Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            {{ __('Office Name') }}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $office->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                            placeholder="{{ __('Enter office name') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Office -->
                    <div class="space-y-2">
                        <label for="parent_office_id" class="block text-sm font-medium text-gray-700">
                            {{ __('Parent Office') }}
                        </label>
                        <select id="parent_office_id" name="parent_office_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4285F4] focus:ring focus:ring-[#4285F4] focus:ring-opacity-50 @error('parent_office_id') border-red-500 @enderror">
                            <option value="">{{ __('None') }}</option>
                            @foreach($offices as $parentOffice)
                                @if($parentOffice->id != $office->id)
                                    <option value="{{ $parentOffice->id }}" {{ old('parent_office_id', $office->parent_office_id) == $parentOffice->id ? 'selected' : '' }}>
                                        {{ $parentOffice->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('parent_office_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('offices.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-[#4285F4] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4285F4]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4285F4] transition ease-in-out duration-150">
                            <i class="fas fa-save mr-2"></i>{{ __('Update Office') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection