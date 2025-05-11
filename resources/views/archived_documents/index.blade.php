@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Teams Column -->
        <div class="w-full md:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-4">Teams</h2>
                <ul>
                    @forelse($teams as $team)
                        <li>
                            <a href="?team_id={{ $team->id }}"
                               class="block px-4 py-2 rounded {{ $selectedTeamId == $team->id ? 'bg-blue-100 text-blue-700 font-bold' : 'hover:bg-blue-50' }}">
                                {{ $team->name }}
                            </a>
                        </li>
                    @empty
                        <li class="text-gray-400">No teams found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- Archived Documents Column -->
        <div class="w-full md:w-2/3">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-4">Archived Documents</h2>
                @if($documents->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Uploader</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Archived By</th>
<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                                <tr>
                                    <td class="px-4 py-2">{{ $doc->title }}</td>
                                    <td class="px-4 py-2">{{ $doc->user->first_name ?? 'N/A' }} {{ $doc->user->last_name ?? '' }}</td>
                                    <td class="px-4 py-2">{{ $doc->updated_at->diffForHumans() }}</td>
<td class="px-4 py-2">
    @if($doc->archivedBy)
        {{ $doc->archivedBy->first_name }} {{ $doc->archivedBy->last_name }}
    @else
        <span class="text-gray-400">N/A</span>
    @endif
</td>
<td class="px-4 py-2">
    <a href="{{ route('documents.show', $doc->id) }}" class="text-blue-600 hover:underline">View</a>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-gray-400">No archived documents found for this team.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
