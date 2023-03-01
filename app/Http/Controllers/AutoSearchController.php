<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AutoSearchController extends Controller
{
    function fetchData(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $output="";
            // destination
            $dest_data = DB::table('destinations')
                ->where('dest_name', 'LIKE', "%{$query}")
                ->orWhere('region', 'LIKE', "%{$query}")
                ->orWhere('actualname', 'LIKE', "%{$query}")
                ->limit(10)
                ->get();
            
             if(count($dest_data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Destination(s)</p>';
                   $cnt=0;
                    foreach($dest_data as $row)
                    {
                        $route = url("departures/card-view").'?type=11&keyword='.$row->dest_name;

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
                ->where('country_name', 'LIKE', "%{$query}")
                ->orWhere('continent', 'LIKE', "%{$query}")
                ->limit(5)
                ->get();
            
             if(count($country_data)>0){
                   $output .= '<ul class="dropdown-menu"><p class="m-0">Countries</p>';
                   $cnt=0;
                    foreach($country_data as $row)
                    {
                        $route = url("departures/card-view").'?type=12&keyword='.$row->country_name;
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
                ->where('approve',1)
                ->where('status',1)
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
                        $route = url("departures/card-view").'?type=13&keyword='.$row->company_name;
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
                        $route = url("departures/card-view").'?type=14&keyword='.$row->name;
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
                ->where('status',1)
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
                        $route = url("departures/card-view").'?type=15&keyword='.$row->title;
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
