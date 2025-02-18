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
        Schema::table('document_transaction', function (Blueprint $table) {
            $table->unsignedBigInteger('to_user_id')->nullable()->after('to_office');
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_transaction', function (Blueprint $table) {
            $table->dropForeign(['to_user_id']);
            $table->dropColumn('to_user_id');
        });
    }
};
