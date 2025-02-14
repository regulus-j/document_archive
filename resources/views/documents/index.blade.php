@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Document Management</h1>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 ">Search Documents</h2>
                <p class="mt-2\] text-sm text-gray-500">Search through documents using text or upload an image</p>
                <form id="search-form" action="{{ route('documents.search') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-grow">
                            <label for="filter-field" class="block text-sm font-medium text-gray-700 mb-1">Search
                                by</label>
                            <select id="filter-field"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="title">Title</option>
                                <option value="uploader">Uploader</option>
                                <option value="status">Status</option>
                                <option value="originating">Originating</option>
                                <option value="recipient">Recipient</option>
                                <option value="description">Description</option>
                            </select>
                        </div>
                        <div class="flex-grow">
                            <label for="quick-search" class="block text-sm font-medium text-gray-700 mb-1">Quick
                                search</label>
                            <input type="text" id="quick-search"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Enter search term...">
                        </div>
                    </div>

                    @if(session('data'))
                    <div class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-gray-900 bg-opacity-50 absolute inset-0"></div>
                        <div class="bg-white p-6 rounded-lg shadow-lg z-10">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">QR Code Generated</h2>
                            <div class="flex justify-center">
                                <img src="{{ session('data') }}" alt="QR Code" class="w-32 h-32">
                                <img src="data:image/png;base64,{{ base64_encode(session('data')) }}" alt="QR Code" class="w-32 h-32 ml-4">
                            </div>
                            <a href="{{ session('data') }}" download="qr-code.png"
                            class="px-4 mx-2 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Save QR Code
                            </a>
                            <button onclick="document.querySelector('.fixed.inset-0').remove()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Close</button>
                        </div>
                    </div>
                    @endif

                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-grow">
                            <label for="text-search" class="block text-sm font-medium text-gray-700 mb-1">Text
                                search</label>
                            <div class="relative">
                                <input type="text" id="text-search" name="text"
                                    class="w-full pl-10 pr-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="Search by text...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" id="submit-button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span id="spinner" class="hidden mr-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                                <span id="button-text">Search</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-4">
                            <button type="button" onclick="document.getElementById('image-input').click()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload Image
                            </button>
                            <button type="button" id="camera-toggle"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Open Camera
                            </button>
                        </div>

                        <input type="file" id="image-input" name="image" accept="image/*" class="hidden">

                        <div id="camera-container" class="hidden">
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-black">
                                <video id="camera-stream" autoplay playsinline
                                    class="w-full h-full object-contain"></video>
                                <button type="button" id="capture-button"
                                    class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Capture
                                </button>
                            </div>
                        </div>

                        <div id="preview-container" class="hidden relative w-full max-w-md mx-auto">
                            <img id="preview-image" src="#" alt="Preview"
                                class="w-full rounded-lg border border-gray-300">
                            <button type="button" onclick="clearImage()"
                                class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Document List</h2>
                    <div class="space-x-2">
                        @can('document-create')
                            <a href="{{ route('documents.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p class="font-bold">Success</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    No</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Title</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Uploader</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Status</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Originating</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Recipient</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Uploaded</th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs relative group cursor-help">
                                    Days Lapsed
                                    <div class="absolute hidden group-hover:block bg-black text-white text-xs rounded py-1 px-2 -left-1/2 transform -translate-x-1/2 mt-1 z-10 w-40 text-center">
                                        Days passed since previous update
                                    </div>
                                </th>
                                <th
                                    class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">{{ $document->title }}</td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ $document->user->first_name . ' ' . $document->user->last_name }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-{{ $document->status?->status == 'Approved' ? 'green' : ($document->status?->status == 'Pending' ? 'yellow' : 'gray') }}-700 bg-{{ $document->status?->status == 'Approved' ? 'green' : ($document->status?->status == 'Pending' ? 'yellow' : 'gray') }}-100 rounded-full">
                                            {{ $document->status?->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ $document->transaction?->fromOffice?->name }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ isset($highestRecipients[$document->id]) ? $highestRecipients[$document->id]->implode(', ') : 'N/A' }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ $document->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        {{ date_diff(new DateTime($document->updated_at), new DateTime(now()))->format('%Hh %Im %Ss') }}
                                    </td>
                                    <td class="border-dashed border-t border-gray-200 px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('documents.show', $document->id) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @can('document-edit')
                                                <a href="{{ route('documents.edit', $document->id) }}"
                                                    class="text-green-600 hover:text-green-900">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('document-delete')
                                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this document?');"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                            <form action="{{ route('documents.download', $document->id) }}" method="GET"
                                                class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Document Audit Logs</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Date/Time</th>
                            <th class="px-6 py-3">Document</th>
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Action</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditLogs as $log)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $log->document?->title ?? 'Deleted Document' }}</td>
                                <td class="px-6 py-4">{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                <td class="px-6 py-4">{{ $log->action }}</td>
                                <td class="px-6 py-4">{{ $log->status }}</td>
                                <td class="px-6 py-4">{{ $log->details }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">No audit logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6">
                {{ $auditLogs->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        imageInput.addEventListener('change', function (e) {
            const file = this.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size should not exceed 5MB');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onloadend = function () {
                    previewImage.src = reader.result;
                    previewContainer.classList.remove('hidden');
                    if (!cameraContainer.classList.contains('hidden')) {
                        stopCamera();
                    }
                }
                reader.readAsDataURL(file);
            }
        });

        cameraToggle.addEventListener('click', async function () {
            if (cameraContainer.classList.contains('hidden')) {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: true });
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

        captureButton.addEventListener('click', function () {
            const canvas = document.createElement('canvas');
            canvas.width = cameraStream.videoWidth;
            canvas.height = cameraStream.videoHeight;
            canvas.getContext('2d').drawImage(cameraStream, 0, 0);

            canvas.toBlob(function (blob) {
                const file = new File([blob], 'camera-capture.jpg', { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageInput.files = dataTransfer.files;
                previewImage.src = canvas.toDataURL('image/jpeg');
                previewContainer.classList.remove('hidden');
                stopCamera();
            }, 'image/jpeg');
        });

        form.addEventListener('submit', function () {
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.textContent = 'Searching...';
        });

        quickSearch.addEventListener('keyup', function () {
            const searchField = filterField.value;
            const searchText = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                let cellIndex;
                switch (searchField) {
                    case 'title': cellIndex = 1; break;
                    case 'uploader': cellIndex = 2; break;
                    case 'status': cellIndex = 3; break;
                    case 'originating': cellIndex = 4; break;
                    case 'recipient': cellIndex = 5; break;
                    case 'description': cellIndex = 7; break;
                    default: cellIndex = 1;
                }

                const cell = row.cells[cellIndex];
                const text = cell.textContent.toLowerCase();

                if (text.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        window.clearImage = function () {
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
@endsection