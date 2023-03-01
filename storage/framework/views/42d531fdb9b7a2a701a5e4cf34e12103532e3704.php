<div class="col-md-12">
    <div class="row">
        <?php if(count($departures) > 0): ?>
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

                <div class="col-md-4 mb-3" id="GridView">
                    <div class="card-box ribbon-box">
                        <?php if($departure->start_date >= $today): ?>
                            <?php if($departure->status == 1 ): ?>
                                <?php if($departure->approve == 1): ?>
                                    <?php if($departure->total_seat-($departure->book_sum+$departure->hold_sum) > 0 ): ?>
                                        <div class="ribbon-style">
                                            <div class="ribbon ribbon-success float-right">OPEN</div>
                                        </div>
                                    <?php else: ?>
                                        <div class="ribbon-style">
                                            <div class="ribbon ribbon-danger float-right">SOLDOUT</div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="ribbon-style">
                                        <div class="ribbon ribbon-primary float-right">Under Review</div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if($departure->company_publish): ?>
                                    <div class="ribbon-style">
                                        <div class="ribbon ribbon-success float-right">OPEN</div>
                                    </div>
                                <?php else: ?>
                                    <div class="ribbon-style">
                                        <div class="ribbon ribbon-danger float-right">DRAFT</div>
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>
                        <?php else: ?>
                            <div class="ribbon-style">
                                <div class="ribbon ribbon-secondary float-right">CLOSE</div>
                            </div>
                        <?php endif; ?>
                        <div class="mb-21">
                            <h4 class="card-title">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departure_view', $permission)): ?>
                                    <a href="<?php echo e(route('departure_details',$departure->id)); ?>" class="">
                                        <?php echo e($departure->title); ?>

                                    </a>
                                <?php endif; ?>
                            </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="day-info- departureID">ID <?php echo e($departure->dep_id); ?></div>
                                <div class="dep-model-action-btn">
                                    <?php if($departure->status == 0 ): ?>
                                        <a href="javascript:void(0);"
                                           <?php if($departure->inclusion < 1): ?> onClick="alert('Please fill the inclusion section before publishing the departure')"
                                           <?php elseif($departure->DeparturePrice < 1): ?>
                                               onClick="alert('Please ensure that the minimum price before publishing the departure')"
                                           <?php elseif($departure->payment_schedule < 1 ): ?> onClick="alert('Please fill the payment schedule section before publishing the departure')"
                                           <?php elseif($departure->cancelation_schedule < 1 ): ?> onClick="alert('Please fill the cancelation schedule section before publishing the departure')"
                                           <?php elseif($departure->termspayment == ''): ?> onClick="alert('Please fill the Terms section before publishing the departure')"
                                           <?php else: ?> data-toggle="modal" data-target="#myModal<?php echo e($departure->id); ?>"
                                           onClick="PublishModelAllMy(<?php echo e($departure->id); ?>)"
                                           <?php endif; ?> data-id="<?php echo e($departure->id); ?>" data-status="<?php echo e($departure->status); ?>"
                                           title="Publish Departure"><i class="fa fa-upload"></i> </a>
                                    <?php else: ?>
                                    <form id="depDisable-form-<?php echo e($departure->id); ?>" method="post" action="<?php echo e(route('departure_unpublish',$departure->id)); ?>" style="display: none;">
                                    <?php echo csrf_field(); ?>

                                    <?php echo e(method_field('POST')); ?>

                                    </form>
                                    <a href="" onclick="
                                        if (confirm('Are you sure, You want to Unpublish Departure?'))
                                          {
                                            event.preventDefault();
                                            document.getElementById('depDisable-form-<?php echo e($departure->id); ?>').submit();
                                          }
                                          else
                                          {
                                            event.preventDefault();
                                          }
                                        " style="cursor: pointer;" title="Unpublish">
                                        <i class="fas fa-download" style="color: #681f4a;"></i></a>
                                    <?php endif; ?>
                                    <!-- <?php if($departure->company_publish == 0 ): ?>
                                        <a href="javascript:void(0);" <?php if($departure->inclusion < 1): ?>
                                            onClick="alert('Please fill the inclusion section before publishing the departure')"



                                        <?php elseif($departure->DeparturePrice < 1): ?>
                                            onClick="alert('Please ensure that the minimum price before publishing the departure')"



                                        <?php elseif($departure->payment_schedule < 1 ): ?>
                                            onClick="alert('Please fill the payment schedule section before publishing the departure')"




                                        <?php elseif($departure->cancelation_schedule < 1 ): ?>
                                            onClick="alert('Please fill the cancelation schedule section before publishing the departure')"




                                        <?php elseif($departure->termspayment == ''): ?>
                                            onClick="alert('Please fill the Terms section before publishing the departure')"



                                        <?php else: ?>
                                            class="disableDepartue1 text-dark"



                                        <?php endif; ?> data-id="<?php echo e($departure->id); ?>" data-status="<?php echo e($departure->status); ?>" title="Publish for Own"><i class="fa fa-upload"></i></a>




                                    <?php endif; ?> -->
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departure_view', $permission)): ?>
                                        <a href="<?php echo e(route('departure_details',$departure->id)); ?>" class=""
                                           title="View Departure" style=""><i class="fa fa-eye"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departure_edit', $permission)): ?>
                                        <a href="<?php echo e(route('departure_edit',$departure->id)); ?>" class="" style=""
                                           title="Edit Departure"><i class="fa fa-edit"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departure_hold', $permission)): ?>
                                        <?php if($departure->start_date >= $today): ?>
                                            <?php if($departure->approve == 1 || $departure->company_publish == 1): ?>
                                                <?php if(($hold < $date)): ?>
                                                    <a href="" class="" data-toggle="modal"
                                                       data-target="<?php if(($hold < $date)): ?>#modal<?php echo e($departure->id); ?><?php endif; ?>"
                                                       title="Hold Units" style=""><i class="fas fa-pause"></i>
                                                    </a>
                                                    <?php else: ?>
                                                    <a href="javascript:void(0);" title="This is Departure Beyond Hold Date" disabled class="tooltipbubble" style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($departure->approve == 1 || $departure->company_publish == 1): ?>
                                        <?php if($departure->start_date >= $today): ?>
                                            <a href="" class="" data-toggle="modal"
                                               data-target="<?php if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum )) != 0): ?> .bd-example-modal-sm<?php echo e($departure->id); ?>b <?php endif; ?>"
                                               title="Book Units" style=""><i class="fas fa-clipboard-check"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('departure_booking_history', $permission)): ?>
                                        <?php if($departure->approve == 1 || $departure->company_publish == 1): ?>
                                            <a href="<?php echo e(route('departure_booking_history', ['id' => $departure->id])); ?>"
                                               class="" title="Booking History" style=""><i
                                                        class="fas fa-calendar-alt"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(in_array('departure-clone',$permission) == 1): ?>
                                        <?php if($departure->approve == 1): ?>
                                        <span class="dropdown cloneDepDropclick<?php echo e($departure->id); ?>" id="">
                                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md " data-toggle="dropdown" id="cloneDepDrop<?php echo e($departure->id); ?>" onclick="DepCloneClick(<?php echo e($departure->id); ?>)" aria-expanded="true">
                                            <i class="fa fa-clone" aria-hidden="true" style="color:#395eba"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="" class="dropdown-item copyDepartue<?php echo e($departure->id); ?>C" data-toggle="modal"  data-target=".cloneDeparture<?php echo e($departure->id); ?>C" title="Clone Departure" style="color:#000;">Clone to a Departure
                                                </a>
                                                <a href="<?php echo e(route('departure_series',$departure->id)); ?>" class="dropdown-item seriesDepartue<?php echo e($departure->id); ?>C" title="Clone Departure" style="color:#000;" target="_blank">Clone to a Series
                                                </a>
                                            </div>
                                        </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <strong class="d-block text-blue"><?php echo e(date('d M, Y', strtotime($departure->start_date))); ?></strong>
                            <?php if($departure->no_of_nights == null || $departure->no_of_days == null): ?>
                            <?php else: ?>
                                <span class="text-dark font-weight-bold"><?php echo e($departure->no_of_nights); ?><span
                                            class="text-muted"> Nights</span> / <?php echo e($departure->no_of_days); ?> <span
                                            class="text-muted"> Days</span></span>
                            <?php endif; ?>
                            <div class="d-flex position-relative">
                                <?php if($departure->from != ""): ?>
                                <div>
                                    <p class="dept-from-text"><?php echo e($departure->from); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if($departure->ending_at != ""): ?>
                                <div class="position-relative px-2">
                                    <strong style="color:#9f206a;">-</strong>
                                </div>
                                <?php endif; ?>
                                <?php if($departure->ending_at != ""): ?>
                                <div>
                                    <p class="dept-from-text"><?php echo e($departure->ending_at); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if($departure->return_to != ""): ?>
                                <div class="position-relative px-2">
                                    <strong style="color:#9f206a;">-</strong>
                                </div>
                                <?php endif; ?>
                                <?php if($departure->return_to != ""): ?>
                                <div>
                                    <p class="dept-from-text"><?php echo e($departure->return_to); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex position-relative inclusion_icons_show">
                                <?php $__currentLoopData = $departure->inclusion_icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inc_icons): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <img src="<?php echo e($inc_icons->icon); ?>" alt="" class="inclu_icon" width="12"
                                         title="<?php echo e($inc_icons->name); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="bg-dept bg-per-pax">
                            <div class="d-flex justify-content-between">
                                <ul class="unit-set">
                                    <li><?php echo e($departure->total_seat); ?> <span>Total Units</span></li>
                                    <li><?php echo e($departure->book_sum); ?>/<?php echo e($departure->hold_sum); ?><span>B/H Units</span></li>
                                </ul>
                                <p class="price-set">
                                    <?php $__currentLoopData = $departure->price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($price->currency_symbol); ?>  <?php echo e($price->price); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <span>Per PAX</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!----Modal-->
                <div class="modal fade bd-example-modal-sm<?php echo e($departure->id); ?>" id="modal<?php echo e($departure->id); ?>" tabindex="-1"
                     role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content hold">
                            <div class="modal-header">
                                <h5 class="modal-title text-white" id="mySmallModalLabel">Hold Units</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
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
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Avl Units</label>
                                                            <input type="text" class="form-control" id="" name="available" value="<?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?>" name="available" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="bh_units">
                                                    <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Avl Units</label>
                                                            <input type="text" class="form-control" id="" name="available" value="Over Holded:<?php echo e(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)); ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="bh_units">
                                                <div class="form-group">
                                                    <input type="hidden" name="id" value="<?php echo e($departure->id); ?>">
                                                    <input type="hidden" name="current_hours" value="<?php echo e(date('H')); ?>">
                                                    <input type="hidden" name="current_minutes" value="<?php echo e(date('i')); ?>">
                                                    <label for="formGroupExampleInput">Hold Till</label>
                                                    <input type="hidden" class="form-control" id="exampleFormControlSelect2"
                                                           name="hours" value="<?php echo e($departure->hold_duration); ?>" readonly>
                                                    <input type="text" class="form-control" id="exampleFormControlSelect2"
                                                           name="hold_time"
                                                           value="<?php echo e(date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))); ?>"
                                                           readonly>
                                                    <input type="hidden" class="form-control" id="exampleFormControlSelect2"
                                                           name="auto_release"
                                                           value="<?php echo e(date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))); ?>"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="bookingMdodal">
                                        <div class="row">
                                            <?php $__currentLoopData = $departure->departure_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <input type="hidden" name="sairing[]" value="<?php echo e($require->sharing); ?>">
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
                                                            <input type="text" class="form-control" id="" name="hotel_name[]" value="<?php echo e($require->hotel_name); ?>" readonly>
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
                                                        <div class="form-group"><!--                                                            <input class="form-control required_unit<?php echo e($departure->id); ?>" id="require_hold_<?php echo e($require->id); ?>" name="hold[]">-->
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <?php if(in_array(32, json_decode($columns))): ?>
                                                                <div class="bh_units">
                                                                    <span>Sharing Basis</span>
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
                                                                    <strong><?php echo e($require->hotel_name); ?></strong>
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
                                                                    <input class="form-control required_unit<?php echo e($departure->id); ?>" id="require_hold_<?php echo e($require->id); ?>" name="hold[]" placeholder="Enter required units">
                                                                </div>
                                                            </div>
                                                            <hr class="mt-1 mb-1">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <strong>Price</strong>
                                                                <div class="form-group mb-0">
                                                                    <input type="hidden" class="form-control" id="price_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                                                    <label id="require_hold_price_<?php echo e($require->id); ?>"></label>
                                                                    <input type="hidden" id="currency_c_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                                                    <input type="hidden" id="currency_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <span id="mesegese1_<?php echo e($departure->id); ?>" class="text-danger" style="position: absolute; right: 0%;"></span>
                                    <span class="text-danger" id="error<?php echo e($departure->id); ?>"></span>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="exampleFormControlInput1">Lead Passenger Name</label>
                                                <input type="text" class="form-control" id="" name="lead_pasanger_name" placeholder="">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="exampleFormControlInput1">Note</label>
                                                <textarea name="note" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-2">
                                                <span id="total_pricebook<?php echo e($departure->id); ?>"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-md-12 text-right">
                                        <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_<?php echo e($departure->id); ?>"
                                             style="width: 3%;  visibility: hidden;">
                                        <span class="text-success" id="mesegese_<?php echo e($departure->id); ?>"
                                              style="margin-left: 10px"></span>
                                        <button class="btn btn-primary active mr-2" type="button"
                                                id="store_form_hold_<?php echo e($departure->id); ?>"><i class="fa fa-save"></i> Hold
                                            Units
                                        </button>
                                        <button class="btn btn-secondary" data-dismiss="modal" id=""><i
                                                    class="flaticon-cancel-12"></i> Close
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end Model -->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-md-12 text-center">
                <h3 class="" style="position:relative; margin-top: 50px;">Departure not found.</h3>
            </div>
        <?php endif; ?>

        <div class="col-md-12 pagiNate" style="text-align:right;"><?php echo e($departures->withQueryString()->links()); ?></div>
    </div>
