{{-- filepath: /c:/xampp/htdocs/development/document_archive/resources/views/documents/forward.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Forward Document</h1>
    <form action="{{ route('documents.forward', $document) }}" method="POST">
        @csrf
        <div id="batches-container">
            <div class="batch-group mb-3" data-index="0">
                <label>Recipients for Step <span class="step-order-label">1</span></label>
                <input type="hidden" name="step_order[]" class="step-order" value="1">
                @foreach($users as $user)
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="recipient_batch[0][]" 
                               id="step0_user{{ $user->id }}" 
                               value="{{ $user->id }}">
                        <label class="form-check-label" for="step0_user{{ $user->id }}">
                            {{ $user->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addBatch()">Add Batch</button>
        <button type="submit" class="btn btn-primary">Forward Document</button>
    </form>
</div>

<script>
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
                checkbox.name = "recipient_batch[" + index + "][]";
                
                // Update id attribute to include the batch index if needed.
                // Split the original id ("step0_userX"), then rebuild using the new index.
                const parts = checkbox.id.split('_');
                // parts[0] is "step0" and parts[1] is "userX"
                // Replace the step part with current index.
                checkbox.id = 'step' + index + '_' + parts.slice(1).join('_');
                
                // Also update the corresponding label's "for" attribute.
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

        // Reset all checkboxes in the new batch
        const checkboxes = newBatch.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });

        container.appendChild(newBatch);
        updateBatchOrders();
    }
</script>
@endsection