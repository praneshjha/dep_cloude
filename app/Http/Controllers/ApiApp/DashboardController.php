<?php

namespace App\Http\Controllers\ApiApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
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


class DashboardController extends Controller
{
    //
    public function favourite_supplier(Request $request)
    {
        //$user = '133';
        $user = Auth::user()->id;
        
        $fav = DB::table('favourite_supplier')
                ->join('users','favourite_supplier.tenant_id','=','users.tenant_id')
                ->select('users.company_name')
                ->where('user_id',$user)->get();
                $date = date("Y-m-d");
        if($fav != null)
        {
            $status = array(
                'error' => false,
                'favourite' => $fav,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'favourite' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
        
    }

    function fetchData(Request $request)
    {
        if($request->keyword)
        {
            $query = $request->keyword;
            $dest_data = DB::table('destinations')
                ->where('dest_name', 'LIKE', "{$query}%")
                ->select('dest_name')
                ->limit(5)
                ->get()->toArray();
                foreach ($dest_data as $key => $value1) 
                {
                    $value1->type = '11';
                }
       
             //country search

            $country_data = DB::table('countries')
                ->where('country_name', 'LIKE', "{$query}%")
                ->orWhere('continent', 'LIKE', "{$query}%")
                ->groupBy('country_name')
                ->select('country_name as dest_name')
                ->limit(3)
                ->get()->toArray();
                foreach ($country_data as $key => $value2) 
                {
                    $value2->type = '12';
                }

            //publisher search
            $pub_data = DB::table('departures')
                ->where('company_name', 'LIKE', "%{$query}%")
                ->where('approve',1)
                ->where('status',1)
                ->groupBy('company_name')
                ->select('company_name as dest_name')
                ->limit(4)
                ->get()->toArray();
                foreach ($pub_data as $key => $value3) 
                {
                    $value3->type = '13';
                }

            //# tag search
            $depTag_data = DB::table('departure_tags')
                ->where('name', 'LIKE', "{$query}%")
                ->groupBy('name')
                ->select('name as dest_name')
                ->limit(4)
                ->get()->toArray();
                foreach ($depTag_data as $key => $value4) 
                {
                    $value4->type = '14';
                }  

            //departure search
            $departure = DB::table('departures')
                ->where('approve',1)
                ->where('status',1)
                ->whereDate('start_date', '>=', date("Y-m-d"))
                ->where('title', 'LIKE', "{$query}%")
                ->orWhere('company_name', 'LIKE', "{$query}%")
                ->groupBy('title')
                ->select('title as dest_name')
                ->limit(4)
                ->get()->toArray();
                foreach ($departure as $key => $value5) 
                {
                    $value5->type = '15';
                }  
            $array_merge = array_merge($dest_data, $country_data, $pub_data, $depTag_data, $departure);
            //$array_unique = array_unique($array_merge);
            if(count($array_merge)>0)
            {
                $status = array(
                    'error' => false,
                    'data' => $array_merge,
                    'message' =>"Success"
                );
            return Response($status,200);
            }
            else
            {
                $status = array(
                    'error' => false,
                    'data' => [],
                    'message' =>"Data not found!"
                );
            return Response($status,200);
            } 
        }
    }

    function fav_Sup_Redirect(Request $request)
    {
        $searchResult = [];
        $date = date("Y-m-d");
        $search_key = $request->favourite;
        $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')->where('status',1)->where('approve',1)
                                ->whereDate('start_date', '>=', $date)->pluck('id')->toArray();
                if($request->keyword)
                {
                    $departures13 = Departure::where('status',1)->where('approve',1)->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request)
                                    {
                                        if($request->keyword != '')
                                        {
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

                $searchResult = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
        if(count($searchResult)>0)
        {
            $status = array(
                    'error' => false,
                    'search_result' => $searchResult,
                    'message' =>"Success"
                    );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'search_result' => [],
                'message' =>"Data not found!"
                );
                return Response($status,200);
        }

    }

    function searchList(Request $request)
    {
        $departures = [];
        $date = date("Y-m-d");
        $packageType = $request->packageType;
        $dates = $request->dates;
        $search_key = $request->keyword;
        $filter = $request->filter;
        if($request->filter != '')
        {
            if(in_array('1',$filter))
            {
                //print_r('hello-');
            }
            if(in_array('2',$filter))
            {
                //print_r('-welco-');
            }
            if(in_array('3',$filter))
            {
                //print_r('-delco');
            }
        }
        else
        {
            dd('hye');
        }
        if($dates != '')
        {
            $diff = Date('Y-m-d', strtotime('+'.$dates.' days'));
        }
        else
        {
            $dates = 0;
        }
        if($request->keyword != '' || $request->keyword != null)
        {   
            //Destination
            if($request->type == 11)
            {
                // dd($packageType);
                if($packageType != '')
                {
                    if($packageType == 0)
                    {
                        if($dates == 0)
                        {
                            //dd('h5');
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                // ->where('departure_type',1)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                // ->where('departure_type',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            //dd('h4');
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                // ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                // dd($departures11t);
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereBetween('start_date',[$date,$diff])
                                    //->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
                                $departures11 = [];
                            }
                            $dep_all = array_merge($departures11t,$departures11);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);
                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                // ->where('departure_type',1)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                // dd($departures);
                        }
                        if($dates == 31)
                        {
                             //dd($diff);
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                // ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                // dd($departures11t);
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    // ->whereBetween('start_date',[$date,$diff])
                                    ->whereDate('start_date', '>=', $diff)
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                // ->where('departure_type',1)
                                // ->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', $diff)
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        
                            // dd($departures);
                    }

                    if($packageType == 1)
                    {
                        if($dates == 0)
                        {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                        
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                // ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45); 
                        }
                        if($dates == 31)
                        {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                        
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', $diff)
                                    //->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                // ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                            // dd($departures);
                    }

                    if($packageType == 2)
                    {
                        if($dates == 0)
                        {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 ||$dates == 15 || $dates == 30)
                        {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
                                $departures11 = [];
                            }
                            $dep_all = array_merge($departures11t,$departures11);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);
                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);   
                        }
                        // if($dates == 15)
                        // {
                        //     $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        //     $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        //     $departures11t = Departure::whereIn('id', $departure_id)
                        //         ->where('status',1)
                        //         ->where('approve',1)
                        //         ->where('departure_type',$packageType)
                        //         ->whereDate('start_date', '>=', $date)
                        //         ->pluck('id')->toArray();
                    
                        //     if($request->keyword ){
                        //         $departures11 = Departure::where('status',1)
                        //             ->where('approve',1)
                        //             ->whereDate('start_date', '>=', date("Y-m-d"))
                        //             ->where(function ($query) use($request){
                        //             if($request->keyword != '')
                        //             {
                        //                 $query->where('title', 'LIKE','%'.$request->keyword.'%')
                        //                     ->whereDate('start_date', '>=', date("Y-m-d"))
                        //                     ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                        //             }
                        //         })
                        //         ->pluck('id')->toArray();
                        //     }
                        //     else
                        //     {
                        //         $departures11 = [];
                        //     }
                        //     $dep_all = array_merge($departures11t,$departures11);
                        //     $departure_unique = array_unique($dep_all);
                        //     $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                        //         ->orderBy('start_date', 'ASC')->count();
                        //     $total_page=$total_departure/45;
                        //     $total_page=(int)ceil($total_page);
                        //     $departures = Departure::whereIn('id',$departure_unique)
                        //         ->where('approve',1)
                        //         ->where('status',1)
                        //         ->where('departure_type',$packageType)
                        //         ->whereBetween('start_date',[$date,$diff])
                        //         //->whereDate('start_date', '>=', date("Y-m-d"))
                        //         ->orderBy('start_date', 'ASC')
                        //         ->simplePaginate(45);
                        // }
                        // if($dates == 30)
                        // {
                        //     $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        //     $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        //     $departures11t = Departure::whereIn('id', $departure_id)
                        //         ->where('status',1)
                        //         ->where('approve',1)
                        //         ->where('departure_type',$packageType)
                        //         ->whereDate('start_date', '>=', $date)
                        //         ->pluck('id')->toArray();
                    
                        //     if($request->keyword ){
                        //         $departures11 = Departure::where('status',1)
                        //             ->where('approve',1)
                        //             ->whereDate('start_date', '>=', date("Y-m-d"))
                        //             ->where(function ($query) use($request){
                        //             if($request->keyword != '')
                        //             {
                        //                 $query->where('title', 'LIKE','%'.$request->keyword.'%')
                        //                     ->whereDate('start_date', '>=', date("Y-m-d"))
                        //                     ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                        //                     ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                        //             }
                        //         })
                        //         ->pluck('id')->toArray();
                        //     }
                        //     else
                        //     {
                        //         $departures11 = [];
                        //     }
                        //     $dep_all = array_merge($departures11t,$departures11);
                        //     $departure_unique = array_unique($dep_all);
                        //     $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                        //         ->orderBy('start_date', 'ASC')->count();
                        //     $total_page=$total_departure/45;
                        //     $total_page=(int)ceil($total_page);
                        //     $departures = Departure::whereIn('id',$departure_unique)
                        //         ->where('approve',1)
                        //         ->where('status',1)
                        //         ->where('departure_type',$packageType)
                        //         ->whereBetween('start_date',[$date,$diff])
                        //         //->whereDate('start_date', '>=', date("Y-m-d"))
                        //         ->orderBy('start_date', 'ASC')
                        //         ->simplePaginate(45);
                        // }
                        if($dates == 31)
                        {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                    
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                            ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
                                $departures11 = [];
                            }
                            $dep_all = array_merge($departures11t,$departures11);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)
                                // ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);
                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                // ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        
                        //dd($departures);
                    }

                    if($packageType == 3)
                    {
                       if($dates == 0)
                       {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                       }
                       if($dates == 7 || $dates == 15 || $dates == 30)
                       {
                        $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        $departures11t = Departure::whereIn('id', $departure_id)
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if($request->keyword ){
                            $departures11 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                            ->pluck('id')->toArray();
                        }
                        else
                        {
                            $departures11 = [];
                        }
                        $dep_all = array_merge($departures11t,$departures11);
                        $departure_unique = array_unique($dep_all);
                        $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page=$total_departure/45;
                        $total_page=(int)ceil($total_page);
                        $departures = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', date("Y-m-d"))
                            ->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
                       }
                       if($dates == 31)
                       {
                        $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        $departures11t = Departure::whereIn('id', $departure_id)
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if($request->keyword ){
                            $departures11 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                            ->pluck('id')->toArray();
                        }
                        else
                        {
                            $departures11 = [];
                        }
                        $dep_all = array_merge($departures11t,$departures11);
                        $departure_unique = array_unique($dep_all);
                        $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page=$total_departure/45;
                        $total_page=(int)ceil($total_page);
                        $departures = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $diff)
                            //->whereDate('start_date', '>=', date("Y-m-d"))
                            //->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
                       } 
                        
                    }

                    if($packageType == 4)
                    {
                        if($dates == 0)
                       {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                       }
                       if($dates == 7 || $dates == 15 || $dates == 30)
                       {
                        $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        $departures11t = Departure::whereIn('id', $departure_id)
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if($request->keyword ){
                            $departures11 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                            ->pluck('id')->toArray();
                        }
                        else
                        {
                            $departures11 = [];
                        }
                        $dep_all = array_merge($departures11t,$departures11);
                        $departure_unique = array_unique($dep_all);
                        $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page=$total_departure/45;
                        $total_page=(int)ceil($total_page);
                        $departures = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', date("Y-m-d"))
                            ->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
                       }
                            // if($dates == 15)
                            // {
                            //     $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            //     $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            //     $departures11t = Departure::whereIn('id', $departure_id)
                            //         ->where('status',1)
                            //         ->where('approve',1)
                            //         ->where('departure_type',$packageType)
                            //         ->whereDate('start_date', '>=', $date)
                            //         ->pluck('id')->toArray();
                            //     if($request->keyword ){
                            //         $departures11 = Departure::where('status',1)
                            //             ->where('approve',1)
                            //             ->whereDate('start_date', '>=', date("Y-m-d"))
                            //             ->where(function ($query) use($request){
                            //         if($request->keyword != '')
                            //         {
                            //             $query->where('title', 'LIKE','%'.$request->keyword.'%')
                            //                 ->whereDate('start_date', '>=', date("Y-m-d"))
                            //                 ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            //         }
                            //     })
                            //         ->pluck('id')->toArray();
                            //     }
                            //     else
                            //     {
                            //         $departures11 = [];
                            //     }
                            //     $dep_all = array_merge($departures11t,$departures11);
                            //     $departure_unique = array_unique($dep_all);
                            //     $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            //         ->orderBy('start_date', 'ASC')->count();
                            //     $total_page=$total_departure/45;
                            //     $total_page=(int)ceil($total_page);
                            //     $departures = Departure::whereIn('id',$departure_unique)
                            //         ->where('approve',1)
                            //         ->where('status',1)
                            //         ->where('departure_type',$packageType)
                            //         //->whereDate('start_date', '>=', date("Y-m-d"))
                            //         ->whereBetween('start_date',[$date,$diff])
                            //         ->orderBy('start_date', 'ASC')
                            //         ->simplePaginate(45);
                            // }
                            // if($dates == 30)
                            // {
                            //     $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            //     $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            //     $departures11t = Departure::whereIn('id', $departure_id)
                            //         ->where('status',1)
                            //         ->where('approve',1)
                            //         ->where('departure_type',$packageType)
                            //         ->whereDate('start_date', '>=', $date)
                            //         ->pluck('id')->toArray();
                            //     if($request->keyword ){
                            //         $departures11 = Departure::where('status',1)
                            //             ->where('approve',1)
                            //             ->whereDate('start_date', '>=', date("Y-m-d"))
                            //             ->where(function ($query) use($request){
                            //         if($request->keyword != '')
                            //         {
                            //             $query->where('title', 'LIKE','%'.$request->keyword.'%')
                            //                 ->whereDate('start_date', '>=', date("Y-m-d"))
                            //                 ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                            //                 ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            //         }
                            //     })
                            //         ->pluck('id')->toArray();
                            //     }
                            //     else
                            //     {
                            //         $departures11 = [];
                            //     }
                            //     $dep_all = array_merge($departures11t,$departures11);
                            //     $departure_unique = array_unique($dep_all);
                            //     $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            //         ->orderBy('start_date', 'ASC')->count();
                            //     $total_page=$total_departure/45;
                            //     $total_page=(int)ceil($total_page);
                            //     $departures = Departure::whereIn('id',$departure_unique)
                            //         ->where('approve',1)
                            //         ->where('status',1)
                            //         ->where('departure_type',$packageType)
                            //         //->whereDate('start_date', '>=', date("Y-m-d"))
                            //         ->whereBetween('start_date',[$date,$diff])
                            //         ->orderBy('start_date', 'ASC')
                            //         ->simplePaginate(45);
                            // }
                       if($dates == 31)
                       {
                        $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        $departures11t = Departure::whereIn('id', $departure_id)
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if($request->keyword ){
                            $departures11 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                            ->pluck('id')->toArray();
                        }
                        else
                        {
                            $departures11 = [];
                        }
                        $dep_all = array_merge($departures11t,$departures11);
                        $departure_unique = array_unique($dep_all);
                        $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page=$total_departure/45;
                        $total_page=(int)ceil($total_page);
                        $departures = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $diff)
                            //->whereDate('start_date', '>=', date("Y-m-d"))
                            //->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
                       }
                    }

                    if($packageType == 5)
                    {
                        if($dates == 0)
                       {
                            $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                            $departures11t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                                $departures11 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                        ->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                        ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            else
                            {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                       }
                       if($dates == 7 || $dates == 15 || $dates == 30)
                       {
                        $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        $departures11t = Departure::whereIn('id', $departure_id)
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if($request->keyword ){
                            $departures11 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                            ->pluck('id')->toArray();
                        }
                        else
                        {
                            $departures11 = [];
                        }
                        $dep_all = array_merge($departures11t,$departures11);
                        $departure_unique = array_unique($dep_all);
                        $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page=$total_departure/45;
                        $total_page=(int)ceil($total_page);
                        $departures = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', date("Y-m-d"))
                            ->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
                       }
                        //    if($dates == 15)
                        //    {
                        //     $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        //     $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        //     $departures11t = Departure::whereIn('id', $departure_id)
                        //         ->where('status',1)
                        //         ->where('approve',1)
                        //         ->where('departure_type',$packageType)
                        //         ->whereDate('start_date', '>=', $date)
                        //         ->pluck('id')->toArray();
                        //     if($request->keyword ){
                        //         $departures11 = Departure::where('status',1)
                        //             ->where('approve',1)
                        //             ->whereDate('start_date', '>=', date("Y-m-d"))
                        //             ->where(function ($query) use($request){
                        //         if($request->keyword != '')
                        //         {
                        //             $query->where('title', 'LIKE','%'.$request->keyword.'%')
                        //                 ->whereDate('start_date', '>=', date("Y-m-d"))
                        //                 ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                        //         }
                        //     })
                        //         ->pluck('id')->toArray();
                        //     }
                        //     else
                        //     {
                        //         $departures11 = [];
                        //     }
                        //     $dep_all = array_merge($departures11t,$departures11);
                        //     $departure_unique = array_unique($dep_all);
                        //     $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                        //         ->orderBy('start_date', 'ASC')->count();
                        //     $total_page=$total_departure/45;
                        //     $total_page=(int)ceil($total_page);
                        //     $departures = Departure::whereIn('id',$departure_unique)
                        //         ->where('approve',1)
                        //         ->where('status',1)
                        //         ->where('departure_type',$packageType)
                        //         //->whereDate('start_date', '>=', date("Y-m-d"))
                        //         ->whereBetween('start_date',[$date,$diff])
                        //         ->orderBy('start_date', 'ASC')
                        //         ->simplePaginate(45);
                        //    }
                        //    if($dates == 30)
                        //    {
                        //     $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        //     $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        //     $departures11t = Departure::whereIn('id', $departure_id)
                        //         ->where('status',1)
                        //         ->where('approve',1)
                        //         ->where('departure_type',$packageType)
                        //         ->whereDate('start_date', '>=', $date)
                        //         ->pluck('id')->toArray();
                        //     if($request->keyword ){
                        //         $departures11 = Departure::where('status',1)
                        //             ->where('approve',1)
                        //             ->whereDate('start_date', '>=', date("Y-m-d"))
                        //             ->where(function ($query) use($request){
                        //         if($request->keyword != '')
                        //         {
                        //             $query->where('title', 'LIKE','%'.$request->keyword.'%')
                        //                 ->whereDate('start_date', '>=', date("Y-m-d"))
                        //                 ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                        //                 ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                        //         }
                        //     })
                        //         ->pluck('id')->toArray();
                        //     }
                        //     else
                        //     {
                        //         $departures11 = [];
                        //     }
                        //     $dep_all = array_merge($departures11t,$departures11);
                        //     $departure_unique = array_unique($dep_all);
                        //     $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                        //         ->orderBy('start_date', 'ASC')->count();
                        //     $total_page=$total_departure/45;
                        //     $total_page=(int)ceil($total_page);
                        //     $departures = Departure::whereIn('id',$departure_unique)
                        //         ->where('approve',1)
                        //         ->where('status',1)
                        //         ->where('departure_type',$packageType)
                        //         //->whereDate('start_date', '>=', date("Y-m-d"))
                        //         ->whereBetween('start_date',[$date,$diff])
                        //         ->orderBy('start_date', 'ASC')
                        //         ->simplePaginate(45);
                        //    }
                       if($dates == 31)
                       {
                        $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                        $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                        $departures11t = Departure::whereIn('id', $departure_id)
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                        if($request->keyword ){
                            $departures11 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                            ->pluck('id')->toArray();
                        }
                        else
                        {
                            $departures11 = [];
                        }
                        $dep_all = array_merge($departures11t,$departures11);
                        $departure_unique = array_unique($dep_all);
                        $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')->count();
                        $total_page=$total_departure/45;
                        $total_page=(int)ceil($total_page);
                        $departures = Departure::whereIn('id',$departure_unique)
                            ->where('approve',1)
                            ->where('status',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $diff)
                            //->whereDate('start_date', '>=', date("Y-m-d"))
                            //->whereBetween('start_date',[$date,$diff])
                            ->orderBy('start_date', 'ASC')
                            ->simplePaginate(45);
                       }
                    }
                }
                else
                {
                    $destination_id = DB::table('destinations')->where('dest_name', 'LIKE','%'.$search_key.'%')->value('id');
                    $departure_id = DB::table('departure_destinations')->where('destination_id', $destination_id)->pluck('departure_id');
                    $departures11t = Departure::whereIn('id', $departure_id)
                        ->where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    
                    if($request->keyword ){
                        $departures11 = Departure::where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '>=', date("Y-m-d"))
                            ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
                                $query->where('title', 'LIKE','%'.$request->keyword.'%')
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orWhere('company_name', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('dep_id', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('from', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('ending_at', 'LIKE','%'.$request->keyword.'%')
                                    ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                            }
                        })
                        ->pluck('id')->toArray();
                    }
                    if($packageType == '')
                    {
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
                        ->simplePaginate(45);
                    //dd($departures);
                }
                
            }

