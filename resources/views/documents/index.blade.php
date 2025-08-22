@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Document Management</h1>
                        <p class="text-sm text-gray-500">Search, view and manage all documents</p>
                    </div>
                </div>
                <div>
                    @can('document-create')
                        <a href="{{ route('documents.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Document
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-white border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-md"
                role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-white border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd">
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- QR Code Modal -->
        @if (session('data'))
            <div class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-gray-900 bg-opacity-70 absolute inset-0"></div>
                <div class="bg-white p-8 rounded-xl shadow-2xl z-10 max-w-md w-full">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        QR Code Generated
                    </h2>
                    <div class="flex justify-center mb-6">
                        <div class="bg-white p-4 rounded-lg shadow-md border border-blue-100">
                            <img src="{{ session('data') }}" alt="QR Code" class="w-48 h-48">
                        </div>
                    </div>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ session('data') }}" download="qr-code.png"
                            class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Save QR Code
                        </a>
                        <button onclick="document.querySelector('.fixed.inset-0').remove()"
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Search Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-xl overflow-hidden h-full border border-blue-100">
                    <div class="bg-white p-6 border-b border-blue-200">
                        <div class="flex items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-800">Search Documents</h2>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">Search by text or upload an image</p>
                    </div>

                    <div class="p-6">
                        <form id="search-form" action="{{ route('documents.search') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <!-- Quick Search -->
                            <div>
                                <label for="filter-field" class="block text-sm font-medium text-gray-700 mb-1">Search
                                    by</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <select id="filter-field"
                                        class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="title">Title</option>
                                        <option value="uploader">Uploader</option>
                                        <option value="status">Status</option>
                                        <option value="originating">Originating</option>
                                        <option value="recipient">Recipient</option>
                                        <option value="description">Description</option>
                                    </select>
                                    <input type="text" id="quick-search"
                                        class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Quick search...">
                                </div>
                            </div>

                            <!-- Text Search -->
                            <div>
                                <label for="text-search" class="block text-sm font-medium text-gray-700 mb-1">Text
                                    search</label>
                                <div class="relative">
                                    <input type="text" id="text-search" name="text"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Search by text...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload/Camera -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image search</label>
                                <div class="flex flex-wrap gap-3">
                                    <button type="button" onclick="document.getElementById('image-input').click()"
                                        class="inline-flex items-center px-4 py-2 border border-blue-200 shadow-sm text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="h-5 w-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Upload Image
                                    </button>
                                    <button type="button" id="camera-toggle"
                                        class="inline-flex items-center px-4 py-2 border border-indigo-200 shadow-sm text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="h-5 w-5 mr-2 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Open Camera
                                    </button>
                                </div>

                                <input type="file" id="image-input" name="image" accept="image/*" class="hidden">

                                <!-- Camera Container -->
                                <div id="camera-container" class="hidden mt-3">
                                    <div
                                        class="relative w-full aspect-video rounded-xl overflow-hidden bg-black shadow-lg border border-blue-200">
                                        <video id="camera-stream" autoplay playsinline
                                            class="w-full h-full object-contain"></video>
                                        <button type="button" id="capture-button"
                                            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Capture
                                        </button>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="preview-container" class="hidden relative w-full mt-3">
                                    <div class="bg-white p-2 rounded-xl shadow-md border border-blue-200">
                                        <img id="preview-image" src="#" alt="Preview" class="w-full rounded-lg">
                                        <button type="button" onclick="clearImage()"
                                            class="absolute top-4 right-4 p-1.5 bg-white rounded-full shadow-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 border border-gray-200">
                                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Button -->
                            <div>
                                <button type="submit" id="submit-button"
                                    class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <span id="spinner" class="hidden mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                    <span id="button-text">Search Documents</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Document List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-xl overflow-hidden h-full border border-blue-100">
                    <div class="bg-white p-6 border-b border-blue-200 flex justify-between items-center">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-800">Document List</h2>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $documents->total() }} documents</span>
                            <div class="relative">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-1.5 border border-blue-200 text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                        No</th>
                                    <th
                                        class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                        Details</th>
                                    <th
                                        class="bg-white px-6 py-3 text-right text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($documents as $document)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col space-y-2">
                                                <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>

                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                                        {{ $document->user?->first_name ? substr($document->user->first_name, 0, 1) : 'N' }}
                                                    </div>
                                                    <div class="ml-3 text-sm text-gray-700">
                                                        {{ ($document->user?->first_name ?? 'Unknown') . ' ' . ($document->user?->last_name ?? 'User') }}
                                                    </div>
                                                </div>

                                                <div class="flex flex-wrap gap-2 items-center">
                                                    @php
                                                        $statusColor = 'gray';
                                                        $status = $document->status?->status ? strtolower($document->status->status) : '';
                                                        
                                                        if ($status == 'approved') {
                                                            $statusColor = 'emerald';
                                                        } elseif ($status == 'pending') {
                                                            $statusColor = 'amber';
                                                        } elseif ($status == 'forwarded') {
                                                            $statusColor = 'blue';
                                                        } elseif ($status == 'recalled') {
                                                            $statusColor = 'purple';
                                                        } elseif ($status == 'uploaded') {
                                                            $statusColor = 'indigo';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="px-2.5 py-1 text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                        {{ $document->status?->status ?? 'N/A' }}
                                                    </span>

                                                    <span class="text-xs text-gray-500">
                                                        <span class="font-medium">From:</span>
                                                        {{ $document->transaction?->fromOffice?->name ?? $document->user?->offices?->first()?->name ?? 'N/A' }}
                                                    </span>

                                                    <span class="text-xs text-gray-500">
                                                        <span class="font-medium">To:</span>
                                                        @if (isset($documentRecipients[$document->id]) && count($documentRecipients[$document->id]) > 0)
                                                            @foreach ($documentRecipients[$document->id] as $recipient)
                                                                <span class="inline-flex items-center">
                                                                    {{ $recipient['name'] }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            N/A
                                                        @endif
                                                    </span>

                                                    <span class="text-xs text-gray-500">
                                                        <span class="font-medium">Uploaded:</span>
                                                        {{ $document->created_at->format('M d, Y H:i') }}
                                                    </span>

                                                    <span class="text-xs text-gray-500">
                                                        <span class="font-medium">Last Updated:</span>
                                                        {{ $document->updated_at->format('M d, Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('documents.show', $document->id) }}"
                                                    class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                                    title="View">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                @php
                                                    $status = '';
                                                    if (isset($document->status) && $document->status && $document->status->status) {
                                                        $status = strtolower(trim($document->status->status));
                                                    }
                                                    $isDocumentOwner = $document->user && $document->user->id == auth()->user()->id;
                                                    // Always enable buttons for admin users
                                                    $canManageDocument = $isDocumentOwner || auth()->user()->can('document-manage') || auth()->user()->can('document-edit');
                                                @endphp
                                                
                                                @if ($canManageDocument)
                                                    @if ($status == 'uploaded' || $status == 'pending')
                                                        <a href="{{ route('documents.forward', $document->id) }}"
                                                            class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors"
                                                            title="Forward">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                            </svg>
                                                        </a>
                                                    @elseif($status == 'forwarded')
                                                        <form action="{{ route('documents.recall', $document) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button type="submit" 
                                                                class="p-1.5 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors"
                                                                title="Recall Document" 
                                                                onclick="return handleRecallDocument(this.form);">
                                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" 
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                                                        stroke-width="2" 
                                                                        d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if(isset($document->status) && strtolower($document->status->status) === 'recalled' && ($document->uploader == auth()->id() || $document->user->id == auth()->user()->id || auth()->user()->can('document-manage')))
                                                        <form action="{{ route('documents.resume', $document) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button type="submit" 
                                                                class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors"
                                                                title="Resume Document" 
                                                                onclick="return handleResumeDocument(this.form);">
                                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" 
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                                                        stroke-width="2" 
                                                                        d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                @can('document-edit')
                                                        <a href="{{ route('documents.edit', $document->id) }}"
                                                            class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors"
                                                            title="
                                                            ">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                    @endcan
                                                    @can('document-delete')
                                                        <form action="{{ route('documents.destroy', $document->id) }}"
                                                            method="POST"
                                                            onsubmit="return handleDeleteDocument(this);"
                                                            class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                                                title="Delete">
                                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                    <form action="{{ route('documents.download', $document->id) }}"
                                                        method="GET" class="inline-block">
                                                        @csrf
                                                        <button type="submit"
                                                            class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors"
                                                            title="Download">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    @if ($document->status->status != 'archived')
                                                        <form action="{{ route('documents.archive.store', $document) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button type="submit"
                                                                class="p-1.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors"
                                                                title="Archive Document" 
                                                                onclick="return handleArchiveDocument(this.form);">
                                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-6">
                                                <svg class="h-12 w-12 text-gray-400 mb-3"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-gray-500 text-base">No documents found</p>
                                                <p class="text-gray-400 text-sm mt-1">Try adjusting your search criteria
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs -->
        <div class="mt-6 bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100 mb-8">
            <div class="bg-white p-6 border-b border-blue-200 flex justify-between items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Document Audit Logs</h2>
                </div>
                <span class="text-sm text-gray-500">{{ $auditLogs->total() }} entries</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                Date/Time</th>
                            <th
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                Document</th>
                            <th
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                User</th>
                            <th
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                Action</th>
                            <th
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                Status</th>
                            <th
                                class="bg-white px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider border-b border-blue-200">
                                Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                    {{ $log->document?->title ?? 'Deleted Document' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-7 w-7 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm text-xs">
                                            {{ substr($log->user->first_name, 0, 1) }}
                                        </div>
                                        <div class="ml-3 text-sm text-gray-700">
                                            {{ $log->user->first_name }} {{ $log->user->last_name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = 'gray';
                                        if ($log->status == 'Approved') {
                                            $statusColor = 'emerald';
                                        } elseif ($log->status == 'Pending') {
                                            $statusColor = 'amber';
                                        } elseif ($log->status == 'Rejected') {
                                            $statusColor = 'rose';
                                        }
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $log->details }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-gray-500 text-base">No audit logs found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-gray-200">
                {{ $auditLogs->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('search-form');
            const imageInput = document.getElementById('image-input');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const cameraToggle = document.getElementById('camera-toggle');
            const cameraContainer = document.getElementById('camera-container');
            const cameraStream = document.getElementById('camera-stream');
            const captureButton = document.getElementById('capture-button');
            const submitButton = document.getElementById('submit-button');
            const spinner = document.getElementById('spinner');
            const buttonText = document.getElementById('button-text');
            const quickSearch = document.getElementById('quick-search');
            const filterField = document.getElementById('filter-field');

            let stream = null;

            imageInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size should not exceed 5MB');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onloadend = function() {
                        previewImage.src = reader.result;
                        previewContainer.classList.remove('hidden');
                        if (!cameraContainer.classList.contains('hidden')) {
                            stopCamera();
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });

            cameraToggle.addEventListener('click', async function() {
                if (cameraContainer.classList.contains('hidden')) {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        cameraStream.srcObject = stream;
                        cameraContainer.classList.remove('hidden');
                        this.innerHTML = `
                                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Close Camera
                                                `;
                    } catch (err) {
                        alert('Unable to access camera');
                        console.error('Error accessing camera:', err);
                    }
                } else {
                    stopCamera();
                }
            });

            captureButton.addEventListener('click', function() {
                const canvas = document.createElement('canvas');
                canvas.width = cameraStream.videoWidth;
                canvas.height = cameraStream.videoHeight;
                canvas.getContext('2d').drawImage(cameraStream, 0, 0);

                canvas.toBlob(function(blob) {
                    const file = new File([blob], 'camera-capture.jpg', {
                        type: 'image/jpeg'
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    imageInput.files = dataTransfer.files;
                    previewImage.src = canvas.toDataURL('image/jpeg');
                    previewContainer.classList.remove('hidden');
                    stopCamera();
                }, 'image/jpeg');
            });

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                spinner.classList.remove('hidden');
                buttonText.textContent = 'Searching...';
            });

            quickSearch.addEventListener('keyup', function() {
                const searchField = filterField.value;
                const searchText = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');

                tableRows.forEach(row => {
                    let cellIndex;
                    switch (searchField) {
                        case 'title':
                            cellIndex = 1;
                            break;
                        case 'uploader':
                            cellIndex = 2;
                            break;
                        case 'status':
                            cellIndex = 3;
                            break;
                        case 'originating':
                            cellIndex = 4;
                            break;
                        case 'recipient':
                            cellIndex = 5;
                            break;
                        case 'description':
                            cellIndex = 7;
                            break;
                        default:
                            cellIndex = 1;
                    }

                    const cell = row.cells[cellIndex];
                    if (cell) {
                        const text = cell.textContent.toLowerCase();
                        if (text.includes(searchText)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });

            window.clearImage = function() {
                imageInput.value = '';
                previewImage.src = '#';
                previewContainer.classList.add('hidden');
            }

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                cameraContainer.classList.add('hidden');
                cameraToggle.innerHTML = `
                                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Open Camera
                                        `;
            }

            window.addEventListener('beforeunload', stopCamera);
        });
    </script>

    <!-- Popup Notification Styles -->
    <style>
        .popup-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            font-family: system-ui, -apple-system, sans-serif;
        }
        
        .popup-notification.show {
            transform: translateX(0);
        }
        
        .popup-notification.success {
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
            border-left: 4px solid #047857;
        }
        
        .popup-notification.error {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
            border-left: 4px solid #b91c1c;
        }
        
        .popup-notification.warning {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: white;
            border-left: 4px solid #b45309;
        }
        
        .popup-notification .popup-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .popup-notification .popup-icon {
            margin-right: 12px;
            width: 24px;
            height: 24px;
        }
        
        .popup-notification .popup-message {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }
        
        .popup-notification .popup-close {
            margin-left: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            line-height: 1;
        }
        
        .popup-notification .popup-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .confirmation-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .confirmation-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .confirmation-content {
            position: relative;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
            padding: 24px;
            max-width: 450px;
            width: 90%;
            animation: confirmationSlideIn 0.3s ease-out;
        }
        
        @keyframes confirmationSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .confirmation-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .confirmation-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
        }
        
        .confirmation-icon.delete {
            color: #dc2626;
        }
        
        .confirmation-icon.archive {
            color: #f59e0b;
        }
        
        .confirmation-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }
        
        .confirmation-message {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        
        .confirmation-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        
        .confirmation-cancel, .confirmation-confirm {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid;
        }
        
        .confirmation-cancel {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
        }
        
        .confirmation-cancel:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }
        
        .confirmation-confirm.delete {
            background: #dc2626;
            border-color: #dc2626;
            color: white;
        }
        
        .confirmation-confirm.delete:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }
        
        .confirmation-confirm.archive {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }
        
        .confirmation-confirm.archive:hover {
            background: #d97706;
            border-color: #d97706;
        }

        .confirmation-icon.recall {
            width: 24px;
            height: 24px;
            color: #7c3aed;
            margin-right: 12px;
        }

        .confirmation-confirm.recall {
            background: #7c3aed;
            border-color: #7c3aed;
            color: white;
        }

        .confirmation-confirm.recall:hover {
            background: #6d28d9;
            border-color: #6d28d9;
        }

        .confirmation-icon.resume {
            width: 24px;
            height: 24px;
            color: #059669;
            margin-right: 12px;
        }

        .confirmation-confirm.resume {
            background: #059669;
            border-color: #059669;
            color: white;
        }

        .confirmation-confirm.resume:hover {
            background: #047857;
            border-color: #047857;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show popup notifications for session messages
            @if(session('success'))
                showPopup('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                showPopup('{{ session('error') }}', 'error');
            @endif
        });

        // Function to show popup notifications
        function showPopup(message, type = 'success') {
            // Remove any existing popups
            const existingPopups = document.querySelectorAll('.popup-notification');
            existingPopups.forEach(popup => popup.remove());
            
            // Create popup element
            const popup = document.createElement('div');
            popup.className = `popup-notification ${type}`;
            
            let iconSvg = '';
            switch(type) {
                case 'success':
                    iconSvg = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'error':
                    iconSvg = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'warning':
                    iconSvg = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>';
                    break;
            }
            
            popup.innerHTML = `
                <div class="popup-content">
                    ${iconSvg}
                    <span class="popup-message">${message}</span>
                    <button class="popup-close" onclick="closePopup(this)">&times;</button>
                </div>
            `;
            
            // Add to body
            document.body.appendChild(popup);
            
            // Show popup
            setTimeout(() => popup.classList.add('show'), 100);
            
            // Auto close after 5 seconds
            setTimeout(() => closePopup(popup.querySelector('.popup-close')), 5000);
        }
        
        // Function to close popup
        function closePopup(closeBtn) {
            const popup = closeBtn.closest('.popup-notification');
            popup.classList.remove('show');
            setTimeout(() => popup.remove(), 300);
        }

        // Custom confirmation popup function
        function showConfirmationPopup(message, onConfirm, type = 'delete') {
            // Remove any existing popups
            const existingPopups = document.querySelectorAll('.popup-notification, .confirmation-popup');
            existingPopups.forEach(popup => popup.remove());
            
            // Create confirmation popup
            const popup = document.createElement('div');
            popup.className = 'confirmation-popup';
            
            let iconSvg = '';
            let title = '';
            let confirmText = '';
            
            switch(type) {
                case 'delete':
                    iconSvg = '<svg class="confirmation-icon delete" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
                    title = 'Delete Document';
                    confirmText = 'Delete';
                    break;
                case 'archive':
                    iconSvg = '<svg class="confirmation-icon archive" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>';
                    title = 'Archive Document';
                    confirmText = 'Archive';
                    break;
                case 'recall':
                    iconSvg = '<svg class="confirmation-icon recall" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z" /></svg>';
                    title = 'Recall Document';
                    confirmText = 'Recall';
                    break;
                case 'resume':
                    iconSvg = '<svg class="confirmation-icon resume" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-9-4h1m-1 0V8a2 2 0 012-2h8a2 2 0 012 2v2M9 10v4m4-4v4" /></svg>';
                    title = 'Resume Document';
                    confirmText = 'Resume';
                    break;
                default:
                    iconSvg = '<svg class="confirmation-icon delete" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
                    title = 'Confirm Action';
                    confirmText = 'Confirm';
            }
            
            popup.innerHTML = `
                <div class="confirmation-overlay"></div>
                <div class="confirmation-content">
                    <div class="confirmation-header">
                        ${iconSvg}
                        <h3>${title}</h3>
                    </div>
                    <div class="confirmation-message">${message}</div>
                    <div class="confirmation-buttons">
                        <button class="confirmation-cancel">Cancel</button>
                        <button class="confirmation-confirm ${type}">${confirmText}</button>
                    </div>
                </div>
            `;
            
            // Add to body
            document.body.appendChild(popup);
            
            // Add event listeners
            popup.querySelector('.confirmation-cancel').addEventListener('click', function() {
                popup.remove();
            });
            
            popup.querySelector('.confirmation-confirm').addEventListener('click', function() {
                popup.remove();
                onConfirm();
            });
            
            popup.querySelector('.confirmation-overlay').addEventListener('click', function() {
                popup.remove();
            });
            
            // Close on escape key
            document.addEventListener('keydown', function escapeHandler(e) {
                if (e.key === 'Escape') {
                    popup.remove();
                    document.removeEventListener('keydown', escapeHandler);
                }
            });
        }

        // Enhanced delete function
        function handleDeleteDocument(form) {
            showConfirmationPopup(
                'Are you sure you want to delete this document? This action cannot be undone and will permanently remove the document from the system.',
                function() {
                    // Show loading state
                    const button = form.querySelector('button[type="submit"]');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML = `
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                    }
                    form.submit();
                },
                'delete'
            );
            return false;
        }

        // Enhanced archive function
        function handleArchiveDocument(form) {
            showConfirmationPopup(
                'Are you sure you want to archive this document? It will be moved to the archive section and will no longer appear in the active documents list.',
                function() {
                    // Show loading state
                    const button = form.querySelector('button[type="submit"]');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML = `
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                    }
                    form.submit();
                },
                'archive'
            );
            return false;
        }

        // Handle recall document action
        function handleRecallDocument(form) {
            showConfirmationPopup(
                'Are you sure you want to recall this document? This will pause the workflow and notify all recipients.',
                function() {
                    // Show loading state
                    const button = form.querySelector('button[type="submit"]');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML = `
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                    }
                    form.submit();
                },
                'recall'
            );
            return false;
        }

        // Handle resume document action
        function handleResumeDocument(form) {
            showConfirmationPopup(
                'Are you sure you want to resume this document workflow? This will reactivate the workflow and notify all recipients.',
                function() {
                    // Show loading state
                    const button = form.querySelector('button[type="submit"]');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML = `
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        `;
                    }
                    form.submit();
                },
                'resume'
            );
            return false;
        }
    </script>
@endsection
