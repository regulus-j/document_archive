@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
            
            <!-- Header Box -->
            <div class="max-w-7xl mx-auto bg-white rounded-xl mb-6 border border-blue-200/80 overflow-hidden">
                <div class="bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-md">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Workflow Templates</h1>
                            <p class="text-sm text-gray-500">Create and manage reusable workflow patterns</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('documents.workflows') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-md">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Workflows
                        </a>
                        <button onclick="showCreateTemplateModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Template
                        </button>
                    </div>
                </div>
            </div>

            <!-- Templates Dashboard -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="templateManager()" x-init="window.templateManagerInstance = $data">
                
                <!-- Filters & Search Panel -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Search -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200/80 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Search Templates</h3>
                        <div class="space-y-4">
                            <div>
                                <input type="text" x-model="searchQuery" @input="searchTemplates()" 
                                       placeholder="Search templates..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Template Type</label>
                                <select x-model="typeFilter" @change="searchTemplates()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Types</option>
                                    <option value="sequential">Sequential</option>
                                    <option value="parallel">Parallel</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="showMyTemplates" @change="searchTemplates()"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">My templates only</span>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="showPublicTemplates" @change="searchTemplates()"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Public templates</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200/80 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Template Stats</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Total Templates</span>
                                <span class="text-lg font-bold text-indigo-600" x-text="stats.total_count || 0"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">My Templates</span>
                                <span class="text-lg font-bold text-green-600" x-text="stats.my_count || 0"></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Most Used</span>
                                <span class="text-sm font-medium text-blue-600" x-text="stats.most_used || 'N/A'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Templates Grid -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-xl shadow-md border border-gray-200/80 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800 mb-1">Available Templates</h2>
                                    <p class="text-sm text-gray-600">Reusable workflow patterns for your documents</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">
                                        <span x-text="filteredTemplates.length"></span> of <span x-text="allTemplates.length"></span> templates
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Debug: All=<span x-text="allTemplates.length"></span>, Filtered=<span x-text="filteredTemplates.length"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Templates List -->
                        <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                            <template x-for="template in filteredTemplates" :key="template.id">
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <h3 class="text-base font-medium text-gray-900" x-text="template.name"></h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      :class="getTypeBadgeClass(template.workflow_type)"
                                                      x-text="template.workflow_type"></span>
                                                <span x-show="template.is_public" 
                                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Public
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 mb-3" x-text="template.description || 'No description available'"></p>
                                            
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    <span x-text="template.steps_count"></span> steps
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                    </svg>
                                                    Used <span x-text="template.usage_count"></span> times
                                                </span>
                                                <span x-text="'Created by ' + template.created_by"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2 ml-4">
                                            <button @click="viewTemplate(template)"
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </button>
                                            
                                            <button @click="cloneTemplate(template)"
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Clone
                                            </button>
                                            
                                            <button @click="testTemplate(template)"
                                                    class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                Test
                                            </button>
                                            
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-cloak
                                                     class="absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                    <div class="py-1">
                                                        <button x-show="template.can_edit" @click="editTemplate(template); open = false"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            Edit Template
                                                        </button>
                                                        <button @click="getTemplateStats(template); open = false"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            View Statistics
                                                        </button>
                                                        <button @click="exportTemplate(template); open = false"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            Export Template
                                                        </button>
                                                        <button x-show="template.can_edit" @click="deleteTemplate(template); open = false"
                                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                            Delete Template
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="filteredTemplates.length === 0" class="p-12 text-center">
                                <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 mb-1">No templates found</h3>
                                <p class="text-sm text-gray-500 mb-4">Create your first workflow template to get started.</p>
                                <button @click="showCreateTemplateModal()" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">
                                    Create Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('workflows.modals.create-template')
    @include('workflows.modals.view-template')

    <!-- JavaScript -->
    <script>
        function templateManager() {
            return {
                allTemplates: [],
                filteredTemplates: [],
                searchQuery: '',
                typeFilter: '',
                showMyTemplates: true,
                showPublicTemplates: true,
                stats: {},
                loading: true,
                
                async init() {
                    await this.loadTemplates();
                    this.updateStats();
                },
                
                async loadTemplates() {
                    try {
                        console.log('Loading templates...');
                        const response = await fetch('/api/workflow-templates', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            console.log('API Response:', data);
                            if (data.success) {
                                this.allTemplates = data.data;
                                console.log('All templates loaded:', this.allTemplates);
                                console.log('Templates count:', this.allTemplates.length);
                                console.log('First template:', this.allTemplates[0]);
                                this.searchTemplates();
                                this.updateStats();
                            }
                        } else {
                            console.error('API Error:', response.status, response.statusText);
                        }
                    } catch (error) {
                        console.error('Error loading templates:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                searchTemplates() {
                    console.log('Searching templates with filters:', {
                        searchQuery: this.searchQuery,
                        typeFilter: this.typeFilter,
                        showMyTemplates: this.showMyTemplates,
                        showPublicTemplates: this.showPublicTemplates,
                        allTemplatesCount: this.allTemplates.length
                    });
                    
                    let filtered = this.allTemplates;
                    
                    // Search by name/description
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(t => 
                            t.name.toLowerCase().includes(query) ||
                            (t.description && t.description.toLowerCase().includes(query))
                        );
                    }
                    
                    // Filter by type
                    if (this.typeFilter) {
                        filtered = filtered.filter(t => t.workflow_type === this.typeFilter);
                    }
                    
                    // Filter by ownership/visibility
                    if (this.showMyTemplates && this.showPublicTemplates) {
                        // Show all templates (no filtering)
                    } else if (this.showMyTemplates && !this.showPublicTemplates) {
                        // Show only my templates
                        filtered = filtered.filter(t => t.can_edit);
                    } else if (!this.showMyTemplates && this.showPublicTemplates) {
                        // Show only public templates
                        filtered = filtered.filter(t => t.is_public);
                    } else {
                        // Both unchecked - show nothing
                        filtered = [];
                    }
                    
                    this.filteredTemplates = filtered;
                    console.log('Filtered templates:', this.filteredTemplates);
                    console.log('Filtered templates count:', this.filteredTemplates.length);
                    if (this.filteredTemplates.length > 0) {
                        console.log('First filtered template:', this.filteredTemplates[0]);
                    }
                },
                
                updateStats() {
                    this.stats = {
                        total_count: this.allTemplates.length,
                        my_count: this.allTemplates.filter(t => t.can_edit).length,
                        most_used: this.allTemplates.length > 0 ? 
                            this.allTemplates.reduce((a, b) => a.usage_count > b.usage_count ? a : b).name :
                            'N/A'
                    };
                },
                
                viewTemplate(template) {
                    showTemplateDetailsModal(template.id);
                },
                
                async cloneTemplate(template) {
                    const name = prompt(`Clone "${template.name}" as:`, `${template.name} (Copy)`);
                    if (name && name.trim()) {
                        try {
                            const response = await fetch(`/api/workflow-templates/${template.id}/clone`, {
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
                                    await this.loadTemplates();
                                } else {
                                    throw new Error(data.message);
                                }
                            }
                        } catch (error) {
                            showNotification('error', 'Failed to clone template: ' + error.message);
                        }
                    }
                },
                
                editTemplate(template) {
                    showEditTemplateModal(template);
                },
                
                async deleteTemplate(template) {
                    if (confirm(`Are you sure you want to delete the template "${template.name}"? This action cannot be undone.`)) {
                        try {
                            const response = await fetch(`/api/workflow-templates/${template.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    showNotification('success', 'Template deleted successfully');
                                    await this.loadTemplates();
                                } else {
                                    throw new Error(data.message);
                                }
                            }
                        } catch (error) {
                            showNotification('error', 'Failed to delete template: ' + error.message);
                        }
                    }
                },
                
                async getTemplateStats(template) {
                    try {
                        const response = await fetch(`/api/workflow-templates/${template.id}/stats`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                showTemplateStatsModal(data.data);
                            }
                        }
                    } catch (error) {
                        showNotification('error', 'Failed to load template statistics');
                    }
                },
                
                exportTemplate(template) {
                    window.open(`/workflow-templates/${template.id}/export`, '_blank');
                },
                
                async testTemplate(template) {
                    // First, let's get available documents to test with
                    try {
                        const response = await fetch('/api/documents?limit=10', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            const documents = data.data || data;
                            
                            if (documents && documents.length > 0) {
                                this.showTestTemplateModal(template, documents);
                            } else {
                                showNotification('info', 'No documents available for testing. Please upload some documents first.');
                            }
                        }
                    } catch (error) {
                        console.error('Error loading documents:', error);
                        showNotification('error', 'Failed to load documents for testing');
                    }
                },
                
                showTestTemplateModal(template, documents) {
                    // Create a simple modal to select document and test the template
                    const modalHtml = `
                        <div id="testTemplateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Test Template: ${template.name}</h3>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Document to Test:</label>
                                        <select id="testDocumentSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            ${documents.map(doc => `<option value="${doc.id}">${doc.filename || doc.document_name || 'Document ID: ' + doc.id}</option>`).join('')}
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional):</label>
                                        <textarea id="testDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add a custom description for this workflow..."></textarea>
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Urgency:</label>
                                        <select id="testUrgency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                    
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                                        <h4 class="font-medium text-blue-900 mb-2">Template Steps Preview:</h4>
                                        <div class="text-sm text-blue-800">
                                            ${template.steps_count} step(s) | ${template.workflow_type} workflow
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-3">
                                        <button onclick="window.templateManagerInstance.closeTestModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Cancel
                                        </button>
                                        <button onclick="window.templateManagerInstance.executeTemplateTest(${template.id})" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                            Apply Template to Document
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                },
                
                closeTestModal() {
                    const modal = document.getElementById('testTemplateModal');
                    if (modal) {
                        modal.remove();
                    }
                },
                
                async executeTemplateTest(templateId) {
                    const documentId = document.getElementById('testDocumentSelect').value;
                    const description = document.getElementById('testDescription').value;
                    const urgency = document.getElementById('testUrgency').value;
                    
                    try {
                        const response = await fetch('/api/sequential-workflows/from-template', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                template_id: templateId,
                                document_id: documentId,
                                description: description,
                                urgency: urgency
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            showNotification('success', `Template applied successfully! Workflow created with ${data.data.total_steps} step(s).`);
                            this.closeTestModal();
                        } else {
                            throw new Error(data.message || 'Failed to apply template');
                        }
                    } catch (error) {
                        console.error('Error applying template:', error);
                        showNotification('error', 'Failed to apply template: ' + error.message);
                    }
                },
                
                getTypeBadgeClass(type) {
                    const classes = {
                        'sequential': 'bg-blue-100 text-blue-800',
                        'parallel': 'bg-green-100 text-green-800',
                        'hybrid': 'bg-purple-100 text-purple-800'
                    };
                    return classes[type] || 'bg-gray-100 text-gray-800';
                }
            }
        }

        // Global functions
        function showCreateTemplateModal() {
            document.getElementById('createTemplateModal').style.display = 'flex';
        }

        function showTemplateDetailsModal(templateId) {
            // Load and show template details
            document.getElementById('templateDetailsModal').style.display = 'flex';
        }

        function showNotification(type, message) {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }
    </script>
@endsection
