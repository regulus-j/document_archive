# Multiple Receivers in Sequential Workflow - Technical Concept

## 🎯 Problem Statement
How do we handle multiple receivers in a sequential workflow where:
1. **Identify** all receivers for one workflow chain
2. **Track** the current active receiver
3. **Manage** the sequence and order
4. **Handle** different scenarios (skip, parallel sub-steps, etc.)

## 🏗️ Proposed Database Schema Enhancement

### Option 1: Workflow Chain with Unique Identifier
```sql
-- Add to document_workflows table
ALTER TABLE document_workflows ADD COLUMN workflow_chain_id VARCHAR(36) NULL; -- UUID for grouping
ALTER TABLE document_workflows ADD COLUMN is_active BOOLEAN DEFAULT FALSE;
ALTER TABLE document_workflows ADD COLUMN workflow_type ENUM('sequential', 'parallel') DEFAULT 'parallel';
ALTER TABLE document_workflows ADD COLUMN completion_action ENUM('proceed', 'wait_all', 'branch') DEFAULT 'proceed';

-- New table for workflow chain metadata
CREATE TABLE workflow_chains (
    id VARCHAR(36) PRIMARY KEY,
    document_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    workflow_type ENUM('sequential', 'parallel', 'hybrid') DEFAULT 'sequential',
    current_step INT DEFAULT 1,
    total_steps INT,
    status ENUM('active', 'completed', 'cancelled', 'paused') DEFAULT 'active',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
```

### Option 2: Enhanced Current Structure
```sql
-- Add to existing document_workflows table
ALTER TABLE document_workflows ADD COLUMN workflow_group_id INT DEFAULT 1; -- Group parallel steps
ALTER TABLE document_workflows ADD COLUMN is_current_step BOOLEAN DEFAULT FALSE;
ALTER TABLE document_workflows ADD COLUMN depends_on_step INT NULL; -- Previous step dependency
ALTER TABLE document_workflows ADD COLUMN workflow_config JSON NULL; -- Flexible configuration
```

## 🔄 Workflow Identification Methods

### Method 1: Chain-Based Identification
```php
class WorkflowChain {
    public function getReceiversForDocument($documentId) {
        return DocumentWorkflow::where('document_id', $documentId)
            ->orderBy('step_order')
            ->with(['recipient', 'sender'])
            ->get()
            ->groupBy('workflow_chain_id');
    }
    
    public function getCurrentActiveReceiver($documentId) {
        return DocumentWorkflow::where('document_id', $documentId)
            ->where('is_active', true)
            ->with('recipient')
            ->first();
    }
    
    public function getNextReceiver($currentWorkflowId) {
        $current = DocumentWorkflow::findOrFail($currentWorkflowId);
        
        return DocumentWorkflow::where('document_id', $current->document_id)
            ->where('step_order', '>', $current->step_order)
            ->orderBy('step_order')
            ->first();
    }
}
```

### Method 2: Step-Order Based (Enhanced Current System)
```php
class SequentialWorkflowService {
    public function getAllReceiversInOrder($documentId) {
        return DocumentWorkflow::where('document_id', $documentId)
            ->orderBy('step_order')
            ->orderBy('workflow_group_id') // For parallel sub-steps
            ->get()
            ->map(function($workflow) {
                return [
                    'id' => $workflow->id,
                    'recipient' => $workflow->recipient,
                    'step_order' => $workflow->step_order,
                    'group_id' => $workflow->workflow_group_id,
                    'status' => $workflow->status,
                    'is_active' => $workflow->is_current_step,
                    'type' => $this->getStepType($workflow)
                ];
            });
    }
    
    private function getStepType($workflow) {
        $sameStepCount = DocumentWorkflow::where('document_id', $workflow->document_id)
            ->where('step_order', $workflow->step_order)
            ->count();
            
        return $sameStepCount > 1 ? 'parallel' : 'sequential';
    }
}
```

## 📊 Visual Workflow Representation

### Example: Document with 5 Receivers
```
Document: "Annual Report Review"

Sequential Chain:
Step 1: [John Doe] (Review) ✅ Completed
Step 2: [Jane Smith, Mike Wilson] (Parallel Approval) ⏳ Current
Step 3: [Sarah Chen] (Legal Review) ⏸️ Waiting
Step 4: [Bob Manager] (Final Approval) ⏸️ Waiting
Step 5: [Admin Archive] (Archive) ⏸️ Waiting

Current Status: Step 2 - Waiting for Jane Smith & Mike Wilson
Progress: 20% (1 of 5 steps completed)
```

