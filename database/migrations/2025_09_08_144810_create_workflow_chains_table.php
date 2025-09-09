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
        Schema::create('workflow_chains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('created_by');
            $table->enum('workflow_type', ['sequential', 'parallel', 'hybrid'])->default('sequential');
            $table->integer('current_step')->default(1);
            $table->integer('total_steps');
            $table->enum('status', ['active', 'completed', 'cancelled', 'paused'])->default('active');
            $table->text('description')->nullable();
            $table->json('step_config')->nullable(); // Configuration for each step
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['document_id']);
            $table->index(['created_by']);
            $table->index(['status']);
            $table->index(['workflow_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_chains');
    }
};
