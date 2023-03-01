 <?php
    $new_time = ($departure['hold_duration']) + 5;
    $hold_till = DB::table('hold_departures')->where('departure_id', $departure['id'])->get();
    if (count($hold_till) > 0) {
        foreach ($hold_till as $row) {
            if ($row->departure_id == $departure['id']) {
                $hold = $row->hold_till;
            }
        }
    } else {
        $hold = 0;
    }
    $today = date("Y-m-d");
    $date1 = date_create($today);
    $date2 = date_create($departure['start_date']);
    $diff = date_diff($date1, $date2);
    $date = $diff->format("%R%a");

    if (($hold < $date) && ($departure['available_seat'] > 0)) {
        $popup = '.bd-example-modal-sm';
    } else {
        $popup = 0;
    }
?>
 
        <form role="form" id="BookDepartureForm_<?php echo e($departure['id']); ?>">
            <?php echo csrf_field(); ?>
            <div class="bookingMdodal">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="id" value="<?php echo e($departure['id']); ?>">
                            <div class="d-flex align-items-center totalava_unit">
                                <h5>Available Units :</h5>
                                <span class="ml-2 text-black text-bold"><?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?></span>
                            </div>
                            <div class="form-group d-none">
                                <label for="exampleFormControlInput1">Available Units</label>
                                <input type="text" class="form-control" id="avl_<?php echo e($departure['id']); ?>" name="available" value="<?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?>" readonly>
                            </div>
                        </div>
 
                     </div>
                    <div class="row">
                        <?php $__currentLoopData = $departure['departure_price']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <input type="hidden" name="sairing[]" value="<?php echo e($require['sharing']); ?>">
                            <div class="row d-none">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="" value="<?php echo e(ucfirst($require['sharing'])); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="flight_class[]" value="<?php echo e($require['flight_class']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="passenger[]" value="<?php echo e($require['passenger']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="hotel_name[]" value="<?php echo e($require['hotel_name']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="hotel_type[]" value="<?php echo e($require['hotel_type']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="transport_type[]" value="<?php echo e($require['transport_type']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="airport_transfers[]" value="<?php echo e($require['airport_transfers']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="meal_plan[]" value="<?php echo e($require['meal_type']); ?>" readonly>
                                        </div>
                                    </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="grp_<?php echo e($require['id']); ?>" name="group_size[]" value="<?php echo e($require['group_size']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2 pr-5">
                                    <div class="form-group">

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
                                                <span>Hotel Name</span>
                                                <strong><?php echo e($require['hotel_name']); ?></strong>
                                            </div>
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <span class="text-danger" id="error<?php echo e($departure['id']); ?>"></span>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-10">
                            <label for="exampleFormControlInput1">Lead Passenger/Agent Name<span id="error_book_msg_<?php echo e($departure['id']); ?>" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                            <input type="text" class="form-control" id="require_<?php echo e($departure['id']); ?>" name="lead_pasanger_name" placeholder="">

                        </div>
                        <div class="col-md-2">
             <span id="total_pricebook<?php echo e($departure['id']); ?>">
             </span>
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
            <img src="<?php echo e(asset('images/loader.gif')); ?>" id="gif_book_<?php echo e($departure['id']); ?>" style="width: 3%;  visibility: hidden;">
            <span class="text-success" id="mesegese_book_<?php echo e($departure['id']); ?>" style="margin-left: 10px"></span>
            <button class="btn btn-primary active mr-2" type="button" id="store_form_book_<?php echo e($departure['id']); ?>"><i class="fa fa-save"></i> Book Units</button>
            <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
        </div>
    </div>
</div>
</form> 

<?php 
  if (auth::user()->id == $departure['user_id'] || (auth::user()->main_user_type == 2)) {
            if (ucfirst(auth::user()->country) == 'India') {
                $value = intval($departure['price_inr']);
                if (isset($departure['single_supplyment_price_inr'])) {
                    $single_value = intval($departure['single_supplyment_price_inr']);
                } else {
                    $single_value = 0;
                }
            } else {
                $value = intval($departure['price_usd']);
                if (isset($departure['single_supplyment_price_usd'])) {
                    $single_value = intval($departure['single_supplyment_price_usd']);
                } else {
                    $single_value = 0;
                }
            }
        } else {
            if (isset($departure['OtherPrice']) && count($departure['OtherPrice']) > 0) {
                foreach ($departure['OtherPrice'] as $price) {
                    if (ucfirst(auth::user()->country) == 'India') {
                        $value = intval($price['price_inr']);
                    } else {
                        $value = intval($price['price_usd']);
                    }
                }
            } else {
                if (ucfirst(auth::user()->country) == 'India') {
                    $value = $departure['price_inr'];
                } else {
                    $value = $departure['price_usd'];
                }
            }
            if (isset($departure['singlePrice']) &&  count($departure['singlePrice']) > 0) {
                foreach ($departure['singlePrice'] as $sprice) {
                    if (ucfirst(auth::user()->country) == 'India') {
                        $single_value = $sprice['price_inr'];
                    } else {
                        $single_value = $sprice['price_usd'];
                    }
                }
            } else {
                if (ucfirst(auth::user()->country) == 'India') {
                    $single_value = $departure['single_supplyment_price_inr'];
                } else {
                    $single_value = $departure['single_supplyment_price_usd'];
                }
            }

        }


 ?>

 <script>
            $('#book<?php echo e($departure["id"]); ?>').keyup(function () {
                var total = '<?php echo e(($departure["total_seat"])-($departure["hold_sum"] + $departure["book_sum"])); ?>';
                if ($(this).val() > <?php echo e(($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])); ?>) {
                    $("#error<?php echo e($departure['id']); ?>").text('Value can not be greater than - ' + total + "");
                    $(this).val('');
                } else {
                    $("#error<?php echo e($departure['id']); ?>").text('');
                }
            });
            $("#book<?php echo e($departure['id']); ?>").keyup(function () {
                var required = $("#book<?php echo e($departure['id']); ?>").val();
                var price = '<?php echo e($value); ?>'
                var sum = parseInt(required) * parseInt(price);

                var required1 = $("#single_bookbook<?php echo e($departure['id']); ?>").val();
                var price1 = '<?php echo e($single_value); ?>'
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#msg").html('Total')
                $("#required_pricebook<?php echo e($departure['id']); ?>").html(sum);

                $("#total_pricebook<?php echo e($departure['id']); ?>").html(sum);
            })

            <?php $departureCount = count($departure['departure_price']); $j = 0; ?>
            var total = 0;
            <?php $__currentLoopData = $departure['departure_price']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $("#require_<?php echo e($require['id']); ?>").keyup(function () {console.log('d');
                var group_size = <?php echo e($require['group_size']); ?>;
                var check = parseInt($("#require_<?php echo e($require['id']); ?>").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_<?php echo e($require['id']); ?>").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_<?php echo e($require['id']); ?>").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_<?php echo e($require['id']); ?>").val(sum_no);
                    }
                }
            });
            <?php  $j++; ?>
            $("#require_<?php echo e($require['id']); ?>").keyup(function () {
                console.log('dc');
                var required = $("#require_<?php echo e($require['id']); ?>").val();
                //var total = 0;
                required = required ? required : 0;
                if (required != '') {
                    var total_required = required + required;
                    var price = $("#price_<?php echo e($require['id']); ?>").val();
                    var sum = parseInt(required) * parseInt(price);
                    var currency = $("#currency_<?php echo e($require['id']); ?>").val();
                    $("#require_price_<?php echo e($require['id']); ?>").html(currency + +sum);
                    total = sum + total;
                } else {
                    $("#require_price_<?php echo e($require['id']); ?>").html('');

                    var price = $("#price_<?php echo e($require['id']); ?>").val();
                    var currency = $("#currency_<?php echo e($require['id']); ?>").val();
                    price = parseInt(price);
                    total = total - price;
                    //alert(currency);
                    //$("#total_pricebook<?php echo e($departure["id"]); ?>").html("Total Price " + currency + +total);
                }
                //$("#total_pricebook<?php echo e($departure["id"]); ?>").html("Total Price " + currency + +total);
                //alert(total);
            })

            $("#require_<?php echo e($require['id']); ?>").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $departure['departure_price']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $require): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            $("#require_hold_<?php echo e($require['id']); ?>").keyup(function () {
                var group_size = <?php echo e($require['group_size']); ?>;
                var check = parseInt($("#require_hold_<?php echo e($require['id']); ?>").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_hold_<?php echo e($require['id']); ?>").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_hold_<?php echo e($require['id']); ?>").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_hold_<?php echo e($require['id']); ?>").val(sum_no);
                    }
                }
            });
            $("#require_hold_<?php echo e($require['id']); ?>").keyup(function () {
                var required = $("#require_hold_<?php echo e($require['id']); ?>").val();
                if (required != '') {
                    var total_required = required + required;
                    var price = $("#price_<?php echo e($require['id']); ?>").val();
                    var sum = parseInt(required) * parseInt(price);
                    var currency = $("#currency_<?php echo e($require['id']); ?>").val();
                    $("#require_hold_price_<?php echo e($require['id']); ?>").html(currency + +sum);
                } else {
                    $("#require_hold_price_<?php echo e($require['id']); ?>").html('');
                }
                //$("#total_pricebook<?php echo e($departure["id"]); ?>").html(total);
            })
            $("#require_hold_<?php echo e($require['id']); ?>").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
       
        <script>
            $('#isAgeSelected<?php echo e($departure["id"]); ?>').click(function () {
                $("#txtAge<?php echo e($departure['id']); ?>").toggle(this.checked);
            });
        </script>

  <script>
            $('#check<?php echo e($departure["id"]); ?>').keyup(function () {
                var value = $(this).val();
                var total = '<?php echo e(($departure["total_seat"])-($departure["hold_sum"] + $departure["book_sum"])); ?>';
                var subtotal = value - total;
                if ($(this).val() > <?php echo e(($departure["total_seat"])-($departure["hold_sum"] + $departure["book_sum"])); ?>) {
                    //alert('Your are Request ' +subtotal + ' Extra Departure');
                    //$(this).val('')
                    $("#message<?php echo e($departure['id']); ?>").text('Request More ' + subtotal + " Extra Departure");
                } else {
                    $("#message<?php echo e($departure['id']); ?>").text("");
                }
            });
            $("#check<?php echo e($departure['id']); ?>").keyup(function () {
                var required = $("#check<?php echo e($departure['id']); ?>").val();
                var price = '<?php echo e($value); ?>'
                var sum = parseInt(required) * parseInt(price);
                $("#total_hold_price<?php echo e($departure['id']); ?>").html(sum);
            })
        </script>
