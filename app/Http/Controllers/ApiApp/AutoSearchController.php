<?php

namespace App\Http\Controllers\ApiApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AutoSearchController extends Controller
{
    //Auto Search API
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

    public function DepartureFrom(Request $request)
    {
        $startDest = [];
        $date = date("Y-m-d");
        if($request->keyword != '' || $request->keyword != null){
            $search = $request->keyword;
            $startDest = DB::table('departures')->select("from as departure_from")
                    ->where('status',1)
                    ->where('approve',1)
                    ->where('from','!=', '')
                    ->where('from','LIKE',"%$search%")
                    ->whereDate('start_date', '>=', $date)
                    ->distinct()
                    ->get(10)->toArray();
        
        }
        else
        {
            $startDest = DB::table('departures')->select("from as departure_from")
                     ->where('status',1)
                    ->where('approve',1)
                    ->where('from','!=', '')
                    ->whereDate('start_date', '>=', $date)
                    ->distinct()
                    ->limit(10)
                    ->get()->toArray();
        }
        if(count($startDest)>0)
        {
            $status = array(
                'error' => false,
                'departure_from' => $startDest,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'departure_from' => [],
                'message' =>"Data not found!"
            );
        return Response($status,200);
        } 
    }
    public function DepartureTo(Request $request)
    {
        $startDest = [];
        $date = date("Y-m-d");
        if($request->keyword){
        $search = $request->keyword;
            $startDest = DB::table('departures')->select("ending_at as departure_to")
                    ->where('status',1)
                    ->where('approve',1)
                    ->where('from','!=', '')
                    ->where('ending_at','LIKE',"%$search%")
                    ->whereDate('start_date', '>=', $date)
                    ->distinct()
                    ->get(15);
        
        }
        else
        {
            $startDest = DB::table('departures')->select("ending_at as departure_to")
                     ->where('status',1)
                     ->where('approve',1)
                     ->where('from','!=', '')
                     ->whereDate('start_date', '>=', $date)
                     ->distinct()
                     ->limit(15)
                     ->get();
        }
        if(count($startDest)>0)
        {
            $status = array(
                'error' => false,
                'departure_to' => $startDest,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'departure_to' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }

    public function publisherList(Request $request)
    {
        $publishers = [];
        if($request->keyword)
        {
            $search = $request->keyword;
            $publishers = DB::table('departures')->where('status',1)
                    ->where('approve',1)
                    ->where('departure_ownner','!=', '')
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->where('departure_ownner','LIKE',"%$search%")
                    ->orderBy('departure_ownner', 'ASC')
                    ->distinct()
                    ->select('departure_ownner as publisher')
                    ->get(10);
            
        }
        else
        {
            $publishers = DB::table('departures')->where('status',1)
                    ->where('approve',1)
                    ->where('departure_ownner','!=', '')
                    ->whereDate('start_date', '>=', date("Y-m-d"))
                    ->orderBy('departure_ownner', 'ASC')
                    ->distinct()
                    ->select('departure_ownner as publisher')
                    ->get(10);
        }
        if(count($publishers)>0)
        {
            $status = array(
                'error' => false,
                'publisher' => $publishers,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'publisher' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }
    //All Booking (Confirmed) List
    public function allDepartureList(Request $request)
    {
        $departures = [];
        $dep_id = DB::table('departures')
                    ->where('tenant_id', auth()->user()->tenant_id)
                    ->where('approve',1)
                    ->where('status',1)
                    ->pluck('id')
                    ->toArray();

        $dep_ids = DB::table('book_departures')
                ->whereIn('departure_id', $dep_id)
                ->distinct()
                ->pluck('departure_id')
                ->toArray();

        if($request->keyword)
        {
            $search = $request->keyword;
            $departures = DB::table('departures')
                    ->whereIn('id', $dep_ids)
                    ->where('title','LIKE',"%$search%")
                    ->distinct()
                    ->select('id as dep_id','title')
                    ->get(10);
           // dd($departures);
        }
        else
        {
            $departures = DB::table('departures')
                    ->whereIn('id', $dep_ids)
                    ->distinct()
                    ->select('id as dep_id','title')
                    ->get(10);
        }
        if(count($departures)>0)
        {
            $status = array(
                'error' => false,
                'departures' => $departures,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'departures' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }

    public function allDeparturePublishers(Request $request)
    {
        $publishers = [];
        $dep_id = DB::table('departures')->where('tenant_id', auth()->user()->tenant_id)->where('approve',1)->where('status',1)
                    ->pluck('id')
                    ->toArray();

        $user_ids = DB::table('book_departures')
                ->whereIn('departure_id', $dep_id)
                ->distinct()
                ->pluck('user_id')
                ->toArray();

        if($request->keyword){
            $search = $request->keyword;
            $publishers = DB::table('users')
                    ->whereIn('id', $user_ids)
                    ->where('company_name','LIKE',"%$search%")
                    ->distinct()
                    ->select('tenant_id as publisher','company_name')
                    ->get(10);
        }
        else
        {
            $publishers = DB::table('users')
                    ->whereIn('id', $user_ids)
                    ->distinct()
                    ->select('tenant_id as publisher','company_name')
                    ->get(10);
        }
        if(count($publishers)>0)
        {
            $status = array(
                'error' => false,
                'publisher' => $publishers,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'publisher' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }

    //My Booking (Confirmed) List
    public function myBookedDepartureList(Request $request)
    {
        $departures = [];

        $dep_ids = DB::table('book_departures')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->distinct()
                ->pluck('departure_id')
                ->toArray();

        if($request->keyword){
            $search = $request->keyword;
            $departures = DB::table('departures')
                    ->whereIn('id', $dep_ids)
                    ->where('title','LIKE',"%$search%")
                    ->distinct()
                    ->select('id as dep_id','title')
                    ->get(10);
        }
        else
        {
            $departures = DB::table('departures')
                    ->whereIn('id', $dep_ids)
                    ->distinct()
                    ->select('id as dep_id','title')
                    ->get(10);
        }
        if(count($departures)>0)
        {
            $status = array(
                'error' => false,
                'departures' => $departures,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'departures' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }

    public function myBookedDeparturePublishers(Request $request)
    {
        $publishers = [];

        $dep_ids = DB::table('book_departures')
                ->where('tenant_id', auth()->user()->tenant_id)
                ->distinct()
                ->pluck('departure_id')
                ->toArray();

        if($request->keyword)
        {
            $search = $request->keyword;
            $publishers = DB::table('departures')
                    ->whereIn('id', $dep_ids)
                    ->where('company_name','LIKE',"%$search%")
                    ->distinct()
                    ->select('tenant_id as publisher','company_name')
                    ->get(10);
        }
        else
        {
            $publishers = DB::table('departures')
                    ->whereIn('id', $dep_ids)
                    ->distinct()
                    ->select('tenant_id as publisher','company_name')
                    ->get(10);
        }
        if(count($publishers)>0)
        {
            $status = array(
                'error' => false,
                'publisher' => $publishers,
                'message' =>"Success"
            );
            return Response($status,200);
        }
        else
        {
            $status = array(
                'error' => false,
                'publisher' => [],
                'message' =>"Data not found!"
            );
            return Response($status,200);
        }
    }
}
