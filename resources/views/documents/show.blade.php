@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
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
                        <h1 class="text-2xl font-bold text-gray-800">{{ __('Document Details') }}</h1>
                        <p class="text-sm text-gray-500">View complete document information and history</p>
                    </div>
                </div>
                <a href="javascript:history.back()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <!-- Progress Tracking Card -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Document Progress
                </h3>

                <!-- Timeline -->
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute h-full w-0.5 bg-gray-200 left-6 top-0"></div>

                    <!-- Timeline Items -->
                    <div class="space-y-8 relative">
                        @php
                            // Sort audit logs by created_at timestamp in descending order
                            $sortedLogs = $auditLogs->sortByDesc('created_at');

                            // Define status colors
                            $statusColors = [
                                'created' => ['bg' => 'bg-green-500', 'text' => 'text-green-800', 'light' => 'bg-green-100'],
                                'pending' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-800', 'light' => 'bg-yellow-100'],
                                'received' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-800', 'light' => 'bg-blue-100'],
                                'approved' => ['bg' => 'bg-green-500', 'text' => 'text-green-800', 'light' => 'bg-green-100'],
                                'rejected' => ['bg' => 'bg-red-500', 'text' => 'text-red-800', 'light' => 'bg-red-100'],
                                'returned' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-800', 'light' => 'bg-amber-100'],
                                'forwarded' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-800', 'light' => 'bg-purple-100'],
                                'completed' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-800', 'light' => 'bg-indigo-100'],
                                'uploaded' => ['bg' => 'bg-green-500', 'text' => 'text-green-800', 'light' => 'bg-green-100'],
                                'needs_revision' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-800', 'light' => 'bg-amber-100'],
                                'cancelled' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-800', 'light' => 'bg-gray-100'],
                                'draft' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-800', 'light' => 'bg-gray-100']
                            ];

                            // Helper function to get status color with fallback
                            function getStatusColor($status, $type) {
                                global $statusColors;
                                if (!isset($statusColors) || !is_array($statusColors)) {
                                    // Fallback colors if $statusColors is not available
                                    return $type === 'bg' ? 'bg-gray-500' : ($type === 'light' ? 'bg-gray-100' : 'text-gray-800');
                                }

                                $defaultColors = [
                                    'bg' => 'bg-gray-500',
                                    'light' => 'bg-gray-100',
                                    'text' => 'text-gray-800'
                                ];

                                if ($status === null) {
                                    return $defaultColors[$type] ?? $defaultColors['bg'];
                                }

                                $status = strtolower($status);
                                if (!isset($statusColors[$status])) {
                                    return $defaultColors[$type] ?? $defaultColors['bg'];
                                }

                                return $statusColors[$status][$type] ?? $defaultColors[$type] ?? $defaultColors['bg'];
                            }
                        @endphp

                        @php
                            $currentStep = 1;
                            $totalSteps = $sortedLogs->count();
                        @endphp

                        @foreach($sortedLogs as $log)
                            <div class="flex items-start relative">
                                <!-- Timeline Point -->
                                <div class="flex-shrink-0 w-12 flex flex-col items-center">
                                    <div class="relative">
                                        @php
                                            $dotColor = match(strtolower($log->status ?? '')) {
                                                'pending' => 'yellow',
                                                'approved' => 'green',
                                                'rejected' => 'red',
                                                'received' => 'blue',
                                                'forwarded' => 'purple',
                                                'returned' => 'amber',
                                                'completed' => 'indigo',
                                                'needs_revision' => 'amber',
                                                'cancelled' => 'gray',
                                                'draft' => 'gray',
                                                default => 'gray'
                                            };
                                        @endphp
                                        <div class="bg-{{ $dotColor }}-500 h-4 w-4 rounded-full border-4 border-white shadow"></div>
                                    </div>
                                </div>

                                <!-- Timeline Content -->
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-800">
                                            <span class="font-medium">
                                                {{ $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'System' }}
                                            </span>
                                            @if($log->action === 'created')
                                                created the document
                                            @elseif($log->action === 'updated')
                                                updated the document
                                            @elseif($log->action === 'forwarded')
                                                @php
                                                    $workflowEntry = $workflows->where('id', $log->workflow_id)->first();
                                                    $recipientOffice = $workflowEntry->recipientOffice->name ?? null;
                                                @endphp
                                                forwarded the document to {{ $recipientOffice ?? 'another office' }}
                                            @elseif($log->action === 'received')
                                                received the document
                                            @elseif($log->action === 'reviewed')
                                                reviewed the document
                                            @elseif($log->action === 'approved')
                                                approved the document
                                            @elseif($log->action === 'rejected')
                                                rejected the document
                                            @elseif($log->action === 'returned')
                                                returned the document
                                            @else
                                                @php
                                                    $action = strtolower($log->action ?? 'updated');
                                                    $action = str_replace('workflow', 'processed', $action);
                                                @endphp
                                                {{ $action }} the document
                                            @endif

                                            @if($log->status)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ getStatusColor($log->status, 'light') }} {{ getStatusColor($log->status, 'text') }} ml-2">
                                                    {{ ucfirst($log->status) }}
                                                </span>
                                            @endif
                                        </p>
                                        @if($log->details)
                                            <p class="text-sm text-gray-500 mt-0.5">{{ $log->details }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M d, Y H:i') }}</time>
                                    </div>
                                </div>
                            </div>
                            @php $currentStep++; @endphp
                        @endforeach
                    </div>
                </div>

                <!-- Current Status -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <h4 class="text-sm font-medium text-gray-500">Current Status</h4>
                        @php
                            $latestLog = $sortedLogs->first();
                            $currentStatus = $latestLog ? $latestLog->status : ($document->status?->status ?? 'Pending');
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ getStatusColor($currentStatus, 'light') }} {{ getStatusColor($currentStatus, 'text') }}">
                            {{ ucfirst($currentStatus) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-white border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md" role="alert">
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
                    <p class="text-sm font-medium text-red-800"><strong>Error!</strong> {{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Document Details Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-8 border border-blue-100">
            <!-- Card Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $document->title }}</h2>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mt-2 md:mt-0">
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $document->status?->status ?? "N/A" }}
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Document Information Grid with Attachment Card -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Tracking Number Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">Tracking Number</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $document->trackingNumber->tracking_number ?? 'N/A' }}
                        </p>
                    </div>
                    <!-- Classification Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">Classification</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $document->categories->first()->category ?? 'N/A' }}
                        </p>
                    </div>
                    <!-- From Office Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">From Office</p>
                        <p class="text-base font-medium text-gray-900">
                            @if(isset($docRoute[1]) && count($docRoute[1]) > 0)
                            @foreach($docRoute[1] as $route)
                            @if($route['type'] == 'office')
                            {{ $route['name'] }}
                            @break
                            @endif
                            @endforeach
                            @else
                            {{ $document->user->offices->first()->name ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <!-- To Office Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">To Office</p>
                        <p class="text-base font-medium text-gray-900">
                            @if(isset($workflows) && $workflows->isNotEmpty())
                            @php
                            $officeNames = [];
                            foreach($workflows as $workflow) {
                            if($workflow->recipient_office && $workflow->recipientOffice) {
                            $officeNames[] = $workflow->recipientOffice->name;
                            }
                            }
                            @endphp

                            @if(count($officeNames) > 0)
                            {{ implode(', ', array_unique($officeNames)) }}
                            @else
                            N/A
                            @endif
                            @else
                            N/A
                            @endif
                        </p>
                    </div>
                    <!-- Status Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $document->status?->status ?? "N/A" }}
                        </p>
                    </div>
                    <!-- Remarks Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">Remarks</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $document->remarks ?? 'N/A' }}
                        </p>
                    </div>
                    <!-- Attachments Card -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-1">Attachments</p>
                        @if($document->attachments->isNotEmpty())
                        <ul class="list-disc pl-4">
                            @foreach($document->attachments as $attachment)
                            <li>
                                <a href="{{ route('documents.download', $attachment->id) }}"
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                    {{ $attachment->filename }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-base font-medium text-gray-900">N/A</p>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-8">
                    <p class="text-sm font-medium text-gray-500 mb-1">Description</p>
                    <p class="text-base text-gray-900">{{ $document->description }}</p>
                </div>

                <!-- Workflow -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Workflow
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Processing Order</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Recipient</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                        Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($workflows as $workflow)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $workflow->step_order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        @if($workflow->recipient)
                                        {{ $workflow->recipient->first_name }} {{ $workflow->recipient->last_name }}
                                        @elseif($workflow->recipient_office && $workflow->recipientOffice)
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $workflow->recipientOffice->name }}
                                        </span>
                                        @else
                                        <span class="text-gray-400">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $workflow->remarks ?? 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Download Button -->
                <div class="flex justify-center mt-8 space-x-4">
                    <a href="{{ route('documents.download', $document->id) }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Document
                    </a>

                    @foreach($workflows as $workflow)
                    @if($workflow->recipient_id == auth()->id() && $workflow->status == 'pending')
                    <form action="{{ route('documents.receive', $workflow->id) }}" method="GET">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Receive Document
                        </button>
                    </form>
                    @endif

                    @if($workflow->recipient_id == auth()->id() && $workflow->status == 'received')
                    <a href="{{ route('documents.review', $workflow->id) }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                        <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Review Document
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Audit Logs Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Document Audit Logs
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Date/Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                                Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($auditLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $log->user->first_name }} {{ $log->user->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ getStatusColor($log->status, 'light') }} {{ getStatusColor($log->status, 'text') }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $log->status }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ Str::limit($log->details, 50) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                                No audit logs found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $auditLogs->links() }}
            </div>
        </div>

    </div>
</div>
@endsection

@if($document->status === 'needs_revision')
<div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-amber-700">
                This document was rejected and needs revision.
            </p>
            @if(isset($workflow) && $workflow->remarks)
            <p class="mt-2 text-sm text-amber-700">
                <strong>Rejection remarks:</strong> {{ $workflow->remarks }}
            </p>
            @endif
            <div class="mt-4">
                <a href="{{ route('documents.edit', $document->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    Revise Document
                </a>
                <form action="{{ route('documents.cancel', $document->id) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel Workflow
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if($document->status === 'returned')
<div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-amber-700">
                This document was returned to you for updates.
            </p>
            @php
                $returnedWorkflow = $document->documentWorkflow()->where('status', 'returned')->first();
            @endphp
            @if($returnedWorkflow && $returnedWorkflow->remarks)
            <p class="mt-2 text-sm text-amber-700">
                <strong>Return remarks:</strong> {{ $returnedWorkflow->remarks }}
            </p>
            @endif
            <div class="mt-4">
                <a href="{{ route('documents.edit', $document->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    Update Document
                </a>
                <form action="{{ route('documents.cancel', $document->id) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel Workflow
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
