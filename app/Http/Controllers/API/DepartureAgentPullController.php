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

class DepartureAgentPullController extends Controller
{
    public function countryRelatedDepartureForAgent(Request $request){

      	$country_name = $request->country;
      	$current_date = date('Y-m-d');
  		$departures = DB::table('countries')
      			->join('destinations','destinations.country_id','=','countries.id')
      			->join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                ->join('departures','departures.id','=','departure_destinations.departure_id')
                ->where('countries.country_name',$country_name)
                ->where(['departures.status'=> 1, 'departures.approve' =>1])
                ->where('departures.start_date','>=',$current_date)
                ->distinct()
                ->select('departures.*')
                ->get();
        $country_departure = [];
        if(count($departures) > 0){
        	
        	foreach ($departures as $key => $dep) {
      			$dest_row = DB::table('departure_destinations')
      						->where('departure_id',$dep->id)
				        	->pluck('destination_id')
				        	->toArray();
				$destname_name = array();
				
	            foreach ($dest_row as $key => $value) {
	                $desti_name = Destination::join('countries','countries.id','=','destinations.country_id')
	                			->where('destinations.id', $value)
	                			->distinct()
	                			->select('destinations.id as dest_id','destinations.reference_id as reff_id','destinations.dest_name','destinations.actualname','destinations.latitude','destinations.longitude','destinations.region','destinations.description','destinations.country_iso_2','destinations.country_iso_3','countries.id as count_id','countries.country_name','countries.official_name','countries.capital','countries.largest_city','countries.continent','countries.description as count_des','countries.sub_continent','countries.iso_2','countries.iso_3','countries.isd_code','countries.latitude as count_lat','countries.longitude as count_long','countries.internet_tld','countries.currency','countries.currency_symbol','countries.currency_code','countries.drives_on','countries.area','countries.area_unit','countries.population')
	                			->first();
	                if($desti_name){
	                	array_push($destname_name, $desti_name);
	                }
	            }
	            $dep->destinations = $destname_name;

	            // Itinerary Pdf

	            $itinerary_id = DB::table('agent_itineraries')
	            			->where('departure_id',$dep->id)
				        	->value('id');
                $itinerary = DB::table('agent_itineraries')
                			->where('departure_id',$dep->id)
                			->select('id','pdf_file','tenant_id','dep_type','unique_key')
                			->first();
	            $dep->itineraries = $itinerary;

	            //Inclusions
	            $inclu_row = DB::table('inclusions')
	            			->where('departure_id',$dep->id)
				        	->pluck('id')->toArray();
	            $inclusion = array();
	            foreach ($inclu_row as $key => $value) {
	                $inclu = DB::table('inclusions')->join('departures','departures.id','=','inclusions.departure_id')
                			->where('inclusions.id', $value)
                			->select('inclusions.name','inclusions.description','inclusions.tenant_id','inclusions.dep_type')
                			->first();
	                if($inclu){
	                 array_push($inclusion, $inclu);
	                }
	            }
	            $dep->inclusions = $inclusion;
	            
	            $o_flight = DB::table('departure_flight_details')
                			->where('departure_id', $dep->id)
                			->get();
            	$dep->origin_flights = $o_flight;

            	$r_flight = DB::table('return_flight_details')
                			->where('departure_id', $dep->id)
                			->get();
            	$dep->return_flights = $r_flight;
	            //Pricing
	            // $price_row = DB::table('prices')
	            // 			->where('departure_id',$dep->id)
	            // 			->where('price_type_id', 1)
				        	// ->select('price_type_id','price_inr','price_usd')
				        	// ->get();
	            
	            // $dep->prices = $price_row;

	            //hold departure seat
	            // $hold_seat = DB::table('hold_departures')
	            // 			->where('departure_id',$dep->id)
				        	// ->select('hold_till','date')
				        	// ->first();
	            // $dep->hold_departure = $hold_seat;

	        array_push($country_departure, $dep);
      		}
      	}
      	//dd($departures);
    	return response()->json(['data' => $country_departure], 200);
    }
}
