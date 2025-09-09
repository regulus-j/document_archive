<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SequentialWorkflowController;
use App\Http\Controllers\WorkflowTemplateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

// Sequential Workflow API Routes
Route::middleware('auth')->group(function () {
    
    // Workflow Management
    Route::prefix('workflows')->group(function () {
        // Create new sequential workflow
        Route::post('/', [SequentialWorkflowController::class, 'create']);
        
        // Create workflow from template
        Route::post('/from-template', [SequentialWorkflowController::class, 'createFromTemplate']);
        
        // Process workflow step (approve, reject, etc.)
        Route::patch('/step/{workflow}/process', [SequentialWorkflowController::class, 'processStep']);
        
        // Get workflow chain details
        Route::get('/chain/{workflowChain}', [SequentialWorkflowController::class, 'getChain']);
        
        // Get document workflow progress
        Route::get('/document/{document}/progress', [SequentialWorkflowController::class, 'getProgress']);
        
        // Pause/Resume workflow
        Route::patch('/chain/{workflowChain}/toggle-pause', [SequentialWorkflowController::class, 'togglePause']);
        
        // Cancel workflow
        Route::patch('/chain/{workflowChain}/cancel', [SequentialWorkflowController::class, 'cancel']);
        
        // User dashboard
        Route::get('/dashboard', [SequentialWorkflowController::class, 'getDashboard']);
        
        // Analytics
        Route::get('/analytics', [SequentialWorkflowController::class, 'getAnalytics']);
    });
    
    // Workflow Templates API Routes
    Route::prefix('workflow-templates')->group(function () {
        // List available templates
        Route::get('/', [WorkflowTemplateController::class, 'index']);
        
        // Create new template
        Route::post('/', [WorkflowTemplateController::class, 'store']);
        
        // Get template details
        Route::get('/{template}', [WorkflowTemplateController::class, 'show']);
        
        // Update template
        Route::put('/{template}', [WorkflowTemplateController::class, 'update']);
        
        // Delete template
        Route::delete('/{template}', [WorkflowTemplateController::class, 'destroy']);
        
        // Clone template
        Route::post('/{template}/clone', [WorkflowTemplateController::class, 'clone']);
        
        // Create template from workflow
        Route::post('/from-workflow/{workflowChain}', [WorkflowTemplateController::class, 'createFromWorkflow']);
        
        // Get template statistics
        Route::get('/{template}/stats', [WorkflowTemplateController::class, 'stats']);
    });
});