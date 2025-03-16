@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-4xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit User') }}</h1>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">{{ __('Whoops! Something went wrong.') }}</p>
                <ul class="mt-3 list-disc list-inside text-sm">S
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="name" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="text-sm" />
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="email" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="email" name="email" :value="old('email', $user->email)" required autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="text-sm" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="password" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="password" name="password" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="password_confirmation" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-[#4285F4] focus:border-transparent transition-all"
                        type="password" name="password_confirmation" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-sm" />
                </div>

                <!-- Roles -->
                <div class="space-y-2">
                    <x-input-label for="roles" :value="__('Roles')" class="text-sm font-medium text-gray-700" />
                    <select name="roles[]" id="roles" multiple
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#4285F4] focus:border-[#4285F4] sm:text-sm">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('roles')" class="text-sm" />
                </div>
            </div>

            

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <x-primary-button class="px-6 py-3 bg-[#4285F4] hover:bg-[#4285F4]/90 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>{{ __('Update User') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection