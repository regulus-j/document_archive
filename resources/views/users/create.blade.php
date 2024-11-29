@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold">Create New User</h2>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left mr-2"></i> Back
      </a>
    </div>
  
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
      <strong>Whoops!</strong> There were some problems with your input.
      <ul class="mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
  
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <x-input-label for="first_name" :value="__('First Name')" />
          <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
          <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="middle_name" :value="__('Middle Name')" />
          <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" autocomplete="middle_name" />
          <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="last_name" :value="__('Last Name')" />
          <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
          <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        {{-- <div>
          <x-input-label for="password" :value="__('Password')" />
          <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
          <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
          <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div> --}}
        <div>
          <x-input-label for="roles" :value="__('Role')" />
          <select name="roles[]" id="roles" class="block mt-1 w-full" multiple>
            @foreach ($roles as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('roles')" class="mt-2" />
        </div>
      </div>
  
      <div class="flex justify-end mt-6">
        <x-primary-button>
          <i class="fa-solid fa-floppy-disk mr-2"></i> {{ __('Submit') }}
        </x-primary-button>
      </div>
    </form>
  </div>
@endsection