</div>

<?php $__env->startSection('footerSection'); ?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo e(asset('js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/customJS/basic-details.js')); ?>"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#from_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd-M-yy',
            });
            $('#to_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd-M-yy',
            });
        });
        // $(".disableDepartue").click(function () {
        //     if (confirm("Are You sure, Want to publish this departure?"))
        //         var id = $(this).data("id");
        //     var status = $(this).data("status");
        //     //var flag = (status == 0)?'Buyer':'Buyer & Supplier';
        //     var token = "<?php echo e(csrf_token()); ?>";
        //     if (id) {

        //         $.ajax({

        //             url: '/departure-disable/' + id,
        //             type: 'POST',
        //             data: {
        //                 "id": id,
        //                 "_token": token,
        //             },
        //             success: function (data) {
        //                 //console.log(data);
        //                 alert('Departure has been published successfully. Details will be reviewed and approved by the admin soon!');
        //                 window.location.href = "<?php echo e(route('departure')); ?>";
        //             }
        //         });
        //     }
        // });
        // $(".disableDepartue1").click(function () {
        //     if (confirm("Are You sure, Want to publish this departure?"))
        //         var id = $(this).data("id");
        //     var status = $(this).data("status");
        //     //var flag = (status == 0)?'Buyer':'Buyer & Supplier';
        //     var token = "<?php echo e(csrf_token()); ?>";
        //     if (id) {

        //         $.ajax({

        //             url: '/departure-company-publish/' + id,
        //             type: 'POST',
        //             data: {
        //                 "id": id,
        //                 "_token": token,
        //             },
        //             success: function (data) {
        //                 //console.log(data);
        //                 alert('Departure has been published successfully.');
        //                 window.location.href = "<?php echo e(route('departure')); ?>";
        //             }
        //         });
        //     }
        // });
    </script>
    <script>
        $("#alert").show().delay(7000).queue(function (n) {
            $(this).hide();
            n();
        });
    </script>
    <script>
        $('.widget-content .custom-width-padding-background').on('click', function () {
            swal({
                title: 'Custom width, padding, background.',
                width: 600,
                padding: "7em",
                customClass: "background-modal",
                background: '#fff url(assets/img/sweet-bg.jpg) no-repeat 100% 100%',
            })
        })
    </script>
    <?php $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php
        if (ucfirst(auth::user()->country) == 'India') {
            $value = intval($row->price_inr);
            $single_value = intval($row->single_supplyment_price_inr);
        } else {
            $value = intval($row->price_usd);
            $single_value = intval($row->single_supplyment_price_usd);
        }

        ?>


        <div class="modal fade cloneDeparture<?php echo e($row->id); ?>C" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel" aria-hidden="true" style="width: 50%">
            <div class="modal-dialog modal-mb w-100" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white1" id="mySmallModalLabel">Clone to a Departure</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <form id="myCopyForm<?php echo e($row->id); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Departure Name
                                    <span class="validationError" id="titleerror_<?php echo e($row->id); ?>"></span></label>
                                <input type="hidden" class="form-control" id="dep-id" name="dep_id"
                                       value="<?php echo e(($row->id)); ?>">
                                <input type="text" class="form-control" id="title_<?php echo e(($row->id)); ?>" name="title"
                                       value="<?php echo e($row->title); ?>">

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">Departure Date<span class="validationError"></span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control pull-right" value="<?php echo e(date('d M Y', strtotime($row->start_date))); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">New Departure Date<span class="validationError" id="start_dateerror_<?php echo e($row->id); ?>"></span></label>
                                        <div class="input-group date">
                                            <input type="text"
                                                   class="form-control pull-right start_date<?php echo e($row->id); ?> fromdate"
                                                   name="start_date" id="start_date<?php echo e($row->id); ?>">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="calendar<?php echo e($row->id); ?>"><i class="fa fa-calendar calendar<?php echo e($row->id); ?>"
                                                            aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Total Units<span class="validationError" id="total_seaterror_<?php echo e($row->id); ?>"></span></label>
                                        <input type="text" class="form-control" name="total_seat"
                                               value="<?php echo e($row->total_seat); ?>" id="total_seat_<?php echo e($row->id); ?>">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal" id=""><i
                                        class="flaticon-cancel-12"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary" id="copyDeparture_<?php echo e($row->id); ?>">Clone
                                Departure
                            </button>
                            <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_<?php echo e($row->id); ?>"
                                 style="width: 8%; visibility: hidden;">
                            <span class="text-success" id="mesegese_<?php echo e($row->id); ?>" style="margin-left: 10px"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!----End Copy Modal-->

        <script>

            $(document).ready(function () {
                $('.start_date<?php echo e($row->id); ?>').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd-M-yy',
                    minDate: 0,
                });
                $('.calendar<?php echo e($row->id); ?>').click(function () {
                    $(".start_date<?php echo e($row->id); ?>").focus();
                });

                $('#countryUrl').select2({
                    placeholder:'Select Country',
                    // ajax: {
                    //     url: "<?php echo e(route('public_destinations')); ?>",
                    //     dataType: 'json',
                    //     delay: 250,
                    //     processResults: function (data) {
                    //         return {
                    //             results: $.map(data, function (item) {
                    //                 return {
                    //                     text: item.dest_name,
                    //                     id: item.id
                    //                 }
                    //             })
                    //         };
                    //     },
                    //     cache: true
                    // }
                });
            });


            $('#check<?php echo e($row->id); ?>').keyup(function () {
                var value = $(this).val();
                var total = '<?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>';
                var subtotal = value - total;
                if ($(this).val() > <?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>) {
                    //alert('Your are Request ' +subtotal + ' Extra Departure');
                    $("#message<?php echo e($row->id); ?>").text('Request Mode ' + subtotal + " Extra Departure");
                    //$(this).val('')
                } else {
                    $("#message<?php echo e($row->id); ?>").text('');
                }
            });
            $("#check<?php echo e($row->id); ?>").keyup(function () {
                var required = $("#check<?php echo e($row->id); ?>").val();
                var price = '<?php echo e($value); ?>'
                var sum = parseInt(required) * parseInt(price);
                $("#total_hold_price<?php echo e($row->id); ?>").html(sum);
            })
        </script>

        <script>
            $('#book<?php echo e($row->id); ?>').keyup(function () {
                var total = '<?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>';
                if ($(this).val() > <?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>) {
                    //alert('Value can not be greater than <?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>');
                    $("#error<?php echo e($row->id); ?>").text('Value can not be greater than - ' + total + "");
                    $(this).val('')
                }
            });
            $("#book<?php echo e($row->id); ?>").keyup(function () {
                var required = $("#book<?php echo e($row->id); ?>").val();
                var price = '<?php echo e($value); ?>'
                var sum = parseInt(required) * parseInt(price);

                var required1 = $("#single_bookbook<?php echo e($row->id); ?>").val();
                var price1 = '<?php echo e($single_value); ?>'
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;

                //alert(sum);
                $("#required_pricebook<?php echo e($row->id); ?>").html(sum);

                $("#total_pricebook<?php echo e($row->id); ?>").html(sum);
            })
            $("#single_bookbook<?php echo e($row->id); ?>").keyup(function () {
                var required = $("#single_bookbook<?php echo e($row->id); ?>").val();
                var price = '<?php echo e($single_value); ?>'
                var sum = parseInt(required) * parseInt(price);
                //alert(sum);
                $("#single_pricebook<?php echo e($row->id); ?>").html(sum);

                var required1 = $("#book<?php echo e($row->id); ?>").val();
                var price1 = '<?php echo e($value); ?>'
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#total_pricebook<?php echo e($row->id); ?>").html(total);
            })
        </script>

        <!-- <script>
  $(document).ready(function(){
    $("#1<?php echo e($row->id); ?>").click(function(){
      location.reload(true);
    });
  });
  $(document).ready(function(){
    $("#2<?php echo e($row->id); ?>").click(function(){
      location.reload(true);
    });
  });
  $(document).ready(function(){
    $("#3<?php echo e($row->id); ?>").click(function(){
      location.reload(true);
    });
  });
  $(document).ready(function(){
    $("#1<?php echo e($row->id); ?>").click(function(){
      location.reload(true);
    });
  });