### Database Records for Above Example:
```
document_workflows table:
+----+--------+----------+------+----------+-------------+------+--------+
| id | doc_id | recip_id | step | group_id | is_current  | stat | active |
+----+--------+----------+------+----------+-------------+------+--------+
| 1  | 123    | 101      | 1    | 1        | false       | comp | false  |
| 2  | 123    | 102      | 2    | 1        | true        | pend | true   |
| 3  | 123    | 103      | 2    | 1        | true        | pend | true   |
| 4  | 123    | 104      | 3    | 1        | false       | pend | false  |
| 5  | 123    | 105      | 4    | 1        | false       | pend | false  |
| 6  | 123    | 106      | 5    | 1        | false       | pend | false  |
+----+--------+----------+------+----------+-------------+------+--------+
```

## 🎮 User Experience Scenarios

### 1. Sequential Progress View (For Sender)
```
┌─────────────────────────────────────────────────┐
│ Workflow Progress: Annual Report Review         │
├─────────────────────────────────────────────────┤
│ ✅ Step 1: John Doe (Completed - 2 hours ago)  │
│ ⏳ Step 2: Jane Smith & Mike Wilson (Current)   │
│    │ Jane Smith - Pending (2 days remaining)    │
│    │ Mike Wilson - Pending (2 days remaining)   │
│ ⏸️ Step 3: Sarah Chen (Waiting)                │
│ ⏸️ Step 4: Bob Manager (Waiting)               │
│ ⏸️ Step 5: Admin Archive (Waiting)             │
├─────────────────────────────────────────────────┤
│ Progress: 20% | Estimated completion: 5 days   │
│ [View Details] [Modify Workflow] [Send Reminder]│
└─────────────────────────────────────────────────┘
```

### 2. Current Step View (For Active Receivers)
```
┌─────────────────────────────────────────────────┐
│ 🚨 ACTION REQUIRED - Your Turn                  │
├─────────────────────────────────────────────────┤
│ Document: Annual Report Review                   │
│ Your Role: Step 2 of 5 - Approval Required      │
│                                                  │
│ Progress Chain:                                  │
│ John Doe ✅ → [You & Mike Wilson] → Sarah Chen   │
│                                                  │
│ ⚠️ Waiting for: You and Mike Wilson             │
│ Once both approve, continues to Sarah Chen       │
│                                                  │
│ [Approve] [Reject] [Request Changes] [Forward]   │
└─────────────────────────────────────────────────┘
```

### 3. Waiting Step View (For Future Receivers)
```
┌─────────────────────────────────────────────────┐
│ 📋 In Queue - Your Turn Coming Up               │
├─────────────────────────────────────────────────┤
│ Document: Annual Report Review                   │
│ Your Position: Step 3 of 5 - Legal Review       │
│                                                  │
│ Current Status:                                  │
│ ✅ John Doe (Completed)                         │
│ ⏳ Jane Smith & Mike Wilson (Current - Active)  │
│ 👆 YOU ARE NEXT                                 │
│                                                  │
│ Estimated time until your turn: 2-3 days        │
│ [View Document Preview] [Set Reminder]          │
└─────────────────────────────────────────────────┘
```

## 🔧 Implementation Methods

### Method A: Query-Based Identification
```php
// Get all receivers for a document workflow
public function getWorkflowReceivers($documentId) {
    $workflows = DocumentWorkflow::where('document_id', $documentId)
        ->with(['recipient', 'sender'])
        ->orderBy('step_order')
        ->get();
    
    $receivers = [];
    $currentStep = null;
    
    foreach ($workflows as $workflow) {
        $step = $workflow->step_order;
        
        if (!isset($receivers[$step])) {
            $receivers[$step] = [
                'step_order' => $step,
                'type' => 'sequential',
                'recipients' => [],
                'status' => 'pending',
                'is_current' => false
            ];
        }
        
        $receivers[$step]['recipients'][] = [
            'id' => $workflow->recipient_id,
            'name' => $workflow->recipient->first_name . ' ' . $workflow->recipient->last_name,
            'status' => $workflow->status,
            'received_at' => $workflow->received_at,
            'workflow_id' => $workflow->id
        ];
        
        // Determine if this step is current
        if ($workflow->status === 'pending' && $workflow->is_current_step) {
            $receivers[$step]['is_current'] = true;
            $currentStep = $step;
        }
        
        // Check if it's parallel (multiple recipients same step)
        if (count($receivers[$step]['recipients']) > 1) {
            $receivers[$step]['type'] = 'parallel';
        }
    }
    
    return [
        'receivers' => $receivers,
        'current_step' => $currentStep,
        'total_steps' => count($receivers),
        'progress_percentage' => $this->calculateProgress($receivers)
    ];
}
```

