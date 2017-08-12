<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RideNotification extends Model
{
    protected $table='ride_notifications';
    protected $fillable = [
        'driver_id',
		'passenger_id',
		'published',
		'decision',
		'ride_id'
    ];

    public static function notification()
    {
    	return RideNotification::with('rider','passenger','ride')
    							->where('published',0)
    							->get();
    }

    public function rider()
    {
    	return $this->belongsTo(User::class,'driver_id','id');
    }

    public function passenger()
    {
    	return $this->belongsTo(User::class,'passenger_id','id');
    }

    public function ride()
    {
    	return $this->belongsTo(Ride::class,'ride_id','id');
    }

    public static function isPublished($id)
    {
    	return (bool) RideNotification::find($id)->published;
    }

    public static function publish($id)
    {
    	if(!RideNotification::isPublished($id))
    		RideNotification::find($id)->update(['published' => 1]);
    }
}
