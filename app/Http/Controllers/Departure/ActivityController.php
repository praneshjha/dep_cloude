<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\Activity;
use App\ActivityPointOfInterest;
use App\DepartureDestinationPointOfInterest;

class ActivityController extends Controller
{
    public function activityIndex(Request $request)
    {

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;

        $activities = ActivityPointOfInterest::where(["departure_id" => $route_id, "tenant_id" => auth()->user()->tenant_id])->get();
        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                    ->where('departure_destinations.departure_id',$route_id)
                    ->select("destinations.id","destinations.dest_name")
                    ->get();
                    //dd($destinations);
            if(count($destinations) > 0){
                foreach ($destinations as $key => $destName) {
                    $poi_row = DB::table('departure_destination_point_of_interests')
                                ->where('destination_id',$destName->id)
                                ->where('departure_id', $route_id)
                                ->pluck('reference_id')
                                ->toArray();

                    $poi_array = array();
                    foreach ($poi_row as $key => $value) {
                        $poi = DB::table('departure_destination_point_of_interests')
                                    ->where('reference_id', $value)
                                    ->distinct()
                                    ->select('id','reference_id','poi_name')
                                    ->first();
                        array_push($poi_array, $poi);
                    }
                    $destName->pois = $poi_array;

                    $exp_row = DB::table('experiences')
                                ->join('destination_experiences','destination_experiences.experience_id','=','experiences.id')
                                ->where('destination_experiences.destination_id',$destName->id)
                                ->pluck('experience_id')
                                ->toArray();

                    $exp_array = array();
                    foreach ($exp_row as $key => $value) {
                        $exp = DB::table('experiences')
                                    ->where('id', $value)
                                    ->select('id','experience_name')
                                    ->first();
                        array_push($exp_array, $exp);
                    }
                     $destName->exps = $exp_array;

                    $exp_row = DB::table('experiences')
                                ->join('destination_experiences','destination_experiences.experience_id','=','experiences.id')
                                ->where('destination_experiences.destination_id',$destName->id)
                                ->pluck('experience_id')
                                ->toArray();
                    //foreach ($exp_row as $key => $value) {
                        $act = DB::table('activities')
                                    ->whereIn('experience_id', $exp_row)
                                    ->select('id as act_id','activity_name')
                                    ->get();
                    //} 
                    $destName->activity = $act;  
                    //echo "<pre>";
                    //print_r($destName);
                }
                //exit;
            }
        if(count($activities) > 0){
            return view('departure.activity_edit',compact('activities','destinations'));
        }else{
            return view('departure.activity_create',compact('destinations'));
        }
        
    }

    public function activityStore(Request $request)
    {
        $data = $request->all();

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 

        $poiID = $request->poi_ref_id;
        foreach ($poiID as $value) {
            $poiAct = $request->activities[$value];
            foreach ($poiAct as $activity) {
                $act  = ActivityPointOfInterest::where(['point_of_interest_reff_id' => $value, 'activity_id' => $activity, 'departure_id' => $route_id])->first();

                if(is_null($act)){
                    $activitiesPoi = new ActivityPointOfInterest;
                    $activitiesPoi->tenant_id = $user->tenant_id;
                    $activitiesPoi->departure_id = $route_id;
                    $activitiesPoi->activity_id = $activity;
                    $activitiesPoi->point_of_interest_reff_id = $value;
                    $activitiesPoi->save();
                }
            }
        
        }
        $status = [
                'status'=> 'Success!',
            ];
        return response()->json($status); 
    }

    public function activityUpdate(Request $request)
    {
        $data = $request->all();

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 

        $poiID = $request->poi_ref_id;
        if(count($poiID)>0){
            ActivityPointOfInterest::where('departure_id', $route_id)
                                    ->where('tenant_id', $user->tenant_id)
                                    ->delete();
            foreach ($poiID as $value) {
                $poiAct = $request->activities[$value];
                //$a = print_r($poiAct);
                
                foreach ($poiAct as $activity) {
                    $act  = ActivityPointOfInterest::where(['point_of_interest_reff_id' => $value, 'activity_id' => $activity, 'departure_id' => $route_id])->first();

                    if(is_null($act)){
                        $activitiesPoi = new ActivityPointOfInterest;
                        $activitiesPoi->tenant_id = $user->tenant_id;
                        $activitiesPoi->departure_id = $route_id;
                        $activitiesPoi->activity_id = $activity;
                        $activitiesPoi->point_of_interest_reff_id = $value;
                        $activitiesPoi->save();
                    }
                }
            }
        }
        //exit;
        $status = [
                'status'=> 'Success!',
            ];
        return response()->json($status); 
    }

    public function getExpActivityAjax(Request $request)
    {
        $route_id = $request->route_id;
        $ids = explode(',',$request->experience_id);
        //$ids = array(1,4,5);
        $activities = DB::table('activities')
                ->join('experiences','experiences.id','=','activities.experience_id')
                ->whereIn("experiences.id",$ids)
                ->orderBy('activities.activity_name', 'ASC')
                ->distinct()
                ->select("activities.id",'activities.activity_name')
                ->get();
            return response()->json($activities);
    }
}
