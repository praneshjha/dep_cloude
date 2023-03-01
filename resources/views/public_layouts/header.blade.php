<style type="text/css">
    .dropdown-item.active, .dropdown-item:active {
        /*color: #323a46;
        text-decoration: none;*/
        background-color: transparent;
    }
</style>
<header id="topnav">
    <div class="navbar-custom">
        <div class="container-fluid">
            <ul class="list-unstyled topnav-menu float-right mb-0">
                <li class="dropdown notification-list">
                    <a class="navbar-toggle nav-link">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                </li>
                @if(auth()->user())
                <?php  
                    //use App\User;
                    $date = date("Y-m-d");
                    $active = DB::table('departures')->where('approve',1)
                            ->where('status',1)
                            ->where('company_publish',1)
                            ->whereDate('start_date', '>=', $date)
                            ->count();

                    $pending = DB::table('departures')->where('approve',0)
                            ->where('status',1)
                            ->where('company_publish',1)
                            ->whereDate('start_date', '>=', $date)
                            ->count();

                    $departed = DB::table('departures')->whereDate('start_date', '<', $date)->count();

                     $notification = DB::table('notifications')->where('user_id',auth()->user()->id)->orderBy('status_view', 'ASC')->orderBy('created_at', 'DESC')->take(25)->get();
                     $total_notification = DB::table('notifications')->where('user_id',auth()->user()->id)->where('status_view', 0)->count();
                     //$permission = User::getPermissions();
                ?>
                    <li class="dropdown notification-list">
                    <a id="notificationDropdownAlert" class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-bell noti-icon"></i>
                        <span class="badge badge-danger rounded-circle noti-icon-badge">{{$total_notification}}</span>
                    </a>
                    <div class="dropdown-menu notificationDropdown dropdown-menu-right dropdown-lg" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(51px, 70px, 0px);" x-placement="bottom-end">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-right">
                                </span>Notifications
                            </h5>
                        </div>

                        <div class="slimScrollDiv" >
                            <div class="slimscroll noti-scroll" style="overflow: auto; width: auto; height: 511.494px;">

                               @foreach($notification as $notifi)
                                <a class="dropdown-item notify-item @if($notifi->status_view == 0) active @endif" onclick="showNotification({{$notifi->id}})">
                                    <p class="notify-details">{{$notifi->title}}</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>{!! $notifi->body_html !!}</small>
                                    </p>
                                </a>
                                @endforeach
                            </div>
                            <div class="slimScrollBar" style="background: rgb(158, 165, 171); width: 8px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.33px;">
                            </div>
                            <div class="slimScrollRail" style="width: 8px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;">
                            </div>
                        </div>
                        <a href="{{route('get_notification')}}" class="dropdown-item text-center text-primary notify-item notify-all">View All <i class="fe-arrow-right"></i></a>
                    </div>
                </li>
                <li class="">
                    <a class="nav-link" id="pullit-search-btn" onclick="pullitShow();"><img src="{{ asset('images/pullit-logo-menu-icon.png')}}" alt="icon" class="img-fluid" style="width:32px;"></a>
                </li>
                @endif
                <li class="dropdown profiledropdowntop notification-list ">
                    @if(Auth::user())
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" id="dc_admin_drop1">
                        <img src="@if(isset(auth::user()->logo)){{ asset('companyLogo/' . Auth::user()->logo) }} @else {{asset('images/no-image.png')}} @endif" alt="user-image" class="rounded-circle">
                        <span class="pro-user-name ml-1">
                            {{ucfirst(Auth::user()->name)}} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown"  aria-labelledby="dc_admin_drop1">
                        @if(Auth::user()->main_user_type == 2)
                        @else
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !
                                    <small> @if(Auth::user()->main_user_type == 1) (Supplier) @elseif(Auth::user()->main_user_type == 0) (Buyer) @endif</small>
                                </h6>
                            </div>
                        @endif
                        <a href="{{route('change_password')}}" class="dropdown-item notify-item">
                            <i class="fe-lock"></i>
                            <span>Change Password</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span> Logout</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                    @else
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="{{route('login')}}" role="button" aria-haspopup="false" aria-expanded="false" id="dc_admin_drop1">
                        <span class="pro-user-name ml-1 btn btn-danger RRR">
                            <i class='fas fa-sign-in-alt'></i> Login
                        </span>
                    </a>
                    @endif
                </li>
            </ul>
            <div class="logo-box">
                <a href="{{url('/')}}" class="logo text-left">
                    <span class="logo-lg">
                        <img src="{{ asset('departure-cloud-logo.png') }}" alt="DepartureCloud Logo" height="45">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('departure-cloud-logo.png')}}" alt="DepartureCloud Logo" height="35">
                    </span>
                </a>
            </div>
            @if(auth()->user())
                <div id="navigation" class="d-lg-flex">
                <ul class="navigation-menu">
                    @if(Auth::user()->main_user_type == 0)
                        <li class="">
                            <a href="{{route('all_departure')}}" class="@if(Route::currentRouteName() == 'all_departure') active @endif"><i class="fe-airplay"></i>Departures</a>
                        </li>
                        <li class="">
                            <a href="{{route('my_booking')}}" class="@if(Route::currentRouteName() == 'my_booking') active @endif"><i class="mdi mdi-laptop-chromebook"></i> My Bookings</a>
                        </li>
                        <li class="">
                            <a href="{{route('profile')}}" class="@if(Route::currentRouteName() == 'profile') active @endif"><i class="mdi  mdi-home-variant"></i> Company Profile</a>
                        </li>
                    @elseif(Auth::user()->main_user_type == 1)
                        <li class="">
                            <a href="{{route('all_departure')}}" class="@if(Route::currentRouteName() == 'all_departure') active @endif"><i class="fe-airplay"></i>All Departures</a>
                        </li>
                        <li>
                            <a href="{{route('departure')}}" class="@if(Route::currentRouteName() == 'departure') active @endif"><i class="mdi mdi-view-list"></i> My Departures</a>
                        </li>
                        <li class="">
                            <a href="{{route('my_booking')}}" class="@if(Route::currentRouteName() == 'my_booking') active @endif"><i class="mdi mdi-laptop-chromebook"></i> My Bookings</a>
                        </li>
                        <li class="">
                            <a href="{{route('profile')}}" class="@if(Route::currentRouteName() == 'profile') active @endif"><i class="mdi mdi-home-variant"></i> Company Profile</a>
                        </li>
                        <li class="">
                            <a href="{{route('term_master')}}" class="@if(Route::currentRouteName() == 'term_master') active @endif"><i class="mdi mdi-clipboard-text"></i> Terms Master</a>
                        </li>
                    @elseif(Auth::user()->main_user_type == 2)
                        <li class="">
                            <a href="{{route('all_departure')}}" class="@if(Route::currentRouteName() == 'all_departure') active @endif"><i class="fe-airplay"></i>Active Departures <sup class="text-blue">{{$active}}</sup></a>
                        </li>
                        <li class="">
                            <a href="{{route('unapproved_departure')}}" class="@if(Route::currentRouteName() == 'unapproved_departure') active @endif"><i class="fe-airplay"></i>Departures Pending for Approval <sup class="text-blue">{{$pending}}</sup></a>
                        </li>
                        <li class="">
                            <a href="{{route('inactive_depature')}}" class="@if(Route::currentRouteName() == 'inactive_depature') active @endif"><i class="fe-airplay"></i>Departed <sup class="text-blue">{{$departed}}</sup></a>
                        </li>
                        <li class="">
                            <a href="{{route('user_list')}}" class="@if(Route::currentRouteName() == 'user_list') active @endif"><i class="fe-airplay"></i>Users</a>
                        </li>
                        <!-- <li class="">
                            <a href="{{route('suplier_list')}}" class="@if(Route::currentRouteName() == 'suplier_list') active @endif"><i class="mdi mdi-laptop-chromebook"></i>Suppliers</a>
                        </li> -->
                         <li class="">
                            <a href="{{route('all_departure_booking_history')}}" class="@if(Route::currentRouteName() == 'all_departure_booking_history') active @endif"><i class="mdi mdi-clipboard-text"></i> Booking History</a>
                        </li>
                         
                        <!-- <li class="">
                            <a href="{{route('currency_converion')}}" class="@if(Route::currentRouteName() == 'currency_converion') active @endif"><i class="mdi mdi-clipboard-text"></i> Currency Conversion (INR. to USD)</a>
                        </li> -->
                    @endif
                </ul>
                <div class="clearfix"></div>
            </div>
            @endif
            
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</header>

<style type="text/css">
    .btn-danger.RRR {
        color: #fff;
        background-color: #681f4a;
        border-color: #681f4a;
    }
</style>