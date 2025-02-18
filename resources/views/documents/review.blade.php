@extends('layouts.app')

@section('content')
    <h1>Review Document</h1>

    <div>
        <p><strong>Title:</strong> {{ $document->title ?? 'Document Title Here' }}</p>
        <p><strong>Description:</strong> {{ $document->description ?? 'Document description...' }}</p>
    </div>

    <!-- Download link for the main document -->
    <div style="margin: 1em 0;">
        <a class="btn btn-primary" href="{{ route('documents.download', $document->id) }}">
            Download Document
        </a>
    </div>

    <!-- List existing attachments (if any) with download links -->
    @if(isset($document->attachments) && $document->attachments->count())
        <div style="margin-bottom: 1em;">
            <h3>Existing Attachments</h3>
            <ul>
                @foreach($document->attachments as $attachment)
                    <li>
                        <a href="{{ Storage::url($attachment->path) }}" target="_blank">
                            {{ $attachment->filename }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to add remarks and new attachments -->
    <form action="{{ route('documents.review', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="remark">Remark:</label>
            <textarea id="remark" name="remark" rows="3"></textarea>
        </div>
        <div>
            <label for="attachments">Add Attachments:</label>
            <input type="file" name="attachments[]" id="attachments" multiple>
        </div>
        <button type="submit" name="action" value="approve">Approve</button>
        <button type="submit" name="action" value="reject">Reject</button>
    </form>
@endsection