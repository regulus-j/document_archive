<!-- Template Details Modal -->
<div id="templateDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;" x-data="templateDetailsModal()">
    <div class="relative top-5 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-xl bg-white">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900" x-text="template.name || 'Template Details'"></h3>
                    <p class="text-sm text-gray-500">View template configuration and usage statistics</p>
                </div>
            </div>
            <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6" x-show="!loading">
            
            <!-- Tab Navigation -->
            <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg">
                <button @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                    Overview
                </button>
                <button @click="activeTab = 'steps'" 
                        :class="activeTab === 'steps' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                    Workflow Steps
                </button>
                <button @click="activeTab = 'usage'" 
                        :class="activeTab === 'usage' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                    Usage Statistics
                </button>
                <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors">
                    Usage History
                </button>
            </div>
            
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Basic Information -->
                    <div class="lg:col-span-2 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Template Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <p class="text-sm text-gray-900" x-text="template.name"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="getTypeBadgeClass(template.workflow_type)"
                                      x-text="template.workflow_type"></span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <p class="text-sm text-gray-900 capitalize" x-text="template.category || 'Not specified'"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                                <p class="text-sm text-gray-900" x-text="template.created_by"></p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-sm text-gray-900" x-text="template.description || 'No description provided'"></p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="flex items-center space-x-4">
                                    <span x-show="template.is_public" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                                        </svg>
                                        Public Template
                                    </span>
                                    <span x-show="template.require_all_steps" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        All Steps Required
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Total Uses</span>
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900" x-text="template.usage_count || 0"></p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Workflow Steps</span>
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900" x-text="template.steps_count || 0"></p>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Created</span>
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-900" x-text="formatDate(template.created_at)"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Workflow Steps Tab -->
            <div x-show="activeTab === 'steps'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900">Workflow Steps Configuration</h4>
                    <div class="text-sm text-gray-500">
                        <span x-text="template.steps ? template.steps.length : 0"></span> steps defined
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <template x-for="(step, index) in template.steps || []" :key="step.id">
                        <div class="border-b border-gray-200 last:border-b-0 p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Step Number -->
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    <span x-text="index + 1"></span>
                                </div>
                                
                                <!-- Step Details -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <h5 class="text-lg font-medium text-gray-900" x-text="step.name"></h5>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 capitalize"
                                              x-text="step.action_type"></span>
                                        <span x-show="step.is_required" 
                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Required
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-3" x-text="step.description || 'No description provided'"></p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div x-show="step.due_days">
                                            <span class="font-medium text-gray-700">Due in:</span>
                                            <span x-text="step.due_days + ' days'"></span>
                                        </div>
                                        <div x-show="step.reminder_days">
                                            <span class="font-medium text-gray-700">Reminder:</span>
                                            <span x-text="step.reminder_days + ' days before due'"></span>
                                        </div>
                                        <div class="space-x-3">
                                            <span x-show="step.allows_comments" class="text-green-600">✓ Comments</span>
                                            <span x-show="step.sends_notification" class="text-blue-600">✓ Notifications</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Step Type Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                         :class="getStepTypeIconClass(step.action_type)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  :d="getStepTypeIconPath(step.action_type)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="!template.steps || template.steps.length === 0" class="p-12 text-center">
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">No steps configured</h3>
                        <p class="text-sm text-gray-500">This template doesn't have any workflow steps defined.</p>
                    </div>
                </div>
            </div>
            
            <!-- Usage Statistics Tab -->
            <div x-show="activeTab === 'usage'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">Total Uses</p>
                                <p class="text-lg font-semibold text-blue-600" x-text="stats.total_usage || 0"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">Completed</p>
                                <p class="text-lg font-semibold text-green-600" x-text="stats.completed || 0"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">In Progress</p>
                                <p class="text-lg font-semibold text-yellow-600" x-text="stats.in_progress || 0"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700">Success Rate</p>
                                <p class="text-lg font-semibold text-purple-600" x-text="getSuccessRate() + '%'"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Usage Chart Placeholder -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Usage Over Time</h5>
                    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">Usage chart would be displayed here</p>
                    </div>
                </div>
            </div>
            
            <!-- Usage History Tab -->
            <div x-show="activeTab === 'history'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900">Recent Usage History</h4>
                    <div class="text-sm text-gray-500">
                        Last 30 days
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        <template x-for="usage in usageHistory" :key="usage.id">
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                 :class="getStatusBadgeClass(usage.status)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          :d="getStatusIconPath(usage.status)"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" x-text="usage.workflow_name"></p>
                                            <p class="text-xs text-gray-500">
                                                Created by <span x-text="usage.created_by"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900" x-text="formatDate(usage.created_at)"></p>
                                        <p class="text-xs text-gray-500 capitalize" x-text="usage.status.replace('_', ' ')"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="usageHistory.length === 0" class="p-8 text-center">
                            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">No usage history</h3>
                            <p class="text-sm text-gray-500">This template hasn't been used yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loading State -->
        <div x-show="loading" class="p-12 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-gray-500 mt-2">Loading template details...</p>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-6 border-t border-gray-200">
            <div class="flex space-x-3">
                <button x-show="template.can_edit" @click="editTemplate()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Template
                </button>
                <button @click="cloneTemplate()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Clone Template
                </button>
            </div>
            <button @click="closeModal()" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function templateDetailsModal() {
        return {
            template: {},
            stats: {},
            usageHistory: [],
            activeTab: 'overview',
            loading: true,
            
            async init() {
                // This will be called when modal is opened
            },
            
            async loadTemplate(templateId) {
                this.loading = true;
                
                try {
                    const response = await fetch(`/api/workflow-templates/${templateId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            this.template = data.data;
                            await this.loadStats(templateId);
                            await this.loadUsageHistory(templateId);
                        }
                    }
                } catch (error) {
                    console.error('Error loading template:', error);
                } finally {
                    this.loading = false;
                }
            },
            
            async loadStats(templateId) {
                try {
                    const response = await fetch(`/api/workflow-templates/${templateId}/stats`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            this.stats = data.data;
                        }
                    }
                } catch (error) {
                    console.error('Error loading stats:', error);
                }
            },
            
            async loadUsageHistory(templateId) {
                try {
                    const response = await fetch(`/api/workflow-templates/${templateId}/usage-history`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            this.usageHistory = data.data;
                        }
                    }
                } catch (error) {
                    console.error('Error loading usage history:', error);
                }
            },
            
            editTemplate() {
                this.closeModal();
                showEditTemplateModal(this.template);
            },
            
            async cloneTemplate() {
                const name = prompt(`Clone "${this.template.name}" as:`, `${this.template.name} (Copy)`);
                if (name && name.trim()) {
                    try {
                        const response = await fetch(`/api/workflow-templates/${this.template.id}/clone`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                name: name.trim()
                            })
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                showNotification('success', 'Template cloned successfully');
                                this.closeModal();
                            } else {
                                throw new Error(data.message);
                            }
                        }
                    } catch (error) {
                        showNotification('error', 'Failed to clone template: ' + error.message);
                    }
                }
            },
            
            closeModal() {
                document.getElementById('templateDetailsModal').style.display = 'none';
                this.activeTab = 'overview';
                this.template = {};
                this.stats = {};
                this.usageHistory = [];
            },
            
            // Utility functions
            getTypeBadgeClass(type) {
                const classes = {
                    'sequential': 'bg-blue-100 text-blue-800',
                    'parallel': 'bg-green-100 text-green-800',
                    'hybrid': 'bg-purple-100 text-purple-800'
                };
                return classes[type] || 'bg-gray-100 text-gray-800';
            },
            
            getStepTypeIconClass(type) {
                const classes = {
                    'review': 'bg-blue-100 text-blue-600',
                    'approve': 'bg-green-100 text-green-600',
                    'edit': 'bg-yellow-100 text-yellow-600',
                    'comment': 'bg-purple-100 text-purple-600',
                    'sign': 'bg-red-100 text-red-600',
                    'custom': 'bg-gray-100 text-gray-600'
                };
                return classes[type] || 'bg-gray-100 text-gray-600';
            },
            
            getStepTypeIconPath(type) {
                const paths = {
                    'review': 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                    'approve': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'edit': 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                    'comment': 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                    'sign': 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
                    'custom': 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                };
                return paths[type] || paths['custom'];
            },
            
            getStatusBadgeClass(status) {
                const classes = {
                    'completed': 'bg-green-100 text-green-600',
                    'in_progress': 'bg-yellow-100 text-yellow-600',
                    'pending': 'bg-gray-100 text-gray-600',
                    'cancelled': 'bg-red-100 text-red-600'
                };
                return classes[status] || 'bg-gray-100 text-gray-600';
            },
            
            getStatusIconPath(status) {
                const paths = {
                    'completed': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'in_progress': 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'pending': 'M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z',
                    'cancelled': 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
                };
                return paths[status] || paths['pending'];
            },
            
            getSuccessRate() {
                if (!this.stats.total_usage || this.stats.total_usage === 0) return 0;
                return Math.round((this.stats.completed / this.stats.total_usage) * 100);
            },
            
            formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
        }
    }

    // Global function for external access
    function showTemplateDetailsModal(templateId) {
        const modal = document.getElementById('templateDetailsModal');
        modal.style.display = 'flex';
        const component = modal.querySelector('[x-data]').__x.$data;
        component.loadTemplate(templateId);
    }
</script>
