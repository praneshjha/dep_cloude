<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use DB;
use App\Departure;
use App\Destination;
use App\Itinerary;
use App\DepartureDestination;

class ItineraryController extends Controller
{
    public function itineraryIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $tour = Departure::where('id',$route_id)->value('no_of_days');
        $itineraries = Itinerary::where(['departure_id' => $route_id, 'tenant_id' => auth()->user()->tenant_id])
            ->orderBy('day_number', 'ASC')
            ->get();
        $count = count($itineraries);

        $day_set = Itinerary::where(['departure_id'=>$route_id])
                ->pluck('day_number')->toArray();
        $day_total = range(1,$tour);
        $day_number= array_diff($day_total,$day_set);
        
        if($count > 0 ){
            foreach ($itineraries as $key => $value) {
                $dest_id = explode(",",$value->destinations);
                //$value->destinations = Destination::whereIn('id', $dest_id)
                        //->select('id','dest_name')->first();

                $destNames = Destination::whereIn('id', $dest_id)
                        ->pluck('dest_name')->toArray();
                $value->destNames = implode(', ',$destNames);
                // for Model
                $data = explode(",",$value->destinations);
               // dd($value->destinations);
                $value['destinations_id'] = $data;
                
                if($value->destinations != ''){
                    $destination_name = array();
                    foreach ($data as $key => $values) {
                        $itineary_row = Destination::where('id',$values)
                                    ->first();
                        if($itineary_row){
                            array_push($destination_name, $itineary_row->dest_name);
                        }
                    }
                    $value['destinations_name'] = $destination_name;
                }else{
                     $value['destinations_name'] = [];
                }
            } 
        }

        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                    ->where('departure_destinations.departure_id',$route_id)
                    ->distinct()
                    ->select("destinations.id","destinations.dest_name")
                    ->get();
        $departure = Departure::where('id', $route_id)->select('title','no_of_days')->first();
        $day = Itinerary::where('departure_id',$route_id)->count()+1;
        return view('departure.itinerary_create',compact('destinations','itineraries','departure','count','day','day_number'));
    }

    public function itineraryStore(Request $request)
    {
    	$data = $request->all();

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $array_check = array();
        for($i = 0; $i < count($request->destinations); $i++) {
            if ($request->destinations[$i] != '') {
                array_push($array_check, $request->destinations[$i]);
            }
        }
        $itinerary = new Itinerary;
        $itinerary->day_number = $request->day;
        $itinerary->day_heading = $request->day_heading;
        $itinerary->description = $request->description;
        $itinerary->departure_id=$route_id;
        $itinerary->tenant_id = $user->tenant_id;
        $itinerary->user_id = $user->id;
        $itinerary->dep_type = "main";
        $itinerary->destinations = implode(",",$array_check);
        $itinerary->unique_key = Str::random(10).time();
        $itinerary->save();
        return response()->json($itinerary->id);
    }

    public function itineraryUpdate(Request $request, $id)
    {
        $array_check = array();
        for($i = 0; $i < count($request->edit_destinations); $i++) {
            if ($request->edit_destinations[$i] != '') {
                array_push($array_check, $request->edit_destinations[$i]);
            }
        }
        $user = auth()->user(); 
        $itinerary = Itinerary::find($id);
        $itinerary->day_heading = $request->edit_day_heading;
        $itinerary->description = $request->edit_description;
        $itinerary->destinations = implode(",",$array_check);
        $itinerary->save();
        $last_id = $itinerary->id;

        return response()->json($itinerary->id);
    }

    public function itinerayDelete(Request $request, $id)
    {
        $itineraries = Itinerary::where('id',$id)->delete();
        return redirect()->back();
    }

    public function getDestinationPoiAjax(Request $request)
    {
        $route_id = $request->route_id;
        $ids = explode(',',$request->destination_id);
        //$ids = array(11110,3186);
        $pois = DB::table('departure_destination_point_of_interests')
          ->join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
          ->where('departure_destination_point_of_interests.departure_id',$route_id)
              ->where(function ($query) use ($ids) {
                  $query->whereIn("departure_destination_point_of_interests.destination_id",$ids);
              })
            ->orderBy('departure_destination_point_of_interests.id', 'ASC')
            ->select("departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","destinations.dest_name","destinations.id as dest_id","departure_destination_point_of_interests.id as loc_id")
                ->get();
            return response()->json($pois);
    }
}