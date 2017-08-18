<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class LocationController extends Controller
{
	public function __construct()
	{
		$this->middleware('approved');
	}

    public function driverLastLocation(Request $request)
    {
    	$user_id = $request->user()->id;
    	\App\Driver::where('user_id',$user_id)->first()->update($request->all());
        if($request->isHired != 2)
        {
            $rides = \App\RideNotification::rider_notification($user_id);
            return response()->json(['code' => 200,'rides' => $rides]);
        }
        else
            return response()->json(['code' => 200,'message' => 'Location Inserted']);
            
    	
    }
}
