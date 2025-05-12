@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <!-- Header Box -->
        <div class="bg-white rounded-xl shadow-xl mb-6 border border-blue-100 overflow-hidden">
            <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Forward Document</h1>
                        <p class="text-sm text-gray-500">Select recipients to forward this document</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Documents
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-white border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-md"
                role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-white border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        @if ($users->isEmpty())
            <div class="bg-white rounded-xl shadow-xl p-8 text-center border border-blue-100">
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="h-16 w-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">No Recipients Available</h2>
                    <p class="text-gray-500 text-lg mb-6">There are no users available to forward the document to.</p>
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Return to Documents
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-xl border border-blue-100 overflow-hidden">
                <div class="bg-white p-6 border-b border-blue-200">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-800">Forward Document to Recipients</h2>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Select offices and users for each forwarding step</p>
                </div>

                <form action="{{ route('documents.forward.submit', $document->id) }}" method="POST" class="p-6">
                    @csrf
                    <div id="batches-container" class="space-y-8">
                        <div class="batch-group bg-blue-50 p-6 rounded-xl border border-blue-100" data-index="0">
                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                    <span class="step-order-label">1</span>
                                </div>
                                <label class="ml-3 block text-gray-700 text-lg font-semibold">
                                    Recipients for Step <span class="step-order-label">1</span>
                                </label>
                            </div>
                            <input type="hidden" name="step_order[]" class="step-order" value="1">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Offices Selection -->
                                <div class="bg-white p-5 rounded-lg shadow-sm border border-blue-100">
                                    <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Select Offices
                                    </h3>
                                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                        @foreach ($offices as $office)
                                            <div
                                                class="form-check flex items-center p-2 hover:bg-blue-50 rounded-md transition-colors">
                                                <input
                                                    class="form-checkbox office-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                                    type="checkbox" name="recipient_office_batch[0][]"
                                                    id="step0_office{{ $office->id }}" value="{{ $office->id }}"
                                                    data-office-id="{{ $office->id }}">
                                                <label class="ml-2 text-gray-700 flex-grow cursor-pointer"
                                                    for="step0_office{{ $office->id }}">
                                                    {{ $office->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Users Selection -->
                                <div class="bg-white p-5 rounded-lg shadow-sm border border-blue-100">
                                    <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Select Users
                                    </h3>
                                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                        @foreach ($users as $user)
                                            <div class="form-check flex items-center p-2 hover:bg-blue-50 rounded-md transition-colors user-item"
                                                data-office-ids="{{ json_encode($user->offices->pluck('id')) }}">
                                                <input
                                                    class="form-checkbox user-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                                    type="checkbox" name="recipient_batch[0][]"
                                                    id="step0_user{{ $user->id }}" value="{{ $user->id }}">
                                                <label class="ml-2 text-gray-700 flex-grow cursor-pointer"
                                                    for="step0_user{{ $user->id }}">
                                                    {{ $user->first_name . ' ' . $user->last_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Purpose and Urgency Selection -->
                            <div class="bg-white p-5 rounded-lg shadow-sm border border-blue-100 mt-4">
                                <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Document Purpose and Timing
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Purpose Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                        <select name="purpose_batch[0]"
                                            class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            required>
                                            <option value="">Select Purpose</option>
                                            <option value="appropriate_action">Appropriate Action (Approval Required)
                                            </option>
                                            <option value="dissemination">Dissemination of Information</option>
                                            <option value="for_comment">For Comment</option>
                                        </select>
                                    </div>

                                    <!-- Urgency Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Urgency Level</label>
                                        <select name="urgency_batch[0]"
                                            class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="">Select Urgency (Optional)</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>

                                    <!-- Due Date Selection -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date
                                            (Optional)</label>
                                        <input type="date" name="due_date_batch[0]"
                                            class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            min="{{ date('Y-m-d') }}">
                                        <p class="text-xs text-gray-500 mt-1">Due date must be today or later.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Errors Container -->
                    <div id="validation-errors" class="mt-4"></div>

                    <div class="flex flex-wrap gap-4 mt-6">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            onclick="addBatch()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Batch
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            onclick="prepareFormData()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            Forward Document
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        let batchIndex = 1;

        function updateBatchOrders() {
            const batches = document.querySelectorAll('#batches-container .batch-group');
            batches.forEach((batch, index) => {
                // Update data-index and step order label/hidden input fields
                batch.dataset.index = index;
                const stepOrderLabels = batch.querySelectorAll('.step-order-label');
                stepOrderLabels.forEach(label => {
                    label.innerText = index + 1;
                });
                batch.querySelector('.step-order').value = index + 1;

                // Update checkbox names & ids for each batch
                const checkboxes = batch.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach((checkbox) => {
                    // Check if it's a user checkbox or office checkbox
                    if (checkbox.classList.contains('user-checkbox')) {
                        checkbox.name = "recipient_batch[" + index + "][]";
                    } else if (checkbox.classList.contains('office-checkbox')) {
                        checkbox.name = "recipient_office_batch[" + index + "][]";
                    }

                    // Update id attribute to include the batch index
                    const parts = checkbox.id.split('_');
                    checkbox.id = 'step' + index + '_' + parts.slice(1).join('_');

                    // Also update the corresponding label's "for" attribute
                    const label = checkbox.nextElementSibling;
                    if (label && label.tagName.toLowerCase() === 'label') {
                        label.htmlFor = checkbox.id;
                    }
                });

                // Update purpose_batch, urgency_batch and due_date_batch names
                const selects = batch.querySelectorAll('select');
                selects.forEach((select) => {
                    if (select.name.startsWith('purpose_batch')) {
                        select.name = "purpose_batch[" + index + "]";
                    } else if (select.name.startsWith('urgency_batch')) {
                        select.name = "urgency_batch[" + index + "]";
                    }
                });

                const dateInputs = batch.querySelectorAll('input[type="date"]');
                dateInputs.forEach((input) => {
                    if (input.name.startsWith('due_date_batch')) {
                        input.name = "due_date_batch[" + index + "]";
                    }
                });
            });
        }

        function addBatch() {
            const container = document.getElementById('batches-container');
            // Clone the first batch-group as a template
            const template = container.querySelector('.batch-group');
            const newBatch = template.cloneNode(true);

            // Reset checkboxes in the new batch
            const checkboxes = newBatch.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => {
                cb.checked = false;
                const formCheck = cb.closest('.form-check');
                if (formCheck) {
                    formCheck.style.display = '';
                }
            });

            // Reset select and input fields
            const selects = newBatch.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });

            const dateInputs = newBatch.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.value = '';
            });

            // Show all users in the new batch
            const userItems = newBatch.querySelectorAll('.user-item');
            userItems.forEach(userItem => {
                userItem.style.display = '';
            });

            container.appendChild(newBatch);
            batchIndex++;
            updateBatchOrders();

            // Attach event listeners to the new batch's office checkboxes
            const newOfficeCheckboxes = newBatch.querySelectorAll('.office-checkbox');
            newOfficeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function(event) {
                    filterUsersByOffice(event, newBatch);
                });
            });
        }

        function validateForm() {
            const batches = document.querySelectorAll('.batch-group');
            let isValid = true;

            // Remove any existing error messages
            document.querySelectorAll('.validation-error').forEach(el => el.remove());

            batches.forEach(batch => {
                const batchIndex = batch.dataset.index;
                const selectedOffices = batch.querySelectorAll('.office-checkbox:checked');
                const selectedUsers = batch.querySelectorAll('.user-checkbox:checked');

                // Each batch must have at least one office OR one user selected
                if (selectedOffices.length === 0 && selectedUsers.length === 0) {
                    isValid = false;

                    // Create and display error message
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error text-red-600 mt-2 mb-2';
                    errorMsg.textContent = 'Please select at least one office or one user in this batch';

                    // Insert error before the end of this batch
                    batch.appendChild(errorMsg);
                }
            });

            return isValid;
        }

        function filterUsersByOffice(event, specificBatch = null) {
            // Get the current batch that triggered the event
            const currentBatch = specificBatch || (event ? event.target.closest('.batch-group') : null);
            
            if (!currentBatch) return;
            
            // Get selected offices in this batch
            const selectedOfficeIds = Array.from(currentBatch.querySelectorAll('.office-checkbox:checked'))
                .map(checkbox => checkbox.value);
            
            // Filter user items in this batch only
            const userItems = currentBatch.querySelectorAll('.user-item');
            userItems.forEach(userItem => {
                const userOfficeIds = JSON.parse(userItem.dataset.officeIds);
                
                // If no offices are selected, show all users
                if (selectedOfficeIds.length === 0) {
                    userItem.style.display = '';
                    return;
                }
                
                // Check if the user belongs to any of the selected offices
                const isVisible = selectedOfficeIds.some(officeId => 
                    userOfficeIds.includes(parseInt(officeId)));
                
                // Show or hide the user item based on the filter
                userItem.style.display = isVisible ? '' : 'none';
            });
        }

        function prepareFormData() {
            // This function is called on form submission
            // Keeping it for compatibility with the original code
            return validateForm();
        }

        // Add form validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                }
            });

            // Add event listeners to office checkboxes
            const batches = document.querySelectorAll('.batch-group');
            batches.forEach(batch => {
                const officeCheckboxes = batch.querySelectorAll('.office-checkbox');
                officeCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function(event) {
                        filterUsersByOffice(event, batch);
                    });
                });
            });
        });
    </script>
@endsection
