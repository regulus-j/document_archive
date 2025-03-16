@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-center mb-6">Edit Company</h2>
        
        <form method="POST" action="{{ route('companies.update', $company->id) }}">
            @csrf
            @method('PUT')

            <!-- Company Details -->
            <div class="grid gap-4">
                <div>
                    <label class="block font-medium">Company Name</label>
                    <input type="text" name="company_name" value="{{ $company->company_name }}" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Registered Name</label>
                    <input type="text" name="registered_name" value="{{ $company->registered_name }}" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Company Email</label>
                    <input type="email" name="company_email" value="{{ $company->company_email }}" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Company Phone</label>
                    <input type="text" name="company_phone" value="{{ $company->company_phone }}" class="w-full p-2 border rounded" required>
                </div>
            </div>
           <!-- Company Address -->
<div class="mt-4">
    <h3 class="font-medium">Company Address</h3>
    <div class="grid grid-cols-2 gap-4 mt-2">
        <div>
            <label class="block font-medium">State</label>
            <input type="text" name="state" value="{{ old('state', $company->address->state ?? '') }}" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label class="block font-medium">City</label>
            <input type="text" name="city" value="{{ old('city', $company->address->city ?? '') }}" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label class="block font-medium">ZIP Code</label>
            <input type="text" name="zip_code" value="{{ old('zip_code', $company->address->zip_code ?? '') }}" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label class="block font-medium">Country</label>
            <input type="text" name="country" value="{{ old('country', $company->address->country ?? '') }}" class="w-full p-2 border rounded" required>
        </div>
    </div>
</div>

            <!-- Users in Company -->
            <div class="mt-6">
                <h3 class="font-medium">Users in Company</h3>
                <input type="text" id="userSearch" placeholder="Search users..." class="w-full p-2 border rounded mt-2">
                <div id="userList" class="mt-2 max-h-40 overflow-y-auto border p-2 rounded">
                    @foreach($users as $user)
                        <div class="flex justify-between items-center border-b py-2">
                            <span>{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</span>
                            <button type="button" class="text-red-500" onclick="removeUser({{ $user->id }})">Remove</button>
                        </div>
                    @endforeach
                </div>
                
                <!-- Add User Section -->
                <select name="new_user_id" class="w-full p-2 border rounded mt-2">
                    <option value="">-- Select User to Add --</option>
                    @foreach($allUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
    <label class="block font-medium">Company Admin</label>
    <select name="company_admin_id" class="w-full p-2 border rounded">
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $company->company_admin_id == $user->id ? 'selected' : '' }}>
                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
</div>


            <!-- Submit Buttons -->
            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update Company</button>
                <a href="{{ route('companies.index') }}" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('userSearch').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let users = document.querySelectorAll('#userList div');

        users.forEach(function(user) {
            let text = user.textContent.toLowerCase();
            user.style.display = text.includes(searchText) ? '' : 'none';
        });
    });

    function removeUser(userId) {
        // Implement AJAX call to remove user from company
        alert('Removing user ID ' + userId);
    }
</script>
@endsection
