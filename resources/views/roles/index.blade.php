@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50/30 p-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-4 mt-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 p-3 rounded-lg shadow">
                    <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 4.354a4 4 0 110 5.292V4.354zM15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197L15 21zM13 7a4 4 0 11-8 0 4 4 0 018 0z"
                            fill="currentColor" />
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-gray-900">{{ __('Role Management') }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                @can('role-create')
                    <a href="{{ route('roles.create') }}"
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6V18M18 12H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Create New Role
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Search/Filter Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 p-6 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" placeholder="Search by name"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="rounded-md bg-[#0066FF]/10 p-4 mb-8 shadow-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-[#0066FF]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-[#0066FF]">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NO</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAME</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        PERMISSIONS</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ACTION
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($roles as $key => $role)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ++$i }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow">
                                {{ count($role->permissions) }} Permissions
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-3">
                            <a href="{{ route('roles.show', $role->id) }}"
                                class="text-blue-600 hover:text-blue-900 hover:underline">View</a>
                            @can('role-edit')
                                <a href="{{ route('roles.edit', $role->id) }}"
                                    class="text-blue-600 hover:text-blue-900 hover:underline">Edit</a>
                            @endcan
                            @can('role-delete')
                                <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this role?')">
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 shadow-md">
        {{ $roles->links() }}
    </div>
</div>
@endsection