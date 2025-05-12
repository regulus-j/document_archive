@extends('layouts.app')

@php
use App\Models\Office;

$currentUserCompany = auth()->user()->companies()->first();
$originatingOfficeId = auth()->user()->offices->first()->id ?? null;
$offices = $currentUserCompany
    ? Office::where('company_id', $currentUserCompany->id)
    ->where('id', '!=', $originatingOfficeId)
    ->get()
    : collect();
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center">
                <div class="bg-blue-600 p-2 rounded-lg mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
                <div>
                    <h1 class="text-xl font-bold text-black-600">Create New Document</h1>
                    <p class="text-sm text-gray-600">Add a new document </p>
                </div>
            </div>
            <a href="{{ route('documents.index') }}" 
               class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Document Information Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-medium text-gray-800">Document Information</h2>
                </div>
            </div>

            <div class="p-6 space-y-6">
    <!-- Basic Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Document Title -->
        <div class="space-y-2">
            <label for="title" class="block text-sm font-medium text-gray-700">Document Title</label>
            <input type="text" name="title" id="title" required
                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                placeholder="Enter document title">
            <p class="text-xs text-gray-500">Provide a clear, descriptive title for the document</p>
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3" 
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                placeholder="Enter document description">{{ old('description') }}</textarea>
            <p class="text-xs text-gray-500">Provide additional details about the document</p>
        </div>
    </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                            <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type
                                <span class="text-red-500">*</span></label>
                            <select name="document_type" id="document_type"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                required>
                                <option value="">Select Document Type</option>
                                <option value="memo" {{ old('document_type')=='memo' ? 'selected' : '' }}>Memorandum
                                </option>
                                <option value="letter" {{ old('document_type')=='letter' ? 'selected' : '' }}>Letter
                                </option>
                                <option value="report" {{ old('document_type')=='report' ? 'selected' : '' }}>Report
                                </option>
                                <option value="invoice" {{ old('document_type')=='invoice' ? 'selected' : '' }}>Invoice
                                </option>
                                <option value="contract" {{ old('document_type')=='contract' ? 'selected' : '' }}>
                                    Contract</option>
                                <option value="other" {{ old('document_type')=='other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>


                    <!-- Classification -->
                    <div class="space-y-2">
                        <label for="classification" class="block text-sm font-medium text-gray-700">Classification</label>
                        <select name="classification" id="classification"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="Public">Public</option>
                            <option value="Private">Private</option>
                        </select>
                    </div>
                </div>

                 <!-- Purpose Section -->
                 <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Purpose
                    </h3>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 mb-3">Select the purpose of this document. This will determine what actions recipients can take.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <label class="flex items-start cursor-pointer">
                                    <input type="radio" name="purpose" value="appropriate_action" 
                                        class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ old('purpose') == 'appropriate_action' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-700">Appropriate Action</span>
                                        <span class="block text-xs text-gray-500 mt-1">Recipient can approve, reject, reroute, return, or forward</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <label class="flex items-start cursor-pointer">
                                    <input type="radio" name="purpose" value="comment" 
                                        class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ old('purpose') == 'comment' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-700">Comment</span>
                                        <span class="block text-xs text-gray-500 mt-1">Recipient can only leave remarks</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <label class="flex items-start cursor-pointer">
                                    <input type="radio" name="purpose" value="disseminate_info" 
                                        class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ old('purpose') == 'disseminate_info' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-700">Disseminate Information</span>
                                        <span class="block text-xs text-gray-500 mt-1">Recipient can only mark as received</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Routing Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        Routing Information
                    </h3>
                    
                    <!-- Originating Office -->
                    <div class="space-y-2 mb-4">
                        <label for="from_office" class="block text-sm font-medium text-gray-700">Originating Office</label>
                        <input type="text"
                            id="from_office"
                            value="{{ auth()->user()->offices->first()->name ?? 'N/A' }}"
                            class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed"
                            readonly>
                        <input type="hidden"
                            name="from_office"
                            value="{{ auth()->user()->offices->first()->id }}">
                    </div>
                </div>

                <!-- Document Upload Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Document Files
                    </h3>

                    <!-- Main Document Upload -->
                    <div class="mb-6">
                        <label for="main-document" class="block text-sm font-medium text-gray-700 mb-2">Upload Main Document</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="main-document"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="main-document" name="upload" type="file" accept=".pdf,.doc,.docx"
                                            class="sr-only" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 10MB</p>
                            </div>
                        </div>
                        <div class="upload-feedback hidden mt-2 text-sm text-blue-600"></div>
                    </div>

                    <!-- Attachments Upload -->
                    <div>
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Upload Attachments</label>
                        <div id="attachments-container" class="mt-1 flex flex-col gap-4">
                            <!-- Initial attachments input -->
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center w-full">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="attachments"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload attachments</span>
                                            <input id="attachments" name="attachments[]" type="file" multiple
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG up to 10MB each</p>
                                </div>
                            </div>
                            <!-- File list to display selected attachments -->
                            <ul class="file-list mt-2 space-y-2"></ul>
                        </div>
                        <div class="mt-2 flex items-center">
                            <button type="button" id="add-attachment"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Another Attachment
                            </button>
                            <span class="text-xs text-gray-500 ml-2">(Maximum 5 attachments)</span>
                        </div>
                    </div>
                </div>

              <!-- Form Actions -->
<div class="border-t border-gray-200 pt-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <!-- Checkboxes -->
        <div class="flex items-center space-x-6 mb-4 md:mb-0">
            <label class="inline-flex items-center">
                <input type="checkbox" name="archive" value="1"
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Add to Archives</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="forward" value="1"
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Forward to user/s</span>
            </label>
        </div>
        
        <!-- Buttons -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('documents.index') }}"
                class="text-sm text-gray-700 hover:text-gray-500 transition-colors">Cancel</a>
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Submit Document
            </button>
        </div>
    </div>
</div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = "{{ route('documents.index') }}";
                    } else {
                        return response.json().then(data => {
                            console.error('Upload error:', data);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection