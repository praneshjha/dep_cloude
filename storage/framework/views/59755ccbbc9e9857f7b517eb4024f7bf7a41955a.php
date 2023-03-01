<?php
// dd($company_details_all->email);
$today = date("Y-m-d");
$new_time = ($departure_details->hold_duration) + 5;
?>

<?php $__env->startSection('tagSection'); ?>
    <?php echo $__env->make('layouts/og_tags', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('headCssSection'); ?>
    <style type="text/css">
        .LastUpdated {
            margin: 5px 0;
            font-weight: 500;
            font-family: "Cerebri Sans,sans-serif";
            color: #681f4a;
        }

        .someThing {
            max-height: 430px;
            overflow-y: auto;
        }

        .someThing p {
            position: relative;
            font-size: 16px;
            font-weight: 700;
            color: #898989;
            background-color: #fbfdff;
            box-shadow: 0 3px 4px 0 rgb(22 70 147 / 70%);
            padding: 4px 12px;
            margin-bottom: 22px;
        }

        .someThing p:not(:last-child):before {
            content: '';
            position: absolute;
            width: 1px;
            height: 16px;
            background-color: #681f4a;
            left: 50%;
            transform: translateX(-50%);
            top: 100%;
        }

        .someThing p:not(:last-child):after {
            content: '';
            position: absolute;
            display: block;
            width: 8px;
            height: 8px;
            border: 1px solid #681f4a;
            border-left: 0;
            border-top: 0;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            top: calc(100% + 9px);
        }
    </style>
    <link href="<?php echo e(asset('assets1/css/departure_details.css')); ?>" rel="stylesheet" type="text/css"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Departure Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert text-light alert-dismissible fade show " id="alert" role="alert" style="">
                    <div id="success_msg" style="">
                        <?php echo e(session('success')); ?>

                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-xl-6 itinerary_linkandImg">

                                

                                <?php if(isset($itinerary->pdf_file)): ?>
                                    <div class="tab-content pt-0">
                                        <embed src="<?php echo e(asset('agentitinerary') . '/' . $itinerary->pdf_file); ?>" type="application/pdf" width="100%" height="480px"/>
                                    </div>
                                <?php else: ?>
                                    <?php if(isset($itinerary->description)): ?>
                                        <div class="tab-content pt-0">
                                            <div class="tab-pane active show" id="product-1-item">
                                                <figure style="height: 480px;overflow: hidden;">
                                                    <img src="<?php echo e(asset('ScreenShot') . '/' . $itinerary->description); ?>" style="width:100%" class="img-fluid">
                                                </figure>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php if($departure_types->id == 3 ): ?>
                                            <figure>
                                                <img src="<?php echo e(asset('assets1/images/flight_h_iti-defualt.jpg')); ?>" style="width:100%" class="img-fit">
                                            </figure>
                                        <?php elseif($departure_types->id == 4): ?>
                                            <figure>
                                                <img src="<?php echo e(asset('assets1/images/hotel_iti-defualt.jpg')); ?>" style="width:100%" class="img-fit">
                                            </figure>
                                        <?php elseif($departure_types->id == 5): ?>
                                            <figure>
                                                <img src="<?php echo e(asset('assets1/images/flight_iti-defualt.jpg')); ?>" style="width:100%" class="img-fit">
                                            </figure>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                            </div>
                            <div class="col-xl-6 pl-md-0 departureDetail">
                                <div class=" d-flex justify-content-between align-items-center">
                                    <div class="w-100 d-flex justify-content-end cl_last_update align-items-center">
                                        <span>Last Updated :</span> &nbsp;
                                        <h5 class="m-0"><?php echo e(date('d M, Y | H:i A', strtotime($departure_details->last_updated_dep))); ?></h5>
                                    </div>
                                    <div class="btn-group w-100 d-flex justify-content-end">
                                        <!-- <p class="text-right"><h4 style="margin: 5px 0;">&nbsp; Last Updated: </h4> <span class="LastUpdated"> <?php echo e(date('d M, Y H:i A', strtotime($departure_details->last_updated_dep))); ?></span></p> -->
                                        <?php if(auth::user()->main_user_type==2): ?>
                                            <?php if($departure_details->start_date >= date('Y-m-d')): ?>
                                                

                                                <?php if($departure_details->approve == 1): ?>
                                                    <?php if($departure_details->start_date >= $today): ?>
                                                        <a href="" class="btn btn-primary btn-sm pull-right ml-2" data-toggle="modal"
                                                           data-target="<?php if((($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)) > 0): ?>.bd-example-modal-sm<?php echo e($departure_details->id); ?> <?php endif; ?>">Book Units</a>
                                                        <a href="" class="btn btn-info btn-sm pull-right ml-2" data-toggle="modal" data-target="<?php if($hold_till->hold_till < $date): ?>.bd-example-modal-sm <?php endif; ?>">Hold Units</a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if($departure_details->start_date >= $today): ?>
                                                <a href="" class="btn btn-primary btn-sm pull-right ml-2" data-toggle="modal"
                                                   data-target="<?php if((($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)) > 0): ?>.bd-example-modal-sm<?php echo e($departure_details->id); ?> <?php endif; ?>">Book Units</a>
                                                <a href="" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="<?php if($hold_till->hold_till < $date): ?>.bd-example-modal-sm <?php endif; ?>">Hold Units</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-1 text-capitalize" style="color: #093E8E;"><?php echo e($departure_details->title); ?></h2>
                                </div>
                                <div class="mb-0 d-flex align-items-center justify-content-between position-relative">
                                    <div class="d-flex align-items-center">
                                        <div class="CompanyName">
                                            <a href="<?php echo e(url('profile/'.$company_url)); ?>" class="userprofileName"> <span><?php echo e($departure_details->departure_ownner); ?></span></a>
                                            <div class="companyOverlay">
                                                <div class="companyDetailsBox">
                                                    <div class="topBox">
                                                        <?php if($company_details_all->logo == null): ?>
                                                            <figure><img src="<?php echo e(asset('images/company_icon.png')); ?>" alt="company-profile" class="img-fixed"></figure>
                                                        <?php else: ?>
                                                            <figure><img src="<?php echo e(url('companyLogo')); ?>/<?php echo e($company_details_all->logo); ?> " alt="company-profile" class="img-fixed"></figure>
                                                        <?php endif; ?>
                                                        <div class="name">
                                                            <h5><?php echo e($company_details_all->name); ?> <?php echo e($company_details_all->last_name); ?></h5>
                                                            <h4><?php echo e($company_details_all->company_name); ?></h4>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="detailBox">
                                                        <p class="text-muted"><i class="fas fa-phone fa-rotate-90"></i> <a href="tel:<?php echo e($company_details_all->mobile); ?>"><?php echo e($company_details_all->mobile); ?>lol</a></p>
                                                        <p class="text-muted"><i class="fas fa-envelope"></i> <a href="mailto:<?php echo e($company_details_all->email); ?>"><?php echo e($company_details_all->email); ?></a></p>
                                                        <?php if($company_details_all->website == null): ?>

                                                        <?php else: ?>
                                                            <p class="text-muted"><i class="fa fa-globe"></i> <a href="<?php echo e($company_details_all->website); ?>"><?php echo e($company_details_all->website); ?></a></p>
                                                        <?php endif; ?>
                                                        <p class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php if($company_details_all->address == null): ?>

                                                            <?php else: ?>
                                                                <?php echo e($company_details_all->address); ?>

                                                            <?php endif; ?>
                                                            <br>
                                                            <?php echo e($company_details_all->city); ?>,
                                                            <?php if($company_details_all->state == null): ?>

                                                            <?php else: ?>
                                                                <?php echo e($company_details_all->state); ?>,
                                                            <?php endif; ?>
                                                            <br><?php echo e($company_details_all->country); ?> - <?php if($company_details_all->pin == null): ?>

                                                            <?php else: ?>
                                                                <?php echo e($company_details_all->pin); ?>

                                                            <?php endif; ?>
                                                        </p>
                                                        <ul class="">
                                                            <?php if($company_details_all->facebook == null): ?>

                                                            <?php else: ?>
                                                                <li><a href="<?php echo e($company_details_all->facebook); ?>"><i class="fab fa-facebook"></i></a></li>
                                                            <?php endif; ?>
                                                            <?php if($company_details_all->twitter == null): ?>

                                                            <?php else: ?>
                                                                <li><a href="<?php echo e($company_details_all->twitter); ?>"><i class="fab fa-twitter"></i></a></li>
                                                            <?php endif; ?>
                                                            <?php if($company_details_all->instagram == null): ?>

                                                            <?php else: ?>
                                                                <li><a href="<?php echo e($company_details_all->instagram); ?>"><i class="fab fa-instagram"></i></a></li>
                                                            <?php endif; ?>
                                                            <?php if($company_details_all->youtube == null): ?>

                                                            <?php else: ?>
                                                                <li><a href="<?php echo e($company_details_all->youtube); ?>"><i class="fab fa-youtube"></i></a></li>
                                                            <?php endif; ?>
                                                            <?php if($company_details_all->pinterest == null): ?>

                                                            <?php else: ?>
                                                                <li><a href="<?php echo e($company_details_all->pinterest); ?>"><i class="fab fa-pinterest-p"></i></a></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="departureID font-16 mb-0 ml-1">(<?php echo e($departure_details->dep_id); ?>)</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <input type="hidden" name="dep_val_<?php echo e($departure_details->id); ?>" id="dep-<?php echo e($departure_details->id); ?>-val" value="<?php echo e($departure_details->id); ?>">

                                        <a href="javascript:void(0);" class="pl-2 pr-2 chat_data tooltipbubble" id="dep-<?php echo e($departure_details->id); ?>" title="chat"><i class="far fa-comment-dots"></i></a>
                                        <?php if(isset($contact_person_det->mobile)): ?>
                                            <a href="tel:<?php echo e($contact_person_det->mobile); ?>" class="pl-2 pr-2 tooltipbubble" title="<?php echo e($contact_person_det->mobile); ?>"><i class="fas fa-phone fa-rotate-90 1"></i></a>
                                        <?php endif; ?>
                                        <?php if(isset($contact_person_det->email)): ?>
                                            <a href="mailto:<?php echo e($contact_person_det->email); ?>" class="pl-2 pr-2 tooltipbubble" title="<?php echo e($contact_person_det->email); ?>"><i class="far fa-envelope"></i></a>
                                        <?php endif; ?>
                                        <div class="DetailsPageShareIcon ml-2 ">
                                            <a href="javascript:void(0);" class="ShareIcons">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!--! Font Awesome Pro 6.1.1 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M448 127.1C448 181 405 223.1 352 223.1C326.1 223.1 302.6 213.8 285.4 197.1L191.3 244.1C191.8 248 191.1 251.1 191.1 256C191.1 260 191.8 263.1 191.3 267.9L285.4 314.9C302.6 298.2 326.1 288 352 288C405 288 448 330.1 448 384C448 437 405 480 352 480C298.1 480 256 437 256 384C256 379.1 256.2 376 256.7 372.1L162.6 325.1C145.4 341.8 121.9 352 96 352C42.98 352 0 309 0 256C0 202.1 42.98 160 96 160C121.9 160 145.4 170.2 162.6 186.9L256.7 139.9C256.2 135.1 256 132 256 128C256 74.98 298.1 32 352 32C405 32 448 74.98 448 128L448 127.1zM95.1 287.1C113.7 287.1 127.1 273.7 127.1 255.1C127.1 238.3 113.7 223.1 95.1 223.1C78.33 223.1 63.1 238.3 63.1 255.1C63.1 273.7 78.33 287.1 95.1 287.1zM352 95.1C334.3 95.1 320 110.3 320 127.1C320 145.7 334.3 159.1 352 159.1C369.7 159.1 384 145.7 384 127.1C384 110.3 369.7 95.1 352 95.1zM352 416C369.7 416 384 401.7 384 384C384 366.3 369.7 352 352 352C334.3 352 320 366.3 320 384C320 401.7 334.3 416 352 416z"/>
                                                </svg>
                                            </a>
                                            <div class="a2a_kit a2a_kit_size_32 a2a_default_style socialIconsList">
                                                <!-- <a class="a2a_dd" href="https://www.addtoany.com/share"></a> -->
                                                <a target="_blank" class="a2a_button_email" title="Email Share"></a>
                                                <a target="_blank" class="a2a_button_facebook" title="FB Share"></a>
                                                <a target="_blank" class="a2a_button_twitter" title="Twitter Share"></a>
                                                <a target="_blank" class="a2a_button_whatsapp" title="Whatsuapp Share"></a>
                                                <a target="_blank" class="a2a_button_pinterest" title="Pinterest Share"></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <script async src="https://static.addtoany.com/menu/page.js"></script>
                                <?php if($departure_details->ending_at != ""): ?>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-map-marker-radius-outline mr-1"></i> <strong>Departure To : </strong> &nbsp; <strong class="text-uppercase text-primary"><?php echo e($departure_details->ending_at); ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if($departure_details->from != ""): ?>
                                    <?php if($departure_types->id != 4): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-map-marker-multiple-outline mr-1"></i><strong> Ex : </strong> &nbsp
                                            <strong class="text-uppercase text-pink"><?php echo e($departure_details->from); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if($departure_details->return_to != ""): ?>
                                    <?php if($departure_types->id != 4): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-map-marker-multiple-outline mr-1"></i><strong> Return To : </strong> &nbsp
                                            <strong class="text-uppercase text-pink"><?php echo e($departure_details->return_to); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-calendar-check mr-1"></i><strong> Date : </strong> &nbsp
                                    <strong class="text-uppercase "><?php echo e(date('d M, Y', strtotime($departure_details->start_date))); ?></strong>
                                    <div class="ml-2 position-relative commingDates">
                                        <i class="mdi mdi-calendar-multiple"></i>
                                        <div class="alternateDateofPackage">
                                            <strong class="text-blue">Near by dates</strong>
                                            <hr class="mt-0">
                                            <ul class="list-unstyled mb-0">
                                                <?php $__currentLoopData = $nearByDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nearbydate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><a href="<?php echo e(route('all_departure_details',$nearbydate->id)); ?>"><?php echo e(date('d M, Y', strtotime($nearbydate->start_date))); ?> </a></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php if($departure_types->id != 5): ?>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-weather-night mr-1"></i>
                                        <p style="margin-bottom: 0;"><strong><?php echo e($departure_details->no_of_nights); ?></strong> Nights / <strong><?php echo e($departure_details->no_of_days); ?></strong> Days</p>
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex flex-wrap align-items-center destination_covered">
                                    <i class="mdi mdi-map-marker-distance mr-1"></i>
                                    <p style="margin-bottom: 0;color: #002a68;padding-right: 4px;"><strong>Destination(s) Covered :</strong></p>
                                    <ul class="list-inline mb-0">
                                        <?php $__currentLoopData = $departure_destination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="list-inline-item"><?php echo e($row->dest_name); ?> <span class="text-dark">(<?php echo e($row->country_name); ?>)</span></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                </div>
                                <?php if($departure_details->description != null): ?>
                                    <div class="card descriptionBg  mt-2">
                                        <div class="card-body p-2 badge-soft-pink">
                                            <h4 class="mb-1 mt-0"> Description</h4>
                                            <p class="mb-0"><?php echo e($departure_details->description); ?>

                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(count($inclusion) >0): ?>
                                    <div class="card incluionCard mt-2 mb-0">
                                        <div class="card-body p-2 badge-soft-pink">
                                            <h4 class="mb-1 mt-0">Inclusions</h4>
                                            <?php $__currentLoopData = $inclusion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p class="mb-0">
                                                    <?php if(isset($row->icon)): ?>
                                                        <img src="<?php echo e(asset('inclusion-images/'.$row->icon)); ?>" style="width: 12px;">
                                                    <?php endif; ?>
                                                    <strong><?php echo e($row->name); ?> :</strong> <?php echo e($row->description); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($itinerary->title)): ?>
                                    <b>Itinerary Finder Link</b> <a href="<?php echo e($itinerary->title); ?>" style="text-decoration:none"><?php echo e($itinerary->title); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if(count($day_itinerary)>0): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mt-3">Day Itineraries</h5>
                                </div>
                                <?php $__currentLoopData = $day_itinerary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="dc_day_itinerary">
                                        <h5 class="day_num">Day <?php echo e($row->day_number); ?>: <?php echo e($row->day_heading); ?></h5>
                                        <?php if(count($row->day_destination) > 0): ?>
                                        <div class="dc_day_destinations mt-2">
                                            <?php $__currentLoopData = $row->day_destination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="destination"><?php echo e($dest->dest_name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($row->description != Null): ?>
                                        <div class="description position-relative dc_hide mb-2 <?php if(count($row->day_destination) < 1): ?> mt-2 <?php endif; ?> <?php if(str_word_count($row->description) <= 32): ?> dc_show pb-0 <?php endif; ?>"><?php echo $row->description; ?> <?php if(str_word_count($row->description) >= 33): ?><a href="javascript:void(0);" class="read_more">Read more...</a><?php endif; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($departure_types->id != 4 || $departure_types->id != 2): ?>
                            <div class="row">
                                <?php if(count($originating)>0): ?>
                                    <div class="col-md-12">
                                        <h5 class="mt-3">Flight Details</h5>
                                    </div>

                                    <?php $__currentLoopData = $originating; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4">
                                            <?php if($key == 0): ?>
                                                <div class="card-box ribbon-box flight-card">
                                                    <div class="ribbon-two ribbon-two-primary"><span><i class="mdi mdi-airplane-takeoff"></i>Originating</span></div>

                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                <?php if($row->logo != null): ?>
                                                                    <img src="https://pullit-bucket.s3.us-west-2.amazonaws.com/airlines/<?php echo e($row->logo); ?>" alt="flight-img">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/flight.png')); ?>" alt="flight-img">
                                                                <?php endif; ?>
                                                                <div>
                                                                    <h4><?php echo e(strtolower($row->flight_name)); ?> <span>(<?php echo e($row->code); ?>-<?php echo e($row->flight_no); ?>)</span><small><?php echo e(date('d M, Y', strtotime($row->flight_date))); ?></small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small><?php echo e($row->flight_dep_airport); ?></small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small><?php echo e($row->flight_arrival_airport); ?></small>
                                                        </h4>
                                                    </div>

                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong><?php echo e($row->flight_dep_time); ?></strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong><?php echo e($row->flight_arrival_time); ?></strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($key > 0): ?>
                                                <div class="card-box ribbon-box flight-card">
                                                    <div class="ribbon-two ribbon-two-primary"><span><i class="mdi mdi-airplane-takeoff"></i>Originating: <?php echo e($key+1); ?></span></div>

                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                <?php if( strcasecmp($row->flight_name, "air india") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/air-india.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "vistara airlines") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/vistara-airlines.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "fly dubai") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/fly-dubai.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "indigo") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/indigo.png')); ?>" alt="flight-img">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/flight.png')); ?>" alt="flight-img">
                                                                <?php endif; ?>
                                                                <div>
                                                                    <h4><?php echo e(strtolower($row->flight_name)); ?> <span>(<?php echo e($row->code); ?>-<?php echo e($row->flight_no); ?>)</span><small><?php echo e(date('d M, Y', strtotime($row->flight_date))); ?></small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small><?php echo e($row->flight_dep_airport); ?></small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small><?php echo e($row->flight_arrival_airport); ?></small>
                                                        </h4>
                                                    </div>

                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong><?php echo e($row->flight_dep_time); ?></strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong><?php echo e($row->flight_arrival_time); ?></strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(count($returning)>0): ?>
                                    <?php $__currentLoopData = $returning; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <!-- start my code -->
                                        <div class="col-md-4">
                                            <?php if($key == 0): ?>
                                                <div class="card-box ribbon-box flight-card">

                                                    <div class="ribbon-two ribbon-two-success"><span><i class="mdi mdi-airplane-takeoff"></i>Returning</span></div>

                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                <?php if( strcasecmp($row->flight_name, "air india") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/air-india.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "vistara airlines") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/vistara-airlines.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "fly dubai") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/fly-dubai.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "indigo") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/indigo.png')); ?>" alt="flight-img">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/flight.png')); ?>" alt="flight-img">
                                                                <?php endif; ?>

                                                                <div>
                                                                    <h4><?php echo e(strtolower($row->flight_name)); ?> <span>(<?php echo e($row->code); ?>-<?php echo e($row->flight_no); ?>)</span><small><?php echo e(date('d M, Y', strtotime($row->flight_date))); ?></small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small><?php echo e($row->flight_dep_airport); ?></small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small><?php echo e($row->flight_arrival_airport); ?></small>
                                                        </h4>
                                                    </div>

                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong><?php echo e($row->flight_dep_time); ?></strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong><?php echo e($row->flight_arrival_time); ?></strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($key > 0): ?>
                                                <div class="card-box ribbon-box flight-card">

                                                    <div class="ribbon-two ribbon-two-success"><span><i class="mdi mdi-airplane-takeoff"></i>Returning: <?php echo e($key+1); ?></span></div>

                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                <?php if( strcasecmp($row->flight_name, "air india") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/air-india.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "vistara airlines") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/vistara-airlines.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "fly dubai") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/fly-dubai.png')); ?>" alt="flight-img">
                                                                <?php elseif( strcasecmp($row->flight_name, "indigo") == 0): ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/indigo.png')); ?>" alt="flight-img">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('assets1/images/flight/flight.png')); ?>" alt="flight-img">
                                                                <?php endif; ?>

                                                                <div>
                                                                    <h4><?php echo e(strtolower($row->flight_name)); ?> <span>(<?php echo e($row->code); ?>-<?php echo e($row->flight_no); ?>)</span><small><?php echo e(date('d M, Y', strtotime($row->flight_date))); ?></small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small><?php echo e($row->flight_dep_airport); ?></small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small><?php echo e($row->flight_arrival_airport); ?></small>
                                                        </h4>
                                                    </div>

                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong><?php echo e($row->flight_dep_time); ?></strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong><?php echo e($row->flight_arrival_time); ?></strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($departure_types->id != 5): ?>
                            <div class="row">
                                <?php if(count($hotels)>0): ?>
                                    <div class="col-md-12">
                                        <h5 class="mt-3">Hotel Details</h5>
                                    </div>
                                    <?php $__currentLoopData = $hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-3 ribbon-box">
                                            <div class="price-card">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="title">
                                                            Hotel: <span class="text-info"><?php echo e(ucfirst($hotel->name)); ?></span>
                                                        </h6>
                                                        <h4 class="price mb-0">Room: <?php echo e($hotel->total_room); ?>

                                                        </h4>
                                                    </div>
                                                </div>
                                                <hr class="mb-1 mt-1">
                                                <div class="d-flex2">
                                                    <div class="w-501 d-flex align-items-center justify-content-between">
                                                        <div class="transport">
                                                            <small>Destination</small>
                                                            <div class="star">
                                                                <?php echo e($hotel->destination_id); ?>

                                                            </div>

                                                        </div>
                                                        <div class="transport">
                                                            <small>Hotel Category</small>
                                                            <div class="star">
                                                                <?php if( strcasecmp( $hotel->hotel_category, "5 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $hotel->hotel_category, "4 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 4; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $hotel->hotel_category, "3 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 3; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $hotel->hotel_category, "2 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 2; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $hotel->hotel_category, "1 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 1; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">
                                                                    <?php endfor; ?>
                                                                <?php endif; ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <!--End Hotel Section -->
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <?php if(count($departure_prices)>0): ?>
                                <div class="col-md-12">
                                    <h5 class=" mt-3">Pricing Details</h5>
                                </div>
                                <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-3 ribbon-box">
                                        <div class="price-card" style="">
                                            <!-- <div class="ribbon-2 ribbon-2-primary"><span><?php echo e(ucfirst($price->sharing)); ?></span></div> -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex flex-column">
                                                    <h6 class="title">
                                                        <?php if(in_array(32, json_decode($columns))): ?>
                                                            <span class="d-block">Sharing: <span class="text-info"><?php echo e(ucfirst($price->sharing)); ?></span></span>
                                                        <?php endif; ?>
                                                        <?php if(in_array(33, json_decode($columns))): ?>
                                                            <span class="d-block">Flight Class: <span class="text-info"><?php echo e(ucfirst($price->flight_class)); ?></span></span>
                                                        <?php endif; ?>
                                                    </h6>
                                                </div>
                                                <h4 class="price mb-0">
                                                    <sup class=""><?php echo e($price->currency_symbol); ?></sup><?php echo e($price->price); ?>

                                                </h4>
                                            </div>
                                            <hr class="mb-1 mt-1">
                                            <div class="d-flex2">
                                                <div class="w-501 d-flex align-items-center justify-content-between flex-wrap">
                                                    <div class="transport">
                                                        <?php if(in_array(35, json_decode($columns))): ?>
                                                            <small>Hotel Name</small>
                                                            <div>
                                                                <?php echo e($price->hotel_name); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="transport">
                                                        <?php if(in_array(35, json_decode($columns))): ?>
                                                            <small>Hotel Ctegory</small>
                                                            <div class="star">
                                                                <?php if( strcasecmp( $price->hotel_type, "5 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $price->hotel_type, "4 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 4; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $price->hotel_type, "3 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 3; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $price->hotel_type, "2 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 2; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">

                                                                    <?php endfor; ?>
                                                                <?php elseif( strcasecmp( $price->hotel_type, "1 star") == 0 ): ?>
                                                                    <?php for($i = 1; $i <= 1; $i++): ?>
                                                                        <img src="<?php echo e(asset('assets1/images/star.png')); ?>" alt="star">
                                                                    <?php endfor; ?>
                                                                <?php elseif($price->hotel_type == "Mix"): ?>
                                                                    <?php echo e($price->hotel_type); ?>

                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="transport">
                                                        <?php if(in_array(33, json_decode($columns))): ?>
                                                            <small>Age Bracket</small>
                                                            <div>
                                                                <?php echo e($price->passenger); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if(in_array(36, json_decode($columns))): ?>
                                                        <div class="transport">
                                                            <small>Transport</small>
                                                            <span class=""><?php echo e($price->transport_type); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="transport">
                                                        <small>Airport Transfer</small>
                                                        <span class=""><?php echo e($price->airport_transfers); ?></span>
                                                    </div>
                                                    <div class="transport">
                                                        <small>Minimum Pax</small>
                                                        <span><?php echo e($price->group_size); ?></span>
                                                    </div>
                                                </div>
                                                <?php if(in_array(38, json_decode($columns))): ?>
                                                    <div class="meal-plan">
                                                        <small>Meal Plan</small>
                                                        <span><?php echo e($price->meal_type); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if(isset($price->other)): ?>
                                                <h5 class="m-0 note">
                                                    <small>Note: </small>
                                                    <?php echo e($price->other); ?>

                                                </h5>
                                            <?php endif; ?>
                                            <!-- <div class="book_hold_overlay">
                                                <button type="submit" class="btn btn-primary mr-2">Book Unit</button>
                                                <button type="submit" class="btn btn-secondary">Hold Unit</button>
                                            </div> -->
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <!-- Payment schedule -->
                        <?php if(count($payment_schedule) > 0): ?>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mt-0 mt-3">Payment Schedule</h5>
                                </div>
                                <div class="col-md-12">
                                    <?php $__currentLoopData = $payment_schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key==0): ?>
                                            <div class="payment-card-1 bg-danger text-white">
                                                Minimum Booking Price <strong class="p-amount text-white"><?php echo e($payment->percentage); ?>%</strong>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($key>0): ?>
                                            <div class="payment-card-1">
                                                <?php echo e(date('d M, Y', strtotime($payment->date))); ?> |
                                                <strong class="p-amount"><?php echo e($payment->percentage); ?>%</strong>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Payment schedule end -->
                        <?php if(count($cancelation_schedule) > 0): ?>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mt-0 mt-3">Cancelation Charge</h5>
                                </div>
                                <div class="col-md-12">
                                    <?php $__currentLoopData = $cancelation_schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key==0): ?>
                                            <div class="payment-card-1 bg-danger text-white">
                                                Minimum Cancelation Charge <strong class="p-amount text-white"><?php echo e($payment->percentage); ?>%</strong>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($key>0): ?>
                                            <div class="payment-card-1">
                                                <?php echo e(date('d M, Y', strtotime($payment->date))); ?> |
                                                <strong class="p-amount"><?php echo e($payment->percentage); ?>%</strong>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- end row -->
                        <?php if(auth::user()->id == $departure_details->user_id || auth::user()->main_user_type == 2): ?>
                            <div class="row">

                                <?php if($hold !=0): ?>
                                    <!-- <div class="container table-bordered" >
                            <h5 class="card-user_name mb-2 mt-2">Hold Details </h5>
                             <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead >
                                              <tr>
                                              <th>Dep. Name</th>
                                              <th>Buyer Name</th>
                                              <th>Hold Date & time</th>
                                              <th>Hold Units</th>
                                              <th>Total Price</th>
                                              <th>Action</th>
                                              </tr>
                                         </thead>
                                          <tbody>
                                          
                                          <?php $__currentLoopData = $hold_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$holding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                          <td><?php echo e($holding->departure_name); ?></td>
                                              <td><?php echo e($holding->name); ?></td>
                                              <td><?php echo e(date('d M, Y h:i a', strtotime($holding->created_at."+5 hours +30 minutes"))); ?></td>
                                              <td><?php echo e($holding->booked_seat); ?>

                                        </td>
                                        <td><?php echo e($holding->currency->currency_symbol); ?>   <?php echo e($holding->price); ?>

                                        </td>
                                        <td>
                                        <a href="<?php echo e(url('/departures-hold-history-details/'.$holding->unique_id)); ?>" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                              </td>
                                            </tr>














                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                              </table>
<?php echo e($hold_date->links()); ?>

                                    </div>
                                </div>
                              </div> -->
                                <?php endif; ?>
                                <?php if($departure_details->extra_hold_sum != 0): ?>
                                    <!-- <div class="container table-bordered" >
                            <h5 class="card-user_name">Request More</h5>
                            
                             <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Company</th>
                                                <th>Request Mode Unit</th>
                                                <th>Auto Release</th>
                                                <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <?php $__currentLoopData = $extra_hold; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                           <td><?php echo e($row->company_name); ?></td>
                                               <td><?php echo e($row->extra_hold_seat); ?> Unit</td>
                                               <td><?php echo e(date('d M, Y', strtotime("+{$row->date}"))); ?></td>
                                               <td>
                                               <a href="" data-id="<?php echo e($row->id); ?>" class="mr-2 ReleaseDepartue" title="Release"><i class="fa fa-unlink"></i></a>||
                                               <a href="<?php echo e(url('/departures-hold-history-details/'.$row->id)); ?>" class="mr-2" title="More Details" style=><i class="fa fa-eye"></i></a>
                                               </td>
                                            </tr>














                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                               </table>
                           </div>
                       </div>
                     </div> -->
                                <?php endif; ?>

                                <?php if(count($departure_book)> 0 ): ?>
                                    <!-- <div class="container table-bordered" >
                            <h5 class="card-title mb-2 mt-2">Booking Details <a href="<?php echo e(route('all_departure_booking_history')); ?>" class="btn btn-info btn-sm pull-right">All Booking</a></h5>
                             <div class="col-md-12">
                                <div class="table-responsive">
                                   <table id="" class="table table-bordered table-striped">
                                        <thead >
                                              <tr>
                                              <th>Dep. Name</th>
                                              <th>Buyer Name</th>
                                              <th>Booking Date & time</th>
                                              <th>Booking Units</th>
                                              <th>Total Price</th>
                                              <th>Action</th>
                                              </tr>
                                         </thead>
                                          <tbody>
                                          
                                          <?php $__currentLoopData = $book_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                          <td><?php echo e($booking->departure_name); ?></td>
                                              <td><?php echo e($booking->name); ?></td>
                                              <td><?php echo e(date('d M, Y h:i a', strtotime($booking->created_at."+5 hours +30 minutes"))); ?></td>
                                              <td><?php echo e($booking->booked_seat); ?>

                                        </td>
                                        <td><?php echo e($booking->currency->currency_symbol); ?>   <?php echo e($booking->price); ?>

                                        </td>
                                        <td>
                                        <a href="<?php echo e(url('/departures-booking-history-details/'.$booking->unique_id)); ?>" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                              </td>
                                            </tr>














                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                              </table>
<?php echo e($book_date->links()); ?>

                                    </div>
                                </div>
                              </div> -->
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>
                        <?php if(isset($departure_details->termspayment)): ?>
                            <div class="card incluionCard mt-2">

                                <div class="card-body p-2 badge-soft-pink">
                                    <h4 class="mb-1 mt-0">Terms</h4>
                                    <?php echo $departure_details->termspayment; ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div> <!-- end card-->

                </div> <!-- end col-->
            </div>
            <?php if(session('success')): ?>
                <div class="modal fade" id="myModal" role="dialog" style="">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h5><?php echo e(session('success')); ?></h5>
                                <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(session()->has('msg')): ?>
                <div class="modal fade" id="myModal" role="dialog" style="">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h5><?php echo e(session()->get('msg')); ?></h5>
                                <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <!--  </div>

     </div>  -->
    <!----Modal-->
    <div class="modal fade bd-example-modal-sm" id="hold" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-mb" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="mySmallModalLabel">Hold Units</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <form role="form" id="HoldDepartureForm" style="background-color: #fdfdfd;" class="p-1">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center totalava_unit">
                                <?php if(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)>0): ?>
                                    <div class="bh_units">
                                        <input type="hidden" class="form-control" value="<?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>" readonly>
                                        <div class="form-group mb-0">
                                            <h5 class="mb-0">Available Units :</h5>
                                            <span class="text-black text-bold"><?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?></span>
                                            <input type="hidden" class="form-control" id="" name="available" value="<?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>" name="available" readonly>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="bh_units">
                                        <input type="hidden" class="form-control" name="available" value="0" readonly>
                                        <div class="form-group mb-0">
                                            <h5 class="mb-0">Available Units :</h5>
                                            <span class="text-black text-bold"><?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?></span>
                                            <input type="hidden" class="form-control" id="" name="available" value="Over Holded:<?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>" readonly>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="bh_units">
                                    <div class="form-group mb-0">
                                        <input type="hidden" name="id" value="<?php echo e($departure_details->id); ?>">
                                        <input type="hidden" name="current_hours" value="<?php echo e(date('H')); ?>">
                                        <input type="hidden" name="current_minutes" value="<?php echo e(date('i')); ?>">
                                        <h5 class="mb-0">Hold Till</h5>
                                        <span class="text-black text-bold"><?php echo e(date('d M, Y | h:ia', strtotime("+{$new_time} hours +30 minutes"))); ?></span>
                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hours" value="<?php echo e($departure_details->hold_duration); ?>" readonly>
                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hold_time" value="<?php echo e(date('d M, Y h:ia', strtotime("+{$new_time} hours +30 minutes"))); ?>" readonly>
                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="auto_release" value="<?php echo e(date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))); ?>" readonly>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bookingMdodal">
                            <div class="row">
                                <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <input type="hidden" name="sairing[]" value="<?php echo e($require->sharing); ?>">
                                    <div class="row d-none">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="<?php echo e(ucfirst($require->sharing)); ?>" readonly>
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
                                                <input type="text" class="form-control" id="" name="hotel_name[]" value="<?php echo e($require['hotel_name']); ?>" readonly>
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
                                        <!--                                <div class="col-md-2 pr-5">
                                    <div class="form-group">
                                        <input class="form-control required_unit<?php echo e($departure_details->id); ?>" id="require_hold_<?php echo e($require->id); ?>" name="hold[]">
                                    </div>
                                </div>-->
                                        <!--                                <div class="col-md-1" style="padding:0px">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="price_hold_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                        <label id="require_hold_price_<?php echo e($require->id); ?>"></label>
                                        <input type="hidden" id="currency_c_hold_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                        <input type="hidden" id="currency_hold_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                    </div>
                                </div>-->
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <?php if(in_array(32, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Room Sharing</span>
                                                            <strong><?php echo e(ucfirst($require->sharing)); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array(33, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Flight Class</span>
                                                            <strong><?php echo e(ucfirst($require->flight_class)); ?></strong>
                                                        </div>
                                                        <div class="bh_units">
                                                            <span>Passenger Type</span>
                                                            <strong><?php echo e($require->passenger); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <?php if(in_array(35, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Hotel Name</span>
                                                            <strong><?php echo e($require['hotel_name']); ?></strong>
                                                        </div>
                                                        <div class="bh_units">
                                                            <span>Hotel Type</span>
                                                            <strong><?php echo e($require->hotel_type); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array(36, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Transport Type</span>
                                                            <strong><?php echo e($require->transport_type); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="bh_units">
                                                        <span>Airport Transfers</span>
                                                        <strong><?php echo e($require->airport_transfers); ?></strong>
                                                    </div>

                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <?php if(in_array(38, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Meal Plan</span>
                                                            <strong><?php echo e($require->meal_type); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="bh_units">
                                                        <span>Minimum Pax</span>
                                                        <strong><?php echo e($require->group_size); ?></strong>
                                                    </div>
                                                    <div class="bh_units">
                                                        <input class="form-control required_unit<?php echo e($departure_details->id); ?>" id="require_hold_<?php echo e($require->id); ?>" name="hold[]" placeholder="Enter required units">
                                                    </div>
                                                </div>
                                                <hr class="mt-1 mb-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <strong>Price</strong>
                                                    <div class="form-group mb-0">
                                                        <input type="hidden" class="form-control" id="price_hold_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                                        <label id="require_hold_price_<?php echo e($require->id); ?>"></label>
                                                        <input type="hidden" id="currency_c_hold_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                                        <input type="hidden" id="currency_hold_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="exampleFormControlInput1">Lead Passenger Name<span id="error_msg_hold" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                                    <input type="text" class="form-control" id="" name="lead_pasanger_name" placeholder="">
                                </div>
                                <div class="col-md-2">
                                    <!-- <span style="position:absolute; right:10%;" id="total_pricebook">
                                   </span> -->
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
                            <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif" style="width: 3%;  visibility: hidden;">
                            <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                            <button class="btn btn-primary active mr-2" type="button" id="store_form_hold"><i class="fa fa-save"></i> Hold Units</button>
                            <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!----End Modal-->

    <!----Modal-->

    <div class="modal fade bd-example-modal-sm<?php echo e($departure_details->id); ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-mb" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="mySmallModalLabel">Book Units</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="BookDepartureForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group d-none">
                            <label for="exampleFormControlInput1">Available Units
                            </label>
                            <input type="text" class="form-control" id="" name="available" value="<?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>" readonly>
                        </div>
                        <div class="form-group d-none">
                            <input type="hidden" name="id" value="<?php echo e($departure_details->id); ?>">
                            <div class="row">
                                <?php if(in_array(32, json_decode($columns))): ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Room Sharing</label>
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
                                            <label for="exampleFormControlInput1">Passenger</label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array(35, json_decode($columns))): ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Hotel Name</label>
                                        </div>
                                    </div>
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
                                        <label for="exampleFormControlInput1">Airport Transfer</label>
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

                            <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="sairing[]" value="<?php echo e(ucfirst($require->sharing)); ?>">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" value="<?php echo e(ucfirst($require->sharing)); ?>" readonly>
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
                                            <input type="text" class="form-control" id="" name="hotel_name[]" value="<?php echo e($require['hotel_name']); ?>" readonly>
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
                                        <!--                                        <div class="form-group">
                                            <input type="text" class="form-control" id="require_<?php echo e($require->id); ?>" name="book[]" placeholder=""
                                            >
                                        </div>-->
                                    </div>
                                    <div class="col-md-1">
                                        <!--                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="price_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                            <label id="require_price_<?php echo e($require->id); ?>"></label>
                                            <input type="hidden" id="currency_c_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                            <input type="hidden" id="currency_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                        </div>-->
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="bookingMdodal">
                            <div class="d-flex align-items-center totalava_unit">
                                <h5>Available Units :</h5>
                                <span class="ml-2 text-black text-bold"><?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?></span>
                            </div>
                            <div class="row">
                                <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <?php if(in_array(32, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Room Sharing</span>
                                                            <strong><?php echo e(ucfirst($require->sharing)); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array(33, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Flight Class</span>
                                                            <strong><?php echo e(ucfirst($require->flight_class)); ?></strong>
                                                        </div>
                                                        <div class="bh_units">
                                                            <span>Passenger Type</span>
                                                            <strong><?php echo e($require->passenger); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <?php if(in_array(35, json_decode($columns))): ?>
                                                    <div class="bh_units">
                                                            <span>Hotel Name</span>
                                                            <strong><?php echo e($require->hote_name); ?></strong>
                                                        </div>
                                                        <div class="bh_units">
                                                            <span>Hotel Type</span>
                                                            <strong><?php echo e($require->hotel_type); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array(36, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Transport Type</span>
                                                            <strong><?php echo e($require->transport_type); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="bh_units">
                                                        <span>Airport Transfers</span>
                                                        <strong><?php echo e($require->airport_transfers); ?></strong>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <?php if(in_array(38, json_decode($columns))): ?>
                                                        <div class="bh_units">
                                                            <span>Meal Plan</span>
                                                            <strong><?php echo e($require->meal_type); ?></strong>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="bh_units">
                                                        <span>Minimum Pax</span>
                                                        <strong><?php echo e($require->group_size); ?></strong>
                                                    </div>
                                                    <div class="bh_units">
                                                        <input type="text" class="form-control" id="require_<?php echo e($require->id); ?>" name="book[]" placeholder="Enter required units">
                                                    </div>
                                                </div>
                                                <hr class="mt-1 mb-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <strong>Price</strong>
                                                    <div class="form-group mb-0">
                                                        <input type="hidden" class="form-control" id="price_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                                        <strong id="require_price_<?php echo e($require->id); ?>"></strong>
                                                        <input type="hidden" id="currency_c_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                                        <input type="hidden" id="currency_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 d-flex align-items-center justify-content-between">

                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Lead Passenger Name<span id="require_error" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                                        <input type="text" class="form-control" id="" name="lead_pasanger_name" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span style="position:absolute; right:10%;" id="total_pricebook"></span>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Note</label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-12 text-right">
                                <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_book" style="width: 3%;  visibility: hidden;">
                                <span class="text-success" id="mesegese_book" style="margin-left: 10px"></span>
                                <button class="btn btn-primary active mr-2" type="button" id="store_form_book"><i class="fa fa-save"></i> Book Units</button>
                                <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!----End Modal-->
    <div class="modal fade" id="edit-price" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form method="post" name="myEditForm" enctype="multipart/form-data" class="form-inline" id="myEditForm">
            <?php echo csrf_field(); ?>
            <div class="modal-dialog modal-sm" role="document" style="width: 60%;max-width: 100%; right:-190px">
                <div class="modal-content">
                    <div class="modal-header ">
                        <span class="inlineFlax"><h5 class="modal-title text-light" id="exampleModalLabel">Update Pricing</h5></span>
                        <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="itinerary-setup m-t-20">
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div id="pricingModule">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="edit_send_form"><i class="fa fa-save"></i> Update</button>
                            <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif" style="width: 8%; display: none;">
                            <span id="mesegess"></span>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    <?php if(session('success')): ?>
        <div class="modal fade" id="myModal" role="dialog" style="">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5><?php echo e(session('success')); ?></h5>
                        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerSection'); ?>
    <script>
        <?php $departureCount = count($departure_prices); $j = 0; ?>
        var total = 0;
        <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        $("#require_<?php echo e($require->id); ?>").keydown(function () {
            var group_size = <?php echo e($require->group_size); ?>;
            var check = parseInt($("#require_<?php echo e($require->id); ?>").val());
            var sum_no = check + 1;
            var sum_no1 = check + 2;
            if (check != '') {
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_<?php echo e($require->id); ?>").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        alert(1);
                        $("#require_hold_<?php echo e($require->id); ?>").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        alert(2);
                        $("#require_hold_<?php echo e($require->id); ?>").val(sum_no);
                    }
                }
            }
        });
        <?php  $j++; ?>
        $("#require_<?php echo e($require->id); ?>").keyup(function () {
            var required = $("#require_<?php echo e($require->id); ?>").val();
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

                //alert(total);
                //$("#total_pricebook").html("Total Price " +currency+ +total);

            }
            //$("#total_pricebook").html("Total Price " +currency+ +total);
            //alert(total);
        })
        $("#require_<?php echo e($require->id); ?>").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        $("#require_hold_<?php echo e($require->id); ?>").keyup(function () {
            var group_size = <?php echo e($require->group_size); ?>;
            var check = parseInt($("#require_hold_<?php echo e($require->id); ?>").val());
            var sum_no = check + 1;
            var sum_no1 = check + 2;
            if (check != '') {
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_hold_<?php echo e($require->id); ?>").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_hold_<?php echo e($require->id); ?>").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_hold_<?php echo e($require->id); ?>").val(sum_no);
                    }
                }
            }
        });
        $("#require_hold_<?php echo e($require->id); ?>").keyup(function () {
            var required = $("#require_hold_<?php echo e($require->id); ?>").val();
            var total = 0;
            if (required != '') {
                var total_required = required + required;
                var price = $("#price_hold_<?php echo e($require->id); ?>").val();
                var sum = parseInt(required) * parseInt(price);
                var currency = $("#currency_hold_<?php echo e($require->id); ?>").val();
                $("#require_hold_price_<?php echo e($require->id); ?>").html(currency + +sum);
                total = sum + total;
            } else {
                $("#require_hold_price_<?php echo e($require->id); ?>").html('');
            }
            $("#total_price_hold<?php echo e($row->id); ?>").html(total);
        })
        $("#require_hold_<?php echo e($require->id); ?>").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        $(document).ready(function () {
            var default_inr = "<?php echo $inr; ?>";
            $(document).on('keyup', '#price_inr_1', function () {
                //alert('hi');
                var price_inr;

                price_inr = parseFloat($('#price_inr_1').val());
                if (price_inr) {
                    var result = Math.round(price_inr / default_inr);
                    if ($("#price_usd_1").val(result)) {
                        $("#price_usd_1").val(result)
                        $("#price_usd_1").prop("readonly", true);
                    }
                } else {
                    $("#price_usd_1").val('')
                    $("#price_usd_1").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_usd_1', function () {
                var price_usd;
                price_usd = parseFloat($('#price_usd_1').val());
                if (price_usd) {
                    var result = Math.round(price_usd * default_inr);
                    if ($("#price_inr_1").val(result)) {
                        $("#price_inr_1").val(result)
                        $("#price_inr_1").prop("readonly", true);
                    }
                } else {
                    $("#price_inr_1").val('')
                    $("#price_inr_1").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_inr_2', function () {
                //alert('hi');
                var price_inr;

                price_inr = parseFloat($('#price_inr_2').val());
                if (price_inr) {
                    var result = Math.round(price_inr / default_inr);
                    if ($("#price_usd_2").val(result)) {
                        $("#price_usd_2").val(result)
                        $("#price_usd_2").prop("readonly", true);
                    }
                } else {
                    $("#price_usd_2").val('')
                    $("#price_usd_2").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_usd_2', function () {
                var price_usd;
                price_usd = parseFloat($('#price_usd_2').val());
                if (price_usd) {
                    var result = Math.round(price_usd * default_inr);
                    if ($("#price_inr_2").val(result)) {
                        $("#price_inr_2").val(result)
                        $("#price_inr_2").prop("readonly", true);
                    }
                } else {
                    $("#price_inr_2").val('')
                    $("#price_inr_2").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_inr_3', function () {
                //alert('hi');
                var price_inr;

                price_inr = parseFloat($('#price_inr_3').val());
                if (price_inr) {
                    var result = Math.round(price_inr / default_inr);
                    if ($("#price_usd_3").val(result)) {
                        $("#price_usd_3").val(result)
                        $("#price_usd_3").prop("readonly", true);
                    }
                } else {
                    $("#price_usd_3").val('')
                    $("#price_usd_3").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_usd_3', function () {
                var price_usd;
                price_usd = parseFloat($('#price_usd_3').val());
                if (price_usd) {
                    var result = Math.round(price_usd * default_inr);
                    if ($("#price_inr_3").val(result)) {
                        $("#price_inr_3").val(result)
                        $("#price_inr_3").prop("readonly", true);
                    }
                } else {
                    $("#price_inr_3").val('')
                    $("#price_inr_3").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_inr_4', function () {
                //alert('hi');
                var price_inr;

                price_inr = parseFloat($('#price_inr_4').val());
                if (price_inr) {
                    var result = Math.round(price_inr / default_inr);
                    if ($("#price_usd_4").val(result)) {
                        $("#price_usd_4").val(result)
                        $("#price_usd_4").prop("readonly", true);
                    }
                } else {
                    $("#price_usd_4").val('')
                    $("#price_usd_4").prop("readonly", false);
                }
            });
            $(document).on('keyup', '#price_usd_4', function () {
                var price_usd;
                price_usd = parseFloat($('#price_usd_4').val());
                if (price_usd) {
                    var result = Math.round(price_usd * default_inr);
                    if ($("#price_inr_4").val(result)) {
                        $("#price_inr_4").val(result)
                        $("#price_inr_4").prop("readonly", true);
                    }
                } else {
                    $("#price_inr_4").val('')
                    $("#price_inr_4").prop("readonly", false);
                }
            });
            $(document).on('keypress', '#price_inr_1', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $(document).on('keypress', '#price_inr_2', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $(document).on('keypress', '#price_inr_3', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $(document).on('keypress', '#price_inr_4', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });

            $(document).on('keypress', '#price_usd_1', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $(document).on('keypress', '#price_usd_2', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $(document).on('keypress', '#price_usd_3', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
            $(document).on('keypress', '#price_usd_4', function () {
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if (!regex.test(key)) {
                    event.preventDefault();
                    return false;
                }
            });
        });


    </script>
    <script>
        $("#alert").show().delay(7000).queue(function (n) {
            $(this).hide();
            n();
        });
    </script>
    <script type="text/javascript">
        $(".ReleaseDepartue").click(function () {
            if (confirm("Are you sure you want to release this?")) var id = $(this).data("id");
            var token = "<?php echo e(csrf_token()); ?>";
            if (id) {
                $.ajax({
                    url: '/hold/departure/release/' + id,
                    type: 'POST',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function (data) {
                        window.location.reload();
                    }
                });
            }
        });
    </script>
    <script>
        $('#book').keyup(function () {
            var total = '<?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>';
            if ($(this).val() > <?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>) {
                //alert('Value can not be greater than <?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>');
                $("#book_errors").text('Value can not be greater than - ' + total + "");
                $(this).val('')
            } else {
                $("#book_errors").text('');
            }
        });
    </script>

    <script>
        $("#book").keyup(function () {
            var required = $("#book").val();
            var price = '';
            var price1 = '';
            var required1 = $("#single_book").val();
            if (required) {

                var sum = parseInt(required) * parseInt(price);
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#required_price").html(sum);
                $("#total_price").html(total);
            } else {
                var sum = 0;
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#required_price").html(sum);
                $("#total_price").html(total);
            }
        });
        $("#single_book").keyup(function () {
            var required = $("#single_book").val();
            var price = ''
            var sum = parseInt(required) * parseInt(price);
            //alert(sum);
            $("#single_price").html(sum);

            var required1 = $("#book").val();
            var price1 = ''
            var sum1 = parseInt(required1) * parseInt(price1);
            var total = sum + sum1;
            $("#total_price").html(total);
        });
    </script>
    <script>
        $('#hold<?php echo e($departure_details->id); ?>').keyup(function () {
            var value = $(this).val();
            var total = '<?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>';
            var subtotal = value - total;
            if ($(this).val() > <?php echo e(($departure_details->total_seat)-($departure_details->hold_sum + $departure_details->book_sum)); ?>) {
                //alert('Your are Request ' +subtotal  + 'Extra Departure');

                $("#message").text('Request Mode' + subtotal + " Extra Departure");
                //$(this).val('')
            } else {
                $("#message<?php echo e($row->id); ?>").text('');
            }
        });
        $("#hold<?php echo e($departure_details->id); ?>").keyup(function () {
            var required = $("#hold<?php echo e($departure_details->id); ?>").val();
            var price = ''
            var sum = parseInt(required) * parseInt(price);
            $("#total_hold_price").html(sum);
        })
    </script>


    <script>
        $('.edit-item').click(function () {
            $("#pricingModule").html('');

            var id = $(this).data("id");
            //alert(id);
            //$('#edit-price').modal('show');
            if (id) {
                $('#edit_id').val(id);
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(url('/get_pricing_ajax')); ?>?departure_id=" + id,
                    success: function (res) {
                        if (res && res.length > 0) {
                            var html = '';
                            console.log(res);
                            for (data of res) {
                                if (data.pricing && data.pricing.price_inr) {
                                    var priceInr = data.pricing.price_inr ? data.pricing.price_inr : '';
                                    var priceUsd = data.pricing.price_usd ? data.pricing.price_usd : '';
                                } else {
                                    var priceInr = '';
                                    var priceUsd = '';
                                }
                                html += '<div class="row"><div class="col-md-12 col-lg-12 col-xl-12"><label class="labelClass">' + data.type + ' (' + data.name + ')</label><span class="validationError days_error" id="error_price_inr_' + data.id + '"></span></div></div><div class="row"><div class="col-md-12 col-lg-12 col-xl-12 rowMargin"><div class="form-group"><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_inr[' + data.id + ']" value="' + data.symbol_inr + '" readonly><input type="hidden" class="form-control" name="price_type_id[]" value="' + data.id + '"></div><div class="col-md-3 col-lg-3 col-xl-3 md3Margin"><input type="text" class="form-control" name="price_inr[' + data.id + ']" id="price_inr_' + data.id + '" value="' + priceInr + '"></div><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_usd[' + data.id + ']" value="' + data.symbol_usd + '" readonly></div><div class="col-md-3 col-lg-3 col-xl-3 md3Margin"><input type="text" class="form-control" name="price_usd[' + data.id + ']" id="price_usd_' + data.id + '" value="' + priceUsd + '"></div></div></div></div>';
                            }
                            $("#pricingModule").html(html);

                        } else {
                            $("#pricingModule").empty();
                        }
                    }
                });
            } else {
                $("#pricingModule").empty();
            }
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var price_inr_1 = $('#price_inr_1').val();
                if (price_inr_1 == "") {
                    $("span#error_price_inr_1").html('This field is required!');
                    $("input#price_inr_1").focus();
                    return false;
                }

                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "<?php echo e(route('price_update')); ?>",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Price has been updated successfully!</span>");
                        window.location.reload();
                    },
                    statusCode: {
                        504: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        500: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        502: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        400: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Bad request please try again later!</span>");
                        },
                        422: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        404: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Not Found please try again later!</span>");
                        },
                        401: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Not authorized wrong please try again later!</span>");
                        }
                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                    }
                });
            });
        });
    </script>

    <script>
        $('#isAgeSelected').click(function () {
            $("#txtAge").toggle(this.checked);
        });
    </script>
    <script>
        var userName = document.querySelector('.required_unit');
        userName.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = newValue;
        }

        var userName = document.querySelector('.required_unit1');
        userName.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = newValue;
        }

        var userName = document.querySelector('.required_unit2');
        userName.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = newValue;
        }

    </script>
    <script type="text/javascript">
        $(".disableDepartue").click(function () {
            if (confirm("Are you sure you want to approve?")) {
                var id = $(this).data("id");
                //var status = $(this).data("status");
                //var flag = (status == 1) ? 'Unapproved' : 'Approved';
                var token = "<?php echo e(csrf_token()); ?>";
                if (id) {
                    $.ajax({
                        url: '/departure-approve/' + id,
                        type: 'POST',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function (data) {
                            //console.log(data);
                            //window.location.reload();
                            window.location.href = "<?php echo e(route('all_departure')); ?>";
                        }
                    });
                }
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#store_form_book').click(function (e) {
                e.preventDefault();
                //alert('hello');
                $('#gif_book').show();

                $('#gif_book').css('visibility', 'visible');
                //$('#store_form').prop('disabled', true);
                var formDatas = new FormData(document.getElementById('BookDepartureForm'));
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
                        $('#gif_book').hide();
                        if (data.error) {
                            $("#require_error").html(data.error);
                        } else if (data.required) {
                            //alert(data.required);
                            $("#require_error").html(data.required);
                        } else {
                            $('#mesegese_book').html("<span class='sussecmsg'>Success!</span>");
                            location.reload();
                        }

                    },
                    errors: function () {
                        $('#gif_book').hide();
                        $('#mesegese_book').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });

        $(document).ready(function () {
            $('#store_form_hold').click(function (e) {
                e.preventDefault();
                //alert('hello');
                $('#gif').show();

                $('#gif').css('visibility', 'visible');
                //$('#store_form').prop('disabled', true);
                var formDatas = new FormData(document.getElementById('HoldDepartureForm'));
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
                        if (data.error) {
                            $("#error_msg_hold").html(data.error);
                            //window.location = data.url;
                        } else {
                            $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                            $("#error_msg_hold").html(data.required);
                            location.reload();
                        }

                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });

    </script>
    <style type="text/css">
        .modal {
            left: unset;
            padding-right: 0 !important;
        }

        .modal.fade .modal-dialog {
            -webkit-transform: translate(25%, 0);
            transform: translate(25%, 0);
        }

        .modal.show .modal-dialog {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }

        .modal-content {
            background-color: #fdfdfd;
            border-radius: 0;
            min-height: 100vh;
        }

        .modal-backdrop.show {
            opacity: .7;
        }

        .card-title {
            line-height: 24px;
            margin-bottom: 0;
            text-overflow: ellipsis;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .bg-dept {
            background-color: #E3F7F5;
            margin: 0 -1rem;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .bg-per-pax {
            background-color: #F9F9F9;
            padding: 16px 12px;
            position: absolute;
            width: calc(100% - 16px);
            bottom: 10px;
        }

        .dept-from-to {
            margin: 2px 16px 8px;
            position: relative;
            top: 8px;
            border-top: 1px solid #8f8f8f;
            width: calc(100% - 39px);
        }

        .dept-from-to:before, .dept-from-to:after {
            content: "";
            position: absolute;
            top: -6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid #8f8f8f;
            background: #fff;
            left: -6px;
        }

        .dept-from-to:after {
            left: unset;
            right: -12px;
        }

        .dept-from-text, .dept-from-text- {
            text-transform: uppercase;
            font-size: 14px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .dept-from-text {
            max-width: 120px;
        }

        .dept-from-text- {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px
        }

        .dept-from-text- span {
            font-weight: 600;
            margin-left: 6px;
        }

        .price-set, .unit-set li {
            font-size: 18px;
            line-height: 24px;
            font-weight: 900;
            color: #000;
            text-align: right;
            margin-bottom: 0;
        }

        .price-set span, .unit-set li span {
            color: #9b9b9b;
            font-size: 11px;
            line-height: 11px;
            display: block;
            font-weight: 500;
        }

        .unit-set {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }

        .unit-set li {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-right: 16px;
        }

        .dep-model-action-btn a {
            padding: 1px 2px;
            display: inline-block;
            position: relative;
        }

        .dep-model-action-btn a:hover:after {
            display: -webkit-flex;
            display: flex;
            -webkit-justify-content: center;
            justify-content: center;
            background: #444;
            border-radius: 4px;
            color: #fff;
            content: attr(title);
            font-size: 13px;
            padding: 4px 6px;
            position: absolute;
            bottom: 28px;
            top: auto;
            z-index: 99;
        }

        .dep-model-action-btn a:first-child:hover:after {
            width: 108px;
            left: -42px;
        }

        .dep-model-action-btn a:hover:after {
            width: 85px;
            left: -40px;
        }

        .dep-model-action-btn a:hover:before {
            border: solid;
            border-color: #444 transparent;
            border-width: 8px 4px 0 4px;
            content: "";
            left: 6px;
            top: -6px;
            position: absolute;
            z-index: 99;
        }

        .ribbon-box .ribbon {
            padding: 4px 8px;
            line-height: 13px;
        }

        .ribbon-style {
            position: absolute;
            top: 7px;
            px;
            right: 24px;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #fff;
        }

        .modal-header {
        }

        .hold, form, .form-group, label {
            line-height: 1.2;
        }

        div#product-1-item {
            pointer-events: none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/departure/all_departure_details.blade.php ENDPATH**/ ?>