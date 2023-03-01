<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\DeparturePrice;
use App\PaymentSchedule;
use App\CancelSchedule;
use Auth;

class DeparturesPullController extends Controller
{
    public function getDCDepartureDook(Request $request){

        //$country_name = 'france';
        //$current_date = date('Y-m-d');
        //$tenant_id = auth::user()->tenant_id;
        //$company = $request->company_name;
        //$user_tenant = User::where('company_name',$company)->value('tenant_id');
        //$country_id = DB::table('countries')->where('country_name', $country_name)->value('id');
        if($request->text == "DCPull"){
            $current_date = date('Y-m-d');
            $dep_type = [1,2];
            $departures = DB::table('departures')
                ->where(['status'=> 1, 'approve' =>1, 'pull_status'=>0])
                ->where('start_date','>',$current_date)
                ->where('tenant_id',"oxwtvlnriqs1634705279")
                ->whereIn('departure_type',$dep_type)
                ->take(25)
                ->get();
            
            if(count($departures)>0) {
                foreach ($departures as $key => $value) {
                    // if($value->departure_type == 1){
                    //     $value->departure_type = "Land + Flight";
                    // }
                    // if($value->departure_type == 2){
                    //     $value->departure_type = "Land Only";
                    // }
                    // if($value->departure_type == 3){
                    //     $value->departure_type = "Hotel + Flight";
                    // }
                    // if($value->departure_type == 4){
                    //     $value->departure_type = "Hotel Only";
                    // }
                    // if($value->departure_type == 5){
                    //     $value->departure_type = "Flight Only";
                    // }

                    $row = DB::table('departure_destinations')
                            ->where('departure_id', $value->id)
                            ->orderBy('destination_id', 'ASC')
                            ->pluck('destination_id')
                            ->toArray();
                    $destination_str = implode(",",$row);
                    $value->destination_str = $destination_str;
                    
                    //Destinations
                    $destname_name = array();
                    foreach ($row as $key => $value_dest) {
                        $desti_name = Destination::join('countries','countries.id','=','destinations.country_id')
                                    ->where('destinations.id', $value_dest)
                                    ->distinct()
                                    ->select('destinations.id as dest_id','destinations.dest_name','countries.id as count_id','countries.country_name')
                                    ->first();
                        if($desti_name){
                            array_push($destname_name, $desti_name);
                            if($key == 0){
                                $arr = explode(" ",$desti_name->dest_name);
                                $str = implode("-",$arr);
                                $value->url_pre_dest = strtolower($str);
                            }
                        }
                    }
                    $value->destinations = $destname_name;

                    //Itineraries
                    
                    $itinerary = DB::table('agent_itineraries')
                            ->where('departure_id',$value->id) 
                            ->select('pdf_file','title')
                            ->first();
                    if(isset($itinerary->pdf_file) && isset($itinerary->pdf_file)){
                        $value->itinerary_pdf = $itinerary->pdf_file;
                        $value->itinerary_url = $itinerary->title;
                    }
                    elseif(isset($itinerary->pdf_file)){
                       $value->itinerary_pdf = url('agentitinerary').'/'.$itinerary->pdf_file;
                       $value->itinerary_url = "";
                    }
                    elseif(isset($itinerary->pdf_file)){
                        $value->itinerary_url = $itinerary->title;
                        $value->itinerary_pdf = "";
                    }
                    else{
                        $value->itinerary_url = "";
                        $value->itinerary_pdf = "";
                    }

                    //Inclusions

                    $inclusion = DB::table('inclusions')
                                ->where('departure_id', $value->id)
                                ->select('inclusions.name','inclusions.description','inclusions.tenant_id','inclusions.dep_type','icon')
                                ->get();
                    $value->inclusions = $inclusion;

                    //Day Schedule
                    $daySchedule = DB::table('itineraries')
                            ->where('departure_id', $value->id)
                            ->select('day_number','day_heading','description','destinations as destIds','tenant_id')
                            ->get();
                    foreach ($daySchedule as $key => $values) {
                        $arr = explode(',',$values->destIds);
                        $values->destinations = DB::table('destinations')
                                ->whereIn('id',$arr)->select('dest_name','reference_id')->get();
                    }
                    $value->day_schedule = $daySchedule;

                    $o_flight = DB::table('departure_flight_details')
                                ->where('departure_id', $value->id)
                                ->get();
                    $value->origin_flights = $o_flight;

                    $r_flight = DB::table('return_flight_details')
                                ->where('departure_id', $value->id)
                                ->get();
                    $value->return_flights = $r_flight;
                    $pricingS = DeparturePrice::where('departure_id', $value->id)
                             ->first();
                    $value->departure_price = $pricingS->price;
                    $value->departure_cs = $pricingS->currency_symbol;
                    $value->pricingFirst = $pricingS;

                    $pricing = DeparturePrice::where('departure_id', $value->id)
                             ->get();
                    $value->departure_pricing = $pricing;

                    $payment_schedule = PaymentSchedule::where('departure_id', $value->id)->get();
                    $value->payment_schedule = $payment_schedule;

                    $cnacel_schedule = CancelSchedule::where('departure_id', $value->id)->get();
                    $value->cnacel_schedule = $cnacel_schedule;
                    $update = Departure::find($value->id);
                    $update->pull_status = 1;
                    $update->change_status = 0;
                    $update->pulled_date = $current_date;
                    $update->save();
                }
            }
            return response()->json(['data' => $departures], 200);
        }
        else{
            return response()->json(['data' => 'Something went gg wrong!'], 200);
        }  
    }

