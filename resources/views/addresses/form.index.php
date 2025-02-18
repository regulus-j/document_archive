<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ isset($address) ? 'Edit Address' : 'Add New Address' }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ isset($address) ? 'Update company address information' : 'Add a new company address' }}
                        </p>
                    </div>

                    <form
                        action="{{ isset($address) ? route('addresses.update', $address) : route('addresses.store') }}"
                        method="POST" class="space-y-6">
                        @csrf
                        @if(isset($address))
                        @method('PUT')
                        @endif

                        @if(!isset($address))
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>
                            <select id="company_id" name="company_id" required
                                class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                <option value="">Select a company</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id')==$company->id ? 'selected' : ''
                                    }}>
                                    {{ $company->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('company_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
                            <input type="text" id="address" name="address"
                                value="{{ old('address', $address->address ?? '') }}" required
                                class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                            @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city', $address->city ?? '') }}"
                                    required
                                    class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" id="state" name="state"
                                    value="{{ old('state', $address->state ?? '') }}" required
                                    class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                @error('state')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                <input type="text" id="zip_code" name="zip_code"
                                    value="{{ old('zip_code', $address->zip_code ?? '') }}" required
                                    class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                @error('zip_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" id="country" name="country"
                                    value="{{ old('country', $address->country ?? '') }}" required
                                    class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                                @error('country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-x-3">
                            <a href="{{ route('addresses.index') }}"
                                class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                {{ isset($address) ? 'Update Address' : 'Add Address' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>