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
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
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
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('is_active', $schedule->is_active ?? false) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
            </div>

            <div class="lg:col-span-4 flex gap-4">
                <button type="submit" class="flex-1 py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">Save Schedule</button>
                
                @if($schedule && $schedule->is_active)
                <form action="{{ route('admin.document-management.run-schedule') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded" onclick="return confirm('Are you sure you want to run the deletion schedule now? This will permanently delete documents based on the configured criteria.');">
                        Run Deletion Schedule Now
                    </button>
                </form>
                @endif
            </div>
        </form>
        
        @if($schedule)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Current Schedule Settings</h3>
            <div class="bg-gray-100 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium">Status:</span> 
                        <span class="ml-2 {{ $schedule->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium">Criteria:</span> 
                        <span class="ml-2">{{ ucfirst($schedule->criteria) }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Retention Period:</span> 
                        <span class="ml-2">{{ $schedule->retention_days }} days</span>
                    </div>
                    <div>
                        <span class="font-medium">Storage Limit:</span> 
                        <span class="ml-2">{{ $schedule->storage_limit_mb }} MB</span>
                    </div>
                    <div>
                        <span class="font-medium">Last Executed:</span> 
                        <span class="ml-2">
                            {{ $schedule->last_executed_at ? $schedule->last_executed_at->format('M d, Y H:i') : 'Never' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
