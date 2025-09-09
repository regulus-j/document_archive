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
        Schema::table('workflow_templates', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['company_id']);
            
            // Recreate with onDelete('set null') to allow null company_id
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_templates', function (Blueprint $table) {
            // Drop the modified foreign key
            $table->dropForeign(['company_id']);
            
            // Recreate the original constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};
