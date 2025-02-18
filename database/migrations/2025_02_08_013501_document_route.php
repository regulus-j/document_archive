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
        //
        Schema::create('document_workflows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id'); // The document being routed
            $table->unsignedBigInteger('sender_id');     // Who forwarded the document
            $table->unsignedBigInteger('recipient_id');  // Who is supposed to take action
            $table->unsignedInteger('step_order');       // The order or sequence of the workflow
            $table->enum('status', ['pending', 'received', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();         // Remarks if any during approval/rejection
            $table->timestamp('received_date')->nullable();
            $table->timestamps();


            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('document_attachments', function (Blueprint $table)
        {
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('document_workflows')->onDelete('cascade');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
