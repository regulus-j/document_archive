@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-8">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Office Details</h2>
            <a href="{{ route('office.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i> Back
            </a>
        </div>

        <!-- Office Information -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-xl font-semibold">{{ $office->name }}</h3>
                        <p class="text-gray-600">
                            @if ($office->parentOffice)
                                Parent Office: {{ $office->parentOffice->name }}
                            @else
                                <span class="text-gray-400">Parent Office: N/A</span>
                            @endif
                        </p>
                        <p class="text-gray-600">Created At: {{ $office->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-gray-600">Updated At: {{ $office->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('office.edit', $office->id) }}" class="btn btn-primary">
                            <i class="fa fa-pencil mr-2"></i> Edit
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-2">Users</h4>
                    @if ($office->users->isEmpty())
                        <p class="text-gray-400">No users assigned to this office.</p>
                    @else
                        <ul class="list-disc list-inside">
                            @foreach ($office->users as $user)
                                <li>{{ $user->name }} ({{ $user->email }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection