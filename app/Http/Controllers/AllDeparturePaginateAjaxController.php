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
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use App\Notification;
use App\Traits\FireBaseNotification;
use App\Helpers\MailHelper;

class AllDeparturePaginateAjaxController extends Controller
{
    use FireBaseNotification;

    public function get_booking_modal(Request $request){ 
        $departure=$request->all(); 
        $columns = DepartureColumnType::where('departure_type_id', $departure['departure_type'])->get()->pluck('column_name_id');
        $columns = json_encode($columns); 
         return view('ajax_paginate.booking_modal',compact('departure','columns'));    
    }
     public function get_hold_modal(Request $request){ 
        $departure=$request->all(); 
        $columns = DepartureColumnType::where('departure_type_id', $departure['departure_type'])->get()->pluck('column_name_id');
        $columns = json_encode($columns); 
         return view('ajax_paginate.hold_modal',compact('departure','columns'));    
    }
    public function allDeparturePaginate(Request $request){ 
        $getData = MailHelper::setMailConfig();
        $from_date = $request->from;
        $to_date = $request->to;
        $date = date("Y-m-d");
        $from = $request->departure_from;
        $to = $request->departure_to;
        $status_filter =$request->status;
        $keywords = $request->keyword;
        $publisher =$request->publiser_name;
        if($request->publiser_name){
            $publisher_n =$request->publiser_name;
        }
        else{
            $publisher_n = [];
        }
        //dd($request->type);
        //from searchbox
        $search_key = $request->keyword; 
        $req_type = $request->type;
        $total_page=0;
        if($request->type != ""){
            if($request->type == 15){
                $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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
                $total_departure = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')->count();
                $total_page=$total_departure/45;
                $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->where('approve',1)
                    ->where('status',1)
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 13){
                $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures13 = Departure::where('status',1)
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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
                $departures13 = [];
                $dep_all = array_merge($departures13t,$departures13);
                $departure_unique = array_unique($dep_all);

                $total_departure = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')->count();
                $total_page=$total_departure/45;
                $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->where('approve',1)
                    ->where('status',1)
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 11){
                $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                $departures11t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                    
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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

                $total_departure = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')->count();
                $total_page=$total_departure/45;
                $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->where('approve',1)
                    ->where('status',1)
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 12){
                $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                $departures12t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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
                
                $total_departure = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')->count();
                $total_page=$total_departure/45;
                $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->where('approve',1)
                    ->where('status',1)
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 14){
                $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                $departures14t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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
                
                $total_departure = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')->count();
                $total_page=$total_departure/45;
                $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->where('approve',1)
                    ->where('status',1)
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
        }elseif(($request->type == "" && $request->keyword == "") && ($request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name)){
                
                $departuresNull = Departure::where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->where(function ($query) use($request){
                        if($request->from != '' || $request->to != ''){
                            $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                            ->whereDate('start_date', '>=', date("Y-m-d"));
                        }
                        if($request->departure_from || $request->departure_to){
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

                $total_departure = Departure::whereIn('id',$departuresNull)
                    ->orderBy('start_date', 'ASC')->count();
                $total_page=$total_departure/45;
                $total_page=(int)ceil($total_page);

                $departures = Departure::whereIn('id',$departuresNull)
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            //dd($departures);
        }else{
            $departures = Departure::where('approve',1)
            ->where('status',1)
            ->whereDate('start_date', '>=', date("Y-m-d"))
            ->orderBy('start_date', 'ASC')
            ->simplePaginate(24);

            $total_departure = Departure::where('approve',1)
            ->where('status',1)
            ->whereDate('start_date', '>=', date("Y-m-d"))
            ->orderBy('start_date', 'ASC')->count();
            $total_page=$total_departure/45;
            $total_page=(int)ceil($total_page);

            

        }
        $columns = "";
        $departure_types = new \stdClass();
        if(count($departures)>0){
            foreach ($departures as $key => $value) {
                $userId = User::where(['tenant_id'=>$value->tenant_id, 'main_user_type'=>1,'user_type'=>0])->select('id')->first();
                $value->profileUserId = $userId->id;
                $hold = HoldDepartureSeat::where('departure_id',$value->id) 
                        ->sum('hold_seat');
                if($hold){
                    $value->hold_sum = $hold;
                }
                else{
                    $value->hold_sum = 0;
                }


                //return $value->id;
                $book = BookDeparture::where('departure_id',$value->id) 
                        ->where('status',1)->sum('booked_seat');
                $single_book = BookDeparture::where('departure_id',$value->id) 
                        ->where('status',1)
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
               //return $value->book_sum
                //user logo
                $logo_u = DB::table('users')->where('tenant_id',$value->tenant_id)->value('logo');
                $value->logo_image = url('companyLogo').'/'.$logo_u;
                // Last Updated Departure
                $last_updated = DB::table('departures')->where('id',$value->id)->value('updated_at');
                $last_updated1 = DB::table('inclusions')->where('departure_id',$value->id)->value('updated_at');
                $last_updated2 = DB::table('return_flight_details')->where('departure_id',$value->id)->value('updated_at');
                $last_updated3 = DB::table('departure_flight_details')->where('departure_id',$value->id)->value('updated_at');
                $last_updated4 = DB::table('agent_itineraries')->where('departure_id',$value->id)->value('updated_at');
                $last_updated5 = DB::table('departure_prices')->where('departure_id',$value->id)->get();
                $last_updated6 = DB::table('payment_schedules')->where('departure_id',$value->id)->value('updated_at');
                $last_updated7 = DB::table('cancel_schedules')->where('departure_id',$value->id)->value('updated_at');

                $last_max = max($last_updated,$last_updated1,$last_updated2,$last_updated3,$last_updated4,$last_updated5,$last_updated6,$last_updated7);
                $value->last_updated_dep = $last_max;
                // Inclusion icons
                $inclu_icons = Inclusion::where('departure_id',$value->id)->where('icon','!=', null)->select('icon','name')->get();
                foreach ($inclu_icons as $key => $inclu_icon) {
                    $inclu_icon->icon = url('inclusion-images').'/'.$inclu_icon->icon;
                }
                $value->inclusion_icons = $inclu_icons;

                $columns = DepartureColumnType::where('departure_type_id', $value->departure_type)->get()->pluck('column_name_id');
                $columns = json_encode($columns);
                $departure_destination = DB::table('departure_destinations')
                    ->join('destinations','departure_destinations.destination_id','destinations.id')
                    ->select('destinations.country_name','destinations.dest_name')
                    ->where('departure_destinations.departure_id',$value->id)
                    ->get();
                $users = User::where(['tenant_id'=>$value->tenant_id, 'main_user_type'=>1,'user_type'=>0])->first();
                $value->destination = $departure_destination;
                $other_price = DB::table('prices')->where('departure_id',$value->id)->where('price_type_id',3)->get();
                $single_price = Price::where('departure_id',$value->id)->where('price_type_id',4)->get();
                 $value->singlePrice = $single_price;
                 $value->OtherPrice = $other_price;

                $prices1 = DeparturePrice::where('departure_id',$value->id)
                        ->where('default_price',1)
                        ->take(1)->get();
                $prices2 = DeparturePrice::where('departure_id',$value->id)
                        ->where('sharing','Double')
                        ->where('transport_type','SIC')
                        ->take(1)->get();
                $prices3 = DeparturePrice::where('departure_id',$value->id)
                        ->where('sharing','Double')
                        ->take(1)->get();
                $prices4 = DeparturePrice::where('departure_id',$value->id)
                        ->take(1)->get();
                        //dd($prices3);
                       
                if(count($prices1)>0){
                    $value->price = $prices1;
                }elseif(count($prices2)>0){
                    $value->price = $prices2;
                }elseif(count($prices3)>0){
                    $value->price = $prices3;
                }else{
                    $value->price = $prices4;
                }
                 
                $departure_prices = DeparturePrice::where('departure_id',$value->id)->where('status',1)->get();
                $value->departure_price = $departure_prices;
                // dd($value);
                // $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','???','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
                // $strlower = Str::lower($removeSC);
                // $arr_cn = explode(' ', $strlower);
                // $str_cn = implode('-', $arr_cn);
                // $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
                // $mainstr = rtrim($mainstrs, '-');
                $value->company_url = strtolower($users->company_id);
                //$bd->company_name = $user_data->company_name;
            }
        }
       

        $release = HoldDepartureSeat::all();
        //$now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
        foreach($release as $row){
            //$add = $row->hold_duration + 5 ;
            $now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
            if($now >= $row->auto_release){    
                $hold =$row->hold_seat;
                $departure = $row->departure_id;
                $dep = Departure::find($departure);
                $available = $dep->available_seat;
                //Notification
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud - Hold Units Released';
                $noti_save->body = $hold.' units held by you in departure for' .$dep->ending_at. ' as per policy defined by supplier.';

                $noti_save->body_html = '<p><b> '.$hold.' </b>units held by you in departure for<b> ' .$dep->ending_at. ' </b>as per policy defined by supplier.</p>';
                $noti_save->user_id = $row->user_id;
                $noti_save->type = "AutoRelease";
                $noti_save->url_1 = url('login');
                $noti_save->save();
                
                $last_body_sup = $noti_save->body;
                $last_title_sup = $noti_save->title;
                $last_link_sup = $noti_save->url_1;
                $users = User::where('id',$row->user_id)->first();

                $firebaseToken = User::where('id',$row->user_id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_save->id);

                Mail::send('mail.release_hold', ['hold' => $hold,'destination'=>$dep->ending_at,'user'=>$users,'forceRelease'=>'no'], function ($m) use ($users, $getData) {
                    $m->from($getData['from_mail'], $getData['from_name']);
                    $m->to($users->email);
                    //$m->to('raj.kumar@watconsultingservices.com');
                    $m->subject('Departure cloud  -Hold Units Released');
                });


                
                $update = Departure::find($departure);
                $update->available_seat = $available + $hold;
                $update->save();
                HoldDepartureSeat::find($row->id)->delete();
                
            }
            //return $row->date;
        }

        $holdduration = HoldDuration::all(); 
        $departureCount = Departure::all();
        $total = count($departureCount);
        $active_departureCount = Departure::where('dep_type','main')
                        //->whereDate('start_date', '>=', $date)
                         ->where('approve',1)
                         ->where('status',1)
                        ->get();
        $active = count($active_departureCount);
        $pending_departureCount = Departure::where('dep_type','main')
                        ->whereDate('start_date', '>=', $date)
                        ->where('approve',0)
                        ->where('status',1)
                        ->get();
        $pending = count($pending_departureCount);
        $inactive_departureCount = Departure::where('dep_type','main')
                        ->whereDate('start_date', '<=', $date)
                        ->where('approve',1)
                        ->where('status',1)
                        ->get();
        $inactive = count($inactive_departureCount);
         
        if($request->id == 'table'){
           return $card_table = $request->id;
        }
        else{
             $card_table = 'card';
        } 
        //dd($total);
        return view('ajax_paginate.all_departure',compact('departures','total','to_date','from_date','holdduration','active','inactive','pending','from','to','status_filter','card_table','publisher_n','search_key','req_type','columns','total_page'));    
        // return view('ajax_paginate.all_departure',compact('departures','total','to_date','from_date','holdduration','active','inactive','pending','from','to','status_filter','card_table','publisher_n','keywords'));    
    }
    public function allDeparture_ajax(Request $request){
         
        $from_date = $request->from;
        $to_date = $request->to;
        $date = date("Y-m-d");
        $from = $request->departure_from;
        $to = $request->departure_to;
        $status_filter =$request->status;
        $keywords = $request->keyword;
        $publisher =$request->publiser_name;
        if($request->publiser_name){
            $publisher_n =$request->publiser_name;
        }
        else{
            $publisher_n = [];
        }
        //from searchbox
        $search_key = $request->keyword; 
        $req_type = $request->type;
        if($request->type != null){
            if($request->type == 15){
                $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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

                $departures = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 13){
                $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures13 = Departure::where('status',1)
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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
                $departures13 = [];
                $dep_all = array_merge($departures13t,$departures13);
                $departure_unique = array_unique($dep_all);

                $departures = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 11){
                $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                $departures11t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                    
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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

                $departures = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
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
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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

                $departures = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
            if($request->type == 14){
                $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                $departures14t = Departure::whereIn('id', $departure_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
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
                            if($request->from != '' || $request->to != ''){
                                $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
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

                $departures = Departure::whereIn('id',$departure_unique)
                    ->orderBy('start_date', 'ASC')
                    ->simplePaginate(24);
            }
        }else{
            $departures = Departure::where('approve',1)
            ->where('status',1)
            ->whereDate('start_date', '>=', date("Y-m-d"))
            ->orderBy('start_date', 'ASC')
            ->simplePaginate(24);
        }
        $columns = "";
        $departure_types = new \stdClass();
        if(count($departures)>0){
            foreach ($departures as $key => $value) {
                $userId = User::where(['tenant_id'=>$value->tenant_id, 'main_user_type'=>1,'user_type'=>0])->select('id')->first();
                $value->profileUserId = $userId->id;
                $hold = HoldDepartureSeat::where('departure_id',$value->id) 
                        ->sum('hold_seat');
                if($hold){
                    $value->hold_sum = $hold;
                }
                else{
                    $value->hold_sum = 0;
                }


                //return $value->id;
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
               //return $value->book_sum
                //user logo
                $logo_u = DB::table('users')->where('tenant_id',$value->tenant_id)->value('logo');
                $value->logo_image = url('companyLogo').'/'.$logo_u;
                // Last Updated Departure
                $last_updated = DB::table('departures')->where('id',$value->id)->value('updated_at');
                $last_updated1 = DB::table('inclusions')->where('departure_id',$value->id)->value('updated_at');
                $last_updated2 = DB::table('return_flight_details')->where('departure_id',$value->id)->value('updated_at');
                $last_updated3 = DB::table('departure_flight_details')->where('departure_id',$value->id)->value('updated_at');
                $last_updated4 = DB::table('agent_itineraries')->where('departure_id',$value->id)->value('updated_at');
                $last_updated5 = DB::table('departure_prices')->where('departure_id',$value->id)->value('updated_at');
                $last_updated6 = DB::table('payment_schedules')->where('departure_id',$value->id)->value('updated_at');
                $last_updated7 = DB::table('cancel_schedules')->where('departure_id',$value->id)->value('updated_at');

                $last_max = max($last_updated,$last_updated1,$last_updated2,$last_updated3,$last_updated4,$last_updated5,$last_updated6,$last_updated7);
                $value->last_updated_dep = $last_max;
                // Inclusion icons
                $inclu_icons = Inclusion::where('departure_id',$value->id)->where('icon','!=', null)->select('icon','name')->get();
                foreach ($inclu_icons as $key => $inclu_icon) {
                    $inclu_icon->icon = url('inclusion-images').'/'.$inclu_icon->icon;
                }
                $value->inclusion_icons = $inclu_icons;

                $columns = DepartureColumnType::where('departure_type_id', $value->departure_type)->get()->pluck('column_name_id');
                $columns = json_encode($columns);
            }
        }
       

       $release = HoldDepartureSeat::all();
       //$now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
        foreach($release as $row){
            //$add = $row->hold_duration + 5 ;
            $now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
            if($now >= $row->auto_release){    
                $hold =$row->hold_seat;
                $departure = $row->departure_id;
                $dep = Departure::find($departure);
                $available = $dep->available_seat;
                ////////////////////////////////


                //Notification
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud - Hold Units Released';
                $noti_save->body = $hold.' units held by you in departure for' .$dep->ending_at. ' as per policy defined by supplier';

                $noti_save->body_html = '<p><b> '.$hold.' </b>units held by you in departure for<b> ' .$dep->ending_at. ' </b>as per policy defined by supplier</p>';
                $noti_save->user_id = $row->user_id;
                $noti_save->type = "AutoRelease";
                $noti_save->url_1 =url('login');
                $noti_save->save();
                
                $last_body_sup = $noti_save->body;
                $last_title_sup = $noti_save->title;
                $last_link_sup = $noti_save->url_1;
                $users = User::where('id',$row->user_id)->first();

                $firebaseToken = User::where('id',$row->user_id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_save->id);

                Mail::send('mail.release_hold', ['hold' => $hold,'destination'=>$dep->ending_at,'user'=>$users,'forceRelease'=>'no'], function ($m) use ($users, $getData) {
                    $m->from($getData['from_mail'], $getData['from_name']);
                    $m->to($users->email);
                    //$m->to('raj.kumar@watconsultingservices.com');
                    $m->subject('Departure cloud  -Hold Units Released');
                });
                $update = Departure::find($departure);
                $update->available_seat = $available + $hold;
                $update->save();
                HoldDepartureSeat::find($row->id)->delete();
            }
            //return $row->date;
        }

        $holdduration = HoldDuration::all(); 
        $departureCount = Departure::all();
        $total = count($departureCount);
        $active_departureCount = Departure::where('dep_type','main')
                        //->whereDate('start_date', '>=', $date)
                         ->where('approve',1)
                         ->where('status',1)
                        ->get();
        $active = count($active_departureCount);
        $pending_departureCount = Departure::where('dep_type','main')
                        ->whereDate('start_date', '>=', $date)
                        ->where('approve',0)
                        ->where('status',1)
                        ->get();
        $pending = count($pending_departureCount);
        $inactive_departureCount = Departure::where('dep_type','main')
                        ->whereDate('start_date', '<=', $date)
                        ->where('approve',1)
                        ->where('status',1)
                        ->get();
        $inactive = count($inactive_departureCount);

        foreach($departures as $row){
        $departure_destination = DB::table('departure_destinations')
            ->join('destinations','departure_destinations.destination_id','destinations.id')
            ->select('destinations.country_name','destinations.dest_name')
            ->where('departure_destinations.departure_id',$row->id)
            ->get();
        $users = User::where(['tenant_id'=>$row->tenant_id, 'main_user_type'=>1,'user_type'=>0])->first();
        $row->destination = $departure_destination;
        $other_price = DB::table('prices')->where('departure_id',$row->id)->where('price_type_id',3)->get();
        $single_price = Price::where('departure_id',$row->id)->where('price_type_id',4)->get();
         $row->singlePrice = $single_price;
         $row->OtherPrice = $other_price;

         $prices = DeparturePrice::where('departure_id',$row->id)->take(1)->get();
         $row->price = $prices;
         $departure_prices = DeparturePrice::where('departure_id',$row->id)->where('status',1)->get();
         $row->departure_price = $departure_prices;

        $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','???','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
             $strlower = Str::lower($removeSC);
             $arr_cn = explode(' ', $strlower);
             $str_cn = implode('-', $arr_cn);
             $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
            $mainstr = rtrim($mainstrs, '-');
            $row->company_url = $mainstr;
             //$bd->company_name = $user_data->company_name;
        }
         
        if($request->id == 'table'){
           return $card_table = $request->id;
        }
        else{
             $card_table = 'card';
        }  
        
       
        $view_type =$request->view_type;
        if($view_type=='box'){
        return view('ajax_paginate.all_departure_ajax',compact('departures','total','to_date','from_date','holdduration','active','inactive','pending','from','to','status_filter','card_table','publisher_n','keywords'));  

        }else{
            return view('ajax_paginate.all_departure_table_ajax',compact('departures','total','to_date','from_date','holdduration','active','inactive','pending','from','to','status_filter','card_table','publisher_n','keywords'));  

        }  
    }   

    function fetchData(Request $request)
    {
        $getData = MailHelper::setMailConfig();
        if($request->get('query'))
        {
            $query = $request->get('query');
            $output="";
            // destination
            $dest_data = DB::table('destinations')
                ->where('dest_name', 'LIKE', "%{$query}%")
                ->orWhere('region', 'LIKE', "%{$query}%")
                ->orWhere('actualname', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();
            
             if(count($dest_data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Destination(s)</p>';
                   $cnt=0;
                    foreach($dest_data as $row)
                    {
                        $route = url("all-departures").'?type=11&keyword='.$row->dest_name;

                        $dep_list_cls="";
                        $dep_read_more_text="";
                        if($cnt>10){
                            $dep_list_cls="dest_hide";
                            $dep_read_more=true;
                        }
                        $output .= '
                        <li class="list-item '.$dep_list_cls.'"><a href="'.$route.'">'.$row->dest_name.' (<span><strong>'.$row->region.'</strong></span>)</a></li>
                        ';
                        $cnt++;
                    }
                    $output .= '</ul>';
             } 
             //country search

             $country_data = DB::table('countries')
                ->where('country_name', 'LIKE', "%{$query}%")
                ->orWhere('continent', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();
            
             if(count($country_data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Countries</p>';
                   $cnt=0;
                    foreach($country_data as $row)
                    {
                        $route = url("all-departures").'?type=12&keyword='.$row->country_name;
                        $dep_list_cls="";
                        $dep_read_more_text="";
                        if($cnt>5){
                            $dep_list_cls="country_hide";
                            $dep_read_more=true;
                        }
                        $output .= '
                        <li class="list-item '.$dep_list_cls.'"><a href="'.$route.'">'.$row->country_name.' (<span><strong>'.$row->continent.'</strong></span>)</a></li>
                        ';
                        $cnt++;
                    }
                    $output .= '</ul>';
             }  

             //publisher search
            $pub_data = DB::table('departures')
                ->where('company_name', 'LIKE', "%{$query}%")
                ->select('company_name')
                ->groupBy('company_name')
                ->limit(10)
                ->get();

             $dep_read_more=false;
             if(count($pub_data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Publisher(s)</p>';
                   $cnt=0;
                    foreach($pub_data as $row)
                    {
                        $route = url("all-departures").'?type=13&keyword='.$row->company_name;
                        $dep_list_cls="";
                        $dep_read_more_text="";
                        if($cnt>10){
                            $dep_list_cls="pub_hide";
                            $dep_read_more=true;
                        }
                        if($cnt==4){
                            // $dep_read_more_text="<p><a href='javascript:void(0)'>More</a></p>";
                        }
                        $output .= '
                        <li class="list-item '.$dep_list_cls.'"><a href="'.$route.'">'.$row->company_name.'</a></li>
                        ';
                        $cnt++;
                    }
                    $output .= '</ul>';
            }

             //# tag search
            $depTag_data = DB::table('departure_tags')
                ->where('name', 'LIKE', "%{$query}%")
                ->select('name')
                ->groupBy('name')
                ->limit(10)
                ->get();
             if(count($depTag_data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Tag(s)</p>';
                   $cnt=0;
                    foreach($depTag_data as $row)
                    {
                        $route = url("all-departures").'?type=14&keyword='.$row->name;
                        $dep_list_cls="";
                        $dep_read_more_text="";
                        if($cnt>10){
                            $dep_list_cls="depTag_hide";
                            $dep_read_more=true;
                        }
                        $output .= '
                        <li class="list-item '.$dep_list_cls.'"><a href="'.$route.'">'.$row->name.'</a></li>
                        ';
                        $cnt++;
                    }
                    $output .= '</ul>';
             }  

             //departure search
             $data = DB::table('departures')
                ->where('approve',1)
                ->whereDate('start_date', '>=', date("Y-m-d"))
                ->where('title', 'LIKE', "%{$query}%")
                ->orWhere('company_name', 'LIKE', "%{$query}%")
                
                ->limit(10)
                ->get();

             $dep_read_more=false;
             if(count($data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Departure(s)</p>';
                   $cnt=0;
                    foreach($data as $row)
                    {
                        $route = url("all-departures").'?type=15&keyword='.$row->title;
                        $dep_list_cls="";
                        $dep_read_more_text="";
                        if($cnt>10){
                            $dep_list_cls="dep_hide";
                            $dep_read_more=true;
                        }
                        if($cnt==4){
                            // $dep_read_more_text="<p><a href='javascript:void(0)'>More</a></p>";
                        }
                        $output .= '
                        <li class="list-item '.$dep_list_cls.'"><a href="'.$route.'">'.$row->title.'<p><strong>'.$row->company_name.'</strong><span>'.date('d M, Y', strtotime($row->start_date)).'</span></p></a></li>
                        ';
                        $cnt++;
                    }
                    $output .= '</ul>';
             }
            
            echo $output;
        }
    }
}
