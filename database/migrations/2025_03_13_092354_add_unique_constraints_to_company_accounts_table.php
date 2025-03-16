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
            $table->unique('company_name');
            $table->unique('registered_name');
            $table->unique('company_email');
            $table->unique('company_phone');
        });
    }
    
    public function down()
    {
        Schema::table('company_accounts', function (Blueprint $table) {
            $table->dropUnique(['company_name']);
            $table->dropUnique(['registered_name']);
            $table->dropUnique(['company_email']);
            $table->dropUnique(['company_phone']);
        });
    }
    
};
