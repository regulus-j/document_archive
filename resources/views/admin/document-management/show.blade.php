<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="h3">Document Details</h1>
            </div>
            <div class="text-right">
                <a href="{{ route('admin.document-management.documents') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        
        <div class="mb-6">
            <div class="bg-white shadow rounded">
                <div class="p-6">
                    <h5 class="text-xl font-semibold mb-2">{{ $document->title }}</h5>
                    <p class="text-gray-600 mb-4">Uploaded by {{ $document->user->name }} on {{ $document->created_at->format('M d, Y') }}</p>
                    <p>{{ $document->description }}</p>
                    <div class="mb-4">
                        <strong>Categories:</strong>
                        @foreach($document->categories as $cat)
                            <span class="badge bg-info text-dark">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                    <div class="mb-4">
                        <strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-white {{ $document->is_archived ? 'bg-yellow-500' : 'bg-green-600' }}">
                            {{ $document->is_archived ? 'Archived' : 'Active' }}
                        </span>
                    </div>
                    <div class="mb-4">
                        <strong>Attachments:</strong>
                        <ul>
                            @foreach($document->attachments as $attach)
                                <li><a href="{{ Storage::url($attach->path) }}" target="_blank">{{ $attach->filename }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <form action="{{ route('admin.document-management.toggle-archive', $document->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded font-semibold {{ $document->is_archived ? 'bg-blue-500 text-white' : 'bg-yellow-500 text-white' }}">
                                {{ $document->is_archived ? 'Unarchive' : 'Archive' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.document-management.delete', $document->id) }}" method="POST" onsubmit="return confirm('Delete this document?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white shadow rounded">
                <div class="px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">Audit Logs</h5>
                </div>
                <div class="p-6">
                    <div class="table-responsive">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr class="text-left">
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($auditLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ ucfirst($log->action) }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($log->status) }}</td>
                                    <td class="px-4 py-2">{{ $log->user->name }}</td>
                                    <td class="px-4 py-2">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-2">{{ $log->details }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No audit logs available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>