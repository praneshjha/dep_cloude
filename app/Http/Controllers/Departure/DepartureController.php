<?php

namespace App\Http\Controllers\Departure;

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
use App\Helpers\MailHelper;
class DepartureController extends Controller
{
    use FireBaseNotification;
    public function uniqueEmailCheck(Request $request){
         //print_r($request->get('pkg_id'));die;
       if($request->get('pkg_id')){
           $unique_id = $request->get('pkg_id');
           $id_count = User::where('email','LIKE', '%' . $unique_id . '%' )
                       ->count();
           if($id_count>0){
               echo 'not-unique';
           }
           else{
              echo 'unique';
           }

       }
    }
    public function departureIndex(Request $request)
    {
        //dd($request->route()->uri);
      if(Auth::user()->main_user_type == 1)
      {
        $keyword = $request->keyword;
        $status = $request->status;
        $permission = User::getPermissions();
        $date = date("Y-m-d");
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $departures = Departure::where('tenant_id',auth()->user()->tenant_id)
                ->where(function ($query) use($request){
                    if($request->keyword != ''){
                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%');
                    }
                    if($request->from_date != '' || $request->to_date != ''){
                        $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from_date))),(date("Y-m-d", strtotime($request->to_date)))]);
                        //->whereDate('start_date', '>=', date("Y-m-d"));
                    }
                    if($request->status == 5){
                      $query->where('approve',1)
                            ->where('status',1)
                            ->where('available_seat','>',0)
                            ->whereDate('start_date', '>=', date("Y-m-d"));

                    }
                    if($request->status == 4){
                      $query->where('approve',1)
                            ->where('status',1)
                            ->where('available_seat',0)
                            ->whereDate('start_date', '>=', date("Y-m-d"));

                    }
                    if($request->status == 3){
                      $query->where('approve',0)
                            ->where('status',0)
                            ->whereDate('start_date', '>=', date("Y-m-d"));

                    }
                    if($request->status == 2){
                      $query->where('approve',0)
                            ->where('status',1)
                            ->whereDate('start_date', '>=', date("Y-m-d"));

                    }
                    if($request->status == 1){
                      $query->whereDate('start_date', '<', date("Y-m-d"));
                            // ->where('available_seat','>',0)
                            // ->where('status',1)
                            // ->where('approve',1)
                    }
                })
            ->orderBy('start_date', 'DESC')
            ->distinct()
            ->paginate(35);


       // if($keyword){
       //      $departures = DB::table('departures')
       //            ->where('user_id',Auth::user()->id)
       //            ->where('dep_id', 'like', '%' . $keyword . '%')
       //            ->orWhere('title', 'like', '%' . $keyword . '%')
       //            ->orWhere('id', 'like', '%' . $keyword . '%')
       //            ->orWhere('from', 'like', '%' . $keyword . '%')
       //            ->orWhere('ending_at', 'like', '%' . $keyword . '%')
       //             ->orWhere('dep_id', 'like', '%' . $keyword . '%')
       //            ->orderBy('start_date', 'DESC')
       //            ->distinct()
       //            ->paginate(25);

       //  }
       //  elseif($status){
       //      if($status == 5){
       //          $departures = DB::table('departures')
       //            ->where('user_id',Auth::user()->id)
       //            ->where('status',1)
       //            ->where('approve',1)
       //            ->where('available_seat','>',0)
       //            ->whereDate('start_date', '>=', $date)
       //            ->orderBy('start_date', 'DESC')
       //            ->distinct()
       //            ->paginate(24);
       //      }
       //      elseif($status == 4){
       //          $departures = DB::table('departures')
       //            ->where('user_id',Auth::user()->id)
       //            ->where('status',1)
       //            ->where('approve',1)
       //            ->where('available_seat',0)
       //            ->whereDate('start_date', '>=', $date)
       //            ->orderBy('start_date', 'DESC')
       //            ->distinct()
       //            ->paginate(24);
       //      }
       //      elseif($status == 3){
       //         $departures = DB::table('departures')
       //            ->where('user_id',Auth::user()->id)
       //            ->whereDate('start_date', '>=', $date)
       //            ->where('approve',0)
       //            ->where('status',0)
       //            ->orderBy('start_date', 'DESC')
       //            ->distinct()
       //            ->paginate(24);
       //           //return count($departures);
       //      }
       //      elseif($status == 2){
       //          $departures = DB::table('departures')
       //            ->where('user_id',Auth::user()->id)
       //            ->where('status',1)
       //            ->where('approve',0)
       //            ->whereDate('start_date', '>=', $date)
       //            ->orderBy('start_date', 'DESC')
       //            ->distinct()
       //            ->paginate(24);
       //      }
       //      elseif($status == 1){

       //          $departures = DB::table('departures')
       //            ->where('user_id',Auth::user()->id)
       //            //->where('status',1)
       //            //->where('approve',1)
       //            ->whereDate('start_date', '<', $date)
       //            ->orderBy('start_date', 'DESC')
       //            ->distinct()
       //            ->paginate(24);
       //      }


       //  }
       //  else{
       //        $departures = Departure::where('dep_type','main')
       //                  ->where('tenant_id',auth()->user()->tenant_id)
       //                  //->orderBy('start_date', 'ASC')
       //                  ->orderBy('start_date', 'DESC')
       //                  ->paginate(24);
       //  }
        //$hold_array=[];

        foreach($departures as $departure)
        {

            $departure_prices = DeparturePrice::where('departure_id',$departure->id)->where('status',1)->get();
            $departure->departure_price = $departure_prices;
        }
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

            }
        }
        if(count($departures)>0){
            foreach ($departures as $key => $value) {
               $book = BookDeparture::where('departure_id',$value->id)
                     ->where('status',1)
                     ->sum('booked_seat');
               $single_book = BookDeparture::where('departure_id',$value->id)
                            ->sum('single_supplement_booked_seat');
               $book_date = DB::table('book_departures')
                            ->join('users','book_departures.user_id','users.id')
                            ->where('book_departures.departure_id',$value->id)
                            ->select('book_departures.*','users.company_name')->get();
               $prices = DeparturePrice::where('departure_id',$value->id)->take(1)->get();
               $value->price = $prices;
                $value->book_date = $book_date;
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


               $hold_till = DB::table('hold_departures')->where('departure_id',$value->id)->get();
                if(count($hold_till)>0){
                    foreach($hold_till as $row){
                      if($row->departure_id == $value->id){
                      $hold = $row->hold_till;
                      }
                     }
                }else{
                    $hold = 0;
                }

                $today = date("Y-m-d");
                $date1=date_create($today);
                $date2=date_create($value->start_date);
                $diff=date_diff($date1,$date2);
                $date = $diff->format("%R%a");

                if(($hold < $date) && ($value->available_seat > 0)){
                    $popup = '.bd-example-modal-sm';
                }
                else{
                    $popup = 0;
                }
           $inclusion = DB::table('inclusions')
                        ->where('departure_id',$value->id)
                        ->count();
                if( $inclusion > 0){
                    $value->inclusion = $inclusion;
                }else{
                     $value->inclusion = 0;
                }
            $DeparturePrice = DeparturePrice::where('departure_id',$value->id)->count();
               if($DeparturePrice>0){
                $value->DeparturePrice=$DeparturePrice;
               }else{
                $value->DeparturePrice=0;
               }
            $payment_schedule = PaymentSchedule::where('departure_id',$value->id)->count();
               if($payment_schedule>0){
                $value->payment_schedule = $payment_schedule;
               }else{
                $value->payment_schedule = 0;
               }
               $cancelation_schedule = CancelSchedule::where('departure_id',$value->id)->count();
               if($cancelation_schedule>0){
                $value->cancelation_schedule = $cancelation_schedule;
               }else{
                $value->cancelation_schedule = 0;
               }

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
            }

        }
        else{
            $columns = [];
            $popup = 0;
            $hold = 0;
        }
        foreach($departures as $row){
            $departure_destination = DB::table('departure_destinations')
            ->join('destinations','departure_destinations.destination_id','destinations.id')
            ->select('destinations.country_name','destinations.dest_name')
            ->where('departure_destinations.departure_id',$row->id)
            ->get();

            //dd($row->departure_price = $departure_prices);
           }

        $holdduration = HoldDuration::all();
        $departureCount = Departure::where('dep_type','main')
                        ->where('tenant_id',auth()->user()->tenant_id)
                        ->where('user_id',Auth::user()->id)
                        ->get();
        $total = count($departureCount);
        $active_departureCount = Departure::where('dep_type','main')
                        ->where('tenant_id',auth()->user()->tenant_id)
                        ->whereDate('start_date', '>=', $date)
                        ->where('approve',1)
                        ->get();
        $active = count($active_departureCount);
        $pending_departureCount = Departure::where('dep_type','main')
                        ->where('tenant_id',auth()->user()->tenant_id)
                        ->whereDate('start_date', '>=', $date)
                        ->where('approve',0)
                        ->get();
        $pending = count($pending_departureCount);
        $inactive_departureCount = Departure::where('dep_type','main')
                        ->where('tenant_id',auth()->user()->tenant_id)
                        ->whereDate('start_date', '<=', $date)
                        ->where('approve',1)
                        ->get();
        $inactive = count($inactive_departureCount);
        $departure_types = DepartureType::all();
        $departure_ids = Departure::where('status',1)
                ->where('approve',1)
                ->whereDate('start_date', '>=', date("Y-m-d"))
                ->pluck('id')
                ->toArray();
        $country_id = CountryDeparture::whereIn('departure_id',$departure_ids)
                ->distinct()
                ->pluck('country_id')
                ->toArray();
        $countryDeparturePublic= DB::table('countries')
            ->whereIn('id',$country_id)
            ->distinct()
            ->get();
        $totalDep = Departure::where('tenant_id',auth()->user()->tenant_id)->count();
        $openDep = Departure::where('tenant_id',auth()->user()->tenant_id)
            ->whereDate('start_date', '>=', date("Y-m-d"))->count();

         if($request->ajax()){
            return view('departure.departure_index_data',compact('departures','holdduration','permission','columns','departure_types','countryDeparturePublic'));
       }
        return view('departure.departure_index',compact('departures','total','holdduration','pending','inactive','active','popup','hold','date','permission','keyword','status','columns','departure_types','countryDeparturePublic','from_date','to_date','totalDep','openDep'));
        }
     else
     {
        return view('404');
     }
    }

    public function allDeparture(Request $request){
        return view('404');
        //return $request->id;
        $from_date = $request->from;
        $to_date = $request->to;
        $date = date("Y-m-d");
        $from = $request->departure_from;
        $to = $request->departure_to;
        $status_filter =$request->status;
        $keywords = $request->keywords;
        $publisher =$request->publiser_name;
        if($request->publiser_name){
            $publisher_n =$request->publiser_name;
        }
        else{
            $publisher_n = [];
        }
        //from searchbox
        $search_key = $request->keyword;
        //dd($search_key);
        //$search_type = $request->search;
        $req_type = $request->type;
        // if(($from_date && $to_date && $from && $to)){

        //     $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->Where('from',$request->departure_from)
        //                 ->Where('ending_at',$request->departure_to)
        //                 ->WhereBetween('start_date',[$from_date, $to_date])
        //                 //->where('available_seat','>',$status_filter)
        //                 ->orderBy('id', 'DESC')
        //                 ->paginate(24);
        //    }
        // elseif($from && $to){
        //     $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->Where('from',$request->departure_from)
        //                 ->Where('ending_at',$request->departure_to)
        //                 ->orderBy('start_date', 'DESC')
        //                 ->paginate(24);
        //    }
        //    elseif($from){
        //      $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->Where('from',$request->departure_from)
        //                 //->where('available_seat','!=',0)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->orderBy('start_date', 'DESC')
        //                 ->paginate(24);
        //    }
        //    elseif($to){
        //     $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->Where('ending_at',$request->departure_to)
        //                 //->where('available_seat','!=',0)
        //                 ->orderBy('start_date', 'DESC')
        //                 ->paginate(24);
        //    }
        //    elseif(($from_date && $to_date)){
        //     $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->WhereBetween('start_date',[$from_date, $to_date])
        //                 ->orderBy('start_date', 'DESC')
        //                 ->paginate(24);
        //    }
        //    elseif(($from_date)){
        //     $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->Where('start_date',$from_date)
        //                 ->orderBy('start_date', 'DESC')
        //                 ->paginate(24);
        //    }
        //    elseif(($to_date)){
        //     $departures = Departure::where('status',1)
        //                 ->where('approve',1)
        //                 ->whereDate('start_date', '>=', $date)
        //                 ->WhereBetween('start_date',$to_date)
        //                 ->orderBy('start_date', 'DESC')
        //                 ->paginate(24);
        //    }
        //     elseif($status_filter == 1){
        //         $departures = Departure::where('status',1)
        //         ->where('approve',1)
        //         ->whereDate('start_date', '>=', $date)
        //         ->where('available_seat','>',0)
        //         ->orderBy('start_date', 'DESC')
        //         ->paginate(24);
        //        }
        //     elseif($status_filter == 2 ){
        //        $departures = Departure::where('status',1)
        //          ->where('approve',1)
        //          ->whereDate('start_date', '>=', $date)
        //          ->where('available_seat','=',0)
        //          ->orderBy('start_date', 'DESC')
        //          ->paginate(24);
        //         // dd($departures);
        //         }


        //     elseif(Auth::user()->main_user_type == 2)
        //     {
        //         $departures = Departure::
        //                     where('dep_type','main')
        //                     ->where('status',1)
        //                     ->where('approve',1)
        //                     ->whereDate('start_date', '>=', $date)
        //                     ->orderBy('start_date', 'DESC')
        //                     ->paginate(24);
        //         //dd($departures);
        //     }
        //     elseif($publisher)
        //     {
        //         $departures = Departure::
        //                     where('dep_type','main')
        //                     ->where('status',1)
        //                     ->where('approve',1)
        //                     ->whereDate('start_date', '>=', $date)
        //                     ->where('departure_ownner',$publisher)
        //                     ->orderBy('start_date', 'DESC')
        //                     ->paginate(24);
        //         //dd($departures);
        //     }
        //     else{
        //         $departures = Departure::
        //                       where('dep_type','main')
        //                     ->where('status',1)
        //                     ->where('approve',1)
        //                     ->whereDate('start_date', '>=', $date)
        //                     ->orderBy('start_date', 'DESC')
        //                     ->paginate(24);

        //     }
        // if($request->route('id'))
        //dd($request->search);
        if($request->type != null){
            if($request->type == 15){
                $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                    // ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keywords || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures15 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keywords != ''){
                                $query->where('title', 'LIKE','%'.$request->keywords.'%')
                                    // ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keywords.'%');
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
                    ->simplePaginate(45);
            }
            if($request->type == 13){
                $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                    // ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keywords || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures13 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keywords != ''){
                                $query->where('title', 'LIKE','%'.$request->keywords.'%')
                                    // ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keywords.'%');
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
                    ->simplePaginate(45);
            }
            if($request->type == 11){
                $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                $departures11t = Departure::whereIn('id', $departure_id)
                    // ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();

                if($request->keywords || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures11 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keywords != ''){
                                $query->where('title', 'LIKE','%'.$request->keywords.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keywords.'%');
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
                    ->simplePaginate(45);
                //dd($departures);
            }
            if($request->type == 12){
                $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                $departures12t = Departure::whereIn('id', $departure_id)
                    // ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keywords || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures12 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keywords != ''){
                                $query->where('title', 'LIKE','%'.$request->keywords.'%')
                                    // ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keywords.'%');
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
                    ->simplePaginate(45);
            }
            if($request->type == 14){
                $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                $departures14t = Departure::whereIn('id', $departure_id)
                    // ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray();
                if($request->keywords || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                    $departures14 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keywords != ''){
                                $query->where('title', 'LIKE','%'.$request->keywords.'%')
                                    // ->where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keywords.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keywords.'%');
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
                    ->simplePaginate(45);
            }
        }else{
            $departures = Departure::where('approve',1)
            ->where('status',1)
            ->whereDate('start_date', '>=', date("Y-m-d"))
            ->orderBy('start_date', 'ASC')
            ->simplePaginate(45);
        }

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
        else{
            $columns = [];
        }


       $release = HoldDepartureSeat::all();
       //$now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
        foreach($release as $row){
            //$add = $row->hold_duration + 5 ;
            $now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
            if($now >= $row->auto_release){
                // $hold =$row->hold_seat;
                // $departure = $row->departure_id;
                // $dep = Departure::find($departure);
                // $available = $dep->available_seat;
                // $update = Departure::find($departure);
                // $update->available_seat = $available + $hold;
                // $update->save();
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

        // $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
        //      $strlower = Str::lower($removeSC);
        //      $arr_cn = explode(' ', $strlower);
        //      $str_cn = implode('-', $arr_cn);
        //      $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
        //     $mainstr = rtrim($mainstrs, '-');
            $row->company_url = strtolower($users->company_id);
             //$bd->company_name = $user_data->company_name;
        }

        // if($request->ajax()){
        //     return view('departure.all_departure_data',compact('departures','to_date','from_date','holdduration','status_filter'));
        // }
        if($request->id == 'table'){
           return $card_table = $request->id;
        }
        else{
             $card_table = 'card';
        }
        // $publishers = Departure::where('status',1)
        //         ->orderBy('departure_ownner', 'ASC')
        //         ->distinct()
        //         ->select('departure_ownner')
        //         ->get();
        return view('departure.all_departure',compact('departures','total','to_date','from_date','holdduration','active','inactive','pending','from','to','status_filter','card_table','publisher_n','keywords','search_key','req_type','columns'));
    }

    public function publisherListSearch(Request $request){
    $publishers = [];
    if($request->has('q')){
        $search = $request->q;

        $publishers = Departure::where('status',1)
                ->where('departure_ownner','LIKE',"%$search%")
                ->orderBy('departure_ownner', 'ASC')
                ->distinct()
                ->select('departure_ownner')
                ->get(25);

    }else{
        $publishers = Departure::where('status',1)
                ->orderBy('departure_ownner', 'ASC')
                ->distinct()
                ->select('departure_ownner')
                ->get(50);
    }
    return response()->json($publishers);
    }

    public function ApprovedDeparture(Request $request){
        $from_date = $request->from;
        $to_date = $request->to;
        $date = date("Y-m-d");
        $start = $request->start_date;
        $end = $request->end_date;
        $title = $request->departure_name;
        $owner = $request->departure_owner;
        $from = $request->departure_from;
        $to = $request->departure_to;

        $from_destination = Departure::where('approve',0)
                ->where('status',1)
                ->where('company_publish',1)
                ->whereDate('start_date', '>=', $date)
                ->select('from')
                ->groupBy('from')
                ->get();
        $end_destination = Departure::where('approve',0)
                ->where('status',1)
                ->where('company_publish',1)
                ->whereDate('start_date', '>=', $date)
                ->select('ending_at')
                ->groupBy('ending_at')
                ->get();
        $departure_ownner = Departure::where('approve',0)
                ->where('status',1)
                ->where('company_publish',1)
                ->whereDate('start_date', '>=', $date)
                ->select('departure_ownner')
                ->groupBy('departure_ownner')
                ->get();

       $release = HoldDepartureSeat::all();
       $now =date("Y-m-d H:i:s");
        foreach($release as $row){
          if($now >= $row->date){
                $update = HoldDepartureSeat::find($row->id);
                $update->delete();
            }
        }

       if($from_date !=null || $to_date !=null || $start !=null || $end !=null || $title !=null || $owner !=null || $from !=null || $to !=null){
         //return $request->all();
         $start_date = date("Y-m-d", strtotime($request->start_date));
         $end_date = date("y-m-d", strtotime($request->end_date));
          $departures = Departure::where('approve',0)
                    ->where('status',1)
                    ->where('company_publish',1)
                    ->where('title',$request->departure_name)
                    ->orWhere('departure_ownner',$request->departure_owner)
                    ->orWhere('from',$request->departure_from)
                    ->orWhere('ending_at',$request->departure_to)
                    ->orWhereBetween('start_date',[$start_date,$end_date])
                    ->whereDate('start_date', '>=', $date)
                    ->orderBy('id','DESC')
                    ->paginate(20);
                   // return count($pending_departureCount);
       }
       else{
           $departures = Departure::where('approve',0)
                        ->where('status',1)
                        ->where('company_publish',1)
                        ->whereDate('start_date', '>=', $date)
                        ->orderBy('id','DESC')
                        ->paginate(20);
       }

       $pending = count($departures);
       foreach($departures as $row){
            $departure_destination = DB::table('departure_destinations')
            ->join('destinations','departure_destinations.destination_id','destinations.id')
            ->select('destinations.country_name','destinations.dest_name')
            ->where('departure_destinations.departure_id',$row->id)
            ->get();
            $row->destination = $departure_destination;
       }
       if(Auth::user()->main_user_type == 2)
        {
          return view('departure.unapproved_departure',compact('departures','to_date','from_date','pending','from_destination','end_destination','departure_ownner','start','end','title','owner','from','to'));
        }else{
            return view('404');
        }
    }

    public function inactiveDeparture(Request $request){

        $date = date("Y-m-d");
        $departure_title = Departure::where('dep_type','main')
                            ->where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '<=', $date)
                            ->select('title')
                            ->distinct()
                            ->get();
        $departure_owner = Departure::where('dep_type','main')
                            ->where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '<=', $date)
                            ->select('departure_ownner')
                            ->distinct()
                            ->get();
        $departure_from = Departure::where('dep_type','main')
                            ->where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '<=', $date)
                            ->select('from')
                            ->distinct()
                            ->get();
        $departure_to = Departure::where('dep_type','main')
                            ->where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '<=', $date)
                            ->select('ending_at')
                            ->distinct()
                            ->get();
        $start = $request->start_date;
        $end = $request->end_date;
        $title = $request->departure_name;
        $owner = $request->departure_owner;
        $from = $request->departure_from;
        $to = $request->departure_to;

        if($start !=null || $end !=null || $title !=null || $owner !=null || $from !=null || $to !=null){

            $start_date = date("Y-m-d", strtotime($request->start_date));
            $end_date = date("Y-m-d", strtotime($request->end_date));
            $departures = Departure::where('title',$request->departure_name)
                        //->where('status',1)
                        //->where('approve',1)
                        ->orWhere('departure_ownner',$request->departure_owner)
                        ->orWhere('from',$request->departure_from)
                        ->orWhere('ending_at',$request->departure_to)
                        ->orWhereBetween('start_date',[$start_date,$end_date])
                        ->whereDate('start_date', '<=', $date)
                        ->orderBy('id', 'DESC')
                        ->paginate(20);
        }
        else
        {
            $departures = Departure::whereDate('start_date', '<', $date)
                    //->where('status',1)
                    //->where('approve',1)
                    ->orderBy('id', 'DESC')
                    ->paginate(20);
        }
        $inactive = count($departures);
        if(Auth::user()->main_user_type == 2)
        {
        return view('departure.inactive_departure',compact('departures','inactive','departure_title','departure_owner','departure_from','departure_to','start','end','title','owner','from','to'));
        }
        else{
            return view('404');
        }
    }
    public function departureCreate($id)
    {
        $departure_types = [1,2,3,4,5];

        if(Auth::user()->main_user_type = 1 && in_array($id, $departure_types))
        {
            $permission = User::getPermissions();
            if (Gate::allows('departure_create',$permission))
            {
                if($id == 1){
                   $dep_type_name = "Land + Flight";
                }elseif($id == 2){
                    $dep_type_name = "Land Only";
                }
                elseif($id == 3){
                    $dep_type_name = "Hotel + Flight";
                }
                elseif($id == 4){
                    $dep_type_name = "Hotel Only";
                }
                elseif($id == 5){
                    $dep_type_name = "Flight Only";
                }
                $departure_types = DepartureType::all();
                $flight = AllAirline::all();
                $hotel = HotelCategory::all();
                $alldestination = AllDestination::all();
                $holdtill = HoldTill::all();
                $holdduration = HoldDuration::orderBy('hours','ASC')->get();
                $indian_currency=CurrencyConversion::first();
                $inr = $indian_currency->indian_currency;

                $users = User::where('tenant_id',Auth::user()->tenant_id)->get();
                foreach ($users as $key => $user_val) {
                    if($user_val->last_name != null){
                        $user_val->flname = $user_val->name.' '.$user_val->last_name;
                    }else{
                        $user_val->flname = $user_val->name;
                    }
                }
                $contact_p = User::where('tenant_id',Auth::user()->tenant_id)
                    ->where(['user_type'=>0,'main_user_type'=>1])
                    ->select('contact_person_id')
                    ->first();
                return view('departure.basic_details_create',compact('flight','hotel','alldestination','holdtill','holdduration','inr','permission','contact_p','users','departure_types','dep_type_name'));
            }
            else{
                return abort(403);
            }
        }
        else{
            return view('404');
        }
    }

    public function departureStore(Request $request, $departure_type_id)
    {
        //dd($request);
        $dep_type_id = (int)$departure_type_id;
        $data = $request->all();

        $data = $request->all();
        $startFormat = $request->start_date;
        $start_date = date("y-m-d", strtotime($startFormat));
        $return_date = date("y-m-d", strtotime($request->return_date));
        $user = auth()->user();
        if($request->starting_from){
           $from_destination = implode(", ",$request->starting_from);
        }
        else{
            $from_destination = '';
        }

        if($request->return_to){
           $return_to_destination = implode(", ",$request->return_to);
        }
        else{
            $return_to_destination = '';
        }
        
        //Transport Type
        if($request->transport_type){
            $array_transport_type = array();
            for($i = 0; $i < count($request->transport_type); $i++) {
                if ($request->transport_type[$i] != '') {
                    array_push($array_transport_type, $request->transport_type[$i]);
                }
            }
        }
        else{
            $array_transport_type = [];
        }
        //Meal Plan
        if($request->meal_plan){
            $array_meal_plan = array();
            for($i = 0; $i < count($request->meal_plan); $i++) {
                if ($request->meal_plan[$i] != '') {
                    array_push($array_meal_plan, $request->meal_plan[$i]);
                }
            }
        }
        else{
            $array_meal_plan = [];
        }
        $departure_last_id =Departure::orderBy('id', 'desc')->first();


        $departure = new Departure;
        $departure->title = ucfirst($request->title);
        $departure->no_of_days = $request->days;
        $departure->no_of_nights = $request->nights;
        //$departure->team_size = $request->team_size;
        $departure->transport_type = implode(",",$array_transport_type);
        $departure->meal_type = implode(",",$array_meal_plan);
        $departure->total_seat = $request->total_seat;
        $departure->total_room = $request->total_room;
        //$departure->booked_seat = $request->booked_seat;
        $departure->available_seat = $request->total_seat;
        $departure->from = $from_destination;
        $departure->ending_at = $request->ending_at;
        $departure->return_to = $return_to_destination;
        $departure->start_date = $start_date;
        $departure->end_date = $return_date;
        $departure->departure_ownner = $request->ownner;
        //$departure->price_inr = round($request->price_inr);
        //$departure->price_usd = round($request->price_usd);
        $departure->hotel_category = $request->hotel;
        $departure->company_name = $user->company_name;
        $departure->tenant_id = $user->tenant_id;
        $departure->hold_duration = $request->hold_time;
        //$departure->single_supplyment_price_inr = $request->single_price_inr;
        //$departure->single_supplyment_price_usd = $request->single_price_usd;
        $departure->description = $request->description;
        $departure->dep_id = $departure_last_id->dep_id +1;
        $departure->user_id = $user->id;
        $departure->departure_type = $dep_type_id;
        if($request->tags){
            $str_tags = implode('@@', $request->tags);
            $departure->tags = $str_tags;
        }

        $departure->contact_person_id = $request->contact_person;
        $departure->additional_person_id = $request->additional_person_id;
        if($request->contact_person){
            $u = User::where('id', $request->contact_person)->first();
            if($u->last_name != null){
                $departure->contact_person = $u->name.' '.$u->last_name;
            }else{
                $departure->contact_person = $u->name;
            }
        }

        if($request->additional_person_id){
            $au = User::where('id', $request->additional_person_id)->first();
            if($au->last_name != null){
                $departure->additional_contact_person = $au->name.' '.$au->last_name;
            }else{
                $departure->additional_contact_person = $au->name;
            }
        }

        $departure->dep_type = "main";
        $departure->unique_key = Str::random(10).time();
        $departure->save();
        $last_id = $departure->id;
        $array_check = array();

        $hold = new HoldDeparture;
        $hold->user_id =  $user->id;
        $hold->departure_id = $last_id;
        $hold->hold_till = $request->hold_duration;
        //$hold->hold_till = $request->hold_duration;
        $hold->date = date("Y-m-d H:i:s");
        $hold->save();

        if($request->tags){
            foreach ($request->tags as $key => $tags) {
                $tagsAdd = new DepartureTag;
                $tagsAdd->departure_id = $last_id;
                $tagsAdd->name = $tags;
                $tagsAdd->save();
            }
        }
        $destArrays = json_decode($request->destinations);
        if($destArrays){
            foreach ($destArrays as $value) {
                if($value != null){
                    $countryCheck = Country::where('country_name',$value->country)
                            ->where('reference_id', $value->country_id)
                            ->first();
                    $cname = explode(' ', $value->country);
                    $str = implode('-', $cname);
                    $countryN = strtolower($str);
                    if($countryCheck == null)
                    {
                        $country  = new Country;
                        $country->country_name = $value->country;
                        $country->reference_id = $value->country_id;
                        $country->latitude = $value->country_lat;
                        $country->longitude = $value->country_long;
                        $country->official_name = $value->official_name;
                        $country->capital = $value->capital;
                        $country->largest_city = $value->largest_city;
                        $country->continent = $value->continent;
                        $country->description = $value->count_description;
                        $country->sub_continent = $value->sub_continent;
                        $country->iso_2 = $value->count_iso2;
                        $country->iso_3 = $value->count_iso3;
                        $country->isd_code = $value->isd_code;
                        $country->internet_tld = $value->internet_tld;
                        $country->currency = $value->currency;
                        $country->currency_symbol = $value->currency_symbol;
                        $country->currency_code = $value->currency_code;
                        $country->drives_on = $value->drive_on;
                        $country->area = $value->area;
                        $country->area_unit = $value->area_unit;
                        $country->population = $value->population;
                        $country->country_slug = $countryN.'-group-tours';
                        $country->save();
                        $country_last_id = $country->id;
                    }else{
                        $count_id = Country::where('id',$countryCheck->id)->value('id');
                        $country = Country::find($count_id);
                        $country->country_name = $value->country;
                        $country->reference_id = $value->country_id;
                        $country->latitude = $value->country_lat;
                        $country->longitude = $value->country_long;
                        $country->official_name = $value->official_name;
                        $country->capital = $value->capital;
                        $country->largest_city = $value->largest_city;
                        $country->continent = $value->continent;
                        $country->description = $value->count_description;
                        $country->sub_continent = $value->sub_continent;
                        $country->iso_2 = $value->count_iso2;
                        $country->iso_3 = $value->count_iso3;
                        $country->isd_code = $value->isd_code;
                        $country->internet_tld = $value->internet_tld;
                        $country->currency = $value->currency;
                        $country->currency_symbol = $value->currency_symbol;
                        $country->currency_code = $value->currency_code;
                        $country->drives_on = $value->drive_on;
                        $country->area = $value->area;
                        $country->area_unit = $value->area_unit;
                        $country->population = $value->population;
                        $country->country_slug = $countryN.'-group-tours';
                        $country->save();
                        $country_last_id = $country->id;
                    }
                    $country_dep_unique = CountryDeparture::where('departure_id',$last_id)
                                        ->where('country_id', $country_last_id)
                                        ->first();
                    if($country_dep_unique == null || $country_dep_unique == '')
                    {
                        $countryDepUpdate = new CountryDeparture;
                        $countryDepUpdate->country_id = $country_last_id;
                        $countryDepUpdate->departure_id = $last_id;
                        $countryDepUpdate->save();
                    }

                    $desti = Destination::where('dest_name',$value->name)
                            ->where('reference_id', $value->id)
                            ->first();
                    if($desti == null || $desti == '')
                    {

                        $destination  = new Destination;
                        $destination->dest_name = $value->name;
                        $destination->actualname = $value->actual_name;
                        $destination->country_name = $value->country;
                        $destination->country_id = $country_last_id;
                        $destination->reference_id = $value->id;
                        if($value->lat != ''){
                            $destination->latitude = $value->lat;
                        }
                        if($value->long != ''){
                            $destination->longitude = $value->long;
                        }
                        //$destination->longitude = $value->long;
                        $destination->country_iso_2 = $value->iso2;
                        $destination->country_iso_3 = $value->iso3;
                        $destination->region = $value->region;
                        $destination->description = $value->description;
                        $destination->save();
                        $dest_last_id = $destination->id;
                    }
                    else{
                        $destination = Destination::find($desti->id);
                        $destination->dest_name = $value->name;
                        $destination->actualname = $value->actual_name;
                        $destination->country_name = $value->country;
                        $destination->country_id = $country_last_id;
                        $destination->reference_id = $value->id;
                        if($value->lat != ''){
                            $destination->latitude = $value->lat;
                        }
                        if($value->long != ''){
                            $destination->longitude = $value->long;
                        }
                        $destination->country_iso_2 = $value->iso2;
                        $destination->country_iso_3 = $value->iso3;
                        $destination->region = $value->region;
                        $destination->description = $value->description;
                        $destination->save();
                        $dest_last_id = $destination->id;
                    }
                    $dep_unique = DepartureDestination::where('departure_id',$last_id)
                                ->where('destination_id', $dest_last_id)
                                ->first();
                    if($dep_unique == null || $dep_unique == '')
                    {
                        $depdestination  = new DepartureDestination;
                        $depdestination->departure_id=$last_id;
                        $depdestination->destination_id=$dest_last_id;
                        $depdestination->save();
                    }
                }
            }
        }
        $status = [
            //'url'=> url('/departure/inclusion',$last_id),
            'url'=> url('/departure/inclusion',$last_id),
        ];
        return response()->json($status);
    }

    public function departureEdit(Request $request, $id)
    {
        if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
        {
            $data = Departure::where('id', $id)->first();
            if($data){
                $permission = User::getPermissions();
                if (Gate::allows('departure_edit',$permission)) {

                    $flight = AllAirline::all();
                    $hotel = HotelCategory::all();
                    $alldestination = AllDestination::all();
                    $holdtill = HoldTill::all();
                    $holdduration = HoldDuration::orderBy('hours','ASC')->get();

                    $route_ids = $request->route('id');
                    $route_id = (int)$route_ids;
                    if(Auth::user()->main_user_type == 2)
                    {
                    $departures  = Departure::where('id',$id)
                                //->where('tenant_id',auth()->user()->tenant_id)
                                ->first();
                    }
                    else{
                        $departures  = Departure::where('id',$id)
                        ->where('tenant_id',auth()->user()->tenant_id)
                        ->first();
                    }
                    if($departures){
                        $data = explode(",",$departures->transport_type);
                        $departures['transport_type'] = $data;
                    }
                    else{
                        $departures['transport_type'] = [];
                    }
                    if($departures){
                        $data = explode(",",$departures->meal_type);
                        $departures['meal_type'] = $data;
                    }
                    else{
                        $departures['meal_type'] = [];
                    }
                    $holdDep = HoldDeparture::where('departure_id',$id)->first();
                    if($holdDep){
                        $departures->holdDep = $holdDep->hold_till;
                    }
                    else{
                        $departures->holdDep = 1;
                    }
                    $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                                    ->join('countries','countries.id','=','destinations.country_id')
                                    ->where('departure_destinations.departure_id',$route_id)
                                    ->select('destinations.dest_name as name','destinations.country_name as country','destinations.reference_id as id','destinations.latitude as lat','destinations.longitude as long','destinations.country_iso_2 as iso_2','destinations.country_iso_2 as iso_3','destinations.region as region','destinations.description as description','destinations.status as status','destinations.actualname as actualname','countries.reference_id as count_id','countries.official_name as o_name','countries.capital as cap','countries.largest_city as largest_city','countries.continent as continent','countries.description as count_description','countries.sub_continent as count_sub_continent','countries.iso_2 as count_iso_2','countries.iso_3 as count_iso_3','countries.isd_code as count_isd_code','countries.latitude as count_latitude','countries.reference_id as count_id','countries.reference_id as count_latitude','countries.longitude as count_longitude','countries.internet_tld as count_internet_tld','countries.currency as count_currency','countries.currency_symbol as count_currency_symbol','countries.currency_code as count_currency_code','countries.cost_index_id as count_cost_index','countries.drives_on as count_drives_on','countries.area as count_area','countries.area_unit as count_area_unit','countries.population as count_population','countries.status as count_status')
                                    ->get();
                    $indian_currency=CurrencyConversion::first();
                    $inr = $indian_currency->indian_currency;
                    $hold_departure = DB::table('hold_departures')->where('departure_id',$departures->id)->first();
                    $originating = DepartureFlightDetail::where('departure_id',$id)->get();
                    $returning = ReturnFlightDetail::where('departure_id',$id)->get();
                    $tags = DepartureTag::where('departure_id',$id)->select('name')->get();

                    $users = User::where('tenant_id',Auth::user()->tenant_id)->get();
                    foreach ($users as $key => $user_val) {
                        if($user_val->last_name != null){
                            $user_val->flname = $user_val->name.' '.$user_val->last_name;
                        }else{
                            $user_val->flname = $user_val->name;
                        }
                    }
                    $columns = DepartureColumnType::where('departure_type_id', $departures->departure_type)->get()->pluck('column_name_id');
                    $columns = json_encode($columns);

                    if($departures->departure_type == 1){
                        $departures->departure_type = "Land + Flight";
                    }
                    if($departures->departure_type == 2){
                        $departures->departure_type = "Land Pakage";
                    }
                    if($departures->departure_type == 3){
                        $departures->departure_type = "Hotel + Flight";
                    }
                    if($departures->departure_type == 4){
                        $departures->departure_type = "Hotel Only";
                    }
                    if($departures->departure_type == 5){
                        $departures->departure_type = "Flight Only";
                    }
                     $departures_types = Departure::where('id',$departures->id)->first();

                    $dep_from_arr = explode(',', $departures->from);
                    $departures->end_at = $dep_from_arr;
                    if($departures->return_to != ""){
                        $dep_return_arr = explode(',', $departures->return_to);
                        $departures->return_to = $dep_return_arr;
                    }else{
                        $departures->return_to = [];
                    }
                    return view('departure.basic_details_edit',compact('departures','destinations','flight','hotel','alldestination','holdtill','holdduration','hold_departure','inr','permission','originating','returning','tags','users','columns','departures_types'));
                }
                else{
                    return abort(403);
                }
            }else{
                return view('404');
            }
        }
        else{
            return view('404');
        }
    }

    public function departureUpdate(Request $request, $id)
    {
        //dd($request->hold_duration);
        $data = $request->all();
        $startFormat = $request->start_date;
        $start_date = date("y-m-d", strtotime($startFormat));
        $return_date = date("y-m-d", strtotime($request->return_date));
        if($request->starting_from){
           $from_destination = implode(", ",$request->starting_from);
        }
        else{
            $from_destination = '';
        }

        if($request->return_to){
           $return_to_destination = implode(", ",$request->return_to);
        }
        else{
            $return_to_destination = '';
        }
        //Transport Type
        // if($request->transport_type){
        //     $array_transport_type = array();
        //     for($i = 0; $i < count($request->transport_type); $i++) {
        //         if ($request->transport_type != '') {
        //             array_push($array_transport_type, $request->transport_type);
        //         }
        //     }
        // }
        // else{
        //     $array_transport_type = [];
        // }
        // //Meal Plan
        // if($request->meal_plan){
        //     $array_meal_plan = array();
        //     for($i = 0; $i < count($request->meal_plan); $i++) {
        //         if ($request->meal_plan != '') {
        //             array_push($array_meal_plan, $request->meal_plan);
        //         }
        //     }
        // }
        // else{
        //     $array_meal_plan = [];
        // }
        //return $request->hold_duration;
        $holds = HoldDeparture::where('departure_id',$id)->first();
        //dd(Auth::user()->id);
        // 70ct
        if($holds != null)
           {
              $hold = HoldDeparture::find($holds->id);
              $hold->user_id =  Auth::user()->id;
              $hold->departure_id = $id;
              $hold->hold_till = $request->hold_duration;
              //$hold->hold_till = $request->hold_duration;
              $hold->date = date("Y-m-d H:i:s");
              $hold->save();
             }
             else
             {
               $hold = new HoldDeparture;
               $hold->user_id =  Auth::user()->id;
               $hold->departure_id = $id;
               $hold->hold_till = $request->hold_duration;
               //$hold->hold_till = $request->hold_duration;
               $hold->date = date("Y-m-d H:i:s");
               $hold->save();
             }
            //end_70ct
        // $result = DB::table('hold_departures')
        //         ->where('departure_id', $id)
        //         ->update([
        //          'hold_till' => $request->hold_duration,
        //          ]);


        $departure = Departure::find($id);
        $departure->title = ucfirst($request->title);
        $departure->no_of_days = $request->days;
        $departure->no_of_nights = $request->nights;
        //$departure->team_size = $request->team_size;
        $departure->transport_type = $request->transport_type;
        $departure->meal_type = $request->meal_plan;
        $departure->total_seat = $request->total_seat;
        $departure->total_room = $request->total_room;
        //$departure->booked_seat = $request->booked_seat;
        $departure->from = $from_destination;
        $departure->ending_at = $request->ending_at;
        $departure->return_to = $return_to_destination;
        
        $departure->departure_ownner = $request->ownner;
        $departure->hotel_category = $request->hotel;
        //$departure->price_inr =round($request->price_inr);
        //$departure->price_usd =round($request->price_usd);
        //$departure->flight = $request->flight;
        $departure->start_date = $start_date;
        $departure->end_date = $return_date;
        $departure->description = $request->description;
        //$departure->single_supplyment_price_inr = $request->single_price_inr;
        //$departure->single_supplyment_price_usd = $request->single_price_usd;
        if($request->tags){
            $str_tags = implode('@@', $request->tags);
            $departure->tags = $str_tags;
        }
        $departure->contact_person_id = $request->contact_person;
        $departure->additional_person_id = $request->additional_person_id;
        if($request->contact_person){
            $u = User::where('id', $request->contact_person)->first();
            if($u->last_name != null){
                $departure->contact_person = $u->name.' '.$u->last_name;
            }else{
                $departure->contact_person = $u->name;
            }
        }
        if($request->additional_person_id){
            $au = User::where('id', $request->additional_person_id)->first();
            if($au->last_name != null){
                $departure->additional_contact_person = $au->name.' '.$au->last_name;
            }else{
                $departure->additional_contact_person = $au->name;
            }
        }
        $departure->hold_duration = $request->hold_time;
        $departure->change_status = 1;
        $departure->save();
        $last_id = $departure->id;

        if($request->tags){
            DepartureTag::where('departure_id',$id)->delete();
            foreach ($request->tags as $key => $tags) {
                $tagsAdd = new DepartureTag;
                $tagsAdd->departure_id = $last_id;
                $tagsAdd->name = $tags;
                $tagsAdd->save();
            }
        }
        $array_check = array();
        if($request->flight_name){
        for($i = 0; $i < count($request->flight_name); $i++) {
            if ($request->flight_name[$i] != '') {
                array_push($array_check, $request->flight_name[$i]);
            }
        }
        }
        $array_check1 = array();
        if($request->r_flight_name){
        for($i = 0; $i < count($request->r_flight_name); $i++) {
            if ($request->r_flight_name[$i] != '') {
                array_push($array_check1, $request->r_flight_name[$i]);
            }
        }
        }
        if(count($array_check) > 0){
            DepartureFlightDetail::where('departure_id',$id)->delete();
            foreach ($request->flight_name as $key => $value) {
                if($value != ''){
                    $flight = new DepartureFlightDetail;
                    $flight->code = $request->code[$key];
                    $flight->flight_name = $request->flight_name[$key];
                    $flight->flight_no = $request->flight_no[$key];
                    $flight->flight_date = $request->flight_date[$key];
                    $flight->flight_arrival_date = $request->flight_arrival_date[$key];
                    $flight->flight_dep_time = $request->flight_dep_time[$key];
                    $flight->flight_arrival_time = $request->flight_arrival_time[$key];
                    $flight->flight_dep_airport = $request->flight_dep_airport[$key];
                    $flight->flight_arrival_airport = $request->flight_arrival_airport[$key];
                    $flight->departure_id = $last_id;
                    $flight->save();
                }
           }
       }
       if(count($array_check1) > 0){
           ReturnFlightDetail::where('departure_id',$id)->delete();
           foreach ($request->r_flight_name as $key => $value) {
            if($value != ''){
                    $flight = new ReturnFlightDetail;
                    $flight->code = $request->r_code[$key];
                    $flight->flight_name = $request->r_flight_name[$key];
                    $flight->flight_no = $request->r_flight_no[$key];
                    $flight->flight_date = $request->r_flight_date[$key];
                    $flight->flight_arrival_date = $request->r_flight_arrival_date[$key];
                    $flight->flight_dep_time = $request->r_flight_dep_time[$key];
                    $flight->flight_arrival_time = $request->r_flight_arrival_time[$key];
                    $flight->flight_dep_airport = $request->r_flight_dep_airport[$key];
                    $flight->flight_arrival_airport = $request->r_flight_arrival_airport[$key];
                    $flight->departure_id = $last_id;
                    $flight->save();
             }
           }
        }
        $destArrays = json_decode($request->destinations);
        $departure->single_supplyment_price_inr = $request->single_price_inr;
        $departure->single_supplyment_price_usd = $request->single_price_usd;

        if($destArrays){
            DepartureDestination::where('departure_id', $last_id)->delete();
            CountryDeparture::where('departure_id', $last_id)->delete();
            foreach ($destArrays as $value) {
                if($value != null){
                    $cname = explode(' ', $value->country);
                    $str = implode('-', $cname);
                    $countryN = strtolower($str);
                    $countryCheck = Country::where('country_name',$value->country)
                            ->where('reference_id', $value->country_id)
                            ->first();
                    if($countryCheck == null || $countryCheck == '')
                    {
                        $country  = new Country;
                        $country->country_name = $value->country;
                        $country->reference_id = $value->country_id;
                        $country->latitude = $value->country_lat;
                        $country->longitude = $value->country_long;
                        $country->official_name = $value->official_name;
                        $country->capital = $value->capital;
                        $country->largest_city = $value->largest_city;
                        $country->continent = $value->continent;
                        $country->description = $value->count_description;
                        $country->sub_continent = $value->sub_continent;
                        $country->iso_2 = $value->count_iso2;
                        $country->iso_3 = $value->count_iso3;
                        $country->isd_code = $value->isd_code;
                        $country->internet_tld = $value->internet_tld;
                        $country->currency = $value->currency;
                        $country->currency_symbol = $value->currency_symbol;
                        $country->currency_code = $value->currency_code;
                        $country->drives_on = $value->drive_on;
                        $country->area = $value->area;
                        $country->area_unit = $value->area_unit;
                        $country->population = $value->population;
                        $country->country_slug = $countryN.'-group-tours';
                        $country->save();
                        $country_last_id = $country->id;
                    }else{
                        $count_id = Country::where('id',$countryCheck->id)->value('id');
                        $country = Country::find($count_id);
                        $country->country_name = $value->country;
                        $country->reference_id = $value->country_id;
                        $country->latitude = $value->country_lat;
                        $country->longitude = $value->country_long;
                        $country->official_name = $value->official_name;
                        $country->capital = $value->capital;
                        $country->largest_city = $value->largest_city;
                        $country->continent = $value->continent;
                        $country->description = $value->count_description;
                        $country->sub_continent = $value->sub_continent;
                        $country->iso_2 = $value->count_iso2;
                        $country->iso_3 = $value->count_iso3;
                        $country->isd_code = $value->isd_code;
                        $country->internet_tld = $value->internet_tld;
                        $country->currency = $value->currency;
                        $country->currency_symbol = $value->currency_symbol;
                        $country->currency_code = $value->currency_code;
                        $country->drives_on = $value->drive_on;
                        $country->area = $value->area;
                        $country->area_unit = $value->area_unit;
                        $country->population = $value->population;
                        $country->country_slug = $countryN.'-group-tours';
                        $country->save();
                        $country_last_id = $country->id;
                    }
                    $country_dep_unique = CountryDeparture::where('departure_id',$last_id)
                                        ->where('country_id', $country_last_id)
                                        ->first();
                    if($country_dep_unique == null || $country_dep_unique == '')
                    {
                        $countryDepUpdate = new CountryDeparture;
                        $countryDepUpdate->country_id = $country_last_id;
                        $countryDepUpdate->departure_id = $last_id;
                        $countryDepUpdate->save();
                    }
                    //Destination
                    $desti = Destination::where('dest_name',$value->name)
                            ->where('reference_id', $value->id)
                            ->first();
                    if($desti == null || $desti == '')
                    {
                        $destination  = new Destination;
                        $destination->dest_name = $value->name;
                        $destination->actualname = $value->actual_name;
                        $destination->country_name = $value->country;
                        $destination->country_id = $country_last_id;
                        $destination->reference_id = $value->id;
                        $destination->latitude = $value->lat;
                        $destination->longitude = $value->long;
                        $destination->country_iso_2 = $value->iso2;
                        $destination->country_iso_3 = $value->iso3;
                        $destination->region = $value->region;
                        $destination->description = $value->description;
                        $destination->save();
                        $dest_last_id = $destination->id;
                    }
                    else{
                        $destination = Destination::find($desti->id);
                        $destination->dest_name = $value->name;
                        $destination->actualname = $value->actual_name;
                        $destination->country_name = $value->country;
                        $destination->country_id = $country_last_id;
                        $destination->reference_id = $value->id;
                        $destination->latitude = $value->lat;
                        $destination->longitude = $value->long;
                        $destination->country_iso_2 = $value->iso2;
                        $destination->country_iso_3 = $value->iso3;
                        $destination->region = $value->region;
                        $destination->description = $value->description;
                        $destination->save();
                        $dest_last_id = $destination->id;
                    }
                    $dep_unique = DepartureDestination::where('departure_id',$last_id)
                                ->where('destination_id', $dest_last_id)
                                ->first();
                    if($dep_unique == null || $dep_unique == '')
                    {
                        $depdestination  = new DepartureDestination;
                        $depdestination->departure_id=$last_id;
                        $depdestination->destination_id=$dest_last_id;
                        $depdestination->save();
                    }
                }
            }
        }
        $status = [
                'url'=> url('/departure/inclusion',$last_id),
            ];
        return response()->json($status);
    }

    public function departureDisable(Request $request, $id)
    {
        $departures  = Departure::find($id);
        //dd($departures);
        if($departures->status == 0){
            $departures->status = 1;
            $departures->company_publish=1;
            $departures->save();
        }
        return response()->json(['success'=>'Departure disabled successfully!']);

    }

    public function departureCompanyPublish(Request $request, $id)
    {
        $departures  = Departure::find($id);
        //dd($departures);
        if($departures->company_publish == 0){
            $departures->company_publish = 1;
            $departures->approve = 1;
            $departures->status = 1;
            $departures->save();
        }
        return response()->json(['success'=>'Departure published for own successfully!']);
    }
    public function departureUnpublish(Request $request, $id)
    {
        $departures  = Departure::find($id);
        $departures->company_publish = 0;
        $departures->status = 0;
        $departures->save();
        return redirect()->back();
    }


    public function DepartureConfirm(Request $request){

     $old_hold_seat = HoldDepartureSeat::where('unique_id',$request->id)->get();
      if(count($old_hold_seat) > 0)
      {
          $unique_id = time();
          foreach($old_hold_seat as $hold)
          {
               $save = new BookDeparture;
               $save->user_id = Auth::user()->id;
               $save->departure_id = $hold->departure_id;
               $save->unique_id = $unique_id;
               $save->sairing = $hold->sairing;
               $save->transport_type = $hold->transport_type;
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
        HoldDepartureSeat::where('unique_id',$request->id)->delete();
       }
    }
    // Departure End
    // Departure Approve and unapprove
    public function departureApprove(Request $request, $id)
    {
        $getData = MailHelper::setMailConfig();
        $departures  = Departure::find($id);
        //dd($departures);
        if($departures->approve == 1){
            $departures->approve = 0;
            $departures->status = 0;
            $departures->save();
        }
        else{
            $departures->approve = 1;
            $departures->status = 1;
            $departures->save();
        }
        $departure = Departure::where('id', $id)->first();
        //$buyersMail = User::where(['user_type'=> 0,'main_user_type'=>0,'status'=>1])
            // ->where('email_verified_at','!=',"")
            // ->where('verified',1)
            // ->pluck('email')
            // ->toArray();
        $buyersMail = ['shharat@watconsultingservices.com','raj.kumar@watconsultingservices.com'];
            
        if($departure->contact_person_id != null)
        {
            $user = User::where('id',$departure->contact_person_id)->first();
            //dd($buyers_mail);
            // Supplier details

            $noti_sup = new Notification;
            $noti_sup->title = "Departure Cloud  - Departure Approved Sucessfully";
            $noti_sup->body = 'Your Deaparture '.$departure->title. ' for ' .$departure->ending_at.' on '.date('d M, Y', strtotime($departure->start_date)).' has been approved and published in Departure Cloud. To view your departure pls click here.';
            $noti_sup->body_html = '<p>Your Deaparture '.$departure->title. ' for ' .$departure->ending_at.' on '.date('d M, Y', strtotime($departure->start_date)).' has been approved and published in Departure Cloud.</p>';
            $noti_sup->user_id = $user->id;
            $noti_sup->type = "Departure Approved";
            $noti_sup->url_1 = url("departures-details").'/'.$id;
            $noti_sup->save();
            $last_body_sup = $noti_sup->body;
            $last_title_sup = $noti_sup->title;
            $last_link_sup = $noti_sup->url_1;

            //++++++++Notification send Code for Suplier++++++++++++//

            $firebaseToken = User::where('id',$user->id)->whereNotNull('fcm_token')->value('fcm_token');
            $imagecompress = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);

            // Supplier Mail
            Mail::send('mail.departure_approved', ['departure'=>$departure,'fname'=>$user->name,'lname'=>$user->last_name], function ($m) use ($user, $getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to($user->email);
                //$m->to('raj.kumar@watconsultingservices.com');
                $m->subject('Departure Cloud - Departure Approved Sucessfully');
            });
        }
        // Buyers Mail
        Mail::send('emails.mail_to_buyers', ['departure'=>'name'], function ($m) use ($buyersMail, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($buyersMail);
            //$m->to('raj.kumar@watconsultingservices.com');
            $m->subject('Departure Cloud - Departure Approved Sucessfully');
        });

        return response()->json(['success'=>'Departure approved successfully!']);
    }
    // Departure approve and unapprove end

    public function getDestinationAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Destination::select("id","dest_name")
                        ->where('dest_name','LIKE',"$search%")
                        ->get(20);
            }
        else{
            $data = Destination::select("id","dest_name")
                        ->limit(10)
                        ->get();
        }
        return response()->json($data);
    }

    public function getPricingAjax(Request $request)
    {
      $departure_id = $request->departure_id;
       $type = DB::table('pricing_types')
               ->select("pricing_types.id","pricing_types.type","name","pricing_types.symbol_inr","pricing_types.symbol_usd")
               ->distinct()
               ->get();
       foreach ($type as $key => $value) {
           $type_row = Price::where('price_type_id',$value->id)
                       ->where('departure_id', $departure_id)
                       ->select('price_inr','price_usd')
                       ->first();
            if($type_row){
                $value->pricing = $type_row;
            }
            else{
                $value->pricing = '';
            }
       }
       // echo "<pre>";
       // print_r($type);
       //$result = array_filter($optional_tour);
      return response()->json($type);
    }
   public function updatePriceModal(Request $request)
    {
      $data = $request->all();
      //dd($data);
      Price::where('departure_id',$request->edit_id)->delete();
      foreach($request->price_type_id as $key => $value)
      {
      if($request->price_inr[$value] || $request->price_usd[$value])
      {
      $price_update = new Price;
      $price_update->price_inr = $request->price_inr[$value];
      $price_update->price_usd = $request->price_usd[$value];
      $price_update->symbol_inr = $request->symbol_inr[$value];
      $price_update->symbol_usd = $request->symbol_usd[$value];
      $price_update->price_type_id = $value;
      $price_update->departure_id = $request->edit_id;
      $price_update->save();
      }
    }
        $status = [
        'message'=> "Success!",
    ];
    return response()->json($status);
    }

   public function details($id)
   {
        $data = Departure::where('id', $id)->first();
        if($data){
            $permission = User::getPermissions();
            if (Gate::allows('departure_view',$permission)) {
                $departure_details = Departure::where('tenant_id',auth()->user()->tenant_id)->where('id',$id)->first();

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
                                ->where('departures.tenant_id',Auth::user()->tenant_id)
                                ->where('inclusions.departure_id',$id)
                                ->get();
                $day_itinerary = Itinerary::where('departure_id', $id)->get();

                foreach($day_itinerary as $row){
                    $day_destination = explode(',',$row->destinations);
                    $row->day_destination = DB::table('destinations')
                        ->whereIn('id',$day_destination)
                        ->select('id','dest_name')
                        ->get();
                }
                $user_hold = DB::table('hold_departure_seats')
                                ->join('users','hold_departure_seats.user_id','users.id')
                                ->select('hold_departure_seats.*','users.company_name')
                                ->where('departure_id', $departure_details->id)
                                ->get();
                $extra_hold = DB::table('hold_departure_seats')
                                ->join('users','hold_departure_seats.user_id','users.id')
                                ->select('hold_departure_seats.*','users.company_name')
                                ->where('departure_id', $departure_details->id)
                                ->where('extra_hold_seat','>',0)
                                ->get();

                $departure_destination = DB::table('departure_destinations')
                                ->join('destinations','departure_destinations.destination_id','destinations.id')
                                ->select('destinations.country_name','destinations.dest_name')
                                ->where('departure_destinations.departure_id',$departure_details->id)
                                ->get();
                $book_date = DB::table('book_departures')
                    ->where('departure_id',$departure_details->id)
                    ->select('unique_id','user_id','tenant_id')
                    ->distinct()
                    //->orderBy('created_at','DESC')
                    ->get();
                   //return count($book_date);
                foreach($book_date as $bd)
                {

                    $booked_seat = BookDeparture::where('departure_id',$departure_details->id)->where('unique_id',$bd->unique_id)->sum('booked_seat');
                    $bd->booked_seat = $booked_seat;

                    $booked_value = BookDeparture::where('unique_id',$bd->unique_id)->where('departure_id',$departure_details->id)->where('user_id',$bd->user_id)->first();
                    $bd->booked_value = $booked_value;

                    $price = BookDeparture::where('user_id',$bd->user_id)->where('unique_id',$bd->unique_id)->sum('price');

                    $bd->price = $price;

                    $bd_currency = BookDeparture::where('unique_id',$bd->unique_id)->where('user_id',$bd->user_id)->where('departure_id',$departure_details->id)->first();
                    $bd->currency = $bd_currency;


                    $dep_name = Departure::where('id',$departure_details->id)->value('title');
                    $bd->departure_name = $dep_name;

                    $currency_symbol = Departure::where('id',$departure_details->id)->value('currency_symbol');
                    $bd->currency_symbol = $currency_symbol;

                    $company_name = Departure::where('id',$departure_details->id)->value('company_name');
                    $bd->company_name = $company_name;

                    $user_name = User::where('id',$bd->user_id)
                                ->value('name');
                    $bd->name = $user_name;

                }
            $hold_date = DB::table('hold_departure_seats')
                        ->where('departure_id',$departure_details->id)
                        ->select('unique_id','user_id')
                        ->distinct()
                        //->orderBy('created_at','DESC')
                        ->paginate(10);
            foreach($hold_date as $bd)
            {

                $booked_seat = DB::table('hold_departure_seats')
                        ->where('unique_id',$bd->unique_id)->sum('hold_seat');
                $bd->booked_seat = $booked_seat;

                $extra_hold = DB::table('hold_departure_seats')
                        ->where('unique_id',$bd->unique_id)->sum('extra_hold_seat');
                $bd->extra_hold = $extra_hold;

                $booked_value = DB::table('hold_departure_seats')->where('departure_id',$departure_details->id)->where('user_id',$bd->user_id)->first();
                $bd->booked_value = $booked_value;

                $price =DB::table('hold_departure_seats')
                        ->where('user_id',$bd->user_id)->where('unique_id',$bd->unique_id)->sum('price');

                $bd->price = $price;

                $bd_currency = DB::table('hold_departure_seats')
                        ->where('user_id',$bd->user_id)->where('departure_id',$departure_details->id)->first();
                $bd->currency = $bd_currency;


                $dep_name = Departure::where('id',$departure_details->id)->value('title');
                $bd->departure_name = $dep_name;

                $currency_symbol = Departure::where('id',$departure_details->id)->value('currency_symbol');
                $bd->currency_symbol = $currency_symbol;

                $company_name = Departure::where('id',$departure_details->id)->value('company_name');
                $bd->company_name = $company_name;

                $user_name = User::where('id',$bd->user_id)
                            ->value('name');
                $bd->name = $user_name;

            }
                $departure_book = DB::table('book_departures')
                        ->join('users','book_departures.user_id','users.id')
                        ->join('departures','departures.id','book_departures.departure_id')
                        ->select('book_departures.*','users.company_name','users.name','departures.price_inr','departures.price_usd','single_supplyment_price_usd','single_supplyment_price_inr')
                        ->where('book_departures.departure_id',$id)->get();


                $sharing_book= DB::table('book_departures')->where('departure_id',$id)->sum('booked_seat');
                $sharing_hold= DB::table('hold_departure_seats')->where('departure_id',$id)->sum('hold_seat');


                $single_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Single')->sum('booked_seat');
                $double_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Double')->sum('booked_seat');
                $triple_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Triple')->sum('booked_seat');
                $quard_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Quard')->sum('booked_seat');
                $twin_cwb_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Twin + CWB')->sum('booked_seat');
                $twin_cwbo_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Twin + CW/OB')->sum('booked_seat');
                $infrant_b= DB::table('book_departures')->where('departure_id',$id)->where('sairing','Infrant')->sum('booked_seat');
                // Hold
                $single_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Single')->sum('hold_seat');
                $double_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Double')->sum('hold_seat');
                $triple_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Triple')->sum('hold_seat');
                $quard_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Quard')->sum('hold_seat');
                $twin_cwb_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Twin + CWB')->sum('hold_seat');
                $twin_cwbo_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Twin + CW/OB')->sum('hold_seat');
                $infrant_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('sairing','Infrant')->sum('hold_seat');
                //flight graph book
                $flight_b= DB::table('book_departures')->where('departure_id',$id)->where('flight_class','First Class')->sum('booked_seat');
                $flight1_b= DB::table('book_departures')->where('departure_id',$id)->where('flight_class','Business Class')->sum('booked_seat');
                $flight2_b= DB::table('book_departures')->where('departure_id',$id)->where('flight_class','Economy Class')->sum('booked_seat');
                 //flight graph Hold
                $flight_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('flight_class','First Class')->sum('hold_seat');
                $flight1_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('flight_class','Business Class')->sum('hold_seat');
                $flight2_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('flight_class','Economy Class')->sum('hold_seat');

                //Passenger book
                $passenger_b= DB::table('book_departures')->where('departure_id',$id)->where('passenger','Infant')->sum('booked_seat');
                $passenger1_b= DB::table('book_departures')->where('departure_id',$id)->where('passenger','Child')->sum('booked_seat');
                $passenger2_b= DB::table('book_departures')->where('departure_id',$id)->where('passenger','Adult')->sum('booked_seat');
                 //Passenger Hold
                $passenger_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('passenger','Infant')->sum('hold_seat');
                $passenger1_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('passenger','Child')->sum('hold_seat');
                $passenger2_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('passenger','Adult')->sum('hold_seat');

                //Airport Transfer book
                $airport_b= DB::table('book_departures')->where('departure_id',$id)->where('airport_transfers','Not Included')->sum('booked_seat');
                $airport1_b= DB::table('book_departures')->where('departure_id',$id)->where('airport_transfers','SIC')->sum('booked_seat');
                $airport2_b= DB::table('book_departures')->where('departure_id',$id)->where('airport_transfers','Private')->sum('booked_seat');
                 //Airport Transfer Hold
                $airport_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('airport_transfers','Not Included')->sum('hold_seat');
                $airport1_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('airport_transfers','SIC')->sum('hold_seat');
                $airport2_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('airport_transfers','Private')->sum('hold_seat');

                $t_sic_b= DB::table('book_departures')->where('departure_id',$id)->where('transport_type','SIC')->sum('booked_seat');
                $t_private_b= DB::table('book_departures')->where('departure_id',$id)->where('transport_type','Private')->sum('booked_seat');

                $t_sic_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('transport_type','SIC')->sum('hold_seat');
                $t_private_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('transport_type','Private')->sum('hold_seat');

                $h_1_b= DB::table('book_departures')->where('departure_id',$id)->where('hotel_type','1 Star')->sum('booked_seat');
                $h_2_b= DB::table('book_departures')->where('departure_id',$id)->where('hotel_type','2 Star')->sum('booked_seat');
                $h_3_b= DB::table('book_departures')->where('departure_id',$id)->where('hotel_type','3 Star')->sum('booked_seat');
                $h_4_b= DB::table('book_departures')->where('departure_id',$id)->where('hotel_type','4 Star')->sum('booked_seat');
                $h_5_b= DB::table('book_departures')->where('departure_id',$id)->where('hotel_type','5 Star')->sum('booked_seat');

                $h_1_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('hotel_type','1 Star')->sum('hold_seat');
                $h_2_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('hotel_type','2 Star')->sum('hold_seat');
                $h_3_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('hotel_type','3 Star')->sum('hold_seat');
                $h_4_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('hotel_type','4 Star')->sum('hold_seat');
                $h_5_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('hotel_type','5 Star')->sum('hold_seat');

                $meal_amp_b= DB::table('book_departures')->where('departure_id',$id)->where('meal_plan','AP')->sum('booked_seat');
                $meal_mamp_b= DB::table('book_departures')->where('departure_id',$id)->where('meal_plan','MAP')->sum('booked_seat');
                $meal_cmp_b= DB::table('book_departures')->where('departure_id',$id)->where('meal_plan','CP')->sum('booked_seat');
                $meal_ep_b= DB::table('book_departures')->where('departure_id',$id)->where('meal_plan','EP')->sum('booked_seat');

                $meal_amp_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('meal_plan','AP')->sum('hold_seat');
                $meal_mamp_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('meal_plan','MAP')->sum('hold_seat');
                $meal_cmp_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('meal_plan','CP')->sum('hold_seat');
                $meal_ep_h= DB::table('hold_departure_seats')->where('departure_id',$id)->where('meal_plan','EP')->sum('hold_seat');


                //return count($sharing_hold);
                $departure_prices = DeparturePrice::where('departure_id',$departure_details->id)->where('status',1)->get();
                $originating = DepartureFlightDetail::where('departure_id',$departure_details->id)->get();
                $returning = ReturnFlightDetail::where('departure_id',$departure_details->id)->get();
                $payment_schedule = PaymentSchedule::where('departure_id',$departure_details->id)->get();
                 $total_price = PaymentSchedule::where('departure_id',$departure_details->id)->sum('price');
                 $total_percentage = PaymentSchedule::where('departure_id',$departure_details->id)->sum('percentage');
                 $total_single = PaymentSchedule::where('departure_id',$departure_details->id)->sum('single_supplement');
                 $total_cost = PaymentSchedule::where('departure_id',$departure_details->id)->sum('total');

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

                //Hotels
                $hotels = DB::table('departure_hotels')->where('departure_id',$departure_details->id)->get();
                if(count($hotels)>0){
                    foreach ($hotels as $key => $hotel) {
                        $hotel->destination_id = Destination::where('id',$hotel->destination_id)->value('dest_name');
                        $hotel->hotel_category = DB::table('hotel_categories')->where('id',$hotel->hotel_category)
                                ->value('hotel_category');
                    }
                }
                $columns = DepartureColumnType::where('departure_type_id', $departure_details->departure_type)->get()->pluck('column_name_id');
                $columns = json_encode($columns);
                $departure_types = DepartureType::where('id', $departure_details->departure_type)->first();

                if($data->departure_type == 5){
                    $f_originating = DB::table('departure_flight_details')
                    ->where('departure_id', $data->id)->get();
                    $f_returning = DB::table('return_flight_details')
                    ->where('departure_id', $data->id)->get();

                foreach ($f_originating as $key => $f_origin) {
                    $d_date = date("d M, Y", strtotime($f_origin->flight_date));
                    $a_date = date("d M, Y", strtotime($f_origin->flight_arrival_date));
                    $f_origin->departure_itinerary = "Departure - Flight - ".$d_date;
                    $f_origin->arrival_itinerary = "Arrival - Flight - ".$a_date;
                }
                foreach ($f_returning as $key => $f_return) {
                    $rd_date = date("d M, Y", strtotime($f_return->flight_date));
                    $ra_date = date("d M, Y", strtotime($f_return->flight_arrival_date));
                    $f_return->departure_itinerary = "Departure - Flight - ".$rd_date;
                    $f_return->arrival_itinerary = "Arrival - Flight - ".$ra_date;
                }
                $default_iti0 = $f_originating;
                $default_iti1 = $f_returning;
                $default_iti2 = [];

            }elseif($data->departure_type == 4){
                $hotels2 = DB::table('departure_hotels')
                    ->where('departure_id', $data->id)->get();

                foreach ($hotels2 as $key => $hotel) {
                    $checkin = date("d M, Y", strtotime($data->start_date));
                    $checkout = date("d M, Y", strtotime($data->end_date));
                    $hotel->checkin = "Checkin - Hotel - ".$checkin;
                    $hotel->checkout = "Checkout - Hotel - ".$checkout;
                }
                $default_iti0 = [];
                $default_iti1 = [];
                $default_iti2 = $hotels2;

            }elseif($data->departure_type == 3){
                $f_originating = DB::table('departure_flight_details')
                    ->where('departure_id', $data->id)->get();
                $f_returning = DB::table('return_flight_details')
                    ->where('departure_id', $data->id)->get();

                foreach ($f_originating as $key => $f_origin) {
                    $d_date = date("d M, Y", strtotime($f_origin->flight_date));
                    $a_date = date("d M, Y", strtotime($f_origin->flight_arrival_date));
                    $f_origin->departure_itinerary = "Departure - Flight - ".$d_date;
                    $f_origin->arrival_itinerary = "Arrival - Flight - ".$a_date;
                }
                foreach ($f_returning as $key => $f_return) {
                    $rd_date = date("d M, Y", strtotime($f_return->flight_date));
                    $ra_date = date("d M, Y", strtotime($f_return->flight_arrival_date));
                    $f_return->departure_itinerary = "Departure - Flight - ".$rd_date;
                    $f_return->arrival_itinerary = "Arrival - Flight - ".$ra_date;
                }

                $hotels1 = DB::table('departure_hotels')
                    ->where('departure_id', $data->id)->get();
                foreach ($hotels1 as $key => $hotel) {
                    $checkin = date("d M, Y", strtotime($data->start_date));
                    $checkout = date("d M, Y", strtotime($data->end_date));
                    $hotel->checkin = "Checkin - Hotel - ".$checkin;
                    $hotel->checkout = "Checkout - Hotel - ".$checkout;
                }

                $default_iti0 = $f_originating;
                $default_iti1 = $f_returning;
                $default_iti2 = $hotels1;
            }else{
                $default_iti0 = [];
                $default_iti1 = [];
                $default_iti2 = [];
            }
                return view('departure.departure_details',compact('departure_details','hold','book','itinerary','inclusion','user_hold','departure_destination','departure_book','permission','extra_hold','originating','returning','payment_schedule','total_price','total_percentage','total_percentage','total_single','total_cost','departure_prices','hold_date','book_date','sharing_book','sharing_hold','single_b','double_b','triple_b','twin_cwb_b','twin_cwbo_b','infrant_b','single_h','double_h','triple_h','twin_cwb_h','twin_cwbo_h','infrant_h','t_sic_b','t_private_b','t_sic_h','t_private_h','h_1_b','h_2_b','h_3_b','h_4_b','h_5_b','h_1_h','h_2_h','h_3_h','h_4_h','h_5_h','meal_amp_b','meal_mamp_b','meal_cmp_b','meal_ep_b','meal_amp_h','meal_mamp_h','meal_cmp_h','meal_ep_h','cancelation_schedule','quard_h','quard_b','departure_types','columns','hotels','airport_b','airport1_b','airport2_b','airport_h','airport1_h','airport2_h','passenger_b','passenger1_b','passenger2_b','passenger_h','passenger1_h','passenger2_h','flight_b','flight1_b','flight2_b','flight_h','flight1_h','flight2_h','default_iti0','default_iti1','default_iti2','day_itinerary'));
            }
            else{
                return abort(403);
            }
        }else{
            return view('404');
        }
   }
    public function holdDurationUpdate(Request $request){
        $getData = MailHelper::setMailConfig();
        //return $request->all();
        $count = count(array_filter($request->hold));
        $total = 0;
        $price = 0;
        $total_price = 0;
        $unique_id =time();
        if(array_sum($request->hold) > 0)
        {
            if($request->available >= array_sum($request->hold)){
                $currency = $request->currency;
                $mail_duration =  $request->hold_time;
                $held_time = $request->hours;
                $available = Departure::find($request->id);
                $supplier = User::find($available->user_id);
                $extra_av = $request->available;
                $extra_request =array_sum($request->hold);
                    if($extra_request > $extra_av){
                        $extra_seat_hold = $extra_request - $extra_av;
                        $extra_hold_user = 0;
                        $request1 = $request->available;
                    }else{
                        $request1 = array_sum($request->hold);
                        $extra_hold_user = $request->available - array_sum($request->hold);
                        $extra_seat_hold = 0;
                    }
                foreach($request->hold as $key=>$hold){
                    $total = $total + $hold;
                    $total_price =$total_price + ($hold * $request->price[$key]);
                    if($hold != '')
                    {
                        $save = new HoldDepartureSeat;
                        $save->user_id = Auth::user()->id;
                        $save->departure_id = $request->id;
                        $save->unique_id = $unique_id;
                        $save->hold_seat = $hold;
                        $save->extra_hold_seat = $extra_seat_hold;
                        $save->sairing = $request->sairing[$key];
                        $save->transport_type = $request->transport_type[$key];
                        $save->hotel_type = $request->hotel_type[$key];
                        $save->hotel_name = $request->hotel_name[$key];
                        $save->meal_plan = $request->meal_plan[$key];
                        $save->group_size = $request->group_size[$key];
                        $save->flight_class = $request->flight_class[$key];
                        $save->passenger = $request->passenger[$key];
                        $save->airport_transfers = $request->airport_transfers[$key];
                        $save->lead_pasanger_name = $request->lead_pasanger_name;
                        $save->price = $request->price[$key] * $hold;
                        $save->currency_code = $request->currency;
                        $save->currency_symbol = $request->currency_symbol;
                        $save->hold_duration = $request->hours;
                        $save->auto_release = $request->auto_release;
                        $save->date = date("Y-m-d H:i:s", strtotime("+{$request->hours} hours"));
                        $save->note = $request->note;
                        $save->save();
                    }
                }

                $available = Departure::find($request->id);
                $available->available_seat = $extra_hold_user;
                $available->save();
                $supplier_name = User::where('id',$available->contact_person_id)
                        ->first();
                if($available->additional_person_id !=""){
                    $supplier_forNoti_add = User::where('id',$available->additional_person_id)->first();
                }else{
                    $supplier_forNoti_add = "";
                }
                // Booker details
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud - Departure Hold';
                $noti_save->body = $extra_request .' units are on hold for you in Departure for '.$available->ending_at.'. Deaparture Name: ' .$available->title. ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' Supplier: ' . auth()->user()->name . auth()->user()->last_name . ' No of units on hold: ' . $extra_request. ' Units are on hold till: ' . $mail_duration;

                $noti_save->body_html = '<p><b> '.$extra_request . ' </b>units are on hold for you in Departure for<b> '.$available->ending_at.' </b>. </p><p><b>Deaparture Name:</b> ' .$available->title. ' </p><p><b>Departure Date:</b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>Supplier:</b> ' . auth()->user()->name . auth()->user()->last_name . ' </p><p><b>No of units on hold:</b> ' . $extra_request. ' </p><p><b>Units are on hold till:</b> ' . $mail_duration. ' </p>';
                $noti_save->user_id = auth()->user()->id;
                $noti_save->type = "Hold";
                $noti_save->url_1 = url('departures-details').'/'.$available->id;
                $noti_save->save();
                $last_body_auth = $noti_save->body;
                $last_title_auth = $noti_save->title;
                $last_link_auth = $noti_save->url_1;

                //++++++++++++++Notification send Code for Auth+++++++++++//

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

                $firebaseToken = User::where('id',$supplier_name->id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);

                if($supplier_forNoti_add != ""){
                    $firebaseTokens = User::where('id',$supplier_forNoti_add->id)->whereNotNull('fcm_token')->value('fcm_token');
                    $sendNotification = $this->sendNotificationSupplier($firebaseTokens, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);
                }
                //+++++++ Notification send Code End for Suplier +++++++++//

                Mail::send('mail.buyer_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name, 'lname'=>$supplier_name->last_name], function ($m) use ($supplier, $getData) {
                    $m->from($getData['from_mail'], $getData['from_name']);
                    $m->to(auth()->user()->email);
                    //$m->to('raj.kumar@watconsultingservices.com');
                    $m->subject('Departure cloud - Departure Hold');
                });
                if($supplier_forNoti_add != ""){
                    Mail::send('mail.supplier_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name], function ($m) use ($supplier_name,$supplier_forNoti_add, $getData) {
                        $m->from($getData['from_mail'], $getData['from_name']);
                        $m->to($supplier_name->email);
                        $m->cc($supplier_forNoti_add->email);
                        $m->subject('Departure cloud - Departure Hold');
                    });
                }else{
                    Mail::send('mail.supplier_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name], function ($m) use ($supplier_name, $getData) {
                        $m->from($getData['from_mail'], $getData['from_name']);
                        $m->to($supplier_name->email);
                        $m->subject('Departure cloud - Departure Hold');
                    });
                }

                return redirect()->back()->withSuccess('Departure held successfully for ' .$request->hours. ' hours. ');
            }
            else{
                $currency = $request->currency;
                $mail_duration =  $request->hold_time;
                $held_time = $request->hours;
                $available = Departure::find($request->id);
                $supplier = User::find($available->user_id);
                $extra_av = $request->available;
                $extra_request =array_sum($request->hold);
                $extra = array_sum($request->hold)-$request->available;
                $extra_request = array_sum($request->hold);
                foreach($request->hold as $key=>$hold){
                $total = $total + $hold;
                $total_price =$total_price + ($hold * $request->price[$key]);
                    if($hold != '')
                    {
                        $save = new HoldDepartureSeat;
                        $save->user_id = Auth::user()->id;
                        $save->departure_id = $request->id;
                        $save->unique_id = $unique_id;
                        $save->hold_seat = $hold;
                        if($key == $count-1)
                        {
                          $save->extra_hold_seat = $extra;
                        }
                        $save->sairing = $request->sairing[$key];
                        $save->price = $request->price[$key] * $hold;
                        $save->currency_code = $request->currency;
                        $save->currency_symbol = $request->currency_symbol;
                        $save->hold_duration = $request->hours;
                        $save->auto_release = $request->auto_release;
                        $save->date = date("Y-m-d H:i:s", strtotime("+{$request->hours} hours"));
                        $save->note = $request->note;
                        //$save->save();
                    }
                }
                $available = Departure::find($request->id);
                $available->available_seat = $request->available;
                $available->save();
                $supplier_name = User::where('id',$available->contact_person_id)
                        ->first();
                if($available->additional_person_id !=""){
                    $supplier_forNoti_add = User::where('id',$available->additional_person_id)->first();
                }else{
                    $supplier_forNoti_add = "";
                }
                // Booker details
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud - Departure Hold';
                $noti_save->body = $extra_request .' units are on hold for you in Departure for '.$available->ending_at.' .Deaparture Name: ' .$available->title. ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' Supplier: ' . auth()->user()->name . auth()->user()->last_name . ' No of units on hold: ' . $extra_request. ' Units are on hold till: ' . $mail_duration;
                $noti_save->body_html = '<p><b> '.$extra_request . ' </b>units are on hold for you in Departure for<b> '.$available->ending_at.' .</b></p><p><b>Deaparture Name:</b> ' .$available->title. '</p><p><b>Departure Date:</b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>Supplier:</b> ' . auth()->user()->name . auth()->user()->last_name . ' </p><p><b>No of units on hold:</b> ' . $extra_request. ' </p><p><b>Units are on hold till:</b> ' . $mail_duration. ' </p>';
                $noti_save->user_id = auth()->user()->id;
                $noti_save->type = "Hold";
                $noti_save->url_1 = url('departures-details').'/'.$available->id;
                $noti_save->save();

                $last_body_auth = $noti_save->body;
                $last_title_auth = $noti_save->title;
                $last_link_auth = $noti_save->url_1;

                //++++++++++++++Notification send Code for Auth+++++++++++//

                $firebaseToken = User::where('id',auth()->user()->id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationBuyer($firebaseToken, $last_title_auth, $last_body_auth, $last_link_auth,$noti_save->id);

                //+++++++++++Notification send Code End for Auth+++++++++//

                // Supplier details
                $noti_sup = new Notification;
                $noti_sup->title = 'Departure cloud  - Departure Hold';
                $noti_sup->body = $extra_request .' units are on hold for you in Departure for '.$available->ending_at.'. Hold Request By: ' . auth()->user()->name. auth()->user()->last_name .' Buyer Company Name: ' . auth()->user()->company_name . ' Deaparture Name: ' . $available->title . ' Departure Date: ' . date("d M, Y", strtotime($available->start_date)). ' No of units on hold: ' . $extra_request;;

                $noti_sup->body_html = '<p><b> '.$extra_request .' </b>units are on hold for you in Departure for '.$available->ending_at.' .</p><p><b>Hold Request By:</b> ' . auth()->user()->name. auth()->user()->last_name .' </p><p><b>Buyer Company Name:</b> ' . auth()->user()->company_name . ' </p><p><b>Deaparture Name:</b> ' . $available->title . ' </p><p><b>Departure Date:</b> ' . date("d M, Y", strtotime($available->start_date)). ' </p><p><b>No of units on hold:</b> ' . $extra_request . ' </p>';
                $noti_sup->user_id = $supplier_name->id;
                $noti_sup->type = "Hold";
                $noti_sup->url_1 = url('departures-details').'/'.$available->id;
                $noti_sup->save();
                $last_body_sup = $noti_sup->body;
                $last_title_sup = $noti_sup->title;
                $last_link_sup = $noti_sup->url_1;
                //++++++++Notification send Code for Suplier++++++++++++//

                $firebaseToken = User::where('id',$supplier_name->id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);
                if($supplier_forNoti_add != ""){
                    $firebaseTokens = User::where('id',$supplier_forNoti_add->id)->whereNotNull('fcm_token')->value('fcm_token');
                    $sendNotification = $this->sendNotificationSupplier($firebaseTokens, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);
                }


                //+++++++ Notification send Code End for Suplier +++++++++//

                // Mail::send('mail.buyer_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'request_more'=>$extra,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name], function ($m) use ($supplier) {
                // $m->from($getData['from_mail'], $getData['from_name']);
                //     $m->to('deepak.gole@watconsultingservices.com');
                //     //$m->bcc('raj.kumar@watconsultingservices.com');
                //     $m->subject('Departure Cloud - Departure Hold');
                //     //$m->to(auth::user()->email, auth::user()->name)->subject('Departure Cloud - Departure Held Successfully');

                // });
                Mail::send('mail.buyer_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'request_more'=>$extra,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name], function ($m) use ($supplier, $getData) {
                    $m->from($getData['from_mail'], $getData['from_name']);
                    $m->to(auth()->user()->email);
                    //$m->to('raj.kumar@watconsultingservices.com');
                    $m->subject('Departure cloud - Departure Hold');
                });
                if($supplier_forNoti_add != ""){
                    Mail::send('mail.supplier_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'request_more'=>$extra,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name], function ($m) use ($supplier_name,$supplier_forNoti_add, $getData) {
                        $m->from($getData['from_mail'], $getData['from_name']);
                        $m->to($supplier_name->email);
                        $m->cc($supplier_forNoti_add->email);
                        $m->subject('Departure cloud - Departure Hold');
                    });
                }else{
                    Mail::send('mail.supplier_hold_seat_email', ['supplier' => $supplier,'departure'=>$available,'mail_duration'=>$mail_duration,'hold_seat'=> $extra_request,'request_more'=>$extra,'total_price'=>$total_price,'currency'=>$currency,'note'=>$request->note,'save'=>$save, 'senTo'=>'wat','fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name], function ($m) use ($supplier_name, $getData) {
                        $m->from($getData['from_mail'], $getData['from_name']);
                        $m->to($supplier_name->email);
                        $m->subject('Departure cloud - Departure Hold');
                    });
                }
                $error = 'Request More units = '.$extra;
                $url = '/departures-details/'.$request->id;
                return response()->json(['success'=>$error,'url'=>$url,'error'=>$error]);
            }
        }
        else{
            return response()->json(['required'=>"Please enter required unit."]);
        }
    }
    public function bookSeat(Request $request){
        $getData = MailHelper::setMailConfig();
        $payment_schedule = PaymentSchedule::where('departure_id',$request->id)->get();
        $cancel_schedule = CancelSchedule::where('departure_id',$request->id)->get();
        if(array_sum($request->book) > 0)
        {
            if($request->available >= array_sum($request->book))
            {
                $dd_tId = Departure::where('id',$request->id)->value('tenant_id');
                $departure = Departure::find($request->id);
                   $supplier = User::find($departure->user_id);
                   $user_tenant = User::where('tenant_id',$dd_tId)->first();
                   //dd($user_tenant->email);
                   $dook_price = DB::table('prices')->where('departure_id',$request->id)->where('price_type_id',3)->get();
                   $total = 0;
                   $price = 0;
                   $total_price = 0;
                   $currency = $request->currency;
                   $unique_id =time();

                   foreach($request->book as $key=>$require_unit){
                    $total = $total + $require_unit;
                    $total_price =$total_price + ($require_unit * $request->price[$key]);
                        if($require_unit != '')
                        {
                           $save = new BookDeparture;
                           $save->user_id = Auth::user()->id;
                           $save->tenant_id = Auth::user()->tenant_id;
                           $save->departure_id = $request->id;
                           $save->unique_id = $unique_id;
                           $save->sairing = $request->sairing[$key];
                           $save->transport_type = $request->transport_type[$key];
                           $save->hotel_type = $request->hotel_type[$key];
                           $save->hotel_name = $request->hotel_name[$key];
                           $save->meal_plan = $request->meal_plan[$key];
                           $save->group_size = $request->group_size[$key];
                           $save->flight_class = $request->flight_class[$key];
                           $save->passenger = $request->passenger[$key];
                           $save->airport_transfers = $request->airport_transfers[$key];
                           $save->lead_pasanger_name = $request->lead_pasanger_name;
                           $save->booked_seat = $require_unit;
                           $save->price = $request->price[$key] * $require_unit;
                           $save->currency_code = $request->currency;
                           $save->currency_symbol = $request->currency_symbol;
                           $save->note = $request->note;
                           $save->date = date("Y-m-d");
                           $save->save();
                        }
                   }

                    $book_details_mail = BookDeparture::where('unique_id',$unique_id)->get();
                    $available = Departure::find($request->id);
                    $available->available_seat = $request->available - ($total);
                    $available->save();
                    $columns = DepartureColumnType::where('departure_type_id', $available->departure_type)->get()->pluck('column_name_id');
                    $columns = json_encode($columns);
                    $supplier_name = User::where('id',$available->contact_person_id)->first();
                    $company_supplier = User::where('tenant_id',$available->tenant_id)->first();
                    if($available->additional_person_id !=""){
                        $supplier_forNoti_add = User::where('id',$available->additional_person_id)->first();
                    }else{
                        $supplier_forNoti_add = "";
                    }
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

                    $firebaseToken = User::where('id',$supplier_name->id)->whereNotNull('fcm_token')->value('fcm_token');
                    $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);
                    if($supplier_forNoti_add != ""){
                        $firebaseTokens = User::where('id',$supplier_forNoti_add->id)->whereNotNull('fcm_token')->value('fcm_token');
                        $sendNotification = $this->sendNotificationSupplier($firebaseTokens, $last_title_sup, $last_body_sup, $last_link_sup,$noti_sup->id);
                    }


                    // Booker Mail Notifiction ETC
                    Mail::send('mail.buyer_book_departure_mail_new', ['departure'=>$departure,'book_seat'=> $total, 'fname'=>auth()->user()->name,'lname'=>auth()->user()->last_name,'company'=>$company_supplier->company_name,'currency'=>$currency,'price'=>$total_price,'book_details_mail'=>$book_details_mail,'payment_schedule'=>$payment_schedule,'cancel_schedule'=>$cancel_schedule,'columns'=>$columns], function ($m) use ($supplier, $getData) {
                        $m->from($getData['from_mail'], $getData['from_name']);
                        $m->to(auth::user()->email);
                        //$m->to('raj.kumar@watconsultingservices.com');
                        $m->subject('Departure cloud  - Departure Book');
                    });
                    // Booker Mail Notifiction ETC End

                    // Supplier Mail Notifiction ETC
                    if($supplier_forNoti_add != ""){
                        Mail::send('mail.supplier_book_departure_mail_new', ['departure'=>$departure,'book_seat'=> $total,'fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name,'currency'=>$currency,'price'=>$total_price,'book_details_mail'=>$book_details_mail,'payment_schedule'=>$payment_schedule,'cancel_schedule'=>$cancel_schedule,'columns'=>$columns], function ($m) use ($supplier_name, $supplier_forNoti_add,$getData) {
                            $m->from($getData['from_mail'], $getData['from_name']);
                            $m->to($supplier_name->email);
                            $m->cc($supplier_forNoti_add->email);
                            $m->subject('Departure cloud  - Departure Book');
                        });
                    }else{
                        Mail::send('mail.supplier_book_departure_mail_new', ['departure'=>$departure,'book_seat'=> $total,'fname'=>$supplier_name->name,'lname'=>$supplier_name->last_name,'currency'=>$currency,'price'=>$total_price,'book_details_mail'=>$book_details_mail,'payment_schedule'=>$payment_schedule,'cancel_schedule'=>$cancel_schedule,'columns'=>$columns], function ($m) use ($supplier_name,$getData) {
                            $m->from($getData['from_mail'], $getData['from_name']);
                            $m->to($supplier_name->email);
                            $m->subject('Departure cloud  - Departure Book');
                        });
                    }
                    // Supplier Mail Notifiction ETC End

                    $success = 'Departure Booked Successfully';
                    $url = '/departures-details/'.$request->id;
                    return response()->json(['success'=>$success,'url'=>$url]);

            }
            else{
                 $error = 'Please make sure less than and equal to available unit = '.$request->available;
                return response()->json(['error'=>$error]);
            }
        }
        else{

            return response()->json(['required'=>"Please enter required unit."]);
        }
    }
    public function myBooked(Request $request){
    $departure_name = $request->departure_name;
    $departure_owner = $request->departure_owner;
    $departure_from = $request->departure_from;
    $departure_to = $request->departure_to;
    $booking_date = $request->booking_Date;
    $start = $request->start_date;
    $end = $request->end_date;
    $user = User::find(Auth::user()->id);
    if($request->departure_name || $request->departure_owner || $request->departure_from || $request->departure_to || $request->booking_Date || $request->start_date
        || $request->end_date){

        $start_date = date("Y-m-d", strtotime($request->start_date));
        $end_date = date("y-m-d", strtotime($request->end_date));
        $b = Departure::orWhere('title',$request->departure_name)
                ->orWhere('title',$request->departure_name)
                ->orWhere('departure_ownner',$request->departure_owner)
                ->orWhere('from',$request->departure_from)
                ->orWhere('ending_at',$request->departure_to)
                ->select('id')
                ->pluck('id')
                ->toArray();
        $bookcount = DB::table('book_departures')
                ->where('user_id',Auth::user()->id)
                ->whereIn('departure_id',$b)
                ->orWhereBetween('date',[$start_date,$end_date])
                ->select('unique_id')
                ->distinct()
                ->pluck('unique_id')
                ->toArray();
    }
    else{
        $bookcount = DB::table('book_departures')
                ->where('user_id',Auth::user()->id)
                ->select('unique_id')
                ->distinct()
                ->pluck('unique_id')
                ->toArray();
    }

    $mybook = DB::table('book_departures')
                ->whereIn('unique_id',$bookcount)
                ->select('unique_id','user_id')
                ->distinct()
                ->orderBy('unique_id','DESC')
                ->paginate(15);

    $dep_id = DB::table('book_departures')
                ->where('user_id',Auth::user()->id)
                ->select('departure_id')
                ->distinct()
                ->pluck('departure_id')
                ->toArray();
    $filter_departure = Departure::whereIn('id',$dep_id)->get();
    $filter_departure_company = Departure::whereIn('id',$dep_id)
                           ->select('departure_ownner')
                           ->distinct()
                           ->get();

    $filter_departure_from = Departure::whereIn('id',$dep_id)
                           ->select('from')
                           ->distinct()
                           ->get();
    $filter_departure_to = Departure::whereIn('id',$dep_id)
                           ->select('ending_at')
                           ->distinct()
                           ->get();
    $filter_booking_date = DB::table('book_departures')
                        ->where('user_id',Auth::user()->id)
                        ->select('date')
                        ->distinct()
                        ->get();
    foreach($mybook as $bd)
    {

        $booked_seat = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->sum('booked_seat');
        $bd->booked_seat = $booked_seat;

        $price = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->sum('price');
        $bd->price = $price;

        $book_value = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
        $bd->book_value = $book_value;

        $bd_currency = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
        $bd->currency = $bd_currency;

        $bookingDate = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
        $bd->bookingDate = date('d-M-Y h:i a', strtotime($bookingDate->created_at."+5 hours +30 minutes"));


        $d_id = BookDeparture::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
        $d_id->departure_id;
        $dep_name = Departure::where('id',$d_id->departure_id)->value('title');
        $bd->departure_name = $dep_name;

        $departure = Departure::where('id',$d_id->departure_id)->first();
        $bd->departure=$departure;

        $currency_symbol = Departure::where('id',$d_id->departure_id)->value('currency_symbol');
        $bd->currency_symbol = $currency_symbol;

        $company_name = Departure::where('id',$d_id->departure_id)->value('company_name');
        $bd->company_name = $company_name;

        $user_name = User::where('id',$user->id)
                    ->value('name');
        $bd->name = $user_name;

        $user_data = User::where(['tenant_id'=> $departure->tenant_id, 'main_user_type'=>1, 'user_type'=>0])->first();
        $bd->profileUserId = $user_data->id;

        // $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $user_data->company_id);
        // $strlower = Str::lower($removeSC);
        // $arr_cn = explode(' ', $strlower);
        // $str_cn = implode('-', $arr_cn);
        // $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
        // $mainstr = rtrim($mainstrs, '-');
        $bd->company_url = strtolower($user_data->company_id);
        //$bd->company_name = $user_data->company_name;

    }
    return view('departure.mybook',compact('mybook','user','filter_departure','filter_departure_company','filter_departure_from','filter_departure_to','filter_booking_date','departure_name','departure_owner','departure_from','departure_to','booking_date','start','end'));
   }


    public function myHolded(){
        $user = User::find(Auth::user()->id);
         // $holdcount = DB::table('hold_departure_seats')
         //            ->where('user_id',Auth::user()->id)
         //            ->select('unique_id')
         //            ->distinct()
         //            ->pluck('unique_id')
         //            ->toArray();

        $myhold = DB::table('hold_departure_seats')
                    ->where('user_id',Auth::user()->id)
                    ->select('unique_id')
                    ->groupBy('unique_id')
                    ->orderBy('unique_id','DESC')
                    ->paginate(25);
                   // dd($myhold);
       //return count($myhold);
            foreach($myhold as $bd)
            {
                $booked_seat = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->sum('hold_seat');
                $bd->booked_seat = $booked_seat;

                $price = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->sum('price');
                $bd->price = $price;

                $book_value = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
                $bd->book_value = $book_value;

                $bd_currency = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
                $bd->currency = $bd_currency;

                $d_id = HoldDepartureSeat::where('user_id',$user->id)->where('unique_id',$bd->unique_id)->first();
                $bd->d_id = $d_id;

                $dep_name = Departure::where('id',$bd->d_id->departure_id)->value('title');
                $dep_nights = Departure::where('id',$bd->d_id->departure_id)->value('no_of_nights');
                $dep_days = Departure::where('id',$bd->d_id->departure_id)->value('no_of_days');
                $bd->departure_name = $dep_name;
                $bd->nights = $dep_nights;
                $bd->days = $dep_days;

                $currency_symbol = Departure::where('id',$bd->d_id->departure_id)->value('currency_symbol');
                $bd->currency_symbol = $currency_symbol;

                $company_name = Departure::where('id',$bd->d_id->departure_id)->value('company_name');
                $bd->company_name = $company_name;

                $user_name = User::where('id',$user->id)
                            ->value('name');
                $bd->name = $user_name;

            }

            //return view('departure.departure_booking_history',compact('book_date
        return view('departure.myhold',compact('myhold','user'));
    }

    public function release(Request $request, $id){
        $getData = MailHelper::setMailConfig();
        $d_id = HoldDepartureSeat::where('unique_id',$id)->first();
        //dd($d_id);
        $release = HoldDepartureSeat::where('unique_id',$id)->sum('hold_seat');
        $hold =$release;
        $departure = $d_id->departure_id;
        $dep = Departure::find($departure);
        $available = $dep->available_seat;
        $update = Departure::find($departure);
        $update->available_seat = $available + $hold;
        $update->save();

        $user = User::where('id',$dep->contact_person_id)->first();
        $ids = HoldDepartureSeat::where('unique_id',$id)->get();

        //Notification
        $noti_save = new Notification;
        $noti_save->title = 'Departure cloud - Hold Units Released';
        $noti_save->body = $hold.' units held by you in departure for' .$dep->ending_at. ' are released  by supplier';

        $noti_save->body_html = '<p><b> '.$hold.' </b>units held by you in departure for<b> ' .$dep->ending_at. ' </b>are released  by supplier. To contact supplier pls login to departure cloud</p>';
        $noti_save->user_id = $user->id;
        $noti_save->type = "ForceRelease";
        $noti_save->url_1 = url('login');
        $noti_save->save();

        $last_body_sup = $noti_save->body;
        $last_title_sup = $noti_save->title;
        $last_link_sup = $noti_save->url_1;

        $firebaseToken = User::where('id',$user->id)->whereNotNull('fcm_token')->value('fcm_token');
        $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_save->id);


        //Notification
        Mail::send('mail.release_hold', ['hold' => $hold,'destination'=>$dep->ending_at,'user'=>$user,'forceRelease'=>'yes'], function ($m) use ($user, $getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to($user->email);
                //$m->to('raj.kumar@watconsultingservices.com');
                $m->subject('Departure cloud  -Hold Units Released');
            });
        foreach ($ids as $key => $value) {
            HoldDepartureSeat::where('id',$value->id)->delete();
        }

        //return redirect()->back()->with('msg','Released Departures');
        return response()->json();
    }

    // public function releaseHoldMyBooking(Request $request, $id){
    //     $d_id = HoldDepartureSeat::where('unique_id',$id)->first();
    //     $release = HoldDepartureSeat::where('unique_id',$id)->sum('hold_seat');
    //     $hold =$release;
    //     $departure = $d_id->departure_id;
    //     $dep = Departure::find($d_id->departure_id);
    //     $available = $dep->available_seat;
    //     $update = Departure::find($d_id->departure_id);
    //     $update->available_seat = $available + $d_id->hold_seat;
    //     $update->save();
    //     $ids = HoldDepartureSeat::where('unique_id',$id)->get();
    //     foreach ($ids as $key => $value) {
    //         HoldDepartureSeat::where('id',$value->id)->delete();
    //     }
    //     return redirect()->back()->with('msg','Released');
    //     //return redirect()->back();
    // }
    // hold history function
    public function ForceRelease(Request $request, $id){
        $getData = MailHelper::setMailConfig();
        $hold_seat = HoldDepartureSeat::where('id',$id)->first();
        $available = Departure::where('id', $hold_seat->departure_id)->first();
        $update_dep = Departure::find($hold_seat->departure_id);
        $update_dep->available_seat = $available->available_seat + $hold_seat->hold_seat;
        $update_dep->save();
        $user = User::where('id',$available->contact_person_id)->first();

        //Notification
        $noti_save = new Notification;
        $noti_save->title = 'Departure cloud - Hold Units Released';
        $noti_save->body = $hold_seat->hold_seat.' units held by you in departure for' .$available->ending_at. 'are released  by supplier';

        $noti_save->body_html = '<p><b> '.$hold.' </b>units held by you in departure for<b> ' .$dep->ending_at. ' </b>are released  by supplier. To contact supplier pls login to departure cloud</p>';
        $noti_save->user_id = $user->id;
        $noti_save->type = "ForceRelease";
        $noti_save->url_1 = url('login');
        $noti_save->save();
        $last_body_sup = $noti_save->body;
        $last_title_sup = $noti_save->title;
        $last_link_sup = $noti_save->url_1;

        $firebaseToken = User::where('id',$user->id)->whereNotNull('fcm_token')->value('fcm_token');
        $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_save->id);

        //Notification

        Mail::send('mail.release_hold', ['hold' => $hold_seat->hold_seat,'user'=>$user,'destination'=>$available->ending_at,'forceRelease'=>'yes'], function ($m) use ($user,$getData ) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($user->email);
            //$m->to('raj.kumar@watconsultingservices.com');
            $m->subject('Departure cloud  -Hold Units Released');
        });

        HoldDepartureSeat::find($id)->delete();
        return redirect()->back()->with('msg','Hold seat released successfully');
    }

    public function startFromDestination(Request $request){
        $startDest = [];
        if($request->has('q')){
            $search = $request->q;
            $startDest = DB::table('all_destinations')->select("id","destination")
                        ->where('destination','LIKE',"$search%")
                        ->get(15);

        }else{
            $startDest = DB::table('all_destinations')->select("id","destination")
                        ->limit(15)
                        ->get();
        }
        return response()->json($startDest);
    }

   public function SearchCountry(Request $request){
        $startDest = [];
        if($request->has('q')){
         $search = $request->q;
            $startDest = DB::table('all_countries')->select("id","country_name")
                        ->where('country_name','LIKE',"$search%")
                        ->get(15);

        }else{
            $startDest = DB::table('all_countries')->select("id","country_name")
                        ->limit(15)
                        ->get();
        }
        return response()->json($startDest);
    }

    public function departureAirline(Request $request){
        $startDest = [];
        if($request->has('q')){
            $search = $request->q;
            $startDest = DB::table('all_airlines')->select("id","airline")
                        ->where('airline','LIKE',"%$search%")
                        ->get(15);

        }else{
            $startDest = DB::table('all_airlines')->select("id","airline")
                        ->limit(15)
                        ->get();
        }
        return response()->json($startDest);
    }

    public function DepartureDestinationSearch(Request $request){
        $startDest = [];
        if($request->has('q')){
            $search = $request->q;
            $startDest = DB::table('all_destinations')->select("id","destination")
                        ->where('destination','LIKE',"$search%")
                        ->get(15);

        }else{
            $startDest = DB::table('all_destinations')->select("id","destination")
                        ->limit(10)
                        ->get();
        }
        return response()->json($startDest);
    }


    public function AllDepartureDetails($id){
        $departure_details = Departure::where('id',$id)->first();
        $log_img = DB::table('users')->where('tenant_id',$departure_details->tenant_id)->value('logo');
        $departure_details->logo_image = url('companyLogo').'/'.$log_img;
        if($departure_details){
        // company details
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
                     //->where('inclusions.user_id',Auth::user()->id)
                     ->where('inclusions.departure_id',$id)
                     ->get();
        $day_itinerary = Itinerary::where('departure_id', $id)->get();

        foreach($day_itinerary as $row){
            $day_destination = explode(',',$row->destinations);
            $row->day_destination = DB::table('destinations')
                ->whereIn('id',$day_destination)
                ->select('id','dest_name')
                ->get();
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
            ->where('book_departures.departure_id',$id)->get();
        $departure_prices = DeparturePrice::where('departure_id',$departure_details->id)->where('status', 1)->get();

        $originating = DepartureFlightDetail::where('departure_id',$departure_details->id)->get();
        $returning = ReturnFlightDetail::where('departure_id',$departure_details->id)->get();
        $indian_currency=CurrencyConversion::first();
        $inr = $indian_currency->indian_currency;
        $payment_schedule = PaymentSchedule::where('departure_id',$departure_details->id)->get();
        $total_price = PaymentSchedule::where('departure_id',$departure_details->id)->sum('price');
        $total_percentage = PaymentSchedule::where('departure_id',$departure_details->id)->sum('percentage');
        $total_single = PaymentSchedule::where('departure_id',$departure_details->id)->sum('single_supplement');
        $total_cost = PaymentSchedule::where('departure_id',$departure_details->id)->sum('total');
        $users = User::where(['tenant_id'=>$departure_details->tenant_id, 'main_user_type'=>1, 'user_type'=>0])->first();
        //  $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
        //  $strlower = Str::lower($removeSC);
        //  $arr_cn = explode(' ', $strlower);
        //  $str_cn = implode('-', $arr_cn);
        //  $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
        // $mainstr = rtrim($mainstrs, '-');
        $company_url = strtolower($users->company_id);

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
        $hotels = DB::table('departure_hotels')->where('departure_id',$departure_details->id)->get();
        if(count($hotels)>0){
            foreach ($hotels as $key => $hotel) {
                $hotel->destination_id = Destination::where('id',$hotel->destination_id)->value('dest_name');
                $hotel->hotel_category = DB::table('hotel_categories')->where('id',$hotel->hotel_category)
                        ->value('hotel_category');
            }
        }
        //dd($hotels);
        $columns = DepartureColumnType::where('departure_type_id', $departure_details->departure_type)->get()->pluck('column_name_id');
        $columns = json_encode($columns);
        $departure_types = DepartureType::where('id', $departure_details->departure_type)->first();

        // Nearest Dates
        $nearByDates = Departure::where('start_date','>',$departure_details->start_date)
                    ->orderBy('start_date','ASC')->limit(10)->get();


        if($departure_details->departure_type == 5){
            $f_originating = DB::table('departure_flight_details')
                ->where('departure_id', $departure_details->id)->get();
            $f_returning = DB::table('return_flight_details')
                ->where('departure_id', $departure_details->id)->get();

            foreach ($f_originating as $key => $f_origin) {
                $d_date = date("d M, Y", strtotime($f_origin->flight_date));
                $a_date = date("d M, Y", strtotime($f_origin->flight_arrival_date));
                $f_origin->departure_itinerary = "Departure - Flight - ".$d_date;
                $f_origin->arrival_itinerary = "Arrival - Flight - ".$a_date;
            }
            foreach ($f_returning as $key => $f_return) {
                $rd_date = date("d M, Y", strtotime($f_return->flight_date));
                $ra_date = date("d M, Y", strtotime($f_return->flight_arrival_date));
                $f_return->departure_itinerary = "Departure - Flight - ".$rd_date;
                $f_return->arrival_itinerary = "Arrival - Flight - ".$ra_date;
            }
            $default_iti0 = $f_originating;
            $default_iti1 = $f_returning;
            $default_iti2 = [];

        }elseif($departure_details->departure_type == 4){
            $hotels2 = DB::table('departure_hotels')
                ->where('departure_id', $departure_details->id)->get();

            foreach ($hotels2 as $key => $hotel) {
                $checkin = date("d M, Y", strtotime($departure_details->start_date));
                $checkout = date("d M, Y", strtotime($departure_details->end_date));
                $hotel->checkin = "Checkin - Hotel - ".$checkin;
                $hotel->checkout = "Checkout - Hotel - ".$checkout;
            }
            $default_iti0 = [];
            $default_iti1 = [];
            $default_iti2 = $hotels2;

        }elseif($departure_details->departure_type == 3){
            $f_originating = DB::table('departure_flight_details')
                ->where('departure_id', $departure_details->id)->get();
            $f_returning = DB::table('return_flight_details')
                ->where('departure_id', $departure_details->id)->get();

            foreach ($f_originating as $key => $f_origin) {
                $d_date = date("d M, Y", strtotime($f_origin->flight_date));
                $a_date = date("d M, Y", strtotime($f_origin->flight_arrival_date));
                $f_origin->departure_itinerary = "Departure - Flight - ".$d_date;
                $f_origin->arrival_itinerary = "Arrival - Flight - ".$a_date;
            }
            foreach ($f_returning as $key => $f_return) {
                $rd_date = date("d M, Y", strtotime($f_return->flight_date));
                $ra_date = date("d M, Y", strtotime($f_return->flight_arrival_date));
                $f_return->departure_itinerary = "Departure - Flight - ".$rd_date;
                $f_return->arrival_itinerary = "Arrival - Flight - ".$ra_date;
            }

            $hotels1 = DB::table('departure_hotels')
                ->where('departure_id', $departure_details->id)->get();
            foreach ($hotels1 as $key => $hotel) {
                $checkin = date("d M, Y", strtotime($departure_details->start_date));
                $checkout = date("d M, Y", strtotime($departure_details->end_date));
                $hotel->checkin = "Checkin - Hotel - ".$checkin;
                $hotel->checkout = "Checkout - Hotel - ".$checkout;
            }

            $default_iti0 = $f_originating;
            $default_iti1 = $f_returning;
            $default_iti2 = $hotels1;
        }else{
            $default_iti0 = [];
            $default_iti1 = [];
            $default_iti2 = [];
        }
        //dd($company_details_all);
        return view('departure.all_departure_details',compact('departure_details','itinerary','inclusion','user_hold','departure_destination','price','price1','price2','price3','hold','date','count','departure_book','hold_till','extra_hold','originating','returning','inr','payment_schedule','total_price','total_percentage','total_single','total_cost','departure_prices','book_date','hold_date','company_url','cancelation_schedule','company_details_all','contact_person_det','columns','departure_types','hotels','nearByDates','default_iti0','default_iti1','default_iti2','day_itinerary'));
        }else{
            return view('404');
        }
    }



    public function BookingHistory(){

        $book = DB::table('book_departures')
        ->leftjoin('departures','departures.id','book_departures.departure_id')
        ->select('departures.*','book_departures.booked_seat','book_departures.date','book_departures.user_id as uid')
        ->paginate(15);

        foreach($book as $row){
          $buyers = DB::table('users')->where('id',$row->uid)->get();
           $row->company=$buyers;
        }
              return view('departure.booking_history',compact('book'));
    }

    public function HoldHistory(){
          $hold = DB::table('hold_departure_seats')
        ->leftjoin('departures','departures.id','hold_departure_seats.departure_id')
        ->select('departures.*','hold_departure_seats.hold_duration','hold_departure_seats.date','hold_departure_seats.hold_seat','hold_departure_seats.user_id as uid','hold_departure_seats.id as hold_id','hold_departure_seats.price','hold_departure_seats.currency_code')
        //->where('book_departures.user_id',Auth::user()->id)
        ->paginate(25);
        foreach($hold as $row){
            $holded_user = DB::table('users')->where('id',$row->uid)->get();
         $row->hold=$holded_user;
          }
          return view('departure.hold_history',compact('hold'));
    }
    public function DepartureBookingHistory($id)
    {
        $b = DB::table('book_departures')->where('departure_id',$id)->get();
        $permission = User::getPermissions();
        if (Gate::allows('departure_booking_history',$permission))
        {
        $book_date = DB::table('book_departures')
                    ->where('departure_id',$id)
                    ->select('unique_id','user_id','tenant_id')
                    ->distinct()
                    //->orderBy('created_at','DESC')
                    ->paginate(10);
                   //return count($book_date);
        foreach($book_date as $bd)
            {

                $booked_seat = BookDeparture::where('departure_id',$id)->where('unique_id',$bd->unique_id)->sum('booked_seat');
                $bd->booked_seat = $booked_seat;

                $booked_value = BookDeparture::where('unique_id',$bd->unique_id)->where('departure_id',$id)->where('user_id',$bd->user_id)->first();
                $bd->booked_value = $booked_value;

                $price = BookDeparture::where('user_id',$bd->user_id)->where('unique_id',$bd->unique_id)->sum('price');

                $bd->price = $price;

                $bd_currency = BookDeparture::where('unique_id',$bd->unique_id)->where('user_id',$bd->user_id)->where('departure_id',$id)->first();
                $bd->currency = $bd_currency;


                $dep_name = Departure::where('id',$id)->value('title');
                $bd->departure_name = $dep_name;

                $currency_symbol = Departure::where('id',$id)->value('currency_symbol');
                $bd->currency_symbol = $currency_symbol;

                $company_name = User::where('id',$bd->user_id)->value('company_name');
                $bd->company_name = $company_name;

                $user_name = User::where('id',$bd->user_id)
                            ->value('name');
                $bd->name = $user_name;

                // $company_nameBuyer = User::where('id',$bd->user_id)->value('company_name');
                // $bd->company_nameBuyer = $company_nameBuyer;

            }

            return view('departure.departure_booking_history',compact('book_date','permission','id'));
        }
        else{
            return view('404');
        }
    }
    public function DepartureHoldHistory($id)
    {


        $b = DB::table('hold_departure_seats')->where('departure_id',$id)->get();
        if($b)
        {
        $permission = User::getPermissions();
        if (Gate::allows('departure_booking_history',$permission))
        {
           $book_date = DB::table('hold_departure_seats')
                    ->where('departure_id',$id)
                    ->select('unique_id','user_id')
                    ->distinct()
                    //->orderBy('created_at','DESC')
                    ->paginate(10);
                   //return count($book_date);
          foreach($book_date as $bd)
            {

                $booked_seat = HoldDepartureSeat::where('departure_id',$id)->where('unique_id',$bd->unique_id)->sum('hold_seat');
                $bd->booked_seat = $booked_seat;

                $booked_value = HoldDepartureSeat::where('departure_id',$id)->where('user_id',$bd->user_id)->first();
                $bd->booked_value = $booked_value;

                $price = HoldDepartureSeat::where('user_id',$bd->user_id)->where('unique_id',$bd->unique_id)->sum('price');

                $bd->price = $price;

                $bd_currency = HoldDepartureSeat::where('user_id',$bd->user_id)->where('departure_id',$id)->first();
                $bd->currency = $bd_currency;


                $dep_name = Departure::where('id',$id)->value('title');
                $bd->departure_name = $dep_name;

                $currency_symbol = Departure::where('id',$id)->value('currency_symbol');
                $bd->currency_symbol = $currency_symbol;

                $company_name = Departure::where('id',$id)->value('company_name');
                $bd->company_name = $company_name;

                $user_name = User::where('id',$bd->user_id)
                            ->value('name');
                $bd->name = $user_name;

            }

            return view('departure.departure_hold_history',compact('book_date','permission','id'));
        }
        }
        else{
            return view('404');
        }
    }
    public function AllDepartureBookingHistory(Request $request){
        $start = '';
        $end = '';
        if(Auth::user()->main_user_type == 2){
            $departure_id = Departure::where('status',1)
                            ->where('approve',1)
                            ->pluck('id')
                            ->toArray();
            if($request->start_date || $request->departure_name || $request->departure_to || $request->departure_from || $request->end_date || $request->departure_owner){
                $request->departure_name;
                $departure_id = Departure::orWhere('title',$request->departure_name)
                    ->orWhere('from',$request->departure_from)
                    ->orWhere('ending_at',$request->departure_to)
                    //->where('tenant_id',$u->tenant_id)
                    ->pluck('id')
                    ->toArray();
                if($request->start_date != '' || $request->end_date){
                   $start = $request->start_date;
                   $end = $request->end_date;
                   $start_date = date("Y-m-d", strtotime($request->start_date));
                   $end_date = date("Y-m-d", strtotime($request->end_date));
                   $book = DB::table('book_departures')
                        ->whereBetween('date',[$start_date,$end_date])
                        ->orWhere('user_id',$request->departure_owner)
                        ->whereIn('departure_id',$departure_id)
                        ->select('unique_id','user_id','tenant_id','created_at')
                        ->distinct()
                        ->orderBy('unique_id','DESC')
                        ->paginate(20);
                        //return count($book);
                }else{
                   $book = DB::table('book_departures')
                        ->orWhere('user_id',$request->departure_owner)
                        ->whereIn('departure_id',$departure_id)
                        ->select('unique_id','user_id','tenant_id','created_at')
                        ->distinct()
                        ->orderBy('unique_id','DESC')
                        ->paginate(20);
               }
            }
            else
            {
                $book = DB::table('book_departures')
                //->where('departure_id',$departure_id)
                ->select('unique_id','user_id','tenant_id','created_at')
                ->distinct()
                ->orderBy('unique_id','DESC')
                ->paginate(20);
            }
            $departure_id = Departure::where('status',1)
                        ->where('approve',1)
                        ->pluck('id')
                        ->toArray();
            $b = DB::table('book_departures')
                        ->whereIn('departure_id',$departure_id)
                        ->select('unique_id')
                        ->distinct()
                        ->pluck('unique_id')
                        ->toArray();
            $filter_departure = Departure::whereIn('id',$departure_id)->get();
            $filter_departure_from = Departure::whereIn('id',$departure_id)
                                   ->select('from')
                                   ->distinct()
                                   ->get();
            $filter_departure_to = Departure::whereIn('id',$departure_id)
                                   ->select('ending_at')
                                   ->distinct()
                                   ->get();
            $filter_booking_date = DB::table('book_departures')
                                ->whereIn('departure_id',$departure_id)
                                ->whereIn('unique_id',$b)
                                ->select('date')
                                ->distinct()
                                ->get();
            $filter_company = DB::table('book_departures')
                                ->whereIn('departure_id',$departure_id)
                                ->whereIn('unique_id',$b)
                                ->select('user_id')
                                ->distinct()
                                ->pluck('user_id')
                                ->toArray();
            $filter_departure_company = User::whereIn('id',$filter_company)->get();
            $total = count($book);
        }
        elseif(Auth::user()->main_user_type == 1){
            $u = User::where('id',Auth::user()->id)->first();
            if($request->start_date || $request->departure_name || $request->departure_to || $request->departure_from || $request->end_date || $request->departure_owner){
                $departure_id = Departure::orWhere('title',$request->departure_name)
                    ->orWhere('from',$request->departure_from)
                    ->orWhere('ending_at',$request->departure_to)
                    ->where('tenant_id',$u->tenant_id)
                    ->pluck('id')
                    ->toArray();
              if($request->start_date != '' || $request->end_date){
                   $start = $request->start_date;
                       $end = $request->end_date;
                   $start_date = date("Y-m-d", strtotime($request->start_date));
                   $end_date = date("y-m-d", strtotime($request->end_date));
                   $book = DB::table('book_departures')
                        ->whereBetween('date',[$start_date,$end_date])
                        ->orWhere('user_id',$request->departure_owner)
                        ->whereIn('departure_id',$departure_id)
                        ->select('unique_id','user_id','tenant_id')
                        ->distinct()
                        ->orderBy('unique_id','DESC')
                        ->paginate(20);
                }else{
                   $book = DB::table('book_departures')
                        ->orWhere('user_id',$request->departure_owner)
                        ->whereIn('departure_id',$departure_id)
                        ->select('unique_id','user_id','tenant_id','created_at')
                        ->distinct()
                        ->orderBy('unique_id','DESC')
                        ->paginate(20);
                }
            }
            else{
                 $departure_id = Departure::where('tenant_id',$u->tenant_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->pluck('id')
                    ->toArray();
                 $book = DB::table('book_departures')
                        ->whereIn('departure_id',$departure_id)
                        ->select('unique_id','user_id','tenant_id','created_at')
                        ->distinct()
                        ->orderBy('unique_id','DESC')
                        ->paginate(20);
            }


            $total = count($book);

            $b = DB::table('book_departures')
                        ->whereIn('departure_id',$departure_id)
                        ->distinct()
                        ->pluck('unique_id')
                        ->toArray();
            $filter_departure = Departure::whereIn('id',$departure_id)->get();
            $filter_departure_from = Departure::whereIn('id',$departure_id)
                                   ->select('from')
                                   ->distinct()
                                   ->get();
            $filter_departure_to = Departure::whereIn('id',$departure_id)
                                   ->select('ending_at')
                                   ->distinct()
                                   ->get();
            $filter_booking_date = DB::table('book_departures')
                                ->whereIn('departure_id',$departure_id)
                                ->whereIn('unique_id',$b)
                                ->select('date')
                                ->distinct()
                                ->get();
           $filter_company = DB::table('book_departures')
                                ->whereIn('departure_id',$departure_id)
                                ->whereIn('unique_id',$b)
                                ->select('user_id')
                                ->distinct()
                                ->pluck('user_id')
                                ->toArray();
          $filter_departure_company = User::whereIn('id',$filter_company)->get();

        }
        else
        {
            return view('404');
        }
        foreach($book as $bd)
            {
                $d_id =BookDeparture::where('unique_id',$bd->unique_id)->first();
                $departure = Departure::where('id',$d_id->departure_id)->first();
                $bd->departure = $departure;

                $booked_seat = BookDeparture::
                            where('unique_id',$bd->unique_id)
                            //->distinct()
                            ->sum('booked_seat');
                $bd->booked_seat = $booked_seat;

                $price = BookDeparture::where('unique_id',$bd->unique_id)
                        //->distinct()
                        ->sum('price');
                $bd->price = $price;

                $booked_value = BookDeparture::where('unique_id',$bd->unique_id)
                            //->where('user_id',$departure->user_id)
                            ->first();
                $bd->booked_value = $booked_value;

                $created_date = BookDeparture::where('unique_id',$bd->unique_id)
                            ->where('user_id',$departure->user_id)
                            ->value('created_at');
                $bd->created_date = $created_date;

                $bd_currency = BookDeparture::where('departure_id',$departure->id)
                            ->where('unique_id',$bd->unique_id)
                            ->value('currency_symbol');
                $bd->currency = $bd_currency;

                // public urls

                $user_data1 = User::where('id', $bd->user_id)->first();
                if($user_data1->last_name != ""){
                    $bd->full_name = $user_data1->name .' '.$user_data1->last_name;
                }else{
                    $bd->full_name = $user_data1->name;
                }
                $user_data = User::where('tenant_id', $bd->tenant_id)
                    ->first();
                $bd->profileUserId = $user_data->id;
                $bd->name = $user_data->company_name;

                // $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $user_data->company_id);
                // $strlower = Str::lower($removeSC);
                // $arr_cn = explode(' ', $strlower);
                // $str_cn = implode('-', $arr_cn);
                // $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
                // $mainstr = rtrim($mainstrs, '-');
                $bd->company_url = strtolower($user_data->company_id);

            }
           // dd($book);
       
       return view('departure.all_departure_booking_history',compact('book','total','filter_departure','filter_departure_company','filter_departure_from','filter_departure_to','filter_booking_date','start','end'));
    }
    public function AllDepartureHoldHistory(){
        if(Auth::user()->main_user_type == 2){
            $hold = DB::table('hold_departure_seats')
            //->where('departure_id',$departure_id)
            ->select('unique_id','created_at')
            ->distinct()
            ->orderBy('created_at','DESC')
            ->paginate(20);
        $total = count($hold);
        }
        elseif(Auth::user()->main_user_type == 1){
        $u = User::where('id',Auth::user()->id)->first();
        $departure_id = Departure::where('tenant_id',$u->tenant_id)
                ->pluck('id')
                ->toArray();
        $hold = DB::table('hold_departure_seats')
                    ->whereIn('departure_id',$departure_id)
                    ->select('unique_id','created_at')
                    ->distinct()
                    ->orderBy('created_at','DESC')
                    ->paginate(20);
        $total = count($hold);
        }
        else
        {
            return view('404');
        }
        foreach($hold as $bd)
            {
                $d_id =HoldDepartureSeat::where('unique_id',$bd->unique_id)->first();
                 $booked_seat = HoldDepartureSeat::where('user_id',$d_id->user_id)->where('unique_id',$d_id->unique_id)->sum('hold_seat');
                $bd->booked_seat = $booked_seat;

                $extra_hold = HoldDepartureSeat::where('user_id',$d_id->user_id)->where('unique_id',$d_id->unique_id)->sum('extra_hold_seat');
                $bd->extra_hold = $extra_hold;

                $price = HoldDepartureSeat::where('user_id',$d_id->user_id)->where('unique_id',$d_id->unique_id)->sum('price');
                $bd->price = $price;

                $hold_value = HoldDepartureSeat::where('unique_id',$d_id->unique_id)->where('user_id',$d_id->user_id)->first();
                $bd->hold_value = $hold_value;

                $bd_currency = HoldDepartureSeat::where('departure_id',$d_id->departure_id)->where('unique_id',$d_id->unique_id)->first();
                 $bd->currency = $bd_currency;
                $departure = Departure::where('id',$d_id->departure_id)->first();
                $bd->departure = $departure;

                $user_name = User::where('id',$d_id->user_id)
                    ->select('company_name','name','last_name')
                    ->first();
                if($user_name->last_name != ""){
                    $bd->full_name = $user_name->name .' '.$user->last_name;
                }else{
                    $bd->full_name = $user_name->name;
                }
                $bd->name = $user_name->company_name;

            }
       return view('departure.all_departure_hold_history',compact('hold','total'));
    }
    public function BookingHistoryDetails($id){

        $book = DB::table('book_departures')
                    ->where('unique_id',$id)
                    ->distinct()
                    ->value('id');
        if($book){
         $booking_details = BookDeparture::find($book);
         $booking_price = BookDeparture::where('unique_id',$id)->sum('price');
         $price_details = BookDeparture::where('unique_id',$id)->get();
         $booking_unit = BookDeparture::where('unique_id',$id)->sum('booked_seat');
         $departure = Departure::find($booking_details->departure_id);

        $user = User::find($booking_details->user_id);
        if($user->last_name != ""){
            $user->full_name = $user->name .' '.$user->last_name;
        }else{
            $user->full_name = $user->name;
        }
        $destination = DB::table('departure_destinations')
            ->join('destinations','departure_destinations.destination_id','destinations.id')
            ->select('destinations.country_name','destinations.dest_name')
         ->where('departure_destinations.departure_id',$departure->id)
            ->get();
        $departure_prices = PaymentSchedule::where('departure_id',$departure->id)->get();
        $update_price = DepartureBookingPriceUpdates::where('booking_unique_id',$id)->get();
        $tenant = User::find($departure->user_id);
        $tenant_id = $tenant->tenant_id;

        $update_price_sum = DepartureBookingPriceUpdates::where('booking_unique_id',$id)->sum('price');
        $users = User::where(['tenant_id'=>$departure->tenant_id, 'main_user_type'=>1, 'user_type'=>0])->first();
        // $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
        //  $strlower = Str::lower($removeSC);
        //  $arr_cn = explode(' ', $strlower);
        //  $str_cn = implode('-', $arr_cn);
        //  $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
        // $mainstr = rtrim($mainstrs, '-');
        $company_url = strtolower($users->company_id);
        $url = '/supplier-profile/'.$departure->id.'/'.$company_url;

        $itinerary = AgentItinerarie::where('departure_id',$booking_details->departure_id)->first();
        return view('departure.departure_booking_history_details',compact('booking_details','departure','user','destination','departure_prices','booking_price','booking_unit','price_details','id','update_price','update_price_sum','url','itinerary','tenant_id'));
        }else{
            return view('404');
        }
    }


    public function HoldHistoryDetails($id){

          $book = DB::table('hold_departure_seats')
                        ->where('unique_id',$id)
                        ->distinct()
                        ->value('id');
         if($book){
             $booking_details = HoldDepartureSeat::find($book);
             $booking_price = HoldDepartureSeat::where('unique_id',$id)->sum('price');
             $price_details = HoldDepartureSeat::where('unique_id',$id)->get();
             $booking_unit = HoldDepartureSeat::where('unique_id',$id)->sum('hold_seat');
             $departure = Departure::find($booking_details->departure_id);

                $user = User::find($booking_details->user_id);
                $destination = DB::table('departure_destinations')
                ->join('destinations','departure_destinations.destination_id','destinations.id')
                ->select('destinations.country_name','destinations.dest_name')
             ->where('departure_destinations.departure_id',$departure->id)
             ->get();

            $departure_prices = PaymentSchedule::where('departure_id',$departure->id)->get();
            return view('departure.departure_holds_history_details',compact('booking_details','departure','user','destination','departure_prices','booking_price','booking_unit','price_details'));
         }else{
           return view('404');
        }
    }




    // buyer & supplier
    public function UserList(Request $request){
        //return $request->all();
        if(isset($request->keyword))
        {
            $keyword = $request->keyword;
        }else{
            $keyword = '';
        }
        if(isset($request->status)){
            $statuss = $request->status;
        }else{
            $statuss = '';
        }
        if($request->keyword || $request->status)
        {
            if($request->status == 2)
            {
                $status = 0;
            }
            else{
                $status = $request->status;
            }
            //return $request->status;
            $userlist = User::distinct('tenant_id')
                        ->where('main_user_type',0)
                        ->where('role_id',0)
                        ->where('user_type',0)
                        ->where('verified',$status)
                        ->where(function ($query) use ($request) {
                           $query->where('name', "like", "%" . $request->keyword. "%");
                           $query->orWhere('email', "like", "%" . $request->keyword. "%");
                           $query->orWhere('company_name', "like", "%" . $request->keyword. "%");

                    })
                    ->orderBy('created_at','DESC')
                    ->paginate(25);
        }
        else
        {
            $userlist = User::distinct('tenant_id')
                ->where(['main_user_type'=>0,'role_id'=>0])
                //->where('company_name','!=','')
                ->orderBy('created_at','DESC')
                ->paginate(25);

        }
        $active_count = User::where(['main_user_type'=>0,'role_id'=>0,'user_type'=>0])->where('verified',1)->count();
        $toal_count = User::where(['main_user_type'=>0,'role_id'=>0,'user_type'=>0])->count();
        
        if($request->excel == 'excel'){
          return Excel::download(new BuyerExport, 'buyers.csv');
        }
        if(Auth::user()->main_user_type == 2)
        {
            return view('departure.user_list',compact('userlist','keyword','statuss','active_count','toal_count'));
        }
        else
        {
            return view('404');
        }

    }

    public function BuyerUserList(Request $request, $id){
        $tenant_id = User::where('id', $id)
                    ->first();
        $userlist = User::where('tenant_id', $tenant_id->tenant_id)
                    ->where('id','!=', $id)
                    ->paginate(15);
        foreach($userlist as $ulist){
            $roles = DB::table('roles')
                ->where('id', $ulist->role_id)
                ->value('name');
            $ulist->role = $roles;
        }
        
        return view('departure.buyer_user',compact('userlist','tenant_id'));
    }


    public function SupplierList(Request $request){
        $date = date("Y-m-d");
        if(isset($request->keyword))
        {
            $keyword = $request->keyword;
        }
        else
        {
            $keyword = '';
        }
        if(isset($request->status))
        {
            $statuss = $request->keyword;
        }
        else
        {
            $statuss = '';
        }
        if($request->keyword || $request->status)
        {
            if($request->status == 2)
            {
                $status = 0;
            }
            else{
                $status = $request->status;
            }

            //return $request->status;
            $userlist = User::distinct('tenant_id')
                        ->where('main_user_type',1)
                        ->where('role_id',0)
                        ->where('user_type',0)
                        ->where('verified',$status)
                        ->where(function ($query) use ($request) {
                           $query->where('name', "like", "%" . $request->keyword. "%");
                           $query->orWhere('email', "like", "%" . $request->keyword. "%");
                           $query->orWhere('company_name', "like", "%" . $request->keyword. "%");

                    })
                    ->orderBy('created_at','DESC')
                    ->paginate(25);
                    
                    foreach ($userlist as $userlists)
                    {
                        $totaldeps = Departure::where('tenant_id',$userlists->tenant_id)->where('approve',1)->count();
                        $userlists->totalcount = $totaldeps;
                        $activedeps = Departure::where('tenant_id',$userlists->tenant_id)
                        ->where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', $date)
                        ->count();
                        $userlists->activedeps = $activedeps;
                    }
         }           
        else
        {
            $userlist = User::distinct('tenant_id')
                    ->where(['main_user_type'=>1,'role_id'=>0])
                    //->where('company_name','!=','')
                    ->orderBy('created_at','DESC')
                    ->paginate(25);
                    foreach ($userlist as $userlists)
                    {
                        $totaldeps = Departure::where('tenant_id',$userlists->tenant_id)->where('approve',1)->count();
                        $userlists->totalcount = $totaldeps;
                        $activedeps = Departure::where('tenant_id',$userlists->tenant_id)
                        ->where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', $date)
                        ->count();
                        $userlists->activedeps = $activedeps;
                        
                    }
        }
        $active_count = User::where(['main_user_type'=>1,'role_id'=>0,'user_type'=>0])->where('verified',1)->count();
        $toal_count = User::where(['main_user_type'=>1,'role_id'=>0,'user_type'=>0])->count();
        //dd($userlist);
        if($request->excel == 'excel')
        {
          return Excel::download(new UsersExport, 'suppliers.csv');
        }

        return view('departure.supplier',compact('userlist','keyword','statuss','active_count','toal_count'));
    }
    public function SupplierUserList(Request $request, $id){
        $tenant_id = User::where('id', $id)
                    ->first();
        $userlist = User::where('tenant_id', $tenant_id->tenant_id)
                    ->where('id','!=', $id)
                    ->paginate(15);
        foreach($userlist as $ulist){
            $roles = DB::table('roles')
                ->where('id', $ulist->role_id)
                ->value('name');
            $ulist->role = $roles;
        }
        return view('departure.suppliers_user',compact('userlist','tenant_id'));
    }


    public function UserTypeChange(Request $request, $id)
    {
        $getData = MailHelper::setMailConfig();
        $user = User::where('tenant_id',$id)->get();
        foreach($user as $row){
        //dd($departures);
        if($row->main_user_type == 0){
            $row->main_user_type = 1;
            $row->save();
            $status = 'Buyer';
            Mail::send('mail.user_type', ['user' => $row,'status'=> $status], function ($m) use ($row, $getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to($row->email)->subject('Departure Cloud- Account Status');
                 });
        }
        else{
            $row->main_user_type = 0;
            $row->save();
            $status = 'Supplier';
            Mail::send('mail.user_type', ['user' => $row,'status'=> $status], function ($m) use ($row, $getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to($row->email)->subject('Departure Cloud- Account Status');
                 });
        }
    }
        return response()->json(['success'=>'User updated successfully!']);
    }
    public function UserStatusChange(Request $request, $id)
    {

        $getData = MailHelper::setMailConfig();
        $user  = User::where('tenant_id',$id)->first();


        if($user->verified == 0){
            $status = 'Welcome to Departure Cloud. Your account is active now. You can login your account with the credentials that you chosen during registration.';

            $user->verified = 1;
            $user->status = 1;
            //$user->email_verified_at =  date('Y-m-d H:i:s');
            $user->save();
            Mail::send('mail.welcome', ['user' => $user], function ($m) use ($user, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($user->email)->subject('Departure Cloud- Account Status!');
             });
        }
        else{
            $status = 'Dear Customer your account is inactive now';

            $user->verified = 0;
            $user->status = 0;
            $user->save();
            Mail::send('mail.welcome', ['user' => $user], function ($m) use ($user, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($user->email)->subject('Departure Cloud- Account Status!');
             });

        }
        return response()->json(['success'=>'User updated successfully!']);
    }

    // filter departure

    public function DepartureFrom(Request $request){
        $startDest = [];
        $date = date("Y-m-d");
        if($request->has('q')){
        $search = $request->q;
        $startDest = DB::table('departures')->select("from")
                    ->where('status',1)
                    ->where('approve',1)
                    ->where('from','LIKE',"$search%")
                    ->whereDate('start_date', '>=', $date)
                    ->distinct()
                    ->get(15);

        }else{
        $startDest = DB::table('departures')->select("from")
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->distinct()
                    ->limit(15)
                    ->get();
        }
        return response()->json($startDest);
    }
    public function DepartureTo(Request $request){
        $startDest = [];
        $date = date("Y-m-d");
        if($request->has('q')){
        $search = $request->q;
        $startDest = DB::table('departures')->select("ending_at")
                    ->where('status',1)
                    ->where('approve',1)
                    ->where('ending_at','LIKE',"$search%")
                    ->whereDate('start_date', '>=', $date)
                    ->distinct()
                    ->get(15);

        }else{
        $startDest = DB::table('departures')->select("ending_at")
                     ->where('status',1)
                     ->where('approve',1)
                     ->whereDate('start_date', '>=', $date)
                     ->distinct()
                     ->limit(15)
                     ->get();
        }
        return response()->json($startDest);
    }

   //end filter departure
    public function destinationDelete($id){
        UserDestination::where('id',$id)->delete();
        return redirect()->back();
    }

    public function pointofInterestGet(Request $request){

           // $destid = $request->destination_id;
           $posts = $request->q;
           $post = array(
           'text' => $posts
           );


           $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, env('pullIt_BaseUrl').'api/get_cloud_airports');
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
           //
            curl_close ($curl);
          return Response()->json(json_decode($response));
   }
    public function UserBooking($id){

        $booked = DB::table('book_departures')
            ->where('user_id',$id)
            ->select('unique_id','created_at')
            ->orderBy('created_at','DESC')
            ->distinct()
            ->paginate(20);
        foreach($booked as $value){
           $bookings = DB::table('book_departures')
                      ->where('user_id',$id)
                      ->where('unique_id',$value->unique_id)
                      ->first();
           $value->bookings = $bookings;

           $booked_seat = BookDeparture::where('user_id',$id)
                      ->where('unique_id',$value->unique_id)
                      ->sum('booked_seat');
           $value->booked_seat = $booked_seat;
           $price = BookDeparture::where('user_id',$id)
                      ->where('unique_id',$value->unique_id)
                      ->sum('price');
           $value->price = $price;

           $departure = Departure::where('id',$bookings->departure_id)
                      ->first();
           $value->departure = $departure;
        }

    return view('departure.user_book',compact('booked','id'));
   }
   public function UserHolding($id){

    $hold = DB::table('hold_departure_seats')
             ->where('user_id',$id)
             ->select('unique_id','created_at')
             ->orderBy('created_at','DESC')
             ->distinct()
             ->paginate(20);
      foreach($hold as $value){
          $bookings = DB::table('hold_departure_seats')
                      ->where('user_id',$id)
                      ->where('unique_id',$value->unique_id)
                      ->first();
          $value->bookings = $bookings;

          $booked_seat = HoldDepartureSeat::where('user_id',$id)
                       ->where('unique_id',$value->unique_id)
                       ->sum('hold_seat');
          $value->booked_seat = $booked_seat;
          $price = HoldDepartureSeat::where('user_id',$id)
                      ->where('unique_id',$value->unique_id)
                      ->sum('price');
           $value->price = $price;

           $departure = Departure::where('id',$bookings->departure_id)
                      ->first();
           $value->departure = $departure;
      }
          return view('departure.user_hold',compact('hold','id'));
   }
    public function copyDeparture(Request $request)
    {
        $data = $request->all();
    // for ($i=1; $i <=$request->no_of_clone ; $i++) {

      $startFormat = $request->start_date;
      $start_date = date("y-m-d", strtotime($startFormat));
      $endFormat = $request->end_date;
      $end_date = date("y-m-d", strtotime($endFormat));
      $departure_id = $request->dep_id;
      $user = auth()->user();
      $departure_data = Departure::where('id', $departure_id)->first();
      $last_dep_id = Departure::orderBy('id','desc')->first();


      // $title_copy = Departure::where('title',$request->title)->first();
      // if($title_copy){
      //     $departure = Departure::find($title_copy->id);
      //     $departure->title = ucfirst($request->title);
      //     $departure->start_date = $start_date;
      //     $departure->total_seat = $request->total_seat;
      //     $departure->no_of_days = $departure_data->no_of_days;
      //     $departure->no_of_nights = $departure_data->no_of_nights;
      //     $departure->meal_type = $departure_data->meal_type;
      //     $departure->description = $departure_data->description;
      //     $departure->from = $departure_data->from;
      //     $departure->ending_at = $departure_data->ending_at;
      //     $departure->company_name = $departure_data->company_name;
      //     $departure->dep_type = $departure_data->dep_type;
      //     $departure->price_inr = $departure_data->price_inr;
      //     $departure->price_usd = $departure_data->price_usd;
      //     $departure->departure_ownner = $departure_data->departure_ownner;
      //     $departure->hotel_category = $departure_data->hotel_category;
      //     $departure->hold_duration = $departure_data->hold_duration;
      //     $departure->source = $departure_data->source;
      //     $departure->termspayment = $departure_data->termspayment;
      //     $departure->single_supplyment_price_inr = $departure_data->single_supplyment_price_inr;
      //     $departure->single_supplyment_price_usd = $departure_data->single_supplyment_price_usd;
      //     $departure->tenant_id = $user->tenant_id;
      //     $departure->user_id = $user->id;
      //     $departure->dep_id = ($last_dep_id->dep_id) +1;
      //     $departure->unique_key = Str::random(10).time();
      //     $departure->save();
      //     $last_id = $departure->id;
      // }else{
      $departure = new Departure;
      $departure->title = $request->title;
      $departure->start_date = $start_date;
      $departure->end_date = $end_date;
      $departure->total_seat = $request->total_seat;
      $departure->no_of_days = $departure_data->no_of_days;
      $departure->no_of_nights = $departure_data->no_of_nights;
      $departure->total_room = $departure_data->total_room;
      $departure->meal_type = $departure_data->meal_type;
      $departure->description = $departure_data->description;
      $departure->from = $departure_data->from;
      $departure->ending_at = $departure_data->ending_at;
      $departure->company_name = $departure_data->company_name;
      $departure->dep_type = $departure_data->dep_type;
      $departure->departure_type = $departure_data->departure_type;
      $departure->price_inr = $departure_data->price_inr;
      $departure->price_usd = $departure_data->price_usd;
      $departure->departure_ownner = $departure_data->departure_ownner;
      $departure->hotel_category = $departure_data->hotel_category;
      $departure->hold_duration = $departure_data->hold_duration;
      $departure->source = $departure_data->source;
      $departure->termspayment = $departure_data->termspayment;
      $departure->single_supplyment_price_inr = $departure_data->single_supplyment_price_inr;
      $departure->single_supplyment_price_usd = $departure_data->single_supplyment_price_usd;
      $departure->tenant_id = $user->tenant_id;
      $departure->user_id = $user->id;
      $departure->dep_id = ($last_dep_id->dep_id) +1;
      $departure->unique_key = Str::random(10).time();
      $departure->save();
      $last_id = $departure->id;

      $inclusions = Inclusion::where('departure_id', $departure_id)->get();
      if(count($inclusions)>0){
          Inclusion::where('departure_id', $last_id)->delete();
          foreach ($inclusions as $inclusion) {
              $dep_inclusions= new Inclusion;
              $dep_inclusions->departure_id = $last_id;
              $dep_inclusions->name = $inclusion->name;
              $dep_inclusions->description = $inclusion->description;
              $dep_inclusions->icon = $inclusion->icon;
              $dep_inclusions->dep_type = $inclusion->dep_type;
              $dep_inclusions->user_id = $user->id;
              $dep_inclusions->tenant_id = $user->tenant_id;
              $dep_inclusions->save();
          }
      }

      $itinerary = AgentItinerarie::where('departure_id', $departure_id)
                  ->first();
      if($itinerary){
          AgentItinerarie::where('departure_id', $last_id)->delete();
          $itinerary_save = new AgentItinerarie;
          $itinerary_save->departure_id = $last_id;
          $itinerary_save->title = $itinerary->title;
          $itinerary_save->description = $itinerary->description;
          $itinerary_save->pdf_file = $itinerary->pdf_file;
          $itinerary_save->tenant_id = $user->tenant_id;
          $itinerary_save->user_id = $user->id;
          $itinerary_save->dep_type = $itinerary->dep_type;
          $itinerary_save->unique_key = Str::random(10).time();
          //return $itinerary_save;
          $itinerary_save->save();
      }
      $hd = HoldDeparture::where('departure_id', $departure_id)
                  ->first();
      if($hd){
          HoldDeparture::where('departure_id', $last_id)->delete();
          $hold = new HoldDeparture;
          $hold->user_id =  $user->id;
          $hold->departure_id = $last_id;
          $hold->hold_till = $hd->hold_till;
          $hold->date = date("Y-m-d H:i:s");
          $hold->save();
      }

        $PaymentSchedule = PaymentSchedule::where('departure_id', $departure_id)->get();
        if(count($PaymentSchedule)>0){
            PaymentSchedule::where('departure_id', $last_id)->delete();
            if($start_date == $departure_data->start_date){
              foreach ($PaymentSchedule as $key => $ps) {
                  $Payment_Schedule = new PaymentSchedule;
                  $Payment_Schedule->departure_id = $last_id;
                  $Payment_Schedule->term_conditions = $ps->term_conditions;
                  $Payment_Schedule->date = $ps->date;
                  $Payment_Schedule->price = $ps->price;
                  $Payment_Schedule->percentage = $ps->percentage;
                  $Payment_Schedule->single_supplement = $ps->single_supplement;
                  $Payment_Schedule->total = $ps->total;
                  $Payment_Schedule->user_id = $user->id;
                  $Payment_Schedule->save();
              }
            }else{
              $hds_till = DB::table('hold_departures')->where('departure_id',$departure_id)->select('hold_till')->first();
              $day_arr = [];
              $date_arr = [];
              $date_range = [];
              foreach ($PaymentSchedule as $key => $ps) {
                $lastpaydate = PaymentSchedule::where('departure_id',$last_id)
                    ->orderBy('id','DESC')->first();
                  $Payment_Schedule = new PaymentSchedule;
                  $Payment_Schedule->departure_id = $last_id;
                  $Payment_Schedule->term_conditions = $ps->term_conditions;
                  if($ps->date == '1970-01-01'){
                    $Payment_Schedule->date = $ps->date;
                  }else{
                    $date1=date_create($ps->date);
                    $date2=date_create($start_date);
                    $diff=date_diff($date1,$date2);
                    $date_in_days = $diff->format("%R%a");

                    if($key == 1){
                         array_unshift($date_range,$ps->date);

                        $ltrim = ltrim($date_in_days, '+');
                    }else{
                        $dat1=date_create(end($date_range));
                        $dat2=date_create($ps->date);
                        $diff1=date_diff($dat1,$dat2);
                        $diff_in_days = $diff1->format("%R%a");

                        $ltrim = ltrim($date_in_days, '+')+$diff_in_days;
                        array_unshift($date_range,$lastpaydate->date);
                    }
                    $date_addition = date('Y-m-d', strtotime($ps->date. ' + '.$ltrim .' days'));
                    $Payment_Schedule->date = date('Y-m-d', strtotime($date_addition. ' - '.$hds_till->hold_till .' days'));

                  }

                  // //$Payment_Schedule->date = $ps->date;
                  $Payment_Schedule->price = $ps->price;
                  $Payment_Schedule->percentage = $ps->percentage;
                  $Payment_Schedule->single_supplement = $ps->single_supplement;
                  $Payment_Schedule->total = $ps->total;
                  $Payment_Schedule->user_id = $user->id;
                  $Payment_Schedule->save();

              }
            }
        }

      $Pricing = DeparturePrice::where('departure_id', $departure_id)->distinct()
                  ->get();

      if(count($Pricing)>0){
          DeparturePrice::where('departure_id', $last_id)->delete();
          foreach ($Pricing as $key => $dp) {
              $Pricing = new DeparturePrice;
              $Pricing->departure_id = $last_id;
              $Pricing->sharing = $dp->sharing;
              $Pricing->transport_type = $dp->transport_type;
              $Pricing->meal_type = $dp->meal_type;
              $Pricing->hotel_type = $dp->hotel_type;
              $Pricing->flight_class = $dp->flight_class;
              $Pricing->passenger = $dp->passenger;
              $Pricing->airport_transfers = $dp->airport_transfers;
              $Pricing->other = $dp->other;
              $Pricing->group_size = $dp->group_size;
              $Pricing->price = $dp->price;
              $Pricing->currency_symbol = $dp->currency_symbol;
              $Pricing->currency_code = $dp->currency_code;
              $Pricing->save();
          }
      }

        $CancelSchedule = CancelSchedule::where('departure_id', $departure_id)          ->get();

        if(count($CancelSchedule)>0){
            CancelSchedule::where('departure_id', $last_id)->delete();
            if($start_date == $departure_data->start_date){
              foreach ($CancelSchedule as $key => $cs) {
                $cancel_Schedule = new CancelSchedule;
                $cancel_Schedule->departure_id = $last_id;
                $cancel_Schedule->date = $cs->date;
                $cancel_Schedule->percentage = $cs->percentage;
                $cancel_Schedule->total = $cs->total;
                $cancel_Schedule->tenant_id = $cs->tenant_id;
                $cancel_Schedule->save();
              }
            }else{

                $hd_till = DB::table('hold_departures')->where('departure_id',$departure_id)->select('hold_till')->first();
                $date_range = [];
              foreach ($CancelSchedule as $key => $cs) {
                $lastpaydate = CancelSchedule::where('departure_id',$last_id)
                    ->orderBy('id','DESC')->first();
                $cancel_Schedule = new CancelSchedule;
                $cancel_Schedule->departure_id = $last_id;
                if($cs->date == '1970-01-01'){
                  $cancel_Schedule->date = $cs->date;
                }else{
                    $date1=date_create($cs->date);
                    $date2=date_create($start_date);
                    $diff=date_diff($date1,$date2);
                    $date_in_days = $diff->format("%R%a");
                    if($key == 1){
                        array_unshift($date_range,$cs->date);
                        $ltrim = ltrim($date_in_days, '+');
                    }else{
                        $dat1=date_create(end($date_range));
                        $dat2=date_create($cs->date);
                        $diff1=date_diff($dat1,$dat2);
                        $diff_in_days = $diff1->format("%R%a");

                        $ltrim = ltrim($date_in_days, '+')+$diff_in_days;
                        array_unshift($date_range,$lastpaydate->date);;
                    }
                    $date_addition = date('Y-m-d', strtotime($cs->date. ' + '.$ltrim .' days'));

                    $cancel_Schedule->date = date('Y-m-d', strtotime($date_addition. ' - '.$hd_till->hold_till .' days'));
                }

                $cancel_Schedule->percentage = $cs->percentage;
                $cancel_Schedule->total = $cs->total;
                $cancel_Schedule->tenant_id = $cs->tenant_id;
                $cancel_Schedule->save();
              }
            }
        }

      $origin_flights = DepartureFlightDetail::where('departure_id', $departure_id)
                  ->distinct()
                  ->get();

      if(count($origin_flights)>0){
          DepartureFlightDetail::where('departure_id', $last_id)->delete();
          foreach ($origin_flights as $key => $oflight) {
              $originFs = new DepartureFlightDetail;
              $originFs->departure_id = $last_id;
              $originFs->flight_name = $oflight->flight_name;
              $originFs->code = $oflight->code;
              $originFs->flight_no = $oflight->flight_no;
              $originFs->flight_date = $oflight->flight_date;
              $originFs->flight_dep_time = $oflight->flight_dep_time;
              $originFs->flight_arrival_time = $oflight->flight_arrival_time;
              $originFs->flight_arrival_date = $oflight->flight_arrival_date;
              $originFs->flight_dep_airport = $oflight->flight_dep_airport;
              $originFs->flight_arrival_airport = $oflight->flight_arrival_airport;
              $originFs->baggage = $oflight->baggage;
              $originFs->save();
          }
      }

      $returning_flights = ReturnFlightDetail::where('departure_id', $departure_id)
                  ->distinct()
                  ->get();

      if(count($returning_flights)>0){
          ReturnFlightDetail::where('departure_id', $last_id)->delete();
          foreach ($returning_flights as $key => $oflight) {
              $returningFs = new ReturnFlightDetail;
              $returningFs->departure_id = $last_id;
              $returningFs->flight_name = $oflight->flight_name;
              $returningFs->code = $oflight->code;
              $returningFs->flight_no = $oflight->flight_no;
              $returningFs->flight_date = $oflight->flight_date;
              $returningFs->flight_dep_time = $oflight->flight_dep_time;
              $returningFs->flight_arrival_time = $oflight->flight_arrival_time;
              $returningFs->flight_arrival_date = $oflight->flight_arrival_date;
              $returningFs->flight_dep_airport = $oflight->flight_dep_airport;
              $returningFs->flight_arrival_airport = $oflight->flight_arrival_airport;
              $returningFs->baggage_arriving = $oflight->baggage_arriving;
              $returningFs->save();
          }
      }

      $countries_departure = CountryDeparture::where('departure_id', $departure_id)->get();
      if(count($countries_departure)>0){
          CountryDeparture::where('departure_id', $last_id)->delete();
          foreach ($countries_departure as $key => $country_id) {
              $country_dep = new CountryDeparture;
              $country_dep->country_id = $country_id->country_id;
              $country_dep->departure_id = $last_id;
              $country_dep->save();
          }
      }
      $destination_departure = DepartureDestination::where('departure_id', $departure_id)->get();
      if(count($destination_departure)>0){
          DepartureDestination::where('departure_id', $last_id)->delete();
          foreach ($destination_departure as $key => $destination_id) {
              $country_dep = new DepartureDestination;
              $country_dep->destination_id = $destination_id->destination_id;
              $country_dep->departure_id = $last_id;
              $country_dep->save();
          }
        }
        $hotels = DepartureHotel::where('departure_id', $departure_id)->get();

        if(count($hotels)>0){
            DepartureHotel::where('departure_id', $last_id)->delete();
            foreach ($hotels as $key => $hotel) {
              $Hotels = new DepartureHotel;
              $Hotels->departure_id = $last_id;
              $Hotels->name = $hotel->name;
              $Hotels->destination_id = $hotel->destination_id;
              $Hotels->hotel_category = $hotel->hotel_category;
              $Hotels->total_room = $hotel->total_room;
              $Hotels->user_id = $hotel->user_id;
              $Hotels->tenant_id = $hotel->tenant_id;
              $Hotels->save();
            }
        }
        //Day Schedule
        $daySchedules = Itinerary::where('departure_id', $departure_id)->get();
        if(count($daySchedules)>0){
            Itinerary::where('departure_id', $last_id)->delete();
            foreach ($daySchedules as $key => $daySchedule) {
                $dayItinerary = new Itinerary;
                $dayItinerary->departure_id = $last_id;
                $dayItinerary->day_number = $daySchedule->day_number;
                $dayItinerary->day_heading = $daySchedule->day_heading;
                $dayItinerary->destinations = $daySchedule->destinations;
                $dayItinerary->description = $daySchedule->description;
                $dayItinerary->user_id = $daySchedule->user_id;
                $dayItinerary->tenant_id = $daySchedule->tenant_id;
                $dayItinerary->dep_type = $daySchedule->dep_type;
                $dayItinerary->unique_key = Str::random(10).time();
                $dayItinerary->save();
            }
        }
    // }
      return response()->json(['status'=>$last_id],200);
  }
  // Tag search if exist
  public function tagsListSearch(Request $request){
    $tags = [];
    if($request->has('q')){
        $search = $request->q;
        $tags = DB::table('departure_tags')
            ->where('name','LIKE',"%$search%")
            ->select('name')
            ->distinct()
            ->limit(15)
            ->get();
        // $arrs = [];
        // foreach ($tg as $key => $tgs) {
        //     $arr = explode('@@', $tgs->tags);
        //     $str = implode(',', $arr);
        //     array_push($arrs, $str);
        // }
        // $arr_unique = array_unique($arrs);
        // $str_tags = implode(',', $arr_unique);
        // $array_tags = explode(',', $str_tags);
        // $arr_unique = array_unique($array_tags);
        // $tags = $arr_unique;

    }else{
        // $tg = DB::table('departures')->where('tags','!=', null)
        //     ->distinct()->select('tags')->limit(15)->get()->toArray();
        // $arrs = [];
        // foreach ($tg as $key => $tgs) {
        //     $arr = explode('@@', $tgs->tags);
        //     $str = implode(',', $arr);
        //     array_push($arrs, $str);
        // }
        // $str_tags = implode(',', $arrs);
        // $array_tags = explode(',', $str_tags);
        // $arr_unique = array_unique($array_tags);
        // $tags = $arr_unique;
        $tags = DB::table('departure_tags')
            ->select('name')
            ->distinct()
            ->limit(20)
            ->get();
    }
    return response()->json($tags);
    }

    public function getColumns(Request $request){
        $id = $request->id;
        $columns = DepartureColumnType::where('departure_type_id', $id)->get();
        return response()->json($columns, 200);
    }


    public function country_slug(Request $request){
       $data = Country::get();
       foreach ($data as $key => $value) {
            $cname = explode(' ', $value->country_name);
            $str = implode('-', $cname);
            $country = strtolower($str);
            $cUrls = Country::find($value->id);
            $cUrls->country_slug = $country.'-group-tours';
            $cUrls->save();
       }
    }
}
