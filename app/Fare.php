<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fare extends Model
{
    //
    protected $table='fare';
    protected $fillable = [
        'device_ID',
        'fare_Device',
        'fare_Driver',
        'tripnumber',

    ];


}
