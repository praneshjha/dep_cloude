
<?php $__env->startSection('tagSection'); ?>
<link rel="canonical" href="https://www.departurecloud.com/profile/<?php echo e(request()->route('public')); ?>" />
<title><?php echo $users->company_name; ?> | Departure Cloud</title>
<meta name="description" content="Check out here all the Group Tour Packages and Fixed Departures published by <?php echo $users->company_name; ?> at DepartureCloud. Contact Now!" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('headCssSection'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets1/css/dc_allstyle.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Company Profile</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(session('message')): ?>
                <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
                    <div id="success_msg" style="">
                        <?php echo e(session('message')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12 position-relative mb-2">
                    <figure class="profileCover">
                        <img src="<?php if(isset($users->banner_image)): ?> <?php echo e(asset('BannerImage/' . $users->banner_image)); ?> <?php else: ?> <?php echo e(asset('/assets1/images/cover.jpg')); ?> <?php endif; ?>" alt="profileCover" class="img-fixed">
                    </figure>
                    <div class="d-md-flex justify-content-between profilePicSection">
                        <div class="d-flex">
                            <figure>
                                <img src="<?php if(isset($users->logo)): ?><?php echo e(asset('companyLogo/' . $users->logo)); ?> <?php else: ?> <?php echo e(asset('images/no-image.png')); ?> <?php endif; ?>" class="img-fixed" alt="profile-image">
                            </figure>
                            <div class="ml-2 mt-2">
                                <h5 class="text-blue mb-1 mt-2"><?php echo e($users->name); ?></h5>
                                <h3 class="mb-2 mt-0"><?php echo e($users->company_name); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-4">
                    <div class="card-box position-relative">
                        <h3>Company Details</h3>
                        <dl>
                            <dt><i class="fas fa-user"></i>Contact Name</dt>
                            <dd><?php echo e($users->contactName); ?></dd>
                            <dt><i class="fas fa-phone fa-rotate-90"></i> Mobile</dt>
                            <dd><?php echo e($users->mobile); ?></dd>
                            <dt><i class="fas fa-envelope"></i> Email</dt>
                            <dd><a href="mailto:<?php echo e($users->email); ?>"><?php echo e($users->email); ?></a></dd>
                            <div class="socail">
                                <?php if(isset($users->website)): ?>
                                    <dt><i class="fa fa-globe" aria-hidden="true"></i> Website</dt>
                                    <dd><?php echo e($users->website); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($users->facebook)): ?>
                                    <dt><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</dt>
                                    <dd><?php echo e($users->facebook); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($users->twitter)): ?>
                                    <dt><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</dt>
                                    <dd><?php echo e($users->twitter); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($users->instagram)): ?>
                                    <dt><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</dt>
                                    <dd><?php echo e($users->instagram); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($users->youtube)): ?>
                                    <dt><i class="fa fa-youtube-play" aria-hidden="true"></i> YouTube</dt>
                                    <dd><?php echo e($users->youtube); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($users->website)): ?>
                                    <dt><i class="fa fa-pinterest-p" aria-hidden="true"></i> Pinterest</dt>
                                    <dd><?php echo e($users->pinterest); ?></dd>
                                <?php endif; ?>
                                <?php if(isset($users->about)): ?>
                                    <dt><i class="fa fa-info-circle" aria-hidden="true"></i> About</dt>
                                    <dd><?php echo e($users->about); ?></dd>
                                <?php endif; ?>
                            </div>
                            <dt><i class="fa fa-address-card" aria-hidden="true"></i> Address</dt>
                            <dd>
                                <?php if(isset($users->address)): ?>
                                    <?php echo e(ucfirst($users->address)); ?><br>
                                <?php endif; ?>
                                <?php if(isset($users->city)): ?>
                                    <?php echo e(ucfirst($users->city)); ?>,
                                <?php endif; ?>
                                <?php if(isset($users->state)): ?>
                                    <?php echo e(ucfirst($users->state)); ?>,<br>
                                <?php endif; ?>
                                <?php if(isset($users->country)): ?>
                                    <?php echo e(ucfirst($users->country)); ?> -
                                <?php endif; ?>
                                <?php if(isset($users->pin)): ?>
                                    <?php echo e(ucfirst($users->pin)); ?>

                                <?php endif; ?>
                            </dd>
                            <?php if(count($user_dest) > 0): ?>
                                <dt><i class="fas fa-map-marker-alt"></i> Destination of Service</dt>
                                <dd>
                                    <?php $__currentLoopData = $user_dest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($destination->destination_name); ?>(<?php echo e($destination->country); ?>)<?php if((count($user_dest))-1 != $key): ?>
                                            ,
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </dd>
                            <?php endif; ?>
                        </dl>

                    </div>
                </div>
                <?php if(count($departures)>0): ?>
                <div class="col-lg-8 col-xl-8">
                    <div class="row">
                        <?php if(count($departures)> 0 ): ?>
                            <?php $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $new_time = ($departure->hold_duration) + 5;
                                $hold_till = DB::table('hold_departures')->where('departure_id', $departure->id)->get();
                                if (count($hold_till) > 0) {
                                    foreach ($hold_till as $row) {
                                        if ($row->departure_id == $departure->id) {
                                            $hold = $row->hold_till;
                                        }
                                    }
                                } else {
                                    $hold = 0;
                                }

                                $today = date("Y-m-d");
                                $date1 = date_create($today);
                                $date2 = date_create($departure->start_date);
                                $diff = date_diff($date1, $date2);
                                $date = $diff->format("%R%a");

                                if (($hold < $date) && ($departure->available_seat > 0)) {
                                    $popup = '.bd-example-modal-sm';
                                } else {
                                    $popup = 0;
                                }
                                ?>
                                <div class="col-md-6 mb-3" id="GridView">
                                    <div class="card-box ribbon-box">
                                        <?php if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0): ?>
                                            <div class="ribbon-style">
                                                <div class="ribbon ribbon-success float-right">OPEN</div>
                                            </div>
                                        <?php else: ?>
                                            <div class="ribbon-style">
                                                <div class="ribbon ribbon-danger float-right">SOLDOUT</div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mb-21">
                                            <h4 class="card-title">
                                                <?php if(Auth::user()): ?>
                                                <a href="<?php echo e(route('all_departure_details',$departure->id)); ?>">
                                                    <?php echo e($departure->title); ?>

                                                </a>
                                                <?php else: ?>
                                                <a href="<?php echo e(route('login')); ?>" target="_blank">
                                                    <?php echo e($departure->title); ?>

                                                </a>
                                                <?php endif; ?>
                                            </h4>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="day-info- departureID">ID <?php echo e($departure->dep_id); ?></div>
                                                <div class="dep-model-action-btn">
                                                    <?php if($users->main_user_type == 2): ?>
                                                        <?php if(Auth::user()): ?>
                                                        <a href="<?php echo e(route('all_departure_details',$departure->id)); ?>" title="View Deprature" class="dep-model-action-btn"><i class="fa fa-eye"></i></a>
                                                        <?php else: ?>
                                                        <a href="<?php echo e(route('login')); ?>" target="_blank"  title="View Deprature" class="tooltipbubble"><i class="fa fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        <?php if(Auth::user()): ?>
                                                            <?php if(($hold < $date)): ?>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm<?php echo e($departure->id); ?>" title="Hold Units"><i class="fas fa-pause"></i></a>   
                                                            <?php else: ?>
                                                             <a href="javascript:void(0);" title="This Departure Beyond Hold Date" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php if(Auth::user()): ?>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="<?php if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0): ?>.bd-example-modal-sm<?php echo e($departure->id); ?>b <?php endif; ?>"
                                                           title="Book Units"><i class="far fa-calendar-check"></i>
                                                        </a>
                                                        <?php endif; ?>
                                                    <?php elseif($users->main_user_type == 0): ?>
                                                    <?php if(Auth::user()): ?>
                                                        <a href="<?php echo e(route('all_departure_details',$departure->id)); ?>" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                    <?php else: ?>
                                                        <a href="<?php echo e(route('login')); ?>" target="_blank" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                                        <?php if(Auth::user()): ?>
                                                            <?php if(($hold < $date)): ?>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm<?php echo e($departure->id); ?>" title="Hold Units"><i class="fas fa-pause"></i></a>   
                                                            <?php else: ?>
                                                             <a href="javascript:void(0);" title="This Departure Beyond Hold Date" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                            <?php endif; ?>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="<?php if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0): ?>.bd-example-modal-sm<?php echo e($departure->id); ?>b <?php endif; ?>"
                                                               title="Book Units"><i class="far fa-calendar-check"></i></a>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php if(Auth::user()): ?>
                                                            <a href="<?php echo e(route('all_departure_details',$departure->id)); ?>" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(route('login')); ?>" target="_blank" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        <?php if(Auth::user()): ?>
                                                            <?php if(($hold < $date)): ?>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm<?php echo e($departure->id); ?>" title="Hold Units"><i class="fas fa-pause"></i></a>   
                                                            <?php else: ?>
                                                             <a href="javascript:void(0);" title="This Departure Beyond Hold Date" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                            <?php endif; ?>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="<?php if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0): ?>.bd-example-modal-sm<?php echo e($departure->id); ?>b <?php endif; ?>"
                                                               title="Book Units"><i class="far fa-calendar-check"></i></a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="day-info-">
                                            <strong class="d-block text-blue"><?php echo e(date('d M, Y', strtotime($departure->start_date))); ?></strong>
                                            <?php if($departure->no_of_nights == null || $departure->no_of_days == null): ?>
                                            <?php else: ?>
                                                <span class="text-dark font-weight-bold"><?php echo e($departure->no_of_nights); ?> <span class="text-muted">Nights</span> / <?php echo e($departure->no_of_days); ?> <span class="text-muted">Days</span></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="d-flex position-relative">
                                            <?php if($departure->from != null): ?>
                                            <div>
                                                <p class="dept-from-text"><?php echo e($departure->from); ?></p>
                                            </div>
                                            <?php endif; ?>
                                            <?php if($departure->from != null && $departure->ending_at != null): ?>
                                            <div class="position-relative px-2">
                                                <strong style="color:#9f206a;">to</strong>
                                            </div>
                                            <?php endif; ?>
                                            <?php if($departure->ending_at != null): ?>
                                            <div>
                                                <p class="dept-from-text"><?php echo e($departure->ending_at); ?></p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="bg-dept bg-per-pax">
                                            <div class="d-flex justify-content-between">
                                                <ul class="unit-set">
                                                    <!-- <li><?php echo e($departure->total_seat); ?> <span>Total Units</span></li>-->
                                                    <li><?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?> <span>Avl Units</span></li>
                                                </ul>
                                                <p class="price-set">
                                                    <?php echo e($departure->departure_first_price->currency_symbol); ?>  <?php echo e($departure->departure_first_price->price); ?>

                                                    <span>Per PAX</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="modal fade bd-example-modal-sm<?php echo e($departure->id); ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content hold">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-white" id="mySmallModalLabel">Hold Units</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                             class="feather feather-x">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <form role="form" id="HoldDepartureForm_<?php echo e($departure->id); ?>" style="background-color: #fdfdfd;" class="p-1">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12 d-flex align-items-center totalava_unit">
                                                                <?php if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)>0): ?>
                                                                    <div class="bh_units">
                                                                        <input type="hidden" class="form-control" value="<?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?>" readonly>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-0">
                                                                                <h5 class="mb-0">Available Units :</h5>
                                                                                <span class="text-black text-bold"><?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?></span>
                                                                                <input type="hidden" class="form-control" id="" name="available" value="<?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?>" name="available" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="bh_units">
                                                                        <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-0">
                                                                                <h5 class="mb-0">Available Units :</h5>
                                                                                <span class="text-black text-bold"><?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?></span>
                                                                                <input type="hidden" class="form-control" id="" name="available" value="Over Holded:<?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="bh_units">
                                                                    <div class="form-group mb-0">
                                                                        <input type="hidden" name="id" value="<?php echo e($departure['id']); ?>">
                                                                        <input type="hidden" name="current_hours" value="<?php echo e(date('H')); ?>">
                                                                        <input type="hidden" name="current_minutes" value="<?php echo e(date('i')); ?>">
                                                                        <h5 class="mb-0">Hold Till</h5>
                                                                        <span class="text-black text-bold"><?php echo e(date('d-M-Y | h:ia', strtotime("+{$new_time} hours +30 minutes"))); ?></span>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hours" value="<?php echo e($departure['hold_duration']); ?>" readonly>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hold_time" value="<?php echo e(date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))); ?>" readonly>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="auto_release" value="<?php echo e(date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))); ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-none">
                                                                <?php if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)>0): ?>
                                                                    <input type="hidden" class="form-control" value="<?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?>" readonly>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="exampleFormControlInput1">Avl Units</label>
                                                                            <input type="text" class="form-control" id="" name="available" value="<?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?>" name="available" readonly>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
    
                                                                    <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="exampleFormControlInput1">Avl Units</label>
                                                                            <input type="text" class="form-control" id="" name="available" value="Over Holded:<?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="id" value="<?php echo e($departure->id); ?>">
                                                                        <input type="hidden" name="current_hours" value="<?php echo e(date('H')); ?>">
                                                                        <input type="hidden" name="current_minutes" value="<?php echo e(date('i')); ?>">
                                                                        <label for="formGroupExampleInput">Hold Till</label>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hours" value="<?php echo e($departure->hold_duration); ?>" readonly>
                                                                        <input type="text" class="form-control" id="exampleFormControlSelect2" name="hold_time" value="<?php echo e(date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))); ?>" readonly>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="auto_release" value="<?php echo e(date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))); ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <?php if(in_array(32, json_decode($columns))): ?>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Sharing</label>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <?php if(in_array(33, json_decode($columns))): ?>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Flight Class</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Passenger Type</label>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <?php if(in_array(35, json_decode($columns))): ?>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Hotel Type</label>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <?php if(in_array(36, json_decode($columns))): ?>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Transport Type</label>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Airport Transfers</label>
                                                                    </div>
                                                                </div>
                                                                <?php if(in_array(38, json_decode($columns))): ?>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Meal Plan</label>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Minimum Pax</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Required Units</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Price</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bookingMdodal">
                                                            <div class="row">
                                                                <?php $__currentLoopData = $departure['departure_price']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <input type="hidden" name="sairing[]" value="<?php echo e($require['sharing']); ?>">
                                                                    <div class="col-md-6">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <?php if(in_array(32, json_decode($columns))): ?>
                                                                                    <div class="bh_units">
                                                                                        <span>Room Sharing</span>
                                                                                        <strong><?php echo e(ucfirst($require['sharing'])); ?></strong>
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if(in_array(33, json_decode($columns))): ?>
                                                                                    <div class="bh_units">
                                                                                        <span>Flight Class</span>
                                                                                        <strong><?php echo e(ucfirst($require['flight_class'])); ?></strong>
                                                                                    </div>
                                                                                    <div class="bh_units">
                                                                                        <span>Passenger Type</span>
                                                                                        <strong><?php echo e($require['passenger']); ?></strong>
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                    
                                                                                </div>
                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <?php if(in_array(35, json_decode($columns))): ?> 
                                                                                    <div class="bh_units">
                                                                                        <span>Hotel Type</span>
                                                                                        <strong><?php echo e($require['hotel_type']); ?></strong>
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if(in_array(36, json_decode($columns))): ?>
                                                                                    <div class="bh_units">
                                                                                        <span>Transport Type</span>
                                                                                        <strong><?php echo e($require['transport_type']); ?></strong>
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                    <div class="bh_units">
                                                                                        <span>Airport Transfers</span>
                                                                                        <strong><?php echo e($require['airport_transfers']); ?></strong>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <?php if(in_array(38, json_decode($columns))): ?>
                                                                                    <div class="bh_units">
                                                                                        <span>Meal Plan</span>
                                                                                        <strong><?php echo e($require['meal_type']); ?></strong>
                                                                                    </div>
                                                                                    <?php endif; ?>
                                                                                    <div class="bh_units">
                                                                                        <span>Minimum Pax</span>
                                                                                        <strong><?php echo e($require['group_size']); ?></strong>
                                                                                    </div>
                                                                                    <div class="bh_units">
                                                                                        <input class="form-control required_unit<?php echo e($departure['id']); ?>" id="require_hold_<?php echo e($require['id']); ?>" name="hold[]" placeholder="Enter required units">
                                                                                    </div>
                                                                                </div>
                                                                                <hr class="mt-1 mb-1">
                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <strong>Price</strong>
                                                                                    <div class="form-group mb-0">
                                                                                        <input type="hidden" class="form-control" id="price_<?php echo e($require['id']); ?>" name="price[]" value="<?php echo e($require['price']); ?>" style="border:none">
                                                                                        <label id="require_hold_price_<?php echo e($require['id']); ?>"></label>
                                                                                        <input type="hidden" id="currency_c_<?php echo e($require['id']); ?>" value="<?php echo e($require['currency_code']); ?> " name="currency">
                                                                                        <input type="hidden" id="currency_<?php echo e($require['id']); ?>" value="<?php echo e($require['currency_symbol']); ?> " name="currency_symbol">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row d-none">
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="" value="<?php echo e(ucfirst($require->sharing)); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="flight_class[]" value="<?php echo e($require->flight_class); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="passenger[]" value="<?php echo e($require->passenger); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="hotel_type[]" value="<?php echo e($require->hotel_type); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="transport_type[]" value="<?php echo e($require->transport_type); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="airport_transfers[]" value="<?php echo e($require->airport_transfers); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-2">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="meal_plan[]" value="<?php echo e($require->meal_type); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-1">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="" name="group_size[]" value="<?php echo e($require->group_size); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 pr-5">
                                                                            <div class="form-group">
                                                                                <input class="form-control required_unit<?php echo e($departure->id); ?>" id="require_hold_<?php echo e($require->id); ?>" name="hold[]">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1" style="padding: 0px;">
                                                                            <div class="form-group">
                                                                                <input type="hidden" class="form-control" id="price_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                                                                <label id="require_hold_price_<?php echo e($require->id); ?>"></label>
                                                                                <input type="hidden" id="currency_c_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                                                                <input type="hidden" id="currency_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        </div>
                                                        <span id="mesegese1_<?php echo e($departure->id); ?>" class="text-danger" style="position: absolute; right: 0%;"></span>
                                                        <span class="text-danger" id="error<?php echo e($row->id); ?>"></span>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                    <label for="exampleFormControlInput1">Lead Passenger Name</label>
                                                                    <input type="text" class="form-control" id="" name="lead_pasanger_name" placeholder="">

                                                                </div>
                                                                <div class="col-md-2">
                                                                <span id="total_pricebook<?php echo e($row->id); ?>"></span>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <label for="exampleFormControlInput1">Note</label>
                                                                    <textarea name="note" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="col-md-12 text-right">
                                                            <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_<?php echo e($departure->id); ?>" style="width: 3%;  visibility: hidden;">
                                                            <span class="text-success" id="mesegese_<?php echo e($departure->id); ?>" style="margin-left: 10px"></span>
                                                            <button class="btn btn-primary active mr-2" type="button" id="store_form_hold_<?php echo e($departure->id); ?>"><i class="fa fa-save"></i> Hold Units</button>
                                                            <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!----End Modal-->
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endif; ?>
                    </div>
                    <div class="col-md-12"><?php echo e($departures->links()); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php $__env->stopSection(); ?>
        <?php $__env->startSection('footerSection'); ?>
            <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <!-- <script src="<?php echo e(asset('morris.min.js')); ?>"></script> -->
            <script src="<?php echo e(asset('js/select2.full.min.js')); ?>"></script>
            <script src="<?php echo e(asset('js/customJS/basic-details.js')); ?>"></script>
            <?php if(count($departures)>0): ?>
            <?php $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!----Modal-->
                <div class="modal fade bd-example-modal-sm<?php echo e($row->id); ?>b" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-mb " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-white" id="mySmallModalLabel">Book Units</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>

                            <form role="form" id="BookDepartureForm_<?php echo e($row->id); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="bookingMdodal">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="id" value="<?php echo e($row->id); ?>">
                                                <div class="d-flex align-items-center totalava_unit">
                                                    <h5>Available Units :</h5>
                                                    <span class="ml-2 text-black text-bold"><?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?></span>
                                                </div>
                                                <div class="form-group d-none">
                                                    <label for="exampleFormControlInput1">Available Units</label>
                                                    <input type="text" class="form-control" id="" name="available" value="<?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <?php if(in_array(32, json_decode($columns))): ?>
                                                            <div class="bh_units">
                                                                <span>Sharing</span>
                                                                <strong><?php echo e(ucfirst($require['sharing'])); ?></strong>
                                                            </div>
                                                            <?php endif; ?>
                                                            <?php if(in_array(33, json_decode($columns))): ?>
                                                            <div class="bh_units">
                                                                <span>Flight Class</span>
                                                                <strong><?php echo e(ucfirst($require['flight_class'])); ?></strong>
                                                            </div>
                                                            <div class="bh_units">
                                                                <span>Passenger Type</span>
                                                                <strong><?php echo e($require['passenger']); ?></strong>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <?php if(in_array(35, json_decode($columns))): ?> 
                                                            <div class="bh_units">
                                                                <span>Hotel Type</span>
                                                                <strong><?php echo e($require['hotel_type']); ?></strong>
                                                            </div>
                                                            <?php endif; ?>
                                                            <?php if(in_array(36, json_decode($columns))): ?>
                                                            <div class="bh_units">
                                                                <span>Transport Type</span>
                                                                <strong><?php echo e($require['transport_type']); ?></strong>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div class="bh_units">
                                                                <span>Airport Transfers</span>
                                                                <strong><?php echo e($require['airport_transfers']); ?></strong>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <?php if(in_array(38, json_decode($columns))): ?>
                                                            <div class="bh_units">
                                                                <span>Meal Plan</span>
                                                                <strong><?php echo e($require['meal_type']); ?></strong>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div class="bh_units">
                                                                <span>Minimum Pax</span>
                                                                <strong><?php echo e($require['group_size']); ?></strong>
                                                            </div>
                                                            <div class="bh_units">
                                                                <input type="text" class="form-control" id="require_<?php echo e($require['id']); ?>" name="book[]" placeholder="Enter required units">
                                                            </div>
                                                        </div>
                                                        <hr class="mt-1 mb-1">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <strong>Price</strong>
                                                            <div class="form-group mb-0">
                                                                <input type="hidden" class="form-control" id="price_<?php echo e($require['id']); ?>" name="price[]" value="<?php echo e($require['price']); ?>" style="border:none">
                                                                <strong id="require_price_<?php echo e($require['id']); ?>"></strong>
                                                                <input type="hidden" id="currency_c_<?php echo e($require['id']); ?>" value="<?php echo e($require['currency_code']); ?> " name="currency">
                                                                <input type="hidden" id="currency_<?php echo e($require['id']); ?>" value="<?php echo e($require['currency_symbol']); ?> " name="currency_symbol">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none">
                                            <?php if(in_array(32, json_decode($columns))): ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Sharing</label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if(in_array(33, json_decode($columns))): ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Flight Class</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Passenger Type</label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if(in_array(35, json_decode($columns))): ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Hotel Type</label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if(in_array(36, json_decode($columns))): ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Transport Type</label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Airport Transfers</label>
                                                </div>
                                            </div>
                                            <?php if(in_array(38, json_decode($columns))): ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Meal Plan</label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Minimum Pax</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Required Units</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="exampleFormControlInput1">Price</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $__currentLoopData = $row->departure_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input type="hidden" name="sairing[]" value="<?php echo e($require->sharing); ?>">
                                            <div class="row d-none">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <!-- <label for="exampleFormControlInput1"><?php echo e(ucfirst($require->sharing)); ?></label> -->
                                                        <input type="text" class="form-control" id="" name="" value="<?php echo e(ucfirst($require->sharing)); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="flight_class[]" value="<?php echo e($require->flight_class); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="passenger[]" value="<?php echo e($require->passenger); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="hotel_type[]" value="<?php echo e($require->hotel_type); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="transport_type[]" value="<?php echo e($require->transport_type); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="airport_transfers[]" value="<?php echo e($require->airport_transfers); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="meal_plan[]" value="<?php echo e($require->meal_type); ?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" name="group_size[]" value="<?php echo e($require->group_size); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 pr-5">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="require_<?php echo e($require->id); ?>" name="book[]" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control" id="price_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                                        <label id="require_price_<?php echo e($require->id); ?>"></label>
                                                        <input type="hidden" id="currency_c_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                                        <input type="hidden" id="currency_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <span class="text-danger" id="error<?php echo e($row->id); ?>"></span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <label for="exampleFormControlInput1">Lead Passenger Name<span id="error_book_msg_<?php echo e($row->id); ?>" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                                                    <input type="text" class="form-control" id="require_<?php echo e($require->id); ?>" name="lead_pasanger_name" placeholder="">
                                                </div>
                                                <div class="col-md-2">
                                                    <span id="total_pricebook<?php echo e($row->id); ?>"></span>
                                                </div>
                                                <div class="col-md-10">
                                                    <label for="exampleFormControlInput1">Note</label>
                                                    <textarea name="note" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="col-md-12 text-right">
                                            <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_book_<?php echo e($row->id); ?>" style="width: 3%;  visibility: hidden;">
                                            <span class="text-success" id="mesegese_book_<?php echo e($row->id); ?>" style="margin-left: 10px"></span>
                                            <button class="btn btn-primary active mr-2" type="button" id="store_form_book_<?php echo e($row->id); ?>"><i class="fa fa-save"></i> Book Units</button>
                                            <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    $('#check<?php echo e($row->id); ?>').keyup(function () {
                        var value = $(this).val();
                        var total = '<?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>';
                        var subtotal = value - total;
                        if ($(this).val() > <?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>) {
                            //alert('Your are Request ' +subtotal + ' Extra Departure');
                            //$(this).val('')
                            $("#message<?php echo e($row->id); ?>").text('Request More ' + subtotal + " Extra Departure");
                        } else {
                            $("#message<?php echo e($row->id); ?>").text("");
                        }
                    });
                    $("#check<?php echo e($row->id); ?>").keyup(function () {
                        var required = $("#check<?php echo e($row->id); ?>").val();
                        var price = ''
                        var sum = parseInt(required) * parseInt(price);
                        $("#total_hold_price<?php echo e($row->id); ?>").html(sum);
                    })
                </script>

                <script>
                    $('#book<?php echo e($row->id); ?>').keyup(function () {
                        var total = '<?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>';
                        if ($(this).val() > <?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>) {
                            $("#error<?php echo e($row->id); ?>").text('Value can not be greater than - ' + total + "");
                            //alert('Value can not be greater than <?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>');
                            $(this).val('');
                        } else {
                            $("#error<?php echo e($row->id); ?>").text('');
                        }
                    });
                    $("#book<?php echo e($row->id); ?>").keyup(function () {
                        var required = $("#book<?php echo e($row->id); ?>").val();
                        var price = ''
                        var sum = parseInt(required) * parseInt(price);

                        var required1 = $("#single_bookbook<?php echo e($row->id); ?>").val();
                        var price1 = ''
                        var sum1 = parseInt(requred1) * parseInt(price1);
                        var total = sum + sum1;

                        //alert(sum);
                        $("#msg").html('Total')
                        $("#required_pricebook<?php echo e($row->id); ?>").html(sum);

                        $("#total_pricebook<?php echo e($row->id); ?>").html(sum);
                    })
                    $("#single_bookbook<?php echo e($row->id); ?>").keyup(function () {
                        var required = $("#single_bookbook<?php echo e($row->id); ?>").val();
                        var price = ''
                        var sum = parseInt(required) * parseInt(price);
                        //alert(sum);
                        $("#single_pricebook<?php echo e($row->id); ?>").html(sum);

                        var required1 = $("#book<?php echo e($row->id); ?>").val();
                        var price1 = ''
                        var sum1 = parseInt(required1) * parseInt(price1);
                        var total = sum + sum1;
                        $("#total_pricebook<?php echo e($row->id); ?>").html(total);
                    })
                </script>
                <script>
                    <?php $departureCount = count($departure->departure_price); $j = 0; ?>
                    var total = 0;
                    <?php $__currentLoopData = $row->departure_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    $("#require_<?php echo e($require->id); ?>").keyup(function () {
                        var group_size = <?php echo e($require->group_size); ?>;
                        var check = parseInt($("#require_<?php echo e($require->id); ?>").val());
                        var sum_no = check + 1;
                        if (check != '') {
                            if (group_size == 2) {
                                if ((check % 2) != 0) {
                                    $("#require_<?php echo e($require->id); ?>").val(sum_no);
                                }
                            }
                            if (group_size == 3) {
                                if ((check % 3) != 0) {
                                    $("#require_<?php echo e($require->id); ?>").val(sum_no1);
                                }
                            }
                        }
                    });
                    <?php  $j++; ?>
                    $("#require_<?php echo e($require->id); ?>").keyup(function () {
                        var required = $("#require_<?php echo e($require->id); ?>").val();
                        //var total = 0;
                        required = required ? required : 0;
                        if (required != '') {
                            var total_required = required + required;
                            var price = $("#price_<?php echo e($require->id); ?>").val();
                            var sum = parseInt(required) * parseInt(price);
                            var currency = $("#currency_<?php echo e($require->id); ?>").val();
                            $("#require_price_<?php echo e($require->id); ?>").html(currency + +sum);
                            total = sum + total;
                        } else {
                            $("#require_price_<?php echo e($require->id); ?>").html('');

                            var price = $("#price_<?php echo e($require->id); ?>").val();
                            var currency = $("#currency_<?php echo e($require->id); ?>").val();
                            price = parseInt(price);
                            total = total - price;
                            //alert(currency);
                            //$("#total_pricebook<?php echo e($row->id); ?>").html("Total Price "+ currency+  +total);
                        }
                        //$("#total_pricebook<?php echo e($row->id); ?>").html("Total Price "+ currency+  +total);
                        //alert(total);
                    })

                    $("#require_<?php echo e($require->id); ?>").on("keypress keyup blur", function (event) {
                        $(this).val($(this).val().replace(/[^\d].+/, ""));
                        if ((event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $row->departure_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    $("#require_hold_<?php echo e($require->id); ?>").keyup(function () {
                        var group_size = <?php echo e($require->group_size); ?>;
                        var check = parseInt($("#require_hold_<?php echo e($require->id); ?>").val());
                        var sum_no = check + 1;
                        if (check != '') {
                            if (group_size == 2) {
                                if ((check % 2) != 0) {
                                    $("#require_hold_<?php echo e($require->id); ?>").val(sum_no);
                                }
                            }
                            if (group_size == 3) {
                                if ((check % 3) != 0) {
                                    $("#require_hold_<?php echo e($require->id); ?>").val(sum_no1);
                                }
                            }
                        }
                    });
                    $("#require_hold_<?php echo e($require->id); ?>").keyup(function () {
                        var required = $("#require_hold_<?php echo e($require->id); ?>").val();
                        if (required != '') {
                            var total_required = required + required;
                            var price = $("#price_<?php echo e($require->id); ?>").val();
                            var sum = parseInt(required) * parseInt(price);
                            var currency = $("#currency_<?php echo e($require->id); ?>").val();
                            $("#require_hold_price_<?php echo e($require->id); ?>").html(currency + +sum);
                        } else {
                            $("#require_hold_price_<?php echo e($require->id); ?>").html('');
                        }
                        //$("#total_pricebook<?php echo e($row->id); ?>").html(total);
                    })
                    $("#require_hold_<?php echo e($require->id); ?>").on("keypress keyup blur", function (event) {
                        $(this).val($(this).val().replace(/[^\d].+/, ""));
                        if ((event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </script>
                <script>
                    $('#isAgeSelected<?php echo e($row->id); ?>').click(function () {
                        $("#txtAge<?php echo e($row->id); ?>").toggle(this.checked);
                    });
                </script>
                <script>
                    var userName = document.querySelector('.required_unit<?php echo e($row->id); ?>');
                    userName.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = newValue;
                    }

                    var userName = document.querySelector('.required_unit1<?php echo e($row->id); ?>');
                    userName.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = newValue;
                    }

                    var userName = document.querySelector('.required_unit2<?php echo e($row->id); ?>');
                    userName.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = newValue;
                    }
                </script>
                <script>
                    $(document).ready(function () {
                        $('#store_form_hold_<?php echo e($row->id); ?>').click(function (e) {
                            e.preventDefault();
                            //alert('hello_<?php echo e($row->id); ?>');
                            $('#gif_<?php echo e($row->id); ?>').show();
                            $('#gif_<?php echo e($row->id); ?>').css('visibility', 'visible');
                            //$('#store_form').prop('disabled', true);
                            var formDatas = new FormData(document.getElementById('HoldDepartureForm_<?php echo e($row->id); ?>'));
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'POST',
                                url: "<?php echo e(route('departure_holdduration')); ?>",
                                data: formDatas,
                                contentType: false,
                                processData: false,
                                success: function (data) {
                                    $('#gif').hide();
                                    if (data.required) {
                                        //alert(data.required)
                                        $("#mesegese1_<?php echo e($row->id); ?>").html(data.error);
                                        $("#mesegese1_<?php echo e($row->id); ?>").html(data.required);
                                        $('#gif_<?php echo e($row->id); ?>').hide();
                                        //window.location = data.url;
                                    } else {
                                        $('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg'>Success!</span>");
                                        //window.location = data.url;
                                        location.reload();
                                    }

                                },
                                errors: function () {
                                    $('#gif_<?php echo e($row->id); ?>').hide();
                                    $('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg'>Something went wrong!</span>");
                                }

                            });
                        });
                    });
                </script>
                <script>
                    $(document).ready(function () {
                        $('#store_form_book_<?php echo e($row->id); ?>').click(function (e) {
                            e.preventDefault();
                            //alert('hello_<?php echo e($row->id); ?>');
                            $('#gif_book_<?php echo e($row->id); ?>').show();
                            $('#gif_book_<?php echo e($row->id); ?>').css('visibility', 'visible');
                            //$('#store_form').prop('disabled', true);
                            var formDatas = new FormData(document.getElementById('BookDepartureForm_<?php echo e($row->id); ?>'));
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'POST',
                                url: "<?php echo e(route('departure_book')); ?>",
                                data: formDatas,
                                contentType: false,
                                processData: false,
                                success: function (data) {
                                    $('#gif_book_<?php echo e($row->id); ?>').hide();
                                    if (data.error) {
                                        $("#error_book_msg_<?php echo e($row->id); ?>").html(data.error);
                                        $('#gif_book_<?php echo e($row->id); ?>').hide();
                                    } else if (data.required) {
                                        $("#error_book_msg_<?php echo e($row->id); ?>").html(data.required);
                                        $('#gif_book_<?php echo e($row->id); ?>').hide();
                                    } else {
                                        $('#mesegese_book_<?php echo e($row->id); ?>').html("<span class='sussecmsg'>Success!</span>");
                                        //window.location = data.url;
                                        location.reload();
                                    }

                                },
                                errors: function () {
                                    $('#gif_book_<?php echo e($row->id); ?>').hide();
                                    $('#mesegese_book_<?php echo e($row->id); ?>').html("<span class='sussecmsg'>Something went wrong!</span>");
                                }

                            });
                        });
                    });
                </script>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <style>
                .socail, dd {
                    font-size: 13px !important;
                }
            </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public_layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/profile/public_profile.blade.php ENDPATH**/ ?>