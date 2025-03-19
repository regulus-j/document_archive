@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Document Archive</h2>
            <a href="{{ route('documents.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm">
                Back to Documents
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Search Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-xl overflow-hidden h-full border border-blue-100">
                    <div class="bg-white p-6 border-b border-blue-200">
                        <div class="flex items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-800">Search Archives</h2>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">Search archived documents</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Full Text Search -->
                        <form action="{{ route('documents.search') }}" method="POST" class="space-y-5 mb-6">
                            @csrf
                            <div>
                                <label for="text-search" class="block text-sm font-medium text-gray-700 mb-1">Text search</label>
                                <div class="relative">
                                    <input type="text" id="text-search" name="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Search by text...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="archived" value="1">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow-sm">
                                Search
                            </button>
                        </form>

                        <!-- Tracking Number Search -->
                        <form action="{{ route('trackingNumber-search') }}" method="POST" class="space-y-5 mb-6">
                            @csrf
                            <div>
                                <label for="tracking-number" class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                                <div class="relative">
                                    <input type="text" id="tracking-number" name="tracking_number" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Enter tracking number...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg shadow-sm">
                                Find by Tracking Number
                            </button>
                        </form>

                        <!-- QR Code Scanner -->
                        <div class="space-y-3">
                            <div class="border-t border-gray-200 pt-5">
                                <h3 class="text-md font-medium text-gray-700 mb-3">Scan QR Code</h3>
                                <div id="qr-reader" class="w-full"></div>
                                <button id="start-scanner" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg shadow-sm">
                                    Start Scanner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents List -->
            <div class="lg:col-span-3">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Archived Documents</h3>
                            <span class="text-sm text-gray-600 bg-blue-100 py-1 px-3 rounded-full">{{ $documents->total() ?? 0 }} documents</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploader</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Archived</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($documents as $document)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-md bg-blue-100 text-blue-500">
                                                @php
                                                    $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                                    $icon = match($extension) {
                                                        'pdf' => 'document-text',
                                                        'doc', 'docx' => 'document',
                                                        'jpg', 'jpeg', 'png' => 'photograph',
                                                        default => 'document'
                                                    };
                                                @endphp
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($document->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->uploader->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->archived_at ? $document->archived_at->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('documents.show', $document->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('documents.download', $document->id) }}" class="text-green-600 hover:text-green-900">Download</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-gray-500 text-base">No archived documents found</p>
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
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startScannerButton = document.getElementById('start-scanner');
        let html5QrCode;

        startScannerButton.addEventListener('click', function() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    startScannerButton.textContent = 'Start Scanner';
                });
                return;
            }

            startScannerButton.textContent = 'Stop Scanner';
            
            html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                },
                (decodedText) => {
                    // QR code detected - submit tracking number search form
                    const trackingInput = document.getElementById('tracking-number');
                    trackingInput.value = decodedText;
                    
                    // Stop scanning
                    html5QrCode.stop().then(() => {
                        startScannerButton.textContent = 'Start Scanner';
                        
                        // Submit the form
                        document.querySelector('form[action*="trackingNumber-search"]').submit();
                    });
                },
                (errorMessage) => {
                    // Handle scan errors (optional)
                    console.log(errorMessage);
                }
            ).catch((err) => {
                console.error("Failed to start scanner:", err);
                startScannerButton.textContent = 'Start Scanner';
            });
        });
    });
</script>
@endpush
@endsection