@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Document</h2>
        <a href="{{ route('documents.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-2"></i>Back
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

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2" for="title">Title</label>
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

            <div>
                <label class="block text-gray-700 font-bold mb-2" for="description">Description</label>
                <textarea 
                    name="description" 
                    id="description"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4"
                    placeholder="Enter document description"
                    required
                >{{ old('description', $document->description) }}</textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2" for="classification">Classification</label>
                <select 
                    name="classification" 
                    id="classification"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                    <option value="">Select Classification</option>
                    @foreach($categories as $id => $category)
                        <option 
                            value="{{ $id }}" 
                            {{ old('classification', $document->categories->first()->id ?? '') == $id ? 'selected' : '' }}
                        >
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="from_office">From Office</label>
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
                                {{ old('from_office', $document->transaction->from_office) == $id ? 'selected' : '' }}
                            >
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="to_office">To Office</label>
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

            <div>
                <label class="block text-gray-700 font-bold mb-2" for="remarks">Remarks</label>
                <textarea 
                    name="remarks" 
                    id="remarks"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Enter any additional remarks"
                    maxlength="250"
                >{{ old('remarks', $document->remarks) }}</textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Current File</label>
                @if($document->path)
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-file text-gray-500"></i>
                        <a href="{{ asset('storage/' . $document->path) }}" class="text-blue-500 hover:text-blue-600" target="_blank">
                            {{ basename($document->path) }}
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 mb-4">No file currently attached</p>
                @endif

                <label class="block text-gray-700 font-bold mb-2" for="upload">Upload New File</label>
                <input 
                    type="file" 
                    name="upload" 
                    id="upload"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-500 file:text-white file:px-4 file:py-2"
                    accept="jpeg,png,jpg,gif,pdf,docx"
                >
                <p class="text-xs text-gray-500 mt-1">Supported formats: jpeg, png, jpg, gif, pdf, docx (Max: 10MB)</p>
            </div>

            <!-- Attachment Upload Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Attachments</label>
                <input 
                    type="file" 
                    name="attachments[]" 
                    multiple
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    accept="jpeg,png,jpg,gif,pdf,docx"
                >
                <p class="mt-2 text-sm text-gray-500">You can upload multiple attachments (Max: 10MB each).</p>

                @if($document->attachments->count())
                    <div class="mt-4">
                        <h4 class="text-gray-700 font-semibold mb-2">Existing Attachments</h4>
                        <ul class="list-disc list-inside">
                            @foreach($document->attachments as $attachment)
                                <li class="flex items-center justify-between">
                                    <a href="{{ asset('storage/' . $attachment->path) }}" class="text-blue-500 hover:text-blue-600" target="_blank">
                                        {{ $attachment->filename }}
                                    </a>
                                    <form action="{{ route('documents.attachments.destroy', $attachment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this attachment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="text-center">
                <button 
                    type="submit" 
                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out flex items-center justify-center mx-auto"
                >
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection