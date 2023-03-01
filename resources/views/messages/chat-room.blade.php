@extends('layouts.app')
@section('tagSection')
    <title>Conversations| Departure Cloud</title>
@endsection
 
@section('content')
<div class="wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
          <h4 class="page-title">Conversations</h4>
          <!-- <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="javascript: void(0);">Chat</a></li>
            </ol>
          </div> -->
        </div>
      </div>
    </div>
    <div class="container chat_container" >
        <div class="row" >
            <div class="col-md-3">
                <div class="card">
                  <div class="card-header left-chat-channel">Channels</div>
        <!-- <ul class="nav nav-tabs justify-content-center" role="tablist" id="exTab1">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#dep_chnnl" role="tab">
                <i class="fe-airplay"></i> Departures
              </a>
            </li> 
             
          </ul> -->
                  <div class="card-body1 chat_user_area">

                       <div class="tab-content p-0">
                         <div class="tab-pane active" id="dep_chnnl" role="tabpanel">
                      @if(count($all_channel)==0)
                        <div class="card-body">
                          <p>No users</p>
                        </div>
                      @else
                          <ul class="list-group list-group-flush" id="channel-ul-section">
                              @foreach ($all_channel as $channel)

                               <li class="list-group-item d-flex justify-content-between align-items-center channel_set_list" id="channel-pp-{{$channel->id}}">

                                  <a  href="{{ route('chat_room_details', [ 'id'=>$channel->id]) }}"  class="d-flex align-items-center">

                                    @if (auth::user()->id==$channel->user_id_1) 
                                        <img src="@if($channel->user_logo_2!='') {{ asset('companyLogo/' . $channel->user_logo_2) }} @else {{asset('avatar.png')}} @endif" alt="" style="width: 40px; height: 40px" class="rounded-circle mr-1" />

                                    @elseif(auth::user()->id==$channel->user_id_2)
                                       <img src="@if($channel->user_logo_1!='') {{ asset('companyLogo/' . $channel->user_logo_1) }} @else {{asset('avatar.png')}} @endif" alt="" style="width: 40px; height: 40px" class="rounded-circle mr-1" /> 

                                    @endif 
                                    <?php 
                                      $msg_count="";
                                      if( $channel->count_message >0){
                                        $msg_count=$channel->count_message;
                                      }

                                    ?>
                                    <div class="ms-3">
                                      @if (auth::user()->id==$channel->user_id_1)    
                                    <p class="fw-bold">{{ $channel->user_name_2 }}
                                    <?php if($msg_count){?>
                                         <sup class='chat_count_m'>{{$msg_count}}</sup>
                                   <?php } ?>

                                </p>
                                    @elseif(auth::user()->id==$channel->user_id_2)
                                      <p class="fw-bold">{{ $channel->user_name_1 }}
                                    <?php if($msg_count){?>
                                         <sup class='chat_count_m'>{{$msg_count}}</sup>
                                   <?php } ?>
                                    </p>
                                    @endif  

                                      <p class="text-muted mb-0 channel_dep">{{$channel->dep_name}}</p>
                                    </div>
                                  </a> 
                                  
                                </li>
                         
                              @endforeach
                          </ul>
                      @endif
                  </div>

                      <div class="tab-pane" id="profile_chnl" role="tabpanel">
                          profile
                         </div>
                        </div>



                       </div>
              </div>
            </div>
            <div class="col-md-6" id="app">
              @if(count($all_channel)==0)
               <div class="card-body">
                          <h3>You are not involve in any chat. Goto departure to chat with any supplier/buyer</h3>
                        </div>
              @else
                <div >
                    <chat-room-component :auth-user="{{ auth()->user() }}" :chat-details="{{$first_chat}}" unique-id="{{ $first_chat->unique_id }}"></chat-room-component>
                </div>
              @endif  
            </div>
            <div class="col-md-3">
                <div class="card">
                   
                  @if($first_chat)
                  <div class="card-body card_dep chat_user_area1"> 


                    <div class="card-header">
                        <div class="card-cover"  ></div>
                             @if (auth::user()->id==$first_chat->user_id_1) 
                                        <img src="@if($first_chat->user_logo_2!='') {{ asset('companyLogo/' . $first_chat->user_logo_2) }} @else {{asset('avatar.png')}} @endif" alt=""  class=" card-avatar" width="100" />

                                    @elseif(auth::user()->id==$first_chat->user_id_2)
                                       <img src="@if($first_chat->user_logo_1!='') {{ asset('companyLogo/' . $first_chat->user_logo_1) }} @else {{asset('avatar.png')}} @endif" alt=""  class=" card-avatar" width="100" /> 
                                    @endif 
                                    @if(auth()->user()->id==$first_chat->user_id_1)
                                    <h1 class="card-fullname">{{$first_chat->user_name_2}}</h1>
                                      @endif 
                                  @if(auth()->user()->id==$first_chat->user_id_2)
                                  <h1 class="card-fullname">{{$first_chat->user_name_1}}</h1>
                                  @endif 
                                    <h2 class="card-jobtitle">{{$first_chat->dep_company_name}}</h2>
                        </div>
                     
                    <div class="details">
                        <h6>
                            <a href="{{route('all_departure_details',$first_chat->dep_id)}}" target="_blank"> {{ $first_chat->dep_name }} </a>
                            <span class="dep_chat_id">({{ $first_chat->depart_id }})</span>
                        </h6>
                        <div class="day-info-"> 
                            <span class="d-block"><strong>Start Date : </strong>{{date('d M, Y', strtotime($first_chat->start_date))}}</span>
                            @if($first_chat->no_of_nights == null || $first_chat->no_of_days == null)
                                
                            @else
                                <strong class="text-dark">{{$first_chat->no_of_nights}}Nights/{{$first_chat->no_of_days}}Days</strong>
                            @endif
                        </div>
                         
                    </div>
                      <p id="info">
                      </p>
                  </div>
                  @endif  
              </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('footerSection')
