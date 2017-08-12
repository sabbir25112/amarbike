<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Application;
use Illuminate\Http\JsonResponse;
use App\Location;
use App\Fare;
use App\User;
use App\Passenger;
use Illuminate\Http\Request;

class APIController extends Controller
{
    /**
     * Get root url.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Application $app)
    {
      return new JsonResponse(['message' => $app->version()]);
    }
    public function Location(Request $request)
    {
      $lon = $request->lastlon;
      $lat = $request->lastlat;
      $user = new User;
      if ($request->has('name')) {
        $user->name = $request->name;
      }if ($request->has('number')) {
        $user->number = $request->number;
      }
      if ($request->has('device_ID')) {
        $user->device_ID = $request->device_ID;
      }
      if ($request->has('lastlon')) {
        $user->lastlon = $lon;
      }
      if ($request->has('lastlat')) {
        $user->lastlat = $lat;
      }
      $user->save();

      return response()->json('Ok');
    }

    public function Passenger(Request $request)
    {

      $user = new Passenger;
      if ($request->has('Name')) {
        $user->Name = $request->Name;
      }if ($request->has('Number')) {
        $user->Number = $request->Number;
      }
      if ($request->has('device_ID')) {
        $user->device_ID = $request->device_ID;
      }

      if ($request->has('Home')) {
        $user->Home= $request->Home;
      }
      if ($request->has('Office')) {
        $user->Office = $request->Office;
      }

      $user->save();

      return response()->json('Welcome');
    }

    public function getUser()
    {
      $user = User::all();
      return response()->json($user);
    }

    public function Update(Request $request, $id)
    {
      $update = User::where('device_ID','=',$id)->first();
      $update->lastlon = $request->lastlon;
      $update->lastlat = $request->lastlat;
      $update->save();

      $location = new Location;
      $location->device_ID = $id;
      $location->lon = $lon;
      $location->lat = $lat;
      $location->save();
      return response()->json('Ok');
    }
    public function updateStatus(Request $request, $id)
    {
      $update = User::where('device_ID','=',$id)->first();
      $update->lastlon = $request->lastlon;
      $update->lastlat = $request->lastlat;
      $update->save();
      DB::table('users')
      ->where('device_ID', $id)
      ->update(['status' => 1]);

      return response()->json('Actived');
    }

    public function updateStatusInactive($id)
    {

      DB::table('users')
      ->where('device_ID', $id)
      ->update(['status' => 0]);

      return response()->json('Inactived');
    }

    public function Hired($id)
    {
      DB::table('users')
      ->where('device_ID', $id)
      ->update(['fareStatus' => 1]);
    }
    public function vacant(Request $request)
    {
      $fare = new Fare;
      if ($request->has('device_ID')) {
        $fare->device_ID = $request->device_ID;
      }
      if ($request->has('fareDevice')) {
        $fare->fareDevice = $request->fareDevice;
      }
      if ($request->has('fareDriver')) {
        $fare->fareDriver = $request->fareDriver;
      }
      $fare->save();

      DB::table('users')
      ->where('device_ID', $request->device_ID)
      ->update(['fareStatus' => 0]);


      return response()->json('Ok');
    }
    public function nearbyRiders(Request $request)
    {
      $lat = $request->latitude;
      $lon = $request->longitude;

      $result = DB::table('users')
      ->select(DB::raw('*, ((ACOS(SIN('.$lat.' * PI() / 180) * SIN(lastlat * PI() / 180) + COS('.$lat.' * PI() / 180) * COS(lastlat * PI() / 180) * COS(('.$lon.' - lastlon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance'))
      ->having('distance','<',1)
      ->orderBy('distance')
      ->where('status',1)
      ->where('fareStatus',0)
      ->limit(5)
      ->get();

      return $result->toJson();

    }
    public function Ride(Request $request)
    {
      $pname = $request->pname;
      $dname = $request->dname;
      $dnumber = $request->dnumber;
      $pnumber = $request->pnumber;
      $pick = $request->pick;
      $drop = $request->drop;

      $log = new PassengerLog;
      $log->pname = $pname;
      $log->dname = $dname;
      $log->dnumber = $dnumber;
      $log->pnumber = $pnumber;
      $log->pick = $pick;
      $log->drop = $drop;

      $log->save();

      $soapClient = new \SoapClient("http://api2.onnorokomsms.com/sendsms.asmx?wsdl");
      // $config = config('onnorokom');
      $onnorokomArray = [
        'request' => [
          'userName'      =>"01708549077",
          'userPassword'  => "r58num1sarker",
          'mobileNumber'  => $dnumber,
          'smsText'       => " ".$pname." ,: ".$pnumber." ,".$pick." ,".$drop." ",
          'type'          => "TEXT",
          'maskName'      => "",
          'campaignName'  => ""
        ]
      ];
      try{
        $value = $soapClient->__call("OnetoOne", $onnorokomArray);
        $arrResult = explode("||", $value->OneToOneResult);

        return $arrResult;

      }
      catch (\SoapFault $ex)
      {
       return [0 => '9999', 1 => $ex->getMessage()];

     }

   }

   public function getLog()
   {

     $log = DB::table('passengerlog')
     ->join('user','passengerlog.dnumber','=','user.number')

     ->join('passengers','passengerlog.pnumber','=' ,'passengers.Number')->get();

       //$log = PassengerLog::all();

     return response()->json([

       'pname' => $log->pname,
       'pnumber' => $log->pnumber,
       'dname' => $log->name,
       'dnumber' => $log->dnumber,
       'pick'=>$log->pick,
       'drop'=>$log->drop,
       'created_at'=>$log->created_at



       ]);


   }
}
