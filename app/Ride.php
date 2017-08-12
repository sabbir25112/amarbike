<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{

	/**
	 * Status
	 * 0 = Pending
	 * 1 = Running
	 * 2 = Completed
	 * 3 = Canceled
	*/
	protected $fillable = [
		'driver_id',
		'passenger_id',
		'start_time',
		'end_time',
		'time',
		'app_fare',
		'status',
		'pick_up',
		'drop_off',
		'start_lon',
		'start_lat',
		'end_lon',
		'end_lat'
	];

	
}
