@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ __('User Management') }}</h1>
                        <p class="text-sm text-gray-500">Manage user accounts and permissions</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-white border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Users Card -->
            <div
                class="flex items-center p-6 bg-white rounded-xl shadow-xl border border-blue-100 hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                <div class="p-3 mr-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-700">{{ $users->total() }}</p>
                </div>
            </div>

            <!-- Active Users Card -->
            <div
                class="flex items-center p-6 bg-white rounded-xl shadow-xl border border-blue-100 hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                <div class="p-3 mr-4 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-3xl font-bold text-gray-700">{{ $users->count() }}</p>
                </div>
            </div>

            <!-- Filter Card -->
            <div
                class="p-6 bg-white rounded-xl shadow-xl border border-blue-100 hover:shadow-2xl transition-shadow duration-300">
                <form action="" method="GET" class="flex flex-col h-full">
                    <h3 class="text-sm font-medium text-gray-600 mb-3">Quick Filter</h3>
                    <div class="flex-1">
                        <select name="role"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">All Roles</option>
                            @if(isset($roles))
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mt-4">
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-colors">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                            Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-blue-100">
            <div class="bg-white flex items-center justify-between px-6 py-4 border-b border-blue-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197L15 21zM13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">User List</h3>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative hidden md:block">
                        <input type="text"
                            class="w-64 pl-10 pr-4 py-2 text-sm bg-white border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            placeholder="Search users...">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-blue-400" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                    </div>

                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                NO
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                NAME
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                EMAIL
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                COMPANY
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                PLAN
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                ROLES
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                STATUS
                            </th>
                            <th scope="col"
                                class="bg-white px-6 py-3 text-right text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                ACTION
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $key => $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ++$i }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->first_name }}
                                                {{ $user->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">Joined
                                                {{ $user->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach($user->companies as $company)
                                        <div class="text-sm text-gray-900">{{ $company->company_name }}</div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach($user->companies as $company)
                                        @foreach($company->subscriptions as $subscription)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                {{ $subscription->plan->plan_name }}
                                            </span>
                                        @endforeach
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach($user->roles as $role)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('users.show', $user->id) }}"
                                            class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if(count($users) == 0)
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197L15 21zM13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                    <div class="mt-6">
                        <a href="{{ route('users.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Create New User
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>

        <!-- Pagination Info -->
        <div class="mt-4 text-sm text-gray-600">
            <div>Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span
                    class="font-medium">{{ $users->lastItem() }}</span> of <span
                    class="font-medium">{{ $users->total() }}</span> users</div>
        </div>
    </div>
@endsection