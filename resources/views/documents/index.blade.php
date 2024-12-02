@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Documents</h2>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Search Documents</h3>
            <p class="mt-2 text-sm text-gray-500">Search through documents using text or upload an image</p>
        </div>
    
        <div class="p-6">
            <div id="error-container" class="hidden mb-4 bg-red-50 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside"></ul>
            </div>

            <div class="mb-6 flex items-center space-x-4">
                <select id="filter-field" class="rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="title">Title</option>
                    <option value="uploader">Uploader</option>
                    <option value="status">Status</option>
                    <option value="originating">Originating</option>
                    <option value="recipient">Recipient</option>
                    <option value="description">Description</option>
                </select>
                <div class="relative flex-grow">
                    <input type="text" 
                           id="quick-search" 
                           class="block w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Quick search...">
                </div>
            </div>

            <script>
            document.getElementById('quick-search').addEventListener('keyup', function() {
                const searchField = document.getElementById('filter-field').value;
                const searchText = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');

                tableRows.forEach(row => {
                    let cellIndex;
                    switch(searchField) {
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
            </script>
    
            <form id="search-form" action="/documents/search" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="flex items-center space-x-4">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" 
                               name="text" 
                               class="block w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Search by text...">
                    </div>

                    <button type="submit" 
                            id="submit-button"
                            class="flex items-center px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                        <span id="spinner" class="hidden mr-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span id="button-text">Search</span>
                    </button>
                </div>
    
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-4">
                            <button type="button" 
                                    onclick="document.getElementById('image-input').click()" 
                                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload Image
                            </button>
                            
                            <button type="button" 
                                    id="camera-toggle"
                                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Open Camera
                            </button>
                        </div>
    
                        <input type="file" 
                               id="image-input" 
                               name="image" 
                               accept="image/*"
                               class="hidden">
    
                        <div id="camera-container" class="hidden">
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-black">
                                <video id="camera-stream" autoplay playsinline class="w-full h-full object-contain"></video>
                                <button type="button" 
                                        id="capture-button"
                                        class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Capture
                                </button>
                            </div>
                        </div>
    
                        <div id="preview-container" class="hidden relative w-full max-w-md">
                            <img id="preview-image" src="#" alt="Preview" class="w-full rounded-lg border border-gray-300">
                            <button type="button" 
                                    onclick="clearImage()"
                                    class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-md hover:bg-gray-100">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
    
                <div class="flex justify-end mb-2 mx-4">
                    @can('document-create')
                    <a href="{{ route('documents.create') }}" class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white text-sm mb-2 mr-2 py-2 px-4 rounded transition-colors">
                        <i class="fa fa-plus mr-2"></i> Create New Document
                    </a>
                    @endcan
                
                    <!-- Modal Trigger -->
                    <button id="open-modal" type="button" class="bg-blue-500 hover:bg-blue-600 text-white text-sm mr-2 mb-2 py-2 px-4 rounded transition-colors">Create Folder</button>
                </div>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
                @elseif(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>


    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-blue-800">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">No</th>
                            <th scope="col" class="px-4 py-3 text-left">Title</th>
                            <th scope="col" class="px-4 py-3 text-left">Uploader</th>
                            <th scope="col" class="px-4 py-3 text-left">Status</th>
                            <th scope="col" class="px-4 py-3 text-left">Originating</th>
                            <th scope="col" class="px-4 py-3 text-left">Recipient</th>
                            <th scope="col" class="px-4 py-3 text-left">Uploaded</th>
                            <th scope="col" class="px-4 py-3 text-left">Description</th>
                            <th scope="col" class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                        <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $document->title }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $document->user->first_name . ' ' . $document->user->last_name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $document->status?->status == 'Approved' ? 'bg-green-100 text-green-800' : 
                                       ($document->status?->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $document->status?->status ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $document->transaction?->fromOffice?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $document->transaction?->toOffice?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $document->created_at }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $document->description }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('documents.show', $document->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fa-solid fa-list mr-1"></i> Show
                                    </a>
                                    @can('document-edit')
                                    <a href="{{ route('documents.edit', $document->id) }}"
                                       class="text-green-600 hover:text-green-800 transition-colors">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                    @endcan
                                    @can('document-delete')
                                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this document?')">
                                            <i class="fa-solid fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                    <form action="{{ route('documents.downloadFile', $document->id) }}" method="get" class="inline-block">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="text-green-600 hover:text-green-800 transition-colors">
                                            <i class="fa-solid fa-download mr-1"></i> Download
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    <div class="mt-6">
        {!! $documents->links() !!}
    </div>
</div>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Document Audit Logs</h2>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
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
                            <td class="px-6 py-4">{{ $log->created_at }}</td>
                            <td class="px-6 py-4">{{ $log->document->title }}</td>
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
        <div class="px-6 py-4">
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

        captureButton.addEventListener('click', function() {
            const canvas = document.createElement('canvas');
            canvas.width = cameraStream.videoWidth;
            canvas.height = cameraStream.videoHeight;
            canvas.getContext('2d').drawImage(cameraStream, 0, 0);
            
            canvas.toBlob(function(blob) {
                const file = new File([blob], 'camera-capture.jpg', { type: 'image/jpeg' });
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

@endsection