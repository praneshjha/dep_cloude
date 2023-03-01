@extends('layouts.app')
@section('tagSection')
<title>My Profile | Departure Cloud</title>
@endsection
@section('content')
@section('headnav') 
@include('layouts.topnav')
@endsection

  <div class="layout-px-spacing">
    <div class="chat-section layout-top-spacing">
      <div class="widget-content widget-content-area br-6">
                <div class="row layout-spacing">

                    <!-- Content -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-top-spacing">

                        <div class="user-profile layout-spacing">
                            <div class="widget-content widget-content-area">
                               
                                    <h1 class="mb-5">My Profile
                                  
                                    </h1>
                                    
                                
                                <div class=" user-info"> 
                                <div class="row">  
                                <div class="col-lg-6 col-mb-6 col-sm-12">
                                <ul class="list-group">
                                <li class="list-group-item"><b>Company ID</b> : {{Auth::user()->tenant_id}}</li>
                                <li class="list-group-item"><b>Company Name</b> : {{Auth::user()->company_name}}</li>
                                <li class="list-group-item"><b>Contact Name</b> : {{ucfirst(Auth::user()->name)}}</li>
                                </ul>
                                </div>
                                <div class="col-lg-6 col-mb-6 col-sm-12">
                                <ul class="list-group">
                                <div class="text-center user-info">
                                
                                    <img src="@if(isset(Auth::user()->logo)){{ asset('companyLogo/' . Auth::user()->logo) }} @else {{asset('images/no-image.png')}} @endif" alt="avatar" style="width:100px"><br>
                                    <li class="list-group-item">Website : <a href="{{Auth::user()->website}}" style="text-decoration:none">{{Auth::user()->website}}</a></li>
                               
                                </div>
                                </ul>
                                </div>
                                <div class="col-lg-6 col-mb-6 col-sm-12">
                                    <br>
                                <h4>Address</h4>
                                <ul class="list-group">
                                <!-- <li class="list-group-item"><b>Company No.</b> : {{Auth::user()->tenant_id}}</li> -->
                                <li class="list-group-item"><b>City</b> : {{ucfirst(Auth::user()->city)}}</li>
                                <li class="list-group-item"><b>State</b> : {{ucfirst(Auth::user()->state)}}</li>
                                <li class="list-group-item"><b>Country</b> : {{ucfirst(Auth::user()->country)}}</li>
                                <li class="list-group-item"><b>Pin</b> : {{Auth::user()->pin}}</li>
                                <li class="list-group-item"><b>Contact No.</b> : {{Auth::user()->mobile}}</li>
                                <li class="list-group-item"><b>Email Id</b> : {{Auth::user()->email}}</li>
                                </ul>
                                </div>
                               
                                <div class="user-info-list">

                                                                  -->
                                </div>
                            </div>
                        </div>

                    

                            
                         
                        </div>

                        <div class="work-experience layout-spacing ">
                    </div>


                </div>
       </div>
    </div>
</div>




@endsection

@section('footerSection')
<script>
  $('#myModal').modal({
      backdrop: 'static',
      keyboard: false  // to prevent closing with Esc button (if you want this too)
  })
  </script>
@endsection