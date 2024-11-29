<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

<!-- Hero Section -->
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-500 text-white mr-4">
                    <!-- Icon -->
                </div>
                <div>
                    <h5 class="text-gray-500">Total Users</h5>
                    <h3 class="text-2xl font-semibold text-gray-900">1,024</h3>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                    <!-- Icon -->
                </div>
                <div>
                    <h5 class="text-gray-500">Active Sessions</h5>
                    <h3 class="text-2xl font-semibold text-gray-900">256</h3>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                    <!-- Icon -->
                </div>
                <div>
                    <h5 class="text-gray-500">Uploaded Document</h5>
                    <h3 class="text-2xl font-semibold text-gray-900">12,345</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex space-x-4">
            <select class="border-gray-300 rounded-md">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>This Year</option>
            </select>
            <select class="border-gray-300 rounded-md">
                <option>All Users</option>
                <option>New Users</option>
                <option>Returning Users</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-indigo-500 text-white rounded-md">Export</button>
            <button class="px-4 py-2 bg-green-500 text-white rounded-md">Add Data</button>
        </div>
    </div>
</div>

<!-- Data Visualizations -->
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Line Chart -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Monthly Revenue Trends</h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <span class="text-gray-500">Line Chart Placeholder</span>
            </div>
        </div>
        <!-- Bar Graph -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Weekly User Engagement</h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <span class="text-gray-500">Bar Graph Placeholder</span>
            </div>
        </div>
        <!-- Donut Chart -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Platform Usage Distribution</h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <span class="text-gray-500">Donut Chart Placeholder</span>
            </div>
        </div>
        <!-- Scatter Plot -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Performance Metrics</h3>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <span class="text-gray-500">Scatter Plot Placeholder</span>
            </div>
        </div>
    </div>
</div>
</x-app-layout>