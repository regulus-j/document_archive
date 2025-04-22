<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="h3">Manage Deletion Schedule</h1>
                <p>Current storage usage: <strong>{{ $storageUsageMB }} MB</strong></p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.document-management.save-schedule') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @csrf
            <div class="mb-4">
                <label for="criteria" class="block text-sm font-medium text-gray-700">Criteria</label>
                <select name="criteria" id="criteria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="age" {{ old('criteria', $schedule->criteria ?? '')=='age' ? 'selected' : '' }}>By Age</option>
                    <option value="storage" {{ old('criteria', $schedule->criteria ?? '')=='storage' ? 'selected' : '' }}>By Storage</option>
                    <option value="both" {{ old('criteria', $schedule->criteria ?? '')=='both' ? 'selected' : '' }}>Both</option>
                </select>
                @error('criteria') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="retention_days" class="block text-sm font-medium text-gray-700">Retention Period (days)</label>
                <input type="number" name="retention_days" id="retention_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('retention_days', $schedule->retention_days ?? 365) }}" min="1">
                @error('retention_days') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="storage_limit_mb" class="block text-sm font-medium text-gray-700">Storage Limit (MB)</label>
                <input type="number" name="storage_limit_mb" id="storage_limit_mb" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('storage_limit_mb', $schedule->storage_limit_mb ?? '') }}" min="1">
                @error('storage_limit_mb') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="flex items-center mb-4">
                <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('is_active', $schedule->is_active ?? false) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
            </div>

            <div class="lg:col-span-4">
                <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">Save Schedule</button>
            </div>
        </form>
    </div>
</x-app-layout>
