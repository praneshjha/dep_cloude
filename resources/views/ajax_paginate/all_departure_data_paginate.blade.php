<div class="row">

    <div class="col-md-12"  id="all_dep_appends">
        <div class="row mt-3 gridviewRow">
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

                                        <!-- <a href="javascript:void(0);" data-toggle="modal" data-target="@if(($hold < $date)) #hold_modal @endif" title="Hold Units" class="tooltipbubble" onclick="openHoldModal({{ $departure}})"><i class="fas fa-pause"></i></a> -->
                                        @if(($hold < $date))
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#hold_modal" title="Hold Units" class="tooltipbubble" onclick="openHoldModal({{ $departure}})"><i class="fas fa-pause"></i></a>      
                                        @else
                                         <a href="javascript:void(0);" title="This is Departure Beyond Hold Date" disabled class="tooltipbubble" style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
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
    <div class="col-md-12 flexbox paginate_loader" style="display:none;">
              <div class="multi-spinner-container">
                  <div class="multi-spinner">
                    <div class="multi-spinner">
                      <div class="multi-spinner">
                        <div class="multi-spinner">
                          <div class="multi-spinner">
                            <div class="multi-spinner">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
</div>

<div class="box-footer clearfix text-right">

</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <form method="post" name="myEditForm" enctype="multipart/form-data" class="form-inline" id="myEditForm">
        @csrf
        <div class="modal-dialog modal-sm" role="document" style="width: 65%">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="inlineFlax"><h5 class="modal-title" id="exampleModalLabel">Update Pricing</h5></span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                        <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 5%; display: none;">
                        <span id="mesegess"></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@section('footerSection')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="{{asset('js/customJS/basic-details.js')}}"></script>
    <script>

        $(document).ready(function () {
            if($(".datepicker").length>0){
               $(".datepicker").flatpickr({dateFormat: "d-M-Y"});
            }

            if($("#departure_from").length>0){
                $('#departure_from').select2({
                    @if(isset($from))
                    allowClear: true,
                    @else
                    placeholder: 'Dep. From',
                    allowClear: true,
                    @endif
                    ajax: {
                        url: "/departure_from",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.from,
                                        id: item.from
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            if($("#departure_to").length>0){
                $('#departure_to').select2({
                    @if(isset($to))
                    allowClear: true,
                    @else
                    placeholder: 'Dep. To',
                    allowClear: true,
                    @endif

                    ajax: {
                        url: "/departure_to",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.ending_at,
                                        id: item.ending_at
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }
        });
    </script>
    <script>
        $("#alert").show().delay(5000).queue(function (n) {
            $(this).hide();
            n();
        });
    </script>

    <script type="text/javascript">
        $('.edit-item').click(function () {
            var id = $(this).data("id");
            $('#editModal').modal('show');
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
                    url: "{{url('/get_pricing_ajax')}}?departure_id=" + id,
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
                                html += '<div class="row"><div class="col-md-12 col-lg-12 col-xl-12 pl-4" style="marg><label class="labelClass">' + data.type + ' (' + data.name + ')</label><span class="validationError days_error" id="error_price_inr_' + data.id + '"></span><div class="form-group"><div class="row"><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_inr[' + data.id + ']" value="' + data.symbol_inr + '" readonly><input type="hidden" class="form-control" name="price_type_id[]" value="' + data.id + '"></div><div class="col-md-2 col-lg-2 col-xl-2"><input type="text" class="form-control" name="price_inr[' + data.id + ']" id="price_inr_' + data.id + '" value="' + priceInr + '"></div></div><div class="row"><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_usd[' + data.id + ']" value="' + data.symbol_usd + '" readonly></div><div class="col-md-2 col-lg-2 col-xl-2"><input type="text" class="form-control" name="price_usd[' + data.id + ']" id="price_usd_' + data.id + '" value="' + priceUsd + '"></div></div></div></div></div>';
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
                    url: "{{ route('price_update') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Price has been updated successfully!</span>");
                        //location.reload();
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
                    url: "{{ route('price_update') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Price has been updated successfully!</span>");
                        //location.reload();
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
    
    <style>
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

        .hold, form, .form-group, label {
            line-height: 1.2;
        }
    </style> 
@endsection