<?php
namespace App\Traits;
use App\User;
use DB;
use Auth;
use App\Notification;

trait FireBaseNotification {

    /**
     * Localize a date to users timezone
     *
     * @param null $dateField
     * @return Carbon
     */
    public function sendNotificationSupplier($deviceToken,$title,$body,$url,$id)
    {
        $payload = array(
            "to"=>$deviceToken,
            "data"=>array("body" => $body, "title" => $title,"click_action"=>$url),
        );
          
        $headers = array(
          'Authorization:key='.env('FireBase'),
          'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        if(auth()->user()->fcm_token == $deviceToken){
            $statusUpdate = Notification::find($id);
            $statusUpdate->status_notify = 1;
            $statusUpdate->save();
        }
    }

    public function sendNotificationBuyer($deviceToken,$title,$body,$url,$id)
    {
        $payload = array(
            "to"=>$deviceToken,
            "data"=>array("body" => $body, "title" => $title,"click_action"=>$url),
        );
          
        $headers = array(
          'Authorization:key='.env('FireBase'),
          'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        if(auth()->user()->fcm_token == $deviceToken){
            $statusUpdate = Notification::find($id);
            $statusUpdate->status_notify = 1;
            $statusUpdate->save();
        }
    }
}
