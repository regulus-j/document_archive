@extends('layouts.app')

@section('content')
<!-- front-end -->

<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Document Archive</h1>

        <!-- Move the overflow classes to the table container only -->
        <div class="bg-white shadow-xl rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <form method="GET" action="{{ route('documents.archive') }}" class="space-y-4" id="searchForm">
                    <div class="flex gap-4 items-end">
                    <div class="flex-grow">
                        <div class="relative">
                        <input type="text" name="search" id="searchInput"
                            class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="Search documents..." value="{{ request('search') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        </div>
                    </div>
                    <button type="button" id="advancedSearchBtn" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Advanced Search
                    </button>
                    </div>
                    
                    <div id="advancedSearchOptions" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                        <label class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="date" name="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            value="{{ request('date_from') }}">
                        </div>
                        <div>
                        <label class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="date" name="date_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            value="{{ request('date_to') }}">
                        </div>
                    </div>
                    </div>
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const advancedSearchBtn = document.getElementById('advancedSearchBtn');
                    const advancedSearchOptions = document.getElementById('advancedSearchOptions');
                    const searchInput = document.getElementById('searchInput');
                    const tableRows = document.querySelectorAll('tbody tr');

                    advancedSearchBtn.addEventListener('click', function() {
                    advancedSearchOptions.classList.toggle('hidden');
                    });

                    searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                    });

                    // Auto-submit form when dates change
                    document.querySelectorAll('input[type="date"]').forEach(input => {
                    input.addEventListener('change', () => document.getElementById('searchForm').submit());
                    });
                });
            </script>

            <div class="overflow-x-auto overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                        <tr class="text-left">
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">No</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Title</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Uploader</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Office</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Recipient</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Description</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Uploaded</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $index => $document)
                            <tr>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $index + 1 }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->title }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->uploader }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->office }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->recipient }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->description }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->created_at->format('M d, Y') }}</td>
                                <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('documents.edit', $document->id) }}" class="text-green-600 hover:text-green-900">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('documents.forward', $document->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>

@endsection