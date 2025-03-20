<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecentActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('recent_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key to the users table
            $table->string('activity'); // Description of the activity
            $table->timestamps(); // Created at and updated at timestamps
    
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recent_activities');
    }
}
