<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DepartureFlightDetail;
use App\ReturnFlightDetail;
use App\Departure;
use Auth;
use DB;

class FlightController extends Controller
{
    public function CreateFlight(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $oflights = DepartureFlightDetail::where("departure_id",$route_id)
                ->get();
        $rflights = ReturnFlightDetail::where("departure_id",$route_id)
                ->get();
        $departure = Departure::find($route_id);
        if(count($oflights) > 0 || count($rflights) > 0){
            if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
            {
              return view('flight.edit',compact('oflights','rflights','departure'));
            }
            else{
                return view('404');
            }
        }
        else
        {
            return view('flight.create',compact('departure'));
        }
    }
    public function FlightStore(Request $request, $id){

        $last_id = $id;
        $array_check=[];
        
        if($request->flight_name){
            for($i = 0; $i < count($request->flight_name); $i++) {
                if ($request->flight_name[$i] != '') {
                    array_push($array_check, $request->flight_name[$i]);
                }
            }
        }
        $array_check1 = array();
        if($request->r_flight_name){
            for($i = 0; $i < count($request->r_flight_name); $i++) {
                if ($request->r_flight_name[$i] != '') {
                    array_push($array_check1, $request->r_flight_name[$i]);
                }
            }
        }
        if(count($array_check) > 0){
            foreach ($request->flight_name as $key => $value) {
                if($value != ''){
                    $airline_data = DB::table('airlines')->where('name',$value)->first();
                
                    $origi_date = date("y-m-d", strtotime($request->flight_date[$key]));
                    $arri_date = date("y-m-d", strtotime($request->flight_arrival_date[$key]));
                        $flight = new DepartureFlightDetail;
                        $flight->code = $request->code[$key];
                        $flight->flight_name = $request->flight_name[$key];
                        $flight->flight_no = $request->flight_no[$key];
                        $flight->flight_date = $origi_date;
                        $flight->flight_arrival_date = $arri_date;
                        $flight->flight_dep_time = $request->flight_dep_time[$key];
                        $flight->flight_arrival_time = $request->flight_arrival_time[$key];
                        $flight->flight_dep_airport = $request->flight_dep_airport[$key];
                        $flight->flight_arrival_airport = $request->flight_arrival_airport[$key];
                        $flight->baggage = $request->baggage[$key];
                        if($airline_data){
                            $flight->logo = $airline_data->logo;
                        }
                        $flight->departure_id = $last_id;
                       $flight->save();
                  }
            }
        }
        
        if(count($array_check1) > 0){
           foreach ($request->r_flight_name as $key => $value) {
            if($value != ''){
                $airline_data1 = DB::table('airlines')->where('name',$value)->first();
                $departed_date = date("y-m-d", strtotime($request->r_flight_date[$key]));
                $arri_date = date("y-m-d", strtotime($request->r_flight_arrival_date[$key]));

                    $flight = new ReturnFlightDetail;
                    $flight->code = $request->r_code[$key];
                    $flight->flight_name = $request->r_flight_name[$key];
                    $flight->flight_no = $request->r_flight_no[$key];
                    $flight->flight_date = $departed_date;
                    $flight->flight_arrival_date = $arri_date;
                    $flight->flight_dep_time = $request->r_flight_dep_time[$key];
                    $flight->flight_arrival_time = $request->r_flight_arrival_time[$key];
                    $flight->flight_dep_airport = $request->r_flight_dep_airport[$key];
                    $flight->flight_arrival_airport = $request->r_flight_arrival_airport[$key];
                    $flight->baggage_arriving = $request->baggage_arriving[$key];
                    if($airline_data1){
                        $flight->logo = $airline_data1->logo;
                    }
                    $flight->departure_id = $last_id;
                    $flight->save();
              }
           }
        }
        $status = [
                'url'=> url('departure/itinerary-create',$last_id),
            ];
        return response()->json($status);  
    }
    public function FlightUpdate(Request $request, $id){
        $last_id = $id;
        $array_check=[];
        $array_check1=[];
        if($request->flight_name){
            for($i = 0; $i < count($request->flight_name); $i++) {
                if ($request->flight_name[$i] != '') {
                    array_push($array_check, $request->flight_name[$i]);
                }
            }
        }
        $array_check1 = array();
        if($request->r_flight_name){
            for($i = 0; $i < count($request->r_flight_name); $i++) {
                if ($request->r_flight_name[$i] != '') {
                    array_push($array_check1, $request->r_flight_name[$i]);
                }
            }
        }
        if(count($array_check) > 0){
            DepartureFlightDetail::where('departure_id',$id)->delete();
            foreach ($request->flight_name as $key => $value) {
                if($value != ''){
                    $airline_data = DB::table('airlines')->where('name',$value)->first();
                    $departed_date = date("y-m-d", strtotime($request->flight_date[$key]));
                    $arri_date = date("y-m-d", strtotime($request->flight_arrival_date[$key]));

                    $flight = new DepartureFlightDetail;
                    $flight->code = $request->code[$key];
                    $flight->flight_name = $request->flight_name[$key];
                    $flight->flight_no = $request->flight_no[$key];
                    $flight->flight_date = $departed_date;
                    $flight->flight_arrival_date = $arri_date;
                    $flight->flight_dep_time = $request->flight_dep_time[$key];
                    $flight->flight_arrival_time = $request->flight_arrival_time[$key];
                    $flight->flight_dep_airport = $request->flight_dep_airport[$key];
                    $flight->flight_arrival_airport = $request->flight_arrival_airport[$key];
                    $flight->baggage = $request->baggage[$key];
                    if($airline_data){
                        $flight->logo = $airline_data->logo;
                    }
                    
                    $flight->departure_id = $last_id;
                    $flight->change_status = 1;
                    $flight->save();
                }
           }
        }
        if(count($array_check1) > 0){
           ReturnFlightDetail::where('departure_id',$id)->delete();
           foreach ($request->r_flight_name as $key => $value) {
            if($value != ''){
                $airline_data1 = DB::table('airlines')->where('name',$value)->first();
                $departed_date = date("y-m-d", strtotime($request->r_flight_date[$key]));
                $arri_date = date("y-m-d", strtotime($request->r_flight_arrival_date[$key]));
                $flight = new ReturnFlightDetail;
                $flight->code = $request->r_code[$key];
                $flight->flight_name = $request->r_flight_name[$key];
                $flight->flight_no = $request->r_flight_no[$key];
                $flight->flight_date = $departed_date;
                $flight->flight_arrival_date = $arri_date;
                $flight->flight_dep_time = $request->r_flight_dep_time[$key];
                $flight->flight_arrival_time = $request->r_flight_arrival_time[$key];
                $flight->flight_dep_airport = $request->r_flight_dep_airport[$key];
                $flight->flight_arrival_airport = $request->r_flight_arrival_airport[$key];
                $flight->baggage_arriving = $request->baggage_arriving[$key];
                if($airline_data1){
                    $flight->logo = $airline_data1->logo;
                }
                
                $flight->departure_id = $last_id;
                $flight->change_status = 1;
                $flight->save();
             }
           }
        }
        
        $departure = Departure::find($last_id);
        $departure->change_status = 1;
        $departure->save(); 
        $status = [
            'url'=> url('departure/itinerary-create',$last_id),
        ];
        return response()->json($status);  
    }
    public function FlightOriginDelete(Request $request, $id)
    {
        // dd($id);
        $flight =DepartureFlightDetail::where('id',$id)->delete();
        // return view('test');
        return redirect()->back();
    }
    public function FlightReturnDelete(Request $request, $id)
    {
        $flight = ReturnFlightDetail::where('id',$id)->delete();
        return redirect()->back();
    }
    public function getAirlineAjax(Request $request){
        $airline =[];
        if($request->has('q')){
            $search = $request->q;
            $airline= DB::table('airlines')
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('airline_code_2', 'like', '%'.$search.'%')
                ->orWhere('airline_code_3', 'like', '%'.$search.'%')
                ->select('name','airline_code_2','airline_code_3','logo')
                ->get(15);
            
        }else{
            $airline= DB::table('airlines')
                ->select('name','airline_code_2','airline_code_3','logo')
                ->limit(15)
                ->get();
        }
        return response()->json($airline);

        // $data = $request->q;
        // $post = array(
        //     'text' => $data
        // );
        
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, env('pullIt_BaseUrl').'api/get_cloud_airline');
        // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        // curl_setopt($curl, CURLOPT_POST, 1);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        // $response = curl_exec($curl);
        // //print_r($response);
        // // die;
        // //  if ($response) { 
        // //     echo "ok";
        // // } else {
        // //     echo 'Curl error: ' . curl_error($ch);
        // // 
        // curl_close ($curl);
        // return Response()->json(json_decode($response));
    }
}
