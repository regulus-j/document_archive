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
        Schema::table('company_accounts', function (Blueprint $table) {
            $table->foreignId('company_admin_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('company_accounts', function (Blueprint $table) {
            $table->dropForeign(['company_admin_id']);
            $table->dropColumn('company_admin_id');
        });
    }
    
};
