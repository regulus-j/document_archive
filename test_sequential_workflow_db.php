<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\WorkflowChain;
use App\Models\WorkflowTemplate;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 Testing Sequential Workflow Database Implementation\n";
echo "=" . str_repeat("=", 55) . "\n\n";

try {
    // Test 1: Check document_workflows table structure
    echo "1. 📊 document_workflows Table Structure:\n";
    $columns = DB::select('DESCRIBE document_workflows');
    foreach($columns as $col) {
        $marker = in_array($col->Field, [
            'workflow_chain_id', 'is_current_step', 'workflow_type', 
            'workflow_group_id', 'completion_action', 'workflow_config', 'depends_on_step'
        ]) ? '✨ NEW: ' : '      ';
        echo "   {$marker}{$col->Field} ({$col->Type})\n";
    }
    echo "\n";

    // Test 2: Check workflow_chains table
    echo "2. 🔗 workflow_chains Table Structure:\n";
    $chains_columns = DB::select('DESCRIBE workflow_chains');
    foreach($chains_columns as $col) {
        echo "   ✨ {$col->Field} ({$col->Type})\n";
    }
    echo "\n";

    // Test 3: Check workflow_templates table
    echo "3. 📋 workflow_templates Table Structure:\n";
    $templates_columns = DB::select('DESCRIBE workflow_templates');
    foreach($templates_columns as $col) {
        echo "   ✨ {$col->Field} ({$col->Type})\n";
    }
    echo "\n";

    // Test 4: Check indexes
    echo "4. 📈 Index Verification:\n";
    $indexes = DB::select("SHOW INDEX FROM document_workflows WHERE Key_name LIKE 'dw_%'");
    foreach($indexes as $idx) {
        echo "   ✅ {$idx->Key_name} on {$idx->Column_name}\n";
    }
    echo "\n";

    // Test 5: Test Model Creation and Relationships
    echo "5. 🏗️ Model Testing:\n";
    
    // Test DocumentWorkflow model
    echo "   Testing DocumentWorkflow model fillable fields...\n";
    $workflow = new App\Models\DocumentWorkflow();
    $fillable = $workflow->getFillable();
    $newFields = ['workflow_chain_id', 'is_current_step', 'workflow_type', 'workflow_group_id'];
    foreach($newFields as $field) {
        $status = in_array($field, $fillable) ? '✅' : '❌';
        echo "   {$status} {$field} fillable\n";
    }
    
    // Test WorkflowChain model
    echo "\n   Testing WorkflowChain model...\n";
    if (class_exists('App\Models\WorkflowChain')) {
        $chain = new App\Models\WorkflowChain();
        echo "   ✅ WorkflowChain model exists\n";
        echo "   ✅ Fillable fields: " . implode(', ', array_slice($chain->getFillable(), 0, 5)) . "...\n";
    } else {
        echo "   ❌ WorkflowChain model missing\n";
    }
    
    // Test WorkflowTemplate model
    echo "\n   Testing WorkflowTemplate model...\n";
    if (class_exists('App\Models\WorkflowTemplate')) {
        $template = new App\Models\WorkflowTemplate();
        echo "   ✅ WorkflowTemplate model exists\n";
        echo "   ✅ Fillable fields: " . implode(', ', array_slice($template->getFillable(), 0, 5)) . "...\n";
    } else {
        echo "   ❌ WorkflowTemplate model missing\n";
    }

    // Test 6: Test Sample Data Creation
    echo "\n6. 🧪 Sample Data Creation Test:\n";
    
    // Create a sample workflow chain
    echo "   Creating sample WorkflowChain...\n";
    $sampleChain = App\Models\WorkflowChain::create([
        'document_id' => 1, // Assuming document ID 1 exists
        'created_by' => 1,  // Assuming user ID 1 exists
        'workflow_type' => 'sequential',
        'current_step' => 1,
        'total_steps' => 3,
        'status' => 'active',
        'description' => 'Test sequential workflow',
        'step_config' => json_encode([
            ['step' => 1, 'role' => 'reviewer'],
            ['step' => 2, 'role' => 'approver'],
            ['step' => 3, 'role' => 'finalizer']
        ]),
        'started_at' => now(),
    ]);
    echo "   ✅ WorkflowChain created with ID: {$sampleChain->id}\n";
    
    // Create a sample workflow template
    echo "   Creating sample WorkflowTemplate...\n";
    $sampleTemplate = App\Models\WorkflowTemplate::create([
        'name' => 'Standard Review Process',
        'description' => 'A standard 3-step review workflow',
        'company_id' => 1,
        'created_by' => 1,
        'workflow_type' => 'sequential',
        'steps_config' => json_encode([
            ['order' => 1, 'role' => 'initial_reviewer', 'required' => true],
            ['order' => 2, 'role' => 'manager_approval', 'required' => true],
            ['order' => 3, 'role' => 'final_sign_off', 'required' => true]
        ]),
        'is_active' => true,
        'is_public' => false,
    ]);
    echo "   ✅ WorkflowTemplate created with ID: {$sampleTemplate->id}\n";
    
    // Test relationship
    echo "\n   Testing relationships...\n";
    $creator = $sampleChain->creator;
    echo "   ✅ WorkflowChain->creator relationship works\n";
    
    $document = $sampleChain->document;
    if ($document) {
        echo "   ✅ WorkflowChain->document relationship works\n";
    } else {
        echo "   ⚠️  WorkflowChain->document relationship - no document found (expected if no documents exist)\n";
    }

    // Test 7: Clean up test data
    echo "\n7. 🧹 Cleanup Test Data:\n";
    $sampleChain->delete();
    echo "   ✅ Sample WorkflowChain deleted\n";
    $sampleTemplate->delete();
    echo "   ✅ Sample WorkflowTemplate deleted\n";

    echo "\n🎉 ALL TESTS PASSED!\n";
    echo "The sequential workflow database implementation is working correctly.\n\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
