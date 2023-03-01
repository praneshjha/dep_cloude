<?php

namespace App\Http\Controllers\ApiApp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use App\Http\Controllers\ApiApp\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
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
use App\DepartureColumnType;
use App\Inclusion;
use App\AgentItinerarie;
use App\DepartureBookingPriceUpdates;
use App\DepartureType;
use App\Exports\UsersExport;
use App\Exports\BuyerExport;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use App\Notification;
use App\Traits\FireBaseNotification;

class DepartureController extends BaseController
{ 
    use FireBaseNotification;

    public function myDeparture(Request $request)
    {
        if(Auth::user()->main_user_type == 1)
        {
           $keyword = $request->keyword;
           $status = $request->status;
           $permission = User::getPermissions();
           $date = date("Y-m-d");
           $item_per_page = 15;
            if (is_null($request->page) || $request->page == 1) {
                $current_page = 1;
                $offset = ($current_page-1)* $item_per_page;
            }else {
                $current_page = $request->page;
                $offset = ($current_page-1)* $item_per_page;
            }
            $departures = Departure::where('tenant_id',auth()->user()->tenant_id)
                   ->where(function ($query) use($request){
                       if($request->keyword != ''){
                           $query->where('title', 'LIKE','%'.$request->keyword.'%')
                               ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                               ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                               ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                               ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%');
                       }
                       if($request->status == 5){
                         $query->where('status',1)
                               ->where('approve',1)
                               ->where('available_seat','>',0)
                               ->whereDate('start_date', '>=', date("Y-m-d"));
                               
                       }
                       if($request->status == 4){
                         $query->where('status',1)
                               ->where('approve',1)
                               ->where('available_seat',0)
                               ->whereDate('start_date', '>=', date("Y-m-d"));
                               
                        }
                        if($request->status == 3){
                            $query->where('status',0)
                                ->whereDate('start_date', '>=', date("Y-m-d"));
                                
                        }
                        if($request->status == 2){
                         $query->where('status',1)
                               ->where('approve',0)
                               ->whereDate('start_date', '>=', date("Y-m-d"));
                               
                        }
                        if($request->status == 1){
                         $query->whereDate('start_date', '<', date("Y-m-d"));
                        }
                    })
                ->orderBy('created_at', 'DESC')
                ->distinct()
                ->offset($offset)
                ->limit($item_per_page)
                ->get();
            $departureT = Departure::where('tenant_id',auth()->user()->tenant_id)
                    ->get();
            $bTotal = count($departureT);
            $pageB = $bTotal/$item_per_page;
            $bookTotal = ceil($pageB);  
            if(count($departures)>0){
                foreach ($departures as $key => $value) {

                    $book = BookDeparture::where('departure_id',$value->id)
                        ->where('status',1)
                        ->sum('booked_seat');
                    $single_book = BookDeparture::where('departure_id',$value->id) 
                               ->sum('single_supplement_booked_seat');
                    $prices = DeparturePrice::where('departure_id',$value->id)->first();
                    $value->price = (isset($prices->price))?$prices->price:'NA';
                    $value->currency_symbol = (isset($prices->currency_symbol))?$prices->currency_symbol:'NA';
                    $value->currency_code = (isset($prices->currency_code))?$prices->currency_code:'NA';
                    if($book){
                       $value->book_sum = $book;
                    }
                    else{
                       $value->book_sum = 0;
                    }
                    if($single_book){
                       $value->single_book_sum = $single_book;
                    }
                    else{
                       $value->single_book_sum = 0;
                    }
   
                    // Inclusions
                    $inclu_icons = Inclusion::where('departure_id',$value->id)->where('icon','!=', null)->select('icon','name')->get();
                    foreach ($inclu_icons as $key => $inclu_icon) {
                       $inclu_icon->icon = url('inclusion-images').'/'.$inclu_icon->icon;
                    }
                    $value->inclusion_icons = $inclu_icons;
                    if($request->status != ""){
                        if($value->start_date >= $date && $value->approve == 1 && $value->status == 1 && $value->available_seat >= 1 && $request->status == 5){
                            $value->departure_status = "Open";
                        }
                        elseif($value->start_date >= $date && $value->approve == 1 && $value->status == 1 && $value->available_seat >= 0 && $request->status == 4){
                            $value->departure_status = "Sold Out";
                        }
                        elseif($value->start_date >= $date && $value->status == 0 && $request->status == 3){
                            $value->departure_status = "Draft";
                        }
                        elseif($value->start_date >= $date && $value->approve == 0 && $value->status == 1 && $request->status == 2){
                            $value->departure_status = "Under Review";
                        }
                        elseif($value->start_date < $date && $request->status == 1){
                            $value->departure_status = "Close";
                        }
                    }else{
                        if($value->start_date >= $date && $value->approve == 1 && $value->status == 1 && $value->available_seat >= 1){
                            $value->departure_status = "Open";
                        }
                        elseif($value->start_date >= $date && $value->approve == 1 && $value->status == 1 && $value->available_seat <= 0){
                            $value->departure_status = "Sold Out";
                        }
                        elseif($value->start_date >= $date && $value->status == 0){
                            $value->departure_status = "Draft";
                        }
                        elseif($value->start_date >= $date && $value->approve == 0 && $value->status == 1){
                            $value->departure_status = "Under Review";
                        }
                        elseif($value->start_date < $date){
                            $value->departure_status = "Close";
                        }
                    }
                    
                    // booking and price

                    $book = BookDeparture::where('departure_id',$value->id)
                        ->where('status',1)
                        ->sum('booked_seat');
                    $single_book = BookDeparture::where('departure_id',$value->id) 
                               ->sum('single_supplement_booked_seat');
                    $prices = DeparturePrice::where('departure_id',$value->id)->first();
                    $value->price = (isset($prices->price))?$prices->price:'NA';
                    $value->currency_symbol = (isset($prices->currency_symbol))?$prices->currency_symbol:'NA';
                    $value->currency_code = (isset($prices->currency_code))?$prices->currency_code:'NA';
                    if($book){
                       $value->book_sum = $book;
                    }
                    else{
                       $value->book_sum = 0;
                    }
                    if($single_book){
                       $value->single_book_sum = $single_book;
                    }
                    else{
                       $value->single_book_sum = 0;
                    }

                    $hold = HoldDepartureSeat::where('departure_id',$value->id) 
                           ->sum('hold_seat');
                    if($hold){
                        $value->hold_sum = $hold;
                    }
                    else{
                        $value->hold_sum = 0;
                    }
                    if(($value->total_seat)-($value->hold_sum + $value->book_sum) > 0){
                        $available_units = ($value->total_seat)-($value->hold_sum + $value->book_sum);
                    }
                    else{
                        $available_units = 0;
                    }
                    $value->available_units = $available_units;

                    $hold_till = DB::table('hold_departures')->where('departure_id', $value->id)->first();
                    if ($hold_till) {
                        $holds = $hold_till->hold_till;
                    } else {
                        $holds = 0;
                    }

                    //dd($holds);
                    $today = date("Y-m-d");
                    $date1 = date_create($today);
                    $date2 = date_create($value->start_date);
                    $diff = date_diff($date1, $date2);
                    $date = $diff->format("%R%a");   
                    if($holds < $date){
                        $value->holdShow = 'Yes';
                    }else{
                        $value->holdShow = 'No';
                    }  
                }
            }
            if(count($departures)>0){
                $response = [
                    'error' => false,
                    'departures' => $departures,
                    'hold' => $hold,
                    'date' => $date,
                    'permission' => $permission,
                    'keyword' => $keyword,
                    'status' => $status,
                    'total' => $bookTotal,
                    'page' => $request->page,
                ];
            }else{
                $response = [
                    'error' => false,
                    'departures' => [],
                    'hold' => 0,
                    'date' => "",
                    'permission' => $permission,
                    'keyword' => $keyword,
                    'status' => $status,
                    'total' => 0,
                    'page' => "",
                ];
            }
            return response()->json($response, 200);
        }
    }

