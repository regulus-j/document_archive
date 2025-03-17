@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">User Management</h1>

            </div>

            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-blue-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Registered Users</h2>
                        <div class="flex space-x-2">
                            <div class="relative">
                                <input type="text" placeholder="Search users..."
                                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute left-3 top-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        No</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Name</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Email</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Company</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Plan</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Roles</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Status</th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ ++$i }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
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
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                            @foreach($user->companies as $company)
                                                <div class="text-sm text-gray-900">{{ $company->company_name }}</div>
                                            @endforeach
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                            @foreach($user->companies as $company)
                                                @foreach($company->subscriptions as $subscription)
                                                    <span
                                                        class="px-2 py-1 font-semibold leading-tight text-emerald-700 bg-emerald-100 rounded-full text-xs">
                                                        {{ $subscription->plan->plan_name }}
                                                    </span>
                                                @endforeach
                                            @endforeach
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                            @foreach($user->roles as $role)
                                                <span
                                                    class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full text-xs">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">
                                                Active
                                            </span>
                                        </td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('users.show', $user->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-md hover:bg-blue-100 transition-colors">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-1.5 rounded-md hover:bg-indigo-100 transition-colors">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-rose-600 hover:text-rose-900 bg-rose-50 p-1.5 rounded-md hover:bg-rose-100 transition-colors"
                                                        onclick="return confirm('Are you sure you want to delete this user?');">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
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

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                    <div class="mt-6 flex justify-between items-center text-sm text-gray-600">
                        <div>Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span
                                class="font-medium">{{ $users->lastItem() }}</span> of <span
                                class="font-medium">{{ $users->total() }}</span> users</div>
                        <div class="flex items-center space-x-2">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection