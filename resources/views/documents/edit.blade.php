@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ __('Edit Document') }}</h1>
                        <p class="text-sm text-gray-500">Update document details and attachments</p>
                    </div>
                </div>
                <a href="{{ route('documents.index') }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-white border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800"><strong>Whoops!</strong> There were some problems with your input.</p>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-white border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-r-lg shadow-md"
                role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-white border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md"
                role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100">
            <form id="editForm" action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="p-6" onsubmit="console.log('Form submitting...', this.method, this.action, new FormData(this));">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="title">Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            value="{{ old('title', $document->title) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter document title"
                            required
                        >
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="description">Description</label>
                        <textarea 
                            name="description" 
                            id="description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="4"
                            placeholder="Enter document description"
                            required
                        >{{ old('description', $document->description) }}</textarea>
                    </div>
                    
                    <!-- Classification -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="classification">Classification</label>
                        <select 
                            name="classification" 
                            id="classification"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                            onchange="toggleAllowedViewers(this.value)"
                        >
                            <option value="">Select Classification</option>
                            <option value="Public" {{ (old('classification', $document->classification) == 'Public') ? 'selected' : '' }}>Public</option>
                            <option value="Private" {{ (old('classification', $document->classification) == 'Private') ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>
                    
                    <!-- Allowed Viewers (for Private classification) -->
                    <div id="allowed-viewers-section" style="{{ (old('classification', $document->classification) == 'Private') ? '' : 'display: none;' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allowed Viewers</label>
                        <div class="mt-2 space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md p-3">
                            @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            type="checkbox" 
                                            name="allowed_viewers[]" 
                                            value="{{ $user->id }}" 
                                            id="viewer-{{ $user->id }}"
                                            {{ $document->allowedViewers->contains('user_id', $user->id) ? 'checked' : '' }}
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="viewer-{{ $user->id }}" class="font-medium text-gray-700">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </label>
                                        <p class="text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Category Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="category">Category</label>
                        <select 
                            name="category" 
                            id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Select Category</option>
                            @foreach($categories as $id => $category)
                                <option 
                                    value="{{ $id }}" 
                                    {{ old('category', $document->categories->first()->id ?? '') == $id ? 'selected' : '' }}
                                >
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- From/To Office -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="from_office">From Office</label>
                            <select 
                                name="from_office" 
                                id="from_office"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select From Office</option>
                                @foreach($userOffice as $id => $name)
                                    <option 
                                        value="{{ $id }}" 
                                        {{ old('from_office', optional($document->transaction)->from_office) == $id ? 'selected' : '' }}
                                    >
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="to_office">To Office</label>
                            <select 
                                name="to_office" 
                                id="to_office"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select To Office</option>
                                @foreach($offices as $office)
                                    <option 
                                        value="{{ $office->id }}" 
                                        {{ old('to_office', optional($document->transaction)->to_office) == $office->id ? 'selected' : '' }}
                                    >
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="remarks">Remarks</label>
                        <textarea 
                            name="remarks" 
                            id="remarks"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="3"
                            placeholder="Enter any additional remarks"
                            maxlength="250"
                        >{{ old('remarks', $document->remarks) }}</textarea>
                    </div>

                    <!-- File Upload Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Current File</label>
                        @if($document->path)
                            <div class="flex items-center space-x-2 mb-4 p-3 bg-white rounded-md border border-gray-200">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <a href="{{ asset('storage/' . $document->path) }}" class="text-blue-500 hover:text-blue-600 hover:underline" target="_blank">
                                    {{ basename($document->path) }}
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500 mb-4 p-3 bg-white rounded-md border border-gray-200">No file currently attached</p>
                        @endif

                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-4" for="main_document">Upload New File</label>
                        <input 
                            type="file" 
                            name="main_document" 
                            id="main_document"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-500 file:text-white file:px-4 file:py-2"
                            accept="jpeg,png,jpg,gif,pdf,docx"
                        >
                        <p class="text-xs text-gray-500 mt-1">Supported formats: jpeg, png, jpg, gif, pdf, docx (Max: 10MB)</p>
                    </div>

                    <!-- Attachment Upload Section -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Upload Attachments</label>
                        <input 
                            type="file" 
                            name="attachments[]" 
                            id="attachments"
                            multiple
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-500 file:text-white file:px-4 file:py-2"
                            accept="jpeg,png,jpg,gif,pdf,docx"
                        >
                        <p class="mt-2 text-xs text-gray-500">You can upload multiple attachments (Max: 10MB each).</p>
                        
                        <!-- New Attachment Files Preview -->
                        <div id="new-attachment-files-preview" class="hidden mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">New Attachments to Upload</h4>
                            <ul id="new-attachment-files-list" class="divide-y divide-gray-200 border border-gray-200 rounded-md overflow-hidden bg-white">
                                <!-- Selected files will be displayed here -->
                            </ul>
                        </div>

                        @if($document->attachments->count())
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Existing Attachments</h4>
                                <ul class="divide-y divide-gray-200 border border-gray-200 rounded-md overflow-hidden bg-white">
                                    @foreach($document->attachments as $attachment)
                                        <li class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <div>
                                                    <a href="{{ asset('storage/' . $attachment->path) }}" class="text-blue-500 hover:text-blue-600 hover:underline" target="_blank">
                                                        {{ $attachment->filename }}
                                                    </a>
                                                    @if($attachment->size)
                                                        <div class="text-xs text-gray-500">
                                                            {{ number_format($attachment->size / 1024, 2) }} KB
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="button" onclick="deleteAttachment({{ $document->id }}, {{ $attachment->id }})" class="text-red-500 hover:text-red-700 flex items-center">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Archive and Forward options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="archive" 
                                id="archive" 
                                value="1" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="archive" class="ml-2 block text-sm text-gray-700">
                                Archive this document
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="forward" 
                                id="forward" 
                                value="1" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="forward" class="ml-2 block text-sm text-gray-700">
                                Forward after update
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center pt-4">
                        <button 
                            type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Update Document
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Popup Notification Styles -->
<style>
    .popup-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 500px;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
        font-family: system-ui, -apple-system, sans-serif;
    }
    
    .popup-notification.show {
        transform: translateX(0);
    }
    
    .popup-notification.success {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
        border-left: 4px solid #047857;
    }
    
    .popup-notification.error {
        background: linear-gradient(45deg, #ef4444, #dc2626);
        color: white;
        border-left: 4px solid #b91c1c;
    }
    
    .popup-notification .popup-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .popup-notification .popup-icon {
        margin-right: 12px;
        width: 24px;
        height: 24px;
    }
    
    .popup-notification .popup-message {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
    }
    
    .popup-notification .popup-close {
        margin-left: 12px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        line-height: 1;
    }
    
    .popup-notification .popup-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>

<script>
// Function to show popup notifications
function showPopup(message, type = 'success') {
    // Remove any existing popups
    const existingPopups = document.querySelectorAll('.popup-notification');
    existingPopups.forEach(popup => popup.remove());
    
    // Create popup element
    const popup = document.createElement('div');
    popup.className = `popup-notification ${type}`;
    
    const iconSvg = type === 'success' 
        ? '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        : '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
    
    popup.innerHTML = `
        <div class="popup-content">
            ${iconSvg}
            <span class="popup-message">${message}</span>
            <button class="popup-close" onclick="closePopup(this)">&times;</button>
        </div>
    `;
    
    // Add to body
    document.body.appendChild(popup);
    
    // Show popup
    setTimeout(() => popup.classList.add('show'), 100);
    
    // Auto close after 5 seconds
    setTimeout(() => closePopup(popup.querySelector('.popup-close')), 5000);
}

// Function to close popup
function closePopup(closeBtn) {
    const popup = closeBtn.closest('.popup-notification');
    popup.classList.remove('show');
    setTimeout(() => popup.remove(), 300);
}

<script>
function toggleAllowedViewers(classification) {
    const allowedViewersSection = document.getElementById('allowed-viewers-section');
    if (classification === 'Private') {
        allowedViewersSection.style.display = 'block';
    } else {
        allowedViewersSection.style.display = 'none';
    }
}

// Function to delete attachment via JavaScript
function deleteAttachment(documentId, attachmentId) {
    if (confirm('Are you sure you want to delete this attachment?')) {
        // Create a temporary form to submit the DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/documents/${documentId}/delete-attachment?attachment_id=${attachmentId}`;
        
        // Add CSRF token
        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_token';
        csrfField.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfField);
        
        // Add method override for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
}

// Ensure form submits with proper method override
document.addEventListener('DOMContentLoaded', function() {
    // Show popup notifications for session messages
    @if(session('success'))
        showPopup('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showPopup('{{ session('error') }}', 'error');
    @endif
    
    @if($errors->any())
        showPopup('Please check the form for errors.', 'error');
    @endif

    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            // Ensure _method field exists and is set to PUT
            let methodField = editForm.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                editForm.appendChild(methodField);
            } else {
                methodField.value = 'PUT';
            }
            console.log('Form submitting with method override:', methodField.value);
            
            // Disable submit button and show loading state
            const submitBtn = editForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating Document...
                `;
            }
        });
    }
    
    // Enhanced attachments preview functionality
    const attachmentsInput = document.getElementById('attachments');
    const newAttachmentPreview = document.getElementById('new-attachment-files-preview');
    const newAttachmentList = document.getElementById('new-attachment-files-list');
    
    if (attachmentsInput) {
        attachmentsInput.addEventListener('change', function() {
            console.log('New attachments selected:', this.files.length);
            
            // Display selected files with names and sizes
            if (this.files.length > 0) {
                newAttachmentList.innerHTML = '';
                
                Array.from(this.files).forEach((file, index) => {
                    const fileSize = formatFileSize(file.size);
                    const fileIcon = getFileIcon(file.name);
                    
                    const li = document.createElement('li');
                    li.className = 'flex items-center justify-between p-3 hover:bg-gray-50';
                    li.innerHTML = `
                        <div class="flex items-center">
                            ${fileIcon}
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">${file.name}</div>
                                <div class="text-xs text-gray-500">${fileSize}</div>
                            </div>
                        </div>
                        <button type="button" onclick="removeNewAttachmentFile(${index})" class="text-red-500 hover:text-red-700 text-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    `;
                    newAttachmentList.appendChild(li);
                });
                
                newAttachmentPreview.classList.remove('hidden');
            } else {
                newAttachmentPreview.classList.add('hidden');
            }
        });
    }
});

// Helper function to format file sizes
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Helper function to get file type icon
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const iconClass = 'h-5 w-5 text-gray-400';
    
    switch(ext) {
        case 'pdf':
            return `<svg class="${iconClass}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>`;
        case 'doc':
        case 'docx':
            return `<svg class="${iconClass}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>`;
        case 'jpg':
        case 'jpeg':
        case 'png':
            return `<svg class="${iconClass}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>`;
        default:
            return `<svg class="${iconClass}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>`;
    }
}

// Function to remove individual new attachment file
function removeNewAttachmentFile(index) {
    const attachmentsInput = document.getElementById('attachments');
    const dt = new DataTransfer();
    
    // Re-add all files except the one to remove
    Array.from(attachmentsInput.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    // Update the input files
    attachmentsInput.files = dt.files;
    
    // Trigger change event to update the preview
    attachmentsInput.dispatchEvent(new Event('change'));
}
</script>
@endsection

