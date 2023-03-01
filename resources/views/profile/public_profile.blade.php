@extends('public_layouts.app')
@section('tagSection')
<link rel="canonical" href="https://www.departurecloud.com/profile/{{request()->route('public')}}" />
<title>{!! $users->company_name !!} | Departure Cloud</title>
<meta name="description" content="Check out here all the Group Tour Packages and Fixed Departures published by {!! $users->company_name !!} at DepartureCloud. Contact Now!" />
@endsection
@section('headCssSection')
<link rel="stylesheet" href="{{asset('assets1/css/dc_allstyle.css')}}">
@endsection
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Company Profile</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('message'))
                <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
                    <div id="success_msg" style="">
                        {{ session('message') }}
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12 position-relative mb-2">
                    <figure class="profileCover">
                        <img src="@if(isset($users->banner_image)) {{ asset('BannerImage/' . $users->banner_image) }} @else {{asset('/assets1/images/cover.jpg')}} @endif" alt="profileCover" class="img-fixed">
                    </figure>
                    <div class="d-md-flex justify-content-between profilePicSection">
                        <div class="d-flex">
                            <figure>
                                <img src="@if(isset($users->logo)){{ asset('companyLogo/' . $users->logo) }} @else {{asset('images/no-image.png')}} @endif" class="img-fixed" alt="profile-image">
                            </figure>
                            <div class="ml-2 mt-2">
                                <h5 class="text-blue mb-1 mt-2">{{$users->name}}</h5>
                                <h3 class="mb-2 mt-0">{{$users->company_name}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-4">
                    <div class="card-box position-relative">
                        <h3>Company Details</h3>
                        <dl>
                            <dt><i class="fas fa-user"></i>Contact Name</dt>
                            <dd>{{$users->contactName}}</dd>
                            <dt><i class="fas fa-phone fa-rotate-90"></i> Mobile</dt>
                            <dd>{{$users->mobile}}</dd>
                            <dt><i class="fas fa-envelope"></i> Email</dt>
                            <dd><a href="mailto:{{$users->email}}">{{$users->email}}</a></dd>
                            <div class="socail">
                                @if(isset($users->website))
                                    <dt><i class="fa fa-globe" aria-hidden="true"></i> Website</dt>
                                    <dd>{{$users->website}}</dd>
                                @endif
                                @if(isset($users->facebook))
                                    <dt><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</dt>
                                    <dd>{{$users->facebook}}</dd>
                                @endif
                                @if(isset($users->twitter))
                                    <dt><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</dt>
                                    <dd>{{$users->twitter}}</dd>
                                @endif
                                @if(isset($users->instagram))
                                    <dt><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</dt>
                                    <dd>{{$users->instagram}}</dd>
                                @endif
                                @if(isset($users->youtube))
                                    <dt><i class="fa fa-youtube-play" aria-hidden="true"></i> YouTube</dt>
                                    <dd>{{$users->youtube}}</dd>
                                @endif
                                @if(isset($users->website))
                                    <dt><i class="fa fa-pinterest-p" aria-hidden="true"></i> Pinterest</dt>
                                    <dd>{{$users->pinterest}}</dd>
                                @endif
                                @if(isset($users->about))
                                    <dt><i class="fa fa-info-circle" aria-hidden="true"></i> About</dt>
                                    <dd>{{$users->about}}</dd>
                                @endif
                            </div>
                            <dt><i class="fa fa-address-card" aria-hidden="true"></i> Address</dt>
                            <dd>
                                @if(isset($users->address))
                                    {{ucfirst($users->address)}}<br>
                                @endif
                                @if(isset($users->city))
                                    {{ucfirst($users->city)}},
                                @endif
                                @if(isset($users->state))
                                    {{ucfirst($users->state)}},<br>
                                @endif
                                @if(isset($users->country))
                                    {{ucfirst($users->country)}} -
                                @endif
                                @if(isset($users->pin))
                                    {{ucfirst($users->pin)}}
                                @endif
                            </dd>
                            @if(count($user_dest) > 0)
                                <dt><i class="fas fa-map-marker-alt"></i> Destination of Service</dt>
                                <dd>
                                    @foreach($user_dest as $key=>$destination)
                                        {{$destination->destination_name}}({{$destination->country}})@if((count($user_dest))-1 != $key)
                                            ,
                                        @endif
                                    @endforeach
                                </dd>
                            @endif
                        </dl>

                    </div>
                </div>
                @if(count($departures)>0)
                <div class="col-lg-8 col-xl-8">
                    <div class="row">
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
                                <div class="col-md-6 mb-3" id="GridView">
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

                                        <div class="mb-21">
                                            <h4 class="card-title">
                                                @if(Auth::user())
                                                <a href="{{route('all_departure_details',$departure->id)}}">
                                                    {{$departure->title}}
                                                </a>
                                                @else
                                                <a href="{{route('login')}}" target="_blank">
                                                    {{$departure->title}}
                                                </a>
                                                @endif
                                            </h4>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="day-info- departureID">ID {{ $departure->dep_id }}</div>
                                                <div class="dep-model-action-btn">
                                                    @if($users->main_user_type == 2)
                                                        @if(Auth::user())
                                                        <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature" class="dep-model-action-btn"><i class="fa fa-eye"></i></a>
                                                        @else
                                                        <a href="{{route('login')}}" target="_blank"  title="View Deprature" class="tooltipbubble"><i class="fa fa-eye"></i></a>
                                                        @endif
                                                        @if(Auth::user())
                                                            @if(($hold < $date))
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm{{$departure->id}}" title="Hold Units"><i class="fas fa-pause"></i></a>   
                                                            @else
                                                             <a href="javascript:void(0);" title="This Departure Beyond Hold Date" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                            @endif
                                                        @endif
                                                        @if(Auth::user())
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif"
                                                           title="Book Units"><i class="far fa-calendar-check"></i>
                                                        </a>
                                                        @endif
                                                    @elseif($users->main_user_type == 0)
                                                    @if(Auth::user())
                                                        <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                    @else
                                                        <a href="{{route('login')}}" target="_blank" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                    @endif
                                                        @if(Auth::user())
                                                            @if(($hold < $date))
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm{{$departure->id}}" title="Hold Units"><i class="fas fa-pause"></i></a>   
                                                            @else
                                                             <a href="javascript:void(0);" title="This Departure Beyond Hold Date" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                            @endif
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif"
                                                               title="Book Units"><i class="far fa-calendar-check"></i></a>
                                                        @endif
                                                    @else
                                                        @if(Auth::user())
                                                            <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                        @else
                                                            <a href="{{route('login')}}" target="_blank" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                        @endif
                                                        @if(Auth::user())
                                                            @if(($hold < $date))
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm{{$departure->id}}" title="Hold Units"><i class="fas fa-pause"></i></a>   
                                                            @else
                                                             <a href="javascript:void(0);" title="This Departure Beyond Hold Date" disabled style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                            @endif
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif"
                                                               title="Book Units"><i class="far fa-calendar-check"></i></a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="day-info-">
                                            <strong class="d-block text-blue">{{date('d M, Y', strtotime($departure->start_date))}}</strong>
                                            @if($departure->no_of_nights == null || $departure->no_of_days == null)
                                            @else
                                                <span class="text-dark font-weight-bold">{{$departure->no_of_nights}} <span class="text-muted">Nights</span> / {{$departure->no_of_days}} <span class="text-muted">Days</span></span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex position-relative">
                                            @if($departure->from != null)
                                            <div>
                                                <p class="dept-from-text">{{$departure->from}}</p>
                                            </div>
                                            @endif
                                            @if($departure->from != null && $departure->ending_at != null)
                                            <div class="position-relative px-2">
                                                <strong style="color:#9f206a;">to</strong>
                                            </div>
                                            @endif
                                            @if($departure->ending_at != null)
                                            <div>
                                                <p class="dept-from-text">{{$departure->ending_at}}</p>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="bg-dept bg-per-pax">
                                            <div class="d-flex justify-content-between">
                                                <ul class="unit-set">
                                                    <!-- <li>{{$departure->total_seat}} <span>Total Units</span></li>-->
                                                    <li>{{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}} <span>Avl Units</span></li>
                                                </ul>
                                                <p class="price-set">
                                                    {{$departure->departure_first_price->currency_symbol}}  {{$departure->departure_first_price->price}}
                                                    <span>Per PAX</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="modal fade bd-example-modal-sm{{$departure->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content hold">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-white" id="mySmallModalLabel">Hold Units</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                                                <span class="text-black text-bold">{{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}</span>
                                                                                <input type="hidden" class="form-control" id="" name="available" value="{{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}" name="available" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="bh_units">
                                                                        <input type="hidden" class="form-control" name="available" value="0" readonly>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group mb-0">
                                                                                <h5 class="mb-0">Available Units :</h5>
                                                                                <span class="text-black text-bold">{{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}</span>
                                                                                <input type="hidden" class="form-control" id="" name="available" value="Over Holded:{{($departure['total_seat'])-($departure['hold_sum'] + $departure['book_sum'])}}" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="bh_units">
                                                                    <div class="form-group mb-0">
                                                                        <input type="hidden" name="id" value="{{$departure['id']}}">
                                                                        <input type="hidden" name="current_hours" value="{{date('H')}}">
                                                                        <input type="hidden" name="current_minutes" value="{{date('i')}}">
                                                                        <h5 class="mb-0">Hold Till</h5>
                                                                        <span class="text-black text-bold">{{date('d-M-Y | h:ia', strtotime("+{$new_time} hours +30 minutes"))}}</span>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hours" value="{{$departure['hold_duration']}}" readonly>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="hold_time" value="{{date('d-M-Y h:ia', strtotime("+{$new_time} hours +30 minutes"))}}" readonly>
                                                                        <input type="hidden" class="form-control" id="exampleFormControlSelect2" name="auto_release" value="{{date('Y-m-d H:i', strtotime("+{$new_time} hours +30 minutes"))}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-none">
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
                                                                        <label for="exampleFormControlInput1">Passenger Type</label>
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
                                                                        <label for="exampleFormControlInput1">Airport Transfers</label>
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
                                                        </div>
                                                        <div class="bookingMdodal">
                                                            <div class="row">
                                                                @foreach($departure['departure_price'] as $require)
                                                                    <input type="hidden" name="sairing[]" value="{{$require['sharing']}}">
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
                                                                                        <input class="form-control required_unit{{$departure['id']}}" id="require_hold_{{$require['id']}}" name="hold[]" placeholder="Enter required units">
                                                                                    </div>
                                                                                </div>
                                                                                <hr class="mt-1 mb-1">
                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <strong>Price</strong>
                                                                                    <div class="form-group mb-0">
                                                                                        <input type="hidden" class="form-control" id="price_{{$require['id']}}" name="price[]" value="{{$require['price']}}" style="border:none">
                                                                                        <label id="require_hold_price_{{$require['id']}}"></label>
                                                                                        <input type="hidden" id="currency_c_{{$require['id']}}" value="{{$require['currency_code']}} " name="currency">
                                                                                        <input type="hidden" id="currency_{{$require['id']}}" value="{{$require['currency_symbol']}} " name="currency_symbol">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                </div>
                            @endforeach

                        @endif
                    </div>
                    <div class="col-md-12">{{$departures->links()}}</div>
                </div>
                @endif
            </div>
        </div>
        @endsection
        @section('footerSection')
            <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <!-- <script src="{{asset('morris.min.js')}}"></script> -->
            <script src="{{asset('js/select2.full.min.js')}}"></script>
            <script src="{{asset('js/customJS/basic-details.js')}}"></script>
            @if(count($departures)>0)
            @foreach( $departures as $row )
                <!----Modal-->
                <div class="modal fade bd-example-modal-sm{{$row->id}}b" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-mb " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-white" id="mySmallModalLabel">Book Units</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-x">
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            @if(in_array(32, json_decode($columns)))
                                                            <div class="bh_units">
                                                                <span>Sharing</span>
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
                                        </div>
                                        <div class="row d-none">
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
                                                    <label for="exampleFormControlInput1">Passenger Type</label>
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
                                                    <label for="exampleFormControlInput1">Airport Transfers</label>
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
                                            <div class="row d-none">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <!-- <label for="exampleFormControlInput1">{{ucfirst($require->sharing)}}</label> -->
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
                                                    <input type="text" class="form-control" id="require_{{$require->id}}" name="lead_pasanger_name" placeholder="">
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
                        var price = ''
                        var sum = parseInt(required) * parseInt(price);
                        $("#total_hold_price{{$row->id}}").html(sum);
                    })
                </script>

                <script>
                    $('#book{{$row->id}}').keyup(function () {
                        var total = '{{($row->total_seat)-($row->hold_sum + $row->book_sum)}}';
                        if ($(this).val() > {{($row->total_seat)-($row->hold_sum + $row->book_sum)}}) {
                            $("#error{{$row->id}}").text('Value can not be greater than - ' + total + "");
                            //alert('Value can not be greater than {{($row->total_seat)-($row->hold_sum + $row->book_sum)}}');
                            $(this).val('');
                        } else {
                            $("#error{{$row->id}}").text('');
                        }
                    });
                    $("#book{{$row->id}}").keyup(function () {
                        var required = $("#book{{$row->id}}").val();
                        var price = ''
                        var sum = parseInt(required) * parseInt(price);

                        var required1 = $("#single_bookbook{{$row->id}}").val();
                        var price1 = ''
                        var sum1 = parseInt(requred1) * parseInt(price1);
                        var total = sum + sum1;

                        //alert(sum);
                        $("#msg").html('Total')
                        $("#required_pricebook{{$row->id}}").html(sum);

                        $("#total_pricebook{{$row->id}}").html(sum);
                    })
                    $("#single_bookbook{{$row->id}}").keyup(function () {
                        var required = $("#single_bookbook{{$row->id}}").val();
                        var price = ''
                        var sum = parseInt(required) * parseInt(price);
                        //alert(sum);
                        $("#single_pricebook{{$row->id}}").html(sum);

                        var required1 = $("#book{{$row->id}}").val();
                        var price1 = ''
                        var sum1 = parseInt(required1) * parseInt(price1);
                        var total = sum + sum1;
                        $("#total_pricebook{{$row->id}}").html(total);
                    })
                </script>
                <script>
                    <?php $departureCount = count($departure->departure_price); $j = 0; ?>
                    var total = 0;
                    @foreach($row->departure_price as $require)
                    $("#require_{{$require->id}}").keyup(function () {
                        var group_size = {{$require->group_size}};
                        var check = parseInt($("#require_{{$require->id}}").val());
                        var sum_no = check + 1;
                        if (check != '') {
                            if (group_size == 2) {
                                if ((check % 2) != 0) {
                                    $("#require_{{$require->id}}").val(sum_no);
                                }
                            }
                            if (group_size == 3) {
                                if ((check % 3) != 0) {
                                    $("#require_{{$require->id}}").val(sum_no1);
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
                            //$("#total_pricebook{{$row->id}}").html("Total Price "+ currency+  +total);
                        }
                        //$("#total_pricebook{{$row->id}}").html("Total Price "+ currency+  +total);
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
                        if (check != '') {
                            if (group_size == 2) {
                                if ((check % 2) != 0) {
                                    $("#require_hold_{{$require->id}}").val(sum_no);
                                }
                            }
                            if (group_size == 3) {
                                if ((check % 3) != 0) {
                                    $("#require_hold_{{$require->id}}").val(sum_no1);
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
            @endif
            <style>
                .socail, dd {
                    font-size: 13px !important;
                }
            </style>
@endsection
