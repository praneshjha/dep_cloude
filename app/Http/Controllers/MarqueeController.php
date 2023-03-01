<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;

class MarqueeController extends Controller
{
    public function bakuTeam(Request $request){
        $departure_to = ["Baku","Yerevan","Tbilisi"];
        $date = date("Y-m-d");
        $data = DB::table('departures')
                ->whereIn('ending_at',$departure_to)
                ->where('start_date', '>=', $date)
                ->where('tenant_id','oxwtvlnriqs1634705279')
                ->select('id','start_date','ending_at as departure_to','total_seat','from')
                ->orderBy('start_date','ASC')
                ->paginate(10);
        foreach ($data as $key => $value) {
            $value->start_date = date('d M, Y', strtotime($value->start_date));
            $bookingUnit = DB::table('book_departures')
                    ->where('departure_id',$value->id)
                    ->where('status',1)
                    ->sum('booked_seat');
            $value->booked_seat = $bookingUnit;

            $holdingUnit = DB::table('hold_departure_seats')
                    ->where('departure_id',$value->id)
                    ->sum('hold_seat');
            $value->hold_seat = $holdingUnit;

            $airline = DB::table('departure_flight_details')
                    ->where('departure_id',$value->id)
                    ->select('flight_name','logo')
                    ->first();
            if($airline){
                if($airline->logo != ""){
                    $value->air_icon = url('assets1/images/flight').'/'.$airline->logo;
                }else{
                    $value->air_icon = url('assets1/images/flight').'/default.png';
                }
                $value->airline = $airline->flight_name;
            }else{
                $value->airline = "N/A";
                $value->air_icon = "";
            }
        }
        foreach ($data as $key => $value) {
            $value->available_seat = $value->total_seat - ($value->booked_seat+$value->hold_seat);
            $available_seat = $value->total_seat - ($value->booked_seat+$value->hold_seat);
            if($available_seat <= 0){
                $value->status = "Sold Out";
            }else{
                $value->status = "Open";
            }
        }
        
        if($request->page == ""){
            return view('marquee.index_baku',compact('data'));
        }else{
            return response()->json($data);
        }
    }

    public function almatyTeam(Request $request){
        $departure_to = ["Almaty","Bishkek"];
        $date = date("Y-m-d");
        $data = DB::table('departures')
                ->whereIn('ending_at',$departure_to)
                ->where('start_date', '>=', $date)
                ->where('tenant_id','oxwtvlnriqs1634705279')
                ->select('id','start_date','ending_at as departure_to','total_seat','from')
                ->orderBy('start_date','ASC')
                ->paginate(10);
        
        foreach ($data as $key => $value) {
            $value->start_date = date('d M, Y', strtotime($value->start_date));
            $bookingUnit = DB::table('book_departures')
                    ->where('departure_id',$value->id)
                    ->where('status',1)
                    ->sum('booked_seat');
            $value->booked_seat = $bookingUnit;

            $holdingUnit = DB::table('hold_departure_seats')
                    ->where('departure_id',$value->id)
                    ->sum('hold_seat');
            $value->hold_seat = $holdingUnit;

            $airline = DB::table('departure_flight_details')
                    ->where('departure_id',$value->id)
                    ->select('flight_name','logo')
                    ->first();
            if($airline){
                if($airline->logo != ""){
                    $value->air_icon = url('assets1/images/flight').'/'.$airline->logo;
                }else{
                    $value->air_icon = url('assets1/images/flight').'/default.png';
                }
                $value->airline = $airline->flight_name;
            }else{
                $value->airline = "N/A";
                $value->air_icon = "";
            }
        }
        foreach ($data as $key => $value) {
            $available_seat = $value->total_seat - ($value->booked_seat+$value->hold_seat);
            $value->available_seat = $available_seat;
            if($available_seat <= 0){
                $value->status = "Sold Out";
            }else{
                $value->status = "Open";
            }
        }
        if($request->page == ""){
            return view('marquee.index_almaty',compact('data'));
        }else{
            return response()->json($data);
        }
    }

    public function turkeyTeam(Request $request){
        $departure_to = ["Istanbul","Moscow"];
        $date = date("Y-m-d");
        $data = DB::table('departures')
                ->whereIn('ending_at',$departure_to)
                ->where('start_date', '>=', $date)
                ->where('tenant_id','oxwtvlnriqs1634705279')
                ->select('id','start_date','ending_at as departure_to','total_seat','from')
                ->orderBy('start_date','ASC')
                ->get();
        foreach ($data as $key => $value) {
            $value->start_date = date('d M, Y', strtotime($value->start_date));
            $bookingUnit = DB::table('book_departures')
                    ->where('departure_id',$value->id)
                    ->where('status',1)
                    ->sum('booked_seat');
            $value->booked_seat = $bookingUnit;

            $holdingUnit = DB::table('hold_departure_seats')
                    ->where('departure_id',$value->id)
                    ->sum('hold_seat');
            $value->hold_seat = $holdingUnit;

            $airline = DB::table('departure_flight_details')
                    ->where('departure_id',$value->id)
                    ->select('flight_name','logo')
                    ->first();
            if($airline){
                if($airline->logo != ""){
                    $value->air_icon = url('assets1/images/flight').'/'.$airline->logo;
                }else{
                    $value->air_icon = url('assets1/images/flight').'/default.png';
                }
                $value->airline = $airline->flight_name;
            }else{
                $value->airline = "N/A";
                $value->air_icon = "";
            }
        }
        foreach ($data as $key => $value) {
            $value->available_seat = $value->total_seat - ($value->booked_seat+$value->hold_seat);
            $available_seat = $value->total_seat - ($value->booked_seat+$value->hold_seat);
            if($available_seat <= 0){
                $value->status = "Sold Out";
            }else{
                $value->status = "Open";
            }
        }
        if($request->page == ""){
            return view('marquee.index_turkey',compact('data'));
        }else{
            return response()->json($data);
        }
    }
}
