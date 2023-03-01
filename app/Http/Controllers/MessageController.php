<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Departure;
use Twilio\Rest\Client;
use App\ChatChannel;
use App\TwililioUserSid;
use Auth;
use App\ChatWebhookResponse;
use App\Helpers\MailHelper;
use Mail;
use App\Notification;
use App\Traits\FireBaseNotification;
class MessageController extends Controller
{
    use FireBaseNotification;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        // $users = User::where('id', '<>', $request->user()->id)->get();
        $user_id = Departure::where('id', $id)->select('id','title','departure_ownner','user_id','contact_person_id')->first();
        $users = User::where('id', $user_id->contact_person_id)->get();

        return view('messages.index', compact('users','user_id'));
    }

    public function chat(Request $request, $id, $ids)
    { 
        $authUser = $request->user();
        $otherUser = User::find(explode('-', $ids)[1]);
        $user_id = Departure::where('id', $id)->select('id','title','departure_ownner','user_id','contact_person_id')->first();
        $users = User::where('id', $user_id->contact_person_id)->get();


     //hecking if users convo channel exists:
        $dep_user_ids=explode('-', $ids);
        $twillow_unique_id='';
        $id1=$dep_user_ids[0]?(int)$dep_user_ids[0]:0;
        $id2=$dep_user_ids[1]?(int)$dep_user_ids[1]:0;

        $ChatChannel_check_1=ChatChannel::where('user_id_1',$id1)->where('user_id_2',$id2)->first();
        $ChatChannel_check_2=ChatChannel::where('user_id_1',$id2)->where('user_id_2',$id1)->first();
        $create_new_channel=false;

       if($ChatChannel_check_1==null && $ChatChannel_check_2==null){
       
            $twillow_unique_id='Test-'.rand(1,55).$ids;
            $ChatChannel=new ChatChannel();
            $ChatChannel->dep_id=$id;
            $ChatChannel->dep_user_id=$user_id->contact_person_id;
            $ChatChannel->user_id_1=$id1;
            $ChatChannel->user_id_2=$id2;
            $ChatChannel->unique_id=$twillow_unique_id;
            $ChatChannel->save();
            $create_new_channel=true;
       }elseif($ChatChannel_check_1){
           $twillow_unique_id=$ChatChannel_check_1->unique_id;
            $create_new_channel=false;
         
       }elseif($ChatChannel_check_2){
           $twillow_unique_id=$ChatChannel_check_2->unique_id;
            $create_new_channel=false;
         
       }
       if($create_new_channel){ 
        $this->create_channel($twillow_unique_id,$authUser,$otherUser);
       }else{ 

       } 

       $unique_id['id']=$twillow_unique_id;

        return view('messages.chat', compact('users', 'otherUser','user_id','unique_id'));
    }

    function create_channel($ids,$authUser,$otherUser){



        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

        // Fetch channel or create a new one if it doesn't exist
        try {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels
                ->create([
                    'uniqueName' => $ids,
                    'type' => 'private',
                ]);

            $ChatChannel=ChatChannel::where('unique_id',$ids)->first();
            $ChatChannel->channel_id=$channel->sid;
            $ChatChannel->save();
        }

        // Add first user to the channel
        try {
           $member1 =  $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members($authUser->email)
                ->fetch();
                $this->add_twililio_userSid($otherUser,$member1->sid);
        } catch (\Twilio\Exceptions\RestException $e) {
            $member = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members
                ->create($authUser->email);
                $this->add_twililio_userSid($authUser,$member->sid);

        }

        // Add second user to the channel
        try {
            $member2 = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members($otherUser->email)
                ->fetch();
                $this->add_twililio_userSid($otherUser,$member2->sid);
        } catch (\Twilio\Exceptions\RestException $e) {
           $member =  $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->members
                ->create($otherUser->email);
                $this->add_twililio_userSid($otherUser,$member->sid);
        }


    }
    public function add_twililio_userSid($user,$s_id)
    {
        $twilio_user=TwililioUserSid::where('user_email',$user->email)->first();
        if($twilio_user){

        }else{
            $twililio_user=new TwililioUserSid;
            $twililio_user->user_id=$user->id;
            $twililio_user->user_email=$user->email;
            $twililio_user->s_id=$s_id;
            $twililio_user->save();
        }
    }
     public function check_chat_channel($id,Request $request)
    { 
        $authUser = auth::user(); 
        $user_id = Departure::where('id', $id)->select('id','title','departure_ownner','user_id','contact_person_id')->first();       
        $users = User::where('id', $user_id->contact_person_id)->first();
        $id1=auth::user()->id;
        $id2=$user_id->contact_person_id; 
        $ids=$id1.'-'.$id2;
 
        $twillow_unique_id=''; 
 


        $ChatChannel_check_1=ChatChannel::where('user_id_1',$id1)->where('user_id_2',$id2)->where('dep_id',$id)->first();
        $ChatChannel_check_2=ChatChannel::where('user_id_1',$id2)->where('user_id_2',$id1)->where('dep_id',$id)->first();
        $create_new_channel=false;

       if($ChatChannel_check_1==null && $ChatChannel_check_2==null){
        
            $twillow_unique_id='Dep-'.$id.'_U-'.$id1.'_U-'.$id2.'_Chat';
            $ChatChannel=new ChatChannel();
            $ChatChannel->dep_id=$id;
            $ChatChannel->dep_user_id=$id2;
            $ChatChannel->user_id_1=$id1;
            $ChatChannel->user_id_2=$id2;
            $ChatChannel->unique_id=$twillow_unique_id;
            $ChatChannel->save();
            $create_new_channel=true;
       }elseif($ChatChannel_check_1){
           $twillow_unique_id=$ChatChannel_check_1->unique_id;
            $create_new_channel=false;
         
       }elseif($ChatChannel_check_2){
           $twillow_unique_id=$ChatChannel_check_2->unique_id;
            $create_new_channel=false;
         
       }
       if($create_new_channel && !is_null($users)){ 
        $this->create_channel($twillow_unique_id,$authUser,$users);
       }else{ 

       } 
       $chatDetails=ChatChannel::join('departures','departures.id','chat_channels.dep_id')->join('users as user1','user1.id','chat_channels.user_id_1')
        ->select('user1.name as user_name_1','user2.name as user_name_2','user1.name as user_first_name_1','user1.last_name as user_last_name_1',
            'user2.name as user_first_name_2','user2.last_name as user_last_name_2','chat_channels.*','departures.title as dep_name')
        ->join('users as user2','user2.id','chat_channels.user_id_2')->where('chat_channels.unique_id',$twillow_unique_id)->first(); 
            if($chatDetails){
                if($chatDetails->user_last_name_1==null){
                   $chatDetails->user_name_1=$chatDetails->user_first_name_1;
                }else{
                  $chatDetails->user_name_1=$chatDetails->user_first_name_1.' '.$chatDetails->user_last_name_1;

                }
                if($chatDetails->user_last_name_2==null){
                   $chatDetails->user_name_2=$chatDetails->user_first_name_2;
                }else{
                  $chatDetails->user_name_2=$chatDetails->user_first_name_2.' '.$chatDetails->user_last_name_2;

                } 
            }
           
       $unique_id=$twillow_unique_id; 
       $show_chat=true; 
       $data['user']=$users;
       $data['unique_id']=$unique_id;
       $data['show_chat']=$show_chat; 
       $data['chatDetails']=$chatDetails; 
       return response()->json($data);

    }
    function chat_room(){
        $logged_in_user_id=auth::user()->id;
        $first_chat=array();

         $ChatChannel_check_1=ChatChannel::where('user_id_1',$logged_in_user_id)->pluck('id')->toArray();
        $ChatChannel_check_2=ChatChannel::where('user_id_2',$logged_in_user_id)->pluck('id')->toArray();
 
        $all_channel_id=array_merge($ChatChannel_check_1,$ChatChannel_check_2); 
       // dd($all_channel_id);
        $all_channel_ids=$this->get_all_channels($all_channel_id);
        $all_channel=$this->get_chat_channel_recent($all_channel_ids);

 
          $first_chat=array();
        if(count($all_channel)>0){
            $i=0;
            foreach($all_channel as $channel){
                if($channel->user_last_name_1==null){
                   $channel->user_name_1=$channel->user_first_name_1;
                }else{
                  $channel->user_name_1=$channel->user_first_name_1.' '.$channel->user_last_name_1;

                }
                if($channel->user_last_name_2==null){
                   $channel->user_name_2=$channel->user_first_name_2;
                }else{
                  $channel->user_name_2=$channel->user_first_name_2.' '.$channel->user_last_name_2;

                }
                 
            }
            $first_chat=$all_channel[0]; 
            
        } 

         return view('messages.chat-room', compact('all_channel','first_chat'));
    }
    function get_all_channels($all_channel_id){
        $all_channel=ChatChannel::join('departures','departures.id','chat_channels.dep_id')
                ->leftjoin('chat_webhook_responses','chat_webhook_responses.channel_id','chat_channels.channel_id')
                ->join('users as user1','user1.id','chat_channels.user_id_1')
                ->join('users as user2','user2.id','chat_channels.user_id_2') 
                ->select('user1.name as user_first_name_1','user1.last_name as user_last_name_1','user1.tenant_id as user_tenant_id_1',
                    'user2.name as user_first_name_2','user2.last_name as user_last_name_2','user2.tenant_id as user_tenant_id_2',
                     'chat_channels.id','chat_channels.unique_id','chat_channels.dep_id','chat_channels.user_id_1','chat_channels.user_id_2','departures.title as dep_name',
                     'departures.description as dep_description',
                     'departures.start_date as start_date',
                     'departures.id as dep_id',
                     'departures.no_of_nights as no_of_nights',
                     'departures.company_name as dep_company_name',
                     'departures.no_of_days as no_of_days',
                     'departures.company_name as dep_company_name', 
                 )
                  ->whereIn('chat_channels.id',$all_channel_id)
                //  ->groupBy('chat_channels.unique_id')
                  ->orderBy('chat_webhook_responses.created_at','DESC') 
                  ->get(); 
                  $all_chnl_id=[]; 
                  foreach($all_channel as $chann){

                    $user_details_1=User::where('tenant_id',$chann->user_tenant_id_1)
                                    ->where('main_user_type',1)
                                    ->where('user_type',0)->value('logo');
                    $user_details_2=User::where('tenant_id',$chann->user_tenant_id_2)
                                    ->where('main_user_type',1)
                                    ->where('user_type',0)->value('logo'); 
                                    $chann->user_logo_1='';
                                    $chann->user_logo_2='';
                                    if($user_details_1!=null){
                                      $chann->user_logo_1=$user_details_1;  
                                    }
                                    if($user_details_2!=null){
                                      $chann->user_logo_2=$user_details_2;  
                                    } 
                   
                    if(array_search($chann->id,$all_chnl_id)<-1){
                        array_push($all_chnl_id,$chann->id);
                    }

                  } 
                  //dd($all_chnl_id);
          return $all_chnl_id;
}

