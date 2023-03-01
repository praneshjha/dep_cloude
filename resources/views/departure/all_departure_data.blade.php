<div class="row">

    <div class="col-md-12">
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
                                    <a href="{{route('all_departure_details',$departure->id)}}">{{$departure->title}}</a>
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
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="@if(($hold < $date)).bd-example-modal-sm{{$departure->id}} @endif" title="Hold Units" class="tooltipbubble"><i class="fas fa-pause"></i></a>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif"
                                           title="Book Units" class="tooltipbubble"><i class="far fa-calendar-check"></i></a>
                                        <div class="shareiconList">
                                            <a href="javascript:void(0);" class="ShareIcons">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                    <path d="M448 127.1C448 181 405 223.1 352 223.1C326.1 223.1 302.6 213.8 285.4 197.1L191.3 244.1C191.8 248 191.1 251.1 191.1 256C191.1 260 191.8 263.1 191.3 267.9L285.4 314.9C302.6 298.2 326.1 288 352 288C405 288 448 330.1 448 384C448 437 405 480 352 480C298.1 480 256 437 256 384C256 379.1 256.2 376 256.7 372.1L162.6 325.1C145.4 341.8 121.9 352 96 352C42.98 352 0 309 0 256C0 202.1 42.98 160 96 160C121.9 160 145.4 170.2 162.6 186.9L256.7 139.9C256.2 135.1 256 132 256 128C256 74.98 298.1 32 352 32C405 32 448 74.98 448 128L448 127.1zM95.1 287.1C113.7 287.1 127.1 273.7 127.1 255.1C127.1 238.3 113.7 223.1 95.1 223.1C78.33 223.1 63.1 238.3 63.1 255.1C63.1 273.7 78.33 287.1 95.1 287.1zM352 95.1C334.3 95.1 320 110.3 320 127.1C320 145.7 334.3 159.1 352 159.1C369.7 159.1 384 145.7 384 127.1C384 110.3 369.7 95.1 352 95.1zM352 416C369.7 416 384 401.7 384 384C384 366.3 369.7 352 352 352C334.3 352 320 366.3 320 384C320 401.7 334.3 416 352 416z"/>
                                                </svg>
                                            </a>
                                            <ul class="submenu shareableIcons">
                                                <li><a href="mailto:?subject={{$departure->title}}&body={{route('all_departure_details',$departure->id)}}" title="Email Share" class="mail share_icon"><i class="far fa-envelope"></i></a></li>
                                                <li>
                                                    <a href="https://www.facebook.com/sharer.php?s=100&p[title]={{$departure->title}}&p[url]={{route('all_departure_details',$departure->id)}}&p[summary]={!! $departure->description !!}&p[images][0]={{$departure->logo_image}}"
                                                       target="_blank" title="FB Share" class="facebook" class="facebook share_icon"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="http://twitter.com/intent/tweet?original_referer={{route('all_departure_details',$departure->id)}}&text={{$departure->title}}&url={{route('all_departure_details',$departure->id)}}"
                                                       target="_blank" title="Twitter Share" class="twitter share_icon"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="https://wa.me/?text={{route('all_departure_details',$departure->id)}}" target="_blank" title="Whatsapp Share" class="whatsapp share_icon"><i class="fab fa-whatsapp"></i></a></li>
                                                <li><a href="http://pinterest.com/pin/create/bookmarklet/?media={{$departure->logo_image}}&url={{route('all_departure_details',$departure->id)}}&is_video=false&description={{$departure->title}}"
                                                       target="_blank" title="Pinterest Share" class="pinterest share_icon"><i class="fab fa-pinterest-p"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if($departure->from != "" || $departure->ending_at != "" $departure->return_to != "")
                                    <div class="d-flex position-relative">
                                        @if($departure->from != "")
                                        <div>
                                            <form action="{{route('all_departure')}}" method="get" class="dept-from-text">
                                                <input type="hidden" name="departure_from" value="{{$departure->from}}">
                                                <button type="submit" class="btn btn-sm"
                                                        style="background-color: #ffffff;border:node;font-size: 14px;margin-bottom: 0; color:  #3396d7;font-weight: 600;padding:0;">{{strtok($departure->from, ",")}}</button>
                                            </form>
                                        </div>
                                        @endif
                                        @if($departure->ending_at != "")
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
                                        @if($departure->return_to != null)
                                        <div>
                                            <form action="{{route('all_departure')}}" method="get" class="dept-from-text">
                                                <input type="hidden" name="departure_from" value="{{$departure->return_to}}">
                                                <button type="submit" class="btn btn-sm"
                                                style="background-color: #ffffff;border:node;font-size: 14px;margin-bottom: 0; color:  #3396d7;font-weight: 600;padding:0;">{{strtok($departure->return_to, ",")}}</button>
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
                    <div class="modal fade bd-example-modal-sm{{$departure->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content hold">
                                <div class="modal-header">
                                    <h5 class="modal-title text-white" id="mySmallModalLabel">Hold Units</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                                <form role="form" id="HoldDepartureForm_{{$departure->id}}" style="background-color: #fdfdfd;" class="p-1">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 d-flex align-items-center totalava_unit">
                                                @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)>0)
                                                    <div class="bh_units">
                                                        <input type="hidden" class="form-control" value="{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" readonly>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <h5 class="mb-0">Available Units :</h5>
                                                                <span class="text-black text-bold">{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}</span>
                                                                <input type="hidden" class="form-control" id="" name="available" value="{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" name="available" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="bh_units">
                                                        <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <h5 class="mb-0">Available Units :</h5>
                                                                <span class="text-black text-bold">{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}</span>
                                                                <input type="hidden" class="form-control" id="" name="available" value="Over Holded:{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="bh_units">
                                                    <div class="form-group mb-0">
                                                        <input type="hidden" name="id" value="{{$departure->id}}">
                                                        <input type="hidden" name="current_hours" value="{{date('H')}}">
                                                        <input type="hidden" name="current_minutes" value="{{date('i')}}">
                                                        <h5 class="mb-0">Hold Till</h5>
                                                        <span class="text-black text-bold">{{date('d-M-Y | h:ia', strtotime("+{$new_time} hours +30 minutes"))}}</span>
                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hours" value="{{$departure->hold_duration}}" readonly>
                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hold_time" value="{{date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))}}" readonly>
                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="auto_release" value="{{date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2">
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
                                                @foreach($departure->departure_price as $require)
                                                    <input type="hidden" name="sairing[]" value="{{$require->sharing}}">
                                                    <div class="row d-none">
                                                        @if(in_array(32, json_decode($columns)))
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="" value="{{ucfirst($require->sharing)}}" readonly>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if(in_array(33, json_decode($columns)))
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="flight_class[]" value="{{$require->flight_class}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="passenger[]" value="{{$require->passenger}}" readonly>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if(in_array(35, json_decode($columns)))
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="hotel_name[]" value="{{$require['hotel_name']}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="hotel_type[]" value="{{$require->hotel_type}}" readonly>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if(in_array(36, json_decode($columns)))
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="transport_type[]" value="{{$require->transport_type}}" readonly>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="airport_transfers[]" value="{{$require->airport_transfers}}" readonly>
                                                            </div>
                                                        </div>
                                                        @if(in_array(38, json_decode($columns)))
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="meal_plan[]" value="{{$require->meal_type}}" readonly>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="group_size[]" value="{{$require->group_size}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 pr-5">
                                                            <div class="form-group">
                                                                <!--                                                                <input class="form-control required_unit{{$departure->id}}" id="require_hold_{{$require->id}}" name="hold[]">-->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding: 0px;">
                                                            <!--                                                            <div class="form-group">
                                                                <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                                <label id="require_hold_price_{{$require->id}}"></label>
                                                                <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                                <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                            </div>-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    @if(in_array(32, json_decode($columns)))
                                                                    <div class="bh_units">
                                                                        <span>Room Sharing</span>
                                                                        <strong>{{ucfirst($require->sharing)}}</strong>
                                                                    </div>
                                                                    @endif
                                                                    @if(in_array(33, json_decode($columns)))
                                                                    <div class="bh_units">
                                                                        <span>Flight Class</span>
                                                                        <strong>{{ucfirst($require->flight_class)}}</strong>
                                                                    </div>
                                                                    <div class="bh_units">
                                                                        <span>Passenger Type</span>
                                                                        <strong>{{$require->passenger}}</strong>
                                                                    </div>
                                                                    @endif
                                                                    
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    @if(in_array(35, json_decode($columns))) 
                                                                    <div class="bh_units">
                                                                        <span>Hotel Name</span>
                                                                        <strong>{{$require->hotel_name}}</strong>
                                                                    </div>
                                                                    <div class="bh_units">
                                                                        <span>Hotel Type</span>
                                                                        <strong>{{$require->hotel_type}}</strong>
                                                                    </div>
                                                                    @endif
                                                                    @if(in_array(36, json_decode($columns)))
                                                                    <div class="bh_units">
                                                                        <span>Transport Type</span>
                                                                        <strong>{{$require->transport_type}}</strong>
                                                                    </div>
                                                                    @endif
                                                                    <div class="bh_units">
                                                                        <span>Airport Transfers</span>
                                                                        <strong>{{$require->airport_transfers}}</strong>
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    @if(in_array(38, json_decode($columns)))
                                                                    <div class="bh_units">
                                                                        <span>Meal Plan</span>
                                                                        <strong>{{$require->meal_type}}</strong>
                                                                    </div>
                                                                    @endif
                                                                    <div class="bh_units">
                                                                        <span>Minimum Pax</span>
                                                                        <strong>{{$require->group_size}}</strong>
                                                                    </div>
                                                                    <div class="bh_units">
                                                                        <input class="form-control required_unit{{$departure->id}}" id="require_hold_{{$require->id}}" name="hold[]" placeholder="Enter required units">
                                                                    </div>
                                                                </div>
                                                                <hr class="mt-1 mb-1">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <strong>Price</strong>
                                                                    <div class="form-group mb-0">
                                                                        <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                                        <label id="require_hold_price_{{$require->id}}"></label>
                                                                        <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                                        <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span id="mesegese1_{{$departure->id}}" class="text-danger" style="position: absolute; right: 0%;"></span>
                                        <span class="text-danger" id="error{{$row->id}}"></span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <label for="exampleFormControlInput1">Lead Passenger Name</label>
                                                    <input type="text" class="form-control" id="" name="lead_pasanger_name" placeholder="">
                                                </div>
                                                <div class="col-md-2">
                                                    <span id="total_pricebook{{$row->id}}"></span>
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
                                            <img src="{{ asset('images/loader.gif') }}" id="gif_{{$departure->id}}" style="width: 3%;  visibility: hidden;">
                                            <span class="text-success" id="mesegese_{{$departure->id}}" style="margin-left: 10px"></span>
                                            <button class="btn btn-primary active mr-2" type="button" id="store_form_hold_{{$departure->id}}"><i class="fa fa-save"></i> Hold Units</button>
                                            <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!----End Modal-->

                @endforeach
                <div class="col-md-12">{{$departures->withQueryString()->links()}}</div>
            @else
                <div class="col-md-12" style="text-align:center;">Departure not Found</div>
            @endif
            
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
    </form>
