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

    public static function rider_notification($id)
    {
        return RideNotification::with('rider','passenger','ride')
                                ->where('driver_id',$id)
                                ->where('decision',0)
                                ->where('published',0)
                                ->get();   
    }

    public static function stop_notification($ride_id,$driver_id)
    {
        $rides = RideNotification::where('ride_id',$ride_id)
                                ->get();
        foreach ($rides as $ride)
        {
            $ride->update(['published' => 1]);
        }
        if($driver_id != 0)
        {
            //set as accept
            RideNotification::where('ride_id',$ride_id)
                        ->where('driver_id',$driver_id)
                        ->first()
                        ->update(['decision' => 1]);    
        }
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
