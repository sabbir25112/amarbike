<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RideController extends Controller
{
	public function store(Request $request)
	{
		if($request->user()->type == 3)
		{
			$ride = \App\Ride::create($request->all() + ['passenger_id' => $request->user()->id]);

			$lat = $request->start_lat;
			$lon = $request->start_lon;

			$result = DB::table('driver_last_locations')
			->select(DB::raw('*, ((ACOS(SIN('.$lat.' * PI() / 180) * SIN(lat * PI() / 180) + COS('.$lat.' * PI() / 180) * COS(lat * PI() / 180) * COS(('.$lon.' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance'))
			->having('distance','<',1)
			->orderBy('distance')
			->where('isHired',1)
			->limit(4)
			->get();
			$user_id = $request->user()->id;
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
		else
			return response()->json(['message' => 'Ride Creation not Allowed']);
	}

	public function start(Request $request)
	{
		if($request->user()->type == 2)
		{
			$ride_id = $request->route()[2]['ride_id'];
			$ride = \App\Ride::findOrFail($ride_id);
			if($ride->start_time == null)
			{
				$ride->update([
					'start_time' => \Carbon\Carbon::now(),
					'status' => 1,
					'driver_id' => $request->user()->id
					]);
				return response()->json(['message' => "Ride is on"]);
			}
			else
				return response()->json(['message' => "Ride already started"]);	
		}
		else
		{
			return response()->json(['message' => "You cant start a ride"]);
		}
	}

	public function end(Request $request)
	{
		$ride_id = $request->route()[2]['ride_id'];
		$ride = \App\Ride::findOrFail($ride_id);
		if($ride->start_time != null)
		{
			$ride->update($request->all() + [
				'end_time' => \Carbon\Carbon::now(),
				'status' => 2
				]);
			return response()->json(['message' => "Ride completed successfully"]);
		}
		else
			return response()->json(['message' => "Ride not started yet!"]);	
	}

	public function cancel(Request $request)
	{
		$ride_id = $request->route()[2]['ride_id'];
		$ride = \App\Ride::findOrFail($ride_id);
		$ride->update(['status' => 3]);
	}
}