function get_chat_channel_recent($ids){
        $logged_in_user_id=auth::user()->id;
      $all_channel=ChatChannel::join('departures','departures.id','chat_channels.dep_id')
               // ->leftjoin('chat_webhook_responses','chat_webhook_responses.channel_id','chat_channels.channel_id')
                ->join('users as user1','user1.id','chat_channels.user_id_1')
                ->join('users as user2','user2.id','chat_channels.user_id_2') 
                ->select('user1.name as user_first_name_1','user1.last_name as user_last_name_1','user1.tenant_id as user_tenant_id_1',
                    'user2.name as user_first_name_2','user2.last_name as user_last_name_2','user2.tenant_id as user_tenant_id_2',
                     'chat_channels.id','chat_channels.unique_id','chat_channels.channel_id','chat_channels.dep_id','chat_channels.user_id_1','chat_channels.user_id_2','departures.title as dep_name',
                     'departures.description as dep_description',
                     'departures.start_date as start_date',
                     'departures.id as dep_id',
                     'departures.no_of_nights as no_of_nights',
                     'departures.company_name as dep_company_name',
                     'departures.no_of_days as no_of_days',
                     'departures.company_name as dep_company_name', 
                 )
                  ->whereIn('chat_channels.id',$ids)
                //  ->groupBy('chat_channels.unique_id')
                  //->orderBy('chat_webhook_responses.created_at','DESC') 
                  ->get(); 
                  $all_chnl_id=[]; 
                  foreach($all_channel as $chann){

                    $user_details_1=User::where('tenant_id',$chann->user_tenant_id_1)
                                    ->where('main_user_type',1)
                                    ->where('user_type',0)->value('logo');
                    $user_details_2=User::where('tenant_id',$chann->user_tenant_id_2)
                                    ->where('main_user_type',1)
                                    ->where('user_type',0)->value('logo'); 
                    $chann->user_logo_1='';
                    $chann->user_logo_2='';
                    if($user_details_1!=null){
                      $chann->user_logo_1=$user_details_1;  
                    }
                    if($user_details_2!=null){
                      $chann->user_logo_2=$user_details_2;  
                    } 
                    $msg_log = ChatWebhookResponse::where('channel_id',$chann->channel_id)->where('from_user_id','!=',$logged_in_user_id)
                        ->where('status',0) 
                        ->get();
                    $chann->count_message = count($msg_log);
                    

                  }  
          return $all_channel;
}
    function chat_room_details($id){
      //  dd($id);
        $logged_in_user_id=auth::user()->id;
        $first_chat=array(); 
        $channel_details= ChatChannel::find($id);  
        if($channel_details->user_id_1==$logged_in_user_id){
            $other_user_id=$channel_details->user_id_2;
        }else{
            $other_user_id=$channel_details->user_id_1;            
        }    
       $this->setChatNotification($logged_in_user_id,$other_user_id,$id);
       // dd($channel_details); 
         $first_chat=ChatChannel::join('departures','departures.id','chat_channels.dep_id')
        ->join('users as user1','user1.id','chat_channels.user_id_1')
        ->join('users as user2','user2.id','chat_channels.user_id_2') 
        ->select('user1.name as user_first_name_1','user1.last_name as user_last_name_1','user1.tenant_id as user_tenant_id_1',
            'user2.name as user_first_name_2','user2.last_name as user_last_name_2','user2.tenant_id as user_tenant_id_2',
             'chat_channels.id','chat_channels.unique_id', 'chat_channels.channel_id', 'chat_channels.dep_id','chat_channels.user_id_1','chat_channels.user_id_2','departures.title as dep_name',
             'departures.description as dep_description',
             'departures.start_date as start_date',
             'departures.id as dep_id',
             'departures.dep_id as depart_id',
             'departures.no_of_nights as no_of_nights',
             'departures.no_of_days as no_of_days',
             'departures.company_name as dep_company_name')
          ->where('chat_channels.id',$id) 
          ->first(); 
        if($first_chat){ 

          //update status of webhook
          $webhook= ChatWebhookResponse::where('channel_id',$first_chat->channel_id)->where('from_user_id','!=',$logged_in_user_id)->get();
          foreach($webhook as $web){
                  $webhook_data= ChatWebhookResponse::find($web->id);
                  $webhook_data->status=1;
                  $webhook_data->save();  
          }

           $ChatChannel_check_1=ChatChannel::where('user_id_1',$logged_in_user_id)->pluck('id')->toArray();
            $ChatChannel_check_2=ChatChannel::where('user_id_2',$logged_in_user_id)->pluck('id')->toArray();
     
            $all_channel_id=array_merge($ChatChannel_check_1,$ChatChannel_check_2); 
             
            $all_channel_ids=$this->get_all_channels($all_channel_id);
            $all_channel=$this->get_chat_channel_recent($all_channel_ids);
          


            if($all_channel){
                foreach($all_channel as $channel){
                    if($channel->user_last_name_1==null){
                       $channel->user_name_1=$channel->user_first_name_1;
                    }else{
                      $channel->user_name_1=$channel->user_first_name_1.' '.$channel->user_last_name_1;

                    }
                    if($channel->user_last_name_2==null){
                       $channel->user_name_2=$channel->user_first_name_2;
                    }else{
                      $channel->user_name_2=$channel->user_first_name_2.' '.$channel->user_last_name_2;

                    }
                     
                }
        } 

          if($first_chat->user_last_name_1==null){
               $first_chat->user_name_1=$first_chat->user_first_name_1;
            }else{
              $first_chat->user_name_1=$first_chat->user_first_name_1.' '.$first_chat->user_last_name_1;

            }
            if($first_chat->user_last_name_2==null){
               $first_chat->user_name_2=$first_chat->user_first_name_2;
            }else{
              $first_chat->user_name_2=$first_chat->user_first_name_2.' '.$first_chat->user_last_name_2;
            }
            $user_details_1=User::where('tenant_id',$first_chat->user_tenant_id_1)->where('main_user_type',1)
                ->where('user_type',0)->value('logo');
            $user_details_2=User::where('tenant_id',$first_chat->user_tenant_id_2)->where('main_user_type',1)
                ->where('user_type',0)->value('logo'); 
                $first_chat->user_logo_1='';
                $first_chat->user_logo_2='';
                if($user_details_1!=null){
                  $first_chat->user_logo_1=$user_details_1;  
                }
                if($user_details_2!=null){
                  $first_chat->user_logo_2=$user_details_2;  
                } 
           return view('messages.chat-room', compact('all_channel','first_chat'));
        }else{
            return view('404');
        } 
        
    }
    public function setChatNotification($sender_id,$receiver_id,$chat_id){
               
                $getData = MailHelper::setMailConfig();             
                $sender_user = User::where('id',$sender_id)->first();
                $receiver_users = User::where('id',$receiver_id)->first();
               
                //Notification
                $noti_save = new Notification;
                $noti_save->title = 'Departure cloud  - Chat Notification';
                $noti_save->body = 'Mr/Ms '.$sender_user->name.' '.$sender_user->last_name.' from '.$sender_user->company_name.' tried to contact you in departure cloud chat.';

                $noti_save->body_html =  'Mr/Ms '.$sender_user->name.' '.$sender_user->last_name.' from '.$sender_user->company_name.' tried to contact you in departure cloud chat.';
                $noti_save->user_id = $receiver_id;
                $noti_save->type = "Chat";
                $noti_save->url_1 =url('chat_room').'/'.$chat_id;
                $noti_save->save();
                
                $last_body_sup = $noti_save->body;
                $last_title_sup = $noti_save->title;
                $last_link_sup = $noti_save->url_1;

                $firebaseToken = User::where('id',$receiver_users->user_id)->whereNotNull('fcm_token')->value('fcm_token');
                $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_sup, $last_body_sup, $last_link_sup,$noti_save->id); 

                Mail::send('mail.chat_room_notification', [ 'r_user'=>$receiver_users ,'s_user'=>$sender_user,'last_link_sup'=>$last_link_sup], function ($m) use ($receiver_users, $getData) {
                    $m->from($getData['from_mail'], $getData['from_name']);
                    $m->to($receiver_users->email);
                    //$m->to('raj.kumar@watconsultingservices.com');
                    $m->subject('Departure cloud  - Chat Notification');
                });
    }
}