</script> -->
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#copyDeparture_<?php echo e($row->id); ?>').click(function (e) {
                    e.preventDefault();
                    jQuery('#gif_<?php echo e($row->id); ?>').show();
                    // var id = jQuery('#dep-id').val();
                    // alert(id);

                    var title = jQuery('#title_<?php echo e($row->id); ?>').val();
                    if (title == "") {
                        jQuery('span#titleerror_<?php echo e($row->id); ?>').html('This field is required!');
                        jQuery('input#title_<?php echo e($row->id); ?>').focus();
                        return false;
                    } else {
                        jQuery('span#titleerror_<?php echo e($row->id); ?>').hide();
                    }
                    var start_date = jQuery('#start_date_<?php echo e($row->id); ?>').val();
                    if (start_date == '') {
                        jQuery('span#start_dateerror_<?php echo e($row->id); ?>').html('This field is required!');
                        jQuery('input#start_date_<?php echo e($row->id); ?>').focus();
                        return false;
                    } else {
                        jQuery('span#start_dateerror_<?php echo e($row->id); ?>').hide();
                    }

                    var total_seat = jQuery('#total_seat_<?php echo e($row->id); ?>').val();
                    if (total_seat == "") {
                        jQuery('span#total_seaterror_<?php echo e($row->id); ?>').html('This field is required!');
                        jQuery('input#total_seat_<?php echo e($row->id); ?>').focus();
                        return false;
                    } else {
                        jQuery('span#total_seaterror_<?php echo e($row->id); ?>').hide();
                    }

                    jQuery('#gif_<?php echo e($row->id); ?>').css('visibility', 'visible');
                    jQuery('#copyDeparture_<?php echo e($row->id); ?>').html('Please wait...')
                    jQuery('#copyDeparture_<?php echo e($row->id); ?>').prop('disabled', true);

                    var formDatas = new FormData(document.getElementById('myCopyForm<?php echo e($row->id); ?>'));
                    jQuery.ajax({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "<?php echo e(route('departure_copy')); ?>",
                        data: formDatas,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#gif_<?php echo e($row->id); ?>').hide();
                            console.log(data);
                            $('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg'>Success!</span>");
                            //window.location = data.url;
                            window.location.reload();
                        },
                        statusCode: {
                            500: function (status) {
                                console.log(status);
                                jQuery('#gif_<?php echo e($row->id); ?>').hide();
                                jQuery('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            },

                            400: function () {
                                jQuery('#gif_<?php echo e($row->id); ?>').hide();
                                jQuery('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            },
                            419: function () {
                                jQuery('#gif_<?php echo e($row->id); ?>').hide();
                                jQuery('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            },
                            401: function () {
                                jQuery('#gif_<?php echo e($row->id); ?>').hide();
                                jQuery('#mesegese_<?php echo e($row->id); ?>').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            }
                        }

                    });
                });
            });
        </script>






        <script>
            $('.edit-item').click(function () {
                $("#pricingModule").html('');
                var id = $(this).data("id");
                $('#editModal').modal('show');
                if (id) {
                    $('#edit_id').val(id);
                    $.ajax({
                        type: "GET",
                        url: "<?php echo e(url('/get_pricing_ajax')); ?>?departure_id=" + id,
                        success: function (res) {
                            if (res && res.length > 0) {
                                var html = '';
                                for (data of res) {
                                    if (data.pricing && data.pricing.price_inr) {
                                        var priceInr = data.pricing.price_inr ? data.pricing.price_inr : '';
                                        var priceUsd = data.pricing.price_usd ? data.pricing.price_usd : '';
                                    } else {
                                        var priceInr = '';
                                        var priceUsd = '';
                                    }
                                    html += '<div class="row"><div class="col-md-12 col-lg-12 col-xl-12" style="margin-bottom: 20px;"></div></div>';
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
        <script>
            $('#isAgeSelected<?php echo e($row->id); ?>').click(function () {
                $("#txtAge<?php echo e($row->id); ?>").toggle(this.checked);
            });
        </script>
        <script>
            // var userName = document.querySelector('.required_unit<?php echo e($row->id); ?>');
            // userName.addEventListener('input', restrictNumber);

            // function restrictNumber(e) {
            //     var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            //     this.value = newValue;
            // }

            // var userName = document.querySelector('.required_unit1<?php echo e($row->id); ?>');
            // userName.addEventListener('input', restrictNumber);

            // function restrictNumber(e) {
            //     var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            //     this.value = newValue;
            // }

            // var userName = document.querySelector('.required_unit2<?php echo e($row->id); ?>');
            // userName.addEventListener('input', restrictNumber);

            // function restrictNumber(e) {
            //     var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            //     this.value = newValue;
            // }

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
                               // alert(data.required);
                                $("#required_error_<?php echo e($row->id); ?>").html(data.error);
                                $("#required_error_<?php echo e($row->id); ?>").html(data.required);
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
        <div class="modal fade bd-example-modal-sm<?php echo e($row->id); ?>b" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-mb " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="mySmallModalLabel">Book Units</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <form role="form" id="BookDepartureForm_<?php echo e($row->id); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="id" value="<?php echo e($row->id); ?>">
                                    <div class="d-flex align-items-center totalava_unit">
                                        <h5>Available Units :</h5>
                                        <span class="ml-2 text-black text-bold"><?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?></span>
                                        <input type="hidden" class="form-control" id="" name="available"
                                               value="<?php echo e(($row->total_seat)-($row->hold_sum + $row->book_sum)); ?>"
                                               readonly>
                                    </div>
                                </div>
                                <!--                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Sharing</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Transport Type</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Hotel Type</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Meal Plan</label>
                                                                    </div>
                                                                </div>
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
                                                                </div>-->
                            </div>
                            <div class="bookingMdodal">
                                <div class="row">
                                    <?php $__currentLoopData = $row->departure_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="sairing[]" value="<?php echo e($require->sharing); ?>">
                                        <div class="row d-none">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <!-- <label for="exampleFormControlInput1"><?php echo e(ucfirst($require->sharing)); ?></label> -->
                                                    <input type="text" class="form-control" id="" name=""
                                                           value="<?php echo e(ucfirst($require->sharing)); ?>" readonly>
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
                                                    <input type="text" class="form-control" id="" name="hotel_name[]" value="<?php echo e($require->hotel_name); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="hotel_type[]" value="<?php echo e($require->hotel_type); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id=""
                                                           name="transport_type[]"
                                                           value="<?php echo e($require->transport_type); ?>" readonly>
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
                                                    <!--                                                    <input type="text" class="form-control" id="require_<?php echo e($require->id); ?>" name="book[]" placeholder="Enter require unit">-->
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                
                                            </div>
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
                                                        <!-- <?php echo e($columns); ?> -->
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
                                                            <strong><?php echo e($require->hotel_name); ?></strong>
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
                                                            <input type="text" class="form-control" id="require_<?php echo e($require->id); ?>" name="book[]" placeholder="Enter require unit">
                                                        </div>
                                                    </div>
                                                    <hr class="mt-1 mb-1">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <strong>Price</strong>
                                                        <div class="form-group mb-0">
                                                            <input type="hidden" class="form-control" id="price_<?php echo e($require->id); ?>" name="price[]" value="<?php echo e($require->price); ?>" style="border:none">
                                                            <div class="">
                                                                <label id="require_currency_<?php echo e($require->id); ?>"></label>
                                                                <input type="text" id="require_price_<?php echo e($require->id); ?>" style="border:none;" readonly>
                                                            </div>
                                                            <input type="hidden" id="currency_c_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_code); ?> " name="currency">
                                                            <input type="hidden" id="currency_<?php echo e($require->id); ?>" value="<?php echo e($require->currency_symbol); ?> " name="currency_symbol">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <span class="text-danger" id="error<?php echo e($row->id); ?>"></span>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="exampleFormControlInput1">Lead Passenger Name<span id="error_book_msg_<?php echo e($row->id); ?>" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                                        <input type="text" class="form-control" id="require_pasanger" name="lead_pasanger_name" placeholder="">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleFormControlInput1">Note</label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <span id="total_price_book<?php echo e($row->id); ?>"></span>
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
                    </form>
                </div>
            </div>
        </div>

        <script>
            <?php $departureCount = count($departure->departure_price); $j = 0; ?>
            var total = 0;
            <?php $__currentLoopData = $row->departure_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $("#require_<?php echo e($require->id); ?>").keyup(function () {
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
                            $("#require_<?php echo e($require->id); ?>").val(sum_no1);
                        } else if ((check % 3) == 2) {
                            $("#require_<?php echo e($require->id); ?>").val(sum_no);
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
                    $("#require_currency_<?php echo e($require->id); ?>").html(currency);
                    $("#require_price_<?php echo e($require->id); ?>").val(sum);
                    total = sum + total;
                } else {
                    $("#require_price_<?php echo e($require->id); ?>").val('');

                    var price = $("#price_<?php echo e($require->id); ?>").val();
                    var currency = $("#currency_<?php echo e($require->id); ?>").val();
                    price = parseInt(price);
                    total = total - price;
                    //alert(currency);
                    //$("#total_price_book<?php echo e($row->id); ?>").html("Total Price " + currency+' '+total);
                }


                //$("#total_price_book<?php echo e($row->id); ?>").html("Total Price " + currency+' '+total);
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!----Modal-->
        <div class="modal fade" id="myModal<?php echo e($departure->id); ?>" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content" style="min-height: 152px; width: 50%;">
                    <form>
                        <div class="modal-body">
                            <input type="hidden" name="departure_id<?php echo e($departure->id); ?>" value="<?php echo e($departure->id); ?>">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="publish2"
                                       name="publish<?php echo e($departure->id); ?>" value="2" checked>
                                <label class="form-check-label" for="materialChecked">Publish for all</label>
                            </div>
                            <?php if($departure->company_publish == 0): ?>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="publish1"
                                           name="publish<?php echo e($departure->id); ?>" value="1">
                                    <label class="form-check-label" for="materialUnchecked">Publish for own
                                        company</label>
                                </div>
                            <?php endif; ?>

                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer" style="text-align: right;">
                            <button class="btn btn-primary btn-sm" id="departure_published<?php echo e($departure->id); ?>">Submit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!----End Modal-->

        <script>
            // model for all and my
            $("#departure_published<?php echo e($departure->id); ?>").click(function () {
                if (confirm("Are You sure, Want to publish this departure?"))
                    var id = $('input[name="departure_id<?php echo e($departure->id); ?>"]').val();
                var status = $(this).data("status");
                var publish = $('input[name="publish<?php echo e($departure->id); ?>"]:checked').val();

                var token = "<?php echo e(csrf_token()); ?>";
                if (publish == 2) {

                    $.ajax({
                        url: '/departure-disable/' + id,
                        type: 'POST',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function (data) {
                            console.log(data);
                            alert('Departure has been published successfully. Details will be reviewed and approved by the admin soon!');
                            window.location.href = "<?php echo e(route('departure')); ?>";
                        }
                    });
                }
                if (publish == 1) {
                    $.ajax({
                        url: '/departure-company-publish/' + id,
                        type: 'POST',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function (data) {
                            console.log(data);
                            alert('Departure has been published successfully.');
                            window.location.href = "<?php echo e(route('departure')); ?>";
                        }
                    });
                }
            });
        </script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <script type="text/javascript">
        function PublishModelAllMy(id) {

            //$('#myModal'+id).modal('show');
        }
    </script>
    <style type="text/css">
        .dep-model-action-btn a:hover:before{
            display: none;
        }
        .dep-model-action-btn a:hover:after{
            display: none !important;
        }
        .pagiNate nav{float: right;}
        .totalava_unit {
                background: transparent;
                margin-bottom: 20px;
                padding: 0 20px;
            }
        .modal-dialog {
            max-width: 950px;
            width: 950px;
            margin: 0 auto;
        }

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
            /*background-color: #E3F7F5;
            margin: 0 -1rem;
            padding: 8px 12px;
            border-radius: 6px;*/
        }

        .bg-per-pax {
            /* background-color: #F9F9F9;
             padding: 16px 12px;
             position: absolute;
             width: calc(100% - 16px);
             bottom: 10px;*/
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

        /*.price-set, .unit-set li {
            font-size: 18px;
            line-height: 24px;
            font-weight: 900;
            color: #000;
            text-align: right;
            margin-bottom: 0;
        }*/

        /*.price-set span, .unit-set li span {
            color: #9b9b9b;
            font-size: 11px;
            line-height: 11px;
            display: block;
            font-weight: 500;
        }*/

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
            padding: 1px 3px;
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
            width: 119px;
            left: -42px;
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
            padding: 3px 8px;
            line-height: 13px;
        }

        .ribbon-style {
            position: absolute;
            top: 7px;
            right: 24px;
        }

        .card-box {
            /*height: calc(100% - 24px);*/
            /*padding-bottom: 100px;*/
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #fff;
        }

        .modal-header {
            /*height: 61px;
            align-items: center;
            background-color: #093E8E;
            margin-top: 70px;
            border-radius: 0;*/
        }

        .modal-footer {
            display: block;
            display: -ms-flexbox;
            display: block;
            -webkit-box-align: center;
            -ms-flex-align: center;
            /* align-items: center; */
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: space-between;
            padding: 1rem;
            border-top: none;
        }

    </style>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/timepicker.css')); ?>">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <style>
        input[type="text"]::placeholder {
            font-size: 10px;
        }

        #ui-datepicker-div {
            z-index: 999999999999999999 !important;
        }

        .ui-datepicker-buttonpane.ui-widget-content {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php /**PATH /var/www/departure/resources/views/departure/departure_index_data.blade.php ENDPATH**/ ?>