<script>
$(document).ready(function () {
    $('#store_form_book_<?php echo e($departure["id"]); ?>').click(function (e) {
        e.preventDefault(); 
        $('#gif_book_<?php echo e($departure["id"]); ?>').show();
        $('#gif_book_<?php echo e($departure["id"]); ?>').css('visibility', 'visible');
        //$('#store_form').prop('disabled', true);
        var formDatas = new FormData(document.getElementById('BookDepartureForm_<?php echo e($departure["id"]); ?>'));
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
                $('#gif_book_<?php echo e($departure["id"]); ?>').hide();
                if (data.error) {
                    $("#error_book_msg_<?php echo e($departure["id"]); ?>").html(data.error);
                    $('#gif_book_<?php echo e($departure["id"]); ?>').hide();
                } else if (data.required) {
                    $("#error_book_msg_<?php echo e($departure["id"]); ?>").html(data.required);
                    $('#gif_book_<?php echo e($departure["id"]); ?>').hide();
                } else {
                    $('#mesegese_book_<?php echo e($departure["id"]); ?>').html("<span class='sussecmsg'>Success!</span>");
                    //window.location = data.url;
                    location.reload();
                }

            },
            errors: function () {
                $('#gif_book_<?php echo e($departure["id"]); ?>').hide();
                $('#mesegese_book_<?php echo e($departure["id"]); ?>').html("<span class='sussecmsg'>Something went wrong!</span>");
            }

        });
    });
});
</script>

 <?php /**PATH /var/www/departure/resources/views/ajax_paginate/booking_modal.blade.php ENDPATH**/ ?>