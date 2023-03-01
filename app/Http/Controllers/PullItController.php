<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PullItController extends Controller
{
    public function getCountryData(Request $request){
        $country_data = $request->countries;
        $country_data = json_decode($country_data, true);
        $country_data = $country_data['country'];
        $country_data['s3_url']= "https://pullit-bucket.s3.us-west-2.amazonaws.com/";
        //dd($country_data);
        return view('pullIT.country',compact('country_data'));
    }

    public function getDestinationData(Request $request){
        $destinations = $request->destinations;
        $destinations = json_decode($destinations, true);
        $destinations = $destinations['destination'];
        $destinations['s3_url']= "https://pullit-bucket.s3.us-west-2.amazonaws.com/";
        //$weather = $this->weather($destinations['latitude'],$destinations['longitude']);
        $url = "https://api.openweathermap.org/data/2.5/weather?appid=91d2036228528ad1c20721eac266aa6e&lat=".$destinations['latitude']."&lon=".$destinations['longitude']."";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
           "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        //dd($resp);
        // +++++++++++++++++++++
        $destinations['weathers'] = json_decode($resp, true);
       //dd($destinations);
        return view('pullIT.destination',compact('destinations'));
    }

    public function getPoiData(Request $request){
        $pois = $request->pois;
        $pois = json_decode($pois, true);
        $pois = $pois['poi'];
        $pois['s3_url']= "https://pullit-bucket.s3.us-west-2.amazonaws.com/";
        //$weather = $this->weather($pois['latitude'],$pois['longitude']);
        $url = "https://api.openweathermap.org/data/2.5/weather?appid=91d2036228528ad1c20721eac266aa6e&lat=".$pois['attraction_latitude']."&lon=".$pois['attraction_longitude']."";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
           "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        //dd($resp);
        // +++++++++++++++++++++
        $pois['weathers'] = json_decode($resp, true);
        //dd($pois);
        return view('pullIT.poi',compact('pois'));
    }

    // function weather($lat, $long){
    //     //Weather
    //     $url = "https://api.openweathermap.org/data/2.5/weather?appid=91d2036228528ad1c20721eac266aa6e&lat=".$lat."&lon=".$long."";

    //     $curl = curl_init($url);
    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //     $headers = array(
    //        "Accept: application/json",
    //     );
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //     $resp = curl_exec($curl);
    //     curl_close($curl);
    //     return $resp;
    // }
}
