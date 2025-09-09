@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white p-4 md:p-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Box -->
            <div class="bg-white rounded-xl mb-6 border border-blue-200/80 overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-2xl font-bold text-gray-800">Forward Document</h1>
                            <p class="text-sm text-gray-500">Select recipients to forward this document</p>
                        </div>
                    </div>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Back to Documents
                    </a>
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
                <div class="bg-white rounded-xl p-8 text-center border border-blue-200/80">
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="h-16 w-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">No Recipients Available</h2>
                        <p class="text-gray-500 text-lg mb-6">There are no users available to forward the document to.</p>
                        <a href="{{ route('documents.index') }}"
                            class="inline-flex items-center px-5 py-3 border border-blue-600 text-blue-600 bg-white text-base font-medium rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
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
                <div class="bg-white rounded-xl border border-blue-200/80 overflow-hidden">
                    <div class="bg-white p-6 border-b border-blue-200/60">
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
                    <div id="batches-container" class="space-y-6">
                        <div class="batch-group bg-blue-50/50 p-6 rounded-xl border border-blue-200/60" data-index="0">
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
                                <div class="bg-white p-5 rounded-xl border border-blue-200/60">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Select Offices
                                    </h3>
                                    <div class="space-y-1.5 max-h-60 overflow-y-auto pr-2">
                                        @foreach ($offices as $office)
                                            <div class="p-2">
                                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                                    <input type="radio"
                                                           class="office-radio"
                                                           name="recipient_batch[0]"
                                                           id="step0_office{{ $office->id }}"
                                                           value="office_{{ $office->id }}">
                                                    <span>{{ $office->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Users Selection -->
                                <div class="bg-white p-5 rounded-xl border border-blue-200/60">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Select Users
                                    </h3>

                                    <!-- Office Filter Dropdown -->
                                    <div class="mb-4">
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Filter by Office</label>
                                        <select class="office-filter w-full rounded-lg border-gray-200 text-sm text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            <option value="all">All Offices</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-1.5 max-h-60 overflow-y-auto pr-2 user-list-container">
                                        @foreach ($users as $user)
                                            <div class="user-item p-2" data-office-ids="{{ json_encode($user->offices->pluck('id')) }}">
                                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                                    <input type="radio"
                                                           class="user-radio"
                                                           name="recipient_batch[0]"
                                                           id="step0_user{{ $user->id }}"
                                                           value="user_{{ $user->id }}">
                                                    <span>{{ $user->first_name . ' ' . $user->last_name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Purpose and Urgency Selection -->
                            <div class="bg-white p-5 rounded-xl border border-blue-200/60 mt-4">
                                <!-- Template Selection (New Feature) -->
                                <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg">
                                    <label class="flex items-center mb-3">
                                        <input type="checkbox" id="useTemplate" name="use_template" class="rounded text-purple-600 focus:ring-purple-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                            Apply Workflow Template
                                        </span>
                                    </label>
                                    <div id="templateSelection" class="hidden">
                                        <select name="template_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 mb-2">
                                            <option value="">Select a template...</option>
                                            <!-- Templates will be loaded here via JavaScript -->
                                        </select>
                                        <p class="text-xs text-purple-600 bg-white/60 p-2 rounded">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span id="templateInfo">Template will apply predefined workflow steps to the recipients you select below</span>
                                        </p>
                                    </div>
                                </div>

                                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Document Purpose and Timing
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Purpose Selection -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Purpose</label>
                                        <select name="purpose_batch[0]"
                                            class="w-full rounded-lg border-gray-200 text-sm text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
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
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Urgency Level</label>
                                        <select name="urgency_batch[0]"
                                            class="w-full rounded-lg border-gray-200 text-sm text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select Urgency (Optional)</option>
                                            <option value="low" class="text-blue-600">Low</option>
                                            <option value="medium" class="text-yellow-600">Medium</option>
                                            <option value="high" class="text-orange-600">High</option>
                                            <option value="critical" class="text-red-600">Critical</option>
                                        </select>
                                    </div>

                                    <!-- Due Date Selection -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Due Date
                                            (Optional)</label>
                                        <input type="date" name="due_date_batch[0]"
                                            class="w-full rounded-lg border-gray-200 text-sm text-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                            min="{{ date('Y-m-d') }}">
                                        <p class="text-xs text-gray-500 mt-1.5">Due date must be today or later.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Errors Container -->
                    <div id="validation-errors" class="mt-4"></div>

                    <div class="flex flex-wrap items-center gap-3 mt-6">
                        <button type="button"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-blue-400 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200"
                            onclick="addBatch()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Batch
                        </button>
                        <button type="button" id="remove-batch-btn"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors duration-200 hidden"
                            onclick="removeLastBatch()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove Last Batch
                        </button>
                        <button type="submit"
                            class="flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200 ml-auto"
                            onclick="prepareFormData()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Forward Document
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        let batchIndex = 1; // This is used to give a unique starting point for cloned batch elements before updateBatchOrders standardizes them.

        // Template functionality
        document.addEventListener('DOMContentLoaded', function() {
            const useTemplateCheckbox = document.getElementById('useTemplate');
            const templateSelection = document.getElementById('templateSelection');
            const templateSelect = document.querySelector('select[name="template_id"]');
            const batchesContainer = document.getElementById('batches-container');

            // Toggle template selection visibility
            if (useTemplateCheckbox) {
                useTemplateCheckbox.addEventListener('change', function() {
                    console.log('Template checkbox changed:', this.checked);
                    if (this.checked) {
                        templateSelection.classList.remove('hidden');
                        loadTemplates();
                        // Disable manual recipient selection
                        toggleManualSelection(false);
                    } else {
                        templateSelection.classList.add('hidden');
                        // Re-enable manual recipient selection
                        toggleManualSelection(true);
                    }
                });
            } else {
                console.error('useTemplate checkbox not found');
            }

            // Function to enable/disable manual recipient selection
            function toggleManualSelection(enable) {
                const recipientBatches = batchesContainer.querySelectorAll('.batch-group');
                
                recipientBatches.forEach(batch => {
                    const radioInputs = batch.querySelectorAll('input[type="radio"]');
                    
                    // Keep recipients enabled but visually indicate template mode
                    radioInputs.forEach(input => {
                        // Don't disable, just style differently when template is selected
                        input.disabled = false;
                    });

                    // Visual feedback for the recipient selection area
                    const recipientGrid = batch.querySelector('.grid');
                    if (recipientGrid) {
                        if (enable) {
                            recipientGrid.style.opacity = '1';
                            recipientGrid.style.backgroundColor = '';
                        } else {
                            // Show that template will be used but keep interactive
                            recipientGrid.style.opacity = '0.8';
                            recipientGrid.style.backgroundColor = 'rgba(147, 51, 234, 0.05)';
                        }
                    }
                });

                // Update info message
                const templateInfo = document.getElementById('templateInfo');
                if (templateInfo) {
                    templateInfo.textContent = enable ? 
                        'Select recipients manually' : 
                        'Template will apply predefined workflow steps to the recipients you select below';
                }
            }

            // Load available templates
            async function loadTemplates() {
                console.log('Loading templates...');
                try {
                    const response = await fetch('/api/workflow-templates', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    console.log('Template API response status:', response.status);
                    
                    if (response.ok) {
                        const data = await response.json();
                        console.log('Template API data:', data);
                        if (data.success && data.data) {
                            populateTemplateOptions(data.data);
                        }
                    }
                } catch (error) {
                    console.error('Error loading templates:', error);
                }
            }

            function populateTemplateOptions(templates) {
                // Clear existing options except the first one
                templateSelect.innerHTML = '<option value="">Select a template...</option>';
                
                templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id;
                    option.textContent = `${template.name} (${template.steps_count} steps, ${template.workflow_type})`;
                    templateSelect.appendChild(option);
                });
            }

            // Initialize other functionality
            updateBatchOrders();
            setupFormValidation();
            setupEventListeners();
        });

        function setupFormValidation() {
            // Add form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                }
            });
        }

        function setupEventListeners() {
            // Add event listeners for office filter dropdowns
            document.querySelectorAll('.office-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    filterUsersByOffice(this);
                });
            });

            // Add event listeners for office radio buttons to uncheck user radio buttons when an office is selected
            document.querySelectorAll('.office-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const batch = this.closest('.batch-group');
                        const userRadios = batch.querySelectorAll('.user-radio');
                        userRadios.forEach(userRadio => {
                            userRadio.checked = false;
                        });
                    }
                });
            });

            // Add event listeners for user radio buttons to uncheck office radio buttons when a user is selected
            document.querySelectorAll('.user-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const batch = this.closest('.batch-group');
                        const officeRadios = batch.querySelectorAll('.office-radio');
                        officeRadios.forEach(officeRadio => {
                            officeRadio.checked = false;
                        });
                    }
                });
            });
        }

        function updateBatchOrders() {
            const batches = document.querySelectorAll('#batches-container .batch-group');

            // Show/hide the remove batch button based on number of batches
            const removeBatchBtn = document.getElementById('remove-batch-btn');
            removeBatchBtn.classList.toggle('hidden', batches.length <= 1);

            batches.forEach((batch, index) => {
                // Update data-index and step order label/hidden input fields
                batch.dataset.index = index;
                const stepOrderLabels = batch.querySelectorAll('.step-order-label');
                stepOrderLabels.forEach(label => {
                    label.innerText = index + 1;
                });
                batch.querySelector('.step-order').value = index + 1;

                // Update radio button names & ids for each batch
                const recipientRadios = batch.querySelectorAll('input[type="radio"].office-radio, input[type="radio"].user-radio');
                recipientRadios.forEach((radio) => {
                    radio.name = `recipient_batch[${index}]`; // Shared name for radio group in this batch

                    // Update id attribute to include the batch index
                    const parts = radio.id.split('_'); // e.g., step0_officeID or step0_userID
                    radio.id = `step${index}_${parts.slice(1).join('_')}`; // e.g. step1_officeID

                    // Also update the corresponding label's "for" attribute
                    const label = radio.nextElementSibling;
                    if (label && label.tagName.toLowerCase() === 'label') {
                        label.htmlFor = radio.id;
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

        function removeLastBatch() {
            const container = document.getElementById('batches-container');
            const batches = container.querySelectorAll('.batch-group');

            // Don't remove if there's only one batch
            if (batches.length > 1) {
                const lastBatch = batches[batches.length - 1];
                container.removeChild(lastBatch);
                updateBatchOrders();
            }
        }

        function addBatch() {
            const container = document.getElementById('batches-container');
            // Clone the first batch-group as a template
            const template = container.querySelector('.batch-group');
            const newBatch = template.cloneNode(true);

            // Reset radio buttons in the new batch
            const radios = newBatch.querySelectorAll('input[type="radio"].office-radio, input[type="radio"].user-radio');
            radios.forEach(radio => {
                radio.checked = false;
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
                userItem.style.display = 'flex'; // Ensure user items are visible
            });

            // Remove any validation errors from the cloned template
            newBatch.querySelectorAll('.validation-error').forEach(el => el.remove());

            container.appendChild(newBatch);
            updateBatchOrders();

            // Add event listener for the office filter dropdown in the new batch
            const officeFilter = newBatch.querySelector('.office-filter');
            if (officeFilter) {
                officeFilter.addEventListener('change', function() {
                    filterUsersByOffice(this);
                });
            }
        }

        function validateForm() {
            const batches = document.querySelectorAll('.batch-group');
            const useTemplateCheckbox = document.getElementById('useTemplate');
            const templateSelect = document.querySelector('select[name="template_id"]');
            let isValid = true;

            // Remove any existing error messages
            document.querySelectorAll('.validation-error').forEach(el => el.remove());

            // If using template mode, validate template selection
            if (useTemplateCheckbox && useTemplateCheckbox.checked) {
                if (!templateSelect || !templateSelect.value) {
                    isValid = false;
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error text-red-600 mt-2 mb-2';
                    errorMsg.textContent = 'Please select a workflow template.';
                    templateSelect.parentNode.appendChild(errorMsg);
                    return false;
                }
                
                // For template mode, still require at least one recipient to be selected
                let hasRecipient = false;
                batches.forEach(batch => {
                    const batchIdx = batch.dataset.index;
                    const recipientSelected = batch.querySelector(`input[name="recipient_batch[${batchIdx}]"]:checked`);
                    if (recipientSelected) {
                        hasRecipient = true;
                    }
                });
                
                if (!hasRecipient) {
                    isValid = false;
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error text-red-600 mt-2 mb-2';
                    errorMsg.textContent = 'Please select at least one recipient. Templates apply workflow steps to the recipients you choose.';
                    document.getElementById('batches-container').appendChild(errorMsg);
                    return false;
                }
                
                return true; // Template mode validation passed
            }

            // Standard validation for manual mode
            batches.forEach(batch => {
                const batchIdx = batch.dataset.index; // string value
                const batchNumForDisplay = parseInt(batchIdx) + 1;
                const recipientSelected = batch.querySelector(`input[name="recipient_batch[${batchIdx}]"]:checked`);

                // Each batch must have one recipient selected
                if (!recipientSelected) {
                    isValid = false;

                    // Create and display error message
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error text-red-600 mt-2 mb-2';
                    errorMsg.textContent = `Please select one recipient (office or user) in Step ${batchNumForDisplay}.`;

                    // Insert error before the end of this batch
                    batch.appendChild(errorMsg);
                }
            });

            return isValid;
        }

        // Filter users by selected office
        function filterUsersByOffice(selectElement) {
            const batch = selectElement.closest('.batch-group');
            const selectedOfficeId = selectElement.value;
            const userItems = batch.querySelectorAll('.user-item');

            userItems.forEach(userItem => {
                // Check if we should show all users or filter by office
                if (selectedOfficeId === 'all') {
                    userItem.style.display = 'flex';
                } else {
                    // Get the office IDs for this user
                    const officeIds = JSON.parse(userItem.dataset.officeIds);

                    // Show this user if they belong to the selected office
                    if (officeIds.includes(parseInt(selectedOfficeId))) {
                        userItem.style.display = 'flex';
                    } else {
                        userItem.style.display = 'none';

                        // If a hidden user was selected, uncheck them
                        const userRadio = userItem.querySelector('input[type="radio"]');
                        if (userRadio && userRadio.checked) {
                            userRadio.checked = false;
                        }
                    }
                }
            });
        }

        function prepareFormData() {
            // This function is called on form submission
            return validateForm();
        }

        // Add form validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Update batch order to initialize the remove button visibility
            updateBatchOrders();

            // Add form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                }
            });

            // Add event listeners for office filter dropdowns
            document.querySelectorAll('.office-filter').forEach(filter => {
                filter.addEventListener('change', function() {
                    filterUsersByOffice(this);
                });
            });

            // Add event listeners for office radio buttons to uncheck user radio buttons when an office is selected
            document.querySelectorAll('.office-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const batch = this.closest('.batch-group');
                        const userRadios = batch.querySelectorAll('.user-radio');
                        userRadios.forEach(userRadio => {
                            userRadio.checked = false;
                        });
                    }
                });
            });

            // Add event listeners for user radio buttons to uncheck office radio buttons when a user is selected
            document.querySelectorAll('.user-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const batch = this.closest('.batch-group');
                        const officeRadios = batch.querySelectorAll('.office-radio');
                        officeRadios.forEach(officeRadio => {
                            officeRadio.checked = false;
                        });
                    }
                });
            });
        });
    </script>
@endsection
