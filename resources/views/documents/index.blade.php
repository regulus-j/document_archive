@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Documents</h2>
        </div>
        <div class="pull-right">
            @can('document-create')
            <a class="btn btn-success btn-sm mb-2" href="{{ route('documents.create') }}"><i class="fa fa-plus"></i> Create New Document</a>
            @endcan
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Search </h2>
        </div>
        <form id="search-form" action="{{ route('documents.search') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" name="text" class="form-control" placeholder="Search by text">
                <div class="form-group">
                    <label for="image">Search by Image</label>
                    <input type="file" name="image" id="image-input" class="form-control">
                    <a class="btn btn-primary btn-sm mb-2" id="btn-opencam"><i class="fa fa-camera"></i> Open Camera</a>
                </div>
                <input type="submit" value="Search">
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="cameraModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    {{-- <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a1 1 0 00-1.414-1.414L13.586 8.586a1 1 0 01-1.414 0L9.414 5.414a1 1 0 00-1.414 1.414L12 10m0 0l-4.553 4.553a1 1 0 001.414 1.414L10.414 13.414a1 1 0 011.414 0l2.172 2.172a1 1 0 001.414-1.414L12 10z" />
                        </svg>
                    </div> --}}
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Capture Image</h3>
                        <div class="mt-2">
                            <video id="camfeed" autoplay class="w-full rounded-md"></video>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="snap" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Snap</button>
                <button id="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Title</th>
        <th>Uploader</th>
        <th>Uploaded</th>
        <th>Description</th>
        <th>Path</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($documents as $document)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $document->title }}</td>
        <td>{{ $document->user->first_name . ' ' . $document->user->last_name }}</td>
        <td>{{ $document->created_at }}</td>
        <td>{{ $document->description }}</td>
        <td>{{ $document->path }}</td>
        <td>
            <form action="{{ route('documents.destroy', $document->id, $document->path) }}" method="POST">
                <a class="btn btn-info btn-sm" href="{{ route('documents.show', $document->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                @can('document-edit')
                <a class="btn btn-primary btn-sm" href="{{ route('documents.edit', $document->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                @endcan

                @csrf
                @method('DELETE')

                @can('document-delete')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                @endcan
            </form>
            <form action="{{ route('documents.downloadFile', $document->id)  }}" method="get">
                @csrf
                @method('GET')

                <button type="submit">Download</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! $documents->links() !!}

<script>
    document.querySelector('#snap').addEventListener('click', function() {
        var canvas = document.createElement('canvas');
        canvas.width = 300;
        canvas.height = 300;
        var context = canvas.getContext('2d');
        context.drawImage(document.querySelector('#camfeed'), 0, 0, 300, 300);
        canvas.toBlob(function(blob) {
            var file = new File([blob], "snapshot.png", { type: "image/png" });
            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.querySelector('#image-input').files = dataTransfer.files;
            alert('Image captured and added to the form.');
            document.getElementById('cameraModal').classList.add('hidden');
        }, 'image/png');
    });

    document.querySelector('#closeModal').addEventListener('click', function() {
        document.getElementById('cameraModal').classList.add('hidden');
    });

    document.querySelector('#btn-opencam').addEventListener('click', function() {
        document.getElementById('cameraModal').classList.remove('hidden');
    });
</script>

<script>
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
            var video = document.querySelector('#camfeed');
            video.srcObject = stream;
            video.play();
        });
    } else {
        alert('Camera not found');
    }

    document.querySelector('#snap').addEventListener('click', function() {
        var canvas = document.createElement('canvas');
        canvas.width = 300;
        canvas.height = 300;
        var context = canvas.getContext('2d');
        context.drawImage(document.querySelector('#camfeed'), 0, 0, 300, 300);
        canvas.toBlob(function(blob) {
            var file = new File([blob], "snapshot.png", { type: "image/png" });
            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.querySelector('#image-input').files = dataTransfer.files;
            alert('Image captured and added to the form.');
        }, 'image/png');
    });
</script>
@endsection