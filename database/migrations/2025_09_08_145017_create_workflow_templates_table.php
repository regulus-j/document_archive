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
        Schema::create('workflow_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->enum('workflow_type', ['sequential', 'parallel', 'hybrid'])->default('sequential');
            $table->json('steps_config'); // Configuration for template steps
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false); // Can be used by other companies
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['company_id']);
            $table->index(['created_by']);
            $table->index(['is_active']);
            $table->index(['workflow_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_templates');
    }
};
