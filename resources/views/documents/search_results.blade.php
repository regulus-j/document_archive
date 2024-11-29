@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Search Results</h2>
    @if($documents->isEmpty())
        <p>No documents found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Uploader</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td>{{ $document->uploader }}</td>
                        <td>
                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection