@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Reports & Analytics</h1>
    <form action="{{ route('reports.analytics') }}" method="GET" class="space-y-4 mb-8">
        @csrf
        <div>
            <label for="user_id" class="block font-medium text-sm mb-1">User</label>
            <select name="user_id" id="user_id" class="w-full border-gray-300 rounded">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="office_id" class="block font-medium text-sm mb-1">Office</label>
            <select name="office_id" id="office_id" class="w-full border-gray-300 rounded">
                <option value="">All Offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" {{ $officeId == $office->id ? 'selected' : '' }}>
                        {{ $office->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex space-x-4">
            <div>
                <label for="start_date" class="block font-medium text-sm mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="border-gray-300 rounded w-full">
            </div>
            <div>
                <label for="end_date" class="block font-medium text-sm mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border-gray-300 rounded w-full">
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
    </form>

    <table class="w-full border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Avg Time to Receive</th>
                <th class="border px-4 py-2">Avg Time to Review</th>
                <th class="border px-4 py-2">Docs Forwarded</th>
                <th class="border px-4 py-2">Docs Uploaded</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">{{ $averageTimeToReceive ?? 0 }} minutes</td>
                <td class="border px-4 py-2">{{ $averageTimeToReview ?? 0 }} minutes</td>
                <td class="border px-4 py-2">{{ $averageDocsForwarded }}</td>
                <td class="border px-4 py-2">{{ $documentsUploaded }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
    <!-- Include jQuery if not already loaded -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#user_id, #office_id').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>
@endsection