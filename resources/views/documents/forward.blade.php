@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Forward Document</h1>
    
    @if($users->isEmpty())
        <div class="text-center">
            <p class="text-lg text-gray-700 mb-4">No users available to forward the document to.</p>
            <a href="{{ route('documents.index') }}" 
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Return to Documents
            </a>
        </div>
    @else
        <form action="{{ route('documents.forward.submit', $document->id) }}" method="POST">
            @csrf
            <div id="batches-container">
                <div class="batch-group mb-6" data-index="0">
                    <label class="block text-gray-700 text-lg font-semibold mb-2">
                          Recipients for Step <span class="step-order-label">1</span>
                    </label>
                    <input type="hidden" name="step_order[]" class="step-order" value="1">

                    <div class="mb-4">
                        <h3 class="text-md font-medium text-gray-700 mb-2">Select Offices</h3>
                        @foreach($offices as $office)
                            <div class="form-check mb-2">
                                <input class="form-checkbox office-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" 
                                       type="checkbox" 
                                       name="recipient_office_batch[0][]" 
                                       id="step0_office{{ $office->id }}" 
                                       value="{{ $office->id }}"
                                       data-office-id="{{ $office->id }}">
                                <label class="ml-2 text-gray-700" for="step0_office{{ $office->id }}">
                                    {{ $office->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h3 class="text-md font-medium text-gray-700 mb-2">Select Users</h3>
                        @foreach($users as $user)
                            <div class="form-check mb-2 user-item" data-office-ids="{{ json_encode($user->offices->pluck('id')) }}">
                                <input class="form-checkbox user-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" 
                                       type="checkbox" 
                                       name="recipient_batch[0][]" 
                                       id="step0_user{{ $user->id }}" 
                                       value="{{ $user->id }}">
                                <label class="ml-2 text-gray-700" for="step0_user{{ $user->id }}">
                                    {{ $user->first_name . ' ' . $user->last_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex gap-4 mt-6">
                <button type="button" 
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400" 
                        onclick="addBatch()">
                    Add Batch
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="prepareFormData()">
                    Forward Document
                </button>
            </div>
        </form>
    @endif
</div>

<script>
    let batchIndex = 1;

    function updateBatchOrders() {
        const batches = document.querySelectorAll('#batches-container .batch-group');
        batches.forEach((batch, index) => {
            // Update data-index and step order label/hidden input fields
            batch.dataset.index = index;
            batch.querySelector('.step-order').value = index + 1;
            batch.querySelector('.step-order-label').innerText = index + 1;
            
            // Update checkbox names & ids for each batch
            const checkboxes = batch.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach((checkbox) => {
                // Check if it's a user checkbox or office checkbox
                if(checkbox.classList.contains('user-checkbox')) {
                    checkbox.name = "recipient_batch[" + index + "][]";
                } else if(checkbox.classList.contains('office-checkbox')) {
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

        container.appendChild(newBatch);
        batchIndex++;
        updateBatchOrders();
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

    // Add form validation on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection