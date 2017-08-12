<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use \App\User;

class Driver extends Model
{
    protected $fillable = [
    	'user_id',
    	'bike_number',
    	'bike_paper_pic',
    	'isApproved'
    ];

    public static function createDriver(Request $request, User $user)
    {
    	// dd($user->id);
    	$driver = Driver::create($request->all());
    	$driver->user_id = $user->id;
    	if($request->has('bike_number'))
    		$driver->bike_number = $request->bike_number;
    	//here bike picture adding logic
    	$driver->save();
    }

    public static function isApproved($id)
    {
        $driver = Driver::where('user_id',$id)->select('isApproved')->first();
        return (bool) $driver->isApproved;
    }
}