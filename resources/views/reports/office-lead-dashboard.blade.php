<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ $office->name }} - {{ __('Office Dashboard') }}
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
                    <form action="{{ route('reports.office-dashboard') }}" method="GET" class="flex flex-wrap items-center gap-4">
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
                            <a href="{{ route('reports.office-dashboard', ['start_date' => $startDate, 'end_date' => $endDate, 'export_format' => 'pdf']) }}" 
                               class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export to PDF
                            </a>
                        </div>
                        
                        <div class="flex ml-auto mt-4 sm:mt-0">
                            <a href="{{ route('reports.office-dashboard', ['start_date' => now()->subMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                               class="px-3 py-2 text-sm border border-gray-300 rounded-l bg-gray-50 hover:bg-gray-100">Last Month</a>
                            <a href="{{ route('reports.office-dashboard', ['start_date' => now()->subMonths(3)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                               class="px-3 py-2 text-sm border-t border-b border-gray-300 bg-gray-50 hover:bg-gray-100">Last 3 Months</a>
                            <a href="{{ route('reports.office-dashboard', ['start_date' => now()->subYear()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                               class="px-3 py-2 text-sm border border-gray-300 rounded-r bg-gray-50 hover:bg-gray-100">Last Year</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Documents -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Documents</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $documentsUploaded }}</dd>
                                <dd class="text-sm text-gray-500">In selected date range</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Today's Documents -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Today's Documents</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $documentsUploadedToday }}</dd>
                                <dd class="text-sm text-gray-500">Uploaded today</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Workflows -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-yellow-400 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Workflows</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $pendingWorkflows }}</dd>
                                <dd class="text-sm text-gray-500">Awaiting action</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Office Members -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Team Members</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $officeMembers->count() }}</dd>
                                <dd class="text-sm text-gray-500">In your office</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workflow Statistics -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex items-center border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Workflow Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="bg-blue-50 rounded p-4 border border-blue-100">
                            <div class="text-sm font-medium text-blue-800 mb-1">Sent</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $workflowStats['workflows_sent'] }}</div>
                        </div>
                        
                        <div class="bg-green-50 rounded p-4 border border-green-100">
                            <div class="text-sm font-medium text-green-800 mb-1">Received</div>
                            <div class="text-2xl font-bold text-green-900">{{ $workflowStats['workflows_received'] }}</div>
                        </div>
                        
                        <div class="bg-indigo-50 rounded p-4 border border-indigo-100">
                            <div class="text-sm font-medium text-indigo-800 mb-1">Approved</div>
                            <div class="text-2xl font-bold text-indigo-900">{{ $workflowStats['workflows_approved'] }}</div>
                        </div>
                        
                        <div class="bg-red-50 rounded p-4 border border-red-100">
                            <div class="text-sm font-medium text-red-800 mb-1">Rejected</div>
                            <div class="text-2xl font-bold text-red-900">{{ $workflowStats['workflows_rejected'] }}</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-amber-50 rounded p-4 border border-amber-100">
                            <div class="text-sm font-medium text-amber-800 mb-1">Average Processing Time</div>
                            <div class="text-2xl font-bold text-amber-900">{{ $workflowStats['avg_processing_time'] }}</div>
                        </div>
                        
                        <div class="bg-emerald-50 rounded p-4 border border-emerald-100">
                            <div class="text-sm font-medium text-emerald-800 mb-1">Approval Rate</div>
                            <div class="text-2xl font-bold text-emerald-900">{{ $workflowStats['approval_rate'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Volume Trend Chart -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex items-center border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Document Volume Trends</h3>
                </div>
                <div class="p-6">
                    <canvas id="documentTrendsChart" class="h-80 w-full"></canvas>
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

            <!-- Member Performance Section -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center">
                    <h5 class="font-bold">Member Performance</h5>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-800 text-white">
                                    <th class="px-4 py-2 text-left">Member</th>
                                    <th class="px-4 py-2 text-left">Uploads</th>
                                    <th class="px-4 py-2 text-left">Forwarded</th>
                                    <th class="px-4 py-2 text-left">Processed</th>
                                    <th class="px-4 py-2 text-left">Avg Response</th>
                                    <th class="px-4 py-2 text-left">Avg Processing</th>
                                    <th class="px-4 py-2 text-left">Approval Rate</th>
                                    <th class="px-4 py-2 text-left">Performance Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($memberPerformanceMetrics as $metric)
                                    <tr class="border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="px-4 py-2">{{ $metric['member']->first_name }} {{ $metric['member']->last_name }}</td>
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

            <!-- Performance Recommendations -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
                <div class="bg-gray-100 px-4 py-3 border-b">
                    <h5 class="font-bold">Performance Insights</h5>
                </div>
                <div class="p-4">
                    <ul class="divide-y divide-gray-200">
                        @if(count($memberPerformanceMetrics) > 0)
                            @php
                                $slowestMemberKey = array_search(min(array_column($memberPerformanceMetrics, 'performance_score')), array_column($memberPerformanceMetrics, 'performance_score'));
                                $fastestMemberKey = array_search(max(array_column($memberPerformanceMetrics, 'performance_score')), array_column($memberPerformanceMetrics, 'performance_score'));
                                $slowestMember = $memberPerformanceMetrics[$slowestMemberKey];
                                $fastestMember = $memberPerformanceMetrics[$fastestMemberKey];
                            @endphp

                            @if($slowestMember['performance_score'] < 50)
                                <li class="py-3 bg-yellow-50 px-4 rounded mb-2">
                                    Consider providing additional support to {{ $slowestMember['member']->first_name }} {{ $slowestMember['member']->last_name }}, 
                                    who has the lowest performance score ({{ $slowestMember['performance_score'] }}).
                                </li>
                            @endif

                            @if($fastestMember['performance_score'] > 80)
                                <li class="py-3 bg-green-50 px-4 rounded mb-2">
                                    {{ $fastestMember['member']->first_name }} {{ $fastestMember['member']->last_name }} shows excellent performance 
                                    (score: {{ $fastestMember['performance_score'] }}). Consider recognizing their contributions and sharing best practices with the team.
                                </li>
                            @endif
                        @endif

                        @if($workflowStats['avg_processing_minutes'] > 120)
                            <li class="py-3 bg-yellow-50 px-4 rounded mb-2">
                                The average processing time for your office is {{ $workflowStats['avg_processing_time'] }}. 
                                Consider reviewing workflow procedures to improve efficiency.
                            </li>
                        @elseif($workflowStats['avg_processing_minutes'] < 30)
                            <li class="py-3 bg-green-50 px-4 rounded mb-2">
                                Your office has an excellent average processing time of {{ $workflowStats['avg_processing_time'] }}.
                                Keep up the good work!
                            </li>
                        @endif

                        @if($workflowStats['approval_rate'] < 50)
                            <li class="py-3 bg-yellow-50 px-4 rounded mb-2">
                                Your office's approval rate is {{ $workflowStats['approval_rate'] }}%, which is relatively low.
                                This could indicate quality issues with submitted documents or inconsistent review standards.
                            </li>
                        @elseif($workflowStats['approval_rate'] > 95)
                            <li class="py-3 bg-blue-50 px-4 rounded mb-2">
                                Your office's approval rate is {{ $workflowStats['approval_rate'] }}%, which is very high.
                                While this could indicate excellent quality, ensure that reviews remain thorough and standards are maintained.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                    document.querySelector('.p-4:has(#statusChart)').innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">No status data available</div>';
                }
            }
        } catch (e) {
            console.error('Failed to create status chart:', e);
        }
    });
</script>