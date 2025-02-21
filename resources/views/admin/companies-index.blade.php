@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Company Management</h1>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Company List</h2>
                </div>

                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    ID
                                </th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Company Name
                                </th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Owner
                                </th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Status
                                </th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Plan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ $company['id'] }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ $company['name'] }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ $company['owner'] }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                            {{ $company['status'] === 'Active' ? 'text-green-700 bg-green-100' : 
                                               ($company['status'] === 'Inactive' ? 'text-red-700 bg-red-100' : 
                                                'text-gray-700 bg-gray-100') }}">
                                            {{ $company['status'] }}
                                        </span>
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">
                                            {{ $company['plan'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection