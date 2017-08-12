<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    //
    protected $table='passengers';
    protected $fillable = [

        'Name',
        'Number',
        'device_ID',
        'Home',
        'Office',


    ];


}
