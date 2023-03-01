<?php $__env->startSection('tagSection'); ?>
    <title>Add Pricing | Departure Cloud</title>
<?php $__env->stopSection(); ?>
<style type="text/css">
    .modal-dialog{max-width:800px;width:600px;margin:0 auto}.modal{left:unset;padding-right:0!important}.modal.fade .modal-dialog{-webkit-transform:translate(25%,0);transform:translate(25%,0)}.modal.show .modal-dialog{-webkit-transform:translate(0,0);transform:translate(0,0)}.modal-backdrop.show{opacity:.7}.dep-model-action-btn a{padding:1px 2px;display:inline-block;position:relative}.dep-model-action-btn a:hover:after{display:-webkit-flex;display:flex;-webkit-justify-content:center;justify-content:center;background:#444;border-radius:4px;color:#fff;content:attr(title);font-size:13px;padding:4px 6px;position:absolute;bottom:28px;top:auto;z-index:99}.dep-model-action-btn a:first-child:hover:after{width:108px;left:-42px}.dep-model-action-btn a:hover:after{width:85px;left:-40px}.dep-model-action-btn a:hover:before{border:solid;border-color:#444 transparent;border-width:8px 4px 0 4px;content:"";left:6px;top:-6px;position:absolute;z-index:99}.form-control:disabled,.form-control[readonly]{background-color:#fff}.hold,form,.form-group,label{line-height:1.2} 
        #notificationDropdownAlert{
            padding: 28 15px !important;
        }   
