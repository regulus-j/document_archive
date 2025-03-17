@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-b from-blue-50 to-white min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Company Management</h1>

            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-blue-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Company List</h2>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        ID
                                    </th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Company Name
                                    </th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Owner
                                    </th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Status
                                    </th>
                                    <th
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 border-b border-blue-200 px-6 py-3 text-blue-700 font-bold tracking-wider uppercase text-xs">
                                        Plan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $company)
                                                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                                            <td class="border-dashed border-t border-gray-200 px-6 py-4 text-sm text-gray-700">
                                                                {{ $company['id'] }}
                                                            </td>
                                                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                                                <div class="flex items-center">
                                                                    <div
                                                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                                                                        {{ substr($company['name'], 0, 1) }}
                                                                    </div>
                                                                    <div class="ml-4">
                                                                        <div class="text-sm font-medium text-gray-900">{{ $company['name'] }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                                                <div class="text-sm text-gray-900">{{ $company['owner'] }}</div>
                                                            </td>
                                                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                                                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full 
                                                                                                    {{ $company['status'] === 'Active' ? 'text-emerald-700 bg-emerald-100' :
                                    ($company['status'] === 'Inactive' ? 'text-rose-700 bg-rose-100' :
                                        'text-gray-700 bg-gray-100') }}">
                                                                    {{ $company['status'] }}
                                                                </span>
                                                            </td>
                                                            <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                                                <span
                                                                    class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">
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