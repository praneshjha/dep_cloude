@extends('layouts.app')
@section('tagSection')
    <title>Departure Cloud | My Departure Details</title>
@endsection
@section('headCssSection')
    <style type="text/css">
        .LastUpdated {
            margin: 5px 0;
            font-weight: 500;
            font-family: "Cerebri Sans,sans-serif";
            color: #681f4a;
        }

        .someThing {
            max-height: 430px;
            overflow-y: auto;
        }

        .someThing p {
            position: relative;
            font-size: 16px;
            font-weight: 700;
            color: #898989;
            background-color: #fbfdff;
            box-shadow: 0 3px 4px 0 rgb(22 70 147 / 70%);
            padding: 4px 12px;
            margin-bottom: 22px;
        }

        .someThing p:not(:last-child):before {
            content: '';
            position: absolute;
            width: 1px;
            height: 16px;
            background-color: #681f4a;
            left: 50%;
            transform: translateX(-50%);
            top: 100%;
        }

        .someThing p:not(:last-child):after {
            content: '';
            position: absolute;
            display: block;
            width: 8px;
            height: 8px;
            border: 1px solid #681f4a;
            border-left: 0;
            border-top: 0;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            top: calc(100% + 9px);
        }
    </style>
    <link href="{{asset('assets1/css/departure_details.css')}}" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Departure Detail</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @if(($sharing_book+$sharing_hold)>0)
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col">
                                            @include('graph.unit_graph')
                                        </div>
                                        <div class="col">
                                            @include('graph.room_graph')
                                        </div>
                                        @if(in_array(32, json_decode($columns)))
                                            <div class="col">
                                                @include('graph.sharing')
                                            </div>
                                        @endif
                                        @if(in_array(33, json_decode($columns)))
                                            <div class="col">
                                                @include('graph.flight_class_graph')
                                            </div>
                                        @endif
                                        @if(in_array(33, json_decode($columns)))
                                            <div class="col">
                                                @include('graph.passenger_graph')
                                            </div>
                                        @endif
                                        @if(in_array(35, json_decode($columns)))
                                            <div class="col">
                                                @include('graph.hotel_graph')
                                            </div>
                                        @endif
                                        @if(in_array(36, json_decode($columns)))
                                            <div class="col">
                                                @include('graph.transport_graph')
                                            </div>
                                        @endif
                                        <div class="col">
                                            @include('graph.airtransfer_graph')
                                        </div>
                                        @if(in_array(38, json_decode($columns)))
                                            <div class="col">
                                                @include('graph.meal_plan_graph')
                                            </div>
                                        @endif
                                        <div class="col">
                                            @include('graph.book_hold_graph')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-12 itinerary_linkandImg">
                                {{--@if($departure_types->id == 4 || $departure_types->id == 5 || $departure_types->id == 3)
                                    <h3 class="text-center mb-4">Itineraries</h3>
                                    <div class="someThing text-center">
                                        @foreach($default_iti0 as $odefault_iti)
                                            <p>{{$odefault_iti->departure_itinerary}}</p>
                                            <p>{{$odefault_iti->arrival_itinerary}}</p>
                                        @endforeach
                                        @foreach($default_iti1 as $rdefault_iti)
                                            <p>{{$rdefault_iti->departure_itinerary}}</p>
                                            <p>{{$rdefault_iti->arrival_itinerary}}</p>
                                        @endforeach
                                        @foreach($default_iti2 as $hotel_def)
                                            <p>{{$hotel_def->checkin}}</p>
                                            <p>{{$hotel_def->checkout}}</p>
                                        @endforeach
                                    </div>
                                    @else --}}


                                @if(isset($itinerary->pdf_file))
                                    <div class="tab-content pt-0">
                                        <embed src="{{ asset('agentitinerary') . '/' . $itinerary->pdf_file }}" type="application/pdf" width="100%" height="480px"/>
                                    </div>
                                @else
                                    @if(isset($itinerary->description))
                                        <div class="tab-content pt-0">
                                            <div class="tab-pane active show" id="product-1-item">
                                                <figure style="height: 480px;overflow: hidden;">
                                                    <img src="{{ asset('ScreenShot') . '/' . $itinerary->description }}" style="width:100%" class="img-fluid">
                                                </figure>
                                            </div>
                                        </div>
                                    @else
                                        @if($departure_types->id == 3 )
                                            <figure>
                                                <img src="{{asset('assets1/images/flight_h_iti-defualt.jpg')}}" style="width:100%" class="img-fit">
                                            </figure>
                                        @elseif($departure_types->id == 4)
                                            <figure>
                                                <img src="{{asset('assets1/images/hotel_iti-defualt.jpg')}}" style="width:100%" class="img-fit">
                                            </figure>
                                        @elseif($departure_types->id == 5)
                                            <figure>
                                                <img src="{{asset('assets1/images/flight_iti-defualt.jpg')}}" style="width:100%" class="img-fit">
                                            </figure>
                                        @endif
                                    @endif
                                @endif
                                {{--@endif--}}
                            </div>
                            <div class="col-xl-6 pl-md-0 departureDetail">
                                <div class="mt-3 mt-xl-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex justify-content-center cl_last_update align-items-center">
                                            <span>Last Updated :</span> &nbsp;
                                            <h5 class="m-0"> {{date('d M, Y | H:i A', strtotime($departure_details->last_updated_dep))}}</h5>
                                        </div>
                                        <div class="btn-group d-flex justify-content-end">
                                            @can('departure_edit', $permission)
                                                <a href="{{route('departure_edit',$departure_details->id)}}" class="btn btn-info btn-sm pull-right ml-2">Edit Departure</a>
                                            @endcan
                                        </div>
                                    </div>
                                    <h2 class="mb-1 text-capitalize" style="color: #093E8E;text-transform: capitalize;">{{$departure_details->title}}</h2>
                                    <p class="mb-0"><a href="" class="userprofileName"> <span>{{$departure_details->company_name}}</span></a>
                                        <span class="departureID font-16 mb-0 ml-1">({{$departure_details->dep_id}})</span>
                                    </p>
                                    @if($departure_details->ending_at != null)
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-map-marker-radius-outline mr-1"></i> <strong>Departure To : </strong> &nbsp <strong class="text-primary">{{$departure_details->ending_at}}</strong>
                                        </div>
                                    @endif
                                    @if($departure_details->from != null)
                                        @if($departure_types->id != 4)
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-map-marker-multiple-outline mr-1"></i>
                                                <strong>Ex : </strong> &nbsp
                                                <strong class="text-primary">{{$departure_details->from }}</strong>
                                            </div>
                                        @endif
                                    @endif
                                    @if($departure_details->return_to != "")
                                    @if($departure_types->id != 4)
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-map-marker-multiple-outline mr-1"></i><strong> Return To : </strong> &nbsp
                                            <strong class="text-uppercase text-pink">{{$departure_details->return_to }}</strong>
                                        </div>
                                    @endif
                                @endif
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-calendar-check mr-1"></i>
                                        <strong>Date : </strong> &nbsp
                                        <strong class="">{{date('d M, Y', strtotime($departure_details->start_date))}}</strong>
                                    </div>
                                    @if($departure_types->id != 5)
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-weather-night mr-1"></i>
                                            <p style="margin-bottom: 0;"><strong>{{$departure_details->no_of_nights}}</strong> Nights / <strong>{{$departure_details->no_of_days}}</strong> Days</p>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-wrap align-items-center">
                                        <i class="mdi mdi-map-marker-distance mr-1"></i>
                                        <p style="margin-bottom: 0;color: #002a68;padding-right: 4px;"><strong>Destination(s) Covered :</strong></p>
                                        <ul class="list-inline mb-0">
                                            @foreach($departure_destination as $row)
                                                <li class="list-inline-item">{{$row->dest_name}} <span class="text-dark">({{$row->country_name}})</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @if($departure_details->description != null)
                                        <div class="card descriptionBg mt-2">
                                            <div class="card-body p-2 badge-soft-pink">
                                                <h4 class="mb-1 mt-0">Description</h4>
                                                <p class="mb-0">{{$departure_details->description}}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    @if(count($inclusion) >0)
                                        <div class="card incluionCard mt-2 mb-0">
                                            <div class="card-body p-2 badge-soft-pink">
                                                <h4 class="mb-1 mt-0">Inclusions</h4>
                                                @foreach($inclusion as $row)
                                                    <p class="mb-0">
                                                        @if(isset($row->icon))
                                                            <img src="{{asset('inclusion-images/'.$row->icon)}}" style="width: 12px;">
                                                        @endif
                                                        <strong>{{$row->name}} :</strong> {{$row->description}}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($itinerary->title))
                                        <b>Itinerary Finder Link</b> <a href="{{$itinerary->title}}" style="text-decoration:none">{{$itinerary->title}}</a>
                                    @endif

                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                        @if(count($day_itinerary)>0)
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mt-3">Day Itineraries</h5>
                                </div>
                                @foreach($day_itinerary as $key => $row)
                                <div class="col-md-6">
                                    <div class="dc_day_itinerary">
                                        <h5 class="day_num">Day {{$row->day_number}}: {{$row->day_heading}}</h5>
                                        @if(count($row->day_destination) > 0)
                                        <div class="dc_day_destinations mt-2">
                                            @foreach($row->day_destination as $dest)
                                            <span class="destination">{{$dest->dest_name}}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                        @if($row->description != Null)
                                        <div class="description position-relative dc_hide mb-2 @if(count($row->day_destination) < 1) mt-2 @endif @if(str_word_count($row->description) <= 32) dc_show pb-0 @endif">{!! $row->description !!} @if(str_word_count($row->description) >= 33)<a href="javascript:void(0);" class="read_more">Read more...</a>@endif</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                        @if($departure_types->id != 4 || $departure_types->id != 2)
                            <div class="row">
                                @if(count($originating)>0)
                                    <div class="col-md-12">
                                        <h5 class="mt-3">Flight Details</h5>
                                    </div>
                                    @foreach($originating as $key=> $row)
                                        <div class="col-md-4">
                                            @if($key == 0)
                                                <div class="card-box ribbon-box flight-card">
                                                    <div class="ribbon-two ribbon-two-primary"><span><i class="mdi mdi-airplane-takeoff"></i>Originating</span></div>
                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                @if($row->logo != null)
                                                                    <img src="https://pullit-bucket.s3.us-west-2.amazonaws.com/airlines/{{$row->logo}}" alt="flight-img">
                                                                @else
                                                                    <img src="{{asset('assets1/images/flight/flight.png')}}" alt="flight-img">
                                                                @endif
                                                                <div>
                                                                    <h4>{{strtolower($row->flight_name)}} <span>({{$row->code}}-{{$row->flight_no}})</span><small>{{date('d M, Y', strtotime($row->flight_date))}}</small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small>{{$row->flight_dep_airport }}</small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small>{{$row->flight_arrival_airport}}</small>
                                                        </h4>
                                                    </div>

                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong>{{$row->flight_dep_time}}</strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong>{{$row->flight_arrival_time }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($key > 0)
                                                <div class="card-box ribbon-box flight-card">
                                                    <div class="ribbon-two ribbon-two-primary"><span><i class="mdi mdi-airplane-takeoff"></i>Originating: {{$key+1}}</span></div>
                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                @if( strcasecmp($row->flight_name, "air india") == 0)
                                                                    <img src="{{asset('assets1/images/flight/air-india.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "vistara airlines") == 0)
                                                                    <img src="{{asset('assets1/images/flight/vistara-airlines.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "fly dubai") == 0)
                                                                    <img src="{{asset('assets1/images/flight/fly-dubai.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "indigo") == 0)
                                                                    <img src="{{asset('assets1/images/flight/indigo.png')}}" alt="flight-img">
                                                                @else
                                                                    <img src="{{asset('assets1/images/flight/flight.png')}}" alt="flight-img">
                                                                @endif
                                                                <div>
                                                                    <h4>{{strtolower($row->flight_name)}} <span>({{$row->code}}-{{$row->flight_no}})</span><small>{{date('d M, Y', strtotime($row->flight_date))}}</small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small>{{$row->flight_dep_airport }}</small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small>{{$row->flight_arrival_airport}}</small>
                                                        </h4>
                                                    </div>

                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong>{{$row->flight_dep_time}}</strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong>{{$row->flight_arrival_time }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                                @if(count($returning)>0)
                                    @foreach($returning as $key=> $row)
                                        <div class="col-md-4">
                                            @if($key == 0)
                                                <div class="card-box ribbon-box flight-card">
                                                    <div class="ribbon-two ribbon-two-success"><span><i class="mdi mdi-airplane-takeoff"></i>Returning</span></div>
                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                @if( strcasecmp($row->flight_name, "air india") == 0)
                                                                    <img src="{{asset('assets1/images/flight/air-india.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "vistara airlines") == 0)
                                                                    <img src="{{asset('assets1/images/flight/vistara-airlines.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "fly dubai") == 0)
                                                                    <img src="{{asset('assets1/images/flight/fly-dubai.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "indigo") == 0)
                                                                    <img src="{{asset('assets1/images/flight/indigo.png')}}" alt="flight-img">
                                                                @else
                                                                    <img src="{{asset('assets1/images/flight/flight.png')}}" alt="flight-img">
                                                                @endif

                                                                <div>
                                                                    <h4>{{strtolower($row->flight_name)}} <span>({{$row->code}}-{{$row->flight_no}})</span><small>{{date('d M, Y', strtotime($row->flight_date))}}</small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small>{{$row->flight_dep_airport }}</small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small>{{$row->flight_arrival_airport}}</small>
                                                        </h4>
                                                    </div>
                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong>{{$row->flight_dep_time}}</strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong>{{$row->flight_arrival_time }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($key > 0)
                                                <div class="card-box ribbon-box flight-card">
                                                    <div class="ribbon-two ribbon-two-success"><span><i class="mdi mdi-airplane-takeoff"></i>Returning: {{$key+1}}</span></div>
                                                    <div class="flight-title">
                                                        <div class="flight-img d-flex flex-wrap align-items-center justify-content-between">
                                                            <div class="d-flex align-items-start">
                                                                @if( strcasecmp($row->flight_name, "air india") == 0)
                                                                    <img src="{{asset('assets1/images/flight/air-india.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "vistara airlines") == 0)
                                                                    <img src="{{asset('assets1/images/flight/vistara-airlines.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "fly dubai") == 0)
                                                                    <img src="{{asset('assets1/images/flight/fly-dubai.png')}}" alt="flight-img">
                                                                @elseif( strcasecmp($row->flight_name, "indigo") == 0)
                                                                    <img src="{{asset('assets1/images/flight/indigo.png')}}" alt="flight-img">
                                                                @else
                                                                    <img src="{{asset('assets1/images/flight/flight.png')}}" alt="flight-img">
                                                                @endif

                                                                <div>
                                                                    <h4>{{strtolower($row->flight_name)}} <span>({{$row->code}}-{{$row->flight_no}})</span><small>{{date('d M, Y', strtotime($row->flight_date))}}</small></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="flight-name mr-1">
                                                            <small>{{$row->flight_dep_airport }}</small>
                                                        </h4>
                                                        <h4 class="flight-name text-right ml-1">
                                                            <small>{{$row->flight_arrival_airport}}</small>
                                                        </h4>
                                                    </div>
                                                    <div class="flight-dep-arv">
                                                        <div class="d-flex justify-content-between position-relative">
                                                            <div>
                                                                <p class="flight-time">
                                                                    Depart
                                                                    <strong>{{$row->flight_dep_time}}</strong>
                                                                </p>
                                                            </div>
                                                            <div class="flight-path">
                                                                <span></span>
                                                                <svg _ngcontent-bpw-c19="" style="left: 50%;position: absolute;top: -4px;z-index: 1;transform: translateX(-50%);font-size:24px" fill="#c6cfd6" height="1em" viewBox="0 0 24 24"
                                                                     width="1em"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path _ngcontent-bpw-c19=""
                                                                          d="M9.442 13.886l-5.488-.491-1.358 2.632a.5.5 0 0 1-.436.27l-1.318.022a.5.5 0 0 1-.494-.616l.97-4.064-.975-4.07a.5.5 0 0 1 .479-.617l1.325-.021a.5.5 0 0 1 .453.274l1.415 2.787 5.44-.52-2.22-7.753A.8.8 0 0 1 8.006.7h.857a.8.8 0 0 1 .672.367l5.33 8.269h4.83a4 4 0 0 1 1.8.427l1.485.748a1.308 1.308 0 0 1-.003 2.338l-1.486.744a4 4 0 0 1-1.79.422h-4.858L9.534 22.33a.8.8 0 0 1-.674.37h-.858a.8.8 0 0 1-.77-1.018l2.21-7.795z"></path>
                                                                </svg>
                                                                <p></p>
                                                                <span></span>
                                                            </div>

                                                            <div>
                                                                <p class="flight-time text-right">
                                                                    Arrive
                                                                    <strong>{{$row->flight_arrival_time }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        @if($departure_types->id != 5)
                            <div class="row">
                                @if(count($hotels)>0)
                                    <div class="col-md-12">
                                        <h5 class="mt-3">Hotel Details</h5>
                                    </div>
                                    @foreach($hotels as $hotel)
                                        <div class="col-md-4 ribbon-box">
                                            <div class="price-card">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="title">
                                                        Hotel: <span class="text-info">{{ucfirst($hotel->name)}}</span>
                                                    </h6>
                                                    <h6 class="price mb-0">Room: {{$hotel->total_room}}</h6>
                                                </div>
                                                <hr class="mb-1 mt-1">
                                                <div class="d-flex2">
                                                    <div class="w-501 d-flex align-items-center justify-content-between">
                                                        <div class="transport">
                                                            <small>Destination</small>
                                                            <div class="star">
                                                                {{$hotel->destination_id}}
                                                            </div>

                                                        </div>
                                                        <div class="transport">
                                                            <small>Hotel Category</small>
                                                            <div class="star">
                                                                @if ( strcasecmp( $hotel->hotel_category, "5 star") == 0 )
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $hotel->hotel_category, "4 star") == 0 )
                                                                    @for ($i = 1; $i <= 4; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $hotel->hotel_category, "3 star") == 0 )
                                                                    @for ($i = 1; $i <= 3; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $hotel->hotel_category, "2 star") == 0 )
                                                                    @for ($i = 1; $i <= 2; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $hotel->hotel_category, "1 star") == 0 )
                                                                    @for ($i = 1; $i <= 1; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">
                                                                    @endfor
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <!--End Hotel Section -->
                            </div>
                        @endif
                        <div class="row">
                            @if(count($departure_prices)>0)
                                <div class="col-md-12">
                                    <h4 class=" mt-3">Pricing Details</h4>
                                </div>
                            @endif
                            @foreach($departure_prices as $price)
                                <div class="col-md-3">
                                    <div class="price-card pt-1" style="">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="title">
                                                    @if(in_array(32, json_decode($columns)))
                                                        @if($price->sharing != null)
                                                            <span class="d-block">Room Sharing: <span class="text-info">{{ucfirst($price->sharing)}}</span></span>
                                                        @endif
                                                    @endif
                                                    @if(in_array(33, json_decode($columns)))
                                                        @if($price->flight_class != null)
                                                            <span class="d-block">Flight Class: <span class="text-info">{{ucfirst($price->flight_class)}}</span></span>
                                                        @endif
                                                    @endif
                                                </h6>
                                            </div>
                                            <h4 class="price mb-0">
                                                <sup class="">{{$price->currency_symbol}}</sup>{{$price->price}}
                                            </h4>
                                        </div>
                                        <hr class="mb-1 mt-1">
                                        <div class="d-flex2">
                                            <div class="w-501 d-flex align-items-center justify-content-between flex-wrap">
                                                @if(in_array(35, json_decode($columns)))
                                                    @if($price->hotel_name != "")
                                                    <div class="transport">
                                                        <small>Hotel Name</small>
                                                        <div>
                                                            {{$price->hotel_name}}
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($price->hotel_type != null)
                                                        <div class="transport">
                                                            <small>Hotel Ctegory</small>
                                                            <div class="star">
                                                                @if ( strcasecmp( $price->hotel_type, "5 star") == 0 )
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $price->hotel_type, "4 star") == 0 )
                                                                    @for ($i = 1; $i <= 4; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $price->hotel_type, "3 star") == 0 )
                                                                    @for ($i = 1; $i <= 3; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $price->hotel_type, "2 star") == 0 )
                                                                    @for ($i = 1; $i <= 2; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">

                                                                    @endfor
                                                                @elseif ( strcasecmp( $price->hotel_type, "1 star") == 0 )
                                                                    @for ($i = 1; $i <= 1; $i++)
                                                                        <img src="{{asset('assets1/images/star.png')}}" alt="star">
                                                                    @endfor
                                                                @elseif($price->hotel_type == "Mix")
                                                                    {{$price->hotel_type}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array(33, json_decode($columns)))
                                                    @if($price->passenger != null)
                                                        <div class="transport">
                                                            <small>Age Bracket</small>
                                                            <div>
                                                                {{$price->passenger}}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array(36, json_decode($columns)))
                                                    @if($price->transport_type != null)
                                                        <div class="transport">
                                                            <small>Transport Type</small>
                                                            <span class="">{{$price->transport_type}}</span>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if($price->airport_transfers != null)
                                                    <div class="transport">
                                                        <small>Airport Transfer</small>
                                                        <span class="">{{$price->airport_transfers}}</span>
                                                    </div>
                                                @endif
                                                @if(in_array(38, json_decode($columns)))
                                                    @if($price->meal_type != null)
                                                        <div class="meal-plan">
                                                            <small>Meal Plan</small>
                                                            <span>{{$price->meal_type}}</span>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div class="transport">
                                                    <small>Minimum Pax</small>
                                                    <span>{{$price->group_size}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(count($payment_schedule) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mt-3">Payment Schedule</h4>
                                </div>
                                <div class="col-md-12">
                                    @foreach($payment_schedule as $key=>$payment)
                                        @if($key==0)
                                            <div class="payment-card-1 bg-danger text-white">
                                                Minimum Booking Price <strong class="p-amount text-white">{{$payment->percentage}}%</strong>
                                            </div>
                                        @endif
                                        @if($key>0)
                                            <div class="payment-card-1">
                                                {{date('d M, Y', strtotime($payment->date))}} |
                                                <strong class="p-amount">{{$payment->percentage}}%</strong>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if(count($cancelation_schedule) > 0)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h4 class="mt-0 mt-3">Cancelation Charge</h4>
                                </div>
                                <div class="col-md-12">
                                    @foreach($cancelation_schedule as $key=>$payment)
                                        @if($key==0)
                                            <div class="payment-card-1 bg-danger text-white">
                                                Minimum Cancelation Charge <strong class="p-amount text-white">{{$payment->percentage}}%</strong>
                                            </div>
                                        @endif
                                        @if($key>0)
                                            <div class="payment-card-1">
                                                {{date('d M, Y', strtotime($payment->date))}} |
                                                <strong class="p-amount">{{$payment->percentage}}%</strong>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row ">
                            @if($hold !=0)
                                <div class="container table-bordered">
                                    <h5 class="card-user_name  mb-2 mt-2">Hold Details <a href="{{route('all_departure_hold_history')}}" class="btn btn-info btn-sm float-right" style="">All Hold</a></h5>
                                    @if(session()->has('msg'))
                                        <div class="alert alert-success"> {{ session()->get('msg') }} </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Dep. Name</th>
                                                    <th>Buyer Name</th>
                                                    <th>Hold Date & time</th>
                                                    <th>Hold Units</th>
                                                    <th>Request More</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($hold_date as $key=>$holding)
                                                    <tr>
                                                        <td>{{$holding->departure_name}}</td>
                                                        <td>{{$holding->name}}</td>
                                                        <td>{{date('d M, Y h:i a', strtotime($holding->booked_value->created_at."+5 hours +30 minutes"))}}</td>
                                                        <td>{{($holding->booked_seat)-($holding->extra_hold)}}</td>
                                                        <td>{{$holding->extra_hold}}</td>
                                                        <td>{{$holding->currency->currency_symbol}}   {{$holding->price}}
                                                        </td>
                                                        <td>
                                                            <a data-id="{{ $holding->booked_value->unique_id }}" class="mr-2 disableDepartue" title="Release"><i class="fa fa-unlink"></i></a>||
                                                            <a href="{{url('/departures-hold-history-details/'.$holding->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                            {{$hold_date->links()}}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(count($departure_book)> 0 )
                                <div class="container table-bordered">
                                    <h5 class="mb-2 mt-2">Booking Details <a href="{{route('all_departure_booking_history')}}" class="btn btn-info btn-sm">All Booking</a></h5>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Dep. Name</th>
                                                    <th>Buyer Name</th>
                                                    <th>Booking Date & time</th>
                                                    <th>Booking Units</th>
                                                    <th>Total Price</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($book_date as $key=>$booking)
                                                    <tr>
                                                        <td>{{$booking->departure_name}}</td>
                                                        <td>{{$booking->name}}</td>
                                                        <td>{{date('d M, Y h:i a', strtotime($booking->booked_value->created_at."+5 hours +30 minutes"))}}</td>
                                                        <td>{{$booking->booked_seat}}
                                                        </td>
                                                        <td>{{$booking->currency->currency_symbol}}   {{$booking->price}}
                                                        </td>
                                                        <td>
                                                            @if($booking->booked_value->status == 1)
                                                                Confirm
                                                            @else
                                                                Cancel
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{url('/departures-booking-history-details/'.$booking->booked_value->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        @if(isset($departure_details->termspayment))
                            <div class="card incluionCard mt-2">
                                <div class="card-body p-2 badge-soft-pink">
                                    <h4 class="mb-1 mt-0">Terms</h4>
                                    {!! $departure_details->termspayment !!}
                                </div>
                            </div>
                        @endif
                    </div> <!-- end card-->

                </div> <!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- end container -->
    </div>

@endsection @section('footerSection')
    <style>
        .a2a_kit.a2a_kit_size_32.a2a_default_style {
            display: none !important;
        }

        div#product-1-item {
            pointer-events: none;
        }


    </style>
    <script type="text/javascript">
        $(".disableDepartue").click(function () {
            if (confirm("Are you sure you want to release this Departure?"))
                var id = $(this).data("id");
            var token = "{{ csrf_token() }}";
            if (id) {
                $.ajax({
                    url: '/hold/departure/release/' + id,
                    type: 'POST',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function (data) {
                        window.location.reload();
                    }
                });
            }
        });

        $(document).ready(function () {
            $(function () {
                $(this).bind("contextmenu", function (event) {
                    event.preventDefault();
                    //alert('Right click disable in this site!!')
                });
            });
        });
    </script>

@endsection