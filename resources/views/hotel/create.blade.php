@extends('layouts.app')
@section('tagSection')
    <title>Create Flight | Departure Cloud</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> -->
<link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Add Hotel</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Hotel</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card-box formCard">
                        <h4 class="mt-0 depnameHeading">{{$departure->title}}</h4>
                        @include('layouts/itinerary_menu')
                        <div class="tab-content">
                            <form role="form" id="HotelForm">
                                @csrf
                                <div class="row" id="originating">
                                    <div class="col-md-6 col-lg-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Destinations</label>
                                            <select class="form-control destinations" name="destinations" id="destinations">
                                                @foreach($dep_destinations as $destination)
                                                    <option value="{{$destination->id}}">{{$destination->dest_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-6">
                                        <div class="form-group">
                                            <label>Hotel Name</label>
                                            <input type="text" name="hotel_name" id="hotel_name" value="TBA" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-6">
                                        <div class="form-group">
                                            <label style="font-size:14px">Hotel Category</label>
                                            <select class="form-control categories" name="categories" id="categories">
                                                @foreach($hotel_categories as $hotel_category)
                                                    <option value="{{$hotel_category->id}}">{{$hotel_category->hotel_category}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-6">
                                        <div class="form-group">
                                            <label style="font-size:14px">Total Rooms</label>
                                            <input type="text" class="form-control" name="total_room" id="total_room" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-6">
                                        <button class="btn btn-primary active mt-2" type="button" id="store_form"><i class="fa fa-save"></i> Add</button>
                                        <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 20%; visibility: hidden;padding-top: 10px;">
                                        <span class="text-success d-block" id="mesegese"></span>

                                    </div>
                                </div>
                                <hr>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @if(count($departure_hotel)> 0 )
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Name</th>
                                        <th>Destination</th>
                                        <th>Hotel Category</th>
                                        <th>Total Room</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($departure_hotel as $key=>$row)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>{{$row->destinationName}}</td>
                                            <td>{{$row->hotel_category}}</td>
                                            <td>{{$row->total_room}}</td>
                                            <td>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm{{$row->id}}" title="Edit"><i class="fa fa-edit"></i></a> |
                                                <form id="posts-form-{{ $row->id }}" method="post" action="{{route('hotel_delete',$row->id)}}" style="display: none;">
                                                    @csrf

                                                    {{method_field('POST')}} <!-- posts query -->
                                                </form>
                                                <a href="" onclick="
                                                if (confirm('Are you sure, You want to delete?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('posts-form-{{ $row->id }}').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Delete">
                                                    <i class="fas fa-trash" style="    color: #69204b;"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <style type="text/css">
            .steps.clearfix {
                margin-top: 10px
            }

            span.step-icon {
                padding-top: 10px
            }

            .steps.clearfix > ul > li {
                display: inline-flex;
                margin-right: 20px
            }

            .box.box-primary {
                border-top-color: #3c8dbc;
                background: 0 0
            }

            .radio {
                display: inline
            }

            .radio > label {
                margin-right: 30px
            }

            .validationError {
                color: #ff0c0c;
            }

            .button-submit {
                margin-top: 20px;
                margin-bottom: 20px
            }

            .autocomplete-items {
                z-index: 999;
                position: absolute;
                background: #fff;
                width: 94%;
            }

            .dest_badge {
                margin-right: 7px;
                margin-top: 7px;
                padding: 5px 10px;
                font-weight: 500;
            }

            .dest_badge i {
                margin-left: 3px;
                color: #fff;
            }

            ul.search-list > li {
                display: inherit;
                border-bottom: 1px solid;
                margin: 1px;
                margin-left: -40px;
                padding: 6px;
                box-sizing: border-box;
                color: #444;
                white-space: nowrap;
                direction: ltr;
                vertical-align: middle;
                border-color: #d3d3d3;
            }

            .steps.clearfix.text-center {
                margin-top: 20px;
                padding-bottom: 20px;
            }

            .ui-datepicker-buttonpane.ui-widget-content {
                display: none !important;
            }

            div#ui-datepicker-div {
                width: 18% !important;
            }

            .itinerary_add span#select2-itinerary-container {
                padding: 0px 0px 0px 0px;
                line-height: 1.6;
            }

            .select2-search__field {
                padding-left: 5px !important
            }
            .navbar-custom .topnav-menu .nav-link{
                padding: 28 15px;
            }
        </style>
        @endsection
        @section('footerSection')
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <!-- <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script> -->
            <script src="{{asset('js/select2.full.min.js')}}"></script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#store_form').click(function (e) {
                        e.preventDefault();
                        //alert('hello');
                        jQuery('#gif').show();

                        jQuery('#gif').css('visibility', 'visible');
                        jQuery('#store_form').html('Please wait...')
                        jQuery('#store_form').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('HotelForm'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "/departure/hotel/store/{{request()->route('id')}}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                // window.location = data.url;
                                location.reload();
                            },
                            errors: function () {
                                jQuery('#gif').hide();
                                jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                            }
                        });
                    });
                });
            </script>
            <script>
                jQuery(function () {
                    var jQueryj = jQuery.noConflict();
                    //jQuery("#start_date12").datepicker();
                    //   changeMonth: true,
                    //   changeYear: true,
                    //   showButtonPanel: true,
                    //   dateFormat: 'dd-M-yy',
                    //   minDate: 0,
                    // });
                });
                /*jQuery(document).ready(function(){

                 jQuery('#start_date12').datetimepicker();
                });*/


            </script>
            @foreach($departure_hotel as $key=>$value)
                <div class="modal fade bd-example-modal-sm{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content hold">
                            <div class="modal-header">
                                <h5 class="modal-title text-white" id="mySmallModalLabel">Edit Hotel</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                            <form role="form" id="PricingForm_{{$key}}" style="background-color: #fdfdfd;" class="p-1">
                                @csrf
                                <input type="hidden" name="hotel_id" value="{{$value->id}}">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Destination<span id="sairing_error" class="text-danger">*</span></label>
                                                <select class="form-control" name="destinations" id="destinations_{{$key}}">
                                                    <option value="">Select Destination</option>
                                                    @foreach($dep_destinations as $row)
                                                        <option value="{{$row->id}}" @if($row->id == $value->destination_id) selected @endif>{{$row->dest_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-danger" id="sairing_error_{{$key}}"></span>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label>Hotel Name</label>
                                                <input type="text" name="hotel_name" id="hotel_name" value="{{$value->name}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Hotel Category</label>
                                                <select class="form-control categories" name="categories" id="categories">
                                                    @foreach($hotel_categories as $hotel_category)
                                                        <option value="{{$hotel_category->id}}" @if($hotel_category->id == $value->hotel_category) selected @endif>{{$hotel_category->hotel_category}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Total Rooms</label>
                                                <input type="text" class="form-control" name="total_room" value="{{$value->total_room}}" id="total_room" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <img src="{{ asset('images/loader.gif') }}" id="gif_{{$key}}" style="width: 30px; visibility: hidden;margin-bottom:16px;">
                                            <span class="text-success" id="mesegese_{{$key}}" style="margin-left: 10px;margin-bottom:16px;"></span>
                                            <button class="btn btn-primary active" type="button" id="store_form_{{$key}}" style="margin-bottom:16px;"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function () {
                        $('#store_form_{{$key}}').click(function (e) {
                            e.preventDefault();
                            $('#gif_{{$key}}').show();
                            var title_{{$key}} = $('#saring_validate_{{$key}}').val();
                            if (title_{{$key}} == "") {
                                $("span#sairing_error_{{$key}}").html('This field is required!');
                                $("select#saring_validate_{{$key}}").focus();
                                return false;
                            } else {
                                $("span#sairing_error_{{$key}}").hide();
                            }
                            var price_{{$key}} = $('#price_validate_{{$key}}').val();
                            if (price_{{$key}} == "") {
                                $("span#price_error_msg_{{$key}}").html('This field is required!');
                                $("input#price_validate_{{$key}}").focus();
                                return false;
                            } else {
                                $("span#price_error_msg_{{$key}}").hide();
                            }

                            $('#gif_{{$key}}').css('visibility', 'visible');
                            var formData = new FormData(document.getElementById('PricingForm_{{$key}}'));
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'POST',
                                url: "{{ route('hotel_update',request()->route('id')) }}",
                                data: $('#PricingForm_{{$key}}').serialize(),
                                success: function (data) {
                                    $('#gif_{{$key}}').hide();
                                    $('#mesegese_{{$key}}').html("<span class='sussecmsg'>Success!</span>");
                                    //window.location = data.url;
                                    location.reload();
                                    // window.location.href = "{{ route('pdf_itinerary',request()->route('id')) }}";
                                },
                                errors: function () {
                                    $('#gif_{{$key}}').hide();
                                    $('#mesegese_{{$key}}').html("<span class='sussecmsg'>Something went wrong!</span>");
                                }

                            });
                        });
                    });
                    var price = document.querySelector('#price_validate_{{$key}}');
                    price.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = pricies;
                    }

                    var group_size = document.querySelector('#group_size_{{$key}}');
                    group_size.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = group;
                    }
                </script>
    @endforeach
@endsection