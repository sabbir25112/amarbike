<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverLastLocationsTable extends Migration
{

    public function up()
    {
        Schema::create('driver_last_locations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->string('lon');
            $table->string('lat');
            $table->string('device_ID');
            $table->integer('isHired');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('driver_last_locations');
    }
}
