<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        public function up()
        {
            Schema::table('company_accounts', function (Blueprint $table) {
                $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('cascade');
            });
        }
    
        public function down()
        {
            Schema::table('company_accounts', function (Blueprint $table) {
                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            });
        }
    };
    

