<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 -mt-6 -mx-6 px-6 py-8 mb-6 rounded-b-2xl shadow-lg">
            <h2 class="text-3xl font-bold text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="text-blue-100 mt-2">
                {{ __("Welcome back, " . auth()->user()->first_name . "!") }}
            </p>
        </div>
    </x-slot>

    <!-- Subscription alert banner for company admins -->
    @if(isset($needsSubscription) && $needsSubscription)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
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
                        <a href="{{ route('plans.select') }}" class="font-medium underline text-amber-800 hover:text-amber-900">
                            Click here to select a subscription plan
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="py-8 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([
                    [
                        'title' => 'Total Documents', 
                        'value' => $totalDocuments, 
                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'color' => 'from-blue-500 to-blue-600'
                    ],
                    [
                        'title' => 'Total Users', 
                        'value' => $countCompanyUsers, 
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'color' => 'from-indigo-500 to-indigo-600'
                    ],
                    [
                        'title' => 'Total Offices', 
                        'value' => $countOffices, 
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                        'color' => 'from-purple-500 to-purple-600'
                    ],
                    [
                        'title' => 'Incoming Documents', 
                        'value' => $incomingDocuments + $pendingDocuments, 
                        'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                        'color' => 'from-emerald-500 to-emerald-600'
                    ],
                ] as $stat)
                    <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-blue-100 hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br {{ $stat['color'] }} rounded-xl p-4 shadow-lg">
                                    <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
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
                                        <dd class="text-3xl font-bold text-gray-900">
                                            {{ $stat['value'] }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-xl rounded-xl p-8 border border-blue-100">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-3 shadow-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Quick Actions</h3>
                </div>
                
                <form action="{{ route('trackingNumber-search') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Select Action</label>
                        <select id="action" name="action"
                            class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg shadow-sm">
                            <option value="">Select an action</option>
                            @foreach ([ 
                                ['value' => 'find', 'label' => 'Find Document', 'icon' => 'M10.5 3a7.5 7.5 0 015.916 12.5l4.243 4.242-1.414 1.414-4.242-4.243A7.5 7.5 0 1110.5 3z'], 
                                ['value' => 'receive', 'label' => 'Receive Document', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'], 
                                ['value' => 'acccept', 'label' => 'Accept Document', 'icon' => 'M5 13l4 4L19 7'], 
                                ['value' => 'reject', 'label' => 'Reject Document', 'icon' => 'M6 18L18 6M6 6l12 12'], 
                            ] as $action)
                                <option value="{{ $action['value'] }}">
                                    {{ $action['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Tracking Number
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <input type="text" name="tracking_number" id="tracking_number"
                                class="flex-1 min-w-0 block w-full px-4 py-3 rounded-l-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300"
                                placeholder="Enter tracking number">
                                
                            <button type="button" onclick="startScanner()"
                                class="inline-flex items-center px-4 py-3 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Scan QR
                            </button>
                            
                            <button type="submit"
                                class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-r-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Submit
                            </button>
                        </div>
                        
                        <div id="reader" class="mt-6 hidden rounded-lg overflow-hidden shadow-lg border border-blue-100"></div>
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
                                
                                // Add a success notification
                                const notification = document.createElement('div');
                                notification.className = 'fixed bottom-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center';
                                notification.innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    QR Code scanned successfully!
                                `;
                                document.body.appendChild(notification);
                                
                                // Remove notification after 3 seconds
                                setTimeout(() => {
                                    notification.remove();
                                }, 3000);
                            });
                        }
                    </script>
                    @endpush
                </form>
            </div>
            
            <!-- Recent Activity
            <div class="bg-white shadow-xl rounded-xl p-8 border border-blue-100">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-3 shadow-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Recent Activity</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                D
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Document #12345 was received</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                U
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">User John Doe updated their profile</p>
                            <p class="text-xs text-gray-500">Yesterday at 3:45 PM</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-10 w-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                O
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">New office added: Downtown Branch</p>
                            <p class="text-xs text-gray-500">2 days ago</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                        View all activity
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div> -->
        </div>
    </div>
</x-app-layout>