<script type="text/javascript">
     
  $( document ).ready(function() { 
<?php if($first_chat){?>
    $("#channel-pp-<?php echo $first_chat->id;?>").prependTo("#channel-ul-section");
    $("#channel-pp-<?php echo $first_chat->id;?>").addClass("channel_dep_active");
        $("#channel-pp-<?php echo $first_chat->id;?> .channel_dep").addClass('dep_active'); 
<?php }?> 
  });

</script> 
 
 <style type="text/css">
   

#exTab1 .nav-item .nav-link,
#exTab1 .nav-tabs .nav-link {
    -webkit-transition: all 300ms ease 0s;
    -moz-transition: all 300ms ease 0s;
    -o-transition: all 300ms ease 0s;
    -ms-transition: all 300ms ease 0s;
    transition: all 300ms ease 0s;
}

 

/*#exTab1  [data-toggle="collapse"][data-parent="#accordion"] i {
    -webkit-transition: transform 150ms ease 0s;
    -moz-transition: transform 150ms ease 0s;
    -o-transition: transform 150ms ease 0s;
    -ms-transition: all 150ms ease 0s;
    transition: transform 150ms ease 0s;
}

#exTab1 [data-toggle="collapse"][data-parent="#accordion"][aria-expanded="true"] i {
    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);
}
*/
 




 

#exTab1 .nav-tabs {
    border: 0;
    padding: 7px 0.7rem;
}

#exTab1 .nav-tabs:not(.nav-tabs-neutral)>.nav-item>.nav-link.active {
    box-shadow: 0px 5px 35px 0px rgba(0, 0, 0, 0.3);
}

#exTab1  .card .nav-tabs {
    border-top-right-radius: 0.1875rem;
    border-top-left-radius: 0.1875rem;
}

#exTab1 .nav-tabs>.nav-item>.nav-link {
    color: #888888;
    margin: 0;
    margin-right: 5px;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 30px;
    font-size: 14px;
    padding: 5px 10px;
    line-height: 1.5;
}

.nav-tabs>.nav-item>.nav-link:hover {
    background-color: transparent;
}

.nav-tabs>.nav-item>.nav-link.active {
    background-color: #444;
    border-radius: 30px;
    color: #FFFFFF;
}
 

.nav-tabs.nav-tabs-neutral>.nav-item>.nav-link {
    color: #FFFFFF;
}

.nav-tabs.nav-tabs-neutral>.nav-item>.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
}

  
 

 

 
@media screen and (max-width: 768px) {

   #exTab1  .nav-tabs {
        display: inline-block;
        width: 100%;
        padding-left: 100px;
        padding-right: 100px;
        text-align: center;
    }

    #exTab1 .nav-tabs .nav-item>.nav-link {
        margin-bottom: 5px;
    }
}





 </style>
@endsection