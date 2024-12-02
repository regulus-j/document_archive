@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Release Document
        </h1>
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clipRule="evenodd" />
            </svg>
            Back to List
        </a>
    </div>
    
    <div class="bg-white shadow-2xl rounded-xl overflow-hidden max-w-2xl mx-auto">
        <div class="p-8">
            <div class="space-y-6">
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <h2 class="text-xl font-semibold text-blue-800 mb-3">Document Details</h2>
                    <div class="grid grid-cols-2 gap-3 text-gray-700">
                        <div>
                            <span class="font-medium text-gray-500">Tracking Number:</span>
                            <p>{{ $document->tracking_number }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Title:</span>
                            <p>{{ $document->title }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="font-medium text-gray-500">Description:</span>
                            <p>{{ $document->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500">
                    <h2 class="text-xl font-semibold text-green-800 mb-3">Release Information</h2>
                    <div class="grid grid-cols-2 gap-3 text-gray-700">
                        <div>
                            <span class="font-medium text-gray-500">From:</span>
                            <p>{{ $document->transaction->fromOffice->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">To:</span>
                            <p>{{ $document->transaction->toOffice->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Classification:</span>
                            <p>{{ $document->classification }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('documents.release', $document) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="release_remarks" class="block text-sm font-medium text-gray-700 mb-2">
                            Release Remarks
                        </label>
                        <textarea 
                            name="release_remarks" 
                            id="release_remarks" 
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                            placeholder="Add any remarks about the document release..."
                        ></textarea>
                    </div>
                    <div class="flex space-x-4">
                        <button 
                            type="submit" 
                            class="flex-1 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-300 flex items-center justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                            </svg>
                            Confirm Release
                        </button>
                        <a 
                            href="{{ route('documents.show', $document) }}"
                            class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition duration-300 text-center"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection