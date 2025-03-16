@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0">
            <i class="fas fa-building text-[#4285F4] mr-2"></i>{{ __('Office Details') }}
        </h2>
        <a href="{{ route('offices.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Offices') }}
        </a>
    </div>

    <!-- Office Information -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div class="space-y-2">
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $office->name }}</h3>
                    <p class="text-gray-600">
                        @if ($office->parentOffice)
                            {{ __('Parent Office') }}: {{ $office->parentOffice->name }}
                        @else
                            <span class="text-gray-400">{{ __('Parent Office') }}: {{ __('N/A') }}</span>
                        @endif
                    </p>
                    <p class="text-gray-600">{{ __('Created At') }}: {{ $office->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-gray-600">{{ __('Updated At') }}: {{ $office->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('offices.edit', $office->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-[#4285F4] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4285F4]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4285F4] transition ease-in-out duration-150">
                        <i class="fas fa-edit mr-2"></i>{{ __('Edit') }}
                    </a>
                </div>
            </div>
            <div class="mt-8">
                <h4 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Users') }}</h4>
                @if ($office->users->isEmpty())
                    <p class="text-gray-400">{{ __('No users assigned to this office.') }}</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($office->users as $user)
                            <li class="py-3 flex items-center">

                                <div class="ml-3">
                                <a href="{{ route('users.show', $user->id) }}">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->first_name . ' ' . $user->last_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </a>    
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection