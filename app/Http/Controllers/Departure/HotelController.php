<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DepartureHotel;
use App\HotelCategory;
use App\DepartureDestination;
use App\Departure;
use Auth;

class HotelController extends Controller
{
   public function CreateHotel(Request $request)
   {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $departure = Departure::find($route_id);
        $dep_destinations = DepartureDestination::join('destinations', 'destinations.id', 'departure_destinations.destination_id')
                        ->select('destinations.id', 'destinations.dest_name')
                        ->where('departure_destinations.departure_id', $route_id)
                        ->get();
        $hotel_categories = HotelCategory::all();
        $departure_hotel = DepartureHotel::join('destinations', 'destinations.id', 'departure_hotels.destination_id')
                            ->join('hotel_categories', 'hotel_categories.id', 'departure_hotels.hotel_category')
                            ->select('departure_hotels.*', 'destinations.dest_name as destinationName', 'hotel_categories.hotel_category')->where('departure_hotels.departure_id', $route_id)->get();
        if(count($departure_hotel) > 0){
            if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
            {
              return view('hotel.create',compact('departure', 'dep_destinations','hotel_categories', 'departure_hotel'));
            }
            else{
                return view('404');
            }
        }
        else
        {
            return view('hotel.create',compact('departure', 'dep_destinations','hotel_categories','departure_hotel'));
        }
    }
    public function HotelStore(Request $request, $id){
        $user = auth()->user();
        $hotel = new DepartureHotel();
        $hotel->name = $request->hotel_name;
        $hotel->departure_id = $id;
        $hotel->destination_id = $request->destinations;
        $hotel->hotel_category = $request->categories;
        $hotel->total_room = $request->total_room;
        $hotel->user_id = $user->id;
        $hotel->tenant_id = $user->tenant_id;
        $hotel->save();

        $departure = Departure::where('id',$id)->first();
        $total_room = Departure::find($id);
        if($request->total_room > $departure->total_room){
            $total_room->total_room = $request->total_room;
        }
        $total_room->save();

        $dep_destinations = DepartureDestination::join('all_destinations', 'all_destinations.id', 'departure_destinations.destination_id')
                        ->select('all_destinations.id', 'all_destinations.destination')
                        ->where('departure_destinations.departure_id', $id)
                        ->get();
        $hotel_categories = HotelCategory::all();
        $departure_hotel = DepartureHotel::join('destinations', 'destinations.id', 'departure_hotels.destination_id')
                            ->join('hotel_categories', 'hotel_categories.id', 'departure_hotels.hotel_category')
                            ->select('departure_hotels.*', 'destinations.dest_name as destinationName', 'hotel_categories.hotel_category')->where('departure_hotels.departure_id', $id)->get();
        return view('hotel.create',compact('departure', 'dep_destinations','hotel_categories', 'departure_hotel'));
    }
    public function HotelUpdate(Request $request, $id){

        $id = request()->route('id');
        $hotel =Departurehotel::where('id',$request->hotel_id)->first();
        $hotel->destination_id = $request->destinations;
        $hotel->hotel_category = $request->categories;
        $hotel->total_room = $request->total_room; 
        $hotel->name = $request->hotel_name;
        $hotel->save();
        $last_depId = $hotel->departure_id;
        $departure = Departure::where('id',$last_depId)->first();
        $total_room = Departure::find($departure->id);
        if($request->total_room > $departure->total_room){
            $total_room->total_room = $request->total_room;
        }
        $total_room->change_status = 1;
        $total_room->save();
        return response()->json($hotel);  
    }

    public function HotelDelete(Request $request, $id){

        $hotel =Departurehotel::where('id',$id)->delete();
        return redirect()->back(); 
    }
}
