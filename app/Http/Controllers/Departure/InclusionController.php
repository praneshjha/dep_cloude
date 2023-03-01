<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Departure;
use App\Destination;
use App\DepartureDestination;
use App\Inclusion;
use Auth;

class InclusionController extends Controller
{
	public function inclusionIndex(Request $request)
    {
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;

        $inclu_master = DB::table('inclusion_masters')->pluck('name')->toArray();
        $inclu_all = DB::table('inclusion_icons')->pluck('name')->toArray();
        $array = array_diff($inclu_all, $inclu_master);
        $inclusion_icon = DB::table('inclusion_icons')
                ->whereIn('name', $array)
                ->get();
        //$inclusion_icon = DB::table('inclusion_icons')->get();
        $inclusions = DB::table('inclusion_masters')->select('name','description','icon')->get();
        $inclusione = Inclusion::where(["departure_id" => $route_id, "dep_type" => "main"])->select('name','description','icon')->get();
        
        $array_1 = json_decode(json_encode($inclusions, true),true);
        $array_2 = json_decode(json_encode($inclusione, true),true);
        $array = array_merge($array_1, $array_2);
        foreach($array as $element) {
            $hash = $element['name'];
            $inclusionedit[$hash] = $element;
        }
        foreach ($inclusionedit as $value) {
            $arr[] = $value;
        }
        $inclusionedits = $arr;
        $departure = Departure::find($route_id);
        if(count($inclusione) > 0){
            if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
            {
        	  return view('departure.inclusion_edit',compact('inclusionedits','inclusione','inclusion_icon','departure'));
            }
            else{
                return view('404');
            }
        }
        else{
        	$inclusion_masters = DB::table('inclusion_masters')->get();
	        return view('departure.inclusion_create',compact('inclusion_masters','inclusion_icon','departure'));
	    }
	    	
    }