</style>
<?php $__env->startSection('content'); ?>

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Pricing (Per PAX)</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Pricing</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box formCard">
                        <h4 class="mt-0 depnameHeading"><?php echo e($departure->title); ?></h4>
                        <?php echo $__env->make('layouts/itinerary_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <?php if(!empty($first_data->currency_symbol) && !empty($first_data->currency_code)): ?>
                            <form id="changePricingAll">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Change Currency<span class="text-danger">*</span></label>
                                        <select class="form-control input-group-text" name="currency_change" id="currency_change" style="background-color: #e9ecef;" required>
                                            <?php $__currentLoopData = $currency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($row->currency_code); ?>,<?php echo e($row->currency_symbol); ?>" <?php if($row->currency_symbol == $first_data->currency_symbol): ?> selected <?php endif; ?>><?php echo e($row->currency_symbol); ?> (<?php echo e($row->currency_code); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <p id="currency_change_error" class="text-danger"></p>
                                    </div>
                                </div>
                            </div>
                            </form>
                        <?php endif; ?>
                        <form id="InclusionForm">
                            <?php echo csrf_field(); ?>
                            <?php if(empty($first_data->currency_symbol) && empty($first_data->currency_code)): ?>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Currency<span class="text-danger">*</span></label>
                                            <select class="form-control input-group-text" name="currency" id="currency" style="background-color: #e9ecef;" required>
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $currency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($row->currency_code); ?>,<?php echo e($row->currency_symbol); ?>" <?php if($row->currency_symbol == Auth::user()->currency_symbol): ?> selected <?php endif; ?>><?php echo e($row->currency_symbol); ?> (<?php echo e($row->currency_code); ?>)</option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <p id="currency_error" class="text-danger"></p>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <input type="hidden" id="currency" name="currency" value="<?php echo e($first_data->currency_code); ?>,<?php echo e($first_data->currency_symbol); ?>">
                            <?php endif; ?>

                            <div class="row wrappers">
                                <input type="hidden" name="departure_type" value="<?php echo e($departure->departure_type); ?>">
                                <?php if(in_array(32, json_decode($columns))): ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Room Sharing<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control" name="sharing" id="sairing_validateshhj">
                                                <option value="">Room Sharing</option>
                                                <?php $__currentLoopData = $sairing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($row->sairing); ?>" <?php if($row->sairing == 'Double'): ?> selected <?php endif; ?>><?php echo e($row->sairing); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <span id="sairing_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array(33, json_decode($columns))): ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Flight Class<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control" name="flight_class" id="flight_class">
                                                <option value="">Select Flight Class</option>
                                                <?php $__currentLoopData = $flight_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight_classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($flight_classe->name); ?>" <?php if($flight_classe->name == 'Economy Class'): ?> selected <?php endif; ?>><?php echo e($flight_classe->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Age Bracket</label>
                                            <select class="form-control" name="passenger" id="passenger">
                                                <!-- <option value="">Age Bracket</option> -->
                                                <?php $__currentLoopData = $passenger_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($passenger_type->name); ?>"><?php echo e($passenger_type->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array(35, json_decode($columns))): ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hotel Name</label>
                                            <select class="form-control  hotel" name="hotel_name" id="hotel_name">
                                                <?php if(count($dep_hotel_name)<=0): ?>
                                                    <option value="">Hotel Name</option>
                                                <?php else: ?>
                                                <?php $__currentLoopData = $dep_hotel_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($hName->name); ?>">
                                                    <?php echo e($hName->name); ?> </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hotel Category</label>
                                            <select class="form-control  hotel" name="hotel" id="hotel">
                                                <?php if($hotel_categories == ""): ?>
                                                    <option value="">Hotel Category</option>
                                                <?php else: ?>
                                                    <option value="<?php echo e($hotel_categories); ?>">
                                                    <?php echo e($hotel_categories); ?> </option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                <?php endif; ?>
                                <?php if(in_array(36, json_decode($columns))): ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Transport Type</label>
                                            <select class="form-control transport_type" name="transport_type" id="transport_type">
                                                <option value="">Select Transport</option>
                                                <?php $__currentLoopData = $transport_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transport_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($transport_type->code); ?>" <?php if(($departure->departure_type == 1 && $transport_type->code == 'SIC') || ($departure->departure_type == 2 && $transport_type->code == 'SIC')): ?> selected <?php endif; ?>><?php echo e($transport_type->name); ?> <?php if($transport_type->code != 'Private'): ?>
                                                            (<?php echo e($transport_type->code); ?>)
                                                        <?php endif; ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Airport Transfers</label>
                                        <select class="form-control" name="airport_transfers" id="airport_transfers">
                                            <?php $__currentLoopData = $airport_transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airport_transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($airport_transfer->code); ?>" <?php if($departure->departure_type == 5 && $airport_transfer->code == 'Not Included'): ?> selected <?php elseif($airport_transfer->code == 'SIC'): ?> selected
                                                        <?php elseif($departure->departure_type == 3 && $airport_transfer->code == 'Not Included'): ?>  selected <?php endif; ?>><?php echo e($airport_transfer->name); ?> <?php if($airport_transfer->code != 'Private' && $airport_transfer->code != 'Not Included'): ?>
                                                        (<?php echo e($airport_transfer->code); ?>)
                                                    <?php endif; ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if(in_array(38, json_decode($columns))): ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Meal Plan</label>
                                            <select class="form-control meal_plan" name="meal_type" id="meal_type">
                                                <option value="">Select Meal</option>
                                                <?php $__currentLoopData = $meal_plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal_plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($meal_plan->code); ?>" <?php if($meal_plan->code == $mealplan): ?> selected <?php endif; ?>><?php echo e($meal_plan->name); ?> (<?php echo e($meal_plan->code); ?>)</option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <div class="col-md-2">
                                    <label for="">Minimum Pax</label>
                                    <input type="text" class="form-control" id="group_size_error" name="group_size" value="1">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Note</label>
                                        <input type="text" class="form-control" id="other" name="other">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Price<span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <div class="input-group-prepend ">

                                            </div>
                                            <input type="text" class="form-control" id="price_validates" name="price">
                                        </div>
                                        <span class="text-danger" id="price_error_msg"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end align-items-start">
                                    <div>
                                        <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif" style="width: 36px; visibility: hidden;">
                                        <span class="text-success d-block" id="mesegese"></span>
                                    </div>
                                    <button class="btn btn-primary active mx-2" type="button" id="store_form"><i class="fa fa-save"></i> Add</button>
                                    <a href="<?php echo e(route('terms_payment_create',request()->route('id'))); ?>" class="btn btn-primary active" type="button"><i class="fa fa-save"></i> Next</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if(count($data)> 0 ): ?>
                        <div class="card-box">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <?php if(in_array(32, json_decode($columns))): ?>
                                            <th>Room Sharing</th>
                                        <?php endif; ?>
                                        <?php if(in_array(33, json_decode($columns))): ?>
                                            <th>Flight Class</th>
                                            <th>Passenger Type</th>
                                        <?php endif; ?>
                                        <?php if(in_array(35, json_decode($columns))): ?>
                                            <th>Hotel Name</th>
                                            <th>Hotel Category</th>
                                        <?php endif; ?>
                                        <?php if(in_array(36, json_decode($columns))): ?>
                                            <th>Transport Type</th>
                                        <?php endif; ?>
                                        <th>Airport Transfers</th>
                                        <?php if(in_array(38, json_decode($columns))): ?>
                                            <th>Meal Plan</th>
                                        <?php endif; ?>
                                        <th>Minimum Pax</th>
                                        <th>Note</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key+1); ?></td>
                                            <?php if(in_array(32, json_decode($columns))): ?>
                                                <td><?php echo e($row->sharing); ?></td>
                                            <?php endif; ?>
                                            <?php if(in_array(33, json_decode($columns))): ?>
                                                <td><?php echo e($row->flight_class); ?></td>

                                                <td><?php echo e($row->passenger); ?></td>
                                            <?php endif; ?>
                                            <?php if(in_array(35, json_decode($columns))): ?>
                                                <td><?php echo e($row->hotel_name); ?></td>
                                                <td><?php echo e($row->hotel_type); ?></td>
                                            <?php endif; ?>
                                            <?php if(in_array(36, json_decode($columns))): ?>
                                                <td><?php echo e($row->transport_type); ?></td>
                                            <?php endif; ?>
                                            <td><?php echo e($row->airport_transfers); ?></td>
                                            <?php if(in_array(38, json_decode($columns))): ?>
                                                <td><?php echo e($row->meal_type); ?></td>
                                            <?php endif; ?>
                                            <td><?php echo e($row->group_size); ?></td>
                                            <td><?php echo e($row->other); ?></td>
                                            <td><?php echo e($row->currency_symbol); ?> <?php echo e($row->price); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm<?php echo e($row->id); ?>" title="Edit"><i class="fa fa-edit"></i></a> |
                                                <form id="posts-form-<?php echo e($row->id); ?>" method="post" action="<?php echo e(route('penable_disable',$row->id)); ?>" style="display: none;">
                                                    <?php echo csrf_field(); ?>

                                                    <?php echo e(method_field('POST')); ?> <!-- posts query -->
                                                </form>
                                                <?php if($row->status == 0): ?>
                                                    <a href="" onclick="
                                                if (confirm('Are you sure, You want to enable?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('posts-form-<?php echo e($row->id); ?>').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Enable">
                                                        <i class="fas fa-trash"></i></a>
                                                <?php else: ?>
                                                    <a href="" onclick="
                                                if (confirm('Are you sure, You want to disable?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('posts-form-<?php echo e($row->id); ?>').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Disable">
                                                        <i class="fas fa-upload"></i></a>
                                                <?php endif; ?>
                                                <form id="default-posts-form-<?php echo e($row->id); ?>" method="post" action="<?php echo e(route('default_price',$row->id)); ?>" style="display: none;">
                                                    <?php echo csrf_field(); ?>

                                                    <?php echo e(method_field('POST')); ?> <!-- posts query -->
                                                </form>
                                                <?php if($row->default_price == 0): ?>
                                                <a href="" onclick="
                                                if (confirm('Are you sure, You want to select price default?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('default-posts-form-<?php echo e($row->id); ?>').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Enable">
                                                    <i class="fas fa-square"></i></a>
                                                <?php else: ?>
                                                    <a href="" onclick="
                                                if (confirm('Are you sure, You want to unselect price default?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('default-posts-form-<?php echo e($row->id); ?>').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Disable">
                                                    <i class="fas fa-check-square"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerSection'); ?>

    <!-- <script type="text/javascript">
    $(document).ready(function(){
    var maxFields = 20; //Input fields increment limitation
    var addButtons = $('.add_button'); //Add button selector
    var wrappers = $('.wrappers'); //Input field wrapper
    var fieldHTMLs = '<div class="col-md-12" id="rowes"><div class="row"><div class="col-md-3" style=""><label>Sharing <label for="">Sharing<span class="text-danger">*</span><span id="msg" class="text-danger"></span></label><div class="form-group"><input name="sharing[]" id="sharing" class="form-control" type="text"></div></div><div class="col-md-3"><div class="form-group"><label for="">Transport Type</label><select class="form-control transport_type" name="transport_type[]" id="transport_type"><option value="">Select Transport Type</option><option value="SIC (Seat In Coach)">SIC (Seat In Coach)</option><option value="Private">Private</option></select></div></div><div class="col-md-3"><div class="form-group"><label for="">Hotel Type</label><select class="form-control  hotel" name="hotel[]" id="hotel"><option value="">Select Hotel Type</option><?php $__currentLoopData = $hotel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($row->hotel_category); ?>"><?php echo e($row->hotel_category); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div></div><div class="col-md-3"><div class="form-group"><label for="">Meal Type</label><select class="form-control meal_plan" name="meal_type[]" id="meal_type" ><option value="">Select Meal Type</option><option value="American Meal Plan">American Meal Plan (AMP)</option><option value="Modified American Meal Plan">Modified American Meal Plan (MAMP)</option><option value="Continent Meal Plan">Continent Meal Plan (CMP)</option><option value="European Plan">European Plan (EP)</option></select></div></div><div class="col-md-3"><label>Group Size</label><div class="form-group"><input type="text" name="group_size[]" id="group_size" class="form-control group"></div></div><div class="col-md-3"><label>Other</label><div class="form-group"><input type="text" name="other[]" id="other" class="form-control"></div></div><div class="col-md-3 mr-5"><div class="form-group"><label for="">Price<span class="text-danger">*</span><span class="text-danger" id="price_error"></span></label><div class="d-flex"><div class="input-group-prepend "></div><input type="text" class="form-control price"  id="price" name="price[]" ></div></div></div><div class="col-md-2" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="btn btn-outline-danger remove_button">- Remove</a></div></div></div></div>';
    var x = 1;
    
    $(addButtons).click(function(){
        if(x < maxFields){ 
            x++;
            $(wrappers).append(fieldHTMLs);
            var price = document.querySelector('.price');

              price.addEventListener('input', restrictNumber);
              function restrictNumber (e) {  
                  var pricies = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                  this.value = pricies;
              }
              var group_size = document.querySelector('.group');
              group_size.addEventListener('input', restrictNumber);
              function restrictNumber (e) {  
                  var group = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                  this.value = group;
              }
        }
    });
    $(wrappers).on('click', '.remove_button', function(e){
        e.preventDefault();
         $("#rowes").last().remove();
        
        x--;
    });
  });
</script> -->

    <script>

        $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                // var currency = $('#currency').val();
                //    if (currency == "") {
                //        $("span#currency_error").html('This field is required!');
                //        $("select#currency").focus();
                //        return false;
                //    }else{
                //        $("span#currency_error").hide();
                //    }
                // var title = $('#sairing_validates').val();
                //    if (title == "") {
                //        $("span#sairing_error").html('This field is required!');
                //        $("select#sairing_validates").focus();
                //        return false;
                //    }else{
                //        $("span#sairing_error").hide();
                //    }
                var price = $('#price_validates').val();
                if (price == "") {
                    $("span#price_error_msg").html('This field is required!');
                    $("input#price_validate").focus();
                    return false;
                } else {
                    $("span#price_error_msg").hide();
                }

                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('InclusionForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "<?php echo e(route('pricing_store',request()->route('id'))); ?>",
                    data: $('#InclusionForm').serialize(),
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //window.location = data.url;
                        location.reload();
                        // window.location.href = "<?php echo e(route('pdf_itinerary',request()->route('id'))); ?>";
                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });
        var price = document.querySelector('#price_validates');
        price.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = pricies;
        }

        var group_size = document.querySelector('#group_size_error');
        group_size.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = group;
        }
    </script>

    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade bd-example-modal-sm<?php echo e($value->id); ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content hold">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="mySmallModalLabel">Edit Pricing</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <form role="form" id="PricingForm_<?php echo e($key); ?>" style="background-color: #fdfdfd;" class="p-1">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="price_id" value="<?php echo e($value->id); ?>">
                        <input type="hidden" name="departure_type" value="<?php echo e($value->departure_type); ?>">
                        <div class="modal-body">
                            <div class="row">
                                <?php if(in_array(32, json_decode($columns))): ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Room Sharing<span id="sairing_error" class="text-danger">*</span></label>
                                            <select class="form-control" name="sharing" id="saring_validate_<?php echo e($key); ?>">
                                                <option value="">Select Sairing</option>
                                                <?php $__currentLoopData = $sairing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($row->sairing); ?>" <?php if($row->sairing == $value->sharing): ?> selected <?php endif; ?>><?php echo e($row->sairing); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <span class="text-danger" id="sairing_error_<?php echo e($key); ?>"></span>
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array(33, json_decode($columns))): ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Flight Class<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control" name="flight_class" id="flight_class">
                                                <option value="">Flight Class</option>
                                                <?php $__currentLoopData = $flight_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight_class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($flight_class->name); ?>" <?php if($flight_class->name == $value->flight_class): ?> selected <?php endif; ?>><?php echo e($flight_class->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Age Bracket</label>
                                            <select class="form-control" name="passenger" id="passenger">
                                                <?php $__currentLoopData = $passenger_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($passenger_type->name); ?>" <?php if($passenger_type->name == $value->passenger): ?> selected <?php endif; ?>><?php echo e($passenger_type->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array(35, json_decode($columns))): ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Hotel Name</label>
                                            <select class="form-control  hotel_name" name="hotel_name" id="hotel_name">
                                                <option value="">Hotel Name</option>
                                                <?php $__currentLoopData = $dep_hotel_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dh_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($dh_name->name); ?> <?php if($value->hotel_name == $dh_name->name): ?> selected <?php endif; ?>" selected>
                                                    <?php echo e($dh_name->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Hotel Category</label>
                                            <select class="form-control  hotel" name="hotel" id="hotel">
                                                <option value="">Hotel Category</option>
                                                <option value="<?php echo e($hotel_categories); ?>" selected>
                                                    <?php echo e($hotel_categories); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array(36, json_decode($columns))): ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Transport Type</label>
                                            <select class="form-control transport_type" name="transport_type" id="transport_type">
                                                <option value="">Select Transport</option>
                                                <?php $__currentLoopData = $transport_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transport_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($transport_type->code); ?>" <?php if($transport_type->code == $value->transport_type): ?> selected <?php endif; ?>><?php echo e($transport_type->name); ?> <?php if($transport_type->code != 'Private'): ?>
                                                            (<?php echo e($transport_type->code); ?>)
                                                        <?php endif; ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Airport Transfers</label>
                                        <select class="form-control" name="airport_transfers" id="airport_transfer">
                                            <?php $__currentLoopData = $airport_transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airport_transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($airport_transfer->code); ?>" <?php if($airport_transfer->code == $value->airport_transfers): ?> selected <?php endif; ?>><?php echo e($airport_transfer->name); ?> <?php if($airport_transfer->code != 'Private' && $airport_transfer->code != 'Not Included'): ?>
                                                        (<?php echo e($airport_transfer->code); ?>)
                                                    <?php endif; ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <span id="sairing_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <?php if(in_array(38, json_decode($columns))): ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Meal Plan</label>
                                        <select class="form-control meal_plan" name="meal_type" id="meal_type">
                                            <option value="">Select Meal</option>
                                            <?php $__currentLoopData = $meal_plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal_plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($meal_plan->code); ?>" <?php if($meal_plan->code == $value->meal_type): ?> selected <?php endif; ?>><?php echo e($meal_plan->name); ?> <?php if($meal_plan->code != ''): ?>
                                                        (<?php echo e($meal_plan->code); ?>)
                                                    <?php endif; ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </select>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-4">
                                    <label for="">Minimum Pax</label>
                                    <input type="text" class="form-control" id="group_size_<?php echo e($key); ?>" name="group_size" value="<?php echo e($value->group_size); ?>">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Note</label>
                                        <input type="text" class="form-control" id="other" name="other" value="<?php echo e($value->other); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Price (<?php echo e($value->currency_symbol); ?>)<span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <div class="input-group-prepend ">
                                            </div>
                                            <input type="text" class="form-control" id="price_validate_<?php echo e($key); ?>" name="price" value="<?php echo e($value->price); ?>">
                                        </div>
                                    </div>
                                    <span class="text-danger" id="price_error_msg_<?php echo e($key); ?>"></span>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button class="btn btn-primary active" type="button" id="store_form_<?php echo e($key); ?>" style="margin-bottom:16px;"><i class="fa fa-save"></i> Update</button>
                                    <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_<?php echo e($key); ?>" style="width: 30px; visibility: hidden;margin-bottom:16px;">
                                    <span class="text-success" id="mesegese_<?php echo e($key); ?>" style="margin-left: 10px;margin-bottom:16px;"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $('#store_form_<?php echo e($key); ?>').click(function (e) {
                    e.preventDefault();
                    $('#gif_<?php echo e($key); ?>').show();
                    // var title_<?php echo e($key); ?> = $('#saring_validate_<?php echo e($key); ?>').val();
                    //    if (title_<?php echo e($key); ?> == "") {
                    //        $("span#sairing_error_<?php echo e($key); ?>").html('This field is required!');
                    //        $("select#saring_validate_<?php echo e($key); ?>").focus();
                    //        return false;
                    //    }else{
                    //        $("span#sairing_error_<?php echo e($key); ?>").hide();
                    //    }
                    var price_<?php echo e($key); ?> = $('#price_validate_<?php echo e($key); ?>').val();
                    if (price_<?php echo e($key); ?> == "") {
                        $("span#price_error_msg_<?php echo e($key); ?>").html('This field is required!');
                        $("input#price_validate_<?php echo e($key); ?>").focus();
                        return false;
                    } else {
                        $("span#price_error_msg_<?php echo e($key); ?>").hide();
                    }

                    $('#gif_<?php echo e($key); ?>').css('visibility', 'visible');
                    var formData = new FormData(document.getElementById('PricingForm_<?php echo e($key); ?>'));
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "<?php echo e(route('pricing_updating',request()->route('id'))); ?>",
                        data: $('#PricingForm_<?php echo e($key); ?>').serialize(),
                        success: function (data) {
                            $('#gif_<?php echo e($key); ?>').hide();
                            $('#mesegese_<?php echo e($key); ?>').html("<span class='sussecmsg'>Success!</span>");
                            //window.location = data.url;
                            location.reload();
                            // window.location.href = "<?php echo e(route('pdf_itinerary',request()->route('id'))); ?>";
                        },
                        errors: function () {
                            $('#gif_<?php echo e($key); ?>').hide();
                            $('#mesegese_<?php echo e($key); ?>').html("<span class='sussecmsg'>Something went wrong!</span>");
                        }

                    });
                });
            });
            var price = document.querySelector('#price_validate_<?php echo e($key); ?>');
            price.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = pricies;
            }

            var group_size = document.querySelector('#group_size_<?php echo e($key); ?>');
            group_size.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = group;
            }
        </script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <script type="text/javascript">
        $("#currency_change").on('change', function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "<?php echo e(route('change_pricing_all',request()->route('id'))); ?>",
                data: $('#changePricingAll').serialize(),
                success: function (data) {
                    window.location.reload();
                },
                errors: function () {
                    $('#mesegese_all').html("<span class='sussecmsg'>Something went wrong!</span>");
                }

            });
        });
    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/departurecloud/resources/views/pricing/create.blade.php ENDPATH**/ ?>