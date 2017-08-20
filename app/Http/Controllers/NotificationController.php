<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unpublished()
    {
    	return \App\RideNotification::notification();
    }
    public function publish(Request $request)
    {
    	$ids = explode(',',$request->id);
    	foreach ($ids as $id)
    		\App\RideNotification::publish($id);
    	return response()->json(['message' => 'Notification Published']);
    }
    public function available(Request $request)
    {
        $user_id = $request->user()->id;
        $rides = \App\RideNotification::rider_notification($user_id);
        return response()->json(['code' => 200,'rides' => $rides]);
    }
}
