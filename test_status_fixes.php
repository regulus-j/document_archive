<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\DocumentStatus;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Status Fix Implementation...\n\n";

// Test 1: Check rejected document statuses
echo "=== Test 1: Checking rejected document statuses ===\n";
$rejectedWorkflows = DocumentWorkflow::where('status', 'rejected')->get();

foreach ($rejectedWorkflows as $workflow) {
    $document = $workflow->document;
    $documentStatus = $document->status ? $document->status->status : 'null';
    echo "Document ID: {$document->id}, Title: {$document->title}\n";
    echo "  - Workflow Status: {$workflow->status}\n";
    echo "  - Document Status: {$documentStatus}\n";
    echo "  - Status Match: " . ($documentStatus === 'rejected' ? 'YES ✓' : 'NO ✗') . "\n\n";
}

// Test 2: Simulate document update after rejection
echo "=== Test 2: Simulating document update logic ===\n";
if ($rejectedWorkflows->count() > 0) {
    $testWorkflow = $rejectedWorkflows->first();
    $testDocument = $testWorkflow->document;
    
    echo "Before update:\n";
    echo "  - Workflow Status: {$testWorkflow->status}\n";
    echo "  - Document Status: {$testDocument->status->status}\n";
    
    // Simulate the update logic (without actually updating)
    echo "\nAfter simulated update (document edited):\n";
    echo "  - Expected Workflow Status: forwarded\n";
    echo "  - Expected Document Status: forwarded\n";
} else {
    echo "No rejected workflows found to test with.\n";
}

echo "\n=== Test Results Summary ===\n";
echo "1. Rejected workflows should have document status 'rejected': ";
echo ($rejectedWorkflows->where('document.status.status', 'rejected')->count() === $rejectedWorkflows->count() ? 'PASS ✓' : 'FAIL ✗') . "\n";

echo "2. Status synchronization is working: ";
echo ($rejectedWorkflows->count() === 0 || $rejectedWorkflows->every(function($w) { 
    return $w->document->status->status === 'rejected'; 
}) ? 'PASS ✓' : 'FAIL ✗') . "\n";

echo "\nTest completed!\n";
