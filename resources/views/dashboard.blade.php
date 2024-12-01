<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div
                class="bg-white shadow-lg rounded-2xl p-8 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:shadow-xl">
                <div class="text-3xl font-bold text-indigo-600 mb-2">
                    {{ __("Welcome back!") }}
                </div>
                <p class="text-gray-600">You're logged in and ready to explore your dashboard.</p>
            </div>

            <!-- Hero Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $cards = [
                        ['title' => 'Total Users', 'value' => '1,024', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'from-blue-500 to-blue-600'],
                        ['title' => 'Active Sessions', 'value' => '256', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'color' => 'from-green-500 to-green-600'],
                        ['title' => 'Uploaded Documents', 'value' => '12,345', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'from-yellow-500 to-yellow-600'],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div
                        class="bg-gradient-to-br {{ $card['color'] }} shadow-lg rounded-2xl p-6 text-white transition duration-500 ease-in-out transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-white/20 text-white mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $card['icon'] }}" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-white/80 text-sm font-medium mb-1">{{ $card['title'] }}</h5>
                                <h3 class="text-4xl font-extrabold">{{ $card['value'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Filters and Actions -->
            <div class="bg-white shadow-lg rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="flex space-x-4">
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition duration-150 ease-in-out">
                            <option>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>This Year</option>
                        </select>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition duration-150 ease-in-out">
                            <option>All Users</option>
                            <option>New Users</option>
                            <option>Returning Users</option>
                        </select>
                    </div>
                    <div class="flex space-x-4">
                        <button
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 ease-in-out transform hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </button>
                        <button
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 ease-in-out transform hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Data Visualizations -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @php
                    $charts = [
                        ['title' => 'Monthly Revenue Trends', 'type' => 'Line Chart'],
                        ['title' => 'Weekly User Engagement', 'type' => 'Bar Graph'],
                        ['title' => 'Platform Usage Distribution', 'type' => 'Donut Chart'],
                        ['title' => 'Performance Metrics', 'type' => 'Scatter Plot'],
                    ];
                @endphp

                @foreach ($charts as $chart)
                    <div
                        class="bg-white shadow-lg rounded-2xl p-6 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:shadow-xl">
                        <h3 class="font-bold text-2xl text-gray-900 mb-4">{{ $chart['title'] }}</h3>
                        <div
                            class="h-80 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                            <span class="text-gray-400 text-lg">{{ $chart['type'] }} Placeholder</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>