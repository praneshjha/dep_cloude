<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatWebhookResponse;

use Twilio\Rest\Client;
use App\Departure;
use App\User;
use App\ChatChannel;
use Auth;
use Carbon\Carbon;

class WebhookController extends Controller
{
    
    public function webhook_cloud(Request $request)
    {  
        //dd($request->all()); 

       $response_data=json_encode($request->all()); 
        if($request && $request->EventType=='onMessageAdded'){            
               $chat_webhook = new ChatWebhookResponse();
               $chat_webhook->channel_id=$request->ConversationSid;
               $chat_webhook->response_data=json_encode($response_data);
               $chat_webhook->from_user_email=$request->Author;
               $chat_webhook->from_user_name=$this->get_user_name_by_email($request->Author); 
               $chat_webhook->from_user_id=$this->get_user_id_by_email($request->Author); 
               $chat_webhook->status=0;
               $chat_webhook->save(); 
              //$this->get_message_details($chat_webhook->id,$request->ConversationSid,$request->MessageSid);
        }
    }
function get_user_name_by_email($email){
     $user=User::where('email',$email)->first();
     return $user->name .' '.$user->last_name;
}
function get_user_id_by_email($email){
     $user=User::where('email',$email)->first();
     return $user->id;
}
      public function webhook_cloud_pre(Request $request)
    {  
        //dd($request->all());  

        
    }
   public function get_message_details($id,$ConversationSid,$MessageSid){

       
        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
        $message = $twilio->chat->v2->services("IS386ea104de0945eabc4d94a3c77fde0f")->channels($ConversationSid)->messages($MessageSid)
                            ->fetch();

           $chat_webhook = ChatWebhookResponse::find($id); 
        $chat_webhook->to_user =json_encode($message); 
         $chat_webhook->save(); 

    }
    function get_webhook(){  

         $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
         $all_channels=ChatChannel::whereNull('channel_id')->get();

         foreach($all_channels as $chnl){
            echo $chnl->unique_id.'<br>';
            $message = $twilio->chat->v2->services("IS386ea104de0945eabc4d94a3c77fde0f")->channels($chnl->unique_id)
        ->fetch();

            $all_channel=ChatChannel::find($chnl->id);
            $all_channel->channel_id=$message->sid;
            //$all_channel->save();
         } 

         die();
        $message = $twilio->chat->v2->services("IS386ea104de0945eabc4d94a3c77fde0f")->channels("CHb92358e9c43d4afd81b80815b730dea9")->messages("IM21ac6d3e4cd6407f9d903dafea42d0fb")
                            ->fetch();
        $message = $twilio->chat->v2->services("IS386ea104de0945eabc4d94a3c77fde0f")->channels("D-962_U-207_U-217_Chat")
        ->fetch();
         echo '<pre>'; 
            print_r($message->sid);
dd('fgfh');
        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
        $webhook = $twilio->conversations->v1->configuration
                                                 ->webhooks()
                                                 ->fetch();
            echo '<pre>'; 
            print_r($webhook->postWebhookUrl);
    }

    function get_current_user_chat_channels(){
       // $logged_in_user_id=auth::user()->id;
        $logged_in_user_id=133;
        $first_chat=array();

         $ChatChannel_check_1=ChatChannel::where('user_id_1',$logged_in_user_id)->pluck('channel_id')->toArray();
        $ChatChannel_check_2=ChatChannel::where('user_id_2',$logged_in_user_id)->pluck('channel_id')->toArray();
 
        $all_channel_id=array_merge($ChatChannel_check_1,$ChatChannel_check_2); 
       // dd($all_channel_id);
        $all_channel=$this->get_all_channels($all_channel_id);
        echo '<pre>'; 
        print_r($all_channel_id);
            print_r($all_channel);
        // dd($all_channel_id);
    }

    function get_all_channels($all_channel_id){ 
        $all_channel=ChatChannel::join('chat_webhook_responses','chat_webhook_responses.channel_id','chat_channels.channel_id') 
                ->select(
                     'chat_channels.id','chat_channels.unique_id','chat_channels.dep_id','chat_channels.user_id_1','chat_channels.user_id_2',
                     'chat_webhook_responses.from_user_name'
                 )
                  ->whereIn('chat_channels.channel_id',$all_channel_id) 
                  ->get();  
              
          return $all_channel;
     }

     function checkForNewChat(){
         $logged_in_user_id=auth::user()->id;
         $first_chat=array();

         $ChatChannel_check_1=ChatChannel::where('user_id_1',$logged_in_user_id)->pluck('channel_id')->toArray();
        $ChatChannel_check_2=ChatChannel::where('user_id_2',$logged_in_user_id)->pluck('channel_id')->toArray();
 
        $all_channel_id=array_merge($ChatChannel_check_1,$ChatChannel_check_2); 
       // dd($all_channel_id);
        $all_channel=$this->get_recent_channels($all_channel_id); 
        $message=""; 
        if(count($all_channel)){ 
            $a_msg = 'You have new chat message from '.$all_channel[0]->from_user_name;     
           $message= '<div class="alert animated flipInX alert-success alert-dismissible"><strong><i class="far fa-comment-dots mr-1"></i>New Message Alert!  </strong><p>'.$a_msg.'</p><span class="close" data-dismiss="alert"><i class="fa fa-times-circle"></i></span></div>'; 

           $status = [
                'data'=> $all_channel[0],
                'message'=>$message,
                'new_message'=>true,
            ];
        } else{         
            $status = [
                'data'=> [],
                'message'=>$message,
                'new_message'=>false,
            ];
        }
        
        return response()->json($status);
     }

         function get_recent_channels($all_channel_id){ 
            $logged_in_user_id=auth::user()->id;
            $all_channel=ChatChannel::leftjoin('chat_webhook_responses','chat_webhook_responses.channel_id','chat_channels.channel_id') 
                ->select(
                     'chat_channels.id','chat_channels.unique_id','chat_channels.dep_id','chat_channels.user_id_1','chat_channels.user_id_2',
                     'chat_webhook_responses.from_user_name'
                 )
                  ->whereIn('chat_channels.channel_id',$all_channel_id)
                  ->where('chat_webhook_responses.from_user_id','!=',$logged_in_user_id)
                  ->where('chat_webhook_responses.status',0)
                  ->where('chat_webhook_responses.created_at', '>=', Carbon::now()->subMinutes(55)->toDateTimeString())
                  ->orderBy('chat_webhook_responses.created_at','DESC') 
                  ->get();  
              
          return $all_channel;
     }
}
