<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User; 
use App\Notification;

class NotificationController extends Controller
{
    //Token Add
    public function updateFCMToken(Request $request){
        try{
            $user_id = auth()->user()->id;
            $fcmToken = User::find($user_id);
            $fcmToken->fcm_token = $request->token;
            $fcmToken->save();
            $this->getRecentNotification();
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    function getRecentNotification(){
        $user_id = auth()->user()->id;        
        $deviceToken = User::where('id',$user_id)->whereNotNull('fcm_token')->value('fcm_token');
        $allNotifications = Notification::where(['user_id'=>$user_id, 'status_notify'=>0])->get();
        foreach($allNotifications as $notifData){
            $this->sendNotification($deviceToken,$notifData);
            $statusUpdate = Notification::find($notifData->id);
            $statusUpdate->status_notify = 1;
            $statusUpdate->save();
            sleep(7);
        }

    }

    public function sendNotification($deviceToken,$data){
        $payload = array(
            "to"=>$deviceToken,
            "data"=>array("body" => $data->body, "title" => $data->title,"click_action"=>$data->url_1),
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
    }

    public function getNotification(Request $request){
        $user_id = auth()->user()->id;
        $notifications = Notification::where('user_id', $user_id)
            ->orderBy('created_at','DESC')->paginate(25);

        $total = DB::table('notifications')->where('user_id',$user_id)->count();
        $nAll = Notification::where('user_id', $user_id)->get();
        foreach ($nAll as $key => $value) {
            $viewStatusChange = Notification::find($value->id);
            $viewStatusChange->status_view = 1;
            $viewStatusChange->save();
        }
        return view('notification.notification',compact('notifications','total'));
    }

    public function changeStatusNotification(Request $request, $id){
        
        $viewStatusChange = Notification::find($id);
        $viewStatusChange->status_view = 1;
        $viewStatusChange->save();
        $status = [
            'url'=> $viewStatusChange->url_1,
        ];
        return response()->json($status); 
    }
}
