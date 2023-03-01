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
 
        <form role="form" id="BookDepartureForm_{{$departure['id']}}">
            @csrf
            <div class="bookingMdodal">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="id" value="{{$departure['id']}}">
                            <div class="d-flex align-items-center totalava_unit">
                                <h5>Available Units :</h5>
                                <span class="ml-2 text-black text-bold">{{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}</span>
                            </div>
                            <div class="form-group d-none">
                                <label for="exampleFormControlInput1">Available Units</label>
                                <input type="text" class="form-control" id="avl_{{$departure['id']}}" name="available" value="{{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}" readonly>
                            </div>
                        </div>
 
                     </div>
                    <div class="row">
                        @foreach($departure['departure_price'] as $require)
                            <input type="hidden" name="sairing[]" value="{{$require['sharing']}}">
                            <div class="row d-none">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="" value="{{ucfirst($require['sharing'])}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="flight_class[]" value="{{$require['flight_class']}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="passenger[]" value="{{$require['passenger']}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="hotel_type[]" value="{{$require['hotel_type']}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="transport_type[]" value="{{$require['transport_type']}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="airport_transfers[]" value="{{$require['airport_transfers']}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="" name="meal_plan[]" value="{{$require['meal_type']}}" readonly>
                                        </div>
                                    </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="grp_{{$require['id']}}" name="group_size[]" value="{{$require['group_size']}}" readonly>
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
                                            @if(in_array(32, json_decode($columns)))
                                            <div class="bh_units">
                                                <span>Room Sharing</span>
                                                <strong>{{ucfirst($require['sharing'])}}</strong>
                                            </div>
                                            @endif
                                            @if(in_array(33, json_decode($columns)))
                                            <div class="bh_units">
                                                <span>Flight Class</span>
                                                <strong>{{ucfirst($require['flight_class'])}}</strong>
                                            </div>
                                            <div class="bh_units">
                                                <span>Passenger Type</span>
                                                <strong>{{$require['passenger']}}</strong>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            @if(in_array(35, json_decode($columns))) 
                                            <div class="bh_units">
                                                <span>Hotel Type</span>
                                                <strong>{{$require['hotel_type']}}</strong>
                                            </div>
                                            @endif
                                            @if(in_array(36, json_decode($columns)))
                                            <div class="bh_units">
                                                <span>Transport Type</span>
                                                <strong>{{$require['transport_type']}}</strong>
                                            </div>
                                            @endif
                                            <div class="bh_units">
                                                <span>Airport Transfers</span>
                                                <strong>{{$require['airport_transfers']}}</strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            @if(in_array(38, json_decode($columns)))
                                            <div class="bh_units">
                                                <span>Meal Plan</span>
                                                <strong>{{$require['meal_type']}}</strong>
                                            </div>
                                            @endif
                                            <div class="bh_units">
                                                <span>Minimum Pax</span>
                                                <strong>{{$require['group_size']}}</strong>
                                            </div>
                                            <div class="bh_units">
                                                <input type="text" class="form-control" id="require_{{$require['id']}}" name="book[]" placeholder="Enter required units">
                                            </div>
                                        </div>
                                        <hr class="mt-1 mb-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <strong>Price</strong>
                                            <div class="form-group mb-0">
                                                <input type="hidden" class="form-control" id="price_{{$require['id']}}" name="price[]" value="{{$require['price']}}" style="border:none">
                                                <strong id="require_price_{{$require['id']}}"></strong>
                                                <input type="hidden" id="currency_c_{{$require['id']}}" value="{{$require['currency_code']}} " name="currency">
                                                <input type="hidden" id="currency_{{$require['id']}}" value="{{$require['currency_symbol']}} " name="currency_symbol">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                <span class="text-danger" id="error{{$departure['id']}}"></span>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-10">
                            <label for="exampleFormControlInput1">Lead Passenger Name<span id="error_book_msg_{{$departure['id']}}" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                            <input type="text" class="form-control" id="require_{{$departure['id']}}" name="lead_pasanger_name" placeholder="">

                        </div>
                        <div class="col-md-2">
             <span id="total_pricebook{{$departure['id']}}">
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
            <img src="{{ asset('images/loader.gif') }}" id="gif_book_{{$departure['id']}}" style="width: 3%;  visibility: hidden;">
            <span class="text-success" id="mesegese_book_{{$departure['id']}}" style="margin-left: 10px"></span>
            <button class="btn btn-primary active mr-2" type="button" id="store_form_book_{{$departure['id']}}"><i class="fa fa-save"></i> Book Units</button>
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
            $('#book{{$departure["id"]}}').keyup(function () {
                var total = '{{($departure["total_seat"])-($departure["hold_sum"] + $departure["book_sum"])}}';
                if ($(this).val() > {{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}) {
                    $("#error{{$departure['id']}}").text('Value can not be greater than - ' + total + "");
                    $(this).val('');
                } else {
                    $("#error{{$departure['id']}}").text('');
                }
            });
            $("#book{{$departure['id']}}").keyup(function () {
                var required = $("#book{{$departure['id']}}").val();
                var price = '{{$value}}'
                var sum = parseInt(required) * parseInt(price);

                var required1 = $("#single_bookbook{{$departure['id']}}").val();
                var price1 = '{{$single_value}}'
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#msg").html('Total')
                $("#required_pricebook{{$departure['id']}}").html(sum);

                $("#total_pricebook{{$departure['id']}}").html(sum);
            })

            <?php $departureCount = count($departure['departure_price']); $j = 0; ?>
            var total = 0;
            @foreach($departure['departure_price'] as $require)
            $("#require_{{$require['id']}}").keyup(function () {console.log('d');
                var group_size = {{$require['group_size']}};
                var check = parseInt($("#require_{{$require['id']}}").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_{{$require['id']}}").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_{{$require['id']}}").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_{{$require['id']}}").val(sum_no);
                    }
                }
            });
            <?php  $j++; ?>
            $("#require_{{$require['id']}}").keyup(function () {
                console.log('dc');
                var required = $("#require_{{$require['id']}}").val();
                //var total = 0;
                required = required ? required : 0;
                if (required != '') {
                    var total_required = required + required;
                    var price = $("#price_{{$require['id']}}").val();
                    var sum = parseInt(required) * parseInt(price);
                    var currency = $("#currency_{{$require['id']}}").val();
                    $("#require_price_{{$require['id']}}").html(currency + +sum);
                    total = sum + total;
                } else {
                    $("#require_price_{{$require['id']}}").html('');

                    var price = $("#price_{{$require['id']}}").val();
                    var currency = $("#currency_{{$require['id']}}").val();
                    price = parseInt(price);
                    total = total - price;
                    //alert(currency);
                    //$("#total_pricebook{{$departure["id"]}}").html("Total Price " + currency + +total);
                }
                //$("#total_pricebook{{$departure["id"]}}").html("Total Price " + currency + +total);
                //alert(total);
            })

            $("#require_{{$require['id']}}").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            @endforeach
            @foreach($departure['departure_price'] as $require)
            $("#require_hold_{{$require['id']}}").keyup(function () {
                var group_size = {{$require['group_size']}};
                var check = parseInt($("#require_hold_{{$require['id']}}").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_hold_{{$require['id']}}").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_hold_{{$require['id']}}").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_hold_{{$require['id']}}").val(sum_no);
                    }
                }
            });
            $("#require_hold_{{$require['id']}}").keyup(function () {
                var required = $("#require_hold_{{$require['id']}}").val();
                if (required != '') {
                    var total_required = required + required;
                    var price = $("#price_{{$require['id']}}").val();
                    var sum = parseInt(required) * parseInt(price);
                    var currency = $("#currency_{{$require['id']}}").val();
                    $("#require_hold_price_{{$require['id']}}").html(currency + +sum);
                } else {
                    $("#require_hold_price_{{$require['id']}}").html('');
                }
                //$("#total_pricebook{{$departure["id"]}}").html(total);
            })
            $("#require_hold_{{$require['id']}}").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            @endforeach
        </script>
       
        <script>
            $('#isAgeSelected{{$departure["id"]}}').click(function () {
                $("#txtAge{{$departure['id']}}").toggle(this.checked);
            });
        </script>

  <script>
            $('#check{{$departure["id"]}}').keyup(function () {
                var value = $(this).val();
                var total = '{{($departure["total_seat"])-($departure["hold_sum"] + $departure["book_sum"])}}';
                var subtotal = value - total;
                if ($(this).val() > {{($departure["total_seat"])-($departure["hold_sum"] + $departure["book_sum"])}}) {
                    //alert('Your are Request ' +subtotal + ' Extra Departure');
                    //$(this).val('')
                    $("#message{{$departure['id']}}").text('Request More ' + subtotal + " Extra Departure");
                } else {
                    $("#message{{$departure['id']}}").text("");
                }
            });
            $("#check{{$departure['id']}}").keyup(function () {
                var required = $("#check{{$departure['id']}}").val();
                var price = '{{$value}}'
                var sum = parseInt(required) * parseInt(price);
                $("#total_hold_price{{$departure['id']}}").html(sum);
            })
        </script>
<script>
$(document).ready(function () {
    $('#store_form_book_{{$departure["id"]}}').click(function (e) {
        e.preventDefault(); 
        $('#gif_book_{{$departure["id"]}}').show();
        $('#gif_book_{{$departure["id"]}}').css('visibility', 'visible');
        //$('#store_form').prop('disabled', true);
        var formDatas = new FormData(document.getElementById('BookDepartureForm_{{$departure["id"]}}'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "{{route('departure_book')}}",
            data: formDatas,
            contentType: false,
            processData: false,

            success: function (data) {
                $('#gif_book_{{$departure["id"]}}').hide();
                if (data.error) {
                    $("#error_book_msg_{{$departure["id"]}}").html(data.error);
                    $('#gif_book_{{$departure["id"]}}').hide();
                } else if (data.required) {
                    $("#error_book_msg_{{$departure["id"]}}").html(data.required);
                    $('#gif_book_{{$departure["id"]}}').hide();
                } else {
                    $('#mesegese_book_{{$departure["id"]}}').html("<span class='sussecmsg'>Success!</span>");
                    //window.location = data.url;
                    location.reload();
                }

            },
            errors: function () {
                $('#gif_book_{{$departure["id"]}}').hide();
                $('#mesegese_book_{{$departure["id"]}}').html("<span class='sussecmsg'>Something went wrong!</span>");
            }

        });
    });
});
</script>

 