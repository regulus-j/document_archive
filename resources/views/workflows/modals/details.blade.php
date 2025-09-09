<!-- Workflow Details Modal -->
<div id="workflowDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden" style="display: none;">
    <div class="relative top-4 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-lg bg-white mb-8">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h3 class="text-lg font-semibold text-gray-900" id="workflowTitle">Workflow Details</h3>
                <p class="text-sm text-gray-600" id="workflowSubtitle">Document workflow progress and information</p>
            </div>
            <button onclick="closeWorkflowDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="pt-4" x-data="workflowDetails()">
            
            <div x-show="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Loading workflow details...</span>
            </div>
            
            <div x-show="!loading && workflow" class="space-y-6">
                
                <!-- Workflow Overview -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Document</h4>
                            <p class="text-base font-semibold text-gray-900" x-text="workflow?.document_title"></p>
                            <p class="text-xs text-gray-500 mt-1">ID: <span x-text="workflow?.document_id"></span></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Progress</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-semibold text-gray-900">
                                    <span x-text="workflow?.current_step"></span>/<span x-text="workflow?.total_steps"></span>
                                </span>
                                <span class="text-sm text-gray-500">steps</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-300"
                                     :style="`width: ${workflow?.progress_percentage || 0}%`"></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                  :class="getStatusBadgeClass(workflow?.status)"
                                  x-text="workflow?.status"></span>
                        </div>
                    </div>
                </div>

                <!-- Workflow Steps Timeline -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900">Workflow Steps</h4>
                        <p class="text-sm text-gray-600">Sequential processing steps and their current status</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="flow-root">
                            <template x-for="(step, index) in workflow?.steps" :key="step.step_order">
                                <div class="relative pb-8" :class="index === (workflow?.steps || []).length - 1 ? 'pb-0' : ''">
                                    
                                    <!-- Timeline Line -->
                                    <div x-show="index !== (workflow?.steps || []).length - 1" 
                                         class="absolute top-4 left-4 -ml-px h-full w-0.5"
                                         :class="getTimelineLineClass(step, workflow?.steps[index + 1])"></div>
                                    
                                    <div class="relative flex space-x-3">
                                        <!-- Step Icon -->
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white"
                                                  :class="getStepIconClass(step)">
                                                <template x-if="step.status === 'completed' || step.status === 'approved' || step.status === 'forwarded'">
                                                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </template>
                                                <template x-if="step.is_current && step.status === 'pending'">
                                                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </template>
                                                <template x-if="step.status === 'rejected' || step.status === 'returned'">
                                                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </template>
                                                <template x-if="!step.is_current && step.status === 'pending'">
                                                    <span class="text-sm font-medium text-gray-500" x-text="step.step_order"></span>
                                                </template>
                                            </span>
                                        </div>
                                        
                                        <!-- Step Content -->
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <p class="text-sm font-medium text-gray-900" x-text="`Step ${step.step_order}: ${step.recipient}`"></p>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                                              :class="getStepStatusBadgeClass(step)"
                                                              x-text="step.status"></span>
                                                        <span x-show="step.is_current" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Current
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="text-sm text-gray-600 space-y-1">
                                                        <p x-show="step.office"><strong>Office:</strong> <span x-text="step.office"></span></p>
                                                        <p><strong>Purpose:</strong> <span x-text="step.purpose"></span></p>
                                                        <p><strong>Urgency:</strong> 
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium"
                                                                  :class="getUrgencyBadgeClass(step.urgency)"
                                                                  x-text="step.urgency"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Step Actions -->
                                                <div class="flex flex-col items-end space-y-2">
                                                    <div class="text-right">
                                                        <p class="text-xs text-gray-500">Due Date</p>
                                                        <p class="text-sm font-medium" :class="isOverdue(step.due_date) ? 'text-red-600' : 'text-gray-900'"
                                                           x-text="formatDate(step.due_date)"></p>
                                                    </div>
                                                    
                                                    <div x-show="step.completed_at" class="text-right">
                                                        <p class="text-xs text-gray-500">Completed</p>
                                                        <p class="text-sm font-medium text-green-600" x-text="formatDate(step.completed_at)"></p>
                                                    </div>
                                                    
                                                    <div x-show="step.is_current && canProcessStep(step)" class="space-x-2">
                                                        <button @click="processStep(step, 'approved')"
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Approve
                                                        </button>
                                                        <button @click="showProcessModal(step)"
                                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                                            More Actions
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Workflow Timeline/Activity -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden" x-show="workflow?.timeline && workflow.timeline.length > 0">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900">Activity Timeline</h4>
                        <p class="text-sm text-gray-600">Chronological workflow events and actions</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4 max-h-64 overflow-y-auto">
                            <template x-for="event in workflow?.timeline" :key="event.timestamp + event.event">
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900" x-text="event.event"></p>
                                        <p class="text-sm text-gray-600">by <span x-text="event.user"></span></p>
                                        <p class="text-xs text-gray-500" x-text="formatDateTime(event.timestamp)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="!loading && !workflow" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-sm font-medium text-gray-900 mb-1">No workflow data</h3>
                <p class="text-sm text-gray-500">Unable to load workflow information.</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
            <div class="flex space-x-2" x-data="{ workflowActions: false }">
                <div class="relative">
                    <button @click="workflowActions = !workflowActions" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                        Workflow Actions
                    </button>
                    <div x-show="workflowActions" @click.away="workflowActions = false" x-cloak
                         class="absolute left-0 mt-1 w-56 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                        <div class="py-1">
                            <button @click="exportWorkflow(); workflowActions = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export to PDF
                            </button>
                            <button @click="createTemplate(); workflowActions = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Create Template
                            </button>
                            <button @click="refreshWorkflow(); workflowActions = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button onclick="closeWorkflowDetailsModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function workflowDetails() {
    return {
        loading: false,
        workflow: null,
        workflowId: null,
        
        init() {
            // This will be called when the modal is opened with a workflow ID
        },
        
        async loadWorkflow(chainId) {
            this.loading = true;
            this.workflowId = chainId;
            
            try {
                const response = await fetch(`/api/workflows/chain/${chainId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        this.workflow = data.data;
                        
                        // Update modal title
                        document.getElementById('workflowTitle').textContent = `Workflow: ${this.workflow.document_title}`;
                        document.getElementById('workflowSubtitle').textContent = `${this.workflow.current_step} of ${this.workflow.total_steps} steps completed`;
                    }
                } else {
                    throw new Error('Failed to load workflow');
                }
            } catch (error) {
                console.error('Error loading workflow:', error);
                showNotification('error', 'Failed to load workflow details');
            } finally {
                this.loading = false;
            }
        },
        
        async processStep(step, action) {
            if (!confirm(`Are you sure you want to ${action} this step?`)) {
                return;
            }
            
            try {
                const response = await fetch(`/api/workflows/step/${step.workflow_id}/process`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        action: action,
                        remarks: ''
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        showNotification('success', `Step ${action} successfully`);
                        await this.loadWorkflow(this.workflowId);
                    }
                }
            } catch (error) {
                console.error('Error processing step:', error);
                showNotification('error', 'Failed to process step');
            }
        },
        
        canProcessStep(step) {
            // Check if current user can process this step
            // This would depend on your authentication and authorization logic
            return step.is_current && step.status === 'pending';
        },
        
        showProcessModal(step) {
            // Open a detailed processing modal
            // For now, just show a simple prompt
            const action = prompt('Enter action (approved, rejected, forwarded):', 'approved');
            if (action) {
                this.processStep(step, action);
            }
        },
        
        async refreshWorkflow() {
            if (this.workflowId) {
                await this.loadWorkflow(this.workflowId);
                showNotification('success', 'Workflow data refreshed');
            }
        },
        
        exportWorkflow() {
            if (this.workflowId) {
                window.open(`/workflows/chain/${this.workflowId}/export`, '_blank');
            }
        },
        
        createTemplate() {
            if (this.workflowId) {
                const templateName = prompt('Enter template name:');
                if (templateName && templateName.trim()) {
                    // Create template from this workflow
                    fetch(`/api/workflow-templates/from-workflow/${this.workflowId}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: templateName.trim(),
                            description: `Template created from ${this.workflow?.document_title}`
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', 'Template created successfully');
                        } else {
                            throw new Error(data.message);
                        }
                    }).catch(error => {
                        showNotification('error', 'Failed to create template: ' + error.message);
                    });
                }
            }
        },
        
        getStatusBadgeClass(status) {
            const classes = {
                'active': 'bg-blue-100 text-blue-800',
                'completed': 'bg-green-100 text-green-800',
                'paused': 'bg-yellow-100 text-yellow-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },
        
        getStepIconClass(step) {
            if (step.status === 'completed' || step.status === 'approved' || step.status === 'forwarded') {
                return 'bg-green-500';
            } else if (step.is_current && step.status === 'pending') {
                return 'bg-blue-500';
            } else if (step.status === 'rejected' || step.status === 'returned') {
                return 'bg-red-500';
            } else {
                return 'bg-gray-400';
            }
        },
        
        getStepStatusBadgeClass(step) {
            const classes = {
                'pending': step.is_current ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800',
                'approved': 'bg-green-100 text-green-800',
                'forwarded': 'bg-green-100 text-green-800',
                'completed': 'bg-green-100 text-green-800',
                'rejected': 'bg-red-100 text-red-800',
                'returned': 'bg-yellow-100 text-yellow-800'
            };
            return classes[step.status] || 'bg-gray-100 text-gray-800';
        },
        
        getUrgencyBadgeClass(urgency) {
            const classes = {
                'critical': 'bg-red-100 text-red-800',
                'high': 'bg-orange-100 text-orange-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'low': 'bg-green-100 text-green-800'
            };
            return classes[urgency] || 'bg-gray-100 text-gray-800';
        },
        
        getTimelineLineClass(currentStep, nextStep) {
            if (!nextStep) return 'bg-gray-300';
            
            if (currentStep.status === 'completed' || currentStep.status === 'approved' || currentStep.status === 'forwarded') {
                return 'bg-green-400';
            } else if (currentStep.is_current) {
                return 'bg-blue-400';
            } else {
                return 'bg-gray-300';
            }
        },
        
        isOverdue(dueDateString) {
            if (!dueDateString) return false;
            const dueDate = new Date(dueDateString);
            const today = new Date();
            return dueDate < today;
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        },
        
        formatDateTime(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleString();
        }
    }
}

function closeWorkflowDetailsModal() {
    document.getElementById('workflowDetailsModal').style.display = 'none';
}

// Global function to open workflow details modal
function showWorkflowDetails(chainId) {
    const modal = document.getElementById('workflowDetailsModal');
    modal.style.display = 'flex';
    
    // Initialize Alpine.js component and load workflow
    if (window.Alpine && window.Alpine.$data('workflowDetails')) {
        window.Alpine.$data('workflowDetails').loadWorkflow(chainId);
    }
}
</script>
