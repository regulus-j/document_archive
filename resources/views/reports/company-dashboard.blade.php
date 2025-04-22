<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ $company->company_name }} - {{ __('Company Performance Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="mx-auto w-4/5 sm:px-6 lg:px-8 space-y-8">
            <!-- Date Range Filter -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="flex items-center border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Date Filter</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('reports.company-dashboard') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center">
                                <label for="start_date" class="mr-2 whitespace-nowrap text-sm font-medium">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="rounded border border-gray-300 px-3 py-2 text-sm" value="{{ $startDate }}">
                            </div>
                            <div class="flex items-center">
                                <label for="end_date" class="mr-2 whitespace-nowrap text-sm font-medium">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="rounded border border-gray-300 px-3 py-2 text-sm" value="{{ $endDate }}">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">Apply Filter</button>
                            
                            <!-- Export Buttons -->
                            <a href="{{ route('reports.company-dashboard', ['start_date' => $startDate, 'end_date' => $endDate, 'export_format' => 'pdf']) }}" 
                               class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export to PDF
                            </a>
                        </div>
                        
                        <div class="flex ml-auto mt-4 sm:mt-0">
                            <a href="{{ route('reports.company-dashboard', ['start_date' => now()->subMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                               class="px-3 py-2 text-sm border border-gray-300 rounded-l bg-gray-50 hover:bg-gray-100">Last Month</a>
                            <a href="{{ route('reports.company-dashboard', ['start_date' => now()->subMonths(3)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                               class="px-3 py-2 text-sm border-t border-b border-gray-300 bg-gray-50 hover:bg-gray-100">Last 3 Months</a>
                            <a href="{{ route('reports.company-dashboard', ['start_date' => now()->subYear()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                               class="px-3 py-2 text-sm border border-gray-300 rounded-r bg-gray-50 hover:bg-gray-100">Last Year</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Documents</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $storageMetrics['document_count'] }}</dd>
                                <dd class="text-sm text-gray-500">With {{ $storageMetrics['attachment_count'] }} attachments</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Storage</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $storageMetrics['formatted_total_size'] }}</dd>
                                <dd class="text-sm text-gray-500">Avg: {{ $storageMetrics['formatted_average_size'] }}/doc</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-yellow-400 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Users</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ count($companyUsers) }}</dd>
                                <dd class="text-sm text-gray-500">Across {{ count($companyOffices) }} offices</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Processed</dt>
                                @php
                                    $totalProcessed = array_sum(array_column($userPerformanceMetrics, 'processed_count'));
                                @endphp
                                <dd class="text-3xl font-semibold text-gray-900">{{ $totalProcessed }}</dd>
                                <dd class="text-sm text-gray-500">In selected date range</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Volume Trend Chart - Keeping existing chart but updating container -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex items-center border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Document Volume Trends</h3>
                </div>
                <div class="p-6">
                    <canvas id="documentTrendsChart" class="h-96 w-full"></canvas>
                </div>
            </div>

            <!-- Document Categories and Status -->
            <div class="flex flex-wrap -mx-3 mb-8">
                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 border-b">
                            <h5 class="font-bold">Document Categories</h5>
                        </div>
                        <div class="p-4">
                            <canvas id="categoriesChart" class="h-64 w-full"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 border-b">
                            <h5 class="font-bold">Document Status Distribution</h5>
                        </div>
                        <div class="p-4">
                            <canvas id="statusChart" class="h-64 w-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Performance Section -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center">
                    <h5 class="font-bold">User Performance</h5>
                    <a href="{{ route('reports.company-dashboard', ['start_date' => $startDate, 'end_date' => $endDate, 'export_table' => 'user_performance']) }}" 
                       class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export to Excel
                    </a>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-800 text-white">
                                    <th class="px-4 py-2 text-left">User</th>
                                    <th class="px-4 py-2 text-left">Uploads</th>
                                    <th class="px-4 py-2 text-left">Forwarded</th>
                                    <th class="px-4 py-2 text-left">Processed</th>
                                    <th class="px-4 py-2 text-left">Avg Response Time</th>
                                    <th class="px-4 py-2 text-left">Avg Processing Time</th>
                                    <th class="px-4 py-2 text-left">Approval Rate</th>
                                    <th class="px-4 py-2 text-left">Performance Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userPerformanceMetrics as $metric)
                                    <tr class="border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="px-4 py-2">{{ $metric['user']->first_name }} {{ $metric['user']->last_name }}</td>
                                        <td class="px-4 py-2">{{ $metric['uploads_count'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['forwarded_count'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['processed_count'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['avg_response_time'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['avg_processing_time'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['approval_rate'] }}%</td>
                                        <td class="px-4 py-2">
                                            <div class="w-full bg-gray-200 rounded-full h-4">
                                                <div class="{{ $metric['performance_score'] >= 70 ? 'bg-green-500' : ($metric['performance_score'] >= 40 ? 'bg-yellow-400' : 'bg-red-500') }} h-4 rounded-full" 
                                                    style="width: {{ $metric['performance_score'] }}%;">
                                                    <span class="text-xs text-white text-center block leading-4">{{ $metric['performance_score'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Office Performance Section -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center">
                    <h5 class="font-bold">Office Performance</h5>
                    <a href="{{ route('reports.company-dashboard', ['start_date' => $startDate, 'end_date' => $endDate, 'export_table' => 'office_performance']) }}" 
                       class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export to Excel
                    </a>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-800 text-white">
                                    <th class="px-4 py-2 text-left">Office</th>
                                    <th class="px-4 py-2 text-left">Users</th>
                                    <th class="px-4 py-2 text-left">Documents Originated</th>
                                    <th class="px-4 py-2 text-left">Documents Received</th>
                                    <th class="px-4 py-2 text-left">Workflows Processed</th>
                                    <th class="px-4 py-2 text-left">Avg Processing Time</th>
                                    <th class="px-4 py-2 text-left">Efficiency Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($officePerformanceMetrics as $metric)
                                    <tr class="border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="px-4 py-2">{{ $metric['office']->name }}</td>
                                        <td class="px-4 py-2">{{ $metric['user_count'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['documents_originated'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['documents_received'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['workflows_processed'] }}</td>
                                        <td class="px-4 py-2">{{ $metric['avg_processing_time'] }}</td>
                                        <td class="px-4 py-2">
                                            <div class="w-full bg-gray-200 rounded-full h-4">
                                                <div class="{{ $metric['efficiency_score'] >= 70 ? 'bg-green-500' : ($metric['efficiency_score'] >= 40 ? 'bg-yellow-400' : 'bg-red-500') }} h-4 rounded-full" 
                                                    style="width: {{ $metric['efficiency_score'] }}%;">
                                                    <span class="text-xs text-white text-center block leading-4">{{ $metric['efficiency_score'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Storage Usage -->
            <div class="flex flex-wrap -mx-3 mb-8">
                <!-- User Storage Usage -->
                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 border-b">
                            <h5 class="font-bold">Storage Usage by User</h5>
                        </div>
                        <div class="p-4">
                            <canvas id="userStorageChart" class="h-64 w-full"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Office Storage Usage -->
                <div class="w-full md:w-1/2 px-3">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 border-b">
                            <h5 class="font-bold">Storage Usage by Office</h5>
                        </div>
                        <div class="p-4">
                            <canvas id="officeStorageChart" class="h-64 w-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Storage Tables -->
            <div class="flex flex-wrap -mx-3 mb-8">
                <!-- User Storage Details -->
                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center">
                            <h5 class="font-bold">User Storage Details</h5>
                            <a href="{{ route('reports.company-dashboard', ['start_date' => $startDate, 'end_date' => $endDate, 'export_table' => 'user_storage']) }}" 
                               class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export to Excel
                            </a>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left">User</th>
                                            <th class="px-4 py-2 text-left">Documents</th>
                                            <th class="px-4 py-2 text-left">Storage Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($storageMetrics['user_storage'] as $userStorage)
                                            <tr class="border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                                <td class="px-4 py-2">{{ $userStorage['user']->first_name }} {{ $userStorage['user']->last_name }}</td>
                                                <td class="px-4 py-2">{{ $userStorage['count'] }}</td>
                                                <td class="px-4 py-2">{{ $userStorage['formatted_size'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Office Storage Details -->
                <div class="w-full md:w-1/2 px-3">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center">
                            <h5 class="font-bold">Office Storage Details</h5>
                            <a href="{{ route('reports.company-dashboard', ['start_date' => $startDate, 'end_date' => $endDate, 'export_table' => 'office_storage']) }}" 
                               class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export to Excel
                            </a>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Office</th>
                                            <th class="px-4 py-2 text-left">Documents</th>
                                            <th class="px-4 py-2 text-left">Storage Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($storageMetrics['office_storage'] as $officeStorage)
                                            <tr class="border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                                <td class="px-4 py-2">{{ $officeStorage['office']->name }}</td>
                                                <td class="px-4 py-2">{{ $officeStorage['count'] }}</td>
                                                <td class="px-4 py-2">{{ $officeStorage['formatted_size'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Recommendations -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
                <div class="bg-gray-100 px-4 py-3 border-b">
                    <h5 class="font-bold">Performance Recommendations</h5>
                </div>
                <div class="p-4">
                    <ul class="divide-y divide-gray-200">
                        @if(count($userPerformanceMetrics) > 0)
                            @php
                                $slowestUserKey = array_search(min(array_column($userPerformanceMetrics, 'performance_score')), array_column($userPerformanceMetrics, 'performance_score'));
                                $fastestUserKey = array_search(max(array_column($userPerformanceMetrics, 'performance_score')), array_column($userPerformanceMetrics, 'performance_score'));
                                $slowestUser = $userPerformanceMetrics[$slowestUserKey];
                                $fastestUser = $userPerformanceMetrics[$fastestUserKey];
                            @endphp

                            @if($slowestUser['performance_score'] < 50)
                                <li class="py-3 bg-yellow-50 px-4 rounded mb-2">
                                    Consider performance improvement training for {{ $slowestUser['user']->first_name }} {{ $slowestUser['user']->last_name }}, 
                                    who has the lowest performance score ({{ $slowestUser['performance_score'] }}).
                                </li>
                            @endif

                            @if($fastestUser['performance_score'] > 80)
                                <li class="py-3 bg-green-50 px-4 rounded mb-2">
                                    {{ $fastestUser['user']->first_name }} {{ $fastestUser['user']->last_name }} shows excellent performance 
                                    (score: {{ $fastestUser['performance_score'] }}). Consider them for best practices training for other team members.
                                </li>
                            @endif
                        @endif

                        @php
                            $totalStorage = $storageMetrics['total_size'];
                            $largeFiles = false;
                            foreach($storageMetrics['user_storage'] as $user) {
                                if($user['size'] > ($totalStorage * 0.3)) {
                                    $largeFileUser = $user;
                                    $largeFiles = true;
                                    break;
                                }
                            }
                        @endphp
                        
                        @if($largeFiles)
                            <li class="py-3 bg-yellow-50 px-4 rounded mb-2">
                                {{ $largeFileUser['user']->first_name }} {{ $largeFileUser['user']->last_name }} is using 
                                {{ round(($largeFileUser['size'] / $totalStorage) * 100) }}% of your total storage. 
                                Consider reviewing their document storage practices.
                            </li>
                        @endif

                        @if(count($documentTrends['document_counts']) > 1)
                            @php
                                $lastIndex = count($documentTrends['document_counts']) - 1;
                                $previousIndex = $lastIndex - 1;
                                $currentValue = $documentTrends['document_counts'][$lastIndex];
                                $previousValue = $documentTrends['document_counts'][$previousIndex];
                                $percentChange = $previousValue > 0 ? round((($currentValue - $previousValue) / $previousValue) * 100) : 0;
                            @endphp

                            @if($percentChange > 50)
                                <li class="py-3 bg-blue-50 px-4 rounded mb-2">
                                    Document volume increased by {{ $percentChange }}% compared to the previous period. 
                                    Ensure you have adequate storage capacity for continued growth.
                                </li>
                            @elseif($percentChange < -30)
                                <li class="py-3 bg-yellow-50 px-4 rounded mb-2">
                                    Document volume decreased by {{ abs($percentChange) }}% compared to the previous period. 
                                    This might indicate a process change or issue that needs investigation.
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Load Chart.js from CDN with version specified -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function to format bytes
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Document Trends Chart
        try {
            var trendsCtx = document.getElementById('documentTrendsChart');
            if (trendsCtx) {
                var trendsData = @json($documentTrends);
                if (trendsData && trendsData.months && trendsData.document_counts) {
                    new Chart(trendsCtx, {
                        type: 'line',
                        data: {
                            labels: trendsData.months,
                            datasets: [
                                {
                                    label: 'Documents Created',
                                    data: trendsData.document_counts,
                                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 2,
                                    tension: 0.3
                                },
                                {
                                    label: 'Workflows Created',
                                    data: trendsData.workflow_counts,
                                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                                    borderColor: 'rgba(239, 68, 68, 1)',
                                    borderWidth: 2,
                                    tension: 0.3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    console.error('Document trends data is invalid or missing');
                }
            }
        } catch (e) {
            console.error('Failed to create document trends chart:', e);
        }

        // Document Categories Chart
        try {
            var categoriesCtx = document.getElementById('categoriesChart');
            if (categoriesCtx) {
                var categoriesData = @json($categoryDistribution ?? []);
                if (categoriesData && categoriesData.length > 0) {
                    new Chart(categoriesCtx, {
                        type: 'doughnut',
                        data: {
                            labels: categoriesData.map(item => item.category),
                            datasets: [{
                                label: 'Document Categories',
                                data: categoriesData.map(item => item.count),
                                backgroundColor: [
                                    '#F87171', '#60A5FA', '#FBBF24', '#34D399', '#A78BFA', 
                                    '#FB923C', '#4ADE80', '#F87171', '#94A3B8', '#2DD4BF'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    });
                } else {
                    console.error('Category distribution data is empty');
                    document.querySelector('.p-4:has(#categoriesChart)').innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">No category data available</div>';
                }
            }
        } catch (e) {
            console.error('Failed to create categories chart:', e);
        }
        
        // Document Status Distribution Chart
        try {
            var statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                var statusData = @json($statusDistribution ?? []);
                if (statusData && statusData.length > 0) {
                    new Chart(statusCtx, {
                        type: 'pie',
                        data: {
                            labels: statusData.map(item => item.status),
                            datasets: [{
                                label: 'Document Status',
                                data: statusData.map(item => item.count),
                                backgroundColor: [
                                    '#4ADE80', '#FBBF24', '#F87171', '#60A5FA', '#A78BFA', 
                                    '#2DD4BF', '#FB923C', '#94A3B8'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    });
                } else {
                    console.error('Status distribution data is empty');
                    document.querySelector('.p-4:has(#statusChart)').innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">No status data available</div>';
                }
            }
        } catch (e) {
            console.error('Failed to create status chart:', e);
        }

        // User Storage Chart
        try {
            var userStorageCtx = document.getElementById('userStorageChart');
            if (userStorageCtx) {
                var userStorageData = @json($storageMetrics['user_storage'] ?? []);
                if (userStorageData && userStorageData.length > 0) {
                    new Chart(userStorageCtx, {
                        type: 'bar',
                        data: {
                            labels: userStorageData.map(item => item.user.first_name + ' ' + item.user.last_name),
                            datasets: [{
                                label: 'Storage Used',
                                data: userStorageData.map(item => item.size),
                                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatBytes(value);
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error('User storage data is empty');
                    document.querySelector('.p-4:has(#userStorageChart)').innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">No user storage data available</div>';
                }
            }
        } catch (e) {
            console.error('Failed to create user storage chart:', e);
        }

        // Office Storage Chart
        try {
            var officeStorageCtx = document.getElementById('officeStorageChart');
            if (officeStorageCtx) {
                var officeStorageData = @json($storageMetrics['office_storage'] ?? []);
                if (officeStorageData && officeStorageData.length > 0) {
                    new Chart(officeStorageCtx, {
                        type: 'bar',
                        data: {
                            labels: officeStorageData.map(item => item.office.name),
                            datasets: [{
                                label: 'Storage Used',
                                data: officeStorageData.map(item => item.size),
                                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatBytes(value);
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error('Office storage data is empty');
                    document.querySelector('.p-4:has(#officeStorageChart)').innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">No office storage data available</div>';
                }
            }
        } catch (e) {
            console.error('Failed to create office storage chart:', e);
        }
    });
</script>
