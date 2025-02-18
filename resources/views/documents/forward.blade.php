@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Forward Document</h1>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6">
                <form action="{{ route('documents.forward.submit', $document->id) }}" method="POST">
                    @csrf
                    <div id="batches-container" class="space-y-6">
                        <div class="batch-group" data-index="0">
                            <div class="mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">    
                                    Recipients for Step <span class="step-order-label">1</span>
                                </h2>
                                <input type="hidden" name="step_order[]" class="step-order" value="1">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($users as $user)
                                    <div class="form-check flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-50">
                                        <input class="form-check-input h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            type="checkbox"
                                            name="recipient_batch[0][]"
                                            id="step0_user{{ $user->id }}"
                                            value="{{ $user->id }}">
                                        <label class="form-check-label text-sm text-gray-700 cursor-pointer"
                                            for="step0_user{{ $user->id }}">
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-x-3">
                        <button type="button" 
                            onclick="addBatch()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-5 w-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Step
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Forward Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let batchIndex = 1;

    function updateBatchOrders() {
        const batches = document.querySelectorAll('#batches-container .batch-group');
        batches.forEach((batch, index) => {
            batch.dataset.index = index;
            batch.querySelector('.step-order').value = index + 1;
            batch.querySelector('.step-order-label').innerText = index + 1;
            
            const checkboxes = batch.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach((checkbox) => {
                checkbox.name = `recipient_batch[${index}][]`;
                const parts = checkbox.id.split('_');
                checkbox.id = `step${index}_${parts.slice(1).join('_')}`;
                
                const label = checkbox.nextElementSibling;
                if (label && label.tagName.toLowerCase() === 'label') {
                    label.htmlFor = checkbox.id;
                }
            });
        });
    }

    document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', removeDuplicates);
    });

    function removeDuplicates() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const groups = {};

        checkboxes.forEach(cb => {
            const val = cb.value;
            if (!groups[val]) groups[val] = [];
            groups[val].push(cb);
        });

        Object.keys(groups).forEach(val => {
            const group = groups[val];
            const checked = group.find(cb => cb.checked);
            if (checked) {
                group.forEach(cb => {
                    if (cb !== checked) {
                        const formCheck = cb.closest('.form-check');
                        if (formCheck) formCheck.style.display = 'none';
                    }
                });
            } else {
                group.forEach(cb => {
                    const formCheck = cb.closest('.form-check');
                    if (formCheck) formCheck.style.display = '';
                });
            }
        });
    }

    function addBatch() {
        const container = document.getElementById('batches-container');
        const template = container.querySelector('.batch-group');
        const newBatch = template.cloneNode(true);

        const checkboxes = newBatch.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            cb.checked = false;
            cb.addEventListener('change', removeDuplicates);
            const formCheck = cb.closest('.form-check');
            if (formCheck) formCheck.style.display = '';
        });

        container.appendChild(newBatch);
        updateBatchOrders();
        removeDuplicates();
    }
</script>
@endsection