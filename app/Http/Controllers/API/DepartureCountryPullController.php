<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Departure;
use App\Destination;
use App\Country;

class DepartureCountryPullController extends Controller
{
    public function countryList(Request $request){
    	$country_id = DB::table('country_departures')
    				->distinct()
    				->pluck('country_id')
    				->toArray();

  		$countries = DB::table('countries')
	                ->whereIn('id',$country_id)
	                ->distinct()
	                ->select('country_name')
	                ->get();
	     
    	return response()->json(['data' => $countries], 200);
    }
}
