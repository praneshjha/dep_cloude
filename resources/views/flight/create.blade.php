@extends('layouts.app')
@section('tagSection')
    <title>Create Flight | Departure Cloud</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> -->
<style type="text/css">span#select2-return_flight_00-container {width: 164px;}</style>
<link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Add Flight</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Flight</li>
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
                        <form role="form" id="DeparturForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 d-flex align-items-center">
                                    <h3 class="m-0" style="padding-top:3px;">Flight Details:</h3>
                                    <div class="">
                                        <div class="form-check mb-0 ml-2 form-check-success col-md-6">
                                            <input class="form-check-input" value="O0" type="radio" name="oneway" id="OneWay" checked>
                                            <label class="form-check-label" for="OneWay">One Way</label>
                                        </div>
                                        <div class="form-check mb-0 ml-2 form-check-success col-md-6">
                                            <input class="form-check-input" value="11" type="radio" name="oneway" id="twoway">
                                            <label class="form-check-label" for="twoway">Return</label>
                                        </div>
                                        <div class="d-none align-items-center" id="BothTwowayOneway">
                                            <div class="form-check mb-0 ml-2 form-check-success col-md-6">
                                                <input class="form-check-input" value="O" type="radio" name="type" id="buyer" checked>
                                                <label class="form-check-label" for="buyer">Originating Flight</label>
                                            </div>
                                            <div class="form-check mb-0 ml-2 form-check-success col-md-6">
                                                <input class="form-check-input" value="R" type="radio" name="type" id="supplier">
                                                <label class="form-check-label" for="supplier">Returning Flight</label>
                                            </div>
                                            <!--<h3>Flight Details: <label for="male"><h4>Originating Flight</h4></label> &nbsp;
                                                <input type="radio" id="buyer" name="type" value="O" checked>&nbsp;&nbsp; &nbsp;
                                                <label for="female">&nbsp; <h4>Returning Flight</h4></label>
                                                <input type="radio" id="supplier" name="type" value="R"></h3>
                                            <hr style="border-bottom: 2px solid #777">-->
                                        </div>
                                    </div>
                                </div><!-- OneWay And Return End-->
                            </div>
                            <div class="container2 mt-3" id="originating">
                                <div class="row">
                                    <div class="col-md-2 ml-auto text-right">
                                        <a href="javascript:void(0);" class="add_button btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add More</a>
                                    </div>
                                </div>
                                <div class="wrappers">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>Originating Flight</h4>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label>Airline</label>
                                                <input type="text" name="flight_name[]" id="origin_flight" class="form-control">
                                            </div>
                                        </div> -->
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Airline</label>
                                                <select class="form-control airline_0" name="flight_name[]" id="origin_flight">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label>Airline Code</label>
                                                <input type="text" name="code[]" id="code_0" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Flight Code</label>
                                                <input type="text" class="form-control" name="flight_no[]" id="o_flight_no">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Departure Date</label>
                                                <input type="text" class="form-control" name="flight_date[]" id="o_flight_date" value="{{date('d M Y', strtotime($departure->start_date))}}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Departure Time</label>
                                                <input type="text" class="form-control" name="flight_dep_time[]" id="o_flight_time" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Arrival Date</label>
                                                <input type="text" class="form-control" name="flight_arrival_date[]" id="o_flight_arrival_date" value="{{date('d M Y', strtotime($departure->end_date))}}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Arrival Time</label>
                                                <input type="text" class="form-control" name="flight_arrival_time[]" id="o_flight_arrival_time" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Departing Airport</label>
                                                <select class="form-control airport" name="flight_dep_airport[]" id="o_flight_dep_airport">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Arriving Airport</label>
                                                <select class="form-control airport1" name="flight_arrival_airport[]" id="flight_arrival_airport">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Baggage</label>
                                                <input type="text" class="form-control" name="baggage[]" id="bagage" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                </div>

                            </div>
                            <div class="container2 mt-3" id="returning" style="display: none">
                                <div class="row">
                                    <div class="col-md-2 ml-auto text-right">
                                        <a href="javascript:void(0);" class="add_button1 btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add More</a>
                                    </div>
                                </div>
                                <div class="wrappers1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>Returning Flight </h4>
                                        <!-- <span class="text-danger mr-3" style="font-size:14px; font-weight:bold;"> Same as originating flight(Click to copy originat flight details)<input type="checkbox" id="filladdress" name="filladdress"/></span> -->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label for="return_flight_00">Airline</label>
                                                <select class="form-control airline_00" name="r_flight_name[]" id="return_flight_00" style="width:100%">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label>Airline Code</label>
                                                <input type="text" class="form-control code_00" name="r_code[]" id="code_00">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Flight Code</label>
                                                <input type="text" class="form-control" name="r_flight_no[]" id="r_flight_no">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Departure Date</label>
                                                <input type="text" class="form-control" name="r_flight_date[]" id="r_flight_date" value="{{date('d M Y', strtotime($departure->end_date))}}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Departure Time</label>
                                                <input type="text" class="form-control" name="r_flight_dep_time[]" id="r_flight_dep_time" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Arrival Date</label>
                                                <input type="text" class="form-control" name="r_flight_arrival_date[]" id="r_flight_arrival_date" value="{{date('d M Y', strtotime($departure->end_date))}}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Arrival Time</label>
                                                <input type="text" class="form-control" name="r_flight_arrival_time[]" id="r_flight_arrival_time" autocomplete="off">
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3 col-lg-3 col-sm-6">
                                           <div class="form-group">
                                              <label style="font-size:14px">Departing Airport</label>
                                              <select class="form-control airport" name="r_flight_dep_airport[]" id="r_flight_dep_airport">
                                              </select>
                                           </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                           <div class="form-group">
                                              <label style="font-size:14px">Arriving Airport</label>
                                              <select class="form-control airport" name="r_flight_arrival_airport[]" id="r_flight_arrival_airport">
                                              </select>
                                           </div>
                                        </div> -->
                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Departing Airport</label> <br>
                                                <select class="form-control airport" name="r_flight_dep_airport[]" id="r_flight_dep_airport" style="width:100%">
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Arriving Airport</label><br>
                                                <select class="form-control airport1" name="r_flight_arrival_airport[]" id="r_flight_arrival_airport" style="width:100%">
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label style="font-size:14px">Baggage</label>
                                                <input type="text" class="form-control" name="baggage_arriving[]" id="baggage_arriving" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="row"></div>
                                <div class="col-md-12 ml-auto text-right">
                                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                    <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-primary active" type="button" id="store_form_next"><i class="fa fa-save"></i> Save & Next</button>
                                    <span class="text-success d-block" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            .form-check.mb-0.ml-2.form-check-success.col-md-6 {
                display: inline;
            }

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
        </style>
        @endsection
        @section('footerSection')
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <!-- <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script> -->
            <script src="{{asset('js/select2.full.min.js')}}"></script>
            <script type="text/javascript">
                $('#twoway').click(function () {
                    $("#BothTwowayOneway").removeClass('d-none');
                    $("#BothTwowayOneway").addClass('d-flex');
                    $('#buyer').prop('checked', true);
                    $('#supplier').prop('checked', false);
                });
                $('#OneWay').click(function () {
                    $("#BothTwowayOneway").addClass('d-none');
                    $("#BothTwowayOneway").removeClass('d-flex');
                    $('#supplier').prop('checked', false);
                });
            </script>
            <script>
                $('.airline_0').on("select2:selecting", function (e) {
                    console.log(e.params.args.data.id);
                    console.log(airlines_data);
                    for (let air of airlines_data) {
                        if (air.name == e.params.args.data.id) {
                            $('#code_0').val(e.params.args.data.code);
                        }
                    }
                });
                var airlines_data = [];
                $('.airline_0').select2({
                    placeholder: 'Airline',
                    tags: true,
                    ajax: {
                        url: "/get-airline-ajax",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            airlines_data = data;
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.name,
                                        code: item.airline_code_2
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            </script>
            <script>
                $('.airport').select2({
                    placeholder: 'Departing Airport',
                    tags: true,
                    ajax: {
                        url: "/destination-pois-ajax",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.name
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            </script>
            <script>
                $('.airline_00').on("select2:selecting", function (e) {
                    console.log(e.params.args.data.id);
                    console.log(airlines_data);
                    for (let air of airlines_data) {
                        if (air.name == e.params.args.data.id) {
                            $('#code_00').val(e.params.args.data.code);
                        }
                    }
                });
                var airlines_data = [];
                $('.airline_00').select2({
                    placeholder: 'Airline',
                    tags: true,
                    ajax: {
                        url: "/get-airline-ajax",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            airlines_data = data;
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.name,
                                        code: item.airline_code_2
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            </script>
            <script>
                $('.airport1').select2({
                    placeholder: 'Arriving Airport',
                    tags: true,
                    ajax: {
                        url: "/destination-pois-ajax",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.name
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            </script>

            <script>
                $(document).ready(function () {

                    $('#o_flight_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd M yy',
                        minDate: 0,
                    });
                    $('#o_flight_arrival_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd M yy',
                        minDate: 0,
                    });
                    $('#r_flight_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd M yy',
                        minDate: 0,
                    });
                    $('#r_flight_arrival_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd M yy',
                        minDate: 0,
                    });
                    //timepicker
                    $('#o_flight_time').datetimepicker({
                        format: 'HH:mm',
                    });
                    $('#o_flight_arrival_time').datetimepicker({
                        format: 'HH:mm',
                    });
                    $('#r_flight_dep_time').datetimepicker({
                        format: 'HH:mm',
                    });
                    $('#r_flight_arrival_time').datetimepicker({
                        format: 'HH:mm',
                    });
                });
            </script>


            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#store_form').click(function (e) {
                        e.preventDefault();
                        //alert('hello');
                        jQuery('#gif').show();

                        jQuery('#gif').css('visibility', 'visible');
                        jQuery('#store_form').html('Please wait...')
                        jQuery('#store_form').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('DeparturForm'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "/departure/flight/store/{{request()->route('id')}}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                window.location.reload();
                                //location.reload();
                            },
                            errors: function () {
                                jQuery('#gif').hide();
                                jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                            }

                        });
                    });
                });

                jQuery(document).ready(function () {
                    jQuery('#store_form_next').click(function (e) {
                        e.preventDefault();
                        //alert('hello');
                        jQuery('#gif').show();

                        jQuery('#gif').css('visibility', 'visible');
                        jQuery('#store_form_next').html('Please wait...')
                        jQuery('#store_form_next').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('DeparturForm'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "/departure/flight/store/{{request()->route('id')}}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                window.location = data.url;
                                //location.reload();
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
                    //   dateFormat: 'dd M, yy',
                    //   minDate: 0,
                    // });
                });
                /*jQuery(document).ready(function(){

                 jQuery('#start_date12').datetimepicker();
                });*/


            </script>

            <script>
                jQuery(document).ready(function () {
                    // var radioinput = jQuery("#username-field input[type='radio']")
                    jQuery("input[type='radio']").change(function () {
                        //   alert("radioinput");
                        if (jQuery(this).val() == 'R') {
                            jQuery("#returning").show();
                            jQuery("#originating").hide();
                        } else {
                            jQuery("#returning").hide();
                            jQuery("#originating").show();
                        }
                    });
                });
            </script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var maxFields = 15; //Input fields increment limitation
                    var addButtons = jQuery('.add_button'); //Add button selector
                    var wrappers = jQuery('.wrappers'); //Input field wrapper

                    var x = 1;

                    jQuery(addButtons).click(function () {
                        if (x < maxFields) {
                            x++;
                            jQuery(wrappers).append('<div class="row rowes position-relative"><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Airline</label><select class="form-control airline_' + x + '" name="flight_name[]" id="origin_flight_' + x + '"></select></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group code_' + x + '"><label>Airline Code</label> <input type="text" name="code[]" id="code_' + x + '" class="form-control code_' + x + '"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Flight Code</label><input type="text" class="form-control" name="flight_no[]" id="o_flight_no" ></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Date</label> <input type="text" class="form-control o_flight_date" name="flight_date[]" id="" autocomplete="off"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Time</label><input type="text" class="form-control o_flight_time" name="flight_dep_time[]" id="o_flight_time" autocomplete="off"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Date</label> <input type="text" class="form-control o_flight_arrival_date" name="flight_arrival_date[]" id="" autocomplete="off"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Time</label> <input type="text" class="form-control o_flight_time" name="flight_arrival_time[]" id="o_flight_arrival_time" autocomplete="off"></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Departing Airport</label><select class="form-control airport3" name="flight_dep_airport[]" id="o_flight_dep_airport_' + x + '"></select></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Arriving Airport</label><select class="form-control airport4" name="flight_arrival_airport[]" id="flight_arrival_airport_' + x + '"></select></div></div><div class="col-md-4 col-lg-4 col-sm-6"><div class="form-group"><label style="font-size:14px">Baggage</label><input type="text" class="form-control" name="baggage[]" id="bagage" autocomplete="off"></div></div><div class="col-md-1 col-lg-1 col-sm-6" style="margin-top:20px;"><a href="javascript:void(0);" class="remove_button"><img class="ImgWidth" src="{{ asset("images/remove-icon.png")}}"/></a></div><hr class="col-12"></div></div>');

                            var airlines_datas = [];
                            jQuery('.airline_' + x).select2({
                                placeholder: 'Airline',
                                tags: true,
                                ajax: {
                                    url: "/get-airline-ajax",
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        airlines_datas = data;
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name,
                                                    code: item.airline_code_2
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                }
                            }).on("select2:selecting", function (e) {
                                console.log(e.params.args.data.id);
                                console.log(airlines_datas);
                                for (let air of airlines_datas) {
                                    if (air.name == e.params.args.data.id) {
                                        jQuery('.code_' + x).val(e.params.args.data.code);
                                    }
                                }
                            });
                            jQuery('.o_flight_date').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                dateFormat: 'dd M yy',
                                minDate: 0,
                            });
                            jQuery('.o_flight_arrival_date').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                dateFormat: 'dd M yy',
                                minDate: 0,
                            });
                            //timepicker
                            jQuery('.o_flight_time').datetimepicker({
                                format: 'HH:mm',
                            });
                            jQuery('.o_flight_time').datetimepicker({
                                format: 'HH:mm',
                            });

                            jQuery('.airport3').select2({
                                placeholder: 'Departing Airport',
                                tags: true,
                                ajax: {
                                    url: "/destination-pois-ajax",
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                }
                            });
                            jQuery('.airport4').select2({
                                placeholder: 'Arriving Airport',
                                tags: true,
                                ajax: {
                                    url: "/destination-pois-ajax",
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                }
                            });


                        }
                    });
                    jQuery(wrappers).on('click', '.remove_button', function (e) {
                        e.preventDefault();
                        jQuery(".rowes").last().remove();

                        x--;
                    });
                });

            </script>
            <script type="text/javascript">

                jQuery(document).ready(function () {
                    var maxFields = 15; //Input fields increment limitation
                    var addButtons = jQuery('.add_button1'); //Add button selector
                    var wrappers = jQuery('.wrappers1'); //Input field wrapper

                    var x = 1;

                    jQuery(addButtons).click(function () {
                        if (x < maxFields) {
                            x++;
                            jQuery(wrappers).append('<div class="row rowes1"><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label>Airline</label><select class="form-control airline_0' + x + '" name="r_flight_name[]" id="return_flight_0' + x + '"></select></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label>Airline Code</label><input type="text" name="r_code[]" id="code_0' + x + '" class="form-control code_0' + x + '"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Flight Code</label><input type="text" class="form-control" name="r_flight_no[]" id="r_flight_no"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Date</label> <input type="text" class="form-control r_flight_date" name=r_flight_date[]" id="" autocomplete="off"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Time</label> <input type="text" class="form-control r_flight_time" name="r_flight_dep_time[]" id="r_flight_dep_time" autocomplete="off"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Date</label> <input type="text" class="form-control r_flight_arrival_date" name=r_flight_arrival_date[]" id="" autocomplete="off"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Time</label> <input type="text" class="form-control r_flight_time" name="r_flight_arrival_time[]" id="r_flight_arrival_time" autocomplete="off"></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Departing Airport</label><select class="form-control airport3" name="r_flight_dep_airport[]" id="r_flight_dep_airport_' + x + '"></select></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Arriving Airport</label> <select class="form-control airport4" name="r_flight_arrival_airport[]" id="r_flight_arrival_airport_' + x + '"></select></div></div><div class="col-md-4 col-lg-4 col-sm-6"><div class="form-group"><label style="font-size:14px">Baggage</label><input type="text" class="form-control" name="baggage_arriving[]" id="baggage_arriving" autocomplete="off"></div></div><div class="col-md-1 col-lg-1 col-sm-6" style="margin-top:20px;"><a href="javascript:void(0);" class="remove_button1"><img class="ImgWidth" src="{{ asset("images/remove-icon.png")}}"/></a></div><hr class="col-12"></div></div>');


                            var airlines_data = [];
                            jQuery('.airline_0' + x).select2({
                                placeholder: 'Airline',
                                tags: true,
                                ajax: {
                                    url: "/get-airline-ajax",
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        airlines_data = data;
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name,
                                                    code: item.airline_code_2
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                }
                            }).on("select2:selecting", function (e) {
                                console.log(e.params.args.data.id);
                                console.log(airlines_data);
                                for (let air of airlines_data) {
                                    if (air.name == e.params.args.data.id) {
                                        jQuery('.code_0' + x).val(e.params.args.data.code);
                                    }
                                }
                            });

                            jQuery('.r_flight_date').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                dateFormat: 'dd M yy',
                                minDate: 0,
                            });
                            jQuery('.r_flight_arrival_date').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                dateFormat: 'dd M yy',
                                minDate: 0,
                            });
                            //timepicker
                            jQuery('.r_flight_time').datetimepicker({
                                format: 'HH:mm',
                            });

                            jQuery('.airport3').select2({
                                placeholder: 'Departing Airport',
                                tags: true,
                                ajax: {
                                    url: "/destination-pois-ajax",
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                }
                            });
                            jQuery('.airport4').select2({
                                placeholder: 'Arriving Airport',
                                tags: true,
                                ajax: {
                                    url: "/destination-pois-ajax",
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                }
                            });

                        }
                    });
                    jQuery(wrappers).on('click', '.remove_button1', function (e) {
                        e.preventDefault();
                        jQuery(".rowes1").last().remove();

                        x--;
                    });
                });
            </script>
@endsection