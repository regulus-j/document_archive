@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Document Details</h1>
        <a href="{{ route('documents.index') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Documents
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">{{ $document->title }}</h2>
        </div>

        <div class="p-6 space-y-6">
            <div class="flex items-center text-sm text-gray-600">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium mr-2">Uploader:</span> {{ $document->uploader }}
            </div>

            <div class="flex items-center text-sm text-gray-600">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="font-medium mr-2">Uploaded:</span> {{ $document->uploaded }}
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Content</h3>
                <div class="bg-gray-50 rounded-md p-4 text-sm text-gray-800 whitespace-pre-wrap">
                    {{ $document->content }}
                </div>
            </div>

            <div class="flex items-center text-sm text-gray-600">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <span class="font-medium mr-2">File Path:</span> {{ $document->path }}
            </div>
        </div>

        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-right">
            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900 transition-colors">Download Document</a>
        </div>
    </div>

    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Document Tracking and Archiving System by DocArchive</p>
    </div>
</div>
@endsection