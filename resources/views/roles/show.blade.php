@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Role Details
            </h1>
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Roles
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Role Name</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $role->name }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Permissions</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($rolePermissions as $permission)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-gray-500 italic">No permissions assigned</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection