@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Show Document</h2>
        <a href="javascript:history.back()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <strong class="text-gray-700">Title:</strong>
                <p>{{ $document->title }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Tracking Number:</strong>
                <p>{{ $document->trackingNumber->tracking_number }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Description:</strong>
                <p>{{ $document->description }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Classification:</strong>
                <p>{{ $document->categories->first()->category ?? 'N/A' }}</p>
            </div>

            <div>
                <strong class="text-gray-700">From Office:</strong>
                <p>{{ $document->transaction->fromOffice->name }}</p>
            </div>

            <div>
                <strong class="text-gray-700">To Office:</strong>
                <p>{{ $document->transaction->toOffice->name }}</p>
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
                <strong class="text-gray-700">Attached File:</strong>
                <form action="{{ route('documents.downloadFile', $document->id) }}" method="get" class="inline-block">
                    @csrf
                    @method('GET')
                    <button type="submit" class="text-green-600 hover:text-green-800 transition-colors">
                        <i class="fa-solid fa-download mr-1"></i> Download
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Document Audit Logs</h2>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Date/Time</th>
                        <th class="px-6 py-3">Document</th>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Action</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $log->created_at }}</td>
                            <td class="px-6 py-4">{{ $log->document->title }}</td>
                            <td class="px-6 py-4">{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                            <td class="px-6 py-4">{{ $log->action }}</td>
                            <td class="px-6 py-4">{{ $log->status }}</td>
                            <td class="px-6 py-4">{{ $log->details }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">No audit logs found</td>
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