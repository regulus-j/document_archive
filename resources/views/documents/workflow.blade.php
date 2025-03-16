@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Workflow Management</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Workflow Management</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        @if(isset($workflows) && $workflows->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Document</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Current Step</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Recipient</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($workflows as $workflow)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $workflow->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($workflow->document)
                                    <a href="{{ route('documents.show', $workflow->document_id) }}" class="text-blue-500 hover:underline">
                                        {{ $workflow->document->title ?? 'Untitled' }}
                                    </a>
                                @else
                                    <span class="text-gray-500">No Document</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $workflow->step_order }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ optional($workflow->recipient)->name ?? 'Unassigned' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 capitalize">
                                <span class="px-2 py-1 rounded text-white
                                    @if($workflow->status === 'pending') bg-yellow-500
                                    @elseif($workflow->status === 'received') bg-blue-500
                                    @elseif($workflow->status === 'approved') bg-green-500
                                    @else bg-gray-500
                                    @endif">
                                    {{ $workflow->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($workflow->status === 'pending')
                                    <a href="{{ route('documents.receive', $workflow->id) }}"
                                        class="text-green-500 hover:underline">Receive</a>
                                @endif
                                @if($workflow->status === 'received')
                                    <a href="{{ route('documents.review', $workflow->id) }}"
                                       class="text-green-500 hover:underline">Review</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $workflows->links() }}
            </div>
        @else
            <div class="text-center py-8 text-gray-600">
                <p class="text-lg">No workflows found.</p>
                <a href="{{ route('documents.create') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
                    Create New Document
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        @if(isset($workflows) && $workflows->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Document</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Current Step</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Recipient</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($workflows as $workflow)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $workflow->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <a href="{{ route('documents.show', $workflow->document_id) }}">{{ $workflow->document->title ?? 'N/A' }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $workflow->step_order }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $workflow->recipient->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $workflow->status }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($workflow->status === 'pending')
                                    <a href="{{ route('documents.receive', $workflow->id) }}"
                                        class="text-green-500 hover:underline">Receive</a>
                                @endif
                                @if($workflow->status=== 'received')
                                <a href="{{ route('documents.review', $workflow->id) }}"
                                   class="text-green-500 hover:underline">Review</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $workflows->links() }}
            </div>
        @else
            <p class="text-gray-600">No workflows found.</p>
        @endif
    </div>
</div>
@endsection