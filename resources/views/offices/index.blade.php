@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Offices</h2>
        <a href="{{ route('office.create') }}" class="btn btn-primary">
            <i class="fa fa-plus mr-2"></i> Add New Office
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200">Name</th>
                    <th class="py-2 px-4 border-b border-gray-200">Offices</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Group offices by parent office
                    $officesByParent = $offices->groupBy(function($office) {
                        return $office->parentOffice ? $office->parentOffice->id : null;
                    });
    
                    // Separate top-level and child offices
                    $topLevelOffices = $officesByParent->get(null, collect());
                    $childOfficeGroups = $officesByParent->except(null);
                @endphp
    
                {{-- First, render top-level offices without a parent --}}
                @foreach ($topLevelOffices as $office)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200 font-semibold">{{ $office->name }}</td>
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
                                        <li>{{ $childOffice->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
    
                {{-- Then, render any remaining parent offices with children --}}
                @foreach ($childOfficeGroups as $parentId => $childOffices)
                    @php
                        $parentOffice = $offices->firstWhere('id', $parentId);
                    @endphp
                    
                    @if ($parentOffice && !$topLevelOffices->contains($parentOffice))
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200 font-semibold">{{ $parentOffice->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <ul class="list-disc pl-5">
                                    @foreach ($childOffices as $childOffice)
                                        <li>{{ $childOffice->name }}</li>
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
@endsection