<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Price;
use App\CountryDeparture;
use App\AllAirline;
use App\HotelCategory;
use App\AllDestination;
use App\HoldDeparture;
use App\HoldTill;
use App\HoldDuration;
use App\HoldDepartureSeat;
use App\BookDeparture;
use App\Itinerary;
use App\CurrencyConversion;
use App\DepartureFlightDetail;
use App\ReturnFlightDetail;
use App\UserDestination;
use App\PaymentSchedule;
use App\CancelSchedule;
use App\DeparturePrice;
use App\DepartureTag;
use App\DepartureType;
use App\Inclusion;
use App\AgentItinerarie;
use App\DepartureColumnType;
use App\DepartureBookingPriceUpdates;
use App\Exports\UsersExport;
use App\Exports\BuyerExport;
use App\DepartureHotel;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use App\Notification;
use App\Traits\FireBaseNotification;
use Illuminate\Http\Client\ResponseSequence;

class CountryListController extends Controller
{
    public function countryList()
    {
        $departure_id = Departure::where('status',1)
                ->where('approve',1)
                ->pluck('id')
                ->toArray();
        $country_id = CountryDeparture::whereIn('departure_id',$departure_id)
                ->distinct()
                ->pluck('country_id')
                ->toArray();

        $countryl=DB::table('countries')->whereIn('id', $country_id)
                 ->paginate(25); 
        
        if(auth()->user()->tenant_id == 'jwkpdcqmlez1648799148'){
            return view('country.country_list',compact('countryl'));
        }else{
            return view('404');
        }
    }
    public function metatagUpdate(Request $request)
    {
        $data = $request->all();
        
        $countryid = $request->edit_id;
        $meta_title = $request->meta_title;
        $meta_keywords = $request->meta_keywords;
        $meta_description = $request->meta_description;
    //    dd($data);
        $country = Country::find($countryid);
        $country->meta_title = $meta_title;
        $country->meta_keywords = $meta_keywords;
        $country->meta_description = $meta_description;
        $country->save();
        return redirect()->back();
    }

}