### Method B: Service-Based Management
```php
class SequentialWorkflowManager {
    
    public function createSequentialWorkflow($documentId, $receiversConfig) {
        // $receiversConfig = [
        //     1 => [['user_id' => 101, 'role' => 'reviewer']],
        //     2 => [['user_id' => 102], ['user_id' => 103]], // Parallel step
        //     3 => [['user_id' => 104, 'role' => 'approver']]
        // ];
        
        $workflowChainId = Str::uuid();
        
        foreach ($receiversConfig as $stepOrder => $recipients) {
            foreach ($recipients as $recipient) {
                DocumentWorkflow::create([
                    'workflow_chain_id' => $workflowChainId,
                    'document_id' => $documentId,
                    'recipient_id' => $recipient['user_id'],
                    'step_order' => $stepOrder,
                    'is_current_step' => ($stepOrder === 1),
                    'status' => 'pending',
                    'workflow_type' => count($recipients) > 1 ? 'parallel' : 'sequential'
                ]);
            }
        }
        
        return $workflowChainId;
    }
    
    public function getCurrentActiveReceivers($documentId) {
        return DocumentWorkflow::where('document_id', $documentId)
            ->where('is_current_step', true)
            ->where('status', 'pending')
            ->with('recipient')
            ->get();
    }
    
    public function completeStep($workflowId, $action = 'approve') {
        $workflow = DocumentWorkflow::findOrFail($workflowId);
        
        // Mark current workflow as completed
        $workflow->status = $action;
        $workflow->is_current_step = false;
        $workflow->save();
        
        // Check if all parallel workflows in same step are completed
        $parallelWorkflows = DocumentWorkflow::where('document_id', $workflow->document_id)
            ->where('step_order', $workflow->step_order)
            ->where('status', 'pending')
            ->count();
            
        if ($parallelWorkflows === 0) {
            // All parallel workflows completed, activate next step
            $this->activateNextStep($workflow->document_id, $workflow->step_order);
        }
    }
    
    private function activateNextStep($documentId, $currentStep) {
        $nextStepWorkflows = DocumentWorkflow::where('document_id', $documentId)
            ->where('step_order', '>', $currentStep)
            ->orderBy('step_order')
            ->get();
            
        if ($nextStepWorkflows->isNotEmpty()) {
            $nextStep = $nextStepWorkflows->first()->step_order;
            
            // Activate all workflows in the next step
            DocumentWorkflow::where('document_id', $documentId)
                ->where('step_order', $nextStep)
                ->update(['is_current_step' => true]);
                
            // Send notifications to next step recipients
            $this->notifyNextStepRecipients($documentId, $nextStep);
        } else {
            // Workflow completed
            $this->completeWorkflow($documentId);
        }
    }
}
```

## 🎯 Benefits of These Approaches

### ✅ **Advantages:**
1. **Clear Identification**: Easy to see all receivers per workflow
2. **Step Tracking**: Know exactly who's active, who's waiting
3. **Progress Visibility**: Visual progress for senders and receivers
4. **Flexible Sequencing**: Handle both sequential and parallel steps
5. **Database Efficiency**: Leverages existing structure with minimal changes

### ⚠️ **Considerations:**
1. **Migration Complexity**: Need to update existing workflows
2. **UI Changes**: Views need to show workflow chains properly
3. **Performance**: More complex queries for large workflows
4. **Testing**: Need comprehensive testing for all scenarios

## 🚀 Recommended Implementation Strategy

### Phase 1: Enhance Current Structure
- Add `workflow_chain_id`, `is_current_step` columns
- Create service classes for workflow management
- Update controllers to use sequential logic

### Phase 2: Build UI Components
- Workflow progress visualizer
- Sequential step management interface  
- Enhanced notification system

### Phase 3: Migration & Testing
- Migrate existing parallel workflows
- Comprehensive testing of edge cases
- User training and documentation

This approach gives you maximum flexibility while building on your existing solid foundation!
