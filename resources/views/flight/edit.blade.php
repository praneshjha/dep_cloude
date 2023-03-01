@extends('layouts.app')
@section('tagSection')
    <title>Edit Flight | Departure Cloud</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
<style type="text/css">span#select2-return_flight_042-container {width: 158px;}.form-check.mb-0.ml-2.form-check-success.col-md-6 {display: inline;}
</style>
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Edit Flight</h4>
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
                                        </div>
                                    </div>
                                </div><!-- OneWay And Return End-->
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-12 d-flex align-items-center">
                                    <h3 class="m-0" style="padding-top:3px;">Flight Details:</h3>
                                    <div class="form-check mb-0 ml-2 form-check-success">
                                        <input class="form-check-input" value="O" type="radio" name="type" id="buyer" checked>
                                        <label class="form-check-label" for="buyer">Originating Flight</label>
                                    </div>
                                    <div class="form-check mb-0 ml-2 form-check-success">
                                        <input class="form-check-input" value="R" type="radio" name="type" id="supplier">
                                        <label class="form-check-label" for="supplier">Returning Flight</label>
                                    </div>

                                </div>
                            </div> -->
                            <div class="col-12" id="originating">
                                <div class="row">
                                    <div class="col-md-2 ml-auto text-right mb-2">
                                        <a href="javascript:void(0);" class="add_button btn btn-primary btn-sm" title="Add More"><i class="fas fa-plus"></i> Add More</a>
                                    </div>
                                </div>
                                <div class="wrappers">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>Originating Flight</h4>
                                    </div>
                                    @foreach($oflights as $key=> $row)
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Airline</label>
                                                    <select class="form-control airline_{{$row->id}}" name="flight_name[]" id="origin_flight_{{$row->id}}" >
                                                        <option value="{{$row->flight_name}}" selected>{{$row->flight_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label>Airline Code</label>
                                                    <input type="text" name="code[]" id="code_{{$row->id}}" class="form-control code_{{$row->id}}" value="{{$row->code}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Flight Code</label>
                                                    <input type="text" class="form-control" name="flight_no[]" id="o_flight_no" value="{{$row->flight_no}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Departure Date</label>
                                                    <input type="text" class="form-control" name="flight_date[]" id="o_flight_date{{$key}}" value="{{date('d M Y', strtotime($row->flight_date))}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Departure Time</label>
                                                    <input type="text" class="form-control" name="flight_dep_time[]" id="o_flight_time{{$key}}" value="{{$row->flight_dep_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Arrival Date</label>
                                                    <input type="text" class="form-control" name="flight_arrival_date[]" id="o_flight_arrival_date{{$key}}" value="{{date('d M Y', strtotime($row->flight_arrival_date))}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Arrival Time</label>
                                                    <input type="text" class="form-control" name="flight_arrival_time[]" id="o_flight_arrival_time{{$key}}" value="{{$row->flight_arrival_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Departing Airport</label>
                                                    <select class="form-control airport1" name="flight_dep_airport[]" id="o_flight_dep_airport{{$row->id}}">
                                                        <option selected="selected">
                                                            {{$row->flight_dep_airport}}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Arriving Airport</label>
                                                    <select class="form-control airport1" name="flight_arrival_airport[]" id="o_flight_arrival_airport{{$row->id}}">
                                                        <option selected="selected">
                                                            {{$row->flight_arrival_airport}}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-sm-6">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label style="font-size:14px">Baggage</label>
                                                            <input type="text" class="form-control" name="baggage[]" id="bagage" value="{{$row->baggage}}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-end mb-3" style="font-size: 2em;">
                                                        <a class="deleteFlightOrigin" data-id="{{ $row->id }}" style="cursor: pointer; color: #dc0b0b;margin-left: 5px;">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12" id="returning" style="display: none">
                                <div class="row mb-2">
                                    <div class="col-md-2 ml-auto text-right">
                                        <a href="javascript:void(0);" class="add_button1 btn btn-primary btn-sm" title="Add More"><i class="fas fa-plus"></i> Add More</a>
                                    </div>
                                </div>
                                <div class="wrappers1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4>Returning Flight</h4>
                                    </div>
                                    @foreach($rflights as $key=>$row)
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label>Airline</label>
                                                    <select class="form-control airline_0{{$row->id}}" name="r_flight_name[]" id="return_flight_0{{$row->id}}" >
                                                        <option value="{{$row->flight_name}}" selected>{{$row->flight_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label>Airline Code</label>
                                                    <input type="text" class="form-control code_0{{$row->id}}" name="r_code[]" id="code_0{{$row->id}}" value="{{$row->code}}">
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Flight Code</label>
                                                    <input type="text" class="form-control" name="r_flight_no[]" id="r_flight_no" value="{{$row->flight_no}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Departure Date</label>
                                                    <input type="text" class="form-control" name="r_flight_date[]" id="r_flight_date{{$key}}" value="{{date('d M Y', strtotime($row->flight_date))}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Departure Time</label>
                                                    <input type="text" class="form-control" name="r_flight_dep_time[]" id="r_flight_dep_time{{$key}}" value="{{$row->flight_dep_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Arrival Date</label>
                                                    <input type="text" class="form-control" name="r_flight_arrival_date[]" id="r_flight_arrival_date{{$key}}" value="{{date('d M, Y', strtotime($row->flight_arrival_date))}}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Arrival Time</label>
                                                    <input type="text" class="form-control" name="r_flight_arrival_time[]" id="r_flight_arrival_time{{$key}}" value="{{$row->flight_arrival_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Departing Airport</label>
                                                    <select class="form-control airport" name="r_flight_dep_airport[]" id="r_flight_dep_airport{{$row->id}}">
                                                        <option selected="selected">
                                                            {{$row->flight_dep_airport}}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                    <label style="font-size:14px">Arriving Airport</label>
                                                    <select class="form-control airport1" name="r_flight_arrival_airport[]" id="r_flight_arrival_airport{{$row->id}}">
                                                        <option selected="selected">
                                                            {{$row->flight_arrival_airport}}
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-sm-6">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label style="font-size:14px">Baggage</label>
                                                            <input type="text" class="form-control" name="baggage[]" id="bagage" value="{{$row->baggage}}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-end mb-3" style="font-size: 2em;">
                                                        <a class="deleteFlightReturn" data-id="{{ $row->id }}" style="cursor: pointer; color: #dc0b0b;margin-left: 5px;">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>

                            </div> 
                            <div class="row mt-3">
                                <div class="col-md-2 button-submit1 ml-auto text-right">
                                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 15%; visibility: hidden;">
                                    <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Update</button>
                                    <span class="text-success d-block" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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

        .autocomplete-items {
            z-index: 999;
            position: absolute;
            background: #fff;
            width: 94%;
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

        .select2-container .select2-selection--single .select2-selection__rendered {
            width: 250px;
        }
    </style>
@endsection
@section('footerSection')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            var checkorigin = "<?php echo count($oflights); ?>";
            var checkReturn = "<?php echo count($rflights); ?>";
            console.log(checkorigin);
            console.log(checkReturn);
            if(checkorigin > 0 && checkReturn > 0){
                $("#twoway").prop('checked', true);
                $("#BothTwowayOneway").removeClass('d-none');
                $("#BothTwowayOneway").addClass('d-flex');
                $('#buyer').prop('checked', true);
                $('#supplier').prop('checked', false); 
            }
        });
        
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
    <!-- Delete O -->
    <script type="text/javascript">
        $(".deleteFlightOrigin").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to delete this Flight?"))
              $.ajax(
              {
                url: '/departure/flight/delete-o/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  alert("Flight deleteed successfully!!");
                  window.location.reload();
                }
              });
        });
      </script>
      <!-- Delete R -->
      <script type="text/javascript">
        $(".deleteFlightReturn").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to delete this Flight?"))
              $.ajax(
              {
                url: '/departure/flight/delete-r/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  alert("Flight deleteed successfully!!");
                  window.location.reload();
                }
              });
        });
      </script>
    <script type="text/javascript">
        @foreach ($oflights as $key => $value)

        var airlines_data = [];
        $('.airline_{{$value->id}}').select2({
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
                    $('#code_{{$value->id}}').val(e.params.args.data.code);
                }
            }
        });
        @endforeach
        @foreach ($rflights as $key => $value)

        var airlines_data1 = [];
        $('.airline_0{{$value->id}}').select2({
            placeholder: 'Airline',
            tags: true,
            ajax: {
                url: "/get-airline-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    airlines_data1 = data;
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
            console.log(airlines_data1);
            for (let air of airlines_data1) {
                if (air.name == e.params.args.data.id) {
                    $('#code_0{{$value->id}}').val(e.params.args.data.code);
                }
            }
        });
        @endforeach
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
            $('.start_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd M yy',
                minDate: 0,
            });
            $('.fa-calendar').click(function () {
                $(".start_date").focus();
            });
        });

    </script>
    @foreach($oflights as $key=> $row)
        <script>
            jQuery(document).ready(function () {

                jQuery("#o_flight_date{{$key}}").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd M yy',
                    minDate: 0,
                });
                jQuery("#o_flight_arrival_date{{$key}}").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd M yy',
                    minDate: 0,
                });
                jQuery("#o_flight_time{{$key}}").datetimepicker({
                    format: 'HH:mm',
                })
                jQuery("#o_flight_arrival_time{{$key}}").datetimepicker({
                    format: 'HH:mm',
                })
            });
        </script>
    @endforeach
    <script>
        jQuery(document).ready(function () {

            <?php foreach($rflights as $key=> $row){ ?>
            jQuery("#r_flight_date{{$key}}").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd M yy',
                minDate: 0,
            });
            jQuery("#r_flight_arrival_date{{$key}}").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd M yy',
                minDate: 0,
            });
            jQuery("#r_flight_dep_time{{$key}}").datetimepicker({
                format: 'HH:mm',
            })
            jQuery("#r_flight_arrival_time{{$key}}").datetimepicker({
                format: 'HH:mm',
            })
            <?php } ?>
        });

        
    </script>
    <script>
        $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();

                $('#gif').css('visibility', 'visible');
                $('#store_form').html('Please wait...')
                $('#store_form').prop('disabled', true);
                var formDatas = new FormData(document.getElementById('DeparturForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('flight_update',request()->route('id')) }}",
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
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $("#start_date12").datepicker();
            //     changeMonth: true,
            //     changeYear: true,
            //     showButtonPanel: true,
            //     dateFormat: 'dd M, yy',
            //     minDate: 0,
            //   });
        });
        $('.start-calendar').click(function () {
            $("#start_date").focus();
        });
        $("li a").each(function () {
            //alert(this.href);
            if (this.href == window.location.href) {
                $(this).addClass("active");
            }
        })
    </script>
    <script>
        // Departure Airline
        //  $('#flight').select2({
        //     placeholder: 'Select Airline',
        //     ajax: {
        //         url: "/departure_airline",
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.airline,
        //                         id: item.airline
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // });

        // Return Airline
        // $('#flight_return').select2({
        //     placeholder: 'Select Airline',
        //     ajax: {
        //         url: "/departure_airline_return",
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.airline,
        //                         id: item.airline
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // });
    </script>
    <script>
        $(document).ready(function () {
            // var radioinput = $("#username-field input[type='radio']")
            $("input[type='radio']").change(function () {
                //   alert("radioinput");
                if ($(this).val() == 'R') {
                    $("#returning").show();
                    $("#originating").hide();
                } else {
                    $("#returning").hide();
                    $("#originating").show();
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var maxFields = 5; //Input fields increment limitation
            var addButtons = $('.add_button'); //Add button selector
            var wrappers = $('.wrappers'); //Input field wrapper
            var x = 1;

            $(addButtons).click(function () {
                if (x < maxFields) {
                    x++;
                    $(wrappers).append('<div class="row rowes"><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Airline</label><select class="form-control airline_1' + x + '" name="flight_name[]" id="origin_flight_1' + x + '"></select></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group code_1' + x + '"><label>Airline Code</label> <input type="text" name="code[]" id="code_' + x + '" class="form-control code_1' + x + '"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Flight Code</label><input type="text" class="form-control" name="flight_no[]" id="o_flight_no" ></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Date</label> <input type="text" class="form-control o_flight_date" name="flight_date[]" id=""></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Time</label><input type="text" class="form-control o_flight_time" name="flight_dep_time[]" id="o_flight_time"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Date</label> <input type="text" class="form-control o_flight_arrival_date" name="flight_arrival_date[]" id=""></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Time</label> <input type="text" class="form-control o_flight_time" name="flight_arrival_time[]" id="o_flight_arrival_time"></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Departing Airport</label><select class="form-control airport3" name="flight_dep_airport[]" id="o_flight_dep_airport_' + x + '"></select></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Arriving Airport</label><select class="form-control airport4" name="flight_arrival_airport[]" id="o_flight_arrival_airport_' + x + '"></select></div></div><div class="col-md-4 col-lg-4 col-sm-6"><div class="form-group"><label style="font-size:14px">Baggage</label><input type="text" class="form-control" name="baggage[]" id="bagage" autocomplete="off"></div></div><div class="col-md-1" style="margin-top: 18px;"><a href="javascript:void(0);" class="remove_button btn btn-danger" style="padding:.25rem .5rem"><i class="fas fa-minus-circle"></i>Remove</a></div></div></div>');

                    var airlines_datas = [];
                    jQuery('.airline_1' + x).select2({
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
                                jQuery('.code_1' + x).val(e.params.args.data.code);
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
            $(wrappers).on('click', '.remove_button', function (e) {
                e.preventDefault();
                $(".rowes").last().remove();

                x--;
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var maxFields = 5; //Input fields increment limitation
            var addButtons = $('.add_button1'); //Add button selector
            var wrappers = $('.wrappers1'); //Input field wrapper
            var x = 1;

            $(addButtons).click(function () {
                if (x < maxFields) {
                    x++;
                    $(wrappers).append('<div class="row rowes1"><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label>Airline</label><select class="form-control airline_2' + x + '" name="r_flight_name[]" id="return_flight_2' + x + '"></select></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label>Airline Code</label><input type="text" class="form-control code_2' + x + '" name="r_code[]" id="code_2' + x + '"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Flight Code</label><input type="text" class="form-control" name="r_flight_no[]" id="r_flight_no"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Date</label> <input type="text" class="form-control r_flight_date" name=r_flight_date[]" id=""></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Departure Time</label><input type="text" class="form-control r_flight_time" name="r_flight_dep_time[]" id="r_flight_dep_time"></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Date</label> <input type="text" class="form-control r_flight_arrival_date" name=r_flight_arrival_date[]" id=""></div></div><div class="col-md-2 col-lg-2 col-sm-6"><div class="form-group"><label style="font-size:14px">Arrival Time</label> <input type="text" class="form-control r_flight_time" name="r_flight_arrival_time[]" id="r_flight_arrival_time"></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Departing Airport</label> <select class="form-control airport3" name="r_flight_dep_airport[]" id="r_flight_dep_airport_' + x + '"></select></div></div><div class="col-md-3 col-lg-3 col-sm-6"><div class="form-group"><label style="font-size:14px">Arriving Airport</label><select class="form-control airport4" name="r_flight_arrival_airport[]" id="r_flight_arrival_airport_' + x + '"></select></div></div><div class="col-md-4 col-lg-4 col-sm-6"><div class="form-group"><label style="font-size:14px">Baggage</label><input type="text" class="form-control" name="baggage_arriving[]" id="baggage_arriving" autocomplete="off"></div></div><div class="col-md-1" style="margin-top: 18px;"><a href="javascript:void(0);" class="remove_button1 btn btn-danger" style="padding:.25rem .5rem"><i class="fas fa-minus-circle"></i>Remove</a></div></div></div>');
                    var airlines_datas = [];
                    jQuery('.airline_2' + x).select2({
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
                                jQuery('.code_2' + x).val(e.params.args.data.code);
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
            $(wrappers).on('click', '.remove_button1', function (e) {
                e.preventDefault();
                $(".rowes1").last().remove();

                x--;
            });
        });
    </script>
@endsection