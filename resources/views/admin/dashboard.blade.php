<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Super Admin Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <button
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md hover:from-blue-700 hover:to-blue-800 transition shadow-sm">
                    <span>Export Data</span>
                </button>
                <button
                    class="px-4 py-2 bg-white text-blue-700 border border-blue-200 rounded-md hover:bg-blue-50 transition shadow-sm">
                    <span>Refresh</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Companies Card -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-blue-600 text-sm font-medium">Total Companies</div>
                                <div class="text-3xl font-bold text-gray-900">{{ $totalCompanies }}</div>
                                <div class="text-sm text-emerald-500 mt-1">
                                    <span>↑ 12% from last month</span>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-indigo-600 text-sm font-medium">Total Users</div>
                                <div class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</div>
                                <div class="text-sm text-emerald-500 mt-1">
                                    <span>↑ 8% from last month</span>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Documents Card -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-cyan-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-cyan-600 text-sm font-medium">Total Documents</div>
                                <div class="text-3xl font-bold text-gray-900">{{ $totalDocuments }}</div>
                                <div class="text-sm text-emerald-500 mt-1">
                                    <span>↑ 15% from last month</span>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-3 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Subscriptions Card -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-sky-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sky-600 text-sm font-medium">Active Subscriptions</div>
                                <div class="text-3xl font-bold text-gray-900">{{ $activeSubscriptions }}</div>
                                <div class="text-sm text-emerald-500 mt-1">
                                    <span>↑ 5% from last month</span>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-sky-500 to-sky-600 p-3 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Chart Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-2 border border-blue-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Growth Overview</h3>
                            <div class="flex space-x-2">
                                <button
                                    class="px-3 py-1 bg-white border border-blue-200 text-blue-700 rounded-md text-sm hover:bg-blue-50 transition shadow-sm">Monthly</button>
                                <button
                                    class="px-3 py-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md text-sm hover:from-blue-700 hover:to-blue-800 transition shadow-sm">Yearly</button>
                            </div>
                        </div>
                        <div class="h-64 w-full">
                            <!-- Chart would be rendered here using JavaScript -->
                            <div
                                class="flex items-center justify-center h-full bg-gradient-to-b from-blue-50 to-white rounded-lg border border-dashed border-blue-200">
                                <div class="text-center">
                                    <p class="text-blue-700 font-medium">Growth chart will be displayed here</p>
                                    <p class="text-sm text-blue-500">Integrate with Chart.js or ApexCharts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Quick Stats</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-700 font-medium">New Users (Today)</span>
                                <span class="font-medium text-gray-800">24</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full"
                                    style="width: 45%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-indigo-700 font-medium">Document Uploads (Today)</span>
                                <span class="font-medium text-gray-800">67</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2.5 rounded-full"
                                    style="width: 65%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-cyan-700 font-medium">New Subscriptions (Today)</span>
                                <span class="font-medium text-gray-800">12</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-2.5 rounded-full"
                                    style="width: 30%"></div>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sky-700 font-medium">Support Tickets (Open)</span>
                                <span class="font-medium text-gray-800">8</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-sky-500 to-sky-600 h-2.5 rounded-full"
                                    style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-blue-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Activities</h3>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-50 to-indigo-50">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Company</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Action</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($recentActivities as $activity)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                                                    {{ substr($activity->company_name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $activity->company_name }}</div>
                                                    <div class="text-sm text-blue-600">ID:
                                                        {{ $activity->company_id ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if(strpos($activity->action, 'created') !== false) bg-emerald-100 text-emerald-800
                                                @elseif(strpos($activity->action, 'updated') !== false) bg-blue-100 text-blue-800
                                                @elseif(strpos($activity->action, 'deleted') !== false) bg-rose-100 text-rose-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $activity->action }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $activity->user_name ?? 'System' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md hover:bg-blue-100 transition-colors">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Latest Companies -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Latest Companies</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                        </div>
                        <div class="space-y-4">
                            @for ($i = 0; $i < 5; $i++)
                                <div
                                    class="flex items-center p-3 border border-gray-100 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                                        C{{ $i + 1 }}
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="text-sm font-medium text-gray-900">Company Name {{ $i + 1 }}</div>
                                        <div class="text-sm text-blue-600">Joined {{ rand(1, 30) }} days ago</div>
                                    </div>
                                    <div class="text-sm text-gray-600 bg-blue-50 px-2 py-1 rounded-md">
                                        {{ rand(1, 50) }} users
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Subscription Plans -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Subscription Plans</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Manage</a>
                        </div>
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Basic Plan</div>
                                        <div class="text-sm text-emerald-600">$9.99/month</div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md">
                                    {{ rand(50, 200) }} active
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Pro Plan</div>
                                        <div class="text-sm text-blue-600">$29.99/month</div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium bg-blue-50 text-blue-700 px-2 py-1 rounded-md">
                                    {{ rand(30, 100) }} active
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Enterprise Plan</div>
                                        <div class="text-sm text-indigo-600">$99.99/month</div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium bg-indigo-50 text-indigo-700 px-2 py-1 rounded-md">
                                    {{ rand(10, 50) }} active
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>