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
        Schema::create('document_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->string('status')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
    
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('document_transaction', function($table)
        {
            $table->id();
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('from_office');
            $table->unsignedBigInteger('to_office');
            $table->timestamps();

            $table->foreign('doc_id')->references('id')->on('documents');
            $table->foreign('from_office')->references('id')->on('offices');
            $table->foreign('to_office')->references('id')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_audits');
    }
};
