@extends('layouts.app')

@section('content')
<<<<<<< HEAD <div class="bg-gray-100 min-h-screen py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
                    =======
                    <div class="bg-gray-100 min-h-screen py-12">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <!-- Page Header -->
                            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
                                <div class="p-6 sm:flex sm:items-center sm:justify-between">
                                    <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
                                        >>>>>>> origin/ocrProcessing
                                        <svg class="h-8 w-8 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ __('Offices') }}
                                    </h2>
                                    <<<<<<< HEAD <div
                                        class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                        <a href="{{ route('office.create') }}"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            =======
                                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                                <a href="{{ route('office.create') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    >>>>>>> origin/ocrProcessing
                                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    {{ __('Add New Office') }}
                                                </a>
                                                <button id="viewToggle" <<<<<<< HEAD
                                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    =======
                                                    class="inline-flex items-center px-4 py-2 border border-transparent
                                                    text-sm font-medium rounded-md text-blue-700 bg-blue-100
                                                    hover:bg-blue-200 focus:outline-none focus:ring-2
                                                    focus:ring-offset-2 focus:ring-blue-500">
                                                    >>>>>>> origin/ocrProcessing
                                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                    </svg>
                                                    {{ __('Toggle View') }}
                                                </button>
                                            </div>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="mb-6">
                                <div class="relative">
                                    <input type="text" id="officeSearch" placeholder="{{ __('Search offices...') }}"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Messages -->
                            @if(session('error') || $errors->any())
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6" role="alert">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700 font-medium">
                                                {{ session('error') ?? __('There was an error!') }}
                                            </p>
                                            @if($errors->any())
                                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Default View -->
                            <div id="defaultView" class="bg-white shadow-md rounded-lg overflow-hidden">
                                <table id="officesTable" class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Office Name') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Created') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($offices as $office)
                                            <tr class="office-row hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 office-name">
                                                        {{ $office->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 office-created">
                                                        {{ $office->created_at->format('M d, Y') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">
                                                        <a href="{{ route('office.show', $office->id) }}"
                                                            class="text-blue-600 hover:text-blue-900">{{ __('Show') }}</a>
                                                        <a href="{{ route('office.edit', $office->id) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                        <form action="{{ route('office.destroy', $office->id) }}"
                                                            method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                onclick="return confirm('{{ __('Are you sure you want to delete this office?') }}')">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Hierarchical View -->
                            <div id="hierarchicalView" class="bg-white shadow-md rounded-lg overflow-hidden"
                                style="display:none;">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Offices') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            $officesByParent = $offices->groupBy(function ($office) {
                                                return $office->parentOffice ? $office->parentOffice->id : null;
                                            });

                                            $topLevelOffices = $officesByParent->get(null, collect());
                                            $childOfficeGroups = $officesByParent->except(null);
                                        @endphp

                                        @foreach ($topLevelOffices as $office)
                                                                                <tr>
                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                                        <a href="{{ route('office.show', $office->id) }}"
                                                                                            class="hover:text-blue-600">{{ $office->name }}</a>
                                                                                    </td>
                                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                                        @php
                                                                                            $childOffices = $childOfficeGroups->first(function ($group, $parentId) use ($office) {
                                                                                                $firstOffice = $group->first();
                                                                                                return $firstOffice && $firstOffice->parentOffice && $firstOffice->parentOffice->id === $office->id;
                                                                                            });
                                                                                        @endphp

                                                                                        @if ($childOffices && $childOffices->count() > 0)
                                                                                            <ul class="list-disc pl-5">
                                                                                                @foreach ($childOffices as $childOffice)
                                                                                                    <li><a href="{{ route('office.show', $childOffice->id) }}"
                                                                                                            class="hover:text-blue-600">{{ $childOffice->name }}</a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @else
                                                                                            {{ __('N/A') }}
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                        @endforeach

                                        @foreach ($childOfficeGroups as $parentId => $childOffices)
                                                                                @php
                                                                                    $parentOffice = $offices->firstWhere('id', $parentId);
                                                                                @endphp

                                                                                @if ($parentOffice && !$topLevelOffices->contains($parentOffice))
                                                                                    <tr>
                                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                                            <a href="{{ route('office.show', $parentOffice->id) }}"
                                                                                                class="hover:text-blue-600">{{ $parentOffice->name }}</a>
                                                                                        </td>
                                                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                                            <ul class="list-disc pl-5">
                                                                                                @foreach ($childOffices as $childOffice)
                                                                                                    <li><a href="{{ route('office.show', $childOffice->id) }}"
                                                                                                            class="hover:text-blue-600">{{ $childOffice->name }}</a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('officeSearch');
                            const officeRows = document.querySelectorAll('.office-row');
                            const defaultView = document.getElementById('defaultView');
                            const hierarchicalView = document.getElementById('hierarchicalView');
                            const viewToggle = document.getElementById('viewToggle');

                            searchInput.addEventListener('input', function () {
                                const searchTerm = this.value.toLowerCase().trim();

                                officeRows.forEach(row => {
                                    const officeName = row.querySelector('.office-name').textContent.toLowerCase();
                                    const officeCreated = row.querySelector('.office-created').textContent.toLowerCase();

                                    row.style.display = (officeName.includes(searchTerm) || officeCreated.includes(searchTerm)) ? '' : 'none';
                                });
                            });

                            viewToggle.addEventListener('click', function () {
<<<<<<< HEAD
                                const isDefaultView = !defaultView.classList.contains('hidden');

                                defaultView.classList.toggle('hidden');
                                hierarchicalView.classList.toggle('hidden');
                                searchInput.classList.toggle('hidden');
=======
        const isDefaultView = defaultView.style.display !== 'none';

        defaultView.style.display = isDefaultView ? 'none' : 'block';
        hierarchicalView.style.display = isDefaultView ? 'block' : 'none';
        searchInput.style.display = isDefaultView ? 'none' : 'block';
>>>>>>> origin/ocrProcessing

                                this.innerHTML = isDefaultView
                                    ? '<svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>{{ __("Table View") }}'
                                    : '<svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>{{ __("Hierarchical View") }}';
                            });
                        });
                    </script>

                    @endsection