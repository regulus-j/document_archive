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
            $table->dateTime('uploaded');
            $table->string('content');
            $table->string('path');
            $table->unsignedBigInteger('master')->nullable();
            $table->timestamps();

            $table->foreign('uploader')->references('id')->on('users');
            $table->foreign('master')->references('id')->on('documents');
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
