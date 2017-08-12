<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model {

    protected $fillable = [
    	'user_id',
    	'lon',
    	'lat',
    	'isHired',
    	'device_ID'];


    protected $table = 'driver_last_locations';
    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
