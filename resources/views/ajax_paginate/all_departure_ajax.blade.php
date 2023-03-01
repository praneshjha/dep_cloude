<div class="row paginate_remove" > 
    <div class="col-md-12">
        <div class="row mt-3 gridviewRow" id="all_dep_appends_ajx">
            @if(count($departures)> 0 )
                @foreach( $departures as $key => $departure )
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
                            @if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0)
                                <div class="ribbon-style">
                                    <div class="ribbon ribbon-success float-right">OPEN</div>
                                </div>
                            @else
                                <div class="ribbon-style">
                                    <div class="ribbon ribbon-danger float-right">SOLDOUT</div>
                                </div>
                            @endif
                            <div class="mb-21 ">
                                <h4 class="card-title">
                                    <a href="{{route('all_departure_details',$departure->id)}}" >{{$departure->title}}</a>
                                </h4>
                                <p class="mb-0">
                                    <a href="{{url('profile/'.$departure->company_url)}}" class="userprofileName">{{$departure->departure_ownner}}</a>
                                    <span class="departureID ml-1">({{ $departure->dep_id }})</span>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="day-info-">
                                        <strong class="d-block text-blue">{{date('d M, Y', strtotime($departure->start_date))}}</strong>
                                        @if($departure->no_of_nights == null || $departure->no_of_days == null)
                                        @else
                                            <span class="text-dark font-weight-bold">{{$departure->no_of_nights}} <span class="text-muted">Nights</span> / {{$departure->no_of_days}} <span class="text-muted">Days</span></span>
                                        @endif
                                    </div>
                                    <div class="dep-model-action-btn position-relative">
                                        
                                        <a href="javascript:void(0)" id="dep-{{$departure->id}}" title="chat" class="chat_data tooltipbubble"><i class="far fa-comment-dots"></i></a>
                                        <input type="hidden" name="dep_val_{{$departure->id}}" id="dep-{{$departure->id}}-val" value="{{$departure->id}}">
                                        <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature" class="tooltipbubble"><i class="fa fa-eye"></i></a>
                                        @if(($hold < $date))
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#hold_modal" title="Hold Units" class="tooltipbubble" onclick="openHoldModal({{ $departure}})"><i class="fas fa-pause"></i></a>      
                                        @else
                                         <a href="javascript:void(0);" title="This is Departure Beyond Hold Date" class="tooltipbubble" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                        @endif
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0) #booking_modal @endif"
                                           title="Book Units" class="tooltipbubble" onclick="openBookingModal({{ $departure}})"><i class="far fa-calendar-check"></i></a>
                                        <div class="shareiconList">
                                            <a href="javascript:void(0);" class="ShareIcons">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M448 127.1C448 181 405 223.1 352 223.1C326.1 223.1 302.6 213.8 285.4 197.1L191.3 244.1C191.8 248 191.1 251.1 191.1 256C191.1 260 191.8 263.1 191.3 267.9L285.4 314.9C302.6 298.2 326.1 288 352 288C405 288 448 330.1 448 384C448 437 405 480 352 480C298.1 480 256 437 256 384C256 379.1 256.2 376 256.7 372.1L162.6 325.1C145.4 341.8 121.9 352 96 352C42.98 352 0 309 0 256C0 202.1 42.98 160 96 160C121.9 160 145.4 170.2 162.6 186.9L256.7 139.9C256.2 135.1 256 132 256 128C256 74.98 298.1 32 352 32C405 32 448 74.98 448 128L448 127.1zM95.1 287.1C113.7 287.1 127.1 273.7 127.1 255.1C127.1 238.3 113.7 223.1 95.1 223.1C78.33 223.1 63.1 238.3 63.1 255.1C63.1 273.7 78.33 287.1 95.1 287.1zM352 95.1C334.3 95.1 320 110.3 320 127.1C320 145.7 334.3 159.1 352 159.1C369.7 159.1 384 145.7 384 127.1C384 110.3 369.7 95.1 352 95.1zM352 416C369.7 416 384 401.7 384 384C384 366.3 369.7 352 352 352C334.3 352 320 366.3 320 384C320 401.7 334.3 416 352 416z"/>
                                                </svg>
                                            </a>
                                            <ul class="submenu shareableIcons">
                                                <li><a href="mailto:?subject={{$departure->title}}&body={{route('all_departure_details',$departure->id)}}" title="Email Share" class="mail share_icon"><i class="far fa-envelope"></i></a></li>
                                                <li><a href="https://www.facebook.com/sharer.php?s=100&p[title]={{$departure->title}}&p[url]={{route('all_departure_details',$departure->id)}}&p[summary]={!! $departure->description !!}&p[images][0]={{$departure->logo_image}}" target="_blank" title="FB Share" class="facebook" class="facebook share_icon"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="http://twitter.com/intent/tweet?original_referer={{route('all_departure_details',$departure->id)}}&text={{$departure->title}}&url={{route('all_departure_details',$departure->id)}}"
                                                       target="_blank" title="Twitter Share" class="twitter share_icon"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="https://wa.me/?text={{route('all_departure_details',$departure->id)}}" target="_blank" title="Whatsapp Share" class="whatsapp share_icon"><i class="fab fa-whatsapp"></i></a></li>
                                                <li><a href="http://pinterest.com/pin/create/bookmarklet/?media={{$departure->logo_image}}&url={{route('all_departure_details',$departure->id)}}&is_video=false&description={{$departure->title}}"
                                                       target="_blank" title="Pinterest Share" class="pinterest share_icon"><i class="fab fa-pinterest-p"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if($departure->from != null || $departure->ending_at != null)
                                    <div class="d-flex position-relative">
                                
                                    @if($departure->from != "")
                                        <div>
                                            <form action="{{route('all_departure')}}" method="get" class="dept-from-text">
                                                <input type="hidden" name="departure_from" value="{{$departure->from}}">
                                                <button type="submit" class="btn btn-sm" style="background-color: #ffffff;border:node;font-size: 14px;margin-bottom: 0; color:  #3396d7;font-weight: 600;padding:0;">{{strtok($departure->from, ",")}}</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if($departure->from != null && $departure->ending_at != null)
                                        <div class="position-relative px-2">
                                            <strong style="color:#9f206a;">-</strong>
                                        </div>
                                    @endif
                                    @if($departure->ending_at != null)
                                        <div>
                                            <form action="{{route('all_departure')}}" method="get" class="dept-from-text">
                                                <input type="hidden" name="departure_to" value="{{$departure->ending_at}}">
                                                <button type="submit" class="btn btn-sm" style="background-color: #ffffff; border:node;font-size: 14px;margin-bottom: 0; color:  #3396d7;font-weight: 600;padding:0;">{{$departure->ending_at}}</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if($departure->return_to != "")
                                        <div class="position-relative px-2">
                                            <strong style="color:#9f206a;">-</strong>
                                        </div>
                                    @endif
                                    @if($departure->return_to != "")
                                        <div>
                                            <form action="{{route('all_departure')}}" method="get" class="dept-from-text">
                                                <input type="hidden" name="departure_from" value="{{$departure->return_to}}">
                                                <button type="submit" class="btn btn-sm" style="background-color: #ffffff;border:node;font-size: 14px;margin-bottom: 0; color:  #3396d7;font-weight: 600;padding:0;">{{strtok($departure->return_to, ",")}}</button>
                                            </form>
                                        </div>
                                    @endif
                                    </div>
                                @endif
                                <div class="d-flex position-relative inclusion_icons_show">
                                    @foreach($departure->inclusion_icons as $inc_icons)
                                        <img src="{{$inc_icons->icon}}" alt="" class="inclu_icon" width="12" title="{{$inc_icons->name}}">
                                    @endforeach
                                </div>
                            </div>
                            <div class="bg-dept bg-per-pax">
                                <div class="d-flex justify-content-between">
                                    <ul class="unit-set">
                                        <li>
                                            @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum) > 0)
                                                {{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}
                                            @else
                                                0
                                            @endif
                                            <span>Avl Units</span>
                                        </li>
                                    </ul>
                                    <p class="price-set">
                                        @foreach($departure->price as $price)
                                            {{$price->currency_symbol}}  {{$price->price}}
                                        @endforeach
                                        <span>Per PAX</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!----End Modal-->

                @endforeach

            @else
                <div class="col-md-12" style="text-align:center;">Departure not Found</div>
            @endif
             
        </div>
    </div>
</div>

    @section('footerSection')
    @foreach( $departures as $row )

        <?php
        if (auth::user()->id == $row->user_id || (auth::user()->main_user_type == 2)) {
            if (ucfirst(auth::user()->country) == 'India') {
                $value = intval($row->price_inr);
                if (isset($row->single_supplyment_price_inr)) {
                    $single_value = intval($row->single_supplyment_price_inr);
                } else {
                    $single_value = 0;
                }
            } else {
                $value = intval($row->price_usd);
                if (isset($row->single_supplyment_price_usd)) {
                    $single_value = intval($row->single_supplyment_price_usd);
                } else {
                    $single_value = 0;
                }
            }
        } else {
            if (count($row->OtherPrice) > 0) {
                foreach ($row->OtherPrice as $price) {
                    if (ucfirst(auth::user()->country) == 'India') {
                        $value = intval($price->price_inr);
                    } else {
                        $value = intval($price->price_usd);
                    }
                }
            } else {
                if (ucfirst(auth::user()->country) == 'India') {
                    $value = $row->price_inr;
                } else {
                    $value = $row->price_usd;
                }
            }
            if (count($row->singlePrice) > 0) {
                foreach ($row->singlePrice as $sprice) {
                    if (ucfirst(auth::user()->country) == 'India') {
                        $single_value = $sprice->price_inr;
                    } else {
                        $single_value = $sprice->price_usd;
                    }
                }
            } else {
                if (ucfirst(auth::user()->country) == 'India') {
                    $single_value = $row->single_supplyment_price_inr;
                } else {
                    $single_value = $row->single_supplyment_price_usd;
                }
            }

        }
        ?>


        <script>
            $('#check{{$row->id}}').keyup(function () {
                var value = $(this).val();
                var total = '{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}';
                var subtotal = value - total;
                if ($(this).val() > {{($row->total_seat)-($row->hold_sum + $row->book_sum)}}) {
                    //alert('Your are Request ' +subtotal + ' Extra Departure');
                    //$(this).val('')
                    $("#message{{$row->id}}").text('Request More ' + subtotal + " Extra Departure");
                } else {
                    $("#message{{$row->id}}").text("");
                }
            });
            $("#check{{$row->id}}").keyup(function () {
                var required = $("#check{{$row->id}}").val();
                var price = '{{$value}}'
                var sum = parseInt(required) * parseInt(price);
                $("#total_hold_price{{$row->id}}").html(sum);
            })
        </script>
        <script>
            $('#book{{$row->id}}').keyup(function () {
                var total = '{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}';
                if ($(this).val() > {{($row->total_seat)-($row->hold_sum + $row->book_sum)}}) {
                    $("#error{{$row->id}}").text('Value can not be greater than - ' + total + "");
                    $(this).val('');
                } else {
                    $("#error{{$row->id}}").text('');
                }
            });
            $("#book{{$row->id}}").keyup(function () {
                var required = $("#book{{$row->id}}").val();
                var price = '{{$value}}'
                var sum = parseInt(required) * parseInt(price);

                var required1 = $("#single_bookbook{{$row->id}}").val();
                var price1 = '{{$single_value}}'
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#msg").html('Total')
                $("#required_pricebook{{$row->id}}").html(sum);

                $("#total_pricebook{{$row->id}}").html(sum);
            })

            <?php $departureCount = count($departure->departure_price); $j = 0; ?>
            var total = 0;
            @foreach($row->departure_price as $require)
            $("#require_{{$require->id}}").keyup(function () {
                var group_size = {{$require->group_size}};
                var check = parseInt($("#require_{{$require->id}}").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_{{$require->id}}").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_{{$require->id}}").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_{{$require->id}}").val(sum_no);
                    }
                }
            });
            <?php  $j++; ?>
            $("#require_{{$require->id}}").keyup(function () {
                var required = $("#require_{{$require->id}}").val();
                //var total = 0;
                required = required ? required : 0;
                if (required != '') {
                    var total_required = required + required;
                    var price = $("#price_{{$require->id}}").val();
                    var sum = parseInt(required) * parseInt(price);
                    var currency = $("#currency_{{$require->id}}").val();
                    $("#require_price_{{$require->id}}").html(currency + +sum);
                    total = sum + total;
                } else {
                    $("#require_price_{{$require->id}}").html('');

                    var price = $("#price_{{$require->id}}").val();
                    var currency = $("#currency_{{$require->id}}").val();
                    price = parseInt(price);
                    total = total - price;
                    //alert(currency);
                    //$("#total_pricebook{{$row->id}}").html("Total Price " + currency + +total);
                }
                //$("#total_pricebook{{$row->id}}").html("Total Price " + currency + +total);
                //alert(total);
            })

            $("#require_{{$require->id}}").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            @endforeach
            @foreach($row->departure_price as $require)
            $("#require_hold_{{$require->id}}").keyup(function () {
                var group_size = {{$require->group_size}};
                var check = parseInt($("#require_hold_{{$require->id}}").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (group_size == 2) {
                    if ((check % 2) != 0) {
                        $("#require_hold_{{$require->id}}").val(sum_no);
                    }
                }
                if (group_size == 3) {
                    if ((check % 3) == 1) {
                        $("#require_hold_{{$require->id}}").val(sum_no1);
                    } else if ((check % 3) == 2) {
                        $("#require_hold_{{$require->id}}").val(sum_no);
                    }
                }
            });
            $("#require_hold_{{$require->id}}").keyup(function () {
                var required = $("#require_hold_{{$require->id}}").val();
                if (required != '') {
                    var total_required = required + required;
                    var price = $("#price_{{$require->id}}").val();
                    var sum = parseInt(required) * parseInt(price);
                    var currency = $("#currency_{{$require->id}}").val();
                    $("#require_hold_price_{{$require->id}}").html(currency + +sum);
                } else {
                    $("#require_hold_price_{{$require->id}}").html('');
                }
                //$("#total_pricebook{{$row->id}}").html(total);
            })
            $("#require_hold_{{$require->id}}").on("keypress keyup blur", function (event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            @endforeach
        </script>
       
        <script>
            $('#isAgeSelected{{$row->id}}').click(function () {
                $("#txtAge{{$row->id}}").toggle(this.checked);
            });
        </script>
         
        <script>
            $(document).ready(function () {
                $('#store_form_hold_{{$row->id}}').click(function (e) {
                    e.preventDefault();
                    //alert('hello_{{$row->id}}');
                    $('#gif_{{$row->id}}').show();
                    $('#gif_{{$row->id}}').css('visibility', 'visible');
                    //$('#store_form').prop('disabled', true);
                    var formDatas = new FormData(document.getElementById('HoldDepartureForm_{{$row->id}}'));
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "{{route('departure_holdduration')}}",
                        data: formDatas,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#gif').hide();
                            if (data.required) {
                                //alert(data.required)
                                $("#mesegese1_{{$row->id}}").html(data.error);
                                $("#mesegese1_{{$row->id}}").html(data.required);
                                $('#gif_{{$row->id}}').hide();
                                //window.location = data.url;
                            } else {
                                $('#mesegese_{{$row->id}}').html("<span class='sussecmsg'>Success!</span>");
                                //window.location = data.url;
                                location.reload();
                            }

                        },
                        errors: function () {
                            $('#gif_{{$row->id}}').hide();
                            $('#mesegese_{{$row->id}}').html("<span class='sussecmsg'>Something went wrong!</span>");
                        }

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#store_form_book_{{$row->id}}').click(function (e) {
                    e.preventDefault();
                    //alert('hello_{{$row->id}}');
                    $('#gif_book_{{$row->id}}').show();
                    $('#gif_book_{{$row->id}}').css('visibility', 'visible');
                    //$('#store_form').prop('disabled', true);
                    var formDatas = new FormData(document.getElementById('BookDepartureForm_{{$row->id}}'));
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
                            $('#gif_book_{{$row->id}}').hide();
                            if (data.error) {
                                $("#error_book_msg_{{$row->id}}").html(data.error);
                                $('#gif_book_{{$row->id}}').hide();
                            } else if (data.required) {
                                $("#error_book_msg_{{$row->id}}").html(data.required);
                                $('#gif_book_{{$row->id}}').hide();
                            } else {
                                $('#mesegese_book_{{$row->id}}').html("<span class='sussecmsg'>Success!</span>");
                                //window.location = data.url;
                                location.reload();
                            }

                        },
                        errors: function () {
                            $('#gif_book_{{$row->id}}').hide();
                            $('#mesegese_book_{{$row->id}}').html("<span class='sussecmsg'>Something went wrong!</span>");
                        }

                    });
                });
            });
        </script>

    @endforeach
    
@endsection