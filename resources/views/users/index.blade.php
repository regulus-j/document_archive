@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('User Management') }}</h2>
            <a class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded transition-colors" href="{{ route('users.create') }}">
                <i class="fa fa-plus mr-2"></i> Create New User
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <form method="POST" action="{{ route('users.search') }}" class="space-y-4">
                @csrf
                <div class="flex space-x-4">
                    <input type="text" name="name" placeholder="Name" value="{{ request('name') }}" class="form-input w-full">
                    <input type="text" name="email" placeholder="Email" value="{{ request('email') }}" class="form-input w-full">
                    <select name="role" class="form-select w-full">
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-4 rounded transition-colors">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Roles</th>
                        <th scope="col" class="px-6 py-3">Office</th>
                        <th scope="col" class="px-6 py-3 text-right" width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ ++$i }}</td>
                            <td class="px-6 py-4">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @foreach ($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($user->offices as $office)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $office->name }}</span>
                                @endforeach
                            <td class="px-6 py-4 text-right space-x-2">
                                <a class="inline-flex items-center text-blue-600 hover:text-blue-800" href="{{ route('users.show', $user->id) }}">
                                    <i class="fa-solid fa-list mr-1"></i> Show
                                </a>
                                <a class="inline-flex items-center text-blue-600 hover:text-blue-800" href="{{ route('users.edit', $user->id) }}">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fa-solid fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {!! $users->links() !!}
    </div>
</div>

@endsection