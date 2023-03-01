<div class="row">
    <div class="col-md-12">
        <div class="row mt-3">
            @if(count($departures)> 0 )
                <div class="col-md-12">
                    <div class="card-box">
                        <div class="table-responsive" id="TableView">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Departure Name</th>
                                    <th>Owner</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Avl. Units</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
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
                                    <tr>
                                        <td>{{ $departure->dep_id }}</td>
                                        <td><a href="{{route('all_departure_details',$departure->id)}}" title="{{$departure->title}}" data-toggle="tooltip">{{$departure->title}}</a></td>
                                        <td><a href="{{url('profile/'.$departure->id.'/'.$departure->company_url)}}" class="userprofileName">{{$departure->departure_ownner}}</a></td>
                                        <td>{{date('d M, Y', strtotime($departure->start_date))}}</td>
                                        <td>
                                            @if($departure->no_of_nights == null || $departure->no_of_days == null)
                                                
                                            @else
                                                {{$departure->no_of_nights}}N/{{$departure->no_of_days}}D
                                            @endif
                                        </td>
                                        <td>{{strtok($departure->from, ",")}}</td>
                                        <td>{{$departure->ending_at}}</td>
                                        <td>
                                            @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum) > 0)
                                                {{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}
                                            @endif
                        
                                        </td>
                                        <td>
                                            @foreach($departure->price as $price)
                                                {{$price->currency_symbol}}  {{$price->price}}
                                            @endforeach
                                        </td>
                                        <td>
                                            @if(Auth::user()->main_user_type == 2)
                                            <a href="javascript:void(0)" id="dep-{{$departure->id}}" title="chat" class="chat_data tooltipbubble"><i class="far fa-comment-dots"></i></a>
                                            <input type="hidden" name="dep_val_{{$departure->id}}" id="dep-{{$departure->id}}-val" value="{{$departure->id}}">
                                                <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                <a href="{{route('departure_edit',$departure->id)}}" title="Edit Departure"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="@if(($hold < $date)).bd-example-modal-sm{{$departure->id}} @endif" title="Hold Units"><i class="fas fa-pause"></i></a>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif" title="Book Units"><i class="far fa-calendar-check"></i></a>
                                            @else
                                            <input type="hidden" name="dep_val_{{$departure->id}}" id="dep-{{$departure->id}}-val" value="{{$departure->id}}">
                                            <a href="javascript:void(0)" id="dep-{{$departure->id}}" title="chat" class="chat_data tooltipbubble"><i class="far fa-comment-dots"></i></a>
                                                <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="@if(($hold < $date)).bd-example-modal-sm{{$departure->id}} @endif" title="Hold Units"><i class="fas fa-pause"></i></a>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif" title="Book Units"><i class="far fa-calendar-check"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0)
                                                <span class="badge bg-success">OPEN</span>
                                            @else
                                                <span class="badge bg-danger">SOLDOUT</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade bd-example-modal-sm{{$departure->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content hold">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-white1" id="mySmallModalLabel">Hold Units</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <form role="form" id="HoldDepartureForm_{{$departure->id}}" style="background-color: #fdfdfd;" class="p-1">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)>0)
                                                                <input type="hidden" class="form-control" value="{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" readonly>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Avl Units</label>
                                                                        <input type="text" class="form-control" id="" name="available" value="{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" name="available" readonly>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlInput1">Avl Units</label>
                                                                        <input type="text" class="form-control" id="" name="available" value="Over Holded:{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" readonly>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <input type="hidden" name="id" value="{{$departure->id}}">
                                                                    <input type="hidden" name="current_hours" value="{{date('H')}}">
                                                                    <input type="hidden" name="current_minutes" value="{{date('i')}}">
                                                                    <label for="formGroupExampleInput">Hold Till</label>
                                                                    <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hours" value="{{$departure->hold_duration}}" readonly>
                                                                    <input type="text" class="form-control" id="exampleFormControlSelect2" name="hold_time" value="{{date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))}}" readonly>
                                                                    <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="auto_release" value="{{date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))}}" readonly>
                                                                </div>
                                                            </div>
                                                            @if(in_array(32, json_decode($columns)))
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Room Sharing</label>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            @if(in_array(33, json_decode($columns)))
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
                                                            @endif
                                                            @if(in_array(35, json_decode($columns)))
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Hotel Type</label>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            @if(in_array(36, json_decode($columns)))
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Transport Type</label>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Airport Transfer</label>
                                                                </div>
                                                            </div>
                                                            @if(in_array(38, json_decode($columns)))
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Meal Plan</label>
                                                                </div>
                                                            </div>
                                                            @endif
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
                                                        @foreach($departure->departure_price as $require)
                                                            <input type="hidden" name="sairing[]" value="{{$require->sharing}}">
                                                            <div class="row">
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
                                                                        <input class="form-control required_unit{{$departure->id}}" id="require_hold_{{$require->id}}" name="hold[]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1" style="padding: 0px;">
                                                                    <div class="form-group">
                                                                        <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                                        <label id="require_hold_price_{{$require->id}}"></label>
                                                                        <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                                        <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
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
                                @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12 mt-3">{{$departures->withQueryString()->links()}}</div>
                        </div>
                    </div>
                </div>

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
    <script src="{{asset('js/select2.full.min.js')}}"></script>
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
                        <h5 class="modal-title text-white1" id="mySmallModalLabel">Book Units</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <form role="form" id="BookDepartureForm_{{$row->id}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="id" value="{{$row->id}}">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Available Units</label>
                                        <input type="text" class="form-control" id="" name="available" value="{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}" readonly>
                                    </div>
                                </div>
                                @if(in_array(32, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Sharing</label>
                                        </div>
                                    </div>
                                    @endif
                                    @if(in_array(33, json_decode($columns)))
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
                                    @endif
                                    @if(in_array(35, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Hotel Type</label>
                                        </div>
                                    </div>
                                    @endif
                                    @if(in_array(36, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Transport Type</label>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Airport Transfer</label>
                                        </div>
                                    </div>
                                    @if(in_array(38, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Meal Plan</label>
                                        </div>
                                    </div>
                                    @endif
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
                            @foreach($row->departure_price as $require)
                                <input type="hidden" name="sairing[]" value="{{$require->sharing}}">
                                <div class="row">
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
                                            <input type="text" class="form-control" id="require_{{$require->id}}" name="book[]" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                            <label id="require_price_{{$require->id}}"></label>
                                            <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                            <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

                //alert(sum);
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
                var sum_no = check+1;
                var sum_no1 = check+2;
                if(check != ''){
                    if(group_size == 2){
                        if((check % 2) != 0){
                           $("#require_{{$require->id}}").val(sum_no); 
                        }
                    }
                    if(group_size == 3){
                        if((check % 3) == 1){
                           $("#require_{{$require->id}}").val(sum_no1);
                        }
                        else if((check % 3) == 2){
                            $("#require_{{$require->id}}").val(sum_no);
                        }
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
                    $("#total_pricebook{{$row->id}}").html("Total Price " + currency + +total);
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
                var sum_no = check+1;
                var sum_no1 = check+2;
                if(check != ''){
                    if(group_size == 2){
                        if((check % 2) != 0){
                           $("#require_hold_{{$require->id}}").val(sum_no); 
                        }
                    }
                    if(group_size == 3){
                        if((check % 3) == 1){
                           $("#require_hold_{{$require->id}}").val(sum_no1);
                        }
                        else if((check % 3) == 2){
                            $("#require_hold_{{$require->id}}").val(sum_no);
                        }
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
            var userName = document.querySelector('.required_unit{{$row->id}}');
            userName.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = newValue;
            }

            var userName = document.querySelector('.required_unit1{{$row->id}}');
            userName.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = newValue;
            }

            var userName = document.querySelector('.required_unit2{{$row->id}}');
            userName.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = newValue;
            }
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
            line-height: 1.5;
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
            padding: 3px 8px;
            line-height: 13px;
        }

        .ribbon-style {
            position: absolute;
            top: 7px;
            px;
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

        .hold, form, .form-group, label {
            line-height: 1.2;
        }
        
    </style>
@endsection