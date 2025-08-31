@extends('layouts.app')

@section('content')
        <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Box -->
            <div class="bg-white rounded-xl mb-8 border border-blue-200/80 overflow-hidden">
                <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Create Document</h1>
                            <p class="text-sm text-gray-500">Create and upload new document</p>
                        </div>
                    </div>
                <a href="{{ route('documents.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Document Information Card -->
            <div class="bg-white rounded-xl overflow-hidden border border-blue-200/80 transition-all duration-300 hover:border-blue-300/80">
                <div class="bg-white p-6 border-b border-blue-200/60">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Document Information</h2>
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
                            <label for="category" class="block text-sm font-medium text-gray-700">Document Category
                                <span class="text-red-500">*</span></label>
                            <select name="category" id="category"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                required>
                                <option value="">Select Document Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Classification -->
                        <div class="space-y-2">
                            <label for="classification"
                                class="block text-sm font-medium text-gray-700">Classification</label>
                            <select name="classification" id="classification"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="Public">Public</option>
                                <option value="Private">Private</option>
                            </select>
                        </div>

                        <!-- Allowed Viewers (only for Private) -->
                        <div id="allowed-viewers-section" class="space-y-2 mt-4 hidden">
                            <label for="viewer_office" class="block text-sm font-medium text-gray-700">Select Office to
                                Filter Users</label>
                            <select id="viewer_office" class="w-full rounded-lg border-gray-300 shadow-sm">
                                <option value="">-- Select Office --</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                            <label class="block text-sm font-medium text-gray-700 mt-2">Select Allowed Viewers</label>
                            <div id="allowed-viewers-list" class="max-h-48 overflow-y-auto border rounded p-2 bg-gray-50">
                                @foreach ($users as $user)
                                    <div class="viewer-row" data-office="{{ $user->offices->pluck('id')->implode(',') }}">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="allowed_viewers[]" value="{{ $user->id }}"
                                                class="viewer-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $user->first_name }}
                                                {{ $user->last_name }} <span class="text-xs text-gray-500">
                                                    @foreach ($user->offices as $o)
                                                        {{ $o->name }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </span></span>
                                        </label>
                                    </div>
                                @endforeach
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
                            <label for="from_office" class="block text-sm font-medium text-gray-700">Originating
                                Office</label>
                            @php
                                $userOffice = auth()->user()->offices->first();
                            @endphp
                            <input type="text" id="from_office"
                                value="{{ $userOffice ? $userOffice->name : 'No Office Assigned' }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed" readonly>
                            @if($userOffice)
                                <input type="hidden" name="from_office" value="{{ $userOffice->id }}">
                            @else
                                <p class="text-red-500 text-sm mt-1">Please contact your administrator to be assigned to an office.</p>
                            @endif
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
                            <label for="main-document" class="block text-sm font-medium text-gray-700 mb-2">Upload Main
                                Document</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
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
                                            <input id="main-document" name="main_document" type="file"
                                                accept=".pdf,.doc,.docx" class="sr-only" required>
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
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Upload
                                Attachments</label>
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
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG up to 10MB each (Maximum 5 attachments)</p>
                                </div>
                            </div>
                            <!-- Attachment Files Preview -->
                            <div id="attachment-files-preview" class="hidden mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Selected Attachments</h4>
                                <ul id="attachment-files-list" class="divide-y divide-gray-200 border border-gray-200 rounded-md overflow-hidden bg-white">
                                    <!-- Selected files will be displayed here -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="border-t border-blue-200/60 pt-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <!-- Checkboxes -->
                            <div class="flex items-center space-x-6 mb-4 md:mb-0">
                                <label class="inline-flex items-center bg-white px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="archive" value="1"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Add to Archives</span>
                                </label>
                                <label class="inline-flex items-center bg-white px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="forward" value="1"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Forward to user/s</span>
                                </label>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('documents.index') }}"
                                    class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">Cancel</a>
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
    </script>

    <script>
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

            // --- File upload feedback logic ---

            // Main document feedback
            const mainDocInput = document.getElementById('main-document');
            const mainDocFeedback = document.querySelector('.upload-feedback');
            if (mainDocInput && mainDocFeedback) {
                mainDocInput.addEventListener('change', function() {
                    if (mainDocInput.files.length > 0) {
                        mainDocFeedback.textContent = 'Selected: ' + mainDocInput.files[0].name;
                        mainDocFeedback.classList.remove('hidden');
                    } else {
                        mainDocFeedback.textContent = '';
                        mainDocFeedback.classList.add('hidden');
                    }
                });
            }

            // Attachments feedback - Enhanced version with file list and sizes
            const attachmentsInput = document.getElementById('attachments');
            const attachmentPreview = document.getElementById('attachment-files-preview');
            const attachmentList = document.getElementById('attachment-files-list');

            if (attachmentsInput) {
                attachmentsInput.addEventListener('change', function() {
                    console.log('Attachments selected:', this.files.length);

                    // Simple validation for max 5 files
                    if (this.files.length > 5) {
                        alert('Maximum 5 attachments allowed. Please select fewer files.');
                        this.value = '';
                        attachmentPreview.classList.add('hidden');
                        return;
                    }

                    // Display selected files with names and sizes
                    if (this.files.length > 0) {
                        attachmentList.innerHTML = '';

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
                                <button type="button" onclick="removeAttachmentFile(${index})" class="text-red-500 hover:text-red-700 text-sm">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            `;
                            attachmentList.appendChild(li);
                        });

                        attachmentPreview.classList.remove('hidden');
                    } else {
                        attachmentPreview.classList.add('hidden');
                    }
                });
            }

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

            // Classification change handling
            const classification = document.getElementById('classification');
            const allowedViewersSection = document.getElementById('allowed-viewers-section');
            const viewerOffice = document.getElementById('viewer_office');
            const viewerRows = document.querySelectorAll('.viewer-row');

            function toggleAllowedViewers() {
                if (classification.value === 'Private') {
                    allowedViewersSection.classList.remove('hidden');
                } else {
                    allowedViewersSection.classList.add('hidden');
                }
            }
            classification.addEventListener('change', toggleAllowedViewers);
            toggleAllowedViewers();

            if (viewerOffice) {
                viewerOffice.addEventListener('change', function() {
                    const selectedOffice = this.value;
                    viewerRows.forEach(function(row) {
                        const offices = row.getAttribute('data-office').split(',');
                        if (!selectedOffice || offices.includes(selectedOffice)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Form submission handling
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading Document...
                        `;
                    }
                });
            }
        });
    </script>

    <script>
    // Global function to remove individual attachment file
    function removeAttachmentFile(index) {
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
