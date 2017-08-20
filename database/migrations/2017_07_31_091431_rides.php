<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rides', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('driver_id')->unsigned()->nullable();
            $table->foreign('driver_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->integer('passenger_id')->unsigned();
            $table->foreign('passenger_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->string('pick_up');
            $table->string('drop_off');
            $table->datetime('pickup_time');
            $table->decimal('time')->default(0);
            $table->decimal('app_fare')->default(0);
            $table->integer('status')->default(0);
            $table->string('start_lon');
            $table->string('start_lat');
            $table->string('end_lon');
            $table->string('end_lat');
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
        Schema::drop('rides');
    }
}
