<!-- Create Template Modal -->
<div id="createTemplateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;" x-data="createTemplateModal()">
    <div class="relative top-5 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-xl bg-white">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">
                <span x-show="!editMode">Create New Template</span>
                <span x-show="editMode">Edit Template</span>
            </h3>
            <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <form @submit.prevent="saveTemplate()" class="space-y-6">
                
                <!-- Step 1: Basic Information -->
                <div x-show="currentStep === 1" class="space-y-6">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Template Information</h4>
                        <p class="text-sm text-gray-600">Configure basic template details and settings</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Template Name *</label>
                            <input type="text" x-model="templateData.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter template name">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea x-model="templateData.description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Describe when and how this template should be used"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Workflow Type *</label>
                            <select x-model="templateData.workflow_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select workflow type</option>
                                <option value="sequential">Sequential - One after another</option>
                                <option value="parallel">Parallel - All at once</option>
                                <option value="hybrid">Hybrid - Mix of both</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select x-model="templateData.category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select category</option>
                                <option value="approval">Approval Process</option>
                                <option value="review">Document Review</option>
                                <option value="collaborative">Collaborative Work</option>
                                <option value="compliance">Compliance Check</option>
                                <option value="quality">Quality Assurance</option>
                                <option value="custom">Custom Process</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="templateData.is_public"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Make public (others can use this template)</span>
                            </label>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="templateData.require_all_steps"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">All steps must be completed</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Workflow Steps -->
                <div x-show="currentStep === 2" class="space-y-6">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Configure Workflow Steps</h4>
                        <p class="text-sm text-gray-600">Define the steps and their order in this template</p>
                    </div>
                    
                    <!-- Steps List -->
                    <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-sm font-medium text-gray-700">Workflow Steps</h5>
                            <button type="button" @click="addStep()" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Step
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="(step, index) in templateData.steps" :key="step.temp_id">
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm font-medium text-blue-800">
                                        <span x-text="index + 1"></span>
                                    </div>
                                    
                                    <div class="flex-1 space-y-3">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <input type="text" x-model="step.name" 
                                                       placeholder="Step name" required
                                                       class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            </div>
                                            <div>
                                                <select x-model="step.action_type"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    <option value="review">Review</option>
                                                    <option value="approve">Approve</option>
                                                    <option value="edit">Edit</option>
                                                    <option value="comment">Comment</option>
                                                    <option value="sign">Sign</option>
                                                    <option value="custom">Custom</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <textarea x-model="step.description" rows="2" 
                                                      placeholder="Step description or instructions" 
                                                      class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                        </div>
                                        
                                        <div class="flex items-center space-x-4 text-xs">
                                            <label class="flex items-center">
                                                <input type="checkbox" x-model="step.is_required"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="ml-1 text-gray-600">Required</span>
                                            </label>
                                            
                                            <label class="flex items-center">
                                                <input type="checkbox" x-model="step.allows_comments"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="ml-1 text-gray-600">Allow comments</span>
                                            </label>
                                            
                                            <label class="flex items-center">
                                                <input type="checkbox" x-model="step.sends_notification"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="ml-1 text-gray-600">Send notification</span>
                                            </label>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <label class="block text-gray-600 mb-1">Due in (days)</label>
                                                <input type="number" x-model="step.due_days" min="1" 
                                                       class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                                       placeholder="Optional">
                                            </div>
                                            <div>
                                                <label class="block text-gray-600 mb-1">Reminder (days before due)</label>
                                                <input type="number" x-model="step.reminder_days" min="1" 
                                                       class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                                       placeholder="Optional">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-1">
                                        <button type="button" @click="moveStepUp(index)" :disabled="index === 0"
                                                class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="moveStepDown(index)" :disabled="index === templateData.steps.length - 1"
                                                class="p-1 text-gray-400 hover:text-gray-600 disabled:opacity-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <button type="button" @click="removeStep(index)"
                                                class="p-1 text-red-400 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="templateData.steps.length === 0" class="text-center py-8 text-gray-500">
                                <p>No steps added yet. Click "Add Step" to get started.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Review & Save -->
                <div x-show="currentStep === 3" class="space-y-6">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Review Template</h4>
                        <p class="text-sm text-gray-600">Review your template configuration before saving</p>
                    </div>
                    
                    <!-- Template Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Template Name</label>
                                <p class="text-sm text-gray-900" x-text="templateData.name"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Workflow Type</label>
                                <p class="text-sm text-gray-900 capitalize" x-text="templateData.workflow_type"></p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="text-sm text-gray-900" x-text="templateData.description || 'No description'"></p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Steps Summary</label>
                            <div class="space-y-2">
                                <template x-for="(step, index) in templateData.steps" :key="step.temp_id">
                                    <div class="flex items-center space-x-3 text-sm">
                                        <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-xs font-medium text-blue-800" x-text="index + 1"></span>
                                        <span x-text="step.name"></span>
                                        <span class="text-gray-500">•</span>
                                        <span class="text-gray-500 capitalize" x-text="step.action_type"></span>
                                        <span x-show="step.is_required" class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs">Required</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 text-sm">
                            <span x-show="templateData.is_public" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Public Template
                            </span>
                            <span x-show="templateData.require_all_steps" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                All Steps Required
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-2">
                        <!-- Step Indicators -->
                        <template x-for="step in [1, 2, 3]" :key="step">
                            <div class="w-3 h-3 rounded-full" 
                                 :class="currentStep >= step ? 'bg-blue-600' : 'bg-gray-300'"></div>
                        </template>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="button" @click="closeModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        
                        <button type="button" x-show="currentStep > 1" @click="currentStep--"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Previous
                        </button>
                        
                        <button type="button" x-show="currentStep < 3" @click="nextStep()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Next
                        </button>
                        
                        <button type="submit" x-show="currentStep === 3" :disabled="saving"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                            <span x-show="!saving">
                                <span x-show="!editMode">Create Template</span>
                                <span x-show="editMode">Update Template</span>
                            </span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function createTemplateModal() {
        return {
            currentStep: 1,
            editMode: false,
            saving: false,
            templateData: {
                id: null,
                name: '',
                description: '',
                workflow_type: '',
                category: '',
                is_public: false,
                require_all_steps: false,
                steps: []
            },
            
            nextStep() {
                if (this.validateCurrentStep()) {
                    this.currentStep++;
                }
            },
            
            validateCurrentStep() {
                if (this.currentStep === 1) {
                    if (!this.templateData.name || !this.templateData.workflow_type) {
                        showNotification('error', 'Please fill in required fields');
                        return false;
                    }
                } else if (this.currentStep === 2) {
                    if (this.templateData.steps.length === 0) {
                        showNotification('error', 'Please add at least one workflow step');
                        return false;
                    }
                    // Validate step data
                    for (let step of this.templateData.steps) {
                        if (!step.name || !step.action_type) {
                            showNotification('error', 'Please complete all step information');
                            return false;
                        }
                    }
                }
                return true;
            },
            
            addStep() {
                this.templateData.steps.push({
                    temp_id: Date.now() + Math.random(),
                    name: '',
                    description: '',
                    action_type: 'review',
                    is_required: true,
                    allows_comments: true,
                    sends_notification: true,
                    due_days: null,
                    reminder_days: null,
                    order_index: this.templateData.steps.length
                });
            },
            
            removeStep(index) {
                this.templateData.steps.splice(index, 1);
                // Update order indices
                this.templateData.steps.forEach((step, i) => {
                    step.order_index = i;
                });
            },
            
            moveStepUp(index) {
                if (index > 0) {
                    [this.templateData.steps[index], this.templateData.steps[index - 1]] = 
                    [this.templateData.steps[index - 1], this.templateData.steps[index]];
                    this.updateStepOrder();
                }
            },
            
            moveStepDown(index) {
                if (index < this.templateData.steps.length - 1) {
                    [this.templateData.steps[index], this.templateData.steps[index + 1]] = 
                    [this.templateData.steps[index + 1], this.templateData.steps[index]];
                    this.updateStepOrder();
                }
            },
            
            updateStepOrder() {
                this.templateData.steps.forEach((step, i) => {
                    step.order_index = i;
                });
            },
            
            async saveTemplate() {
                if (!this.validateCurrentStep()) return;
                
                this.saving = true;
                
                try {
                    const url = this.editMode ? 
                        `/api/workflow-templates/${this.templateData.id}` : 
                        '/api/workflow-templates';
                    
                    const method = this.editMode ? 'PUT' : 'POST';
                    
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.templateData)
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        showNotification('success', 
                            this.editMode ? 'Template updated successfully' : 'Template created successfully'
                        );
                        this.closeModal();
                        
                        // Reload templates list if the parent page has the function
                        if (typeof window.templateManagerInstance !== 'undefined' && window.templateManagerInstance.loadTemplates) {
                            await window.templateManagerInstance.loadTemplates();
                        } else {
                            // Fallback: reload the page
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'Failed to save template');
                    }
                } catch (error) {
                    console.error('Error saving template:', error);
                    showNotification('error', 'Failed to save template: ' + error.message);
                } finally {
                    this.saving = false;
                }
            },
            
            closeModal() {
                document.getElementById('createTemplateModal').style.display = 'none';
                this.resetForm();
            },
            
            resetForm() {
                this.currentStep = 1;
                this.editMode = false;
                this.saving = false;
                this.templateData = {
                    id: null,
                    name: '',
                    description: '',
                    workflow_type: '',
                    category: '',
                    is_public: false,
                    require_all_steps: false,
                    steps: []
                };
            },
            
            editTemplate(template) {
                this.editMode = true;
                this.templateData = {
                    id: template.id,
                    name: template.name,
                    description: template.description || '',
                    workflow_type: template.workflow_type,
                    category: template.category || '',
                    is_public: template.is_public,
                    require_all_steps: template.require_all_steps,
                    steps: template.steps ? template.steps.map(step => ({
                        ...step,
                        temp_id: Date.now() + Math.random()
                    })) : []
                };
                document.getElementById('createTemplateModal').style.display = 'flex';
            }
        }
    }

    // Global functions for external access
    function showEditTemplateModal(template) {
        const modal = document.querySelector('#createTemplateModal [x-data]').__x.$data;
        modal.editTemplate(template);
    }
</script>
