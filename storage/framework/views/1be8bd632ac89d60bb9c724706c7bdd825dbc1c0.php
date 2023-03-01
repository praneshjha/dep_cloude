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
                <?php if(auth()->user()): ?>
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
                <?php endif; ?>
                <li class="dropdown profiledropdowntop notification-list ">
                    <?php if(Auth::user()): ?>
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
                        <a href="<?php echo e(route('change_password')); ?>" class="dropdown-item notify-item">
                            <i class="fe-lock"></i>
                            <span>Change Password</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span> Logout</span>
                        </a>
                        <form id="frm-logout" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </div>
                    <?php else: ?>
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="<?php echo e(route('login')); ?>" role="button" aria-haspopup="false" aria-expanded="false" id="dc_admin_drop1">
                        <span class="pro-user-name ml-1 btn btn-danger RRR">
                            <i class='fas fa-sign-in-alt'></i> Login
                        </span>
                    </a>
                    <?php endif; ?>
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
            <?php if(auth()->user()): ?>
                <div id="navigation" class="d-lg-flex">
                <ul class="navigation-menu">
                    <?php if(Auth::user()->main_user_type == 0): ?>
                        <li class="">
                            <a href="<?php echo e(route('all_departure')); ?>" class="<?php if(Route::currentRouteName() == 'all_departure'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>Departures</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('my_booking')); ?>" class="<?php if(Route::currentRouteName() == 'my_booking'): ?> active <?php endif; ?>"><i class="mdi mdi-laptop-chromebook"></i> My Bookings</a>
                        </li>
                        <li class="">
                            <a href="<?php echo e(route('profile')); ?>" class="<?php if(Route::currentRouteName() == 'profile'): ?> active <?php endif; ?>"><i class="mdi  mdi-home-variant"></i> Company Profile</a>
                        </li>
                    <?php elseif(Auth::user()->main_user_type == 1): ?>
                        <li class="">
                            <a href="<?php echo e(route('all_departure')); ?>" class="<?php if(Route::currentRouteName() == 'all_departure'): ?> active <?php endif; ?>"><i class="fe-airplay"></i>All Departures</a>
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
            <?php endif; ?>
            
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
</style><?php /**PATH /var/www/departure/resources/views/public_layouts/header.blade.php ENDPATH**/ ?>