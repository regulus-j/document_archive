<div class="flex items-center justify-end space-x-2">
    <a href="{{ route('documents.show', $document->id) }}"
        class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
        title="View">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </a>

    @php
        $status = '';
        if (isset($document->status) && $document->status && $document->status->status) {
            $status = strtolower(trim($document->status->status));
        }
        $isDocumentOwner = $document->user && $document->user->id == auth()->user()->id;
        // Always enable buttons for admin users
        $canManageDocument = $isDocumentOwner || auth()->user()->can('document-manage') || auth()->user()->can('document-edit');
    @endphp
    
    @if ($canManageDocument)
        @if ($status == 'uploaded' || $status == 'pending')
            <a href="{{ route('documents.forward', $document->id) }}"
                class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors"
                title="Forward">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </a>
        @elseif($status == 'forwarded')
            <form action="{{ route('documents.recall', $document) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                    class="p-1.5 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors"
                    title="Recall Document" 
                    onclick="return handleRecallDocument(this.form);">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" 
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z" />
                    </svg>
                </button>
            </form>
        @endif
        
        @if(isset($document->status) && strtolower($document->status->status) === 'recalled' && ($document->uploader == auth()->id() || $document->user->id == auth()->user()->id || auth()->user()->can('document-manage')))
            <form action="{{ route('documents.resume', $document) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                    class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors"
                    title="Resume Document" 
                    onclick="return handleResumeDocument(this.form);">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" 
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                    </svg>
                </button>
            </form>
        @endif
    @endif

    @can('document-edit')
        <a href="{{ route('documents.edit', $document->id) }}"
            class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors"
            title="Edit">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </a>
    @endcan

    @can('document-delete')
        <form action="{{ route('documents.destroy', $document->id) }}"
            method="POST"
            onsubmit="return handleDeleteDocument(this);"
            class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                title="Delete">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </form>
    @endcan

    <form action="{{ route('documents.download', $document->id) }}"
        method="GET" class="inline-block">
        @csrf
        <button type="submit"
            class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors"
            title="Download">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
        </button>
    </form>

    @if ($document->status && $document->status->status != 'archived')
        <form action="{{ route('documents.archive.store', $document) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit"
                class="p-1.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors"
                title="Archive Document" 
                onclick="return handleArchiveDocument(this.form);">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </button>
        </form>
    @endif
</div>