            if($request->type == 12)
            {
                if($packageType != '')
                {
                    if($packageType == 0)
                    {
                        if($dates == 0)
                        {
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
                                        if($request->keyword != '')
                                        {
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
                                        if($request->from != '' || $request->to != '')
                                        {
                                            $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                                ->whereDate('start_date', '>=', date("Y-m-d"));
                                        }
                                        if($request->departure_from != '' || $request->departure_to)
                                        {
                                            $query->whereDate('start_date', '>=', date("Y-m-d"))
                                                ->where('from',$request->departure_from)
                                                ->orWhere('ending_at',$request->departure_to);
                                        }
                                        if($request->status == 1)
                                        {
                                            $query->whereDate('start_date', '>=', date("Y-m-d"))
                                                ->where('available_seat','>',0);
                                        }
                                        if($request->status == 2)
                                        {
                                            $query->whereDate('start_date', '<=', date("Y-m-d"))
                                                ->where('available_seat','<=',0);
                                        }
                                        if($request->publiser_name != null)
                                        {
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
                                    ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');
    
                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');
    
                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }  
                    }
                    if($packageType == 1)
                    {
                        if($dates == 0)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    if($request->from != '' || $request->to != '')
                                    {
                                        $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                            ->whereDate('start_date', '>=', date("Y-m-d"));
                                    }
                                    if($request->departure_from != '' || $request->departure_to)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('from',$request->departure_from)
                                            ->orWhere('ending_at',$request->departure_to);
                                    }
                                    if($request->status == 1)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('available_seat','>',0);
                                    }
                                    if($request->status == 2)
                                    {
                                        $query->whereDate('start_date', '<=', date("Y-m-d"))
                                            ->where('available_seat','<=',0);
                                    }
                                    if($request->publiser_name != null)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->whereIn('company_name',$request->publiser_name);
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 2)
                    {
                        if($dates == 0)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    if($request->from != '' || $request->to != '')
                                    {
                                        $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                            ->whereDate('start_date', '>=', date("Y-m-d"));
                                    }
                                    if($request->departure_from != '' || $request->departure_to)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('from',$request->departure_from)
                                            ->orWhere('ending_at',$request->departure_to);
                                    }
                                    if($request->status == 1)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('available_seat','>',0);
                                    }
                                    if($request->status == 2)
                                    {
                                        $query->whereDate('start_date', '<=', date("Y-m-d"))
                                            ->where('available_seat','<=',0);
                                    }
                                    if($request->publiser_name != null)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->whereIn('company_name',$request->publiser_name);
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 3)
                    {
                        if($dates == 0)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    if($request->from != '' || $request->to != '')
                                    {
                                        $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                            ->whereDate('start_date', '>=', date("Y-m-d"));
                                    }
                                    if($request->departure_from != '' || $request->departure_to)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('from',$request->departure_from)
                                            ->orWhere('ending_at',$request->departure_to);
                                    }
                                    if($request->status == 1)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('available_seat','>',0);
                                    }
                                    if($request->status == 2)
                                    {
                                        $query->whereDate('start_date', '<=', date("Y-m-d"))
                                            ->where('available_seat','<=',0);
                                    }
                                    if($request->publiser_name != null)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->whereIn('company_name',$request->publiser_name);
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 4)
                    {
                        if($dates == 0)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    if($request->from != '' || $request->to != '')
                                    {
                                        $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                            ->whereDate('start_date', '>=', date("Y-m-d"));
                                    }
                                    if($request->departure_from != '' || $request->departure_to)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('from',$request->departure_from)
                                            ->orWhere('ending_at',$request->departure_to);
                                    }
                                    if($request->status == 1)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('available_seat','>',0);
                                    }
                                    if($request->status == 2)
                                    {
                                        $query->whereDate('start_date', '<=', date("Y-m-d"))
                                            ->where('available_seat','<=',0);
                                    }
                                    if($request->publiser_name != null)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->whereIn('company_name',$request->publiser_name);
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 5)
                    {
                        if($dates == 0)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    if($request->from != '' || $request->to != '')
                                    {
                                        $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                            ->whereDate('start_date', '>=', date("Y-m-d"));
                                    }
                                    if($request->departure_from != '' || $request->departure_to)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('from',$request->departure_from)
                                            ->orWhere('ending_at',$request->departure_to);
                                    }
                                    if($request->status == 1)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->where('available_seat','>',0);
                                    }
                                    if($request->status == 2)
                                    {
                                        $query->whereDate('start_date', '<=', date("Y-m-d"))
                                            ->where('available_seat','<=',0);
                                    }
                                    if($request->publiser_name != null)
                                    {
                                        $query->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->whereIn('company_name',$request->publiser_name);
                                    }
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $country_id = DB::table('countries')->where('country_name', 'LIKE','%'.$search_key.'%')->value('id');
                            $departure_id = DB::table('country_departures')->where('country_id', $country_id)->pluck('departure_id');

                            $departures12t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures12 = Departure::where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures12 = [];
                            $dep_all = array_merge($departures12t,$departures12);
                            $departure_unique = array_unique($dep_all);
                            
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                }
                else
                {
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
                                if($request->keyword != '')
                                {
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
                                if($request->from != '' || $request->to != '')
                                {
                                    $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                        ->whereDate('start_date', '>=', date("Y-m-d"));
                                }
                                if($request->departure_from != '' || $request->departure_to)
                                {
                                    $query->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->where('from',$request->departure_from)
                                        ->orWhere('ending_at',$request->departure_to);
                                }
                                if($request->status == 1)
                                {
                                    $query->whereDate('start_date', '>=', date("Y-m-d"))
                                        ->where('available_seat','>',0);
                                }
                                if($request->status == 2)
                                {
                                    $query->whereDate('start_date', '<=', date("Y-m-d"))
                                        ->where('available_seat','<=',0);
                                }
                                if($request->publiser_name != null)
                                {
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
                            ->orderBy('start_date', 'ASC');
                            // ->simplePaginate(45);
                }
            }

            if($request->type == 13)
            {
                if($packageType != '')
                {
                    if($packageType == 0)
                    {
                        if($dates == 0)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures13 = [];
                            $dep_all = array_merge($departures13t,$departures13);
                            $departure_unique = array_unique($dep_all);

                            $total_departure = Departure::whereIn('id',$departure_unique)
                                ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->whereBetween('start_date',[$date,$diff])
                                //>whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures13 = [];
                            $dep_all = array_merge($departures13t,$departures13);
                            $departure_unique = array_unique($dep_all);

                            $total_departure = Departure::whereIn('id',$departure_unique)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //>whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }    
                    }
                        
                    if($packageType == 1)
                    {
                        if($dates == 0)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7|| $dates == 15||$dates == 30)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures13 = [];
                            $dep_all = array_merge($departures13t,$departures13);
                            $departure_unique = array_unique($dep_all);

                            $total_departure = Departure::whereIn('id',$departure_unique)
                            ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }

                    }
                        
                    if($packageType == 2)
                    {
                        if($dates == 0)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }     
                    }
                        
                    if($packageType == 3)
                    {
                        if($dates == 0)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                        
                    if($packageType == 4)
                    {
                        if($dates == 0)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                        
                    if($packageType == 5)
                    {
                        if($dates == 0)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                        
                }
                else
                {
                    $departures13t = Departure::where('company_name', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures13 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                // if($request->from != '' || $request->to != '')
                                // {
                                //     $query->whereBetween('start_date',[(date("Y-m-d", strtotime($request->from))),(date("Y-m-d", strtotime($request->to)))])
                                //         ->whereDate('start_date', '>=', date("Y-m-d"));
                                // }
                                // if($request->departure_from != '' || $request->departure_to)
                                // {
                                //     $query->whereDate('start_date', '>=', date("Y-m-d"))
                                //         ->where('from',$request->departure_from)
                                //         ->orWhere('ending_at',$request->departure_to);
                                // }
                                // if($request->status == 1)
                                // {
                                //     $query->whereDate('start_date', '>=', date("Y-m-d"))
                                //         ->where('available_seat','>',0);
                                // }
                                // if($request->status == 2)
                                // {
                                //     $query->whereDate('start_date', '<=', date("Y-m-d"))
                                //         ->where('available_seat','<=',0);
                                // }
                                // if($request->publiser_name != null)
                                // {
                                //     $query->whereDate('start_date', '>=', date("Y-m-d"))
                                //         ->whereIn('company_name',$request->publiser_name);
                                // }
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
                                ->simplePaginate(45);
                }
                // 
            }

            if($request->type == 14)
            {
                if($packageType != '')
                {
                    if($packageType == 0)
                    {
                        if($dates == 0)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                if($request->keyword ){
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
                                    ->simplePaginate(45);
                        }
                        if($dates == 7|| $dates == 15 ||$dates == 30)
                        {
                                $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
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
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                                $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
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
                                //->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', $diff)
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 1)
                    {
                        if($dates == 0)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7||$dates == 15 ||$dates == 30)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 2)
                    {
                        if($dates == 0)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7||$dates == 15 ||$dates == 30)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 3)
                    {
                        if($dates == 0)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7||$dates == 15 ||$dates == 30)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 4)
                    {
                        if($dates == 0)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7||$dates == 15 ||$dates == 30)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                    if($packageType == 5)
                    {
                        if($dates == 0)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 7||$dates == 15 ||$dates == 30)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $diff)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departure_id = DB::table('departure_tags')->where('name', 'LIKE','%'.$search_key.'%')->pluck('departure_id');

                            $departures14t = Departure::whereIn('id', $departure_id)
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword ){
                            $departures14 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures14 = [];
                            $dep_all = array_merge($departures14t,$departures14);
                            $departure_unique = array_unique($dep_all);
                    
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                    }
                }
                else
                {
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
                            ->simplePaginate(45);
                    }
            }

            if($request->type == 15)
            {
                if($packageType != '')
                {
                    if($packageType == 0)
                    {
                        if($dates == 0)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($request->keyword)
                            {
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($search_key)
                            {
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        if($dates == 31)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                //->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                            if($search_key)
                            {
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                        }
                        
                    }
                    if($packageType == 1)
                    {
                        if($dates == 0)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                        if($request->keyword != '')
                                        {
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
                                        
                                    })
                                    ->pluck('id')->toArray();
                                }
                                $departures15 = [];
                                $dep_all = array_merge($departures15t,$departures15);
                                $departure_unique = array_unique($dep_all);
                                $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                    ->orderBy('start_date', 'ASC')->count();
                                $total_page=$total_departure/45;
                                $total_page=(int)ceil($total_page);

                                $departures = Departure::whereIn('id',$departure_unique)
                                    ->where('approve',1)
                                    ->where('status',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orderBy('start_date', 'ASC')
                                    ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                          
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($search_key){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($search_key != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$search_key.'%')
                                            ->where('status',1)
                                            ->where('approve',1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('from', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('tags', 'LIKE','%'.$search_key.'%');
                                    }
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                        if($dates == 31)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($search_key != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$search_key.'%')
                                            ->where('status',1)
                                            ->where('approve',1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('from', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('tags', 'LIKE','%'.$search_key.'%');
                                    }
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            //->whereBetween('start_date',[$date,$diff])
                            ->whereDate('start_date', '>=', $diff)    
                            ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                    }
                    if($packageType == 2)
                    {
                        if($dates == 0)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                        if($request->keyword != '')
                                        {
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
                                        
                                    })
                                    ->pluck('id')->toArray();
                                }
                                $departures15 = [];
                                $dep_all = array_merge($departures15t,$departures15);
                                $departure_unique = array_unique($dep_all);
                                $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                    ->orderBy('start_date', 'ASC')->count();
                                $total_page=$total_departure/45;
                                $total_page=(int)ceil($total_page);

                                $departures = Departure::whereIn('id',$departure_unique)
                                    ->where('approve',1)
                                    ->where('status',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orderBy('start_date', 'ASC')
                                    ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                          
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($search_key){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                        if($dates == 31)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            //->whereBetween('start_date',[$date,$diff])
                            ->whereDate('start_date', '>=', $diff)    
                            ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                    }
                    if($packageType == 3)
                    {
                        if($dates == 0)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                        if($request->keyword != '')
                                        {
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
                                        
                                    })
                                    ->pluck('id')->toArray();
                                }
                                $departures15 = [];
                                $dep_all = array_merge($departures15t,$departures15);
                                $departure_unique = array_unique($dep_all);
                                $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                    ->orderBy('start_date', 'ASC')->count();
                                $total_page=$total_departure/45;
                                $total_page=(int)ceil($total_page);

                                $departures = Departure::whereIn('id',$departure_unique)
                                    ->where('approve',1)
                                    ->where('status',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orderBy('start_date', 'ASC')
                                    ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                          
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($search_key){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                        if($dates == 31)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($search_key != '')
                                    {
                                        $query->where('title', 'LIKE','%'.$search_key.'%')
                                            ->where('status',1)
                                            ->where('approve',1)
                                            ->whereDate('start_date', '>=', date("Y-m-d"))
                                            ->orWhere('company_name', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('dep_id', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('from', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('ending_at', 'LIKE','%'.$search_key.'%')
                                            ->orWhere('tags', 'LIKE','%'.$search_key.'%');
                                    }
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            //->whereBetween('start_date',[$date,$diff])
                            ->whereDate('start_date', '>=', $diff)    
                            ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                    }
                    if($packageType == 4)
                    {
                        if($dates == 0)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                if($search_key || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                        if($search_key != '')
                                        {
                                            $query->where('title', 'LIKE','%'.$search_key.'%')
                                                ->where('status',1)
                                                ->where('approve',1)
                                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                                ->orWhere('company_name', 'LIKE','%'.$search_key.'%')
                                                ->orWhere('dep_id', 'LIKE','%'.$search_key.'%')
                                                ->orWhere('from', 'LIKE','%'.$search_key.'%')
                                                ->orWhere('ending_at', 'LIKE','%'.$search_key.'%')
                                                ->orWhere('tags', 'LIKE','%'.$request->keyword.'%');
                                        }
                                        
                                    })
                                    ->pluck('id')->toArray();
                                }
                                $departures15 = [];
                                $dep_all = array_merge($departures15t,$departures15);
                                $departure_unique = array_unique($dep_all);
                                $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                    ->orderBy('start_date', 'ASC')->count();
                                $total_page=$total_departure/45;
                                $total_page=(int)ceil($total_page);

                                $departures = Departure::whereIn('id',$departure_unique)
                                    ->where('approve',1)
                                    ->where('status',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orderBy('start_date', 'ASC')
                                    ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                          
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                        if($dates == 31)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            //->whereBetween('start_date',[$date,$diff])
                            ->whereDate('start_date', '>=', $diff)    
                            ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                    }
                    if($packageType == 5)
                    {
                        if($dates == 0)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                                ->where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $date)
                                ->pluck('id')->toArray();
                                if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                                $departures15 = Departure::where('status',1)
                                    ->where('approve',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->where(function ($query) use($request){
                                        if($request->keyword != '')
                                        {
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
                                        
                                    })
                                    ->pluck('id')->toArray();
                                }
                                $departures15 = [];
                                $dep_all = array_merge($departures15t,$departures15);
                                $departure_unique = array_unique($dep_all);
                                $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                                    ->orderBy('start_date', 'ASC')->count();
                                $total_page=$total_departure/45;
                                $total_page=(int)ceil($total_page);

                                $departures = Departure::whereIn('id',$departure_unique)
                                    ->where('approve',1)
                                    ->where('status',1)
                                    ->where('departure_type',$packageType)
                                    ->whereDate('start_date', '>=', date("Y-m-d"))
                                    ->orderBy('start_date', 'ASC')
                                    ->simplePaginate(45);
                        }
                        if($dates == 7 || $dates == 15 || $dates == 30)
                        {
                          
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            ->whereBetween('start_date',[$date,$diff])
                                ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                        if($dates == 31)
                        {
                            $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                            ->where('status',1)
                            ->where('approve',1)
                            ->where('departure_type',$packageType)
                            //->whereDate('start_date', '>=', $date)
                            ->pluck('id')->toArray();
                            if($request->keyword || $request->from || $request->to || $request->departure_from || $request->departure_to  || $request->status  || $request->publiser_name){
                            $departures15 = Departure::where('status',1)
                                ->where('approve',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', date("Y-m-d"))
                                ->where(function ($query) use($request){
                                    if($request->keyword != '')
                                    {
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
                                    
                                })
                                ->pluck('id')->toArray();
                            }
                            $departures15 = [];
                            $dep_all = array_merge($departures15t,$departures15);
                            $departure_unique = array_unique($dep_all);
                            $total_departure = Departure::whereIn('id',$departure_unique)->where('departure_type',$packageType)
                            //->whereBetween('start_date',[$date,$diff])
                            ->whereDate('start_date', '>=', $diff)    
                            ->orderBy('start_date', 'ASC')->count();
                            $total_page=$total_departure/45;
                            $total_page=(int)ceil($total_page);

                            $departures = Departure::whereIn('id',$departure_unique)
                                ->where('approve',1)
                                ->where('status',1)
                                ->where('departure_type',$packageType)
                                ->whereDate('start_date', '>=', $diff)
                                //->whereBetween('start_date',[$date,$diff])
                                //->whereDate('start_date', '>=', date("Y-m-d"))
                                ->orderBy('start_date', 'ASC')
                                ->simplePaginate(45);
                                
                        }
                    }

                }
                else
                {
                    $departures15t = Departure::where('title', 'LIKE','%'.$search_key.'%')
                        ->where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', $date)
                        ->pluck('id')->toArray();
                    if($request->keyword)
                    {
                        $departures15 = Departure::where('status',1)
                        ->where('approve',1)
                        ->whereDate('start_date', '>=', date("Y-m-d"))
                        ->where(function ($query) use($request){
                            if($request->keyword != '')
                            {
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
                        ->simplePaginate(45);
                }
            }

        }
        if(count($departures)>0)
        {
            $status = array(
                'error' => false,
                'search_result' => $departures,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'search_result' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }
}
