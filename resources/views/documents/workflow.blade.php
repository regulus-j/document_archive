@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Header Box -->
            <div class="bg-white rounded-xl mb-6 border border-blue-200/80 overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ __('Workflow Management') }}</h2>
                            <p class="text-sm text-gray-600">Track and manage document workflows</p>
                        </div>
                    </div>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Back to Documents
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-white border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-r-lg shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Message for Workflow Access -->
            @if (session('info'))
                <div class="bg-white border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-r-lg shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="bg-white border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pending Documents Notice -->
            @if(isset($pendingReceive) && $pendingReceive->count() > 0)
                <div class="bg-white rounded-xl mb-6 border-l-4 border-orange-500 border-t border-b border-r border-orange-200/80">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium text-orange-600">{{ $pendingReceive->count() }}</span> document(s) awaiting receipt
                            </p>
                        </div>
                        <a href="{{ route('documents.receive.index') }}" class="inline-flex items-center px-3 py-1.5 border border-orange-600 text-sm font-medium rounded-md text-orange-600 hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                            Go to Receive
                        </a>
                    </div>
                </div>
            @endif

            <!-- Workflow Instructions -->
            <div class="bg-white rounded-xl mb-6 border-l-4 border-blue-500 border-t border-b border-r border-blue-200/80">
                <div class="p-4 flex items-start space-x-3">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-blue-600">Document Tracking:</span>
                        This page shows the tracking status of all documents in the workflow. Use the "View Details" button to see complete information about any document. For actions like receiving or reviewing documents, please visit the respective pages from the navigation menu.
                    </p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl overflow-hidden border border-blue-200/80 transition-all duration-300 hover:border-blue-300/80">
                <div class="bg-gradient-to-r from-blue-50 to-white p-6 border-b border-blue-200/60">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">{{ __('Active Workflows') }}</h3>
                        </div>
                        <span class="text-sm text-blue-600 bg-blue-50 py-1 px-3 rounded-full border border-blue-200/60">{{ $workflows->count() ?? 0 }} workflows</span>
                    </div>
                </div>

                    @if(isset($workflows) && $workflows->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('ID') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('Document') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('Current Step') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('Recipient') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($workflows as $workflow)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $workflow->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <a href="{{ route('documents.show', $workflow->document_id) }}"
                                                    class="text-blue-600 hover:text-blue-900 hover:underline">
                                                    {{ $workflow->document->title ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $workflow->step_order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $workflow->recipient->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                @if($workflow->status === 'pending')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($workflow->status === 'received')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Received
                                                    </span>
                                                @elseif($workflow->status === 'approved')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @elseif($workflow->status === 'rejected')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                @elseif($workflow->status === 'returned')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                        Returned
                                                    </span>
                                                @elseif($workflow->status === 'referred')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Referred
                                                    </span>
                                                @elseif($workflow->status === 'forwarded')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        Forwarded
                                                    </span>
                                                @elseif($workflow->status === 'completed')
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                        Completed
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ ucfirst($workflow->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex flex-wrap space-x-2">
                                                    <a href="{{ route('documents.show', $workflow->document_id) }}"
                                                        class="text-blue-500 hover:underline">View Details</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $workflows->links() }}
                        </div>
                    @else
                        <div class="p-6">
                            <div class="flex flex-col items-center justify-center p-6 bg-blue-50/50 rounded-lg border border-blue-100">
                                <svg class="w-16 h-16 text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <p class="text-blue-900 text-lg font-medium mb-1">No Active Workflows</p>
                                <p class="text-blue-600 text-sm">No workflows have been created yet</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
