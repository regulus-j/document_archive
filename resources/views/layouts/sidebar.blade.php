<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-full">
        <!-- Logo -->
        <div class="p-4 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <x-application-logo class="block h-8 w-auto text-blue-600" />
                <span class="ml-2 text-gray-900 text-lg font-semibold">DocArchive</span>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-2 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ml-3">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center px-4 py-2 {{ request()->routeIs('users.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="ml-3">{{ __('Users') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('office.index') }}" 
                       class="flex items-center px-4 py-2 {{ request()->routeIs('office.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="ml-3">{{ __('Offices') }}</span>
                    </a>
                </li>

                @can('role-list')
                <li>
                    <a href="{{ route('roles.index') }}" 
                       class="flex items-center px-4 py-2 {{ request()->routeIs('roles.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <span class="ml-3">{{ __('Roles and Permissions') }}</span>
                    </a>
                </li>
                @endcan

                @can('document-list')
                <li x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-4 py-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ml-3">{{ __('Documents') }}</span>
                        <svg :class="{'rotate-180': open}" class="ml-auto w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-10 space-y-1">
                        <a href="{{ route('documents.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            {{ __('View') }}
                        </a>
                        <a href="{{ route('documents.archive') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            {{ __('Archives') }}
                        </a>
                        <a href="{{ route('documents.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            {{ __('Upload') }}
                        </a>
                        <a href="{{ route('documents.workflows') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                            {{ __('Received') }}
                        </a>
                    </div>
                </li>
                @endcan

                <li>
                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center px-4 py-2 {{ request()->routeIs('reports.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="ml-3">{{ __('Reports') }}</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- User Profile -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div>
                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto bg-gray-100">
        @yield('content')
    </div>
</div>

