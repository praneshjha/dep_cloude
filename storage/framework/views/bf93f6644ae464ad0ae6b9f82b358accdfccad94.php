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
                <li class="dropdown notification-list">
                    <a id="notificationDropdownAlert" class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-bell noti-icon"></i>
                        <span class="badge badge-danger rounded-circle noti-icon-badge"><?php echo e($total_notification); ?></span>
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

                               <?php $__currentLoopData = $notification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notifi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a class="dropdown-item notify-item <?php if($notifi->status_view == 0): ?> active <?php endif; ?>" onclick="showNotification(<?php echo e($notifi->id); ?>)">
                                    <p class="notify-details"><?php echo e($notifi->title); ?></p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small><?php echo $notifi->body_html; ?></small>
                                    </p>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="slimScrollBar" style="background: rgb(158, 165, 171); width: 8px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.33px;">
                            </div>
                            <div class="slimScrollRail" style="width: 8px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;">
                            </div>
                        </div>
                        <a href="<?php echo e(route('get_notification')); ?>" class="dropdown-item text-center text-primary notify-item notify-all">View All <i class="fe-arrow-right"></i></a>
                    </div>
                </li>
                
                <li class="">
                    <a class="nav-link" id="pullit-search-btn" onclick="pullitShow();"><img src="<?php echo e(asset('images/pullit-logo-menu-icon.png')); ?>" alt="icon" class="img-fluid" style="width:32px;"></a>
                </li>
                <li class="dropdown profiledropdowntop notification-list ">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" id="dc_admin_drop1">
                        <img src="<?php if(isset(auth::user()->logo)): ?><?php echo e(asset('companyLogo/' . Auth::user()->logo)); ?> <?php else: ?> <?php echo e(asset('images/no-image.png')); ?> <?php endif; ?>" alt="user-image" class="rounded-circle">
                        <span class="pro-user-name ml-1">
                            <?php echo e(ucfirst(Auth::user()->name)); ?> <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown"  aria-labelledby="dc_admin_drop1">
                        <?php if(Auth::user()->main_user_type == 2): ?>
                        <?php else: ?>
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !
                                    <small> <?php if(Auth::user()->main_user_type == 1): ?> (Supplier) <?php elseif(Auth::user()->main_user_type == 0): ?> (Buyer) <?php endif; ?></small>
                                </h6>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo e(route('chat_room')); ?>" class="dropdown-item notify-item">
                            <i class="far fa-comment-dots"></i>
                            <span>Chat</span>
                        </a>
                        <a href="<?php echo e(route('role')); ?>" class="dropdown-item notify-item">
                            <i class="fe-user"></i>
                            <span>Manage Role</span>
                        </a>
                        <a href="<?php echo e(route('user')); ?>" class="dropdown-item notify-item">
                            <i class="fe-settings"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="<?php echo e(route('change_password')); ?>" class="dropdown-item notify-item">
                            <i class="fe-lock"></i>
                            <span>Change Password</span>
                        </a>
                        <?php if(auth()->user()->tenant_id == 'jwkpdcqmlez1648799148'): ?>
                       
                        <a href="<?php echo e(route('index_mailer')); ?>" class="dropdown-item notify-item">
                            <i class="fe-lock"></i>
                            <span>Mailer Shoot</span>
                        </a>
                        <?php endif; ?>
                        <?php if(auth()->user()->tenant_id == 'jwkpdcqmlez1648799148'): ?>
                        <a href="<?php echo e(route('countries')); ?>" class="dropdown-item notify-item">
                            <i class="fe-airplay"></i><span>Countries</span>
                        </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span> Logout</span>
                        </a>
                        <form id="frm-logout" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </div>
                </li>
            </ul>
            <div class="logo-box">
                <a href="<?php echo e(url('/')); ?>" class="logo text-left">
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('departure-cloud-logo.png')); ?>" alt="DepartureCloud Logo" height="45">
                    </span>
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('departure-cloud-logo.png')); ?>" alt="DepartureCloud Logo" height="35">
                    </span>
                </a>
            </div>
            <div id="navigation" class="d-lg-flex">
                <ul class="navigation-menu">
                    <?php if(Auth::user()->main_user_type == 0): ?>
                        <li class="">
                            <a href="<?php echo e(route('dashboard')); ?>" class="<?php if(Route::currentRouteName() == 'dashboard'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>Departures</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('my_booking')); ?>" class="<?php if(Route::currentRouteName() == 'my_booking'): ?> active <?php endif; ?>"><i class="mdi mdi-laptop-chromebook"></i> My Bookings</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('profile')); ?>" class="<?php if(Route::currentRouteName() == 'profile'): ?> active <?php endif; ?>"><i class="mdi  mdi-home-variant"></i> Company Profile</a>
                        </li>
                    <?php elseif(Auth::user()->main_user_type == 1): ?>
                        <li class="">
                            <a href="<?php echo e(route('dashboard')); ?>" class="<?php if(Route::currentRouteName() == 'dashboard'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>All Departures</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('departure')); ?>" class="<?php if(Route::currentRouteName() == 'departure'): ?> active <?php endif; ?>"><i class="mdi mdi-view-list"></i> My Departures</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('my_booking')); ?>" class="<?php if(Route::currentRouteName() == 'my_booking'): ?> active <?php endif; ?>"><i class="mdi mdi-laptop-chromebook"></i> My Bookings</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('profile')); ?>" class="<?php if(Route::currentRouteName() == 'profile'): ?> active <?php endif; ?>"><i class="mdi mdi-home-variant"></i> Company Profile</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('term_master')); ?>" class="<?php if(Route::currentRouteName() == 'term_master'): ?> active <?php endif; ?>"><i class="mdi mdi-clipboard-text"></i> Terms Master</a>
                        </li>
                    <?php elseif(Auth::user()->main_user_type == 2): ?>
                        <li class="">
                            <a href="<?php echo e(route('all_departure')); ?>" class="<?php if(Route::currentRouteName() == 'all_departure'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>Active Departures <sup class="text-blue"><?php echo e($active); ?></sup></a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('unapproved_departure')); ?>" class="<?php if(Route::currentRouteName() == 'unapproved_departure'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>Departures Pending for Approval <sup class="text-blue"><?php echo e($pending); ?></sup></a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('inactive_depature')); ?>" class="<?php if(Route::currentRouteName() == 'inactive_depature'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>Departed <sup class="text-blue"><?php echo e($departed); ?></sup></a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('user_list')); ?>" class="<?php if(Route::currentRouteName() == 'user_list'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>Users</a>
                        </li>
                        <!-- <li class="">
                            <a href="<?php echo e(route('suplier_list')); ?>" class="<?php if(Route::currentRouteName() == 'suplier_list'): ?> active <?php endif; ?>"><i class="mdi mdi-laptop-chromebook"></i>Suppliers</a>
                        </li> -->
                         <li class="">
                            <a href="<?php echo e(route('all_departure_booking_history')); ?>" class="<?php if(Route::currentRouteName() == 'all_departure_booking_history'): ?> active <?php endif; ?>"><i class="mdi mdi-clipboard-text"></i> Booking History</a>
                        </li>
                         
                        <!-- <li class="">
                            <a href="<?php echo e(route('currency_converion')); ?>" class="<?php if(Route::currentRouteName() == 'currency_converion'): ?> active <?php endif; ?>"><i class="mdi mdi-clipboard-text"></i> Currency Conversion (INR. to USD)</a>
                        </li> -->
                    <?php endif; ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

