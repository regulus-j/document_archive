<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
    
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->text('remarks')->nullable(); // Add back if you roll back
        });
    }
    
};
