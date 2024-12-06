@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0">
            <i class="fas fa-user text-[#4285F4] mr-2"></i>{{ __('Show User') }}
        </h2>
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center px-4 py-2 bg-[#4285F4] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4285F4]/90 focus:outline-none focus:ring-2 focus:ring-[#4285F4] focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Users') }}
        </a>
    </div>

    <!-- User Details -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 space-y-6">
            <div class="flex flex-col space-y-2">
                <span class="text-sm font-medium text-gray-500">{{ __('Name') }}</span>
                <span class="text-lg font-semibold text-gray-900">{{ $user->name }}</span>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="text-sm font-medium text-gray-500">{{ __('Email') }}</span>
                <span class="text-lg font-semibold text-gray-900">{{ $user->email }}</span>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="text-sm font-medium text-gray-500">{{ __('Roles') }}</span>
                <div class="flex flex-wrap gap-2">
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $role)
                            <span class="px-2 py-1 text-xs font-medium text-white bg-[#4285F4] rounded-full">
                                {{ $role }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500 italic">{{ __('No roles assigned') }}</span>
                    @endif
                </div>
            </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
    </div>

    <!-- Additional Actions -->
    <div class="mt-8 flex justify-end space-x-4">
        <a href="{{ route('users.edit', $user->id) }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-edit mr-2"></i> {{ __('Edit') }}
        </a>
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                onclick="return confirm('Are you sure you want to delete this user?');">
                <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete') }}
            </button>
        </form>
    </div>
</div>
@endsection