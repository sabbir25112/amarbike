<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PassengerLog extends Model
{
    //
    protected $table='passengerlog';
    protected $fillable = [

        'pname',
        'dnumber',
        'pnumber',
        'pick',
        'drop',


    ];


}
