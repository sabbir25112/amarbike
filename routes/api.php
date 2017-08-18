<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Http\JsonResponse;
$api = $app->make(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    //Add User as admin / driver / passenger
    $api->post('/user/create','App\Http\Controllers\UsersController@add');
    $api->post('/auth/login', [
        'as' => 'api.auth.login',
        'uses' => 'App\Http\Controllers\Auth\AuthController@postLogin',
        ]);
    $api->post('/bot/ride/create','App\Http\Controllers\UsersController@botRide');
    $api->group([
        'middleware' => 'api.auth',
        ], function ($api) {
            //basically this two api are same
            $api->post('/last_location','App\Http\Controllers\UsersController@lastLocation');
            $api->post('/driver_last_location','App\Http\Controllers\LocationController@driverLastLocation');
            //
            //Rides ==========
            $api->post('/ride/create','App\Http\Controllers\RideController@store');
            $api->post('/ride/{ride_id}/start','App\Http\Controllers\RideController@start');
            $api->post('/ride/{ride_id}/end','App\Http\Controllers\RideController@end');
            $api->post('/ride/{ride_id}/cancel','App\Http\Controllers\RideController@cancel');
            $api->post('/ride/{ride_id}/accept','App\Http\Controllers\RideController@accept');
            $api->post('/ride/{ride_id}/deny','App\Http\Controllers\RideController@deny');
            //===========
            // Notification ========
            $api->get('/ride/notification','App\Http\Controllers\NotificationController@unpublished');
            $api->post('/notification/publish','App\Http\Controllers\NotificationController@publish');
            
            // ============
            $api->get('/auth/user', [
                'uses' => 'App\Http\Controllers\Auth\AuthController@getUser',
                'as' => 'api.auth.user'
                ]);
            $api->patch('/auth/refresh', [
                'uses' => 'App\Http\Controllers\Auth\AuthController@patchRefresh',
                'as' => 'api.auth.refresh'
                ]);
            $api->delete('/auth/invalidate', [
                'uses' => 'App\Http\Controllers\Auth\AuthController@deleteInvalidate',
                'as' => 'api.auth.invalidate'
                ]);
        });
});
