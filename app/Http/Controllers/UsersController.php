<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UsersController extends Controller {

    const MODEL = "App\User";

    /**
	 * add user depend on type
	 * Type:
	 * 1 = Admin
	 * 2 = Driver
	 * 3 = Passenger
     */ 
    public function add(Request $request)
    {
    	$this->validate($request, [
            'name' => 'required|string|max:35',
            'email' => 'email',
            'number' => 'required|unique:users,number|regex:/^(?:\+?88)?01[15-9]\d{8}$/',
            'pass' => 'required|string' 
            ]);
        $request['password'] = app('hash')->make($request->pass);
        $user = \App\User::create($request->all());
        if($request->type == 2)
            \App\Driver::createDriver($request, $user);
        return response()->json(['code' => 200, 'message' => 'User Inserted ']);
    }

    public function lastLocation(Request $request)
    {
        //adding user_id in request
        $request['user_id'] = $request->user()->id;
        // check if the driver is not approved, 
        // then make every location request as not Hired 
        $isApproved = \App\Driver::isApproved($request->user_id);
        if(!$isApproved)
            $request['isHired'] = 3;
        \App\DriverLocation::create($request->all());
        return response()->json(['message' => 'Successful']);
    }

    public function botRide(Request $request)
    {
        $user = \App\User::where('number',$request->number)->first();
        //if user not registered 
        if(!$user)
        {
            $request['password'] = app('hash')->make('123456');
            $user = \App\User::create($request->all());
        }
        //Create Ride
        
        $ride = \App\Ride::create($request->all() + ['passenger_id' => $user->id]);

        $lat = $request->start_lat;
        $lon = $request->start_lon;

        $result = DB::table('drivers')
        ->select(DB::raw('*, ((ACOS(SIN('.$lat.' * PI() / 180) * SIN(lat * PI() / 180) + COS('.$lat.' * PI() / 180) * COS(lat * PI() / 180) * COS(('.$lon.' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance'))
        ->having('distance','<',1)
        ->orderBy('distance')
        ->where('isHired',1)
        ->limit(4)
        ->get();
        $user_id = $user->id;
        foreach ($result as $item)
        {
            \App\RideNotification::create([
                'driver_id' => $item->user_id,
                'passenger_id' => $user_id,
                'ride_id' => $ride->id
                ]);
        }
        return response()->json(['message' => 'Ride Created']);
    }

}
