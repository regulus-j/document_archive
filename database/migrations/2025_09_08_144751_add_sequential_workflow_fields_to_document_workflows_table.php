<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_workflows', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('document_workflows', 'workflow_chain_id')) {
                $table->uuid('workflow_chain_id')->nullable()->after('tracking_number');
            }
            if (!Schema::hasColumn('document_workflows', 'is_current_step')) {
                $table->boolean('is_current_step')->default(false)->after('step_order');
            }
            if (!Schema::hasColumn('document_workflows', 'workflow_type')) {
                $table->enum('workflow_type', ['sequential', 'parallel'])->default('parallel')->after('is_current_step');
            }
            if (!Schema::hasColumn('document_workflows', 'workflow_group_id')) {
                $table->integer('workflow_group_id')->default(1)->after('workflow_type');
            }
            if (!Schema::hasColumn('document_workflows', 'completion_action')) {
                $table->enum('completion_action', ['proceed', 'wait_all', 'branch'])->default('proceed')->after('workflow_group_id');
            }
            if (!Schema::hasColumn('document_workflows', 'workflow_config')) {
                $table->json('workflow_config')->nullable()->after('completion_action');
            }
            if (!Schema::hasColumn('document_workflows', 'depends_on_step')) {
                $table->integer('depends_on_step')->nullable()->after('workflow_config');
            }
        });
        
        // Add indexes (only if they don't exist)
        try {
            Schema::table('document_workflows', function (Blueprint $table) {
                $table->index(['document_id', 'workflow_chain_id'], 'dw_doc_chain_idx');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }
        
        try {
            Schema::table('document_workflows', function (Blueprint $table) {
                $table->index(['document_id', 'is_current_step'], 'dw_doc_current_idx');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }
        
        try {
            Schema::table('document_workflows', function (Blueprint $table) {
                $table->index(['document_id', 'step_order', 'workflow_group_id'], 'dw_doc_step_group_idx');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_workflows', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('dw_doc_chain_idx');
            $table->dropIndex('dw_doc_current_idx');
            $table->dropIndex('dw_doc_step_group_idx');
            
            // Drop columns
            $table->dropColumn([
                'workflow_chain_id',
                'is_current_step',
                'workflow_type',
                'workflow_group_id',
                'completion_action',
                'workflow_config',
                'depends_on_step'
            ]);
        });
    }
};
