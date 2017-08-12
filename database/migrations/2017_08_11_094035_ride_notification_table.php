<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RideNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_notifications', function(Blueprint $table) { 
            $table->increments('id');
            $table->integer('driver_id')->unsigned()->nullable();
            $table->foreign('driver_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->integer('passenger_id')->unsigned();
            $table->foreign('passenger_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->integer('ride_id')->unsigned();
            $table->foreign('ride_id')
                  ->references('id')->on('rides')
                  ->onDelete('cascade');
            $table->integer('published')->default(0);
            $table->integer('decision')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ride_notifications');
    }
}
