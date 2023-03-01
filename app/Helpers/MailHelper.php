<?php
namespace App\Helpers;
use Auth;
use App\User;
use Mail;
use DB;
class MailHelper
{
    public static function setMailConfig(){

        //Get the data from settings table
        $settings = DB::table('smtp_integrations')->where('user_id',auth()->user()->id)->first(); 
        //Set the data in an array variable from settings table
        if($settings){
            //Set the data in an array variable from settings table
            $mailConfig = [
                'transport' => 'smtp',
                'host' => $settings->host,
                'port' => $settings->port,
                'encryption' => 'tls',
                'from' => ['address' =>$settings->from_mail, 'name' => $settings->from_name],
                'reply_to' => ['address' => $settings->reply_to, 'name' => $settings->from_name],
                'username' => $settings->user_name,
                'password' => $settings->password,
                'timeout' => null
            ];
            config(['mail.mailers.smtp' => $mailConfig]);
            $from_name = $settings->from_name;
            $from_mail = $settings->from_mail;
        }else{
            $from_name = "DepartureCloud";
            $from_mail = "info@departurecloud.com";
        }
        $data = ['from_name'=>$from_name, 'from_mail'=>$from_mail];
        return $data;
    }
}