    public function remainingChangeDeparture(Request $request){
        $current_date = date('Y-m-d');
        $dep_type = [1,2];
        $pull_status = Departure::where('start_date','>',$current_date)->where('status',1)->where('approve',1)->where('pull_status',1)->whereIn('departure_type',$dep_type)->count();

        $change_status = Departure::where('start_date','>',$current_date)
            ->where(['change_status'=>1, 'status'=>1, 'approve'=>1])
            ->whereIn('departure_type',$dep_type)
            ->count();

        $total = Departure::where('start_date','>',$current_date)->where('status',1)->where('approve',1)->whereIn('departure_type',$dep_type)->count();

        $remaining = Departure::where('start_date','>',$current_date)->where('pull_status',0)->where('status',1)->where('approve',1)->whereIn('departure_type',$dep_type)->count();

        return response()->json(['pull_status' => $pull_status, 'change_status' => $change_status, 'total' => $total, 'remaining' => $remaining], 200);
    }

    public function getDCSyncDepartureDook(Request $request)
    {
        if($request->text == "Sync"){
            $current_date = date('Y-m-d');
            $dep_type = [1,2];
            $departures = DB::table('departures')
                ->where(['status'=> 1, 'approve' =>1])
                ->where('start_date','>',$current_date)
                ->where(['change_status'=>1,'pull_status'=>1])
                ->where('tenant_id',"oxwtvlnriqs1634705279")
                ->whereIn('departure_type',$dep_type)
                ->get();
            
            if(count($departures)>0) {
                foreach ($departures as $key => $value) {
                    $row = DB::table('departure_destinations')
                            ->where('departure_id', $value->id)
                            ->orderBy('destination_id', 'ASC')
                            ->pluck('destination_id')
                            ->toArray();
                    $destination_str = implode(",",$row);
                    $value->destination_str = $destination_str;
                    // Destination ist
                    foreach ($row as $key => $first_dest) {
                        $dest_name1 = Destination::where('id', $first_dest)->select('dest_name')->first();
                        if($dest_name1){
                            if($key == 0){
                                $arr = explode(" ",$dest_name1->dest_name);
                                $str = implode("-",$arr);
                                $value->url_pre_dest = strtolower($str);
                            }
                        }
                    }
                    //Itineraries
                    
                    $itinerary = DB::table('agent_itineraries')
                            ->where('departure_id',$value->id) 
                            ->where('change_status',1)
                            ->select('pdf_file','title')
                            ->first();
                    if(isset($itinerary->pdf_file) && isset($itinerary->pdf_file)){
                        $value->itinerary_pdf = $itinerary->pdf_file;
                        $value->itinerary_url = $itinerary->title;
                    }
                    elseif(isset($itinerary->pdf_file)){
                       $value->itinerary_pdf = $itinerary->pdf_file;
                       $value->itinerary_url = "";
                    }
                    elseif(isset($itinerary->pdf_file)){
                        $value->itinerary_url = $itinerary->title;
                        $value->itinerary_pdf = "";
                    }
                    else{
                        $value->itinerary_url = "";
                        $value->itinerary_pdf = "";
                    }

                    //Inclusions

                    $inclusion = DB::table('inclusions')
                            ->where('departure_id', $value->id)
                            ->where('change_status',1)
                            ->select('inclusions.name','inclusions.description','inclusions.tenant_id','inclusions.dep_type','icon')
                            ->get();
                    $value->inclusions = $inclusion;

                    //Day Schedule
                    $daySchedule = DB::table('itineraries')
                            ->where('departure_id', $value->id)
                            ->select('day_number','day_heading','description','destinations as destIds','tenant_id')
                            ->get();
                    foreach ($daySchedule as $key => $values) {
                        $arr = explode(',',$values->destIds);
                        $values->destinations = DB::table('destinations')
                                ->whereIn('id',$arr)->select('dest_name','reference_id')->get();
                    }
                    $value->day_schedule = $daySchedule;
                    
                    $o_flight = DB::table('departure_flight_details')
                            ->where('departure_id', $value->id)
                            ->where('change_status',1)
                            ->get();
                    $value->origin_flights = $o_flight;

                    $r_flight = DB::table('return_flight_details')
                            ->where('departure_id', $value->id)
                            ->where('change_status',1)
                            ->get();
                    $value->return_flights = $r_flight;
                    $pricingS = DeparturePrice::where('departure_id', $value->id)
                             ->first();
                    $value->departure_price = $pricingS->price;
                    $value->departure_cs = $pricingS->currency_symbol;
                    $pricing = DeparturePrice::where('departure_id', $value->id)
                            ->where('change_status',1)
                            ->get();
                    $value->departure_pricing = $pricing;

                    $payment_schedule = PaymentSchedule::where('departure_id', $value->id)->where('change_status',1)->get();
                    $value->payment_schedule = $payment_schedule;

                    $cnacel_schedule = CancelSchedule::where('departure_id', $value->id)->where('change_status',1)->get();
                    $value->cnacel_schedule = $cnacel_schedule;

                    $update = Departure::find($value->id);
                    $update->pull_status = 1;
                    $update->change_status = 0;
                    $update->pulled_date = $current_date;
                    $update->save();
                }
            }
            return response()->json(['data' => $departures], 200);
        }
        else{
            return response()->json(['data' => 'Something went gg wrong!'], 200);
        }  
    }
}