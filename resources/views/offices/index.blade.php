@extends('layouts.app')
@section('content')
<div class="container mx-auto my-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Offices</h2>
        <div class="flex space-x-2">
            <a href="{{ route('office.create') }}" class="btn btn-primary">
                <i class="fa fa-plus mr-2"></i> Add New Office
            </a>
            <button id="viewToggle" class="btn btn-secondary">
                <i class="fa fa-list-ul mr-2"></i> Toggle View
            </button>
        </div>
    </div>
    
    <div class="mb-4">
        <input 
            type="text" 
            id="officeSearch" 
            placeholder="Search offices..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
    </div>

    @if(session('error') || $errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">{{ session('error') ?? 'There was an error!' }}</strong>
            @if($errors->any())
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <div id="defaultView" class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <table id="officesTable" class="w-full text-sm">
            <thead class="bg-blue-50 text-blue-800">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left">Office Name</th>
                    <th scope="col" class="px-4 py-3 text-left">Created</th>
                    <th scope="col" class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($offices as $office)
                <tr class="office-row border-b border-gray-200 hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 office-name">{{ $office->name }}</td>
                    <td class="px-4 py-3 text-gray-700 office-created">{{ $office->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('office.show', $office->id) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fa-solid fa-list mr-1"></i> Show
                            </a>
                            <a href="{{ route('office.edit', $office->id) }}"
                               class="text-green-600 hover:text-green-800 transition-colors">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            <form action="{{ route('office.destroy', $office->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        href="{{ route('office.destroy', $office->id) }}"
                                        onclick="return confirm('Are you sure you want to delete this office?')">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="hierarchicalView" class="bg-white rounded-lg shadow-lg overflow-hidden" style="display:none;">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200">Name</th>
                    <th class="py-2 px-4 border-b border-gray-200">Offices</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $officesByParent = $offices->groupBy(function($office) {
                        return $office->parentOffice ? $office->parentOffice->id : null;
                    });
   
                    $topLevelOffices = $officesByParent->get(null, collect());
                    $childOfficeGroups = $officesByParent->except(null);
                @endphp
   
                @foreach ($topLevelOffices as $office)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200 font-semibold focus-ring hover:bg-gray-100">
                            <a href="{{ route('office.show', $office->id) }}">{{ $office->name }}</a>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            @php
                                $childOffices = $childOfficeGroups->first(function($group, $parentId) use ($office) {
                                    $firstOffice = $group->first();
                                    return $firstOffice && $firstOffice->parentOffice && $firstOffice->parentOffice->id === $office->id;
                                });
                            @endphp
                           
                            @if ($childOffices && $childOffices->count() > 0)
                                <ul class="list-disc pl-5">
                                    @foreach ($childOffices as $childOffice)
                                        <li><a href="{{ route('office.show', $childOffice->id) }}" class="hover:bg-gray-100 focus-ring">{{ $childOffice->name }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                N/A
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
                            <td class="py-2 px-4 border-b border-gray-200 font-semibold hover:bg-gray-100 focus-ring">
                                <a href="{{ route('office.show', $parentOffice->id) }}">{{ $parentOffice->name }}</a>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <ul class="list-disc pl-5">
                                    @foreach ($childOffices as $childOffice)
                                    <li><a href="{{ route('office.show', $childOffice->id) }}" class="hover:bg-gray-100 focus-ring">{{ $childOffice->name }}</a></li>
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


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('officeSearch');
    const officeRows = document.querySelectorAll('.office-row');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        officeRows.forEach(row => {
            const officeName = row.querySelector('.office-name').textContent.toLowerCase();
            const officeCreated = row.querySelector('.office-created').textContent.toLowerCase();

            // Show row if search term matches office name or created date
            if (officeName.includes(searchTerm) || officeCreated.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    document.getElementById('viewToggle').addEventListener('click', function() {
        const defaultView = document.getElementById('defaultView');
        const hierarchicalView = document.getElementById('hierarchicalView');
        
        if (defaultView.style.display !== 'none') {
            defaultView.style.display = 'none';
            officeSearch.style.display = 'none';
            hierarchicalView.style.display = 'block';
        } else {
            defaultView.style.display = 'block';
            hierarchicalView.style.display = 'none';
            officeSearch.style.display = 'block';
        }
    });
});
</script>

@endsection