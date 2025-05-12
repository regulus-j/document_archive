@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ __('Receive Documents') }}</h1>
                        <p class="text-sm text-gray-500">Receive and process incoming documents</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back to List') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100">
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if($documents->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No documents to receive</h3>
                        <p class="mt-2 text-gray-500">There are no documents pending receipt at your office.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider border-b border-gray-200">Document</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider border-b border-gray-200">From</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider border-b border-gray-200">Status</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider border-b border-gray-200">Date Sent</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider border-b border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($documents as $document)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $document->title }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $document->reference_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $document->transaction->fromOffice->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $document->user->name ?? 'Unknown' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $document->status->color }}-100 text-{{ $document->status->color }}-800">
                                                {{ $document->status->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $document->transaction->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('documents.show', $document->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                            @if ($document->workflow->status !== 'received')
                                                <form method="POST" action="{{ route('documents.receive.confirm', $document->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 hover:underline">
                                                        Receive
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-green-600 font-semibold">Received</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection