@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Add New Document</h1>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8 max-w-2xl mx-auto">
        @csrf
        <div class="grid grid-cols-1 gap-6">
            {{-- Tracking Number --}}
            <div>
                <label for="tracking_number" class="block text-sm font-medium text-gray-700">
                    Tracking Number
                </label>
                <div class="mt-1">
                    <input type="text" name="tracking_number" id="tracking_number" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           placeholder="2021-1020-2890-0049" 
                           required
                           readonly
                           value="{{ $tracking }}">
                    <p class="mt-2 text-sm text-gray-500">Ensure the tracking number matches the physical document.</p>
                </div>
            </div>

            {{-- Title and Description --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">
                    Title and Description
                </label>
                <div class="mt-1 space-y-2">
                    <input type="text" name="title" id="title" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           placeholder="Document Title" 
                           required>
                    <input type="text" name="description" id="description" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                           placeholder="Document Description" 
                           required>
                </div>
            </div>

            {{-- Document Type --}}
            <div>
                <label for="classification" class="block text-sm font-medium text-gray-700">
                    Classification
                </label>
                <select name="classification" id="type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        required>
                    <option value="">Select Document Classification</option>
                    <option value="Unclassified">Unclassified</option>
                    <option value="Confidential">Confidential</option>
                    <option value="Secret">Secret</option>
                    <option value="Top Secret">Top Secret</option>
                </select>
            </div>

            {{-- Purpose Checkboxes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Purpose of Document
                </label>
                <div class="mt-2 space-y-2">
                    @php
                        $purposes = [
                            'Appropriate action', 
                            'Information', 
                            'Recommendation', 
                            'Approval', 
                            'Signature'
                        ];
                    @endphp
                    @foreach($purposes as $purpose)
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="for[]" 
                                   value="{{ $purpose }}" 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label class="ml-2 block text-sm text-gray-900">
                                {{ $purpose }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="originating_office" class="block text-sm font-medium text-gray-700">
                    Originating Office
                </label>
                <select name="originating_office" id="originating_office" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                    <option value="">Select Originating Office</option>
                    @foreach(Auth::user()->offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Recipient Office --}}
            <div>
                <label for="recipient_office" class="block text-sm font-medium text-gray-700">
                    Recipient Office
                </label>
                <select name="recipient_office" id="recipient_office" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        required>
                    <option value="">Select Recipient Office</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Remarks --}}
            <div>
                <label for="remarks" class="block text-sm font-medium text-gray-700">
                    Remarks
                </label>
                <textarea name="remarks" id="remarks" 
                          rows="3"
                          maxlength="250"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                          placeholder="Enter additional remarks (max 250 characters)"></textarea>
            </div>

            {{-- File Upload --}}
            <div>
                <label for="upload" class="block text-sm font-medium text-gray-700">
                    Upload Document
                </label>
                <div class="mt-1 flex items-center space-x-4">
                    <input type="file" 
                           name="upload" 
                           id="upload" 
                           class="block w-full text-sm text-gray-500 
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100" 
                           required>
                    <button type="button" 
                            id="btn-opencam"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                        <i class="fas fa-camera mr-2"></i>Open Camera
                    </button>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <button type="submit" 
                    class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit Document
            </button>
        </div>
    </form>
</div>

@include('documents.partials.webcam')
@endsection