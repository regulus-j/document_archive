@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Edit User</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Validation Errors -->
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

    <!-- Form -->
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <!-- Input Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" 
                              type="text" name="first_name" 
                              :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Middle Name -->
            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" />
                <x-text-input id="middle_name" class="block mt-1 w-full" 
                              type="text" name="middle_name" 
                              :value="old('middle_name', $user->middle_name)" autocomplete="middle_name" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" 
                              type="text" name="last_name" 
                              :value="old('last_name', $user->last_name)" required autocomplete="last_name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" 
                              type="email" name="email" 
                              :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Roles -->
            <div>
                <x-input-label for="roles" :value="__('Role')" />
                <select name="roles[]" id="roles" class="block mt-1 w-full" multiple>
                    @foreach ($roles as $value => $label)
                    <option value="{{ $value }}" {{ in_array($value, $userRoles) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('roles')" class="mt-2" />
            </div>

            <!-- Offices -->
            <div class="space-y-4">
              <div>
                <x-input-label for="offices" :value="__('Office')" class="block text-sm font-medium text-gray-700" />
                <div>
                  <input type="text" id="search-office" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Search an office">
                </div>
                <select name="offices[]" id="offices" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" multiple>
                  @foreach ($offices as $value => $label)
                  <option value="{{ $value }}" {{ in_array($value, $userOffices) ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <x-input-error :messages="$errors->get('offices')" class="text-red-600 text-sm" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <x-primary-button>
                <i class="fa-solid fa-floppy-disk mr-2"></i> {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
</div>

<script>
  document.getElementById('search-office').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    var options = document.getElementById('offices').options;
    
    for (var i = 0; i < options.length; i++) {
      var option = options[i];
      var text = option.text.toLowerCase();
      option.style.display = text.includes(filter) ? '' : 'none';
    }
  });
</script>

@endsection
