@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-200 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 sm:p-10">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Archived Documents</h1>
                    
                    @if ($documents->isEmpty())
    <p class="text-gray-600">No archived documents found.</p>
@else
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Archived</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($documents as $document)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $document->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $document->updated_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('documents.restore', $document->id) }}" class="text-blue-600 hover:text-blue-900">Restore</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
                </div>
            </div>
        </div>
    </div>
@endsection