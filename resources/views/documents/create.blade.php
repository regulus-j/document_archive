@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
    <!-- Header Box -->
    <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
        <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Add New Document</h1>
                    <p class="text-sm text-gray-500">Create and upload a new document to the system</p>
                </div>
            </div>
            <div>
                <a href="{{ route('documents.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Validation Errors -->
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
                    <h3 class="text-sm font-medium text-red-800">There were some problems with your input.</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100 mb-8">
        <div class="bg-white px-6 py-4 border-b border-blue-200">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-lg font-medium text-gray-800">Document Information</h2>
            </div>
        </div>

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('POST')

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   required>
                            <p class="text-xs text-gray-500">Enter a descriptive title for the document</p>
                        </div>
                        <!-- Description -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                      required></textarea>
                            <p class="text-xs text-gray-500">Provide a brief description of the document's content</p>
                        </div>
                        <!-- Classification -->
                        <div class="space-y-2">
                            <label for="classification" class="block text-sm font-medium text-gray-700">Classification</label>
                            <select name="classification" id="classification"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required>
                                <option value="">Select Classification</option>
                                @foreach($categories as $id => $classification)
                                    <option value="{{ $id }}">{{ $classification }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500">Select the appropriate document classification</p>
                        </div>
                        <!-- Remarks -->
                        <div class="space-y-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" maxlength="250"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                      placeholder="Enter additional remarks (max 250 characters)"></textarea>
                            <p class="text-xs text-gray-500"><span id="char-count">0</span>/250 characters</p>
                        </div>
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
                        Purpose of Document
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" name="for[]" value="{{ $purpose }}"
                                       id="purpose-{{ $loop->index }}" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="purpose-{{ $loop->index }}" class="text-sm text-gray-700">{{ $purpose }}</label>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select all purposes that apply to this document</p>
                </div>

                <!-- Routing Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        Routing Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Originating Office -->
                        <div class="space-y-2">
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
                        <!-- Recipient Office -->
                        <div class="space-y-2">
                                <label for="from_office" class="block text-sm font-medium text-gray-700">Recipient Office</label>
                                <select name="recipient_office" id="recipient_office"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        required>
                                    <option value="">Select Recipient Office</option>
                                    @foreach($offices as $office)
                                        @if($office->id != auth()->user()->offices->first()->id)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500">Select the office to which this document will be sent</p>
                            </div>
                        <!-- Recipients -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="recipients" class="block text-sm font-medium text-gray-700">Recipients</label>
                            <select name="recipients[]" id="recipients" multiple
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all">
                                @if(isset($users) && $users->count())
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No users available</option>
                                @endif
                            </select>
                            <p class="text-xs text-gray-500">Select one or more recipients for this document</p>
                        </div>
                    </div>
                </div>

                <!-- Document Upload Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
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
                                            <span>Upload files</span>
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
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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

                <!-- Options Section -->
                <div class="pt-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
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
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('documents.index') }}"
                               class="text-sm text-gray-700 hover:text-gray-500 transition-colors">Cancel</a>
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Character counter for remarks
    const remarksField = document.getElementById('remarks');
    const charCount = document.getElementById('char-count');
    remarksField.addEventListener('input', function () {
        const currentLength = this.value.length;
        charCount.textContent = currentLength;
        if (currentLength > 250) {
            charCount.classList.add('text-red-600');
        } else {
            charCount.classList.remove('text-red-600');
        }
    });

    // Office selection and recipients filtering
    const officeSelect = document.getElementById('office_id');
    const recipientsSelect = document.getElementById('recipients');
    officeSelect.addEventListener('change', function () {
        const officeId = this.value;
        // Clear recipients if no office is selected
        while (recipientsSelect.options.length > 0) {
            recipientsSelect.remove(0);
        }
        if (!officeId) return;

        fetch(`/api/users?office_id=${officeId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.first_name} ${user.last_name}`;
                    recipientsSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No users available";
                recipientsSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error fetching users:', error));
    });

    // Main Document Upload Handler
    const mainDocumentInput = document.getElementById('main-document');
    const mainDocumentFeedback = document.querySelector('.upload-feedback');
    mainDocumentInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (file.size > 10 * 1024 * 1024) { // 10MB limit
                mainDocumentFeedback.textContent = 'Error: File size exceeds 10MB limit';
                mainDocumentFeedback.classList.add('text-red-600');
                mainDocumentFeedback.classList.remove('text-blue-600');
                this.value = ''; // clear input
            } else {
                mainDocumentFeedback.textContent = `File selected: ${file.name}`;
                mainDocumentFeedback.classList.remove('hidden');
            }
        } else {
            mainDocumentFeedback.classList.add('hidden');
        }
    });

    // Attachments Upload Handler
    const attachmentsInput = document.getElementById('attachments');
    const fileList = document.querySelector('.file-list');

    function updateAttachmentsList() {
        fileList.innerHTML = '';
        document.querySelectorAll('input[name="attachments[]"]').forEach(input => {
            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const listItem = document.createElement('li');
                    if (file.size > 10 * 1024 * 1024) {
                        listItem.textContent = `${file.name} - Error: File size exceeds 10MB limit`;
                        listItem.className = 'text-sm text-red-600 flex items-center';
                    } else {
                        listItem.className = 'text-sm text-gray-600 flex items-center';
                        const icon = document.createElement('span');
                        icon.className = 'mr-2 text-blue-500';
                        icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                        listItem.appendChild(icon);
                        listItem.appendChild(document.createTextNode(file.name));
                    }
                    fileList.appendChild(listItem);
                });
            }
        });
    }
    // Attach change event for original attachments input
    attachmentsInput.addEventListener('change', updateAttachmentsList);

    // Add Attachment Button Handler
    let attachmentCount = 1;
    const maxAttachments = 5;
    const addAttachmentBtn = document.getElementById('add-attachment');
    const attachmentsContainer = document.getElementById('attachments-container');

    addAttachmentBtn.addEventListener('click', function () {
        if (attachmentCount >= maxAttachments) return;
        const newInput = document.createElement('input');
        newInput.name = 'attachments[]';
        newInput.type = 'file';
        newInput.accept = '.pdf,.doc,.docx,.jpg,.jpeg,.png';
        newInput.className = 'mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100';
        // Append the new input at the end of the container (above the file list)
        attachmentsContainer.appendChild(newInput);
        // Add change event listener to update the file list
        newInput.addEventListener('change', updateAttachmentsList);
        attachmentCount++;
        if (attachmentCount >= maxAttachments) {
            addAttachmentBtn.disabled = true;
            addAttachmentBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
document.querySelector('form').addEventListener('submit', function (event) {
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
</script>
@endsection