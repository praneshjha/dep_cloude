<div class="col-md-12">
    <div class="row">
        @if(count($departures) > 0)
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
                        @if($departure->start_date >= $today)
                            @if($departure->status == 1 )
                                @if($departure->approve == 1)
                                    @if($departure->total_seat-($departure->book_sum+$departure->hold_sum) > 0 )
                                        <div class="ribbon-style">
                                            <div class="ribbon ribbon-success float-right">OPEN</div>
                                        </div>
                                    @else
                                        <div class="ribbon-style">
                                            <div class="ribbon ribbon-danger float-right">SOLDOUT</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="ribbon-style">
                                        <div class="ribbon ribbon-primary float-right">Under Review</div>
                                    </div>
                                @endif
                            @else
                                @if($departure->company_publish)
                                    <div class="ribbon-style">
                                        <div class="ribbon ribbon-success float-right">OPEN</div>
                                    </div>
                                @else
                                    <div class="ribbon-style">
                                        <div class="ribbon ribbon-danger float-right">DRAFT</div>
                                    </div>
                                @endif

                            @endif
                        @else
                            <div class="ribbon-style">
                                <div class="ribbon ribbon-secondary float-right">CLOSE</div>
                            </div>
                        @endif
                        <div class="mb-21">
                            <h4 class="card-title">
                                @can('departure_view', $permission)
                                    <a href="{{route('departure_details',$departure->id)}}" class="">
                                        {{$departure->title}}
                                    </a>
                                @endcan
                            </h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="day-info- departureID">ID {{ $departure->dep_id }}</div>
                                <div class="dep-model-action-btn">
                                    @if($departure->status == 0 )
                                        <a href="javascript:void(0);"
                                           @if($departure->inclusion < 1) onClick="alert('Please fill the inclusion section before publishing the departure')"
                                           @elseif($departure->DeparturePrice < 1)
                                               onClick="alert('Please ensure that the minimum price before publishing the departure')"
                                           @elseif($departure->payment_schedule < 1 ) onClick="alert('Please fill the payment schedule section before publishing the departure')"
                                           @elseif($departure->cancelation_schedule < 1 ) onClick="alert('Please fill the cancelation schedule section before publishing the departure')"
                                           @elseif($departure->termspayment == '') onClick="alert('Please fill the Terms section before publishing the departure')"
                                           @else data-toggle="modal" data-target="#myModal{{$departure->id}}"
                                           onClick="PublishModelAllMy({{$departure->id}})"
                                           @endif data-id="{{ $departure->id }}" data-status="{{ $departure->status }}"
                                           title="Publish Departure"><i class="fa fa-upload"></i> </a>
                                    @else
                                    <form id="depDisable-form-{{ $departure->id }}" method="post" action="{{route('departure_unpublish',$departure->id)}}" style="display: none;">
                                    @csrf

                                    {{method_field('POST')}}
                                    </form>
                                    <a href="" onclick="
                                        if (confirm('Are you sure, You want to Unpublish Departure?'))
                                          {
                                            event.preventDefault();
                                            document.getElementById('depDisable-form-{{ $departure->id }}').submit();
                                          }
                                          else
                                          {
                                            event.preventDefault();
                                          }
                                        " style="cursor: pointer;" title="Unpublish">
                                        <i class="fas fa-download" style="color: #681f4a;"></i></a>
                                    @endif
                                    <!-- @if($departure->company_publish == 0 )
                                        <a href="javascript:void(0);" @if($departure->inclusion < 1)
                                            onClick="alert('Please fill the inclusion section before publishing the departure')"



                                        @elseif($departure->DeparturePrice < 1)
                                            onClick="alert('Please ensure that the minimum price before publishing the departure')"



                                        @elseif($departure->payment_schedule < 1 )
                                            onClick="alert('Please fill the payment schedule section before publishing the departure')"




                                        @elseif($departure->cancelation_schedule < 1 )
                                            onClick="alert('Please fill the cancelation schedule section before publishing the departure')"




                                        @elseif($departure->termspayment == '')
                                            onClick="alert('Please fill the Terms section before publishing the departure')"



                                        @else
                                            class="disableDepartue1 text-dark"



                                        @endif data-id="{{ $departure->id }}" data-status="{{ $departure->status }}" title="Publish for Own"><i class="fa fa-upload"></i></a>




                                    @endif -->
                                    @can('departure_view', $permission)
                                        <a href="{{route('departure_details',$departure->id)}}" class=""
                                           title="View Departure" style=""><i class="fa fa-eye"></i></a>
                                    @endcan
                                    @can('departure_edit', $permission)
                                        <a href="{{route('departure_edit',$departure->id)}}" class="" style=""
                                           title="Edit Departure"><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('departure_hold', $permission)
                                        @if($departure->start_date >= $today)
                                            @if($departure->approve == 1 || $departure->company_publish == 1)
                                                @if(($hold < $date))
                                                    <a href="" class="" data-toggle="modal"
                                                       data-target="@if(($hold < $date))#modal{{$departure->id}}@endif"
                                                       title="Hold Units" style=""><i class="fas fa-pause"></i>
                                                    </a>
                                                    @else
                                                    <a href="javascript:void(0);" title="This is Departure Beyond Hold Date" disabled class="tooltipbubble" style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    @endcan

                                    @if($departure->approve == 1 || $departure->company_publish == 1)
                                        @if($departure->start_date >= $today)
                                            <a href="" class="" data-toggle="modal"
                                               data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum )) != 0) .bd-example-modal-sm{{$departure->id}}b @endif"
                                               title="Book Units" style=""><i class="fas fa-clipboard-check"></i></a>
                                        @endif
                                    @endif
                                    @can('departure_booking_history', $permission)
                                        @if($departure->approve == 1 || $departure->company_publish == 1)
                                            <a href="{{route('departure_booking_history', ['id' => $departure->id])}}"
                                               class="" title="Booking History" style=""><i
                                                        class="fas fa-calendar-alt"></i></a>
                                        @endif
                                    @endcan
                                    @if(in_array('departure-clone',$permission) == 1)
                                        @if($departure->approve == 1)
                                        <span class="dropdown cloneDepDropclick{{$departure->id}}" id="">
                                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md " data-toggle="dropdown" id="cloneDepDrop{{$departure->id}}" onclick="DepCloneClick({{$departure->id}})" aria-expanded="true">
                                            <i class="fa fa-clone" aria-hidden="true" style="color:#395eba"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="" class="dropdown-item copyDepartue{{$departure->id}}C" data-toggle="modal"  data-target=".cloneDeparture{{$departure->id}}C" title="Clone Departure" style="color:#000;">Clone to a Departure
                                                </a>
                                                <a href="{{route('departure_series',$departure->id)}}" class="dropdown-item seriesDepartue{{$departure->id}}C" title="Clone Departure" style="color:#000;" target="_blank">Clone to a Series
                                                </a>
                                            </div>
                                        </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <strong class="d-block text-blue">{{date('d M, Y', strtotime($departure->start_date))}}</strong>
                            @if($departure->no_of_nights == null || $departure->no_of_days == null)
                            @else
                                <span class="text-dark font-weight-bold">{{$departure->no_of_nights}}<span
                                            class="text-muted"> Nights</span> / {{$departure->no_of_days}} <span
                                            class="text-muted"> Days</span></span>
                            @endif
                            <div class="d-flex position-relative">
                                @if($departure->from != "")
                                <div>
                                    <p class="dept-from-text">{{$departure->from}}</p>
                                </div>
                                @endif
                                @if($departure->ending_at != "")
                                <div class="position-relative px-2">
                                    <strong style="color:#9f206a;">-</strong>
                                </div>
                                @endif
                                @if($departure->ending_at != "")
                                <div>
                                    <p class="dept-from-text">{{$departure->ending_at}}</p>
                                </div>
                                @endif
                                @if($departure->return_to != "")
                                <div class="position-relative px-2">
                                    <strong style="color:#9f206a;">-</strong>
                                </div>
                                @endif
                                @if($departure->return_to != "")
                                <div>
                                    <p class="dept-from-text">{{$departure->return_to}}</p>
                                </div>
                                @endif
                            </div>
                            <div class="d-flex position-relative inclusion_icons_show">
                                @foreach($departure->inclusion_icons as $inc_icons)
                                    <img src="{{$inc_icons->icon}}" alt="" class="inclu_icon" width="12"
                                         title="{{$inc_icons->name}}">
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-dept bg-per-pax">
                            <div class="d-flex justify-content-between">
                                <ul class="unit-set">
                                    <li>{{$departure->total_seat}} <span>Total Units</span></li>
                                    <li>{{$departure->book_sum}}/{{$departure->hold_sum}}<span>B/H Units</span></li>
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
                <!----Modal-->
                <div class="modal fade bd-example-modal-sm{{$departure->id}}" id="modal{{$departure->id}}" tabindex="-1"
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

                            <form role="form" id="HoldDepartureForm_{{$departure->id}}" style="background-color: #fdfdfd;" class="p-1">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-center totalava_unit">
                                            @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum)>0)
                                                <div class="bh_units">
                                                    <input type="hidden" class="form-control" value="{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" readonly>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Avl Units</label>
                                                            <input type="text" class="form-control" id="" name="available" value="{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" name="available" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="bh_units">
                                                    <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Avl Units</label>
                                                            <input type="text" class="form-control" id="" name="available" value="Over Holded:{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="bh_units">
                                                <div class="form-group">
                                                    <input type="hidden" name="id" value="{{$departure->id}}">
                                                    <input type="hidden" name="current_hours" value="{{date('H')}}">
                                                    <input type="hidden" name="current_minutes" value="{{date('i')}}">
                                                    <label for="formGroupExampleInput">Hold Till</label>
                                                    <input type="hidden" class="form-control" id="exampleFormControlSelect2"
                                                           name="hours" value="{{$departure->hold_duration}}" readonly>
                                                    <input type="text" class="form-control" id="exampleFormControlSelect2"
                                                           name="hold_time"
                                                           value="{{date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))}}"
                                                           readonly>
                                                    <input type="hidden" class="form-control" id="exampleFormControlSelect2"
                                                           name="auto_release"
                                                           value="{{date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))}}"
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="bookingMdodal">
                                        <div class="row">
                                            @foreach($departure->departure_price as $require)
                                                <input type="hidden" name="sairing[]" value="{{$require->sharing}}">
                                                <div class="row d-none">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="" name="" value="{{ucfirst($require->sharing)}}" readonly>
                                                        </div>
                                                    </div>
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
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="" name="hotel_name[]" value="{{$require->hotel_name}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="" name="hotel_type[]" value="{{$require->hotel_type}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="" name="transport_type[]" value="{{$require->transport_type}}" readonly>
                                                        </div>
                                                    </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="" name="airport_transfers[]" value="{{$require->airport_transfers}}" readonly>
                                                            </div>
                                                        </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="" name="meal_plan[]" value="{{$require->meal_type}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="" name="group_size[]" value="{{$require->group_size}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 pr-5">
                                                        <div class="form-group"><!--                                                            <input class="form-control required_unit{{$departure->id}}" id="require_hold_{{$require->id}}" name="hold[]">-->
                                                        </div>
                                                    </div>
                                                    {{--<div class="col-md-1" style="padding: 0px;">
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                            <label id="require_hold_price_{{$require->id}}"></label>
                                                            <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                            <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                @if(in_array(32, json_decode($columns)))
                                                                <div class="bh_units">
                                                                    <span>Sharing Basis</span>
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
                                    <span class="text-danger" id="error{{$departure->id}}"></span>
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
                                                <span id="total_pricebook{{$departure->id}}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-md-12 text-right">
                                        <img src="{{ asset('images/loader.gif') }}" id="gif_{{$departure->id}}"
                                             style="width: 3%;  visibility: hidden;">
                                        <span class="text-success" id="mesegese_{{$departure->id}}"
                                              style="margin-left: 10px"></span>
                                        <button class="btn btn-primary active mr-2" type="button"
                                                id="store_form_hold_{{$departure->id}}"><i class="fa fa-save"></i> Hold
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
            @endforeach
        @else
            <div class="col-md-12 text-center">
                <h3 class="" style="position:relative; margin-top: 50px;">Departure not found.</h3>
            </div>
        @endif

        <div class="col-md-12 pagiNate" style="text-align:right;">{{$departures->withQueryString()->links()}}</div>
    </div>
</div>

@section('footerSection')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/select2.full.min.js')}}"></script>
    <script src="{{asset('js/customJS/basic-details.js')}}"></script>
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
        //     var token = "{{ csrf_token() }}";
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
        //                 window.location.href = "{{route('departure')}}";
        //             }
        //         });
        //     }
        // });
        // $(".disableDepartue1").click(function () {
        //     if (confirm("Are You sure, Want to publish this departure?"))
        //         var id = $(this).data("id");
        //     var status = $(this).data("status");
        //     //var flag = (status == 0)?'Buyer':'Buyer & Supplier';
        //     var token = "{{ csrf_token() }}";
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
        //                 window.location.href = "{{route('departure')}}";
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
    @foreach( $departures as $key=>$row )

        <?php
        if (ucfirst(auth::user()->country) == 'India') {
            $value = intval($row->price_inr);
            $single_value = intval($row->single_supplyment_price_inr);
        } else {
            $value = intval($row->price_usd);
            $single_value = intval($row->single_supplyment_price_usd);
        }

        ?>


        <div class="modal fade cloneDeparture{{$row->id}}C" tabindex="-1" role="dialog"
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

                    <form id="myCopyForm{{$row->id}}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Departure Name
                                    <span class="validationError" id="titleerror_{{$row->id}}"></span></label>
                                <input type="hidden" class="form-control" id="dep-id" name="dep_id"
                                       value="{{($row->id)}}">
                                <input type="text" class="form-control" id="title_{{($row->id)}}" name="title"
                                       value="{{$row->title}}">

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">Departure Date<span class="validationError"></span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control pull-right" value="{{date('d M Y', strtotime($row->start_date))}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">New Departure Date<span class="validationError" id="start_dateerror_{{$row->id}}"></span></label>
                                        <div class="input-group date">
                                            <input type="text"
                                                   class="form-control pull-right start_date{{$row->id}} fromdate"
                                                   name="start_date" id="start_date{{$row->id}}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="calendar{{$row->id}}"><i class="fa fa-calendar calendar{{$row->id}}"
                                                            aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Total Units<span class="validationError" id="total_seaterror_{{$row->id}}"></span></label>
                                        <input type="text" class="form-control" name="total_seat"
                                               value="{{$row->total_seat}}" id="total_seat_{{$row->id}}">
                                    </div>
                                </div>
                                {{--<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">No. of Clones<span class="validationError" id="total_cl_{{$row->id}}"></span></label>
                                        <select name="no_of_clone" class="form-control">
                                            <?php $a = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]; ?>
                                            @foreach($a as $ab)
                                                <option value="{{$ab}}">{{$ab}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                --}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal" id=""><i
                                        class="flaticon-cancel-12"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary" id="copyDeparture_{{$row->id}}">Clone
                                Departure
                            </button>
                            <img src="{{ asset('images/loader.gif') }}" id="gif_{{$row->id}}"
                                 style="width: 8%; visibility: hidden;">
                            <span class="text-success" id="mesegese_{{$row->id}}" style="margin-left: 10px"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!----End Copy Modal-->

        <script>

            $(document).ready(function () {
                $('.start_date{{$row->id}}').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd-M-yy',
                    minDate: 0,
                });
                $('.calendar{{$row->id}}').click(function () {
                    $(".start_date{{$row->id}}").focus();
                });

                $('#countryUrl').select2({
                    placeholder:'Select Country',
                    // ajax: {
                    //     url: "{{route('public_destinations')}}",
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


            $('#check{{$row->id}}').keyup(function () {
                var value = $(this).val();
                var total = '{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}';
                var subtotal = value - total;
                if ($(this).val() > {{($row->total_seat)-($row->hold_sum + $row->book_sum)}}) {
                    //alert('Your are Request ' +subtotal + ' Extra Departure');
                    $("#message{{$row->id}}").text('Request Mode ' + subtotal + " Extra Departure");
                    //$(this).val('')
                } else {
                    $("#message{{$row->id}}").text('');
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
                    //alert('Value can not be greater than {{($row->total_seat)-($row->hold_sum + $row->book_sum)}}');
                    $("#error{{$row->id}}").text('Value can not be greater than - ' + total + "");
                    $(this).val('')
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
                $("#required_pricebook{{$row->id}}").html(sum);

                $("#total_pricebook{{$row->id}}").html(sum);
            })
            $("#single_bookbook{{$row->id}}").keyup(function () {
                var required = $("#single_bookbook{{$row->id}}").val();
                var price = '{{$single_value}}'
                var sum = parseInt(required) * parseInt(price);
                //alert(sum);
                $("#single_pricebook{{$row->id}}").html(sum);

                var required1 = $("#book{{$row->id}}").val();
                var price1 = '{{$value}}'
                var sum1 = parseInt(required1) * parseInt(price1);
                var total = sum + sum1;
                $("#total_pricebook{{$row->id}}").html(total);
            })
        </script>

        <!-- <script>
  $(document).ready(function(){
    $("#1{{$row->id}}").click(function(){
      location.reload(true);
    });
  });
  $(document).ready(function(){
    $("#2{{$row->id}}").click(function(){
      location.reload(true);
    });
  });
  $(document).ready(function(){
    $("#3{{$row->id}}").click(function(){
      location.reload(true);
    });
  });
  $(document).ready(function(){
    $("#1{{$row->id}}").click(function(){
      location.reload(true);
    });
  });
</script> -->
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#copyDeparture_{{$row->id}}').click(function (e) {
                    e.preventDefault();
                    jQuery('#gif_{{$row->id}}').show();
                    // var id = jQuery('#dep-id').val();
                    // alert(id);

                    var title = jQuery('#title_{{$row->id}}').val();
                    if (title == "") {
                        jQuery('span#titleerror_{{$row->id}}').html('This field is required!');
                        jQuery('input#title_{{$row->id}}').focus();
                        return false;
                    } else {
                        jQuery('span#titleerror_{{$row->id}}').hide();
                    }
                    var start_date = jQuery('#start_date_{{$row->id}}').val();
                    if (start_date == '') {
                        jQuery('span#start_dateerror_{{$row->id}}').html('This field is required!');
                        jQuery('input#start_date_{{$row->id}}').focus();
                        return false;
                    } else {
                        jQuery('span#start_dateerror_{{$row->id}}').hide();
                    }

                    var total_seat = jQuery('#total_seat_{{$row->id}}').val();
                    if (total_seat == "") {
                        jQuery('span#total_seaterror_{{$row->id}}').html('This field is required!');
                        jQuery('input#total_seat_{{$row->id}}').focus();
                        return false;
                    } else {
                        jQuery('span#total_seaterror_{{$row->id}}').hide();
                    }

                    jQuery('#gif_{{$row->id}}').css('visibility', 'visible');
                    jQuery('#copyDeparture_{{$row->id}}').html('Please wait...')
                    jQuery('#copyDeparture_{{$row->id}}').prop('disabled', true);

                    var formDatas = new FormData(document.getElementById('myCopyForm{{$row->id}}'));
                    jQuery.ajax({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "{{route('departure_copy')}}",
                        data: formDatas,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $('#gif_{{$row->id}}').hide();
                            console.log(data);
                            $('#mesegese_{{$row->id}}').html("<span class='sussecmsg'>Success!</span>");
                            //window.location = data.url;
                            window.location.reload();
                        },
                        statusCode: {
                            500: function (status) {
                                console.log(status);
                                jQuery('#gif_{{$row->id}}').hide();
                                jQuery('#mesegese_{{$row->id}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            },

                            400: function () {
                                jQuery('#gif_{{$row->id}}').hide();
                                jQuery('#mesegese_{{$row->id}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            },
                            419: function () {
                                jQuery('#gif_{{$row->id}}').hide();
                                jQuery('#mesegese_{{$row->id}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                            },
                            401: function () {
                                jQuery('#gif_{{$row->id}}').hide();
                                jQuery('#mesegese_{{$row->id}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
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
                               // alert(data.required);
                                $("#required_error_{{$row->id}}").html(data.error);
                                $("#required_error_{{$row->id}}").html(data.required);
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
        <div class="modal fade bd-example-modal-sm{{$row->id}}b" tabindex="-1" role="dialog"
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

                    <form role="form" id="BookDepartureForm_{{$row->id}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="id" value="{{$row->id}}">
                                    <div class="d-flex align-items-center totalava_unit">
                                        <h5>Available Units :</h5>
                                        <span class="ml-2 text-black text-bold">{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}</span>
                                        <input type="hidden" class="form-control" id="" name="available"
                                               value="{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}"
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
                                    @foreach($row->departure_price as $require)
                                        <input type="hidden" name="sairing[]" value="{{$require->sharing}}">
                                        <div class="row d-none">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <!-- <label for="exampleFormControlInput1">{{ucfirst($require->sharing)}}</label> -->
                                                    <input type="text" class="form-control" id="" name=""
                                                           value="{{ucfirst($require->sharing)}}" readonly>
                                                </div>
                                            </div>
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
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="hotel_name[]" value="{{$require->hotel_name}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="hotel_type[]" value="{{$require->hotel_type}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id=""
                                                           name="transport_type[]"
                                                           value="{{$require->transport_type}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="airport_transfers[]" value="{{$require->airport_transfers}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="meal_plan[]" value="{{$require->meal_type}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="" name="group_size[]" value="{{$require->group_size}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2 pr-5">
                                                <div class="form-group">
                                                    <!--                                                    <input type="text" class="form-control" id="require_{{$require->id}}" name="book[]" placeholder="Enter require unit">-->
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                {{--                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                    <div class="">
                                                        <label id="require_currency_{{$require->id}}"></label>
                                                        <input type="text" id="require_price_{{$require->id}}" style="border:none; margin-left: -17px;" readonly>
                                                    </div>
                                                    <input type="hidden" id="currency_c_{{$require->id}}" value="{{$require->currency_code}} " name="currency">
                                                    <input type="hidden" id="currency_{{$require->id}}" value="{{$require->currency_symbol}} " name="currency_symbol">
                                                </div> --}}
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
                                                        <!-- {{$columns}} -->
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
                                                            <input type="text" class="form-control" id="require_{{$require->id}}" name="book[]" placeholder="Enter require unit">
                                                        </div>
                                                    </div>
                                                    <hr class="mt-1 mb-1">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <strong>Price</strong>
                                                        <div class="form-group mb-0">
                                                            <input type="hidden" class="form-control" id="price_{{$require->id}}" name="price[]" value="{{$require->price}}" style="border:none">
                                                            <div class="">
                                                                <label id="require_currency_{{$require->id}}"></label>
                                                                <input type="text" id="require_price_{{$require->id}}" style="border:none;" readonly>
                                                            </div>
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

                            <span class="text-danger" id="error{{$row->id}}"></span>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="exampleFormControlInput1">Lead Passenger Name<span id="error_book_msg_{{$row->id}}" class="text-danger" style="position: absolute; right: 0%;"></span></label>
                                        <input type="text" class="form-control" id="require_pasanger" name="lead_pasanger_name" placeholder="">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleFormControlInput1">Note</label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <span id="total_price_book{{$row->id}}"></span>
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

        <script>
            <?php $departureCount = count($departure->departure_price); $j = 0; ?>
            var total = 0;
            @foreach($row->departure_price as $require)
            $("#require_{{$require->id}}").keyup(function () {
                var group_size = {{$require->group_size}};
                var check = parseInt($("#require_{{$require->id}}").val());
                var sum_no = check + 1;
                var sum_no1 = check + 2;
                if (check != '') {
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
                    $("#require_currency_{{$require->id}}").html(currency);
                    $("#require_price_{{$require->id}}").val(sum);
                    total = sum + total;
                } else {
                    $("#require_price_{{$require->id}}").val('');

                    var price = $("#price_{{$require->id}}").val();
                    var currency = $("#currency_{{$require->id}}").val();
                    price = parseInt(price);
                    total = total - price;
                    //alert(currency);
                    //$("#total_price_book{{$row->id}}").html("Total Price " + currency+' '+total);
                }


                //$("#total_price_book{{$row->id}}").html("Total Price " + currency+' '+total);
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
                if (check != '') {
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
    @endforeach
    @foreach($departures as $departure)
        <!----Modal-->
        <div class="modal fade" id="myModal{{$departure->id}}" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content" style="min-height: 152px; width: 50%;">
                    <form>
                        <div class="modal-body">
                            <input type="hidden" name="departure_id{{$departure->id}}" value="{{$departure->id}}">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="publish2"
                                       name="publish{{$departure->id}}" value="2" checked>
                                <label class="form-check-label" for="materialChecked">Publish for all</label>
                            </div>
                            @if($departure->company_publish == 0)
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="publish1"
                                           name="publish{{$departure->id}}" value="1">
                                    <label class="form-check-label" for="materialUnchecked">Publish for own
                                        company</label>
                                </div>
                            @endif

                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer" style="text-align: right;">
                            <button class="btn btn-primary btn-sm" id="departure_published{{$departure->id}}">Submit
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
            $("#departure_published{{$departure->id}}").click(function () {
                if (confirm("Are You sure, Want to publish this departure?"))
                    var id = $('input[name="departure_id{{$departure->id}}"]').val();
                var status = $(this).data("status");
                var publish = $('input[name="publish{{$departure->id}}"]:checked').val();

                var token = "{{ csrf_token() }}";
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
                            window.location.href = "{{route('departure')}}";
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
                            window.location.href = "{{route('departure')}}";
                        }
                    });
                }
            });
        </script>
    @endforeach
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
    <link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
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
@endsection
