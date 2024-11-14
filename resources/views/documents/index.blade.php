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
        <th>Content</th>
        <th>Path</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($documents as $document)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $document->title }}</td>
        <td>{{ $document->uploader }}</td>
        <td>{{ $document->uploaded }}</td>
        <td>{{ $document->content }}</td>
        <td>{{ $document->path }}</td>
        <td>
            <form action="{{ route('documents.destroy', $document->id) }}" method="POST">
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
        </td>
    </tr>
    @endforeach
</table>

{!! $documents->links() !!}
@endsection