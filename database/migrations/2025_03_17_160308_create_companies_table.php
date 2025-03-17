<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('site_name')->nullable();
            $table->string('color_theme')->default('blue');
            $table->timestamps();
        });
    }

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Revert the companies table.
     *
     * @return void
     */
/******  3865d542-284d-44fe-bde7-3923670b7d80  *******/    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
