<?php

require 'vendor/autoload.php';

use App\Models\Document;

// Test the trackingNumber relationship fix
echo "Testing Document trackingNumber relationship fix...\n\n";

try {
    // Test with a document that has ID 23 (from the error message)
    $document = Document::with('trackingNumber')->find(23);
    
    if ($document) {
        echo "✅ Document found: ID {$document->id}, Title: '{$document->title}'\n";
        
        if ($document->trackingNumber) {
            echo "✅ Tracking number loaded: {$document->trackingNumber->tracking_number}\n";
        } else {
            echo "ℹ️  No tracking number found for this document\n";
        }
        
        echo "✅ Relationship fix successful!\n";
    } else {
        echo "❌ Document with ID 23 not found\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "The relationship still needs fixing.\n";
}

echo "\n=== Test Complete ===\n";
