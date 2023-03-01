<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use App\Demo;
use App\Contact;
use Mail;
use Storage;
use App\User;
use App\CopyUser;
use App\Helpers\MailHelper;

class DemoContactController extends Controller
{
    public function demo(Request $request){
        $getData = MailHelper::setMailConfig();
        Mail::send('mail.demo',['name' => $request->name,'company_name'=> $request->company_name,'phone'=> $request->phone,'email'=> $request->email,'url'=>url('/demo')], function ($m) use ($request, $getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to('contact@watconsultingservices.com')
                   ->subject('Departure Cloud - Demo Request');
                });
        $save = new Demo;
        $save->name = $request->name;
        $save->company_name = $request->company_name;
        $save->email = $request->email;
        $save->phone = $request->phone;
        //$save->time = $request->time;
        //$save->day = $request->day;
        $save->save();
        // return back()->with('email_message', 'Email sent successfully.');
        $status = [
            //'url'=> url('/departure/inclusion',$last_id),
            'url'=> url('/thankyou'),
        ];
        //S0.40
        $ip=$_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $urlUser = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
        $country = isset($urlUser['geoplugin_countryName'])? $urlUser['geoplugin_countryName'] : '';
        $city = isset($urlUser['geoplugin_city'])? $urlUser['geoplugin_city'] : '';
        // capture_lead start to tutterflycrm
        $curl_data = json_encode(array("token"=> "ed465d3dbc0183f66314e0503a30db1f2f6cd31a7514de8c46b8186a38163603","lead"=>array("first_name"=>"", "last_name"=>$request->name,"city"=>$city,"country"=>$country, "email"=>$request->email, "mobile"=>$request->phone, "phone"=>$request->phone, "company"=>"","campaign_name"=>"DepartureCloud Demo form", "website"=>"","custom_fields"=>array("lead_source"=>"Web", "products"=>"DepartureCloud","no_of_travellers_handled_annually"=>""))));


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://watc.tutterflycrm.com/tfc/api/capture_lead');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($curl_data))                                                                       
        );
        $result = curl_exec($ch);

            // if (curl_errno($ch)) {
            //     echo 'Error:' . curl_error($ch);
            // }
        curl_close($ch);
        // capture_lead end
        return response()->json($status);


       }

       public function contactUs(Request $request){
        $getData = MailHelper::setMailConfig();
        Mail::send('mail.contact',['name' => $request->name,'company_name'=> $request->company_name,'phone'=> $request->phone,'email'=> $request->email, 'day'=> $request->day,'url'=>url('/contact-us')], function ($m) use ($request, $getData) {
                $$m->from($getData['from_mail'], $getData['from_name']);
                $m->to('contact@watconsultingservices.com')
                    ->subject('Departure Cloud - Contact Us');
                });
        $save = new Contact;
        $save->name = $request->name;
        $save->company_name = $request->company_name;
        $save->email = $request->email;
        $save->phone = $request->phone;
        $save->save();
        // return back()->with('email_message', 'Email sent successfully.');
        $status = [
            //'url'=> url('/departure/inclusion',$last_id),
            'url'=> url('/thankyou'),
        ];
         //S0.40
         $ip=$_SERVER['REMOTE_ADDR'];

         if (!empty($_SERVER['HTTP_CLIENT_IP']))
         {
             $ip = $_SERVER['HTTP_CLIENT_IP'];
         }
         //whether ip is from proxy
         elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
         {
             $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
         }
         //whether ip is from remote address
         else
         {
             $ip = $_SERVER['REMOTE_ADDR'];
         }
 
         $urlUser = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
         $country = isset($urlUser['geoplugin_countryName'])? $urlUser['geoplugin_countryName'] : '';
         $city = isset($urlUser['geoplugin_city'])? $urlUser['geoplugin_city'] : '';
         // capture_lead start to tutterflycrm
         $curl_data = json_encode(array("token"=> "ed465d3dbc0183f66314e0503a30db1f2f6cd31a7514de8c46b8186a38163603","lead"=>array("first_name"=>"", "last_name"=>$request->name,"city"=>$city,"country"=>$country, "email"=>$request->email, "mobile"=>$request->phone, "phone"=>$request->phone, "company"=>"$request->company_name","campaign_name"=>"DepartureCloud Contact form", "website"=>"","custom_fields"=>array("lead_source"=>"Web", "products"=>"DepartureCloud","no_of_travellers_handled_annually"=>""))));
 
 
         $ch = curl_init();
 
         curl_setopt($ch, CURLOPT_URL, 'https://watc.tutterflycrm.com/tfc/api/capture_lead');
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
         curl_setopt($ch, CURLOPT_TIMEOUT, 60);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             'Content-Type: application/json',                                                                                
             'Content-Length: ' . strlen($curl_data))                                                                       
         );
         $result = curl_exec($ch);
 
             // if (curl_errno($ch)) {
             //     echo 'Error:' . curl_error($ch);
             // }
         curl_close($ch);
        
        return response()->json($status);
       }

    public function getUserData(Request $request){
        $data = User::where(['role_id'=>0, 'user_type'=>0,'main_user_type'=>0])
            ->get();
        foreach ($data as $key => $value) {
            $users = new CopyUser;
            $users->name = $value->name;
            $users->last_name = $value->last_name;
            $users->email = $value->email;
            $users->mobile = $value->mobile;
            $users->city = $value->city;
            $users->country = $value->country;
            $users->state = $value->state;
            $users->company_name = $value->company_name;
            $users->save();
        }
    }
}
