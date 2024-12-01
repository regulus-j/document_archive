@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New Document</h1>
        <a href="{{ route('documents.index') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0066FF] hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] transition-colors">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Whoops! There were some problems with your input.</p>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow-md rounded-lg p-6">
        @csrf

        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title"
                    class="mt-1 focus:ring-[#0066FF] focus:border-[#0066FF] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="Enter document title" required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <input type="text" name="description" id="description"
                    class="mt-1 focus:ring-[#0066FF] focus:border-[#0066FF] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="Enter document description" required>
            </div>

            <div>
                <label for="file-input" class="block text-sm font-medium text-gray-700">Upload File</label>
                <div class="mt-1 flex items-center space-x-4">
                    <input type="file" id="file-input" name="upload" class="sr-only" required>
                    <label for="file-input"
                        class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF]">
                        <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Choose file
                    </label>
                    <button type="button" id="btn-opencam"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF]">
                        <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Open Camera
                    </button>
                </div>
                <div id="file-name" class="mt-2 text-sm text-gray-500"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0066FF] hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] transition-colors">
                    Submit
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Camera Modal -->
<div id="cameraModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Capture Image</h3>
                        <div class="mt-2">
                            <video id="camfeed" autoplay class="w-full rounded-md"></video>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="snap" type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#0066FF] text-base font-medium text-white hover:bg-[#0052CC] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] sm:ml-3 sm:w-auto sm:text-sm transition-colors">Snap</button>
                <button id="closeModal" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0066FF] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">Cancel</button>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif

<script>
    document.querySelector('#file-input').addEventListener('change', function (e) {
        var fileName = e.target.files[0].name;
        document.querySelector('#file-name').textContent = 'Selected file: ' + fileName;
    });

    document.querySelector('#snap').addEventListener('click', function () {
        var canvas = document.createElement('canvas');
        canvas.width = 300;
        canvas.height = 300;
        var context = canvas.getContext('2d');
        context.drawImage(document.querySelector('#camfeed'), 0, 0, 300, 300);
        canvas.toBlob(function (blob) {
            var file = new File([blob], "snapshot.png", { type: "image/png" });
            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.querySelector('#file-input').files = dataTransfer.files;
            document.querySelector('#file-name').textContent = 'Selected file: snapshot.png';
            alert('Image captured and added to the form.');
            document.getElementById('cameraModal').classList.add('hidden');
        }, 'image/png');
    });

    document.querySelector('#closeModal').addEventListener('click', function () {
        document.getElementById('cameraModal').classList.add('hidden');
    });

    document.querySelector('#btn-opencam').addEventListener('click', function () {
        document.getElementById('cameraModal').classList.remove('hidden');
    });

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
            var video = document.querySelector('#camfeed');
            video.srcObject = stream;
            video.play();
        });
    } else {
        alert('Camera not found');
    }
</script>
@endsection