    public function allDeparture(Request $request)
    {
        $item_per_page = 10;
        if (is_null($request->page) || $request->page == 1) {
            $current_page = 1;
            $offset = ($current_page-1)* $item_per_page;
        }else {
            $current_page = $request->page;
            $offset = ($current_page-1)* $item_per_page;
        }

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $date = date("Y-m-d");
        $from = $request->departure_from;
        $to = $request->departure_to;
        $status_filter =$request->status;
        $publisher =$request->publiser_name;
      
        //from searchbox
        $search_key = $request->keyword; 
        $req_type = $request->type;
        $total_page=0;
        if($request->type != null){
            if($request->type == 15){
                $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from_date || $request->to_date || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures15 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keyword != ''){
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                            if($request->from_date != '' || $request->to_date != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d",  strtotime($request->to_date)))])
                                    ->whereDate('start_date', '>=', date("Y-m-d"));
                            }
                            if($request->departure_from != '' || $request->departure_to){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('from',$request->departure_from)
                                    ->where('ending_at',$request->departure_to);
                            }
                            if($request->status == 1){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where('available_seat','>',0);
                            }
                            if($request->status == 2){
                              $query->whereDate('start_date', '<=', date("Y-m-d"))
                                ->where('available_seat','<=',0);
                            }
                            if($request->publiser_name != null){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->whereIn('company_name',$request->publiser_name);
                            }
                    })
                    ->pluck('id')->toArray();
                }
                $departures15 = [];
                $dep_all = array_merge($departures15t,$departures15);
                $departure_unique = array_unique($dep_all);
                // $total_departure = Departure::whereIn('id',$departure_unique)
                //     ->orderBy('start_date', 'ASC')->count();
                // $total_page=$total_departure/45;
                // $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
                    ->orderBy('start_date', 'ASC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
            }
            if($request->type == 13){
                $departures13 = [];
                //$departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                    // ->where('status',1)
                    // ->where('approve',1)
                    // ->whereDate('start_date', '>=', $date)
                    // ->pluck('id')->toArray();
                   // dd($departures13t);
                if($request->keyword || $request->from_date || $request->to_date || $request->departure_from || $request->departure_to || $request->status || $request->publiser_name){
                    
                    $departures13 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keyword != ''){
                                $query->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                            if($request->from_date != '' || $request->to_date != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d", strtotime($request->to_date)))])
                                ->whereDate('start_date', '>=', date("Y-m-d"));
                            }
                            if($request->departure_from != '' || $request->departure_to){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('from',$request->departure_from)
                                    ->orWhere('ending_at',$request->departure_to);
                            }
                            if($request->status == 1){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where('available_seat','>',0);
                            }
                            if($request->status == 2){
                              $query->whereDate('start_date', '<=', date("Y-m-d"))
                                ->where('available_seat','<=',0);
                            }
                            if($request->publiser_name != null){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->whereIn('company_name',$request->publiser_name);
                            }
                    })
                    ->pluck('id')->toArray();
                
                }
                
                //$dep_all = array_merge($departures13t,$departures13);
                //$departure_unique = array_unique($departures13);

                // $total_departure = Departure::whereIn('id',$departure_unique)
                //     ->orderBy('start_date', 'ASC')->count();
                // $total_page=$total_departure/45;
                // $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departures13)
                    ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
                    ->orderBy('start_date', 'ASC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
                    // echo "<pre>";
                    // print_r($departures);
                    // die;
            }

            if($request->type == 11){
                $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                $departures11t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                    
                if($request->keyword || $request->from_date || $request->to_date || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures11 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keyword != ''){
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                            if($request->from_date != '' || $request->to_date != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d", strtotime($request->to_date)))])
                                ->whereDate('start_date', '>=', date("Y-m-d"));
                            }
                            if($request->departure_from != '' || $request->departure_to){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('from',$request->departure_from)
                                    ->orWhere('ending_at',$request->departure_to);
                            }
                            if($request->status == 1){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where('available_seat','>',0);
                            }
                            if($request->status == 2){
                              $query->whereDate('start_date', '<=', date("Y-m-d"))
                                ->where('available_seat','<=',0);
                            }
                            if($request->publiser_name != null){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->whereIn('company_name',$request->publiser_name);
                            }
                    })
                    ->pluck('id')->toArray();
                }else{
                    $departures11 = [];
                }

                $dep_all = array_merge($departures11t,$departures11);
                $departure_unique = array_unique($dep_all);

                // $total_departure = Departure::whereIn('id',$departure_unique)
                //     ->orderBy('start_date', 'ASC')->count();
                // $total_page=$total_departure/45;
                // $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
                    ->orderBy('start_date', 'ASC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
                //dd($departures);
            }
            if($request->type == 12){
                $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                $departures12t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from_date || $request->to_date || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures12 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keyword != ''){
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                            if($request->from_date != '' || $request->to_date != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d", strtotime($request->to_date)))])
                                ->whereDate('start_date', '>=', date("Y-m-d"));
                            }
                            if($request->departure_from != '' || $request->departure_to){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('from',$request->departure_from)
                                    ->orWhere('ending_at',$request->departure_to);
                            }
                            if($request->status == 1){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where('available_seat','>',0);
                            }
                            if($request->status == 2){
                              $query->whereDate('start_date', '<=', date("Y-m-d"))
                                ->where('available_seat','<=',0);
                            }
                            if($request->publiser_name != null){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->whereIn('company_name',$request->publiser_name);
                            }
                    })
                    ->pluck('id')->toArray();
                }
                $departures12 = [];
                $dep_all = array_merge($departures12t,$departures12);
                $departure_unique = array_unique($dep_all);
                
                // $total_departure = Departure::whereIn('id',$departure_unique)
                //     ->orderBy('start_date', 'ASC')->count();
                // $total_page=$total_departure/45;
                // $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
                    ->orderBy('start_date', 'ASC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
            }
            if($request->type == 14){
                $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                $departures14t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from_date || $request->to_date || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures14 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keyword != ''){
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                            if($request->from_date != '' || $request->to_date != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d", strtotime($request->to_date)))])
                                ->whereDate('start_date', '>=', date("Y-m-d"));
                            }
                            if($request->departure_from != '' || $request->departure_to){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('from',$request->departure_from)
                                    ->orWhere('ending_at',$request->departure_to);
                            }
                            if($request->status == 1){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where('available_seat','>',0);
                            }
                            if($request->status == 2){
                              $query->whereDate('start_date', '<=', date("Y-m-d"))
                                ->where('available_seat','<=',0);
                            }
                            if($request->publiser_name != null){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->whereIn('company_name',$request->publiser_name);
                            }
                    })
                    ->pluck('id')->toArray();
                }
                $departures14 = [];
                $dep_all = array_merge($departures14t,$departures14);
                $departure_unique = array_unique($dep_all);
                
                // $total_departure = Departure::whereIn('id',$departure_unique)
                //     ->orderBy('start_date', 'ASC')->count();
                // $total_page=$total_departure/45;
                // $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
                    ->orderBy('start_date', 'ASC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
            }
        }elseif(($request->type == "" && $request->keyword =="") && ($request->from_date || $request->to_date || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name)){
                    $departuresNull = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->from_date != '' || $request->to_date != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d", strtotime($request->to_date)))])
                                ->whereDate('start_date', '>=', date("Y-m-d"));
                            }
                            if($request->departure_from != '' || $request->departure_to){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where('from',$request->departure_from)
                                    ->orWhere('ending_at',$request->departure_to);
                            }
                            if($request->status == 1){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where('available_seat','>',0);
                            }
                            if($request->status == 2){
                              $query->whereDate('start_date', '<=', date("Y-m-d"))
                                ->where('available_seat','<=',0);
                            }
                            if($request->publiser_name != null){
                              $query->whereDate('start_date', '>=', date("Y-m-d"))
                                ->whereIn('company_name',$request->publiser_name);
                            }
                    })
                    ->pluck('id')->toArray();

                // $total_departure = Departure::whereIn('id',$departuresNull)
                //     ->orderBy('start_date', 'ASC')->count();
                // $total_page=$total_departure/45;
                // $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departuresNull)
                    ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
                    ->orderBy('start_date', 'ASC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
            //dd($departures);
        }else{

            $departures = Departure::where('approve',1)
            ->where('status',1)
            ->whereDate('start_date', '>=', date("Y-m-d"))
            ->select('id', 'title', 'company_name', 'dep_id', 'start_date', 'end_date', 'no_of_nights', 'no_of_days', 'from', 'ending_at','tenant_id', 'total_seat','status', 'flag_status')
            ->orderBy('start_date', 'ASC')
            ->offset($offset)
            ->limit($item_per_page)
            ->get();
        }
        $date = date("Y-m-d");
        $departuresT = Departure::where('status',1)
                ->where('approve',1)
                ->whereDate('start_date', '>=', date("Y-m-d"))
                ->get();
        $pkgTotald = count($departuresT);
        $pagep = $pkgTotald/$item_per_page;
        $pkgTotal = ceil($pagep);
        if(count($departures)>0){
            foreach ($departures as $key => $value) {
                $hold = HoldDepartureSeat::where('departure_id',$value->id) 
                        ->sum('hold_seat');
                if($hold){
                    $value->hold_sum = $hold;
                }
                else{
                    $value->hold_sum = 0;
                }

                $book = BookDeparture::where('departure_id',$value->id) 
                        ->where('status',1)->sum('booked_seat');
                        $single_book = BookDeparture::where('departure_id',$value->id) 
                            ->sum('single_supplement_booked_seat');
                if($book){
                    $value->book_sum = $book;
                }
                else{
                    $value->book_sum = 0;
                }
                if($single_book){
                    $value->single_book_sum = $single_book;
                }
                else{
                    $value->single_book_sum = 0;
                }
                // Inclusion icons
                $inclu_icons = Inclusion::where('departure_id',$value->id)->where('icon','!=', null)->select('icon','name')->get();
                foreach ($inclu_icons as $key => $inclu_icon) {
                    $inclu_icon->icon = url('inclusion-images').'/'.$inclu_icon->icon;
                }
                $value->inclusion_icons = $inclu_icons;
            }
        }
        else{
            $columns = [];
        }
       
        $release = HoldDepartureSeat::all();
        foreach($release as $row){
            $now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
            if($now >= $row->auto_release){    
                HoldDepartureSeat::find($row->id)->delete();
            }
        }
        foreach($departures as $row)
        {
            $users = User::where(['tenant_id'=>$row->tenant_id, 'main_user_type'=>1,'user_type'=>0])->first();

            $prices = DeparturePrice::where('departure_id',$row->id)->first();
            $row->price = $prices->price;
            $row->currency_symbol = $prices->currency_symbol;
            $row->currency_code = $prices->currency_code;
            $row->start_date = date('d-M-Y', strtotime($row->start_date));

            $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
             $strlower = Str::lower($removeSC);
             $arr_cn = explode(' ', $strlower);
             $str_cn = implode('-', $arr_cn);
             $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
            $mainstr = rtrim($mainstrs, '-');
            $row->company_url = $mainstr;
          
            if(($row->total_seat)-($row->hold_sum + $row->book_sum) > 0){
                $available_units = ($row->total_seat)-($row->hold_sum + $row->book_sum);
                $status = 'OPEN';
            }
            else{
                $available_units = 0;
                $status = 'SOLDOUT';
            }
            $row->available_units = $available_units;
            $row->departure_status = $status;


            $hold_till = DB::table('hold_departures')->where('departure_id', $row->id)->first();
            if ($hold_till) {
                $holds = $hold_till->hold_till;
            } else {
                $holds = 0;
            }

            //dd($holds);
            $today = date("Y-m-d");
            $date1 = date_create($today);
            $date2 = date_create($row->start_date);
            $diff = date_diff($date1, $date2);
            $date = $diff->format("%R%a");   
            if($holds < $date){
                $row->holdShow = 'Yes';
            }else{
                $row->holdShow = 'No';
            }  
        }
        if(count($departures)>0){   
            $status = array(
                'error' => false,
                'departures' => $departures,
                'page' => $request->page,
                'total'=> $pkgTotal,
                
            );
        }else{
            $status = array(
                'error' => false,
                'departures' => [],
                'page' => '',
                'total' => 0,
            );
        }
        return Response($status,200);    
    }

    public function myBooking(Request $request){
        $end_date = $request->end_date;
        $start_date = $request->start_date;
        $departure_name = $request->departure_id;
        //$departure_to = $request->departure_to;
        //$departure_from = $request->departure_from;
        $departure_owner = $request->departure_owner;
        $pagePP = $request->page;
        //Pagination
        $item_per_page = 10;
        if (is_null($request->page) || $request->page == 1) {
            $current_page = 1;
            $offset = ($current_page-1)* $item_per_page;
        }else {
            $current_page = $request->page;
            $offset = ($current_page-1)* $item_per_page;
        }

        $user = User::find(Auth::user()->id);
        
        $bookcount = DB::table('book_departures')
                    ->where('user_id',Auth::user()->id)
                    ->select('unique_id')
                    ->distinct()
                    ->pluck('unique_id')
                    ->toArray();
        if($start_date || $departure_name || $end_date || $departure_owner)
        {
            $mybook = DB::table('book_departures')
                ->whereIn('unique_id',$bookcount)
                ->where(function ($query) use($request){
                    if($request->departure_owner != ''){
                        $query->where('tenant_id',$departure_owner);
                    }
                    if($request->start_date != '' || $request->end_date != ''){
                        $query->whereBetween('date',[$request->start_date,$request->end_date]);
                    }
                    if($request->departure_id != ''){
                        $query->where('departure_id',$request->departure_id);
                    }
                })
                ->distinct()
                ->orderBy('unique_id','DESC')
                ->offset($offset)
                ->limit($item_per_page)
                ->get();
        }else{
           $mybook = DB::table('book_departures')
                ->whereIn('unique_id',$bookcount)
                ->select('unique_id')
                ->distinct()
                ->orderBy('unique_id','DESC')
                ->offset($offset)
                ->limit($item_per_page)
                ->get();
        }  
        $bTotal = count($bookcount);
        $pageB = $bTotal/$item_per_page;
        $bookTotal = ceil($pageB); 
        foreach($mybook as $bd)
        { 
            $booked_seat = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->sum('booked_seat');
            $bd->booked_seat = $booked_seat;
    
            $price = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->sum('price');
            $bd->price = $price;

            $bd_currency = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
            $bd->currency_code = $bd_currency->currency_code;
            $bd->currency_symbol = $bd_currency->currency_symbol;
            
            $bookingDate = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
            $bd->bookingDate = date('d-M-Y', strtotime($bookingDate->created_at."+5 hours +30 minutes"));
            $bd->bookingTime = date('h:i A', strtotime($bookingDate->created_at."+5 hours +30 minutes"));
    
            $d_id = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
             
            $departure = Departure::where('id',$d_id->departure_id)->first();
            $bd->departure_name = $departure->title;
            $bd->company_name = $departure->company_name;
            $bd->no_of_nights = $departure->no_of_nights;
            $bd->no_of_days = $departure->no_of_days;
            $bd->from = $departure->from;
            $bd->ending_at = $departure->ending_at;
            $bd->dep_id = $departure->dep_id;
            $bd->id = $departure->id;
            $bd->tenant_id = $departure->tenant_id;
            if($departure->start_date > date("Y-m-d")){
                $bd->cancel = 'yes';
            }else{
                $bd->cancel = 'no';
            }
            
        }
        if(count($mybook)>0){
            $status = array(
                'error' => false,
                'book' => $mybook,
                'total' => $bookTotal,
                'page' => $request->page,
                'message' =>"Success"
                
            );
        }else{
            $status = array(
                'error' => false,
                'book' => [],
                'total' => 0,
                'page' => '',
                'message' =>"Data not found!" 
            );
        }
        return Response($status,200);
    }

    public function deleteDestination(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destination_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $deleted = UserDestination::where('id',$request->destination_id)->delete();
        
        $response = [
            'error' => false,
            'message' => 'Destination deleted succesfully!',
        ];
        return response()->json($response, 200);
        
    }

    public function departureDetails($id){
        $departure_details = Departure::where('id',$id)->first();
        $log_img = DB::table('users')->where('tenant_id',$departure_details->tenant_id)->value('logo');
        $departure_details->logo_image = url('companyLogo').'/'.$log_img;
        if($departure_details){
        $company_details_all = User::where('tenant_id',$departure_details->tenant_id)
                ->where(['role_id' => 0,'user_type' => 0, 'main_user_type' =>1])
                ->first();
        $contact_person_det = User::where('contact_person_id',$departure_details->contact_person_id)->select('mobile','email')
            ->first();
        $departures = DB::table('departures')->where('dep_type','main')
                 ->where('tenant_id',auth()->user()->tenant_id)
                 ->orderBy('id', 'DESC')
                 ->get();
                $hold_till = DB::table('hold_departures')->where('departure_id',$id)->first();
                if($hold_till){           
                    $hold = $hold_till->hold_till;
                }          
            
  
               $today = date("Y-m-d");
               $date1=date_create($today);
               $date2=date_create( $departure_details->start_date);
               $diff=date_diff($date1,$date2);
               $date = $diff->format("%R%a"); 

              
                    $hold = HoldDepartureSeat::where('departure_id',$departure_details->id) 
                            ->sum('hold_seat');
                    if($hold){
                          $departure_details->hold_sum = $hold;
                    }
                    else{
                          $departure_details->hold_sum = 0;
                    }
                    $extra_hold = HoldDepartureSeat::where('departure_id',$departure_details->id)->where('extra_hold_seat','!=',0) 
                            ->sum('extra_hold_seat');
                    if($hold){
                          $departure_details->extra_hold_sum = $extra_hold;
                    }
                    else{
                          $departure_details->extra_hold_sum = 0;
                    }
                    
                    
            
                    $book = BookDeparture::where('departure_id',$departure_details->id) 
                            ->sum('booked_seat');
                    if($book){
                        $departure_details->book_sum = $book;
                    }
                    else{
                        $departure_details->book_sum = 0;
                    }
                    $single_book = BookDeparture::where('departure_id',$departure_details->id) 
                    ->sum('single_supplement_booked_seat');
                    if($single_book){
                       $departure_details->single_book_sum = $single_book;
                    }
                    else{
                        $departure_details->single_book_sum = 0;
                    }
                      

         $itinerary = AgentItinerarie::where('departure_id',$id)
                                            ->first();
         $inclusion = DB::table('departures')
                     ->join('inclusions','inclusions.departure_id','departures.id')
                     ->select('inclusions.*')
                     ->where('inclusions.departure_id',$id)
                     ->get();

         foreach($inclusion as $inc){
             $inc->icon = url('inclusion-images').'/'.$inc->icon;
         }
        // Itinerary
        $daySchedule = Itinerary::where('departure_id',$id)
            ->select('day_number','day_heading','description','destinations')
            ->get();
        foreach ($daySchedule as $key => $daySch) {
            $destId = explode(',',$daySch->destinations);
            $destinations = Destination::whereIn('id', $destId)
                    ->select('dest_name as destination_name')->get();
            $daySch->destinations = $destinations;
        }      
        $user_hold = DB::table('hold_departure_seats')
                     ->join('users','hold_departure_seats.user_id','users.id')
                     ->select('hold_departure_seats.*','users.company_name')
                     ->where('departure_id', $id)
                     ->get();  
        $extra_hold = DB::table('hold_departure_seats')
                     ->join('users','hold_departure_seats.user_id','users.id')
                     ->select('hold_departure_seats.*','users.company_name')
                     ->where('departure_id', $id)
                     ->where('extra_hold_seat','>',0)
                     ->get();  
        $count=count($user_hold); 
       
        $departure_destination = DB::table('departure_destinations')
                               ->join('destinations','departure_destinations.destination_id','destinations.id')
                               ->select('destinations.country_name','destinations.dest_name')
                               ->where('departure_destinations.departure_id',$departure_details->id)
                               ->get();
        $price = DB::table('prices')->where('departure_id',$departure_details->id)->first();
        $price1 = DB::table('prices')->where('departure_id',$departure_details->id)->skip(1)->take(1)->first();
        $price2 = DB::table('prices')->where('departure_id',$departure_details->id)->skip(2)->take(1)->first();
        $price3 = DB::table('prices')->where('departure_id',$departure_details->id)->skip(3)->take(1)->first();
        $book_date = DB::table('book_departures')
                        ->where('departure_id',$departure_details->id)
                        ->select('departure_id','user_id','created_at','unique_id')
                        ->distinct()
                        ->orderBy('created_at','DESC')
                        ->paginate(10);
        foreach($book_date as $bd)
            {

                $booked_seat = BookDeparture::where('departure_id',$bd->departure_id)->where('user_id',$bd->user_id)->sum('booked_seat');
                $bd->booked_seat = $booked_seat;

                $price = BookDeparture::where('user_id',$bd->user_id)->where('departure_id',$bd->departure_id)->sum('price');

                $bd->price = $price;

                $bd_currency = BookDeparture::where('user_id',$bd->user_id)->where('departure_id',$bd->departure_id)->first();
                $bd->currency = $bd_currency;

             
                $dep_name = Departure::where('id',$bd->departure_id)->value('title');
                $bd->departure_name = $dep_name;

                $currency_symbol = Departure::where('id',$bd->departure_id)->value('currency_symbol');
                $bd->currency_symbol = $currency_symbol;

                $company_name = Departure::where('id',$bd->departure_id)->value('company_name');
                $bd->company_name = $company_name;

                $user_name = User::where('id',$bd->user_id)
                            ->value('name');
                $bd->name = $user_name;

            }
        $hold_date = DB::table('hold_departure_seats')
                    ->where('departure_id',$departure_details->id)
                    ->select('departure_id','user_id','created_at','unique_id')
                    ->distinct()
                    ->orderBy('created_at','DESC')
                    ->paginate(10);
        foreach($hold_date as $bd)
        {

                $booked_seat = DB::table('hold_departure_seats')
                        ->where('departure_id',$bd->departure_id)->sum('hold_seat');
                $bd->booked_seat = $booked_seat;

                $price =DB::table('hold_departure_seats')
                        ->where('user_id',$bd->user_id)->where('departure_id',$bd->departure_id)->sum('price');

                $bd->price = $price;

                $bd_currency = DB::table('hold_departure_seats')
                        ->where('user_id',$bd->user_id)->where('departure_id',$bd->departure_id)->first();
                $bd->currency = $bd_currency;

             
                $dep_name = Departure::where('id',$bd->departure_id)->value('title');
                $bd->departure_name = $dep_name;

                $currency_symbol = Departure::where('id',$bd->departure_id)->value('currency_symbol');
                $bd->currency_symbol = $currency_symbol;

                $company_name = Departure::where('id',$bd->departure_id)->value('company_name');
                $bd->company_name = $company_name;

                $user_name = User::where('id',$bd->user_id)
                            ->value('name');
                $bd->name = $user_name;

        }
       $departure_book = DB::table('book_departures')
            ->join('users','book_departures.user_id','users.id')
            ->join('departures','departures.id','book_departures.departure_id')
            ->select('book_departures.*','users.company_name','users.name','departures.price_inr','departures.price_usd','single_supplyment_price_usd','single_supplyment_price_inr')
            ->where('departure_id',$id)->get();
        $departure_prices = DeparturePrice::where('departure_id',$departure_details->id)->where('status', 1)->get();
        foreach($departure_prices as $dep_price){
            $dep_price->transport_type = ($dep_price->transport_type != null || $dep_price->transport_type != '')?$dep_price->transport_type:'NA';
            $dep_price->meal_type = ($dep_price->meal_type != null || $dep_price->meal_type != '')?$dep_price->meal_type:'NA';
            $dep_price->hotel_type = ($dep_price->hotel_type != null || $dep_price->hotel_type != '')?str_replace(" Star","",$dep_price->hotel_type):'NA';
            $dep_price->group_size = ($dep_price->group_size != null || $dep_price->group_size != '')?$dep_price->group_size:'NA';
            $dep_price->price = ($dep_price->price != null || $dep_price->price != '')?$dep_price->price:'NA';
            $dep_price->sharing = ($dep_price->sharing != null || $dep_price->sharing != '')?$dep_price->sharing:'NA';
            $dep_price->airport_transfers = ($dep_price->airport_transfers != null || $dep_price->airport_transfers != '')?$dep_price->airport_transfers:'NA';
            $dep_price->flight_class = ($dep_price->flight_class != null || $dep_price->flight_class != '')?$dep_price->flight_class:'NA';
            $dep_price->passenger = ($dep_price->passenger != null || $dep_price->passenger != '')?$dep_price->passenger:'NA';
        }

        $originating = DepartureFlightDetail::where('departure_id',$departure_details->id)->get();
        $returning = ReturnFlightDetail::where('departure_id',$departure_details->id)->get();
        $indian_currency=CurrencyConversion::first();
        $inr = $indian_currency->indian_currency;
        $payment_schedule = PaymentSchedule::where('departure_id',$departure_details->id)->get();
        $total_price = PaymentSchedule::where('departure_id',$departure_details->id)->sum('price');
        $total_percentage = PaymentSchedule::where('departure_id',$departure_details->id)->sum('percentage');
        $total_single = PaymentSchedule::where('departure_id',$departure_details->id)->sum('single_supplement');
        $total_cost = PaymentSchedule::where('departure_id',$departure_details->id)->sum('total');
         $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $departure_details->departure_ownner);
         $strlower = Str::lower($removeSC);
         $arr_cn = explode(' ', $strlower);
         $str_cn = implode('-', $arr_cn);
         $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
        $mainstr = rtrim($mainstrs, '-');
        $company_url = $mainstr;
         
        $cancelation_schedule = CancelSchedule::where('departure_id',$departure_details->id)->get();

        // Last Updated Departure
        $last_updated = DB::table('departures')->where('id',$departure_details->id)->value('updated_at');
        $last_updated1 = DB::table('inclusions')->where('departure_id',$departure_details->id)->value('updated_at');
        $last_updated2 = DB::table('return_flight_details')->where('departure_id',$departure_details->id)->value('updated_at');
        $last_updated3 = DB::table('departure_flight_details')->where('departure_id',$departure_details->id)->value('updated_at');
        $last_updated4 = DB::table('agent_itineraries')->where('departure_id',$departure_details->id)->value('updated_at');
        $last_updated5 = DB::table('departure_prices')->where('departure_id',$departure_details->id)->value('updated_at');
        $last_updated6 = DB::table('payment_schedules')->where('departure_id',$departure_details->id)->value('updated_at');
        $last_updated7 = DB::table('cancel_schedules')->where('departure_id',$departure_details->id)->value('updated_at');

        $last_max = max($last_updated,$last_updated1,$last_updated2,$last_updated3,$last_updated4,$last_updated5,$last_updated6,$last_updated7);
        $departure_details->last_updated_dep = $last_max;
        $departure_details->available_seat = ($departure_details->available_seat != null || $departure_details->available_seat != '')?$departure_details->available_seat:$departure_details->total_seat;
        $departure_details->from = ($departure_details->from != null || $departure_details->from != '')?$departure_details->from:'NA';

        $status = array(
            'error' => false,
            'departure_details' => $departure_details,
            'itinerary' => $itinerary,
            'day_schedule'=>$daySchedule,
            'inclusion' => $inclusion,
            'user_hold' => $user_hold,
            'departure_destination' => $departure_destination,
            'price' => $price,
            'price1' => $price1,
            'price2' => $price2,
            'price3' => $price3,
            'hold' => $hold,
            'date' => $date,
            'count' => $count,
            'departure_book' => $departure_book,
            'hold_till' => $hold_till,
            'extra_hold' => $extra_hold,
            'originating' => $originating,
            'returning' => $returning,
            'inr' => $inr,
            'payment_schedule' => $payment_schedule,
            'total_price' => $total_price,
            'total_percentage' => $total_percentage,
            'total_single' => $total_single,
            'total_cost' => $total_cost,
            'departure_prices' => $departure_prices,
            'book_date' => $book_date,
            'hold_date' => $hold_date,
            'company_url' => $company_url,
            'cancelation_schedule' => $cancelation_schedule,
            'company_details_all' => $company_details_all,
            'contact_person_det' => $contact_person_det
        );
        return Response($status,200);    
        }
        else{
            $status = array(
                'error' => true
            );
            return Response($status,404);
        }
    }

   public function bookSeat(Request $request)
   {
        if(array_sum($request->required_units) > 0)
        {
            $dd_tId = Departure::where('id',$request->id)->value('tenant_id');
            $departure = Departure::find($request->id);

            $total = 0;
            $total_price = 0;
            $unique_id =time();

            foreach($request->required_units as $key=>$require_unit){
                $total = $total + $require_unit;
                $bk_dep = DeparturePrice::where('id', $request->book_dep_id[$key])->first();
                $total_price =$total_price + ($require_unit * $bk_dep->price);
                if($require_unit != '')
                {
                    $save = new BookDeparture;
                    $save->user_id = Auth::user()->id;
                    $save->tenant_id = Auth::user()->tenant_id;
                    $save->departure_id = $request->id;
                    $save->unique_id = $unique_id;
                    $save->sairing = $bk_dep->sairing;
                    $save->transport_type = $bk_dep->transport_type;
                    $save->hotel_type = $bk_dep->hotel_type;
                    $save->meal_plan = $bk_dep->meal_plan;
                    $save->group_size = $bk_dep->group_size;
                    $save->flight_class = $bk_dep->flight_class;
                    $save->passenger = $bk_dep->passenger;
                    $save->airport_transfers = $bk_dep->airport_transfers;
                    $save->lead_pasanger_name = $request->lead_pasanger_name;
                    $save->booked_seat = $require_unit;
                    $save->price = $bk_dep->price * $require_unit;
                    $save->currency_code = $bk_dep->currency_code;
                    $save->currency_symbol = $bk_dep->currency_symbol;
                    $save->note = $request->note;
                    $save->date = date("Y-m-d");
                    $save->save();
                }
            } 
            $book_details_mail = BookDeparture::where('unique_id',$unique_id)->get();
            $available = Departure::find($request->id);
            $available->available_seat = $request->remaining_units - $total;
            $available->save(); 
            $supplier_name = User::where('id',$available->contact_person_id)->first();
            $company_supplier = User::where('tenant_id',$available->tenant_id)->first();
            // Buyer details

            $noti_save = new Notification;
            $noti_save->title = 'Departure cloud - Departure Book';
        
            $noti_save->body = $total. ' units are booked for you in Departure for '.$available->ending_at.'. Deaparture Name: '. $available->title . ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' Supplier: ' .auth()->user()->name.' '.auth()->user()->last_name . ' No of units booked: ' . $total;

            $noti_save->body_html = '<p> <b> '.$total. ' </b>units are booked for you in Departure for '.$available->ending_at.' .</p><p><b>Deaparture Name:</b> ' . $available->title . ' </p><p><b>Departure Date: </b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>Supplier:</b> ' .auth()->user()->name.' '.auth()->user()->last_name . ' </p><p><b> No of units booked:</b><b> ' . $total . ' </b></p>';
            $noti_save->user_id = auth()->user()->id;
            $noti_save->type = "Book";
            $noti_save->url_1 = url("departures-details").'/'.$departure->id;
            $noti_save->save();
            $last_body_auth = $noti_save->body;
            $last_title_auth = $noti_save->title;
            $last_link_auth = $noti_save->url_1;

            //++++++++Notification send Code for Suplier++++++++++++//
            $firebaseTokenApp = User::where('id',$supplier_name->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotificationApp = $this->sendNotificationBuyerApp($firebaseTokenApp, $last_title_auth, $last_body_auth, "departure_details",$noti_save->id);
            $firebaseToken = User::where('id',$supplier_name->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationBuyer($firebaseToken, $last_title_auth, $last_body_auth, $last_link_auth,$noti_save->id);

            
            // Supplier details

            $noti_sup = new Notification;
            $noti_sup->title = "Departure cloud - Departure Book";
            $noti_sup->body = 'You have received request to book' .$total. 'units in your departure for '.$available->ending_at.'. Buyer Company Name: ' . auth()->user()->company_name . ' Deaparture Name: ' . $available->title . ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' No of units booked: ' . $total;
            $noti_sup->body_html = '<p>You have received request to book ' .$total. ' units in your departure for '.$available->ending_at.' .</p><p><b>Buyer Company Name:</b> ' . auth()->user()->company_name . ' </p><p><b>Deaparture Name:</b> ' . $available->title . ' </p><p><b>Departure Date:</b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>No of units booked:</b><b> ' . $total . ' </b></p>';
            $noti_sup->user_id = $supplier_name->id;
            $noti_sup->type = "Book";
            $noti_sup->url_1 = url("departures-details").'/'.$departure->id;
            $noti_sup->save();
            $last_body_sup = $noti_sup->body;
            $last_title_sup = $noti_sup->title;
            $last_link_sup =  $noti_sup->url_1;

            //++++++++Notification send Code for Suplier++++++++++++//
            $firebaseTokenApp = User::where('id',$supplier_name->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotification = $this->sendNotificationSupplierApp($firebaseTokenApp, $last_title_sup, $last_body_sup, "departure_details",$noti_sup->id);

            $firebaseToken = User::where('id',$supplier_name->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);

            $success = 'Departure Booked Successfully';
            $status = array(
                'error' => false,
                'message' => $success,
            );
            return Response($status,200);
        }
        else{
           
            return response()->json(['required'=>"Please enter required unit."]);
        }          
   }

    public function AllDepartureBookingHistory(Request $request)
    {
        $end_date = $request->end_date;
        $start_date = $request->start_date;
        $departure_name = $request->departure_id;
        //$departure_to = $request->departure_to;
        //$departure_from = $request->departure_from;
        $departure_owner = $request->departure_owner;
        $pagePP = $request->page;
        //Pagination
        $item_per_page = 10;
        if (is_null($request->page) || $request->page == 1) {
            $current_page = 1;
            $offset = ($current_page-1)* $item_per_page;
        }else {
            $current_page = $request->page;
            $offset = ($current_page-1)* $item_per_page;
        }
        $departure_id = Departure::where('tenant_id',Auth::user()->tenant_id)
                ->pluck('id')
                ->toArray();
        if($start_date || $departure_name || $end_date || $departure_owner)
        {
            $book = DB::table('book_departures')
                    ->where(function ($query) use($request){
                        if($request->departure_owner != ''){
                            $query->where('tenant_id',$departure_owner);
                        }
                        if($request->start_date != '' || $request->end_date != ''){
                            $query->whereBetween('date',[$request->start_date,$request->end_date]);
                        }
                        if($request->departure_id != ''){
                            $query->where('departure_id',$request->departure_id);
                        }
                    })
                    ->distinct()
                    ->select('unique_id')
                    ->orderBy('unique_id','DESC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
        }else{
           $book = DB::table('book_departures')
                    ->whereIn('departure_id',$departure_id)
                    ->select('unique_id')
                    ->distinct()
                    ->orderBy('unique_id','DESC')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();
        }      
        $bookT = DB::table('book_departures')
                ->whereIn('departure_id',$departure_id)
                ->select('unique_id')
                ->distinct()
                ->get();   
        $bTotal = count($bookT);
        $pageB = $bTotal/$item_per_page;
        $bookTotal = ceil($pageB);                                                                                                                    
        foreach($book as $bd)
        {
            $d_id =BookDeparture::where('unique_id',$bd->unique_id)->first();
            $departure = Departure::where('id',$d_id->departure_id)
                    ->select('id','title','company_name')
                    ->first();
            $bd->dep_id = $departure->id;
            $bd->title = $departure->title;
            $bd->dep_company_name = $departure->company_name;

            $user_name = User::where('id',$d_id->user_id)
                        ->value('company_name');
            $bd->name = $user_name;

            $booked_seat = BookDeparture::where('unique_id',$bd->unique_id)
                        ->sum('booked_seat');
            $bd->booked_seat = $booked_seat;

            $price = BookDeparture::where('unique_id',$bd->unique_id)
                    ->sum('price');
            $bd->price = $price;

            $booked_value = BookDeparture::where('unique_id',$bd->unique_id)
                    //->where('user_id',$departure->user_id)
                    ->first();
                //dd($booked_value);
            $bd->unique_id = $booked_value->unique_id;
            $bd->created_date = $booked_value->created_at;

            $bd->booked_date = date('d-M-Y', strtotime($booked_value->created_at."+5 hours +30 minutes"));
            $bd->booked_time = date('h:i A', strtotime($booked_value->created_at."+5 hours +30 minutes"));

            $bd_currency = BookDeparture::where('departure_id',$departure->id)
                        ->where('unique_id',$bd->unique_id)
                        ->value('currency_symbol');
            $bd->currency = $bd_currency;
            $bd->departure_status = 'Confirm';                
        }
        if(count($book)>0){
            $status = array(
                'error' => false,
                'book' => $book,
                'total' => $bookTotal,
                'page' => $request->page,
                'message' =>"Success"
            );
        return Response($status,200);
        }
        else{
            $status = array(
                'error' => false,
                'book' => [],
                'total' => 0,
                'page' => '',
                'message' =>"Data not found!"
            );
        return Response($status,200);
        } 
    }

    public function bookDepartureShow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departure_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $departure_prices = DeparturePrice::where('departure_id',$request->departure_id)->where('status',1)->get();
        foreach($departure_prices as $prices)
        {
            $prices->transport_type = (isset($prices->transport_type))?$prices->transport_type:'NA';
            $prices->meal_type = (isset($prices->meal_type))?$prices->meal_type:'NA';
            $prices->hotel_type = (isset($prices->hotel_type))?$prices->hotel_type:'NA';

            $prices->flight_class = (isset($prices->flight_class))?$prices->flight_class:'NA';
            $prices->passenger = (isset($prices->passenger))?$prices->passenger:'NA';
            $prices->airport_transfers = (isset($prices->airport_transfers))?$prices->airport_transfers:'NA';

            $prices->sharing = (isset($prices->sharing))?$prices->sharing:'NA';
        }
        $departure = DB::table('departures')
                ->where('id', $request->departure_id)
                ->first();
        $new_time = $departure->hold_duration + 5;
       
        $hold_date = date('d M, Y', strtotime("+{$new_time} hours +30 minutes"));
        $hold_time = date('h:ia', strtotime("+{$new_time} hours +30 minutes"));
        
        $status = array(
            'error' => false,
            'departure_prices' => $departure_prices,
            'hold_date' => $hold_date,
            'hold_time' => $hold_time
        );
        return Response($status,200);
    }


    public function holdSeatStore(Request $request){
        $departure_id = $request->id;
        $hold_dep_id = $request->hold_dep_id; // hold idies
        $required_units = $request->required_units;
        $note = $request->note;
        $lead_pasanger_name = $request->lead_pasanger_name;
        $departure = Departure::where('id',$departure_id)->first();
        $count = count($required_units);
        $total = 0;
        $price = 0;
        $total_price = 0;
        if(count($required_units) > 0)
        {   
            $hold_sum = HoldDepartureSeat::where('departure_id', $departure_id) 
                        ->sum('hold_seat');
            $book_sum = BookDeparture::where('departure_id', $departure_id)
                     ->where('status',1)
                     ->sum('booked_seat');
            $available_unit = $departure->total_seat-($departure->hold_sum + $departure->book_sum);
            
            $dep_price = DeparturePrice::where('departure_id', $departure_id)->first();

            $new_time = $departure->hold_duration + 5;
            $held_time =  date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"));
            $auto_release = date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"));

            $supplier = User::find($departure->user_id);
            $extra_request =array_sum($required_units);

            if($available_unit >= array_sum($required_units)){
                if($extra_request > $available_unit){
                    $extra_seat_hold = $extra_request - $available_unit;
                    $extra_hold_user = 0;
                    $request1 = $available_unit;
                }else{
                    $extra_hold_user = $available_unit - array_sum($required_units);
                    $extra_seat_hold = 0;
                }
                foreach($required_units as $key=>$hold){
                    $priceT = DeparturePrice::where('id', $request->hold_dep_id[$key])->first();
                    $total = $total + $hold;
                    $total_price = $total_price + ($hold * $priceT->price);

                    $save = new HoldDepartureSeat;
                    $save->user_id = Auth::user()->id;
                    $save->departure_id = $departure_id;
                    $save->unique_id = time();
                    $save->hold_seat = $hold;
                    $save->extra_hold_seat = $extra_seat_hold; 
                    $save->sairing = $priceT->sairing;
                    $save->transport_type = $priceT->transport_type;
                    $save->hotel_type = $priceT->hotel_type;
                    $save->meal_plan = $priceT->meal_plan;
                    $save->group_size = $priceT->group_size;
                    $save->flight_class = $priceT->flight_class;
                    $save->passenger = $priceT->passenger;
                    $save->airport_transfers = $priceT->airport_transfers;
                    $save->lead_pasanger_name = $lead_pasanger_name;
                    $save->price = $priceT->price * $hold;
                    $save->currency_code = $priceT->currency_code;
                    $save->currency_symbol = $priceT->currency_symbol;
                    $save->hold_duration = $departure->hold_duration;
                    $save->auto_release = $auto_release;
                    $save->note = $note;
                    $save->date = date("Y-m-d H:i:s", strtotime("+{$departure->hold_duration} hours"));
                    $save->save();
                }
            }
            else{
                foreach($required_units as $key=>$hold){
                    $extra = array_sum($required_units)-$available_unit;
                    $priceT = DeparturePrice::where('id', $request->hold_dep_id[$key])->first();
                    $total = $total + $hold;
                    $total_price = $total_price + ($hold * $priceT->price);
                    $save = new HoldDepartureSeat;
                    $save->user_id = Auth::user()->id;
                    $save->departure_id = $departure_id;
                    $save->unique_id = time();
                    $save->hold_seat = $hold;
                    $save->extra_hold_seat = $extra; 
                    $save->sairing = $priceT->sairing;
                    $save->transport_type = $priceT->transport_type;
                    $save->hotel_type = $priceT->hotel_type;
                    $save->meal_plan = $priceT->meal_plan;
                    $save->group_size = $priceT->group_size;
                    $save->flight_class = $priceT->flight_class;
                    $save->passenger = $priceT->passenger;
                    $save->airport_transfers = $priceT->airport_transfers;
                    $save->lead_pasanger_name = $lead_pasanger_name;
                    $save->price = $priceT->price * $hold;
                    $save->currency_code = $priceT->currency_code;
                    $save->currency_symbol = $priceT->currency_symbol;
                    $save->hold_duration = $departure->hold_duration;
                    $save->auto_release = $auto_release;
                    $save->note = $note;
                    $save->date = date("Y-m-d H:i:s", strtotime("+{$departure->hold_duration} hours"));
                    $save->save();
                }
            }
            $hold_sum1 = HoldDepartureSeat::where('departure_id', $departure_id) 
                        ->sum('hold_seat');
            $book_sum1 = BookDeparture::where('departure_id', $departure_id)
                     ->where('status',1)
                     ->sum('booked_seat');
            if($available_unit >= array_sum($required_units)){
                $available_unit1 = $departure->total_seat-($hold_sum1 + $book_sum1);
            }else{
                $available_unit1 = 0;
            }
            $available = Departure::find($departure_id);
            $available->available_seat = $available_unit1;
            $available->save(); 
            $supplier_name = User::where('tenant_id',$available->tenant_id)
                        ->first();
            // Booker details
            $noti_save = new Notification;
            $noti_save->title = 'Departure cloud - Departure Hold';
            $noti_save->body = $extra_request .' units are on hold for you in Departure for '.$available->ending_at.'. Deaparture Name: ' .$available->title. ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' Supplier: ' . auth()->user()->name . auth()->user()->last_name . ' No of units on hold: ' . $extra_request. ' Units are on hold till: ' . $held_time;

            $noti_save->body_html = '<p><b> '.$extra_request . ' </b>units are on hold for you in Departure for<b> '.$available->ending_at.' </b>. </p><p><b>Deaparture Name:</b> ' .$available->title. ' </p><p><b>Departure Date:</b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>Supplier:</b> ' . auth()->user()->name . auth()->user()->last_name . ' </p><p><b>No of units on hold:</b> ' . $extra_request. ' </p><p><b>Units are on hold till:</b> ' . $held_time. ' </p>';
            $noti_save->user_id = auth()->user()->id;
            $noti_save->type = "Hold";
            $noti_save->url_1 = url('departures-details').'/'.$available->id;
            $noti_save->save();
            $last_body_auth = $noti_save->body;
            $last_title_auth = $noti_save->title;
            $last_link_auth = $noti_save->url_1;

            //++++++++++++++Notification send Code for Auth+++++++++++//

            $firebaseTokenApp = User::where('id',auth()->user()->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotificationApp = $this->sendNotificationBuyerApp($firebaseTokenApp, $last_title_auth, $last_body_auth, "departure_details",$noti_save->id);

            $firebaseToken = User::where('id',auth()->user()->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationBuyer($firebaseToken, $last_title_auth, $last_body_auth, $last_link_auth,$noti_save->id);
            
            //+++++++++++Notification send Code End for Auth+++++++++//

            // Supplier details
            $noti_sup = new Notification;
            $noti_sup->title = 'Departure cloud  - Departure Hold';
            $noti_sup->body = 'You have received request to hold '.$extra_request .' units in your departure for '.$available->ending_at.'. Hold Request By: ' . auth()->user()->name. auth()->user()->last_name .' Buyer Company Name: ' . auth()->user()->company_name . ' Deaparture Name: ' . $available->title . ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' No of units on hold: ' . $extra_request;

            $noti_sup->body_html = '<p><b> '.$extra_request .' </b>units are on hold for you in Departure for</b> '.$available->ending_at.' </b>. </p><p><b>Hold Request By:</b> ' . auth()->user()->name. auth()->user()->last_name .' </p><p><b>Buyer Company Name:</b> ' . auth()->user()->company_name . ' </p><p><b>Deaparture Name:</b> ' . $available->title . ' </p><p><b>Departure Date:</b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>No of units on hold:</b><b> ' . $extra_request . ' </b></p>';
            $noti_sup->user_id = $supplier_name->id;
            $noti_sup->type = "Hold";
            $noti_sup->url_1 = url('departures-details').'/'.$available->id;
            $noti_sup->save();
            $last_body_sup = $noti_sup->body;
            $last_title_sup = $noti_sup->title;
            $last_link_sup = $noti_sup->url_1;
            //++++++++Notification send Code for Suplier++++++++++++//
            $firebaseTokenApp = User::where('id',$supplier_name->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotificationApp = $this->sendNotificationSupplierApp($firebaseTokenApp, $last_title_sup, $last_body_sup, "departure_details",$noti_sup->id);

            $firebaseToken = User::where('id',$supplier_name->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);

            
            //+++++++ Notification send Code End for Suplier +++++++++//

            $success = 'Departure Held Successfully';
            $status = array(
                'error' => false,
                'message' => $success,
            );
            return Response($status,200);
        }
        else{
            $success = 'Please enter required unit';
            $status = array(
                'error' => false,
                'message' => $success,
            );
            return Response($status,200);
        }
    }

    public function AllDepartureHoldHistory(Request $request){
        
        $user = User::where('id',Auth::user()->id)->first();
        $departure_id = Departure::where('tenant_id',$user->tenant_id)
                ->pluck('id')
                ->toArray();
        $hold = DB::table('hold_departure_seats')
                ->whereIn('departure_id',$departure_id)
                ->select('unique_id','created_at')
                ->distinct()
                ->orderBy('created_at','DESC')
                ->paginate(15);
        $total = count($hold);
        foreach($hold as $hd)
        {
            $d_id =HoldDepartureSeat::where('unique_id',$hd->unique_id)->first();
            $hold_seat = HoldDepartureSeat::where('user_id',$d_id->user_id)->where('unique_id',$d_id->unique_id)->sum('hold_seat');
            $hd->hold_seat = $hold_seat;

            $extra_hold = HoldDepartureSeat::where('user_id',$d_id->user_id)->where('unique_id',$d_id->unique_id)->sum('extra_hold_seat');
            $hd->extra_hold = $extra_hold;

            $price = HoldDepartureSeat::where('user_id',$d_id->user_id)->where('unique_id',$d_id->unique_id)->sum('price');
            $hd->price = $price;

            $hold_value = HoldDepartureSeat::where('unique_id',$d_id->unique_id)->where('user_id',$d_id->user_id)->first();
            
            $hd->unique_id = $hold_value->unique_id;
            $hd->created_date = $hold_value->created_at;

            $hd->hold_date = date('d-M-Y', strtotime($hold_value->created_at."+5 hours +30 minutes"));
            $hd->hold_time = date('h:i A', strtotime($hold_value->created_at."+5 hours +30 minutes"));
            $hd_currency = HoldDepartureSeat::where('departure_id',$d_id->departure_id)->where('unique_id',$d_id->unique_id)->first();

            $hd->currency_symbol = $hd_currency->currency_symbol;

            $departure = Departure::where('id',$d_id->departure_id)
                    ->select('id','title','company_name')
                    ->first();
            $hd->dep_id = $departure->id;
            $hd->title = $departure->title;
            $hd->dep_company_name = $departure->company_name;

            $company_name = User::where('id',$d_id->user_id)->value('company_name');
            $hd->name = $company_name;

        }
        if(count($hold)>0){
            $status = array(
                'error' => false,
                'hold' => $hold,
                'total' => $total,
                'message' =>"Success"
            );
        return Response($status,200);
        }
        else{
            $status = array(
                'error' => false,
                'hold' => [],
                'total' => 0,
                'message' =>"Data not found!"
            );
        return Response($status,200);
        } 
    }

    public function myHoldDeparture(){        
        $user = User::find(Auth::user()->id);

        $myhold = DB::table('hold_departure_seats')
                ->where('user_id',Auth::user()->id)
                ->select('unique_id')
                ->groupBy('unique_id')
                ->orderBy('unique_id','DESC')
                ->paginate(15);
        $total = count($myhold);
        foreach($myhold as $hd)
        {
            $hold_seat = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$hd->unique_id)->sum('hold_seat');
            $hd->hold_seat = $hold_seat;

            $price = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$hd->unique_id)->sum('price');
            $hd->price = $price;

            $hold_value = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$hd->unique_id)->first();
            $hd->unique_id = $hold_value->unique_id;
            $hd->created_date = $hold_value->created_at;
            
            $hd_currency = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$hd->unique_id)->first();
            $hd->currency_symbol = $hd_currency->currency_symbol;
            $d_id = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$hd->unique_id)->first();
            $dep_name = Departure::where('id',$d_id->departure_id)->value('title');
            $dep_nights = Departure::where('id',$d_id->departure_id)->value('no_of_nights');
            $dep_days = Departure::where('id',$d_id->departure_id)->value('no_of_days');
            $hd->title = $dep_name;
            $hd->nights = $dep_nights;
            $hd->days = $dep_days;

            $company_name = Departure::where('id',$d_id->departure_id)->value('company_name');
            $hd->name = $company_name;

            $hd->hold_date = date('d-M-Y', strtotime($hold_value->created_at."+5 hours +30 minutes"));
            $hd->hold_time = date('h:i A', strtotime($hold_value->created_at."+5 hours +30 minutes"));
            $hd->hold_till = date('d-M-Y h:i A', strtotime($hold_value->auto_release."+5 hours +30 minutes"));
        }
        if(count($myhold)>0){
            $status = array(
                'error' => false,
                'hold' => $myhold,
                'total' => $total,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else{
            $status = array(
                'error' => false,
                'hold' => [],
                'total' => 0,
                'message' =>"Data not found!"
            );
        return Response($status,200);
        } 
    }

    public function departureBookingHistory(Request $request)
    { 
        $departure_id = $request->departure_id;
        $permission = User::getPermissions();
        if (Gate::allows('departure_booking_history',$permission)) 
        {
            $book_date = DB::table('book_departures')
                    ->where('departure_id',$departure_id)
                    ->select('unique_id','user_id','created_at as created_date')
                    ->distinct()
                    ->orderBy('created_at','DESC')
                    ->get();
            $total = count($book_date);
            foreach($book_date as $bd)
            {
                $booked_seat = BookDeparture::where('departure_id',$departure_id)->where('unique_id',$bd->unique_id)->sum('booked_seat');
                $bd->booked_seat = $booked_seat;

                $booked_value = BookDeparture::where('unique_id',$bd->unique_id)->where('departure_id',$departure_id)->where('user_id',$bd->user_id)->first();
                $bd->booked_date = date('d-M-Y', strtotime($booked_value->created_date."+5 hours +30 minutes"));
                $bd->booked_time = date('h:i A', strtotime($booked_value->created_date."+5 hours +30 minutes"));

                $price = BookDeparture::where('departure_id',$departure_id)->where('unique_id',$bd->unique_id)->sum('price');

                $bd->price = $price;

                $bd_currency = BookDeparture::where('unique_id',$bd->unique_id)->where('user_id',$bd->user_id)->where('departure_id',$departure_id)->first();
                $bd->currency_symbol = $bd_currency->currency_symbol;

             
                $dep_name = Departure::where('id',$departure_id)->select('title','company_name')->first();
                $bd->title = $dep_name->title;
                $bd->name = $dep_name->company_name;
                $bd->departure_status = 'Confirm'; 

            } 
            if(count($book_date)>0){
                $status = array(
                    'error' => false,
                    'book' => $book_date,
                    'total' => $total,
                    'permission' => $permission,
                    'message' =>"Success"
                );
            return Response($status,200);
            }
            else{
                $status = array(
                    'error' => false,
                    'book' => [],
                    'total' => 0,
                    'permission' => $permission,
                    'message' =>"Data not found!"
                );
            return Response($status,200);
            }  
        }
        else{
            $status = array(
                'error' => true,
                'book' => [],
                'total' => 0,
                'permission' => [],
                'message' =>"Unauthorized access!"
            );
            return Response($status,401);
        }
    }

    public function departureHoldHistory(Request $request){        
        $departure_id = $request->departure_id;

        $myhold = DB::table('hold_departure_seats')
                ->where('departure_id',$departure_id)
                ->select('unique_id')
                ->groupBy('unique_id')
                ->orderBy('unique_id','DESC')
                ->paginate(10);
        $total = count($myhold);
        foreach($myhold as $hd)
        {
            $hold_seat = HoldDepartureSeat::where('departure_id',$departure_id)->where('unique_id',$hd->unique_id)->sum('hold_seat');
            $hd->hold_seat = $hold_seat;

            $price = HoldDepartureSeat::where('departure_id',$departure_id)->where('unique_id',$hd->unique_id)->sum('price');
            $hd->price = $price;

            $hold_value = HoldDepartureSeat::where('departure_id',$departure_id)->where('unique_id',$hd->unique_id)->first();
            $hd->unique_id = $hold_value->unique_id;
            $hd->created_date = $hold_value->created_at;
            
            $hd_currency = HoldDepartureSeat::where('departure_id',$departure_id)->where('unique_id',$hd->unique_id)->first();
            $hd->currency_symbol = $hd_currency->currency_symbol;
            $d_id = HoldDepartureSeat::where('departure_id',$departure_id)->where('unique_id',$hd->unique_id)->first();
            $dep_name = Departure::where('id',$d_id->departure_id)->value('title');
            $dep_nights = Departure::where('id',$d_id->departure_id)->value('no_of_nights');
            $dep_days = Departure::where('id',$d_id->departure_id)->value('no_of_days');
            $hd->title = $dep_name;
            $hd->nights = $dep_nights;
            $hd->days = $dep_days;

            $company_name = Departure::where('id',$d_id->departure_id)->value('company_name');
            $hd->name = $company_name;

            $hd->hold_date = date('d-M-Y', strtotime($hold_value->created_at."+5 hours +30 minutes"));
            $hd->hold_time = date('h:i A', strtotime($hold_value->created_at."+5 hours +30 minutes"));
            $hd->hold_till = date('d-M-Y h:i A', strtotime($hold_value->auto_release."+5 hours +30 minutes"));
        }
        if(count($myhold)>0){
            $status = array(
                'error' => false,
                'hold' => $myhold,
                'total' => $total,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else{
            $status = array(
                'error' => false,
                'hold' => [],
                'total' => 0,
                'message' =>"Data not found!"
            );
        return Response($status,200);
        } 
    }

    public function bookingHistoryDetails(Request $request){
        $unique_id = $request->unique_id;
        $book = DB::table('book_departures')
                ->where('unique_id',$unique_id)
                ->distinct()
                ->value('id');          
        if($book){
            $booking_detail = BookDeparture::where('id',$book)
                    ->select('currency_symbol','departure_id','user_id','date','created_at')
                    ->first();
            $booking_detail->booked_date = date('d-M-Y h:ia', strtotime($booking_detail->created_at."+5 hours +30 minutes"));

            $booking_price = BookDeparture::where('unique_id',$unique_id)->sum('price');
            $booking_unit = BookDeparture::where('unique_id',$unique_id)->sum('booked_seat');
            $update_price_sum = DepartureBookingPriceUpdates::where('booking_unique_id',$unique_id)->sum('price');
            $price_details = BookDeparture::where('unique_id',$unique_id)
                ->select('sairing as sharing','transport_type','hotel_type','meal_plan as meal_type','group_size','flight_class','passenger','airport_transfers','price','booked_seat')
                ->get();
            $total = 0;

            foreach($price_details as $key => $value) {
                $value->transport_type = ($value->transport_type != null || $value->transport_type != '')?$value->transport_type:'NA';
                $value->meal_type = ($value->meal_type != null || $value->meal_type != '')?$value->meal_type:'NA';
                $value->hotel_type = ($value->hotel_type != null || $value->hotel_type != '')?str_replace(" Star","",$value->hotel_type):'NA';
                $value->group_size = ($value->group_size != null || $value->group_size != '')?$value->group_size:'NA';
                $value->flight_class = ($value->flight_class != null || $value->flight_class != '')?$value->flight_class:'NA';
                $value->passenger = ($value->passenger != null || $value->passenger != '')?$value->passenger:'NA';
                $value->sharing = ($value->sharing != null || $value->sharing != '')?$value->sharing:'NA';
                $value->airport_transfers = ($value->airport_transfers != null || $value->airport_transfers != '')?$value->airport_transfers:'NA';

                $total = $total + $value->price / $value->booked_seat * $value->booked_seat;
                $value->price = $booking_detail->currency_symbol.''.$value->price/$value->booked_seat .' * '. $value->booked_seat .' = '. $booking_detail->currency_symbol .''.number_format($value->price/$value->booked_seat * $value->booked_seat);
            }
            $total_price = $booking_detail->currency_symbol .''. number_format($total);
            $departure = Departure::where('id',$booking_detail->departure_id)
                    ->select('id','title','dep_id','user_id','departure_ownner','from as departure_from','ending_at as departure_to','no_of_nights as nights','no_of_days as days','tenant_id','start_date')->first();

            $user = User::where('id',$booking_detail->user_id)->select('id','tenant_id','company_name','company_id')->first();

            $destination = DB::table('departure_destinations')
                ->join('destinations','departure_destinations.destination_id','destinations.id')
                ->select('destinations.country_name','destinations.dest_name')
                ->where('departure_destinations.departure_id',$departure->id)
                ->get();
            $departure_prices = PaymentSchedule::where('departure_id',$departure->id)
                        ->select('date','percentage')
                        ->get();
            $count = 0;
            foreach ($departure_prices as $key => $dep_price) 
            {
                if($booking_detail->date > $dep_price->date) {
                    $count = $count + $dep_price->percentage;

                }
            }
            
            $currency_symbol = $booking_detail->currency_symbol;
            $minimum_P = number_format(($booking_price * $count) / 100);
            $minimum_bookingP="";
            if($minimum_P>0){
                  $minimum_bookingP = $currency_symbol.'' .$minimum_P;
            }

            $payment_schedule = [];

            foreach ($departure_prices as $key => $dep_price) {
                if($key == 0){
                    if($booking_detail->date < $dep_price->date){
                       $minimum_Pc = number_format(($booking_price * $dep_price->percentage)/100);
                        if($minimum_Pc>0){
                              $minimum_bookingP = $currency_symbol.'' .$minimum_Pc;
                        }
                    }

                }
                else{
                    if($booking_detail->date > $dep_price->date){
                        $dataP = ["type"=>"bookDateBig","date"=>date('d M, Y', strtotime($dep_price->date)),"currency_symbol"=>$booking_detail->currency_symbol,"price"=>number_format(($booking_price * $dep_price->percentage)/100)];
                        array_push($payment_schedule,$dataP);
                    }else{
                        $dataP = ["type"=>"bookDateSmall","date"=>date('d M, Y', strtotime($dep_price->date)), "currency_symbol"=>$booking_detail->currency_symbol, "price"=>number_format(($booking_price * $dep_price->percentage)/100)];
                        array_push($payment_schedule,$dataP);
                    }
                }
            }

            $update_price = DepartureBookingPriceUpdates::where('booking_unique_id',$unique_id)->select('date','price')->get();

            $tenant = User::find($departure->user_id);
            $updatedPrice = [];
            $paid = '';
            $balance = '';
            if(count($update_price)>0){
                if($tenant->tenant_id == Auth::user()->tenant_id){
                    foreach($update_price as $update){
                        $updatedP = ["text"=>$update->date, "currency_symbol"=>$booking_detail->currency_symbol, "price"=>$update->price];
                        array_push($updatedPrice, $updatedP);
                    }
                }else{
                    $paid = ["text"=>"Paid", "currency_symbol"=>$booking_detail->currency_symbol, "price"=>$update_price_sum];

                    $balance = ["text"=>"Balance", "currency_symbol"=>$booking_detail->currency_symbol, "price"=>$booking_price - $update_price_sum];
                }
            }

            $itinerary = AgentItinerarie::where('departure_id',$booking_detail->departure_id)
                    ->select('description as image','pdf_file')
                    ->first();
            if($itinerary->image){
                $itinerary->image = url('ScreenShot').'/'.$itinerary->image;
            }
            $itinerary->pdf_file = url('agentitinerary').'/'.$itinerary->pdf_file;
            $status = array(
                'error' => false,
                'total_unit' => $booking_unit,
                'booking_detail' => $booking_detail,
                'price_details' => $price_details,
                'itinerary' => $itinerary,
                'updatedPrice' => $updatedPrice,
                'paid' => $paid,
                'balance' => $balance,
                'total_price' => $total_price,
                'payment_schedule' => $payment_schedule,
                'minimum_bookingP' => $minimum_bookingP,
                'destinations_coverd' => $destination,
                'departure' => $departure,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else{
            $status = array(
                'error' => true,
                'total_unit' => '',
                'booking_detail' =>  new \stdClass(),
                'price_details' => [],
                'itinerary' => new \stdClass(),
                'updatedPrice' => [],
                'paid' => '',
                'balance' => '',
                'total_price' => '',
                'payment_schedule' => [],
                'minimum_bookingP' => '',
                'destinations_coverd' => [],
                'departure' => new \stdClass(),
                'message' =>"No data found!"
            );
        return Response($status,200);
        } 
    }

    public function holdHistoryDetails(Request $request){
        $unique_id = $request->unique_id;
        $hold = DB::table('hold_departure_seats')
                ->where('unique_id',$unique_id)
                ->distinct()
                ->value('id');          
        if($hold){
            $holding_detail = HoldDepartureSeat::where('id', $hold)
                    ->select('currency_symbol','departure_id','user_id','date','created_at','auto_release')
                    ->first();
            $holding_price = HoldDepartureSeat::where('unique_id',$unique_id)->sum('price');
            $holding_unit = HoldDepartureSeat::where('unique_id',$unique_id)->sum('hold_seat');

            $price_details = HoldDepartureSeat::where('unique_id',$unique_id)
                    ->select('sairing as sharing','transport_type','hotel_type','meal_plan as meal_type','group_size','flight_class','passenger','airport_transfers','price','hold_seat')
                    ->get();
            $total = 0;

            foreach($price_details as $key => $value) {
                $value->transport_type = ($value->transport_type != null || $value->transport_type != '')?$value->transport_type:'NA';
                $value->meal_type = ($value->meal_type != null || $value->meal_type != '')?$value->meal_type:'NA';
                $value->hotel_type = ($value->hotel_type != null || $value->hotel_type != '')?str_replace(" Star","",$value->hotel_type):'NA';
                $value->group_size = ($value->group_size != null || $value->group_size != '')?$value->group_size:'NA';
                $value->flight_class = ($value->flight_class != null || $value->flight_class != '')?$value->flight_class:'NA';
                $value->passenger = ($value->passenger != null || $value->passenger != '')?$value->passenger:'NA';
                $value->sharing = ($value->sharing != null || $value->sharing != '')?$value->sharing:'NA';
                $value->airport_transfers = ($value->airport_transfers != null || $value->airport_transfers != '')?$value->airport_transfers:'NA';

                $total = $total + $value->price / $value->hold_seat * $value->hold_seat;
                $value->price = $holding_detail->currency_symbol.''.$value->price/$value->hold_seat .' * '. $value->hold_seat .' = '. $holding_detail->currency_symbol .''.number_format($value->price/$value->hold_seat * $value->hold_seat);
            }
            $total_price = $holding_detail->currency_symbol .''. number_format($total);

            $departure = Departure::where('id', $holding_detail->departure_id)
                ->select('id','title','dep_id','user_id','departure_ownner','from as departure_from','ending_at as departure_to','no_of_nights as nights','no_of_days as days','tenant_id')
                ->first();

            $user = User::where('id', $holding_detail->user_id)
                    ->select('id','tenant_id','company_name','company_id')
                    ->first();
            $destination = DB::table('departure_destinations')
                ->join('destinations','departure_destinations.destination_id','destinations.id')
                ->select('destinations.country_name','destinations.dest_name')
                ->where('departure_destinations.departure_id',$departure->id)
                ->get();
         
            $departure_prices = PaymentSchedule::where('departure_id',$departure->id)
                    ->select('date','percentage')
                    ->get();
            $count = 0;
            foreach ($departure_prices as $key => $dep_price) 
            {
                if($holding_detail->date > $dep_price->date) {
                    $count = $count + $dep_price->percentage;
                }
            }
            $currency_symbol = $holding_detail->currency_symbol;
            $minimum_P = number_format(($holding_price * $count) / 100);
            $minimum_bookingP="";
            if($minimum_P>0){
                  $minimum_bookingP = $currency_symbol.'' .$minimum_P;
            }

            $payment_schedule = [];

            foreach ($departure_prices as $key => $dep_price) {
                if($key == 0){
                    if($holding_detail->date < $dep_price->date){
                       $minimum_Pc = number_format(($holding_price * $dep_price->percentage)/100);
                        if($minimum_Pc>0){
                              $minimum_bookingP = $currency_symbol.'' .$minimum_Pc;
                        }
                    }
                }
                else{
                    if($holding_detail->date > $dep_price->date){
                        $dataP = ["type"=>"bookDateBig","date"=>date('d M, Y', strtotime($dep_price->date)),"currency_symbol"=>$holding_detail->currency_symbol,"price"=>number_format(($holding_price * $dep_price->percentage)/100)];
                        array_push($payment_schedule,$dataP);
                    }else{
                        $dataP = ["type"=>"bookDateSmall","date"=>date('d M, Y', strtotime($dep_price->date)), "currency_symbol"=>$holding_detail->currency_symbol, "price"=>number_format(($holding_price * $dep_price->percentage)/100)];
                        array_push($payment_schedule,$dataP);
                    }
                }
            }

            $hold_date = date('d-M-Y h:iA', strtotime($holding_detail->created_at."+5 hours +30 minutes"));
            $hold_till = date('d-M-Y h:iA', strtotime($holding_detail->auto_release."+5 hours +30 minutes"))
            ; 
            $status = array(
                'error' => false,
                'total_unit' => $holding_unit,
                'holding_detail' => $holding_detail,
                'price_details' => $price_details,
                'total_price' => $total_price,
                'payment_schedule' => $payment_schedule,
                'minimum_bookingP' => $minimum_bookingP,
                'destinations_coverd' => $destination,
                'departure' => $departure,
                'user' => $user,
                'message' =>"Success"
            );
            return Response($status,200);  
        }
        else{
            $status = array(
                'error' => true,
                'total_unit' => '',
                'holding_detail' =>  new \stdClass(),
                'price_details' => [],
                'total_price' => '',
                'payment_schedule' => [],
                'minimum_bookingP' => '',
                'destinations_coverd' => [],
                'departure' => new \stdClass(),
                'user' => new \stdClass(),
                'message' =>"No data found!"
            );
            return Response($status,200);
        } 
    }

    public function bookingCancle(Request $request)
    { 
        $unique_id = $request->unique_id;
        if($unique_id){
            $departure_id = DB::table('book_departures')
                    ->where('unique_id', $unique_id)
                    ->value('departure_id');

            $departure = Departure::find($departure_id);
            $supplier = User::where('tenant_id',$departure->tenant_id)->first();
            $supplier_forNoti = User::where('id',$departure->contact_person_id)->first();
            $buyer = User::find(auth::user()->id);
            $paid_amount = DB::table('departure_booking_price_updates')->where('booking_unique_id',$unique_id)->where('departure_id',$departure_id)->sum('price');
            $date = date('Y-m-d');
            $cancelation = CancelSchedule::where('departure_id',$departure_id)
                        ->where('tenant_id',$departure->tenant_id)
                        ->get();
            $count = 0;
            foreach($cancelation as $row){
                if($row->date <= $date){
                    $count = $count+1;
                   //return $cancel = $row->percentage;
                } 
            }
            foreach($cancelation as $key=>$row){
                if($key == ($count-1)){
                   $cancel = $row->percentage;
                } 
            }
            $payment_schedule = PaymentSchedule::where('departure_id',$departure_id)
                              ->where('user_id',$departure->user_id)
                              ->get();
            $payment_total = 0;
            foreach($payment_schedule as $payment){
                if($payment->date <= $date){
                   $payment_total = $payment->percentage+$payment_total;
                }
            } 
            $booking = BookDeparture::where('unique_id',$unique_id)
                     ->where('user_id',auth::user()->id)
                     ->sum('price');
            $unit = BookDeparture::where('unique_id',$unique_id)
                     ->where('user_id',auth::user()->id)
                     ->sum('booked_seat');
            $booking_data = BookDeparture::where('unique_id',$unique_id)
                     ->where('user_id',auth::user()->id)
                     ->first();
            
            $total_paid_amount = $booking * ($payment_total/100);
            $deduction_amount = $total_paid_amount * ($cancel/100);
            $refund_amount = $paid_amount - $deduction_amount;
            $booking_ids = BookDeparture::where('unique_id',$unique_id)
                     ->where('user_id',auth::user()->id)
                     ->get();
            foreach ($booking_ids as $key => $value) {
                $data = BookDeparture::find($value->id);
                $data->status = 0;
                $data->cancelation_date = $date;
                $data->save();
            }
            
            $dep_update = Departure::find($departure_id);
            $dep_update->available_seat = $departure->available_seat+$unit;
            $dep_update->save();

            // Notification Buyer

            $noti_buyer = new Notification;
            $noti_buyer->title = "Departure cloud - Cancellation";
            $noti_buyer->body = 'Following Cancellation request has been submitted to supplier Deaparture Name: '.$departure->title.' Departure Date: '.date('d M, Y', strtotime($departure->start_date)).' Supplier: '.$departure->departure_ownner.' No of units: '.$unit.' Cancellation charges: '.$deduction_amount. ' To contact supplier pls login to Departure Cloud.';
            $noti_buyer->body_html = '<p>Following Cancellation request has been submitted to supplier </p><p><b>Deaparture Name:</b> '.$departure->title.' </p><p><b>Departure Date:</b> '.date('d M, Y', strtotime($departure->start_date)).' </p><p><b>Supplier:</b> '.$departure->departure_ownner.' </p><p><b>No of units:</b> '.$unit.' </p><p><b>Cancellation charges:</b> '.$deduction_amount. ' </p><p>To contact supplier pls login to Departure Cloud.</p>';
            $noti_buyer->user_id = $buyer->id;
            $noti_buyer->type = "Cancellation";
            $noti_buyer->url_1 = url('login');
            $noti_buyer->save();
            $last_body_buyer = $noti_buyer->body;
            $last_title_buyer = $noti_buyer->title;
            $last_link_buyer = $noti_buyer->url_1;
        //++++++++Notification send Code for Buyer++++++++++++//

            
            $firebaseTokenApp = User::where('id',$buyer->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotificationApp = $this->sendNotificationBuyerApp($firebaseTokenApp, $last_title_buyer, $last_body_buyer, "login",$noti_buyer->id);
            $firebaseToken = User::where('id',$buyer->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationBuyer($firebaseToken, $last_title_buyer, $last_body_buyer, $last_link_buyer,$noti_buyer->id);
            
        
            // Notification Buyer

            // Notification Supplier
            $noti_supplier = new Notification;
            $noti_supplier->title = "Departure cloud - Cancellation";
            $noti_supplier->body = 'Following Cancellation request has been submitted to supplier Deaparture Name: '.$departure->title.' Departure Date: '.date('d M, Y', strtotime($departure->start_date)).' Supplier: '.$departure->departure_ownner.' No of units: '.$unit.' Cancellation charges: '.$deduction_amount. ' To contact supplier pls login to Departure Cloud.';

            $noti_supplier->body_html = '<p>Following Cancellation request has been submitted to supplier.</p><p><b>Deaparture Name:</b> '.$departure->title.' </p><p><b>Departure Date:</b> '.date('d M, Y', strtotime($departure->start_date)).' </p><p><b>Supplier:</b> '.$departure->departure_ownner.' </p><p><b>No of units:</b> '.$unit.' </p><p><b>Cancellation charges:</b> '.$deduction_amount. ' </p><p>To contact supplier pls login to Departure Cloud.</p>';
            $noti_supplier->user_id = $supplier_forNoti->id;
            $noti_supplier->type = "Cancellation";
            $noti_supplier->url_1 = url('login');
            $noti_supplier->save();
            $last_body_supplier = $noti_supplier->body;
            $last_title_supplier = $noti_supplier->title;
            $last_link_supplier = $noti_supplier->url_1;

            //++++++++Notification send Code for Suplier++++++++++++//

            $firebaseToken = User::where('id',$supplier_forNoti->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_supplier, $last_body_supplier, $last_link_supplier,$noti_supplier->id);
            $firebaseTokenApp = User::where('id',$supplier_forNoti->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotificationApp = $this->sendNotificationSupplierApp($firebaseTokenApp, $last_title_supplier, $last_body_supplier, "login",$noti_supplier->id);
        
            // Notification Supplier

            $status = array(
                'error' => false,
                'message' =>"Success"
            );
            return Response($status,200);  
        }else{
            $status = array(
                'error' => true,
                'message' =>"Something went wrong!"
            );
            return Response($status,200);
        } 
    }

    public function departureConfirm(Request $request)
    {
        $unique_key = $request->unique_id;
        $hold_seat = HoldDepartureSeat::where('unique_id',$unique_key)
            ->get();
        if(count($hold_seat) > 0)
        {
            $unique_id = time();
            foreach($hold_seat as $hold)
            {
               $save = new BookDeparture;
               $save->user_id = Auth::user()->id;
               $save->departure_id = $hold->departure_id;
               $save->unique_id = $unique_id;
               $save->sairing = $hold->sairing;
               $save->transport_type = $hold->transport_type;
               $save->flight_class = $hold->flight_class;
               $save->passenger = $hold->passenger;
               $save->airport_transfers = $hold->airport_transfers;
               $save->hotel_type = $hold->hotel_type;
               $save->meal_plan = $hold->meal_plan;
               $save->group_size = $hold->group_size;
               $save->lead_pasanger_name = $hold->lead_pasanger_name;
               $save->booked_seat = $hold->hold_seat;
               $save->price = $hold->price;
               $save->currency_code = $hold->currency;
               $save->currency_symbol = $hold->currency_symbol;
               $save->note = $hold->note;
               $save->date = date("Y-m-d");
               $save->save();
            }
            HoldDepartureSeat::where('unique_id',$unique_key)->delete();
            $status = array(
                'error' => false,
                'message' =>"Booking Confirmed"
            ); 
        }else{
            $status = array(
                'error' => true,
                'message' =>"Something went wrong!"
            );
        } 
        return Response($status,200);
    }

    public function forceRelease(Request $request){
        $unique_id = $request->unique_id;

        $hold_seat = HoldDepartureSeat::where('unique_id',$unique_id)->first();
        if($hold_seat){
            $holdSum = HoldDepartureSeat::where('unique_id',$unique_id)->sum('hold_seat');
            $departure = Departure::where('id', $hold_seat->departure_id)->first();
            $update_dep = Departure::find($hold_seat->departure_id);
            $update_dep->available_seat = $departure->available_seat + $hold_seat->hold_seat;
            $update_dep->save();
            $user = User::where('id',$departure->contact_person_id)->first();
            $ids = HoldDepartureSeat::where('unique_id',$id)->get();
            //Notification
            $noti_save = new Notification;
            $noti_save->title = 'Departure cloud - Hold Units Released';
            $noti_save->body = $holdSum.' units held by you in departure for' .$departure->ending_at. ' are released  by supplier';

            $noti_save->body_html = '<p><b> '.$holdSum.' </b>units held by you in departure for<b> ' .$departure->ending_at. ' </b>are released  by supplier. To contact supplier pls login to departure cloud</p>';
            $noti_save->user_id = $user->id;
            $noti_save->type = "ForceRelease";
            $noti_save->url_1 = url('login');
            $noti_save->save();
            
            $last_body_sup = $noti_save->body;
            $last_title_sup = $noti_save->title;
            $last_link_sup = $noti_save->url_1;

            $firebaseTokenApp = User::where('id',$user->id)->whereNotNull('fcm_token_app')->value('fcm_token_app');
            $sendNotificationApp = $this->sendNotificationSupplierApp($firebaseTokenApp, $last_title_sup, $last_body_sup, "login",$noti_save->id);

            $firebaseToken = User::where('id',$user->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_save->id);


            HoldDepartureSeat::where('unique_id',$unique_id)->delete();
            $status = array(
                'error' => false,
                'message' =>"Success"
            );
            return Response($status,200);  
        }else{
            $status = array(
                'error' => true,
                'message' =>"Something went wrong!"
            );
            return Response($status,200);
        } 
    }

    public function bookingPriceUpdate(Request $request){
        $unique_id =  $request->unique_id;
        $price = $request->price;
        $date = $request->date;
        
        $bookingData = BookDeparture::where('unique_id',$unique_id)
             ->select('departure_id','user_id')
             ->first();
        if($bookingData){
            $data = new DepartureBookingPriceUpdates;
            $data->booking_unique_id = $unique_id;
            $data->user_id = $bookingData->user_id;
            $data->departure_id = $bookingData->departure_id;
            $data->price = $price;
            $data->date = date("Y-m-d", strtotime($date));
            $data->save();
            $status = array(
                'error' => false,
                'message' =>"Success"
            );
            return Response($status,200);  
        }else{
            $status = array(
                'error' => true,
                'message' =>"Something went wrong!"
            );
            return Response($status,200);
        } 
    }

    public function departurePublish(Request $request)
    {
        $departure = Departure::find($request->id);
        if($request->type == 1){
            if($request->publish == 2){
                if($departure->status == 0){
                    $departure->status = 1;
                    $departure->company_publish=1;
                    $departure->save();
                }
                $status = array(
                    'error' => false,
                    'message' =>"Departure has been published successfully. Details will be reviewed and approved by the admin soon!"
                );
            }else{
                if($departure->company_publish == 0){
                    $departure->company_publish = 1;
                    $departure->approve = 1;
                    $departure->status = 1;
                    $departure->save();
                }
                $status = array(
                    'error' => false,
                    'message' =>"Departure has been published successfully!"
                );
            }
        }else{
            $departure->company_publish = 0;
            $departure->status = 0;
            $departure->save();
            $status = array(
                'error' => false,
                'message' =>"Departure Unpublished successfully!"
            );
        }
        return Response($status,200);    
    }

    public function SupplierBuyerProfile(Request $request){
        $tenant_id = $request->tenent_id;
        //$departure_id = $request->id;
        $date = date("Y-m-d");
        $base_url = url('');
        $item_per_page = 10;
        if (is_null($request->page) || $request->page == 1) {
            $current_page = 1;
            $offset = ($current_page-1)* $item_per_page;
        }else {
            $current_page = $request->page;
            $offset = ($current_page-1)* $item_per_page;
        }
        if(auth()->user()->main_user_type == 1 && auth()->user()->user_type ==  0){
            $users = User::where(['tenant_id'=>$tenant_id,'main_user_type'=>1,'user_type'=>0])->first();
            $contact_person = User::where('contact_person_id',$users->contact_person_id)
                ->first();
            if($contact_person->contact_person_id != ""){
                $users->contact_name = $contact_person->name.' '.$contact_person->last_name;
            }else{
                $users->contact_name = $users->name.' '.$users->last_name;
            }
            $users->name = $users->name.' '.$users->last_name;
            
            $users->email = ($users->email !="")?$users->email:'NA';
            $users->mobile = ($users->mobile !="")?$users->mobile:'NA';
            $users->company_name = ($users->company_name !="")?$users->company_name:'NA';
            $users->address = ($users->address !="")?$users->address:'NA';
            $users->country = ($users->country !="")?$users->country:'NA';
            $users->city = ($users->city !="")?$users->city:'NA';
            $users->state = ($users->state !="")?$users->state:'NA';
            $users->pin = ($users->pin !="")?$users->pin:'NA';
            $facebook = ($users->facebook !="")?$users->facebook:'NA';
            $twitter = ($users->twitter !="")?$users->twitter:'NA';
            $instagram = ($users->instagram !="")?$users->instagram:'NA';
            $youtube = ($users->youtube !="")?$users->youtube:'NA';
            $pinterest = ($users->pinterest !="")?$users->pinterest:'NA';
            $linkedin = ($users->linkedin !="")?$users->linkedin:'NA';
            $users->logo = ($users->logo !="")?url('companyLogo').'/'.$users->logo:url('images').'/no-image-logo.png';
            $users->banner_image = ($users->banner_image !="")?url('BannerImage').'/'.$users->banner_image:url('assets1/images').'/cover.jpg';

            $social_network = array(
                array(
                    "url" => strtolower($facebook),
                    "image" => $base_url."/social_icons/facebook.png"
                ),
                array(
                    "url" => strtolower($instagram),
                    "image" => $base_url."/social_icons/instagram.png"
                ),
                array(
                    "url" => strtolower($youtube),
                    "image" => $base_url."/social_icons/youtube.png"
                ),
                array(
                    "url" => strtolower($linkedin),
                    "image" => $base_url."/social_icons/linkedin.png"
                ),
                array(
                    "url" => strtolower($twitter),
                    "image" => $base_url."/social_icons/twitter.png"
                ),
                array(
                    "url" => strtolower($pinterest),
                    "image" => $base_url."/social_icons/pinterest.png"
                )
            );
            $s_network = [];
            foreach($social_network as $key => $social){
                if($social['url'] != ''){
                    $s_network[] = $social;
                }
            }
            $departures = Departure::where('tenant_id', $tenant_id)
                    ->where('approve',1)
                    ->where('status',1)
                    ->where('available_seat','>',0)
                    ->whereDate('start_date', '>=', $date)
                    ->orderBy('start_date', 'DESC')
                    ->select('id','title','dep_id as departure_id','start_date','no_of_nights','no_of_days','from','ending_at','available_seat','dep_id')
                    ->offset($offset)
                    ->limit($item_per_page)
                    ->get();

            if(count($departures)>0){
                foreach ($departures as $key => $value) {
                    $value->departure_status = "Open";
                    $inclu_icons = Inclusion::where('departure_id',$value->id)->where('icon','!=', null)->select('icon','name')->get();
                    foreach ($inclu_icons as $key => $inclu_icon) {
                       $inclu_icon->icon = url('inclusion-images').'/'.$inclu_icon->icon;
                    }
                    $value->inclusion_icons = $inclu_icons;

                    $hold_till = DB::table('hold_departures')->where('departure_id', $value->id)->first();
                    if ($hold_till) {
                        $hold = $hold_till->hold_till;
                    } else {
                        $hold = 0;
                    }

                    //dd($hold);
                    $today = date("Y-m-d");
                    $date1 = date_create($today);
                    $date2 = date_create($value->start_date);
                    $diff = date_diff($date1, $date2);
                    $date = $diff->format("%R%a");   
                    if($hold < $date){
                        $value->holdShow = 'Yes';
                    }else{
                        $value->holdShow = 'No';
                    }  

                    $dep_price = DeparturePrice::where('departure_id', $value->id)
                        ->where('status',1)
                        ->first();
                    $value->price = $dep_price->price; 
                    $value->currency_symbol = $dep_price->currency_symbol; 
                    $value->currency_code = $dep_price->currency_code;  

                    $holds = HoldDepartureSeat::where('departure_id',$value->id) 
                            ->sum('hold_seat');
                    
                    $books = BookDeparture::where('departure_id',$value->id) 
                            ->sum('booked_seat');
                    
                    $avbl_seat = $value->total_seat-($holds+$books);
                    if($avbl_seat >=0){
                        $value->available_units = $avbl_seat;
                    }
                    else{
                        $value->available_units = 0;
                    }      
                }  
            } 
            $departuresT = Departure::where('tenant_id', $tenant_id)
                    ->where('approve',1)
                    ->where('status',1)
                    ->where('available_seat','>',0)
                    ->whereDate('start_date', '>=', $date)
                    ->get();
            $pkgTotald = count($departuresT);
            $pagep = $pkgTotald/$item_per_page;
            $pkgTotal = ceil($pagep);
            $status = array(
                'error' => false,
                'user' => $users,
                'social_network' => $s_network,
                'departures' => $departures,
                'departure_status'=> "Open",
                'user_type' => 'supplier',
                'page' => $request->page,
                'total'=> $pkgTotal,
                'message'=>"Success!"
            );
        }else{
            $users = User::where(['tenant_id'=>$tenant_id,'main_user_type'=>0,'user_type'=>0])->first();
            //if($users)
            $contact_person = User::where('contact_person_id',$users->contact_person_id)
                ->first();
            if($contact_person->contact_person_id != ""){
                $users->contact_name = $contact_person->name.' '.$contact_person->last_name;
            }else{
                $users->contact_name = $users->name.' '.$users->last_name;
            }
            $users->name = $users->name.' '.$users->last_name;
            
            $users->email = ($users->email !="")?$users->email:'NA';
            $users->mobile = ($users->mobile !="")?$users->mobile:'NA';
            $users->company_name = ($users->company_name !="")?$users->company_name:'NA';
            $users->address = ($users->address !="")?$users->address:'NA';
            $users->country = ($users->country !="")?$users->country:'NA';
            $users->city = ($users->city !="")?$users->city:'NA';
            $users->state = ($users->state !="")?$users->state:'NA';
            $users->pin = ($users->pin !="")?$users->pin:'NA';
            $facebook = ($users->facebook !="")?$users->facebook:'NA';
            $twitter = ($users->twitter !="")?$users->twitter:'NA';
            $instagram = ($users->instagram !="")?$users->instagram:'NA';
            $youtube = ($users->youtube !="")?$users->youtube:'NA';
            $pinterest = ($users->pinterest !="")?$users->pinterest:'NA';
            $linkedin = ($users->linkedin !="")?$users->linkedin:'NA';
            $users->logo = ($users->logo !="")?url('companyLogo').'/'.$users->logo:url('images').'/no-image-logo.png';
            $users->banner_image = ($users->banner_image !="")?url('BannerImage').'/'.$users->banner_image:url('assets1/images').'/cover.jpg';
            $users['facebook'] = $facebook;
            $users['instagram'] = $instagram;
            $users['youtube'] = $youtube;
            $users['linkedin'] = $linkedin;
            $users['twitter'] = $twitter;
            $users['pinterest'] = $pinterest;

            $social_network = array(
                array(
                    "url" => strtolower($facebook),
                    "image" => $base_url."/social_icons/facebook.png"
                ),
                array(
                    "url" => strtolower($instagram),
                    "image" => $base_url."/social_icons/instagram.png"
                ),
                array(
                    "url" => strtolower($youtube),
                    "image" => $base_url."/social_icons/youtube.png"
                ),
                array(
                    "url" => strtolower($linkedin),
                    "image" => $base_url."/social_icons/linkedin.png"
                ),
                array(
                    "url" => strtolower($twitter),
                    "image" => $base_url."/social_icons/twitter.png"
                ),
                array(
                    "url" => strtolower($pinterest),
                    "image" => $base_url."/social_icons/pinterest.png"
                )
            );
            $s_network = [];
            foreach($social_network as $key => $social){
                if($social['url'] != ''){
                    $s_network[] = $social;
                }
            }
            $status = array(
                'error' => false,
                'user' => $users,
                'social_network' => $s_network,
                'departures' => [],
                'departure_status'=> "",
                'user_type' => 'buyer',
                'page' => $request->page,
                'total'=> 0,
                'message'=>"Success!"
            );
        }
        return Response($status,200); 
    }
}
