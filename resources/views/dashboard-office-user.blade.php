<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ __('Office Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Welcome Message -->
            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <div class="text-2xl font-semibold text-gray-900">
                        {{ __("Welcome back, " . auth()->user()->first_name . "!") }}
                    </div>
                </div>
            </div>

            <!-- Office User Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ([ 
                    ['title' => 'Documents Received', 'value' => $documentsReceived, 'icon' => 'M3 10h11M9 21V3m0 18v-8m-6 8h6m6-18h6m-6 0v18m0-18v8m6-8v8'], 
                    ['title' => 'Pending Documents', 'value' => $pendingDocuments, 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'], 
                    ['title' => 'Processed Documents', 'value' => $processedDocuments, 'icon' => 'M5 13l4 4L19 7'], 
                ] as $stat)
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200 flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $stat['icon'] }}" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ $stat['title'] }}
                                    </dt>
                                    <dd class="text-3xl font-semibold text-gray-900">
                                        {{ $stat['value'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions for Office Users -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                <form action="{{ route('trackingNumber-search') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">Enter Tracking Number</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" name="tracking_number" id="tracking_number"
                                class="flex-1 min-w-0 block w-full px-3 py-2 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                placeholder="Enter tracking number">
                            <button type="submit"
                                class="ml-2 px-4 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
