<div class="space-y-6">
    <input type="hidden" name="part" value='2'>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>

        <div>
            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
            <input type="text" name="city" id="city" value="{{ old('city') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>

        <div>
            <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
            <input type="text" name="state" id="state" value="{{ old('state') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>

        <div>
            <label for="zip_code" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>

        <div>
            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
            <input type="text" name="country" id="country" value="{{ old('country') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>
    </div>

    <div class="pt-5">
        <div class="flex justify-end">
            <button type="submit"
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save Address
            </button>
        </div>
    </div>
</div>