<?php

namespace App\Http\Controllers\ApiApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ChatChannel;
use DB;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;

class ChatController extends Controller
{
    public function generateToken(Request $request, $email)
    {
        $token = new AccessToken(
            env('TWILIO_AUTH_SID'),
            env('TWILIO_API_SID'),
            env('TWILIO_API_SECRET'),
            3600,
            $email
        );

        $chatGrant = new ChatGrant();
        $chatGrant->setServiceSid(env('TWILIO_SERVICE_SID'));

        $token->addGrant($chatGrant);


         //$userId=ChatChannel::where('user_id_1',$id)->select('id','user_id_1 as loged_in_user_id','user_id_2 as other_user_id','unique_id','dep_user_id','dep_id','channel_id')->first();
         //if($userId){
            $status = array(
                'error' => false,
                //'channel_id' => $userId->channel_id,
                //'user' => $userId,
                'token' => $token->toJWT(),
                'message' =>"Success!"
            );
        // }else{
        //     $status = array(
        //         'error' => false,
        //         'channel_id' => "",
        //         'user' =>new \stdClass(),
        //         'token' => "",
        //         'message' =>"Success!"
        //     );
        // }
        return Response($status,200);
    }
    
}
