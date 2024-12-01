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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_office_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_office_id')->references('id')->on('offices');
        });

        //alter docs table
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('originating_office')
                  ->references('id')
                  ->on('offices')
                  ->onDelete('cascade');
        });

        Schema::create('office_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['originating_office']);
        });
    }
};
