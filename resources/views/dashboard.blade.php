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
                    {{ __("You're logged in " . auth()->user()->first_name . "!") }}
                </div>
            </div>

<!-- Hero Section -->
<div class="container mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Track Document</h3>
            <div class="mb-4">
                <label for="tracking_number" class="block text-gray-700">Tracking Number</label>
                <div class="flex">
                    <input type="text" id="tracking_number" class="form-input flex-grow rounded-l-md">
                    <button id="track" class="btn btn-primary rounded-r-md">Track</button>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Add Document</h3>
            <div class="mb-4">
                <label for="add_tracking_number" class="block text-gray-700">Tracking Number</label>
                <div class="flex">
                    <input type="text" id="add_tracking_number" class="form-input flex-grow rounded-l-md">
                    <button id="add" class="btn btn-primary rounded-r-md">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Receive Document</h3>
            <div class="mb-4">
                <label for="receive_tracking_number" class="block text-gray-700">Tracking Number</label>
                <div class="flex">
                    <input type="text" id="receive_tracking_number" class="form-input flex-grow rounded-l-md">
                    <button id="receive" class="btn btn-primary rounded-r-md">Receive</button>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-900 mb-4">Release Document</h3>
            <div class="mb-4">
                <label for="release_tracking_number" class="block text-gray-700">Tracking Number</label>
                <div class="flex">
                    <input type="text" id="release_tracking_number" class="form-input flex-grow rounded-l-md">
                    <button id="release" class="btn btn-primary rounded-r-md">Release</button>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h3 class="font-semibold text-xl text-gray-900 mb-4">Tag as Terminal</h3>
        <div class="mb-4">
            <label for="terminal_tracking_number" class="block text-gray-700">Tracking Number</label>
            <div class="flex">
                <input type="text" id="terminal_tracking_number" class="form-input flex-grow rounded-l-md">
                <button id="terminal" class="btn btn-primary rounded-r-md">Terminal</button>
            </div>
        </div>
    </div>
</div>

{{-- <!-- Filters and Actions -->
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
</div> --}}

</x-app-layout>