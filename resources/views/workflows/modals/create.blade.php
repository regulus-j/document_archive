<!-- Create Workflow Modal -->
<div id="createWorkflowModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden" style="display: none;">
    <div class="relative top-4 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white mb-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Create Sequential Workflow</h3>
            <button onclick="closeCreateWorkflowModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="pt-4" x-data="createWorkflowForm()">
            
            <!-- Step 1: Document Selection -->
            <div x-show="currentStep === 1" class="space-y-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Step 1: Select Document</h4>
                    <p class="text-sm text-gray-600">Choose the document for which you want to create a sequential workflow.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Document Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Documents</label>
                        <input type="text" x-model="documentSearch" @input="searchDocuments()" 
                               placeholder="Search by title, tracking number..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Document Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                        <select x-model="documentTypeFilter" @change="searchDocuments()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="memo">Memo</option>
                            <option value="letter">Letter</option>
                            <option value="report">Report</option>
                            <option value="contract">Contract</option>
                        </select>
                    </div>
                </div>
                
                <!-- Documents List -->
                <div class="border border-gray-200 rounded-lg max-h-80 overflow-y-auto">
                    <template x-for="document in availableDocuments" :key="document.id">
                        <div class="p-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 cursor-pointer"
                             @click="selectDocument(document)"
                             :class="selectedDocument?.id === document.id ? 'bg-blue-50 border-l-4 border-l-blue-500' : ''">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900" x-text="document.title"></h4>
                                    <p class="text-xs text-gray-500 mt-1" x-text="'Tracking: ' + document.tracking_number"></p>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded" x-text="document.document_type"></span>
                                        <span class="text-xs text-gray-500" x-text="'Created: ' + formatDate(document.created_at)"></span>
                                    </div>
                                </div>
                                <div x-show="selectedDocument?.id === document.id" class="text-blue-500">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="availableDocuments.length === 0" class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">No documents found</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Workflow Configuration -->
            <div x-show="currentStep === 2" class="space-y-6">
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Step 2: Configure Workflow</h4>
                    <p class="text-sm text-gray-600">Set up the workflow details and recipient order.</p>
                </div>
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Workflow Description</label>
                        <input type="text" x-model="workflowDescription" 
                               placeholder="Brief description of this workflow..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urgency Level</label>
                        <select x-model="urgencyLevel"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="low">Low Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="high">High Priority</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                
                <!-- Template Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-sm font-medium text-gray-900">Use Template (Optional)</h5>
                        <button @click="refreshTemplates()" class="text-sm text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-32 overflow-y-auto">
                        <template x-for="template in availableTemplates" :key="template.id">
                            <div class="p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                 @click="selectTemplate(template)"
                                 :class="selectedTemplate?.id === template.id ? 'bg-blue-50 border-blue-300' : ''">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h6 class="text-sm font-medium text-gray-900" x-text="template.name"></h6>
                                        <p class="text-xs text-gray-500" x-text="template.steps_count + ' steps'"></p>
                                    </div>
                                    <div x-show="selectedTemplate?.id === template.id" class="text-blue-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Recipients Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-sm font-medium text-gray-900">Sequential Recipients</h5>
                        <button @click="addRecipient()" 
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Recipient
                        </button>
                    </div>
                    
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        <template x-for="(recipient, index) in recipients" :key="index">
                            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-l-blue-400">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs font-medium rounded-full"
                                              x-text="index + 1"></span>
                                        <span class="text-sm font-medium text-gray-900">Step <span x-text="index + 1"></span></span>
                                    </div>
                                    <button @click="removeRecipient(index)" class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Recipient *</label>
                                        <select x-model="recipient.user_id" @change="updateRecipient(index)"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select user...</option>
                                            <template x-for="user in availableUsers" :key="user.id">
                                                <option :value="user.id" x-text="user.name + ' (' + user.office + ')'"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Step Name</label>
                                        <input type="text" x-model="recipient.step_name" 
                                               :placeholder="'Step ' + (index + 1)"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Required Action</label>
                                        <select x-model="recipient.required_action"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="approve">Approve</option>
                                            <option value="review">Review</option>
                                            <option value="sign">Sign</option>
                                            <option value="verify">Verify</option>
                                            <option value="forward">Forward</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Due Days</label>
                                        <input type="number" x-model="recipient.due_days" min="1" max="30" 
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Purpose</label>
                                        <input type="text" x-model="recipient.purpose" 
                                               placeholder="Review and process..."
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Instructions (Optional)</label>
                                    <textarea x-model="recipient.instructions" rows="2" 
                                              placeholder="Special instructions for this step..."
                                              class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="recipients.length === 0" class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                            <p class="text-sm">No recipients added yet. Click "Add Recipient" to get started.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="currentStep === 1 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'">
                        1. Document
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="currentStep === 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'">
                        2. Workflow
                    </span>
                </div>
                
                <div class="flex space-x-3">
                    <button x-show="currentStep > 1" @click="currentStep--" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Previous
                    </button>
                    
                    <button x-show="currentStep < 2" @click="nextStep()" :disabled="!selectedDocument"
                            class="px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :class="selectedDocument ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'">
                        Next
                    </button>
                    
                    <button x-show="currentStep === 2" @click="createWorkflow()" :disabled="!canCreateWorkflow()"
                            class="px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            :class="canCreateWorkflow() ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed'">
                        <span x-show="!creating">Create Workflow</span>
                        <span x-show="creating" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createWorkflowForm() {
    return {
        currentStep: 1,
        creating: false,
        
        // Document selection
        documentSearch: '',
        documentTypeFilter: '',
        availableDocuments: [],
        selectedDocument: null,
        
        // Template selection
        availableTemplates: [],
        selectedTemplate: null,
        
        // Workflow configuration
        workflowDescription: '',
        urgencyLevel: 'medium',
        recipients: [],
        availableUsers: [],
        
        async init() {
            await this.loadDocuments();
            await this.loadTemplates();
            await this.loadUsers();
        },
        
        async loadDocuments() {
            try {
                // This would be your actual API endpoint for documents
                const response = await fetch('/api/documents', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.availableDocuments = data.data || [];
                }
            } catch (error) {
                console.error('Error loading documents:', error);
            }
        },
        
        async loadTemplates() {
            try {
                const response = await fetch('/api/workflow-templates', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.availableTemplates = data.data || [];
                }
            } catch (error) {
                console.error('Error loading templates:', error);
            }
        },
        
        async loadUsers() {
            try {
                // This would be your actual API endpoint for users
                const response = await fetch('/api/users', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.availableUsers = data.data || [];
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        },
        
        searchDocuments() {
            // Implement document search logic
            // For now, just filter the loaded documents
        },
        
        selectDocument(document) {
            this.selectedDocument = document;
            this.workflowDescription = `Sequential workflow for ${document.title}`;
        },
        
        selectTemplate(template) {
            this.selectedTemplate = template;
            // You would load the template steps here and populate recipients
            // For now, we'll just set the template
        },
        
        nextStep() {
            if (this.currentStep < 2 && this.selectedDocument) {
                this.currentStep++;
            }
        },
        
        addRecipient() {
            this.recipients.push({
                user_id: '',
                step_name: `Step ${this.recipients.length + 1}`,
                required_action: 'approve',
                due_days: 5,
                purpose: 'Review and process',
                instructions: ''
            });
        },
        
        removeRecipient(index) {
            this.recipients.splice(index, 1);
            // Update step names
            this.recipients.forEach((recipient, i) => {
                if (!recipient.step_name || recipient.step_name.startsWith('Step ')) {
                    recipient.step_name = `Step ${i + 1}`;
                }
            });
        },
        
        updateRecipient(index) {
            // Handle recipient updates
            this.$nextTick();
        },
        
        canCreateWorkflow() {
            return this.selectedDocument && 
                   this.recipients.length > 0 && 
                   this.recipients.every(r => r.user_id) &&
                   !this.creating;
        },
        
        async createWorkflow() {
            if (!this.canCreateWorkflow()) return;
            
            this.creating = true;
            
            try {
                const workflowData = {
                    document_id: this.selectedDocument.id,
                    description: this.workflowDescription,
                    urgency: this.urgencyLevel,
                    recipients: this.recipients
                };
                
                const endpoint = this.selectedTemplate 
                    ? '/api/workflows/from-template' 
                    : '/api/workflows';
                
                if (this.selectedTemplate) {
                    workflowData.template_id = this.selectedTemplate.id;
                }
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(workflowData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showNotification('success', 'Sequential workflow created successfully!');
                    closeCreateWorkflowModal();
                    // Refresh the main page
                    if (window.Alpine && window.Alpine.$data('workflowDashboard')) {
                        window.Alpine.$data('workflowDashboard').loadDashboardData();
                    }
                } else {
                    throw new Error(data.message || 'Failed to create workflow');
                }
                
            } catch (error) {
                console.error('Error creating workflow:', error);
                showNotification('error', 'Failed to create workflow: ' + error.message);
            } finally {
                this.creating = false;
            }
        },
        
        refreshTemplates() {
            this.loadTemplates();
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        }
    }
}

function closeCreateWorkflowModal() {
    document.getElementById('createWorkflowModal').style.display = 'none';
    // Reset the form
    if (window.Alpine && window.Alpine.$data('createWorkflowForm')) {
        const form = window.Alpine.$data('createWorkflowForm');
        form.currentStep = 1;
        form.selectedDocument = null;
        form.selectedTemplate = null;
        form.recipients = [];
        form.workflowDescription = '';
        form.urgencyLevel = 'medium';
    }
}
</script>
