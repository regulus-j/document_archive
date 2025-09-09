<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowChain;
use App\Services\WorkflowTemplateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class WorkflowTemplateController extends Controller
{
    protected $templateService;

    public function __construct(WorkflowTemplateService $templateService)
    {
        $this->templateService = $templateService;
        $this->middleware('auth');
    }

    /**
     * Get available workflow templates for the user
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'workflow_type' => 'nullable|in:sequential,parallel,hybrid',
            'created_by_me' => 'nullable|boolean',
            'search' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $filters = $request->only(['workflow_type', 'created_by_me', 'search']);
            
            \Log::info('Getting templates for user', [
                'user_id' => $user->id,
                'filters' => $filters
            ]);
            
            $templates = $this->templateService->getAvailableTemplates($user, $filters);
            
            \Log::info('Templates retrieved', [
                'count' => $templates->count(),
                'template_ids' => $templates->pluck('id')->toArray()
            ]);

            $templatesData = $templates->map(function($template) {
                try {
                    \Log::info('Processing template', ['id' => $template->id, 'name' => $template->name]);
                    
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'description' => $template->description,
                        'workflow_type' => $template->workflow_type,
                        'is_public' => $template->is_public,
                        'usage_count' => $template->usage_count,
                        'created_by' => $template->creator ? $template->creator->name : 'Unknown',
                        'created_at' => $template->created_at,
                        'steps_count' => count(json_decode($template->steps_config, true)),
                        'can_edit' => $this->canUserEditTemplate($template, Auth::user()),
                    ];
                } catch (Exception $e) {
                    \Log::error('Error processing template', [
                        'template_id' => $template->id,
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            });

            return response()->json([
                'success' => true,
                'data' => $templatesData,
                'total' => $templatesData->count()
            ]);

        } catch (Exception $e) {
            \Log::error('Failed to retrieve templates', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve templates',
                'error' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Create a new workflow template
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workflow_type' => 'required|in:sequential,parallel,hybrid',
            'steps' => 'required|array|min:1',
            'steps.*.name' => 'required|string',
            'steps.*.description' => 'nullable|string',
            'steps.*.action_type' => 'nullable|in:approve,review,sign,verify,forward',
            'steps.*.is_required' => 'nullable|boolean',
            'steps.*.allows_comments' => 'nullable|boolean',
            'steps.*.sends_notification' => 'nullable|boolean',
            'steps.*.due_days' => 'nullable|integer|min:1|max:30',
            'steps.*.reminder_days' => 'nullable|integer|min:1|max:30',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Log the exact request data for debugging
            \Log::info('Template creation request', [
                'user_id' => $user->id,
                'user_company_id' => $user->company_id ?? 'null',
                'request_data' => $request->all()
            ]);
            
            $templateData = [
                'name' => $request->name,
                'description' => $request->description,
                'workflow_type' => $request->workflow_type,
                'steps' => $request->steps,
                'is_active' => $request->is_active ?? true,
                'is_public' => $request->is_public ?? false,
            ];

            \Log::info('Processed template data', ['templateData' => $templateData]);

            $template = $this->templateService->createTemplate($templateData, $user);

            return response()->json([
                'success' => true,
                'message' => 'Workflow template created successfully',
                'data' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'workflow_type' => $template->workflow_type,
                    'steps_count' => count(json_decode($template->steps_config, true)),
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to create workflow template', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create template',
                'error' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'user_id' => Auth::id(),
                ]
            ], 500);
        }
    }

    /**
     * Get template details
     */
    public function show(WorkflowTemplate $template): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user can view this template
            if (!$this->canUserViewTemplate($template, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view this template'
                ], 403);
            }

            $templateData = [
                'id' => $template->id,
                'name' => $template->name,
                'description' => $template->description,
                'workflow_type' => $template->workflow_type,
                'steps_config' => json_decode($template->steps_config, true),
                'is_active' => $template->is_active,
                'is_public' => $template->is_public,
                'usage_count' => $template->usage_count,
                'created_by' => $template->creator->name,
                'company' => $template->company->name,
                'created_at' => $template->created_at,
                'updated_at' => $template->updated_at,
                'can_edit' => $this->canUserEditTemplate($template, $user),
                'can_delete' => $this->canUserEditTemplate($template, $user),
            ];

            return response()->json([
                'success' => true,
                'data' => $templateData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a workflow template
     */
    public function update(Request $request, WorkflowTemplate $template): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|array|min:1',
            'steps.*.step_name' => 'required_with:steps|string',
            'steps.*.role' => 'nullable|string',
            'steps.*.required_action' => 'nullable|in:approve,review,sign,verify,forward',
            'steps.*.instructions' => 'nullable|string',
            'steps.*.due_days' => 'nullable|integer|min:1|max:30',
            'steps.*.purpose' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            $updateData = $request->only(['name', 'description', 'steps', 'is_active', 'is_public']);
            $updatedTemplate = $this->templateService->updateTemplate($template, $updateData, $user);

            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully',
                'data' => [
                    'id' => $updatedTemplate->id,
                    'name' => $updatedTemplate->name,
                    'updated_at' => $updatedTemplate->updated_at,
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a workflow template
     */
    public function destroy(WorkflowTemplate $template): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $this->templateService->deleteTemplate($template, $user);

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clone a workflow template
     */
    public function clone(Request $request, WorkflowTemplate $template): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|array',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            // Check if user can view the original template
            if (!$this->canUserViewTemplate($template, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to clone this template'
                ], 403);
            }

            $modifications = $request->only(['name', 'description', 'steps']);
            $modifications['is_public'] = $request->is_public ?? false;

            $clonedTemplate = $this->templateService->cloneTemplate($template, $user, $modifications);

            return response()->json([
                'success' => true,
                'message' => 'Template cloned successfully',
                'data' => [
                    'id' => $clonedTemplate->id,
                    'name' => $clonedTemplate->name,
                    'original_template' => $template->name,
                ]
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clone template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create template from existing workflow
     */
    public function createFromWorkflow(Request $request, WorkflowChain $workflowChain): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            // Check if user can access the workflow
            if (!$this->canUserAccessWorkflow($workflowChain, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to create a template from this workflow'
                ], 403);
            }

            $templateData = [
                'name' => $request->name,
                'description' => $request->description,
                'is_public' => $request->is_public ?? false,
            ];

            $template = $this->templateService->createTemplateFromWorkflow($workflowChain, $user, $templateData);

            return response()->json([
                'success' => true,
                'message' => 'Template created from workflow successfully',
                'data' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'workflow_chain_id' => $workflowChain->id,
                    'steps_count' => count(json_decode($template->steps_config, true)),
                ]
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create template from workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get template usage statistics
     */
    public function stats(WorkflowTemplate $template): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user can view this template
            if (!$this->canUserViewTemplate($template, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view template statistics'
                ], 403);
            }

            $stats = $this->templateService->getTemplateStats($template);

            return response()->json([
                'success' => true,
                'data' => [
                    'template_id' => $template->id,
                    'template_name' => $template->name,
                    'statistics' => $stats
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve template statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Permission helper methods

    private function canUserViewTemplate($template, $user): bool
    {
        return $template->is_public ||
               $user->company_id === $template->company_id ||
               $user->id === $template->created_by ||
               $user->hasRole(['admin', 'super_admin']);
    }

    private function canUserEditTemplate($template, $user): bool
    {
        // User can edit if they created it
        if ($user->id === $template->created_by) {
            return true;
        }
        
        // Super admin can edit any template
        if ($user->hasRole(['super_admin'])) {
            return true;
        }
        
        // Company admin can edit templates from same company (if both have company_id)
        if ($user->hasRole(['admin']) && 
            $user->company_id && 
            $template->company_id && 
            $user->company_id === $template->company_id) {
            return true;
        }
        
        return false;
    }

    private function canUserAccessWorkflow($workflowChain, $user): bool
    {
        return $user->id === $workflowChain->created_by ||
               $workflowChain->documentWorkflows()->where('recipient_id', $user->id)->exists() ||
               $user->hasRole(['admin', 'super_admin']);
    }
}
