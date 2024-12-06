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

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf
                    @method('POST')

        <div class="grid grid-cols-1 gap-6">
            {{-- Tracking Number --}}
            {{-- <div>
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
            </div> --}}

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
                            <label for="classification"
                                class="block text-sm font-medium text-gray-700">Classification</label>
                            <select name="classification" id="classification"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value="">Select Classification</option>
                                @foreach($categories as $id => $classification)
                                    <option value="{{ $id }}">{{ $classification }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="from_office" class="block text-sm font-medium text-gray-700">Originating
                                Office</label>
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

            <div>
                <label for="from_office" class="block text-sm font-medium text-gray-700">
                    Originating Office
                </label>
                <select name="from_office" id="from_office" 
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
                <label for="to_office" class="block text-sm font-medium text-gray-700">
                    Recipient Office
                </label>
                <select name="to_office" id="to_office" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        required>
                    <option value="">Select Recipient Office</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Recipient Users (optional) -->
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
            </div>

                        <div class="sm:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" maxlength="250"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter additional remarks (max 250 characters)"></textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="upload" class="block text-sm font-medium text-gray-700">Upload Document</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="upload"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="upload" name="upload" type="file" class="sr-only" required
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, DOC, DOCX, JPG, JPEG, PNG up to 10MB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit Document
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
</script>

@endsection