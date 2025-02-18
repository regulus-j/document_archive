@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Review Document</h1>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-6">
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Title:</span>
                        <p class="mt-1 text-lg text-gray-900">{{ $document->title ?? 'Document Title Here' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700">Description:</span>
                        <p class="mt-1 text-gray-600">{{ $document->description ?? 'Document description...' }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('documents.download', $document->id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download Document
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($document->attachments) && $document->attachments->count())
            <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Existing Attachments</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($document->attachments as $attachment)
                            <li class="py-3 flex items-center">
                                <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    {{ $attachment->filename }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Review Form</h3>
                <form action="{{ route('documents.review.submit', $document->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label for="remark" class="block text-sm font-medium text-gray-700">Remark</label>
                        <textarea id="remark" name="remark" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                  placeholder="Enter your remarks here..."></textarea>
                    </div>

                    <div>
                        <label for="attachments" class="block text-sm font-medium text-gray-700">Add Attachments</label>
                        <input type="file" name="attachments[]" id="attachments" multiple
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" name="action" value="approve"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve
                        </button>
                        <button type="submit" name="action" value="reject"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection