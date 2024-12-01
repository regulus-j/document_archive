@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Documents</h1>
                <div class="flex gap-3">
                    @can('document-create')
                        <a href="{{ route('documents.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#0066FF] hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Document
                        </a>
                    @endcan
                    <button id="open-modal"
                        class="inline-flex items-center px-4 py-2 border border-[#0066FF] shadow-sm text-sm font-medium rounded-md text-[#0066FF] bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] transition-colors">Create
                        Folder</button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">Search Documents</h2>
                <p class="mt-1 text-sm text-gray-500">Search through documents using text or upload an image</p>
            </div>

            <div class="p-6">
                <div id="error-container" class="hidden mb-4 bg-red-50 text-red-700 p-4 rounded-md">
                    <ul class="list-disc list-inside"></ul>
                </div>

                <form id="search-form" action="/documents/search" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    <div class="flex gap-4">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="text"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0066FF] focus:border-transparent transition-colors"
                                placeholder="Search by text...">
                        </div>

                        <button type="submit" id="submit-button"
                            class="inline-flex items-center px-6 py-2.5 bg-[#0066FF] text-white text-sm font-medium rounded-lg hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] disabled:opacity-50 transition-colors">
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

                    <div class="flex flex-wrap gap-4">
                        <button type="button" onclick="document.getElementById('image-input').click()"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] transition-colors">
                            <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Upload Image
                        </button>

                        <button type="button" id="camera-toggle"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] transition-colors">
                            <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
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
                            <video id="camera-stream" autoplay playsinline class="w-full h-full object-contain"></video>
                            <button type="button" id="capture-button"
                                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-[#0066FF] text-white rounded-md hover:bg-[#0052CC] transition-colors">
                                Capture
                            </button>
                        </div>
                    </div>

                    <div id="preview-container" class="hidden relative w-full max-w-md">
                        <img id="preview-image" src="#" alt="Preview" class="w-full rounded-lg border border-gray-200">
                        <button type="button" onclick="clearImage()"
                            class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Uploader</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Uploaded</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Path</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($documents as $document)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $document->user->first_name . ' ' . $document->user->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->created_at }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $document->description }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $document->path }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('documents.show', $document->id) }}"
                                        class="text-[#0066FF] hover:text-[#0052CC] transition-colors">Show</a>
                                    @can('document-edit')
                                        <a href="{{ route('documents.edit', $document->id) }}"
                                            class="text-[#0066FF] hover:text-[#0052CC] transition-colors">Edit</a>
                                    @endcan
                                    @can('document-delete')
                                        <form action="{{ route('documents.destroy', $document->id, $document->path) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this document?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                    <form action="{{ route('documents.downloadFile', $document->id) }}" method="get"
                                        class="inline-block">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="text-green-600 hover:text-green-700 transition-colors">
                                            Download
                                        </button>
                                    </form>
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
</div>

<script>
    // Your existing JavaScript code here (unchanged)
</script>

@include('documents.partials.folder_create')

@endsection