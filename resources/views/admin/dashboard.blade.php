<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Companies Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl">Total Companies</div>
                        <div class="text-3xl font-bold">{{ $totalCompanies }}</div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl">Total Users</div>
                        <div class="text-3xl font-bold">{{ $totalUsers }}</div>
                    </div>
                </div>

                <!-- Total Documents Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl">Total Documents</div>
                        <div class="text-3xl font-bold">{{ $totalDocuments }}</div>
                    </div>
                </div>

                <!-- Active Subscriptions Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl">Active Subscriptions</div>
                        <div class="text-3xl font-bold">{{ $activeSubscriptions }}</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left">Company</th>
                                    <th class="px-6 py-3 text-left">Action</th>
                                    <th class="px-6 py-3 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $activity->company_name }}</td>
                                    <td class="px-6 py-4">{{ $activity->action }}</td>
                                    <td class="px-6 py-4">{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>