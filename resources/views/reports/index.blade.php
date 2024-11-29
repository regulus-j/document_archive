@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Report Management</h2>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded transition-colors">
                <i class="fa fa-plus mr-2"></i> Create New Report
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex gap-6 bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Left Panel -->
        <div class="w-72 bg-gray-50 p-6 border-r border-gray-200">
            <div class="space-y-2 mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Create a Report</h3>
                <p class="text-sm text-gray-500">Create a custom report based on collected data.</p>
            </div>
            
            <form action="/reports/generate" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label for="report_type" class="text-sm text-gray-600">Choose report type</label>
                    <select 
                        name="report_type"
                        id="report_type"
                        class="w-full rounded-md border border-gray-300 p-2 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="sales">Sales Report</option>
                        <option value="inventory">Inventory Report</option>
                        <option value="customer">Customer Report</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm text-gray-600">Choose range</label>
                    <div class="flex flex-wrap gap-2 items-center">
                        <div class="relative flex-1 min-w-[120px]">
                            <input 
                                type="date" 
                                name="start_date"
                                class="w-full rounded-md border border-gray-300 p-2 pl-8 bg-gray-50"
                                placeholder="From"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <span class="text-gray-400">to</span>
                        <div class="relative flex-1 min-w-[120px]">
                            <input 
                                type="date" 
                                name="end_date"
                                class="w-full rounded-md border border-gray-300 p-2 pl-8 bg-gray-50"
                                placeholder="To"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white rounded-md py-2 px-4 flex items-center justify-center gap-2 transition-colors">
                    <span class="text-lg">+</span> Generate Report
                </button>
            </form>
        </div>

        <!-- Right Panel -->
        <div class="flex-1 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Generated Report Preview</h3>
            <div class="h-64 bg-gray-50 rounded-md border border-gray-200"></div>
        </div>
    </div>
</div>
@endsection