<!--    <div class="topbar-menu">
        <div class="container-fluid">

        </div>
    </div>-->
</header>

<style type="text/css">
.loaderPullIt {
    background: #000;
    background: radial-gradient(#222, #000);
    bottom: 0;
    left: 0;
    overflow: hidden;
    position: fixed;
    right: 0;
    top: 0;
    z-index: 99999;
    display: none;
    opacity: .5;
}

.loader-inner {
    bottom: 0;
    height: 60px;
    left: 0;
    margin: auto;
    position: absolute;
    right: 0;
    top: 0;
    width: 100px;
}

.loader-line-wrap {
    animation: 
        spin 2000ms cubic-bezier(.175, .885, .32, 1.275) infinite
    ;
    box-sizing: border-box;
    height: 50px;
    left: 0;
    overflow: hidden;
    position: absolute;
    top: 0;
    transform-origin: 50% 100%;
    width: 100px;
}
.loader-line {
    border: 4px solid transparent;
    border-radius: 100%;
    box-sizing: border-box;
    height: 100px;
    left: 0;
    margin: 0 auto;
    position: absolute;
    right: 0;
    top: 0;
    width: 100px;
}
.loader-line-wrap:nth-child(1) { animation-delay: -50ms; }
.loader-line-wrap:nth-child(2) { animation-delay: -100ms; }
.loader-line-wrap:nth-child(3) { animation-delay: -150ms; }
.loader-line-wrap:nth-child(4) { animation-delay: -200ms; }
.loader-line-wrap:nth-child(5) { animation-delay: -250ms; }

.loader-line-wrap:nth-child(1) .loader-line {
    border-color: hsl(0, 80%, 60%);
    height: 90px;
    width: 90px;
    top: 7px;
}
.loader-line-wrap:nth-child(2) .loader-line {
    border-color: hsl(60, 80%, 60%);
    height: 76px;
    width: 76px;
    top: 14px;
}
.loader-line-wrap:nth-child(3) .loader-line {
    border-color: hsl(120, 80%, 60%);
    height: 62px;
    width: 62px;
    top: 21px;
}
.loader-line-wrap:nth-child(4) .loader-line {
    border-color: hsl(180, 80%, 60%);
    height: 48px;
    width: 48px;
    top: 28px;
}
.loader-line-wrap:nth-child(5) .loader-line {
    border-color: hsl(240, 80%, 60%);
    height: 34px;
    width: 34px;
    top: 35px;
}

@keyframes  spin {
    0%, 15% {
        transform: rotate(0);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
<div class="pullit" id="pullit-search">
    <div class="box_model">

      <div class="logo_container d-flex justify-content-between py-3">
        <div class="d-flex align-items-center">
          <div class="logo_img">
            <img src="<?php echo e(asset('images/pullit-logo.png')); ?>" alt="logo" class="img-fluid">
          </div>
          <h1>Pullit</h1>
        </div>
        <div>
          
          <a href="javascript:void(0);" class="cancel" title="Close" onclick="pullitHide();">X<i class="fa-solid fa-xmark"></i></a>
        </div>
      </div>

      <div class="searchBar position-relative">
        <form>
          <input type="search" id="searchKey" placeholder="Enter Country, destination and Point of interest...." onkeyup="findKey();" autocomplete="off">
          <div id="resultSearchKey">
            
          </div>
        </form>
      </div>
      <div id="pullItResponse">
      </div>
      <div class="loaderPullIt">
        <div class="loader-inner">
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
        </div>
     </div>
    </div>
  </div>
<script>
    function showNotification(id){
        //alert(id);
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "/notification-status-change/"+id,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert(data.url);
               window.location = data.url;
            },
            errors: function () {

            }

        });
    }
    // $(document).ready(function (){
    //     $('#header_search').on('click', function (e) {
    //         $('#search-dropdown').toggleClass('d-block');
    //     });
    // });
</script><?php /**PATH /var/www/departure/resources/views/layouts/header.blade.php ENDPATH**/ ?>