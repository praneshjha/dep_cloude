
<?php $__env->startSection('tagSection'); ?>
    <title>Departure Cloud | My Departure Details</title>
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
                        <h4 class="page-title">Departure Detail</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(($sharing_book+$sharing_hold)>0): ?>
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col">
                                            <?php echo $__env->make('graph.unit_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                        <div class="col">
                                            <?php echo $__env->make('graph.room_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                        <?php if(in_array(32, json_decode($columns))): ?>
                                            <div class="col">
                                                <?php echo $__env->make('graph.sharing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(in_array(33, json_decode($columns))): ?>
                                            <div class="col">
                                                <?php echo $__env->make('graph.flight_class_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(in_array(33, json_decode($columns))): ?>
                                            <div class="col">
                                                <?php echo $__env->make('graph.passenger_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(in_array(35, json_decode($columns))): ?>
                                            <div class="col">
                                                <?php echo $__env->make('graph.hotel_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(in_array(36, json_decode($columns))): ?>
                                            <div class="col">
                                                <?php echo $__env->make('graph.transport_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col">
                                            <?php echo $__env->make('graph.airtransfer_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                        <?php if(in_array(38, json_decode($columns))): ?>
                                            <div class="col">
                                                <?php echo $__env->make('graph.meal_plan_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col">
                                            <?php echo $__env->make('graph.book_hold_graph', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-12 itinerary_linkandImg">
                                


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
                                <div class="mt-3 mt-xl-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex justify-content-center cl_last_update align-items-center">
                                            <span>Last Updated :</span> &nbsp;
                                            <h5 class="m-0"> <?php echo e(date('d M, Y | H:i A', strtotime($departure_details->last_updated_dep))); ?></h5>
                                        </div>
                                        <div class="btn-group d-flex justify-content-end">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departure_edit', $permission)): ?>
                                                <a href="<?php echo e(route('departure_edit',$departure_details->id)); ?>" class="btn btn-info btn-sm pull-right ml-2">Edit Departure</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <h2 class="mb-1 text-capitalize" style="color: #093E8E;text-transform: capitalize;"><?php echo e($departure_details->title); ?></h2>
                                    <p class="mb-0"><a href="" class="userprofileName"> <span><?php echo e($departure_details->company_name); ?></span></a>
                                        <span class="departureID font-16 mb-0 ml-1">(<?php echo e($departure_details->dep_id); ?>)</span>
                                    </p>
                                    <?php if($departure_details->ending_at != null): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-map-marker-radius-outline mr-1"></i> <strong>Departure To : </strong> &nbsp <strong class="text-primary"><?php echo e($departure_details->ending_at); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($departure_details->from != null): ?>
                                        <?php if($departure_types->id != 4): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-map-marker-multiple-outline mr-1"></i>
                                                <strong>Ex : </strong> &nbsp
                                                <strong class="text-primary"><?php echo e($departure_details->from); ?></strong>
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
                                        <i class="mdi mdi-calendar-check mr-1"></i>
                                        <strong>Date : </strong> &nbsp
                                        <strong class=""><?php echo e(date('d M, Y', strtotime($departure_details->start_date))); ?></strong>
                                    </div>
                                    <?php if($departure_types->id != 5): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-weather-night mr-1"></i>
                                            <p style="margin-bottom: 0;"><strong><?php echo e($departure_details->no_of_nights); ?></strong> Nights / <strong><?php echo e($departure_details->no_of_days); ?></strong> Days</p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <i class="mdi mdi-map-marker-distance mr-1"></i>
                                        <p style="margin-bottom: 0;color: #002a68;padding-right: 4px;"><strong>Destination(s) Covered :</strong></p>
                                        <ul class="list-inline mb-0">
                                            <?php $__currentLoopData = $departure_destination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-inline-item"><?php echo e($row->dest_name); ?> <span class="text-dark">(<?php echo e($row->country_name); ?>)</span></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                    <?php if($departure_details->description != null): ?>
                                        <div class="card descriptionBg mt-2">
                                            <div class="card-body p-2 badge-soft-pink">
                                                <h4 class="mb-1 mt-0">Description</h4>
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
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
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
                                        <div class="col-md-4 ribbon-box">
                                            <div class="price-card">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="title">
                                                        Hotel: <span class="text-info"><?php echo e(ucfirst($hotel->name)); ?></span>
                                                    </h6>
                                                    <h6 class="price mb-0">Room: <?php echo e($hotel->total_room); ?></h6>
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
                                    <h4 class=" mt-3">Pricing Details</h4>
                                </div>
                            <?php endif; ?>
                            <?php $__currentLoopData = $departure_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-3">
                                    <div class="price-card pt-1" style="">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="title">
                                                    <?php if(in_array(32, json_decode($columns))): ?>
                                                        <?php if($price->sharing != null): ?>
                                                            <span class="d-block">Room Sharing: <span class="text-info"><?php echo e(ucfirst($price->sharing)); ?></span></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if(in_array(33, json_decode($columns))): ?>
                                                        <?php if($price->flight_class != null): ?>
                                                            <span class="d-block">Flight Class: <span class="text-info"><?php echo e(ucfirst($price->flight_class)); ?></span></span>
                                                        <?php endif; ?>
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
                                                <?php if(in_array(35, json_decode($columns))): ?>
                                                    <?php if($price->hotel_name != ""): ?>
                                                    <div class="transport">
                                                        <small>Hotel Name</small>
                                                        <div>
                                                            <?php echo e($price->hotel_name); ?>

                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if($price->hotel_type != null): ?>
                                                        <div class="transport">
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
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array(33, json_decode($columns))): ?>
                                                    <?php if($price->passenger != null): ?>
                                                        <div class="transport">
                                                            <small>Age Bracket</small>
                                                            <div>
                                                                <?php echo e($price->passenger); ?>

                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if(in_array(36, json_decode($columns))): ?>
                                                    <?php if($price->transport_type != null): ?>
                                                        <div class="transport">
                                                            <small>Transport Type</small>
                                                            <span class=""><?php echo e($price->transport_type); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if($price->airport_transfers != null): ?>
                                                    <div class="transport">
                                                        <small>Airport Transfer</small>
                                                        <span class=""><?php echo e($price->airport_transfers); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(in_array(38, json_decode($columns))): ?>
                                                    <?php if($price->meal_type != null): ?>
                                                        <div class="meal-plan">
                                                            <small>Meal Plan</small>
                                                            <span><?php echo e($price->meal_type); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <div class="transport">
                                                    <small>Minimum Pax</small>
                                                    <span><?php echo e($price->group_size); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if(count($payment_schedule) > 0): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mt-3">Payment Schedule</h4>
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
                        <?php if(count($cancelation_schedule) > 0): ?>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h4 class="mt-0 mt-3">Cancelation Charge</h4>
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

                        <div class="row ">
                            <?php if($hold !=0): ?>
                                <div class="container table-bordered">
                                    <h5 class="card-user_name  mb-2 mt-2">Hold Details <a href="<?php echo e(route('all_departure_hold_history')); ?>" class="btn btn-info btn-sm float-right" style="">All Hold</a></h5>
                                    <?php if(session()->has('msg')): ?>
                                        <div class="alert alert-success"> <?php echo e(session()->get('msg')); ?> </div>
                                    <?php endif; ?>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Dep. Name</th>
                                                    <th>Buyer Name</th>
                                                    <th>Hold Date & time</th>
                                                    <th>Hold Units</th>
                                                    <th>Request More</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php $__currentLoopData = $hold_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$holding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($holding->departure_name); ?></td>
                                                        <td><?php echo e($holding->name); ?></td>
                                                        <td><?php echo e(date('d M, Y h:i a', strtotime($holding->booked_value->created_at."+5 hours +30 minutes"))); ?></td>
                                                        <td><?php echo e(($holding->booked_seat)-($holding->extra_hold)); ?></td>
                                                        <td><?php echo e($holding->extra_hold); ?></td>
                                                        <td><?php echo e($holding->currency->currency_symbol); ?>   <?php echo e($holding->price); ?>

                                                        </td>
                                                        <td>
                                                            <a data-id="<?php echo e($holding->booked_value->unique_id); ?>" class="mr-2 disableDepartue" title="Release"><i class="fa fa-unlink"></i></a>||
                                                            <a href="<?php echo e(url('/departures-hold-history-details/'.$holding->unique_id)); ?>" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </tbody>
                                            </table>
                                            <?php echo e($hold_date->links()); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(count($departure_book)> 0 ): ?>
                                <div class="container table-bordered">
                                    <h5 class="mb-2 mt-2">Booking Details <a href="<?php echo e(route('all_departure_booking_history')); ?>" class="btn btn-info btn-sm">All Booking</a></h5>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Dep. Name</th>
                                                    <th>Buyer Name</th>
                                                    <th>Booking Date & time</th>
                                                    <th>Booking Units</th>
                                                    <th>Total Price</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php $__currentLoopData = $book_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($booking->departure_name); ?></td>
                                                        <td><?php echo e($booking->name); ?></td>
                                                        <td><?php echo e(date('d M, Y h:i a', strtotime($booking->booked_value->created_at."+5 hours +30 minutes"))); ?></td>
                                                        <td><?php echo e($booking->booked_seat); ?>

                                                        </td>
                                                        <td><?php echo e($booking->currency->currency_symbol); ?>   <?php echo e($booking->price); ?>

                                                        </td>
                                                        <td>
                                                            <?php if($booking->booked_value->status == 1): ?>
                                                                Confirm
                                                            <?php else: ?>
                                                                Cancel
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo e(url('/departures-booking-history-details/'.$booking->booked_value->unique_id)); ?>" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
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
            <!-- end row-->

        </div> <!-- end container -->
    </div>

<?php $__env->stopSection(); ?> <?php $__env->startSection('footerSection'); ?>
    <style>
        .a2a_kit.a2a_kit_size_32.a2a_default_style {
            display: none !important;
        }

        div#product-1-item {
            pointer-events: none;
        }


    </style>
    <script type="text/javascript">
        $(".disableDepartue").click(function () {
            if (confirm("Are you sure you want to release this Departure?"))
                var id = $(this).data("id");
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

        $(document).ready(function () {
            $(function () {
                $(this).bind("contextmenu", function (event) {
                    event.preventDefault();
                    //alert('Right click disable in this site!!')
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/departurecloud/resources/views/departure/departure_details.blade.php ENDPATH**/ ?>