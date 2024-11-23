@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Role Management</h2>
            @can('role-create')
                <a href="{{ route('roles.create') }}" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded transition-colors">
                    <i class="fa fa-plus mr-2"></i> Create New Role
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Role Management</h3>
            <p class="mt-1 text-sm text-gray-500">Manage the roles and permissions for users.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ ++$i }}</td>
                        <td class="px-6 py-4">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('roles.show', $role->id) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fa-solid fa-list mr-1"></i> Show
                            </a>
                            @can('role-edit')
                                <a href="{{ route('roles.edit', $role->id) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                            @endcan
                            @can('role-delete')
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center text-red-600 hover:text-red-800"
                                        onclick="return confirm('Are you sure you want to delete this role?')">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {!! $roles->links() !!}
    </div>
</div>
@endsection