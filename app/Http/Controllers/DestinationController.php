<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
class DestinationController extends Controller
{
    public function destinationIndex(Request $request){

    	$destinations = Destination::join('countries','countries.id','=','destinations.country_id')
    					->orderBy('countries.country_name', 'ASC')->paginate(25);
    	$destinationCount = Destination::get();
        $s3url= "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/";
    	$total = count($destinationCount);
    	if($request->ajax()){
                return view('destination.index_data',compact('destinations','s3url'));
            }
        return view('destination.index',compact('destinations','total','s3url'));
    }
}
