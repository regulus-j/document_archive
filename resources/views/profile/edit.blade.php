<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Box -->
            <div class="bg-white rounded-xl mb-6 border border-blue-200/80 overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-2xl font-bold text-gray-800">{{ __('Profile Settings') }}</h1>
                            <p class="text-sm text-gray-500">Manage your account settings and preferences</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Profile Information -->
                    <div class="bg-white rounded-xl border border-blue-200/80 overflow-hidden transition-all duration-300 hover:border-blue-300/80">
                        <div class="p-6 border-b border-blue-200/60">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h2 class="text-lg font-semibold text-gray-800">Profile Information</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl border border-blue-200/80 overflow-hidden transition-all duration-300 hover:border-blue-300/80">
                            <div class="p-6 border-b border-blue-200/60">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    <h2 class="text-lg font-semibold text-gray-800">Update Password</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <!-- Delete Account -->
                        <div class="bg-white rounded-xl border border-red-200/80 overflow-hidden transition-all duration-300 hover:border-red-300/80">
                            <div class="p-6 border-b border-red-200/60">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <h2 class="text-lg font-semibold text-gray-800">Delete Account</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('message'))
        <script>
            alert("{{ session('message') }}");
        </script>
    @endif
</x-app-layout>
