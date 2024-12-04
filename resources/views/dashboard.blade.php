<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <div class="text-2xl font-semibold text-gray-900">
                        {{ __("Welcome back, " . auth()->user()->first_name . "!") }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ([['title' => 'Track Document', 'id' => 'tracking_number', 'action' => 'Track', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'], ['title' => 'Add Document', 'id' => 'add_tracking_number', 'action' => 'Add', 'icon' => 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'], ['title' => 'Receive Document', 'id' => 'receive_tracking_number', 'action' => 'Receive', 'icon' => 'M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20'], ['title' => 'Release Document', 'id' => 'release_tracking_number', 'action' => 'Release', 'icon' => 'M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4']] as $item)
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300">
                        <h3 class="font-bold text-2xl text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['title'] }}
                        </h3>
                        <div class="mb-4">
                            <label for="{{ $item['id'] }}" class="block text-gray-700 font-medium mb-2">Tracking
                                Number</label>
                            <div class="flex">
                                <input type="text" id="{{ $item['id'] }}"
                                    class="form-input flex-grow rounded-l-md border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <button id="{{ strtolower($item['action']) }}"
                                    class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-300">
                                    {{ $item['action'] }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300">
                <h3 class="font-bold text-2xl text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Tag as Terminal
                </h3>
                <div class="mb-4">
                    <label for="terminal_tracking_number" class="block text-gray-700 font-medium mb-2">Tracking
                        Number</label>
                    <div class="flex">
                        <input type="text" id="terminal_tracking_number"
                            class="form-input flex-grow rounded-l-md border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <button id="terminal"
                            class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-300">
                            Terminal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>