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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('uploader');
            $table->longText('description');
            $table->text('content');
            $table->string('path');
            $table->timestamps();

            $table->foreign('uploader')->references('id')->on('users');
        });

        Schema::create('document_versions', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('prevdoc_id')->nullable();
            $table->unsignedBigInteger('nextdoc_id')->nullable();

            $table->foreign('doc_id')->references('id')->on('documents');
            $table->foreign('prevdoc_id')->references('id')->on('documents');
            $table->foreign('nextdoc_id')->references('id')->on('documents');
        });

        Schema::create('folders', function (Blueprint $table)
        {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('folders');
        });

        Schema::create('document_folder', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('folder_id');

            $table->foreign('doc_id')->references('id')->on('documents');
            $table->foreign('folder_id')->references('id')->on('folders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
