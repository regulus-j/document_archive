@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4 sm:mb-0">
                        Add New Document
                    </h1>
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were some problems with your input.</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('POST')

                    <div class="grid grid-cols-1 gap-6">
                        <div class="sm:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required></textarea>
                        </div>

                        <div>
                            <label for="classification" class="block text-sm font-medium text-gray-700">Classification</label>
                            <select name="classification" id="classification"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value="">Select Classification</option>
                                @foreach($categories as $id => $classification)
                                    <option value="{{ $id }}">{{ $classification }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purpose of Document</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                        <input type="checkbox" name="for[]" value="{{ $purpose }}"
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">{{ $purpose }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" maxlength="250"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter additional remarks (max 250 characters)"></textarea>
                        </div>

                        {{-- <div> --}}
                            {{-- <label for="from_office" class="block text-sm font-medium text-gray-700">Originating Office</label>
                            <select name="from_office" id="from_office"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value="">Select Office</option>
                                @foreach(Auth::user()->offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="to_office" class="block text-sm font-medium text-gray-700">Recipient Office</label>
                            <select name="to_office" id="to_office"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Recipient Users (optional)
                            </label>
                            <div class="mt-1 border border-gray-300 rounded-md max-h-48 overflow-y-auto p-2">
                                <div class="space-y-2">
                                    @foreach($users as $user)
                                        <div class="flex items-center user-checkbox" data-office-ids="{{ implode(',', $user->offices->pluck('id')->toArray()) }}">
                                            <input type="checkbox" 
                                                name="to_user_ids[]" 
                                                value="{{ $user->id }}" 
                                                id="user_{{ $user->id }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="user_{{ $user->id }}" class="ml-2 block text-sm text-gray-900">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Select multiple users if needed</p>
                        </div> --}}

                        <div class="sm:col-span-2">
                            <label for="upload">Upload Main Document</label>
                            <input name="upload" id="main-document" type="file" accept=".pdf,.doc,.docx">
                            <div class="upload-feedback hidden"></div>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="attachments[]">Upload Attachments</label>
                            <input name="attachments[]" id="attachments" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <button type="button" id="add-attachment" class="mt-2 inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Add Another Attachment
                            </button>

                            <script>
                                let attachmentCount = 1;
                                const maxAttachments = 5;

                                document.getElementById('add-attachment').addEventListener('click', function() {
                                    if (attachmentCount >= maxAttachments) return;

                                    const attachmentsDiv = this.parentElement;
                                    const newInput = document.createElement('input');
                                    newInput.name = 'attachments[]';
                                    newInput.type = 'file';
                                    newInput.accept = '.pdf,.doc,.docx,.jpg,.jpeg,.png';
                                    newInput.className = 'mt-2';

                                    attachmentsDiv.insertBefore(newInput, this);

                                    attachmentCount++;

                                    if (attachmentCount >= maxAttachments) {
                                        this.disabled = true;
                                    }
                                });
                            </script>
                            <ul class="file-list"></ul>
                        </div>


                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="archive" value="1"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Add to Archives</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="forward" value="1"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Forward to user/s</span>
                                </label>
                            </div>
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('to_office').addEventListener('change', function() {
        const selectedOfficeId = this.value;
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
        userCheckboxes.forEach(function(userCheckbox) {
            const officeIds = userCheckbox.getAttribute('data-office-ids').split(',');
    
            if (officeIds.includes(selectedOfficeId) || selectedOfficeId === '') {
                userCheckbox.style.display = 'flex'; // Show the user
            } else {
                userCheckbox.style.display = 'none'; // Hide the user
                // Uncheck the checkbox if hidden
                userCheckbox.querySelector('input[type="checkbox"]').checked = false;
            }
        });
    });

    // Main Document Upload Handler
    document.getElementById('main-document').addEventListener('change', function() {
        const uploadArea = this.closest('.sm:col-span-2');
        const feedback = uploadArea.querySelector('.upload-feedback');
        
        if (this.files.length > 0) {
            const file = this.files[0];
            
            // Update visual state
            uploadArea.classList.add('uploaded');
            
            // Show file name or feedback
            if (feedback) {
                feedback.textContent = file.name;
                feedback.classList.remove('hidden');
            }
        } else {
            // Reset state if no file
            uploadArea.classList.remove('uploaded');
            
            if (feedback) {
                feedback.textContent = '';
                feedback.classList.add('hidden');
            }
        }
    });

    // Attachments Upload Handler
    document.getElementById('attachments').addEventListener('change', function() {
        const uploadArea = this.closest('.sm:col-span-2');
        const fileList = uploadArea.querySelector('.file-list');
        
        // Clear previous file list
        if (fileList) {
            fileList.innerHTML = '';
        }
        
        if (this.files.length > 0) {
            // Update visual state
            uploadArea.classList.add('uploaded');
            
            // Populate file list
            if (fileList) {
                Array.from(this.files).forEach(file => {
                    const listItem = document.createElement('li');
                    listItem.textContent = file.name;
                    fileList.appendChild(listItem);
                });
            }
        } else {
            // Reset state if no files
            uploadArea.classList.remove('uploaded');
        }
    });
</script>


@endsection