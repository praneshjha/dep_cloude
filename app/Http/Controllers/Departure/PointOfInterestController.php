<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Departure;
use App\Destination;
use App\DepartureDestinationPointOfInterest;
use App\Experience;
use App\Activity;
use App\DestinationExperience;

class PointOfInterestController extends Controller
{

    public function poiCreate(Request $request)
    {
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                        ->join('countries','countries.id','=','destinations.country_id')
            			->where('departure_destinations.departure_id',$route_id)
            			->select("destinations.id","destinations.dest_name","destinations.geonameid","destinations.region_id","destinations.region","destinations.country_iso_3","destinations.country_name","destinations.reference_id","countries.reference_id as country_reference_id","destinations.latitude","destinations.longitude","destinations.feature_class","destinations.feature_code")
                        ->get();
        $poi_list = DepartureDestinationPointOfInterest::join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
                        ->join('countries','countries.id','=','destinations.country_id')
            			->where(['departure_destination_point_of_interests.departure_id' => $route_id, 'departure_destination_point_of_interests.dep_type' => 'main', 'tenant_id' =>auth()->user()->tenant_id])
            			->distinct()
            			->select("destinations.id as dest_id","destinations.dest_name","departure_destination_point_of_interests.id","departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","departure_destination_point_of_interests.poi_type","departure_destination_point_of_interests.rating","departure_destination_point_of_interests.image","departure_destination_point_of_interests.status","countries.country_name")
                        ->paginate(10);
            if($request->ajax()){
                return view('departure.destination_poi_list',compact('poi_list'));
            }
        return view('departure.destination_poi_create',compact('destinations','poi_list'));
    }

    public function poiStore(Request $request)
    {
        $data = $request->all();
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $destination_id = $request->destinations;
        $poiss = json_encode($request->poiName);
        $pois = json_decode($poiss);
        $length = count($pois);
        if($length>0){
            for ($i = 0; $i < $length; $i++)
            {
            	$value= json_decode($pois[$i]);
            	$inique = DepartureDestinationPointOfInterest::where(['departure_id' => $route_id,'destination_id' => $destination_id, 'reference_id' => $value->id, 'tenant_id' => auth()->user()->tenant_id,'dep_type'=>'main'])->first();
            	if($inique == null){
			        $depdestpoi = new DepartureDestinationPointOfInterest;
			        $depdestpoi->departure_id = $route_id;
                    $depdestpoi->destination_id = $destination_id;
                    $depdestpoi->reference_id = $value->id;
                    $depdestpoi->poi_name = $value->point_name;
                    $depdestpoi->latitude = $value->latitude;
                    $depdestpoi->longitude = $value->longitude;
                    if($value->rating == ''){
                        $depdestpoi->rating = 4;
                    }else{
                        $depdestpoi->rating = $value->rating;
                    }
                    if($value->rating == ''){
                       $depdestpoi->reviews = 250;
                    }else{
                        $depdestpoi->reviews = $value->total_reviews;
                    }
                    
                    $depdestpoi->poi_type = $value->poi_type;
                    $depdestpoi->image = $value->image;
                    $depdestpoi->address = $value->address;
                    $depdestpoi->description = $value->description;
                    $depdestpoi->dep_type = "main";
                    $depdestpoi->tenant_id = $user->tenant_id;
                    $depdestpoi->user_id = $user->id;
			        $depdestpoi->save();
			    }
			}
		}	
        $experiences = $request->experiences;
        if($experiences){
            foreach ($experiences as $value) {
                $destexp = DestinationExperience::where('destination_id',$destination_id)
                            ->where('experience_id',$value)
                            ->first();
                if($destexp == null){                                
                    $experience_destination  = new DestinationExperience;
                    $experience_destination->destination_id = $destination_id;
                    $experience_destination->experience_id = $value;
                    $experience_destination->save();
                }
            }  
        }
        $status = [
                'url'=> url('/departure/poi',$route_id),
            ];
        return response()->json($status);
    }

    public function poiDisable(Request $request, $id)
    {
        $poidisableenable = DepartureDestinationPointOfInterest::find($id);
        if($poidisableenable->status == 1){
            $poidisableenable->status = 0;
            $poidisableenable->save();
        }
        else{
            $poidisableenable->status = 1;
            $poidisableenable->save();
        }
        return response()->json(['success'=>'POIs disabled successfully!']);
    }

    public function getExperiences(Request $request){
        $expActivity = [];
        if($request->has('q')){
            $search = $request->q;
            $expActivity = Experience::select("id","experience_name")
                        ->where('experience_name','LIKE',"%$search%")
                        ->get(10);
            
        }else{
            $expActivity = Experience::select("id","experience_name")
                        ->limit(5)
                        ->get();
        }
        return response()->json($expActivity);
    }
    
 //    public function experiencesGet(Request $request){

 //    		$destid = $request->destination_id;
 //    		$post = array(
	// 		'reference_id' => $destid
	// 		);

	// 		$curl = curl_init();
	// 		 curl_setopt($curl, CURLOPT_URL, env('pullIt_BaseUrl').'api/get_experiences_related_destination');
	// 		 curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	// 		 curl_setopt($curl, CURLOPT_POST, 1);
	// 		 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	// 		 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	// 		 $response = curl_exec($curl);
	// 		 curl_close ($curl);
 //           return Response()->json(json_decode($response));
	// }

	public function pointofInterestGet(Request $request){

    		$destid = $request->destination_id;
    		$post = array(
			'destination_id' => $destid
			);

			$curl = curl_init();
			 curl_setopt($curl, CURLOPT_URL, env('pullIt_BaseUrl').'api/get_poi_related_destination');
			 curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			 curl_setopt($curl, CURLOPT_POST, 1);
			 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
			 $response = curl_exec($curl);
			 // print_r($response);
			 // die;
			//  if ($response) {
			//     echo "ok";
			// } else {
			//     echo 'Curl error: ' . curl_error($ch);
			// }
			 curl_close ($curl);
           return Response()->json(json_decode($response));
	}
    
   public function addMorePoiSaveInPull(Request $request){

            //$destid = $request->destination_id;
            $post = array(
            'place_id' => $request->add_place_id,
            'poi_type' => $request->add_poi_type,
            'hours' => $request->add_hours,
            'lat' => $request->add_lat,
            'long' => $request->add_lat,
            'rating' => $request->add_rating,
            'reviews' => $request->add_reviews,
            'website' => $request->add_web_url,
            'map_url' => $request->add_poi_url,
            'mobile' => $request->add_phone,
            'poi' => $request->add_poi,
            'destination' => $request->add_destination,
            'country' => $request->add_country,
            'address' => $request->add_address,
            'description' => $request->add_description,
            'region_id' => $request->add_regionid,
            'geoname_id' => $request->add_geonameid,
            'image' => $request->add_image,
            );

            $curl = curl_init();
             curl_setopt($curl, CURLOPT_URL, env('pullIt_BaseUrl').'api/add-more-pois');
             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
             curl_setopt($curl, CURLOPT_POST, 1);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
             $response = curl_exec($curl);
             curl_close ($curl);
           return Response()->json(json_decode($response));
    }
}