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

    $api->post('/user/post', [
        'as' => 'api.user.post',
        'uses' => 'App\Http\Controllers\APIController@Location',
        ]);
    //*****************
    $api->post('/passenger/post', [
        'as' => 'api.passenger.post',
        'uses' => 'App\Http\Controllers\APIController@Passenger',
        ]);
    /*
    PARAMETERS
    'Name',
    'Number',
    'device_ID',
    'Home',
    'Office',
    $api->post('/driver/fare', [
        'as' => 'api.fare',
        'uses' => 'App\Http\Controllers\APIController@fare',
    ]);
    */
    //*****************
    $api->post('/driver/update/{id}', [
        'as' => 'api.update',
        'uses' => 'App\Http\Controllers\APIController@Update',
        ]);

    /*

    lastlon;
    lastlat;


    */
    //*****************
    $api->get('/driver/get', [
        'as' => 'api.update',
        'uses' => 'App\Http\Controllers\APIController@getUser',
        ]);
    $api->post('/driver/hired/{id}', [
        'as' => 'api.hired',
        'uses' => 'App\Http\Controllers\APIController@Hired',
        ]);

    //*****************
    $api->post('/driver/vacant/{id}', [
        'as' => 'api.vacant',
        'uses' => 'App\Http\Controllers\APIController@vacant',
        ]);
    /*

    device_ID

    fareDevice

    fareDriver

    */
    //**************
    $api->post('/driver/status/{id}', [
        'as' => 'api.status',
        'uses' => 'App\Http\Controllers\APIController@updateStatus',
        ]);
    $api->get('/driver/off/{id}', [
        'as' => 'api.off',
        'uses' => 'App\Http\Controllers\APIController@updateStatusInactive',
        ]);


    $api->get('/driver/nearby', [
        'as' => 'api.nearby',
        'uses' => 'App\Http\Controllers\APIController@nearbyRiders',
        ]);
   //*****************
    $api->post('/driver/sms/', [
        'as' => 'api.sms',
        'uses' => 'App\Http\Controllers\APIController@Ride',
        ]);

    $api->get('/ride/log/', [
        'as' => 'api.ride.log',
        'uses' => 'App\Http\Controllers\APIController@getLog',
        ]);
    /*
    PARAMETERS
    $pname
    $dnumber
    $pnumber
    $pick
    $drop


    */

    $api->group([
        'middleware' => 'api.auth',
        ], function ($api) {
            $api->get('/', [
                'uses' => 'App\Http\Controllers\APIController@getIndex',
                'as' => 'api.index'
                ]);
            //basically this two api are same
            $api->post('/last_location','App\Http\Controllers\UsersController@lastLocation');
            $api->post('/driver_last_location','App\Http\Controllers\LocationController@driverLastLocation');
            //

            $api->post('/ride/create','App\Http\Controllers\RideController@store');
            $api->post('/ride/{ride_id}/start','App\Http\Controllers\RideController@start');
            $api->post('/ride/{ride_id}/end','App\Http\Controllers\RideController@end');
            $api->post('/ride/{ride_id}/cancel','App\Http\Controllers\RideController@cancel');
            
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
