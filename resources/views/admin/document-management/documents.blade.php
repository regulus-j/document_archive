<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Documents</h1>
                        <p class="text-sm text-gray-500">View and manage your documents</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal"
                        class="inline-flex items-center px-4 py-2 border border-rose-200 text-sm font-medium rounded-lg shadow-sm text-rose-700 bg-rose-50 hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Bulk Delete Documents
                    </button>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-lg mb-6 flex items-center"
                role="alert">
                <svg class="h-5 w-5 text-emerald-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-lg mb-6 flex items-center" role="alert">
                <svg class="h-5 w-5 text-rose-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg mb-6 flex items-center" role="alert">
                <svg class="h-5 w-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <p>{{ session('info') }}</p>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100 mb-6">
            <div class="bg-white p-6 border-b border-blue-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Filter Documents</h2>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.document-management.documents') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full px-3 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                placeholder="Search...">
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date
                                From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                class="mt-1 block w-full px-3 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                class="mt-1 block w-full px-3 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        </div>
                        <div>
                            <label for="show_archived"
                                class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="show_archived" id="show_archived"
                                class="mt-1 block w-full px-3 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="" {{ request('show_archived') === '' ? ' selected' : '' }}>Active Only
                                </option>
                                <option value="1" {{ request('show_archived') == '1' ? ' selected' : '' }}>Archived
                                </option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100">
            <div class="bg-white p-6 border-b border-blue-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800">Document List</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents as $doc)
                                            <tr class="hover:bg-blue-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $doc->title }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                                            {{ substr($doc->user->name, 0, 1) }}
                                                        </div>
                                                        <div class="ml-3 text-sm text-gray-700">
                                                            {{ $doc->user->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $doc->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($doc->is_archived)
                                                        <span
                                                            class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Archived</span>
                                                    @else
                                                        <span
                                                            class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">Active</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <a href="{{ route('admin.document-management.show', $doc->id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 border border-blue-200 text-xs font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </a>

                                                    <form action="{{ route('admin.document-management.toggle-archive', $doc->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="button" 
                                                            onclick="handleArchiveAction(this, {{ $doc->id }}, {{ $doc->is_archived ? 'true' : 'false' }})"
                                                            class="inline-flex items-center px-3 py-1.5 border text-xs font-medium rounded-lg transition-colors
                                                                                                        {{ $doc->is_archived
                            ? 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100'
                            : 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' 
                                                                                                        }}">
                                                            @if($doc->is_archived)
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                                </svg>
                                                                Unarchive
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                                </svg>
                                                                Archive
                                                            @endif
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.document-management.delete', $doc->id) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="handleDeleteAction(this, {{ $doc->id }})"
                                                            class="inline-flex items-center px-3 py-1.5 border border-rose-200 text-xs font-medium rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500 text-base">No documents found.</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter criteria.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white px-4 py-3 border-t border-blue-100 sm:px-6">
                {{ $documents->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-xl border border-blue-100 overflow-hidden">
                <form action="{{ route('admin.document-management.bulk-delete') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-white p-6 border-b border-blue-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <h5 class="text-lg font-semibold text-gray-800" id="bulkDeleteModalLabel">Bulk Delete
                                Documents</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-6">
                        <div class="rounded-md bg-amber-50 p-4 mb-4 border border-amber-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700">
                                        <strong>Warning:</strong> This action will permanently delete documents.
                                        <strong>Archived documents will not be affected.</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="bulk_date_from" class="block text-sm font-medium text-gray-700 mb-1">From
                                Date:</label>
                            <input type="date" name="date_from" id="bulk_date_from"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <small class="text-gray-500">Leave blank to include all documents from the beginning</small>
                        </div>

                        <div class="mb-4">
                            <label for="bulk_date_to" class="block text-sm font-medium text-gray-700 mb-1">To
                                Date:</label>
                            <input type="date" name="date_to" id="bulk_date_to"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <small class="text-gray-500">Leave blank to include all documents up to today</small>
                        </div>
                    </div>
                    <div class="modal-footer flex justify-end space-x-3 bg-blue-50 px-6 py-4 border-t border-blue-200">
                        <button type="button"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-lg shadow-sm transition-colors"
                            onclick="handleBulkDelete(this)">Delete Documents</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Popup Notification Styles -->
    <style>
        .popup-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            font-family: system-ui, -apple-system, sans-serif;
        }
        
        .popup-notification.show {
            transform: translateX(0);
        }
        
        .popup-notification.success {
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
            border-left: 4px solid #047857;
        }
        
        .popup-notification.error {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
            border-left: 4px solid #b91c1c;
        }
        
        .popup-notification.warning {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: white;
            border-left: 4px solid #b45309;
        }
        
        .popup-notification .popup-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .popup-notification .popup-icon {
            margin-right: 12px;
            width: 24px;
            height: 24px;
        }
        
        .popup-notification .popup-message {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }
        
        .popup-notification .popup-close {
            margin-left: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            line-height: 1;
        }
        
        .popup-notification .popup-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show popup notifications for session messages
            @if(session('success'))
                showPopup('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                showPopup('{{ session('error') }}', 'error');
            @endif
            
            @if(session('info'))
                showPopup('{{ session('info') }}', 'warning');
            @endif
        });

        // Function to show popup notifications
        function showPopup(message, type = 'success') {
            // Remove any existing popups
            const existingPopups = document.querySelectorAll('.popup-notification');
            existingPopups.forEach(popup => popup.remove());
            
            // Create popup element
            const popup = document.createElement('div');
            popup.className = `popup-notification ${type}`;
            
            let iconSvg = '';
            switch(type) {
                case 'success':
                    iconSvg = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'error':
                    iconSvg = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'warning':
                    iconSvg = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>';
                    break;
            }
            
            popup.innerHTML = `
                <div class="popup-content">
                    ${iconSvg}
                    <span class="popup-message">${message}</span>
                    <button class="popup-close" onclick="closePopup(this)">&times;</button>
                </div>
            `;
            
            // Add to body
            document.body.appendChild(popup);
            
            // Show popup
            setTimeout(() => popup.classList.add('show'), 100);
            
            // Auto close after 5 seconds
            setTimeout(() => closePopup(popup.querySelector('.popup-close')), 5000);
        }
        
        // Function to close popup
        function closePopup(closeBtn) {
            const popup = closeBtn.closest('.popup-notification');
            popup.classList.remove('show');
            setTimeout(() => popup.remove(), 300);
        }

        // Enhanced archive button handler
        function handleArchiveAction(button, documentId, isArchived) {
            const form = button.closest('form');
            const actionText = isArchived ? 'unarchive' : 'archive';
            const confirmMessage = isArchived 
                ? 'Are you sure you want to unarchive this document?' 
                : 'Are you sure you want to archive this document?';
            
            // Show custom confirmation popup
            showConfirmationPopup(confirmMessage, function() {
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    ${actionText === 'archive' ? 'Archiving...' : 'Unarchiving...'}
                `;
                
                // Submit the form
                form.submit();
            });
            return false;
        }

        // Enhanced delete button handler
        function handleDeleteAction(button, documentId) {
            const form = button.closest('form');
            
            // Show custom confirmation popup
            showConfirmationPopup('Are you sure you want to delete this document? This action cannot be undone.', function() {
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Deleting...
                `;
                
                // Submit the form
                form.submit();
            });
            return false;
        }

        // Custom confirmation popup function
        function showConfirmationPopup(message, onConfirm) {
            // Remove any existing popups
            const existingPopups = document.querySelectorAll('.popup-notification, .confirmation-popup');
            existingPopups.forEach(popup => popup.remove());
            
            // Create confirmation popup
            const popup = document.createElement('div');
            popup.className = 'confirmation-popup';
            popup.innerHTML = `
                <div class="confirmation-overlay"></div>
                <div class="confirmation-content">
                    <div class="confirmation-header">
                        <svg class="confirmation-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <h3>Confirm Action</h3>
                    </div>
                    <div class="confirmation-message">${message}</div>
                    <div class="confirmation-buttons">
                        <button class="confirmation-cancel">Cancel</button>
                        <button class="confirmation-confirm">Confirm</button>
                    </div>
                </div>
            `;
            
            // Add styles for confirmation popup
            if (!document.getElementById('confirmation-styles')) {
                const style = document.createElement('style');
                style.id = 'confirmation-styles';
                style.textContent = `
                    .confirmation-popup {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        z-index: 10000;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    
                    .confirmation-overlay {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        backdrop-filter: blur(4px);
                    }
                    
                    .confirmation-content {
                        position: relative;
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
                        padding: 24px;
                        max-width: 400px;
                        width: 90%;
                        animation: confirmationSlideIn 0.3s ease-out;
                    }
                    
                    @keyframes confirmationSlideIn {
                        from {
                            opacity: 0;
                            transform: scale(0.9) translateY(-20px);
                        }
                        to {
                            opacity: 1;
                            transform: scale(1) translateY(0);
                        }
                    }
                    
                    .confirmation-header {
                        display: flex;
                        align-items: center;
                        margin-bottom: 16px;
                    }
                    
                    .confirmation-icon {
                        width: 24px;
                        height: 24px;
                        color: #f59e0b;
                        margin-right: 12px;
                    }
                    
                    .confirmation-header h3 {
                        font-size: 18px;
                        font-weight: 600;
                        color: #1f2937;
                        margin: 0;
                    }
                    
                    .confirmation-message {
                        color: #4b5563;
                        font-size: 14px;
                        line-height: 1.5;
                        margin-bottom: 20px;
                    }
                    
                    .confirmation-buttons {
                        display: flex;
                        gap: 12px;
                        justify-content: flex-end;
                    }
                    
                    .confirmation-cancel, .confirmation-confirm {
                        padding: 8px 16px;
                        border-radius: 6px;
                        font-size: 14px;
                        font-weight: 500;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        border: 1px solid;
                    }
                    
                    .confirmation-cancel {
                        background: #f9fafb;
                        border-color: #d1d5db;
                        color: #374151;
                    }
                    
                    .confirmation-cancel:hover {
                        background: #f3f4f6;
                        border-color: #9ca3af;
                    }
                    
                    .confirmation-confirm {
                        background: #dc2626;
                        border-color: #dc2626;
                        color: white;
                    }
                    
                    .confirmation-confirm:hover {
                        background: #b91c1c;
                        border-color: #b91c1c;
                    }
                `;
                document.head.appendChild(style);
            }
            
            // Add to body
            document.body.appendChild(popup);
            
            // Add event listeners
            popup.querySelector('.confirmation-cancel').addEventListener('click', function() {
                popup.remove();
            });
            
            popup.querySelector('.confirmation-confirm').addEventListener('click', function() {
                popup.remove();
                onConfirm();
            });
            
            popup.querySelector('.confirmation-overlay').addEventListener('click', function() {
                popup.remove();
            });
            
            // Close on escape key
            document.addEventListener('keydown', function escapeHandler(e) {
                if (e.key === 'Escape') {
                    popup.remove();
                    document.removeEventListener('keydown', escapeHandler);
                }
            });
        }

        // Handle bulk delete action
        function handleBulkDelete(button) {
            const form = button.closest('form');
            
            showConfirmationPopup('Are you sure you want to permanently delete these documents? This action cannot be undone.', function() {
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Deleting Documents...
                `;
                
                // Submit the form
                form.submit();
            });
        }
    </script>
</x-app-layout>