</div>

@section('footerSection')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- <script src="{{asset('morris.min.js')}}"></script> -->
    <!-- <script src="{{asset('js/select2.full.min.js')}}"></script> -->
    <script src="{{asset('js/customJS/basic-details.js')}}"></script>
    <script>

        $(document).ready(function () {
            if ($(".datepicker").length > 0) {
                $(".datepicker").flatpickr({dateFormat: "d-M-Y"});
            }

            if ($("#departure_from").length > 0) {
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

            if ($("#departure_to").length > 0) {
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

                <!----Modal-->
        <div class="modal fade bd-example-modal-sm{{$row->id}}b" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-mb " role="document">
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
                    <form role="form" id="BookDepartureForm_{{$row->id}}">
                        @csrf
                        <div class="bookingMdodal">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" value="{{$row->id}}">
                                        <div class="d-flex align-items-center totalava_unit">
                                            <h5>Available Units :</h5>
                                            <span class="ml-2 text-black text-bold">{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}</span>
                                        </div>
                                        <div class="form-group d-none">
                                            <label for="exampleFormControlInput1">Available Units</label>
                                            <input type="text" class="form-control" id="" name="available" value="{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}" readonly>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
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
                                <div class="row">
                                    @foreach($row->departure_price as $require)
                                        <input type="hidden" name="sairing[]" value="{{$require->sharing}}">
                                        <div class="row d-none">
                                            @if(in_array(32, json_decode($columns)))
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <!-- <label for="exampleFormControlInput1">{{ucfirst($require->sharing)}}</label> -->
                                                    <input type="text" class="form-control" id="" name="" value="{{ucfirst($require->sharing)}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            @if(in_array(33, json_decode($columns)))
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="flight_class[]" value="{{$require->flight_class}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="passenger[]" value="{{$require->passenger}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            @if(in_array(35, json_decode($columns)))
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="hotel_name[]" value="{{$require['hotel_name']}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="hotel_type[]" value="{{$require->hotel_type}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            @if(in_array(36, json_decode($columns)))
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="transport_type[]" value="{{$require->transport_type}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="airport_transfers[]" value="{{$require->airport_transfers}}" readonly>
                                                </div>
                                            </div>
                                            @if(in_array(38, json_decode($columns)))
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="meal_plan[]" value="{{$require->meal_type}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="group_size[]" value="{{$require->group_size}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2 pr-5">
                                                <div class="form-group">
<!--                                                    <input type="text" class="form-control" id="require_{{$require->id}}" name="book[]" placeholder="">-->
                                                </div>
                                            </div>
                                            <div class="col-md-1">
<!--                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                    <label id="require_price_{{$require->id}}"></label>
                                                    <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                    <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                </div>-->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        @if(in_array(32, json_decode($columns)))
                                                        <div class="bh_units">
                                                            <span>Room Sharing</span>
                                                            <strong>{{ucfirst($require->sharing)}}</strong>
                                                        </div>
                                                        @endif
                                                        @if(in_array(33, json_decode($columns)))
                                                        <div class="bh_units">
                                                            <span>Flight Class</span>
                                                            <strong>{{ucfirst($require->flight_class)}}</strong>
                                                        </div>
                                                        <div class="bh_units">
                                                            <span>Passenger Type</span>
                                                            <strong>{{$require->passenger}}</strong>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                    @if(in_array(35, json_decode($columns))) 
                                                        <div class="bh_units">
                                                            <span>Hotel Name</span>
                                                            <strong>{{$require->hotel_name}}</strong>
                                                        </div>
                                                        <div class="bh_units">
                                                            <span>Hotel Type</span>
                                                            <strong>{{$require->hotel_type}}</strong>
                                                        </div>
                                                    @endif
                                                    @if(in_array(36, json_decode($columns)))
                                                        <div class="bh_units">
                                                            <span>Transport Type</span>
                                                            <strong>{{$require->transport_type}}</strong>
                                                        </div>
                                                    @endif
                                                        <div class="bh_units">
                                                            <span>Airport Transfers</span>
                                                            <strong>{{$require->airport_transfers}}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="bh_units">
                                                            <span>Minimum Pax</span>
                                                            <strong>{{$require->group_size}}</strong>
                                                        </div>
                                                    @if(in_array(38, json_decode($columns)))
                                                        <div class="bh_units">
                                                            <span>Meal Plan</span>
                                                            <strong>{{$require->meal_type}}</strong>
                                                        </div>
                                                    @endif
                                                        <div class="bh_units">
                                                            <input type="text" class="form-control" id="require_{{$require->id}}" name="book[]" placeholder="Enter required units">
                                                        </div>
                                                        
                                                    </div>
                                                    <hr class="mt-1 mb-1">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <strong>Price</strong>
                                                        <div class="form-group mb-0">
                                                            <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                            <strong id="require_price_{{$require->id}}"></strong>
                                                            <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                            <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="text-danger" id="error{{$row->id}}"></span>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="exampleFormControlInput1">Lead Passenger Name<span id="error_book_msg_{{$row->id}}" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                                            <input type="text" class="form-control" id="require_{{$row->id}}" name="lead_pasanger_name" placeholder="">

                                        </div>
                                        <div class="col-md-2">
                             <span id="total_pricebook{{$row->id}}">
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
                                    <img src="{{ asset('images/loader.gif') }}" id="gif_book_{{$row->id}}" style="width: 3%;  visibility: hidden;">
                                    <span class="text-success" id="mesegese_book_{{$row->id}}" style="margin-left: 10px"></span>
                                    <button class="btn btn-primary active mr-2" type="button" id="store_form_book_{{$row->id}}"><i class="fa fa-save"></i> Book Units</button>
                                    <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!----End Modal-->

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
            // $(document).ready(function(){
            //   $("#1{{$row->id}}").click(function(){
            //     location.reload(true);
            //   });
            // });
            // $(document).ready(function(){
            //   $("#2{{$row->id}}").click(function(){
            //     location.reload(true);
            //   });
            // });
            // $(document).ready(function(){
            //   $("#3{{$row->id}}").click(function(){
            //     location.reload(true);
            //   });
            // });
            // $(document).ready(function(){
            //   $("#1{{$row->id}}").click(function(){
            //     location.reload(true);
            //   });
            // });


        </script>
        <script>
            $('#isAgeSelected{{$row->id}}').click(function () {
                $("#txtAge{{$row->id}}").toggle(this.checked);
            });
        </script>
        <script>
            // var userName = document.querySelector('.required_unit{{$row->id}}');
            // userName.addEventListener('input', restrictNumber);

            // function restrictNumber(e) {
            //     var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            //     this.value = newValue;
            // }

            // var userName = document.querySelector('.required_unit1{{$row->id}}');
            // userName.addEventListener('input', restrictNumber);

            // function restrictNumber(e) {
            //     var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            //     this.value = newValue;
            // }

            // var userName = document.querySelector('.required_unit2{{$row->id}}');
            // userName.addEventListener('input', restrictNumber);

            // function restrictNumber(e) {
            //     var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            //     this.value = newValue;
            // }
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
    <style>
        /*.modal-dialog {
            max-width: 950px;
            width: 950px;
            margin: 0 auto;
        }*/

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