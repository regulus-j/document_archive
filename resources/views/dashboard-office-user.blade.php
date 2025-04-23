<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 leading-tight">
            {{ __('Office Dashboard') }}
        </h2>
    </x-slot>

    <!-- Subscription alert banner for company users -->
    @if(isset($needsSubscription) && $needsSubscription)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-2">
        <div class="bg-gradient-to-r from-amber-100 to-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-800">
                        Your company doesn't have an active subscription. Some features may be limited.
                        @if(auth()->user()->hasRole('company-admin'))
                        <a href="{{ route('plans.select') }}" class="font-medium underline text-amber-800 hover:text-amber-900">
                            Click here to select a subscription plan
                        </a>
                        @else
                        Please contact your company administrator to activate a subscription.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Welcome Message -->
            <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <div class="text-2xl font-semibold text-gray-900">
                        {{ __("Welcome back, " . auth()->user()->first_name . "!") }}
                        <br>
                        {{ __("From " . optional(auth()->user()->companies()->first())->company_name . " company!") }}
                    </div>
                </div>
            </div>

            <!-- Office Lead Statistics (Only shown for office leads) -->
            @if($isOfficeLead && $ledOffice)
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ $ledOffice->name }} Office Statistics
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Office Document Count -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="text-sm font-medium text-blue-800 mb-1">Total Documents</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $officeDocumentCount }}</div>
                    </div>
                    
                    <!-- Today's Documents -->
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="text-sm font-medium text-green-800 mb-1">Documents Today</div>
                        <div class="text-2xl font-bold text-green-900">{{ $officeDocumentsTodayCount }}</div>
                    </div>
                    
                    <!-- Pending Workflows -->
                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                        <div class="text-sm font-medium text-amber-800 mb-1">Pending Workflows</div>
                        <div class="text-2xl font-bold text-amber-900">{{ $officePendingWorkflowsCount }}</div>
                    </div>
                    
                    <!-- Office Members -->
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="text-sm font-medium text-purple-800 mb-1">Team Members</div>
                        <div class="text-2xl font-bold text-purple-900">{{ $officeMembers->count() }}</div>
                    </div>
                </div>
                
                <!-- Recent Office Documents -->
                @if($officeDocuments->count() > 0)
                <div class="mt-6">
                    <h4 class="text-lg font-medium text-gray-700 mb-3">Recent Office Documents</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploader</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($officeDocuments as $document)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('documents.show', $document) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ Str::limit($document->title, 30) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $document->user ? $document->user->first_name . ' ' . $document->user->last_name : 'Unknown' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $document->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($document->categories as $category)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $category->name ?? $category->category ?? 'Unnamed Category' }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 text-xs">No categories</span>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.office-user-dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                            </svg>
                            View Full Office Dashboard
                        </a>
                    </div>
                </div>
                @else
                <div class="text-center text-gray-500 py-4">No recent office documents found</div>
                @endif
            </div>
            @endif

            <!-- Office User Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ([ 
                    ['title' => 'Documents Received', 'value' => $totalDocuments, 'icon' => 'M3 10h11M9 21V3m0 18v-8m-6 8h6m6-18h6m-6 0v18m0-18v8m6-8v8'], 
                    ['title' => 'Pending Documents', 'value' => $pendingDocuments, 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'], 
                    ['title' => 'Processed Documents', 'value' => $countRecentDocs, 'icon' => 'M5 13l4 4L19 7'], 
                ] as $stat)
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200 flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $stat['icon'] }}" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ $stat['title'] }}
                                    </dt>
                                    <dd class="text-3xl font-semibold text-gray-900">
                                        {{ $stat['value'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions for Office Users -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                <form action="{{ route('trackingNumber-search') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Select Action</label>
                        <select id="action" name="action"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select an action</option>
                            @foreach ([ 
                                ['value' => 'find', 'label' => 'Find', 'icon' => 'M10.5 3a7.5 7.5 0 015.916 12.5l4.243 4.242-1.414 1.414-4.242-4.243A7.5 7.5 0 1110.5 3z'], 
                                ['value' => 'receive', 'label' => 'Receive', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'], 
                                ['value' => 'acccept', 'label' => 'Acccept', 'icon' => 'M5 13l4 4L19 7'], 
                                ['value' => 'reject', 'label' => 'Reject', 'icon' => 'M6 18L18 6M6 6l12 12'], 
                            ] as $action)
                                <option value="{{ $action['value'] }}">
                                    {{ $action['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">Tracking
                            Number</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" name="tracking_number" id="tracking_number"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300"
                                placeholder="Enter tracking number">
                            <button type="button" onclick="startScanner()"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Scan QR
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit
                            </button>
                        </div>
                        <div id="reader" class="mt-4 hidden"></div>
                    </div>

                    @push('scripts')
                    <script src="https://unpkg.com/html5-qrcode"></script>
                    <script>
                        function startScanner() {
                            const reader = document.getElementById('reader');
                            reader.classList.remove('hidden');
                            
                            const html5QrCode = new Html5Qrcode("reader");
                            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                            
                            html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                                document.getElementById('tracking_number').value = decodedText;
                                html5QrCode.stop();
                                reader.classList.add('hidden');
                            });
                        }
                    </script>
                    @endpush
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