    public function storeInclusion(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $array_check = array();
        for($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i] != '') {
                array_push($array_check, $request->name[$i]);
            }
        }
        if(count($array_check) > 0){ 
            if($request->names){
                foreach ($request->names as $key => $value) {
                    $n = 1; 
                    $start = strlen($value) - $n;
                    $str1 = ''; 
                    for ($x = $start; $x < strlen($value); $x++) { 
                        $str1 .= $value[$x]; 
                    }
                    $inc  = Inclusion::where('name',$value)
                            ->where('departure_id',$route_id)
                            ->first();
                    if(is_null($inc)){
                        $inclusion  = new Inclusion;
                        $incName = preg_replace('/[0-9]+/', '', $value);
                        //substr($value, 0, strlen($value)-1);
                        $inclusion->name = $incName; 
                        if($request->descriptions[$str1] != ''){   
                            $inclusion->description = $request->descriptions[$str1];
                        }
                        else{
                            $inclusion->description = '';
                        }
                        $inclusion->icon = $request->icons[$str1];
                        $inclusion->departure_id = $route_id;
                        $inclusion->dep_type = "main";
                        $inclusion->tenant_id = $user->tenant_id;
                        $inclusion->user_id = $user->id;
                        $inclusion->save();
                    }
                }
            }
            foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icon[$key];
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main"; 
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            } 
        }
        else{
            if(count($request->names)>0){
                foreach ($request->names as $key => $value) {
                    $n = 1; 
                    $start = strlen($value) - $n;
                    $str1 = ''; 
                    for ($x = $start; $x < strlen($value); $x++) { 
                        $str1 .= $value[$x]; 
                    }

                    $inc  = Inclusion::where('name',$value)
                            ->where('departure_id',$route_id)
                            ->first();
                    if(is_null($inc)){
                        $inclusion  = new Inclusion;
                        $incName = preg_replace('/[0-9]+/', '', $value);
                        //substr($value, 0, strlen($value)-1);
                        $inclusion->name = $incName; 
                        if($request->descriptions[$str1] != ''){   
                            $inclusion->description = $request->descriptions[$str1];
                        }
                        else{
                            $inclusion->description = '';
                        }
                        $inclusion->icon = $request->icons[$str1];
                        $inclusion->departure_id = $route_id;
                        $inclusion->dep_type = "main";
                        $inclusion->tenant_id = $user->tenant_id;
                        $inclusion->user_id = $user->id;
                        $inclusion->save();
                    }
                }
            }
            else{
                foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icon[$key];
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main"; 
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            } 
            }
        }
        $departure_type = DB::table('departures')->where('id',$route_id)->value('departure_type');
        if($departure_type == 1){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 2){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 3){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 4){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 5){
            $status = [
                    'url'=> url('/departure/flight-details',$route_id),
            ];
        }
        return response()->json($status);  
    }

    public function updateInclusion(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        //return empty($request->names);
        // if(empty($request->names) == 1){
        //     Inclusion::where('departure_id', $route_id)->delete();
        // }
        // else
        // {
           
        $array_check = array();
        for($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i] != '') {
                array_push($array_check, $request->name[$i]);
            }
        }
        if(count($array_check) > 0){ 
            Inclusion::where('departure_id', $route_id)->delete();
            if($request->names){
                foreach ($request->names as $key => $value) {
                    $n = 1; 
                    $start = strlen($value) - $n;
                    $str1 = ''; 
                    for ($x = $start; $x < strlen($value); $x++) { 
                        $str1 .= $value[$x]; 
                    }
                    //dd($str1);
                    $inc  = Inclusion::where('name',$value)
                            ->where('departure_id',$route_id)
                            ->first();
                    if(is_null($inc)){
                        $inclusion  = new Inclusion;
                        $incName = preg_replace('/[0-9]+/', '', $value);
                        //substr($value, 0, strlen($value)-1);
                        $inclusion->name = $incName; 
                        if($request->descriptions[$str1] != ''){   
                            $inclusion->description = $request->descriptions[$str1];
                        }
                        else{
                            $inclusion->description = '';
                        }
                        $inclusion->departure_id = $route_id;
                        $inclusion->icon = $request->icons[$str1];
                        $inclusion->dep_type = "main";
                        $inclusion->tenant_id = $user->tenant_id;
                        $inclusion->user_id = $user->id;
                        $inclusion->change_status = 1;
                        $inclusion->save();
                    }
                }
            }
            foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id; 
                    $inclusion->icon = $request->icon[$str1];
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->change_status = 1;
                    $inclusion->save();
                }
            } 
        }
        else{
            Inclusion::where('departure_id', $route_id)->delete();
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $starts = preg_replace('/[0-9]+/', '', $value);
                
                $start = strlen($starts);
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = preg_replace('/[0-9]+/', '', $value);
                    //substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName;  
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id;
                    $inclusion->icon = $request->icons[$str1];
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->change_status = 1;
                    $inclusion->save();
                }
            }
            //die;
        }
        $departure = Departure::find($route_id);
        $departure->change_status = 1;
        $departure->save();
        
        $departure_type = DB::table('departures')->where('id',$route_id)->value('departure_type');
        if($departure_type == 1){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 2){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 3){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 4){
            $status = [
                    'url'=> url('/departure/hotel-details',$route_id),
            ];
        }
        if($departure_type == 5){
            $status = [
                    'url'=> url('/departure/flight-details',$route_id),
            ];
        }
        return response()->json($status);  
    }
    public function InclusionIcon(Request $request){
        // $startDest = [];
        // if($request->has('q')){
        //     $search = $request->q;
        //     $inclusions = DB::table('inclusion_masters')->pluck('name')->toArray();
        //     $inclusione = DB::table('inclusion_icons')->pluck('name')->toArray();
            
        //     //$array_1 = json_decode(json_encode($inclusions, true),true);
        //     //$array_2 = json_decode(json_encode($inclusione, true),true);

        //     $array = array_diff($inclusione, $inclusions);

        //     $startDest = DB::table('inclusion_icons')
        //                 ->whereIn('name', $array)
        //                 ->where('name','LIKE',"%$search%")
        //                 ->select('name','icon')
        //                 ->get();
            
        // }else{
        //     $inclusions = DB::table('inclusion_masters')->pluck('name')->toArray();
        //     $inclusione = DB::table('inclusion_icons')->pluck('name')->toArray();
            
        //     //$array_1 = json_decode(json_encode($inclusions, true),true);
        //     //$array_2 = json_decode(json_encode($inclusione, true),true);

        //     $array = array_diff($inclusione, $inclusions);
        //     //dd($array);
        //     $startDest = DB::table('inclusion_icons')
        //                 ->whereIn('name', $array)
        //                 ->select('name','icon')
        //                 ->get();
        // }
        // return response()->json($startDest);
    }

}

