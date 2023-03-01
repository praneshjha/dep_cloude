<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\User;
use App\Departure;
use App\Price;
use App\HoldDuration;
use App\HoldDepartureSeat;
use App\BookDeparture;
use App\DeparturePrice;
use App\Inclusion;
use App\DepartureColumnType;
use Mail;
use App\Notification;
use App\Helpers\MailHelper;


class DashboardController extends Controller
{
    public function get_booking_modal(Request $request)
    {
        $departure = $request->all();
        $columns = DepartureColumnType::where('departure_type_id', $departure['departure_type'])->get()->pluck('column_name_id');
        $columns = json_encode($columns);
        return view('dashboard.booking_modal', compact('departure', 'columns'));
    }
    public function get_hold_modal(Request $request)
    {
        $departure = $request->all();
        $columns = DepartureColumnType::where('departure_type_id', $departure['departure_type'])->get()->pluck('column_name_id');
        $columns = json_encode($columns);
        return view('dashboard.hold_modal', compact('departure', 'columns'));
    }

    //
    public function searchResults(Request $request)
    {
        $getData = MailHelper::setMailConfig();

        //constant
        $date = date("Y-m-d");
        $dates = 0;
        $packageType = 0;
        $total_page = 0;
        $user = auth()->user()->id;
        $login_time = auth()->user()->last_login_at;

        //variable
        $search_key = $request->keyword;
        $req_type = $request->type;
        $status_filter = $request->status;
        
        if ($request->keyword != "") {
            if ($request->type != "") {

                if ($request->type == 11) {
                    $destination_id = DB::table('destinations')->where('dest_name', 'LIKE', '%' . $search_key . '%')->value('id');
                    $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                    $departures11t = Departure::whereIn('id', $departure_id)
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();

                    if ($request->keyword) {
                        $departures11 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    } else {
                        $departures11 = [];
                    }

                    $dep_all = array_merge($departures11t, $departures11);
                    $departure_unique = array_unique($dep_all);

                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                }

                if ($request->type == 12) {
                    $country_id = DB::table('countries')->where('country_name', 'LIKE', '%' . $search_key . '%')->value('id');
                    $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                    $departures12t = Departure::whereIn('id', $departure_id)
                    ->where('status', 1) ->where('approve', 1)
                    ->whereDate('start_date', '>=', $date)
                    ->pluck('id')->toArray(); if ($request->keyword) {
                    $departures12 = Departure::where('status', 1)
                    ->where('approve', 1) ->whereDate('start_date', '>=',
                    date("Y-m-d")) ->where(function ($query) use ($request) {
                    if ($request->keyword != '') { $query->where('title',
                    'LIKE', '%' . $request->keyword . '%') ->where('status',
                    1) ->where('approve', 1) ->whereDate('start_date', '>=',
                    date("Y-m-d")) ->orWhere('company_name', 'LIKE', '%' .
                    $request->keyword . '%') ->orWhere('dep_id', 'LIKE', '%' .
                    $request->keyword . '%') ->orWhere('from', 'LIKE', '%' .
                    $request->keyword . '%') ->orWhere('ending_at', 'LIKE',
                    '%' . $request->keyword . '%') ->orWhere('tags', 'LIKE',
                    '%' . $request->keyword . '%'); } })
                    ->pluck('id')->toArray(); } $departures12 = []; $dep_all =
                    array_merge($departures12t, $departures12);
                    $departure_unique = array_unique($dep_all);

                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                }

                if ($request->type == 13) {
                    if ($request->destination != "") {
                        $departures13t = Departure::where('company_name', 'LIKE', '%' . $search_key . '%')
                            ->where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if ($request->keyword) {
                            $departures13 = Departure::where('status', 1)
                                ->where('approve', 1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use ($request) {
                                    if ($request->keyword != '') {
                                        $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                            ->where('status', 1)
                                            ->where('approve', 1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                    }
                                })
                                ->pluck('id')->toArray();
                        }
                        $departures13 = [];
                        $dep_all = array_merge($departures13t, $departures13);
                        $departure_unique = array_unique($dep_all);

                        $total_departure = Departure::whereIn('id', $departure_unique)
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page = $total_departure / 45;
                        $total_page = (int)ceil($total_page);

                        $departures = Departure::whereIn('id', $departure_unique)
                            ->where('approve', 1)
                            ->where('status', 1)
                            ->where('from', $request->destination)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(99);
                    } else {
                        $departures13t = Departure::where('company_name', 'LIKE', '%' . $search_key . '%')
                            ->where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if ($request->keyword) {
                            $departures13 = Departure::where('status', 1)
                                ->where('approve', 1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use ($request) {
                                    if ($request->keyword != '') {
                                        $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                            ->where('status', 1)
                                            ->where('approve', 1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                    }
                                })
                                ->pluck('id')->toArray();
                        }
                        $departures13 = [];
                        $dep_all = array_merge($departures13t, $departures13);
                        $departure_unique = array_unique($dep_all);

                        $total_departure = Departure::whereIn('id', $departure_unique)
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page = $total_departure / 45;
                        $total_page = (int)ceil($total_page);

                        $departures = Departure::whereIn('id', $departure_unique)
                            ->where('approve', 1)
                            ->where('status', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(99);
                    }
                }

                if ($request->type == 14) {
                    $departure_id = DB::table('departure_tags')->where('name', 'LIKE', '%' . $search_key . '%')->pluck('departure_id');

                    $departures14t = Departure::whereIn('id', $departure_id)
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    if ($request->keyword) {
                        $departures14 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->where('status', 1)
                                        ->where('approve', 1)
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    }
                    $departures14 = [];
                    $dep_all = array_merge($departures14t, $departures14);
                    $departure_unique = array_unique($dep_all);

                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                }

                if ($request->type == 15) {
                    $departures15t = Departure::where('title', 'LIKE', '%' . $search_key . '%')
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    if ($request->keyword) {
                        $departures15 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->where('status', 1)
                                        ->where('approve', 1)
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    }
                    $departures15 = [];
                    $dep_all = array_merge($departures15t, $departures15);
                    $departure_unique = array_unique($dep_all);
                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                }
                $return = 0;
            }
            else{
                $departures = [];
                $return = 1;
                // return redirect('/dashboard');
            }
        } else {
            if($request->type != ""){
                $departures = [];
                $return = 1;
                // return redirect('/dashboard');
            }
            else{
                $departures = [];
                $return = 1;
                // return redirect()->route('dashboard');
            }
        }
       
        $columns = "";
        $departure_types = new \stdClass();
        if (count($departures) > 0) {
            foreach ($departures as $key => $value) {
                $userId = User::where(['tenant_id' => $value->tenant_id, 'main_user_type' => 1, 'user_type' => 0])->select('id')->first();
                $value->profileUserId = $userId->id;
                $hold = HoldDepartureSeat::where('departure_id', $value->id)
                    ->sum('hold_seat');
                if ($hold) {
                    $value->hold_sum = $hold;
                } else {
                    $value->hold_sum = 0;
                }

                //return $value->id;
                $book = BookDeparture::where('departure_id', $value->id)
                    ->where('status', 1)->sum('booked_seat');
                $single_book = BookDeparture::where('departure_id', $value->id)
                    ->sum('single_supplement_booked_seat');
                if ($book) {
                    $value->book_sum = $book;
                } else {
                    $value->book_sum = 0;
                }
                if ($single_book) {
                    $value->single_book_sum = $single_book;
                } else {
                    $value->single_book_sum = 0;
                }
               
                //user logo
                $logo_u = DB::table('users')->where('tenant_id', $value->tenant_id)->value('logo');
                $value->logo_image = url('companyLogo') . '/' . $logo_u;

                // Last Updated Departure
                $last_updated = DB::table('departures')->where('id', $value->id)->value('updated_at');
                $last_updated1 = DB::table('inclusions')->where('departure_id', $value->id)->value('updated_at');
                $last_updated2 = DB::table('return_flight_details')->where('departure_id', $value->id)->value('updated_at');
                $last_updated3 = DB::table('departure_flight_details')->where('departure_id', $value->id)->value('updated_at');
                $last_updated4 = DB::table('agent_itineraries')->where('departure_id', $value->id)->value('updated_at');
                $last_updated5 = DB::table('departure_prices')->where('departure_id', $value->id)->value('updated_at');
                $last_updated6 = DB::table('payment_schedules')->where('departure_id', $value->id)->value('updated_at');
                $last_updated7 = DB::table('cancel_schedules')->where('departure_id', $value->id)->value('updated_at');

                $last_max = max($last_updated, $last_updated1, $last_updated2, $last_updated3, $last_updated4, $last_updated5, $last_updated6, $last_updated7);
                $value->last_updated_dep = $last_max;

                // Inclusion icons
                $inclu_icons = Inclusion::where('departure_id', $value->id)->where('icon', '!=', null)->select('icon', 'name')->get();
                foreach ($inclu_icons as $key => $inclu_icon) {
                    $inclu_icon->icon = url('inclusion-images') . '/' . $inclu_icon->icon;
                }
                $value->inclusion_icons = $inclu_icons;

                $columns = DepartureColumnType::where('departure_type_id', $value->departure_type)->get()->pluck('column_name_id');
                $columns = json_encode($columns);
                $departure_destination = DB::table('departure_destinations')
                    ->join('destinations', 'departure_destinations.destination_id', 'destinations.id')
                    ->select('destinations.country_name', 'destinations.dest_name')
                    ->where('departure_destinations.departure_id', $value->id)
                    ->get();
                $users = User::where(['tenant_id' => $value->tenant_id, 'main_user_type' => 1, 'user_type' => 0])->first();
                $value->destination = $departure_destination;
                $other_price = DB::table('prices')->where('departure_id', $value->id)->where('price_type_id', 3)->get();
                $single_price = Price::where('departure_id', $value->id)->where('price_type_id', 4)->get();
                $value->singlePrice = $single_price;
                $value->OtherPrice = $other_price;

                $prices = DeparturePrice::where('departure_id', $value->id)->take(1)->get();
                $value->price = $prices;
                $departure_prices = DeparturePrice::where('departure_id', $value->id)->where('status', 1)->get();
                $value->departure_price = $departure_prices;

                $value->company_url = strtolower($users->company_id);
                //$bd->company_name = $user_data->company_name;

                $favSupplier = DB::table('favourite_supplier')
                    ->where('user_id', $user)
                    ->where('tenant_id', $value->tenant_id)
                    ->distinct()->first();
                if ($favSupplier) {
                    $value->fav_sapplier = 1;
                } else {
                    $value->fav_sapplier = 0;
                }

                $favPkg = DB::table('favourite_package')
                    ->join('departures', 'favourite_package.dep_id', '=', 'departures.id')
                    ->where('favourite_package.user_id', $user)
                    ->select('departures.id', 'departures.title', 'departures.start_date', 'departures.total_seat', 'departures.available_seat')
                    ->get();
                $favPkg_check = DB::table('favourite_package')
                    ->where('user_id', $user)
                    ->where('dep_id', $value->id)
                    ->first();
                if ($favPkg_check) {
                    $value->fav_pkg = 1;
                } else {
                    $value->fav_pkg = 0;
                }
            }
        }

        $release = HoldDepartureSeat::all();
        //$now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
        foreach ($release as $row) {
            //$add = $row->hold_duration + 5 ;
            $now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
            if ($now >= $row->auto_release) {
                $hold = $row->hold_seat;
                $departure = $row->departure_id;
                $dep = Departure::find($departure);
                $available = $dep->available_seat;

                //Notification
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud - Hold Units Released';
                $noti_save->body = $hold . ' units held by you in departure for' . $dep->ending_at . ' as per policy defined by supplier.';

                $noti_save->body_html = '<p><b> ' . $hold . ' </b>units held by you in departure for<b> ' . $dep->ending_at . ' </b>as per policy defined by supplier.</p>';
                $noti_save->user_id = $row->user_id;
                $noti_save->type = "AutoRelease";
                $noti_save->url_1 = url('login');
                $noti_save->save();

                $last_body_sup = $noti_save->body;
                $last_title_sup = $noti_save->title;
                $last_link_sup = $noti_save->url_1;
                $users = User::where('id', $row->user_id)->first();

                $firebaseToken = User::where('id', $row->user_id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup, $noti_save->id);

                $setting = DB::table('smtp_integrations')->where('user_id',auth()->user()->id)->first();
                
               Mail::send('mail.release_hold', ['hold' => $hold, 'destination' => $dep->ending_at, 'user' => $users, 'forceRelease' => 'no'], function ($m) use ($users, $getData) {
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

        $active_departureCount = Departure::where('dep_type', 'main')
            //->whereDate('start_date', '>=', $date)
            ->where('approve', 1)
            ->where('status', 1)
            ->get();

        $active = count($active_departureCount);

        $pending_departureCount = Departure::where('dep_type', 'main')
            ->whereDate('start_date', '>=', $date)
            ->where('approve', 0)
            ->where('status', 1)
            ->get();

        $pending = count($pending_departureCount);

        $inactive_departureCount = Departure::where('dep_type', 'main')
            ->whereDate('start_date', '<=', $date)
            ->where('approve', 1)
            ->where('status', 1)
            ->get();

        $inactive = count($inactive_departureCount);

        $favSup = DB::table('favourite_supplier')
            ->join('users', 'favourite_supplier.tenant_id', '=', 'users.tenant_id')
            ->select('users.company_name', 'users.tenant_id')
            ->where('user_id', $user)
            ->distinct()
            ->get();
        foreach($favSup as $key=>$value){
            $total_departure = DB::table('departures')
              ->where('tenant_id', '=', $value->tenant_id)
              ->where('status', '=', 1)
              ->whereDate('start_date', '>=', $date)
              ->count();
              $value->total_departure = $total_departure;
              $details = DB::table('departures')
              ->where('tenant_id','=',$value->tenant_id)
              ->where('status','=',1)
              ->whereDate('start_date','>=',$date)
              ->select('from')
              ->distinct()->get();
              $value->details = $details;
              $new_dep = DB::table('departures')
                ->where('tenant_id', '=', $value->tenant_id)
                ->where('status', '=', 1)
                ->whereDate('start_date', '>=', $date)
                ->where('created_at','>=',$login_time)
                ->count();
                $value->new_dep = $new_dep;
                $logo = DB::table('users')->where('tenant_id', $value->tenant_id)->select('logo')->first();
                $value->logo = $logo->logo;
        }

        $fav_pkg = DB::table('favourite_package')
            ->join('departures', 'favourite_package.dep_id', '=', 'departures.id')
            ->where('favourite_package.user_id', $user)
            ->whereDate('start_date', '>=', $date)
            ->select('departures.id', 'departures.title', 'departures.start_date', 'departures.total_seat', 'departures.available_seat')
            ->get();

        $favSupCount = $favSup->count();
        $total_pkg = $fav_pkg->count();
        $total_counted = $favSupCount + $total_pkg;
        

        if ($favSupCount <= 0) {
            $fav_sup_select = $favSup->take(0);
            $fav_pkg_select = $fav_pkg->take(5);
        }
        elseif ($favSupCount == 1) {
            $fav_sup_select = $favSup->take(1);
            $fav_pkg_select = $fav_pkg->take(4);
        }
        elseif ($favSupCount == 2) {
            $fav_sup_select = $favSup->take(2);
            $fav_pkg_select = $fav_pkg->take(3);
        }
        elseif ($favSupCount >= 3) {
            if($total_pkg >= 2)
            {
                $fav_sup_select = $favSup->take(3);
                $fav_pkg_select = $fav_pkg->take(2);
            }
            elseif($total_pkg >= 1)
            {
                $fav_sup_select = $favSup->take(4);
                $fav_pkg_select = $fav_pkg->take(1);
            }
            else
            {
                $fav_sup_select = $favSup->take(5);
                $fav_pkg_select = $fav_pkg->take(0);
            }   
        }
        else{
            $fav_sup_select = $favSup->take(0);
            $fav_pkg_select = $fav_pkg->take(0);
        }

        foreach ($departures as $departure) {
            if ((($departure->total_seat) - ($departure->hold_sum + $departure->book_sum)) > 0) {
                $departure->dep_status = 0;
            } else {
                $departure->dep_status = 1;
            }
        }

 $destinationFind =  DB::table('departure_destinations')
                    ->select('departure_destinations.*', 'departures.start_date', 'destinations.dest_name')
                    ->join('departures', 'departures.id', '=', 'departure_destinations.departure_id')
                    ->join('destinations', 'destinations.id', '=', 'departure_destinations.destination_id')
                    ->whereDate('departures.start_date', '>', $date)
                    ->select('destinations.dest_name')
                    ->distinct()
                    ->get()
                    ->take(10);

                    // dd($destinationFind);


        
        return view('dashboard.dashboard', compact('destinationFind', 'user','login_time','date','departures', 'dates', 'packageType', 'total', 'columns', 'total_page', 'favSup', 'fav_pkg', 'favSupCount', 'total_pkg', 'total_counted', 'fav_sup_select', 'fav_pkg_select'));
    }

    function fetchData(Request $request){
        if ($request->get('query')) {
            $query = $request->get('query');
            $output = "";

            // destination
            $dest_data = DB::table('destinations')
                ->where('dest_name', 'LIKE', "%{$query}%")
                ->orWhere('region', 'LIKE', "%{$query}%")
                ->orWhere('actualname', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();

            if (count($dest_data) > 0) {
                $output .= '<ul class="dropdown-menu"><p class="m-0">Destination(s)</p>';
                $cnt = 0;
                foreach ($dest_data as $row) {
                    $route = url("dashboard") . '?type=11&keyword=' . $row->dest_name;

                    $dep_list_cls = "";
                    $dep_read_more_text = "";
                    if ($cnt > 10) {
                        $dep_list_cls = "dest_hide";
                        $dep_read_more = true;
                    }
                    $output .= '<li class="list-item ' . $dep_list_cls . '"><a href="' . $route . '">' . $row->dest_name . ' (<span><strong>' . $row->region . '</strong></span>)</a></li>';
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

            if (count($country_data) > 0) {
                $output .= '<ul class="dropdown-menu"><p class="m-0">Countries</p>';
                $cnt = 0;
                foreach ($country_data as $row) {
                    $route = url("dashboard") . '?type=12&keyword=' . $row->country_name;
                    $dep_list_cls = "";
                    $dep_read_more_text = "";
                    if ($cnt > 5) {
                        $dep_list_cls = "country_hide";
                        $dep_read_more = true;
                    }
                    $output .= '<li class="list-item ' . $dep_list_cls . '"><a href="' . $route . '">' . $row->country_name . ' (<span><strong>' . $row->continent . '</strong></span>)</a></li>';
                    $cnt++;
                }
                $output .= '</ul>';
            }

            //publisher search
            $pub_data = DB::table('departures')
                ->where('company_name', 'LIKE', "%{$query}%")
                ->select('company_name')
                ->groupBy('company_name')
                ->limit(5)
                ->get();

            $dep_read_more = false;
            if (count($pub_data) > 0) {
                $output .= '<ul class="dropdown-menu"><p class="m-0">Supplier(s)</p>';
                $cnt = 0;
                foreach ($pub_data as $row) {
                    $route = url("dashboard") . '?type=13&keyword=' . $row->company_name;
                    $dep_list_cls = "";
                    $dep_read_more_text = "";
                    if ($cnt > 10) {
                        $dep_list_cls = "pub_hide";
                        $dep_read_more = true;
                    }
                    if ($cnt == 4) {
                        // $dep_read_more_text="<p><a href='javascript:void(0)'>More</a></p>";
                    }
                    $output .= '<li class="list-item ' . $dep_list_cls . '"><a href="' . $route . '">' . $row->company_name . '</a></li>';
                    $cnt++;
                }
                $output .= '</ul>';
            }

            //# tag search
            $depTag_data = DB::table('departure_tags')
                ->where('name', 'LIKE', "%{$query}%")
                ->select('name')
                ->groupBy('name')
                ->limit(5)
                ->get();
            if (count($depTag_data) > 0) {
                $output .= '<ul class="dropdown-menu"><p class="m-0">Tag(s)</p>';
                $cnt = 0;
                foreach ($depTag_data as $row) {
                    $route = url("dashboard") . '?type=14&keyword=' . $row->name;
                    $dep_list_cls = "";
                    $dep_read_more_text = "";
                    if ($cnt > 10) {
                        $dep_list_cls = "depTag_hide";
                        $dep_read_more = true;
                    }
                    $output .= '<li class="list-item ' . $dep_list_cls . '"><a href="' . $route . '">' . $row->name . '</a></li>';
                    $cnt++;
                }
                $output .= '</ul>';
            }

            //departure search
            $data = DB::table('departures')->where('approve', 1)->whereDate('start_date', '>=', date("Y-m-d"))->where('title', 'LIKE', "%{$query}%")->orWhere('company_name', 'LIKE', "%{$query}%")->limit(5)->get();
            $dep_read_more = false;
            if (count($data) > 0) {
                $output .= '<ul class="dropdown-menu"><p class="m-0">Departure(s)</p>';
                $cnt = 0;
                foreach ($data as $row) {
                    $route = url("dashboard") . '?type=15&keyword=' . $row->title;
                    $dep_list_cls = "";
                    $dep_read_more_text = "";
                    if ($cnt > 10) {
                        $dep_list_cls = "dep_hide";
                        $dep_read_more = true;
                    }
                    if ($cnt == 4) {
                        // $dep_read_more_text="<p><a href='javascript:void(0)'>More</a></p>";
                    }
                    $output .= '<li class="list-item ' . $dep_list_cls . '"><a href="' . $route . '">' . $row->title . '<p><strong>' . $row->company_name . '</strong><span>' . date('d M, Y', strtotime($row->start_date)) . '</span></p></a></li>';
                    $cnt++;
                }
                $output .= '</ul>';
            }

            if (count($dest_data) == 0 && count($country_data) == 0 && count($pub_data) == 0 && count($depTag_data) == 0 && count($data) == 0) {
                echo "Data Not Found";
            }
            echo $output;
        }
    }

    public function packageTypeData(Request $request)
    {
        $getData = MailHelper::setMailConfig();

        //constant
        $date = date("Y-m-d");
        $total_page = 0;
        $user = auth()->user()->id;
        $login_time = auth()->user()->last_login_at;
        
        //variable
        $search_key = $request->keyword;
        $req_type = $request->type;
        $dates = $request->dates;
        $packageType = $request->packageType;
        $filter = $request->filter;
        $status_filter = $request->status;
        if ($dates != '') {
            $diff = Date('Y-m-d', strtotime('+' . $dates . ' days'));
        } else {
            $dates = 0;
        }

        
        if ($request->keyword != "") {
            if ($request->type != "") {

                if ($request->type == 11) {
                    $destination_id = DB::table('destinations')->where('dest_name', 'LIKE', '%' . $search_key . '%')->value('id');
                    $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                    $departures11t = Departure::whereIn('id', $departure_id)
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();

                    if ($request->keyword) {
                        $departures11 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    } else {
                        $departures11 = [];
                    }

                    $dep_all = array_merge($departures11t, $departures11);
                    $departure_unique = array_unique($dep_all);

                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);
                    
                    if($request->dates != ''|| $request->packageType != ''|| $request->filter != ''){
                        $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->where(function ($query) use ($request,$date,$diff){
                            if($request->dates != ''){
                                if($request->dates == 0){
                                    $query->whereDate('start_date', '>=', date("Y-m-d"));
                                }
                                if($request->dates == 7 || $request->dates == 15 || $request->dates == 30 ){
                                    $query->whereBetween('start_date', [$date, $diff]);
                                }
                                if($request->dates == 31){
                                    $query->whereDate('start_date', '>=', $diff);
                                }
                            }
                            // dd($date);
                            if($request->packageType != ''){
                                if($request->packageType == 1 ||$request->packageType == 2 ||$request->packageType == 3 ||$request->packageType == 4 ||$request->packageType == 5){
                                    $query->where('departure_type', $request->packageType); 
                                }
                            }
                            if($request->filter != ''){
                                if($request->filter == 1){
                                    $best = DB::table('book_departures')
                                    ->select(DB::raw('count(*) as count, tenant_id'))
                                    ->GroupBY('tenant_id')
                                    ->orderBy('count', 'desc')
                                    ->first();
                                    $best_seller = $best->tenant_id;
                                    $query->where('tenant_id', $best_seller);
                                }
                                if($request->filter == 2){
                                    $datesUpdate = Date('Y-m-d H:i:s', strtotime('-120 days'));
                                    $newest = DB::table('users')
                                    ->join('departures', 'users.tenant_id', '=', 'departures.tenant_id')
                                    ->where('users.verified', 1)
                                    ->where('departures.approve', 1)
                                    ->where('users.created_at', '>', $datesUpdate)
                                    ->select('users.tenant_id','users.created_at')
                                    ->orderBy('users.created_at','asc')
                                    ->first();
                                    // dd($newest->tenant_id);
                                    if($newest){
                                        $best_seller = $newest->tenant_id;
                                    }else{
                                        $best_seller = "";
                                    }
                                    $query->where('tenant_id', $best_seller);
                                }
                            }
                        })
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                    }
                }

                if ($request->type == 12) {
                    $country_id = DB::table('countries')->where('country_name', 'LIKE', '%' . $search_key . '%')->value('id');
                    $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                    $departures12t = Departure::whereIn('id', $departure_id)
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    if ($request->keyword) {
                        $departures12 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->where('status', 1)
                                        ->where('approve', 1)
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    }
                    $departures12 = [];
                    $dep_all = array_merge($departures12t, $departures12);
                    $departure_unique = array_unique($dep_all);

                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    if($request->dates != '' || $request->packageType != '' || $request->filter != ''){
                        $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->where(function ($query) use ($request,$date,$diff){
                            if($request->dates != ''){
                                if($request->dates == 0){
                                    $query->whereDate('start_date', '>=', date("Y-m-d"));
                                }
                                if($request->dates == 7 || $request->dates == 15 || $request->dates == 30 ){
                                    $query->whereBetween('start_date', [$date, $diff]);
                                }
                                if($request->dates == 31){
                                    $query->whereDate('start_date', '>=', $diff);
                                }
                            }
                            // dd($date);
                            if($request->packageType != ''){
                                if($request->packageType == 1 ||$request->packageType == 2 ||$request->packageType == 3 ||$request->packageType == 4 ||$request->packageType == 5){
                                    $query->where('departure_type', $request->packageType); 
                                }
                            }
                            if($request->filter != ''){
                                if($request->filter == 1){
                                    $best = DB::table('book_departures')
                                    ->select(DB::raw('count(*) as count, tenant_id'))
                                    ->GroupBY('tenant_id')
                                    ->orderBy('count', 'desc')
                                    ->first();
                                    $best_seller = $best->tenant_id;
                                    $query->where('tenant_id', $best_seller);
                                }
                                if($request->filter == 2){
                                    $datesUpdate = Date('Y-m-d H:i:s', strtotime('-120 days'));
                                    $newest = DB::table('users')
                                    ->join('departures', 'users.tenant_id', '=', 'departures.tenant_id')
                                    ->where('users.verified', 1)
                                    ->where('departures.approve', 1)
                                    ->where('users.created_at', '>', $datesUpdate)
                                    ->select('users.tenant_id','users.created_at')
                                    ->orderBy('users.created_at','asc')
                                    ->first();
                                    // dd($newest->tenant_id);

                                    if($newest){
                                        $best_seller = $newest->tenant_id;
                                    }else{
                                        $best_seller = "";
                                    }                                    $query->where('tenant_id', $best_seller);
                                }
                            }
                        })
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                    }
                }

                if ($request->type == 13) {
                    if ($request->destination != "") {
                        $departures13t = Departure::where('company_name', 'LIKE', '%' . $search_key . '%')
                            ->where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if ($request->keyword) {
                            $departures13 = Departure::where('status', 1)
                                ->where('approve', 1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use ($request) {
                                    if ($request->keyword != '') {
                                        $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                            ->where('status', 1)
                                            ->where('approve', 1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                    }
                                })
                                ->pluck('id')->toArray();
                        }
                        $departures13 = [];
                        $dep_all = array_merge($departures13t, $departures13);
                        $departure_unique = array_unique($dep_all);

                        $total_departure = Departure::whereIn('id', $departure_unique)
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page = $total_departure / 45;
                        $total_page = (int)ceil($total_page);

                        $departures = Departure::whereIn('id', $departure_unique)
                            ->where('approve', 1)
                            ->where('status', 1)
                            ->where('from', $request->destination)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(99);
                    } else {
                        $departures13t = Departure::where('company_name', 'LIKE', '%' . $search_key . '%')
                            ->where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if ($request->keyword) {
                            $departures13 = Departure::where('status', 1)
                                ->where('approve', 1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use ($request) {
                                    if ($request->keyword != '') {
                                        $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                            ->where('status', 1)
                                            ->where('approve', 1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                            ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                    }
                                })
                                ->pluck('id')->toArray();
                        }
                        $departures13 = [];
                        $dep_all = array_merge($departures13t, $departures13);
                        $departure_unique = array_unique($dep_all);

                        $total_departure = Departure::whereIn('id', $departure_unique)
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page = $total_departure / 45;
                        $total_page = (int)ceil($total_page);

                        if($request->dates != ''|| $request->packageType != ''|| $request->filter != ''){
                            $departures = Departure::whereIn('id', $departure_unique)
                            ->where('approve', 1)
                            ->where('status', 1)
                            ->where(function ($query) use ($request,$date,$diff){
                                if($request->dates != ''){
                                    if($request->dates == 0){
                                        $query->whereDate('start_date', '>=', date("Y-m-d"));
                                    }
                                    if($request->dates == 7 || $request->dates == 15 || $request->dates == 30 ){
                                        $query->whereBetween('start_date', [$date, $diff]);
                                    }
                                    if($request->dates == 31){
                                        $query->whereDate('start_date', '>=', $diff);
                                    }
                                }
                                // dd($date);
                                if($request->packageType != ''){
                                    if($request->packageType == 1 ||$request->packageType == 2 ||$request->packageType == 3 ||$request->packageType == 4 ||$request->packageType == 5){
                                        $query->where('departure_type', $request->packageType); 
                                    }
                                }
                                if($request->filter != ''){
                                    if($request->filter == 1){
                                        $best = DB::table('book_departures')
                                        ->select(DB::raw('count(*) as count, tenant_id'))
                                        ->GroupBY('tenant_id')
                                        ->orderBy('count', 'desc')
                                        ->first();
                                        $best_seller = $best->tenant_id;
                                        $query->where('tenant_id', $best_seller);
                                    }
                                    if($request->filter == 2){
                                        $datesUpdate = Date('Y-m-d H:i:s', strtotime('-120 days'));
                                        $newest = DB::table('users')
                                        ->join('departures', 'users.tenant_id', '=', 'departures.tenant_id')
                                        ->where('users.verified', 1)
                                        ->where('departures.approve', 1)
                                        ->where('users.created_at', '>', $datesUpdate)
                                        ->select('users.tenant_id','users.created_at')
                                        ->orderBy('users.created_at','asc')
                                        ->first();
                                        // dd($newest->tenant_id);
    
                                        if($newest){
                                            $best_seller = $newest->tenant_id;
                                        }else{
                                            $best_seller = "";
                                        }
                                        $query->where('tenant_id', $best_seller);
                                    }
                                }
                            })
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(99);
                        }
                    }
                }

                if ($request->type == 14) {
                    $departure_id = DB::table('departure_tags')->where('name', 'LIKE', '%' . $search_key . '%')->pluck('departure_id');

                    $departures14t = Departure::whereIn('id', $departure_id)
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    if ($request->keyword) {
                        $departures14 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->where('status', 1)
                                        ->where('approve', 1)
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    }
                    $departures14 = [];
                    $dep_all = array_merge($departures14t, $departures14);
                    $departure_unique = array_unique($dep_all);

                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    if($request->dates != '' || $request->packageType != '' || $request->filter != ''){
                        $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->where(function ($query) use ($request,$date,$diff){
                            if($request->dates != ''){
                                if($request->dates == 0){
                                    $query->whereDate('start_date', '>=', date("Y-m-d"));
                                }
                                if($request->dates == 7 || $request->dates == 15 || $request->dates == 30 ){
                                    $query->whereBetween('start_date', [$date, $diff]);
                                }
                                if($request->dates == 31){
                                    $query->whereDate('start_date', '>=', $diff);
                                }
                            }
                            // dd($date);
                            if($request->packageType != ''){
                                if($request->packageType == 1 ||$request->packageType == 2 ||$request->packageType == 3 ||$request->packageType == 4 ||$request->packageType == 5){
                                    $query->where('departure_type', $request->packageType); 
                                }
                            }
                            if($request->filter != ''){
                                if($request->filter == 1){
                                    $best = DB::table('book_departures')
                                    ->select(DB::raw('count(*) as count, tenant_id'))
                                    ->GroupBY('tenant_id')
                                    ->orderBy('count', 'desc')
                                    ->first();
                                    $best_seller = $best->tenant_id;
                                    $query->where('tenant_id', $best_seller);
                                }
                                if($request->filter == 2){
                                    $datesUpdate = Date('Y-m-d H:i:s', strtotime('-120 days'));
                                    $newest = DB::table('users')
                                    ->join('departures', 'users.tenant_id', '=', 'departures.tenant_id')
                                    ->where('users.verified', 1)
                                    ->where('departures.approve', 1)
                                    ->where('users.created_at', '>', $datesUpdate)
                                    ->select('users.tenant_id','users.created_at')
                                    ->orderBy('users.created_at','asc')
                                    ->first();
                                    // dd($newest->tenant_id);

                                    if($newest){
                                        $best_seller = $newest->tenant_id;
                                    }else{
                                        $best_seller = "";
                                    }
                                    $query->where('tenant_id', $best_seller);
                                }
                            }
                        })
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                    }
                }

                if ($request->type == 15) {
                    $departures15t = Departure::where('title', 'LIKE', '%' . $search_key . '%')
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    if ($request->keyword) {
                        $departures15 = Departure::where('status', 1)
                            ->where('approve', 1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use ($request) {
                                if ($request->keyword != '') {
                                    $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                                        ->where('status', 1)
                                        ->where('approve', 1)
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('dep_id', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('from', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('ending_at', 'LIKE', '%' . $request->keyword . '%')
                                        ->orWhere('tags', 'LIKE', '%' . $request->keyword . '%');
                                }
                            })
                            ->pluck('id')->toArray();
                    }
                    $departures15 = [];
                    $dep_all = array_merge($departures15t, $departures15);
                    $departure_unique = array_unique($dep_all);
                    $total_departure = Departure::whereIn('id', $departure_unique)
                        ->orderBy('start_date', 'ASC')->count();
                    $total_page = $total_departure / 45;
                    $total_page = (int)ceil($total_page);

                    if($request->dates != ''|| $request->packageType != ''|| $request->filter != ''){
                        $departures = Departure::whereIn('id', $departure_unique)
                        ->where('approve', 1)
                        ->where('status', 1)
                        ->where(function ($query) use ($request,$date,$diff){
                            if($request->dates != ''){
                                if($request->dates == 0){
                                    $query->whereDate('start_date', '>=', date("Y-m-d"));
                                }
                                if($request->dates == 7 || $request->dates == 15 || $request->dates == 30 ){
                                    $query->whereBetween('start_date', [$date, $diff]);
                                }
                                if($request->dates == 31){
                                    $query->whereDate('start_date', '>=', $diff);
                                }
                            }
                            // dd($date);
                            if($request->packageType != ''){
                                if($request->packageType == 1 ||$request->packageType == 2 ||$request->packageType == 3 ||$request->packageType == 4 ||$request->packageType == 5){
                                    $query->where('departure_type', $request->packageType); 
                                }
                            }
                            if($request->filter != ''){
                                if($request->filter == 1){
                                    $best = DB::table('book_departures')
                                    ->select(DB::raw('count(*) as count, tenant_id'))
                                    ->GroupBY('tenant_id')
                                    ->orderBy('count', 'desc')
                                    ->first();
                                    $best_seller = $best->tenant_id;
                                    $query->where('tenant_id', $best_seller);
                                }
                                if($request->filter == 2){
                                    $datesUpdate = Date('Y-m-d H:i:s', strtotime('-120 days'));
                                    $newest = DB::table('users')
                                    ->join('departures', 'users.tenant_id', '=', 'departures.tenant_id')
                                    ->where('users.verified', 1)
                                    ->where('departures.approve', 1)
                                    ->where('users.created_at', '>', $datesUpdate)
                                    ->select('users.tenant_id','users.created_at')
                                    ->orderBy('users.created_at','asc')
                                    ->first();
                                    // dd($newest->tenant_id);

                                    if($newest){
                                        $best_seller = $newest->tenant_id;
                                    }else{
                                        $best_seller = "";
                                    }
                                    $query->where('tenant_id', $best_seller);
                                }
                            }
                        })
                        ->orderBy('start_date', 'ASC')
                        ->simplePaginate(99);
                    }
                }
                $return = 0;
            }
            else{
                $departures = [];
                $return = 1;
                // return redirect('/dashboard');
            }
        } else {
            if($request->type != ""){
                $departures = [];
                $return = 1;
                // return redirect('/dashboard');
            }
            else{
                $departures = [];
                $return = 1;
                // return redirect()->route('dashboard');
            }
        }
       
        $columns = "";
        $departure_types = new \stdClass();
        if (count($departures) > 0) {
            foreach ($departures as $key => $value) {
                $userId = User::where(['tenant_id' => $value->tenant_id, 'main_user_type' => 1, 'user_type' => 0])->select('id')->first();
                $value->profileUserId = $userId->id;
                $hold = HoldDepartureSeat::where('departure_id', $value->id)
                    ->sum('hold_seat');
                if ($hold) {
                    $value->hold_sum = $hold;
                } else {
                    $value->hold_sum = 0;
                }

                //return $value->id;
                $book = BookDeparture::where('departure_id', $value->id)
                    ->where('status', 1)->sum('booked_seat');
                $single_book = BookDeparture::where('departure_id', $value->id)
                    ->sum('single_supplement_booked_seat');
                if ($book) {
                    $value->book_sum = $book;
                } else {
                    $value->book_sum = 0;
                }
                if ($single_book) {
                    $value->single_book_sum = $single_book;
                } else {
                    $value->single_book_sum = 0;
                }
               
                //user logo
                $logo_u = DB::table('users')->where('tenant_id', $value->tenant_id)->value('logo');
                $value->logo_image = url('companyLogo') . '/' . $logo_u;

                // Last Updated Departure
                $last_updated = DB::table('departures')->where('id', $value->id)->value('updated_at');
                $last_updated1 = DB::table('inclusions')->where('departure_id', $value->id)->value('updated_at');
                $last_updated2 = DB::table('return_flight_details')->where('departure_id', $value->id)->value('updated_at');
                $last_updated3 = DB::table('departure_flight_details')->where('departure_id', $value->id)->value('updated_at');
                $last_updated4 = DB::table('agent_itineraries')->where('departure_id', $value->id)->value('updated_at');
                $last_updated5 = DB::table('departure_prices')->where('departure_id', $value->id)->value('updated_at');
                $last_updated6 = DB::table('payment_schedules')->where('departure_id', $value->id)->value('updated_at');
                $last_updated7 = DB::table('cancel_schedules')->where('departure_id', $value->id)->value('updated_at');

                $last_max = max($last_updated, $last_updated1, $last_updated2, $last_updated3, $last_updated4, $last_updated5, $last_updated6, $last_updated7);
                $value->last_updated_dep = $last_max;

                // Inclusion icons
                $inclu_icons = Inclusion::where('departure_id', $value->id)->where('icon', '!=', null)->select('icon', 'name')->get();
                foreach ($inclu_icons as $key => $inclu_icon) {
                    $inclu_icon->icon = url('inclusion-images') . '/' . $inclu_icon->icon;
                }
                $value->inclusion_icons = $inclu_icons;

                $columns = DepartureColumnType::where('departure_type_id', $value->departure_type)->get()->pluck('column_name_id');
                $columns = json_encode($columns);
                $departure_destination = DB::table('departure_destinations')
                    ->join('destinations', 'departure_destinations.destination_id', 'destinations.id')
                    ->select('destinations.country_name', 'destinations.dest_name')
                    ->where('departure_destinations.departure_id', $value->id)
                    ->get();
                $users = User::where(['tenant_id' => $value->tenant_id, 'main_user_type' => 1, 'user_type' => 0])->first();
                $value->destination = $departure_destination;
                $other_price = DB::table('prices')->where('departure_id', $value->id)->where('price_type_id', 3)->get();
                $single_price = Price::where('departure_id', $value->id)->where('price_type_id', 4)->get();
                $value->singlePrice = $single_price;
                $value->OtherPrice = $other_price;

                $prices = DeparturePrice::where('departure_id', $value->id)->take(1)->get();
                $value->price = $prices;
                $departure_prices = DeparturePrice::where('departure_id', $value->id)->where('status', 1)->get();
                $value->departure_price = $departure_prices;

                $value->company_url = strtolower($users->company_id);
                //$bd->company_name = $user_data->company_name;

                $favSupplier = DB::table('favourite_supplier')
                    ->where('user_id', $user)
                    ->where('tenant_id', $value->tenant_id)
                    ->distinct()->first();
                if ($favSupplier) {
                    $value->fav_sapplier = 1;
                } else {
                    $value->fav_sapplier = 0;
                }

                $favPkg = DB::table('favourite_package')
                    ->join('departures', 'favourite_package.dep_id', '=', 'departures.id')
                    ->where('favourite_package.user_id', $user)
                    ->select('departures.id', 'departures.title', 'departures.start_date', 'departures.total_seat', 'departures.available_seat')
                    ->get();
                $favPkg_check = DB::table('favourite_package')
                    ->where('user_id', $user)
                    ->where('dep_id', $value->id)
                    ->first();
                if ($favPkg_check) {
                    $value->fav_pkg = 1;
                } else {
                    $value->fav_pkg = 0;
                }
            }
        }

        $release = HoldDepartureSeat::all();
        //$now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
        foreach ($release as $row) {
            //$add = $row->hold_duration + 5 ;
            $now = date('Y-m-d H:i', strtotime("+5 hours +30 minutes"));
            if ($now >= $row->auto_release) {
                $hold = $row->hold_seat;
                $departure = $row->departure_id;
                $dep = Departure::find($departure);
                $available = $dep->available_seat;

                //Notification
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud - Hold Units Released';
                $noti_save->body = $hold . ' units held by you in departure for' . $dep->ending_at . ' as per policy defined by supplier.';

                $noti_save->body_html = '<p><b> ' . $hold . ' </b>units held by you in departure for<b> ' . $dep->ending_at . ' </b>as per policy defined by supplier.</p>';
                $noti_save->user_id = $row->user_id;
                $noti_save->type = "AutoRelease";
                $noti_save->url_1 = url('login');
                $noti_save->save();

                $last_body_sup = $noti_save->body;
                $last_title_sup = $noti_save->title;
                $last_link_sup = $noti_save->url_1;
                $users = User::where('id', $row->user_id)->first();

                $firebaseToken = User::where('id', $row->user_id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup, $noti_save->id);

                Mail::send('mail.release_hold', ['hold' => $hold, 'destination' => $dep->ending_at, 'user' => $users, 'forceRelease' => 'no'], function ($m) use ($users, $getData) {
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

        $active_departureCount = Departure::where('dep_type', 'main')
            //->whereDate('start_date', '>=', $date)
            ->where('approve', 1)
            ->where('status', 1)
            ->get();

        $active = count($active_departureCount);

        $pending_departureCount = Departure::where('dep_type', 'main')
            ->whereDate('start_date', '>=', $date)
            ->where('approve', 0)
            ->where('status', 1)
            ->get();

        $pending = count($pending_departureCount);

        $inactive_departureCount = Departure::where('dep_type', 'main')
            ->whereDate('start_date', '<=', $date)
            ->where('approve', 1)
            ->where('status', 1)
            ->get();

        $inactive = count($inactive_departureCount);

        return view('dashboard.card', compact('user','date','packageType', 'dates', 'departures', 'total', 'holdduration', 'active', 'inactive', 'pending', 'search_key', 'req_type', 'columns', 'total_page'));


    } 

    public function favouriteTypeData(Request $request)
    {
        //dd($request);
        $id = $request->dep_id;
        $user = auth()->user()->id;
        $tenant_id = Departure::where('dep_id', $id)->value('tenant_id');
        $supplier_id = Departure::where('dep_id', $id)->value('user_id');
        $values = array(
            'user_id' => $user,
            'tenant_id' => $tenant_id,
            'supplier_id' => $supplier_id
        );
        $find_fav = DB::table('favourite_supplier')
            ->where('tenant_id', $tenant_id)
            ->where('user_id', $user)
            ->first();

        if ($find_fav != null) {
            $delete_fav = DB::table('favourite_supplier')->where('id', $find_fav->id)->delete();
        } else {
            $select_fav = DB::table('favourite_supplier')->insert($values);
        }

        // return redirect('dashboard.dashboard')->with('status','Data Updated');
    }

    public function favouritePkgData(Request $request)
    {
        //dd($request);
        $id = $request->id;
        $user = auth()->user()->id;

        $values = array(
            'user_id' => $user,
            'dep_id' => $id
        );
        $find_fav = DB::table('favourite_package')
            ->where('dep_id', $id)
            ->where('user_id', $user)
            ->first();

        if ($find_fav != null) {
            $delete_fav = DB::table('favourite_package')->where('id', $find_fav->id)->delete();
        } else {
            $select_fav = DB::table('favourite_package')->insert($values);
        }

        return response()->json(['dep_id'=>$id]);
    }
    
}
