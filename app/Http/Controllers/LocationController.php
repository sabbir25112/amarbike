<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class LocationController extends Controller
{
    public function driverLastLocation(Request $request)
    {
    	\App\DriverLocation::create($request->all() + ['user_id' => $request->user()->id]);
    	return response()->json(['code' => 200,'message' => 'Location Inserted']);
    }
}
