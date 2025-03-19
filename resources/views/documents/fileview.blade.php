@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $document->title }}</h1>
        <p>{{ $document->description }}</p>

        @if($document->file)
            <div>
                <a href="{{ asset('storage/documents/' . $document->file) }}" target="_blank">
                    Download Document
                </a>
            </div>
        @endif
    </div>
@endsection</a>