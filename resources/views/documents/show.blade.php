@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Document Details</h1>
        <a href="javascript:history.back()"
            class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <strong class="text-gray-700">Title:</strong>
                <p>{{ $document->title }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $document->title }}</h2>
                    <span
                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full uppercase font-semibold tracking-wide mt-2">
                        {{ $document->status->status }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $fields = [
                                'Tracking Number' => $document->trackingNumber->tracking_number,
                                'Classification' => $document->categories->first()->category ?? 'N/A',
                                'From Office' => $document->transaction->fromOffice->name,
                                'To Office' => $document->transaction->toOffice->name,
                                'Remarks' => $document->remarks ?? 'N/A',
                            ];
                        @endphp

                        @foreach ($fields as $label => $value)
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                                <p class="text-base text-gray-900">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 col-span-full">
                        <p class="text-sm font-medium text-gray-500 mb-1">Description</p>
                        <p class="text-base text-gray-900">{{ $document->description }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-700">From Office:</strong>
                        <p>{{ $document->transaction->fromOffice->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-700">To Office:</strong>
                        <p>{{ $document->transaction->toOffice->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-700">Status:</strong>
                        <p>{{ $document->status->status }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-700">Remarks:</strong>
                        <p>{{ $document->remarks ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <strong class="text-gray-700">Attachments:</strong>
                        @forelse ($attachments as $attachment)
                            <p>
                                <a href="{{ route('documents.download', $attachment->id) }}"
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fa-solid fa-paperclip mr-1"></i>{{ $attachment->filename }}
                                </a>
                            </p>
                        @empty
                            <p class="text-gray-500">No attachments found</p>
                        @endforelse
                    </div>

                    <div>
                        <strong class="text-gray-700">Attachments by Date:</strong>
                        @forelse ($document->attachments->groupBy(function ($attachment) {
                                return $attachment->created_at->format('Y-m-d');
                            }) as $date => $attachments)
                                                    <div class="ml-4 mb-2">
                                                        <h4 class="font-medium text-gray-600">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                                        </h4>
                                                        @foreach ($attachments as $attachment)
                                                            <p class="ml-4">
                                                                <a href="{{ route('documents.download', $attachment->id) }}"
                                                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                                                    <i class="fa-solid fa-paperclip mr-1"></i>{{ $attachment->filename }}
                                                                </a>
                                                            </p>
                                                        @endforeach
                                                    </div>
                        @empty
                            <p class="text-gray-500">No attachments found</p>
                        @endforelse
                    </div>

                    <div>
                        <strong class="text-gray-700">Attached File:</strong>
                        <form action="{{ route('documents.download', $document->id) }}" method="get"
                            class="inline-block">
                            @csrf
                            @method('GET')
                            <button type="submit"
                                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download File
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">Document Audit Logs</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Date/Time</th>
                                <th class="px-4 py-3">User</th>
                                <th class="px-4 py-3">Action</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr class="bg-white border-b hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-4 py-3">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                    <td class="px-4 py-3">{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($log->action === 'created') bg-green-100 text-green-800
                                                @elseif($log->action === 'updated') bg-yellow-100 text-yellow-800
                                                @elseif($log->action === 'deleted') bg-red-100 text-red-800
                                                    @else bg-blue-100 text-blue-800
                                                @endif">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $log->status }}</td>
                                    <td class="px-4 py-3">{{ Str::limit($log->details, 50) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500 italic">No audit logs found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4">
                    {{ $auditLogs->links() }}
                </div>
            </div>
        </div>
        @endsection