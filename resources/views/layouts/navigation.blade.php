<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Desktop Navigation Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-8 w-auto text-blue-600" />
                        <span class="ml-2 text-gray-900 text-lg font-semibold">DocTrack</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <!-- Dashboard - Always Visible -->
                        <x-nav-link 
                            :href="route('dashboard')" 
                            :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')" 
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @if(auth()->user()->isSuperAdmin())
                        <x-nav-link 
                                :href="route('users.index')" 
                                :active="request()->routeIs('users.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('admin.registered') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ __('Users') }}
                            </x-nav-link>

                            <x-nav-link 
                                :href="route('roles.index')" 
                                :active="request()->routeIs('roles.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('roles.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                {{ __('Roles and Permissions') }}
                            </x-nav-link>

                            <x-nav-link 
                                :href="route('companies.index')" 
                                :active="request()->routeIs('companies.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('companies.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ __('Companies') }}
                            </x-nav-link>

                            <x-nav-link 
                                :href="route('admin.plans.index')" 
                                :active="request()->routeIs('admin.plans.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('plans.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                {{ __('Plans') }}
                            </x-nav-link>

                            <x-nav-link 
                                :href="route('admin.subscriptions.index')" 
                                :active="request()->routeIs('admin.subscriptions.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('subscriptions.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                </svg>
                                {{ __('Subscriptions') }}
                            </x-nav-link>
                            
                            @elseif(auth()->user()->hasRole('company-admin')) 
                            <x-nav-link 
                                :href="route('users.index')" 
                                :active="request()->routeIs('users.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('admin.registered') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ __('Users') }}
                            </x-nav-link>

                            <x-nav-link 
                                    :href="route('office.index')" 
                                    :active="request()->routeIs('office.index')"
                                    class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('admin.registered') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10V21h18V10l-9-7-9 7z" />
                                    </svg>
                                    {{ __('Office') }}
                                </x-nav-link>

                            <x-nav-link 
                                :href="route('plans.select')" 
                                :active="request()->routeIs('plans.select')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('subscriptions.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                </svg>
                                {{ __('Plans & Subscriptions') }}
                            </x-nav-link>

                            @else
                            <x-nav-link 
                                :href="route('dashboard')" 
                                :active="request()->routeIs('dashboard')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            
                            @can('document-list')
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" 
                                        class="flex items-center px-3 py-2 rounded-md text-sm font-medium 
                                        {{ request()->routeIs('documents.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}
                                        focus:outline-none">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ __('Documents') }}
                                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" 
                                        class="absolute z-50 left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <a href="{{ route('documents.index') }}" 
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('documents.index') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}" 
                                            role="menuitem">{{ __('View') }}</a>
                                        <a href="{{ route('documents.archive') }}" 
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('documents.archive') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}" 
                                            role="menuitem">{{ __('Archives') }}</a>
                                        <a href="{{ route('documents.create') }}" 
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('documents.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}" 
                                            role="menuitem">{{ __('Upload') }}</a>
                                        <a href="{{ route('documents.workflows') }}" 
                                            class="block px-4 py-2 text-sm {{ request()->routeIs('documents.workflows') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}" 
                                            role="menuitem">{{ __('Received') }}</a>
                                    </div>
                                </div>
                            @endcan
                        @endif

                        <x-nav-link 
                                :href="route('reports.index')" 
                                :active="request()->routeIs('reports.index')"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 {{ request()->routeIs('reports.index') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ __('Reports') }}
                            </x-nav-link>

            <!-- Desktop User Dropdown -->
            <div class="hidden md:block">
                <div class="flex items-center justify-between w-full">
                    <div class="relative" style="z-index: 999;">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 focus:outline-none focus:text-blue-600 rounded-md px-3 py-2">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Profile link is always shown -->
                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-blue-50 hover:text-blue-600">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if(auth()->user()->hasRole('company-admin')) 
                                    <!-- Admin sees Plans -->
                                    <x-dropdown-link :href="route('companies.userManaged', auth()->id())" class="hover:bg-blue-50 hover:text-blue-600">
                                        {{ __('Company Account') }}
                                    </x-dropdown-link>
                                @endif

                                <!-- Manual link is shown to all users -->
                                <x-dropdown-link :href="route('userManual.manual', auth()->id())" class="hover:bg-blue-50 hover:text-blue-600">
                                    {{ __('Manual') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('userManual.manual', auth()->id())" class="hover:bg-blue-50 hover:text-blue-600">
                                    {{ __('Settings') }}
                                </x-dropdown-link>

                                <!-- Logout button is always available -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="hover:bg-blue-50 hover:text-blue-600">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <!-- Mobile Navigation Links -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')"
                class="flex items-center {{ request()->routeIs('users.index') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                {{ __('Users') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('office.index')" :active="request()->routeIs('office.index')"
                class="flex items-center {{ request()->routeIs('office.index') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                {{ __('Offices') }}
            </x-responsive-nav-link>
            
            @can('role-list')
            <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')"
                class="flex items-center {{ request()->routeIs('roles.index') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                {{ __('Roles and Permissions') }}
            </x-responsive-nav-link>
            @endcan
            
            @can('document-list')
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 focus:outline-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Documents') }}
                    <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute z-999 left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                    <a href="{{ route('documents.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" role="menuitem">{{ __('View') }}</a>
                    <a href="{{ route('documents.archive') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" role="menuitem">{{ __('Archives') }}</a>
                    <a href="{{ route('documents.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" role="menuitem">{{ __('Upload') }}</a>
                    <a href="{{ route('documents.workflows') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" role="menuitem">{{ __('Received') }}</a>
                </div>
            </div>
            @endcan
            
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')"
                class="flex items-center {{ request()->routeIs('reports.index') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ __('Reports') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Profile Section -->
        <!-- Mobile Profile Section -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link 
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>