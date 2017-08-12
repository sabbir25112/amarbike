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
        $password = app('hash')->make($request->pass);
    	$user = \App\User::create($request->all()+['password' => $password]);
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

}
