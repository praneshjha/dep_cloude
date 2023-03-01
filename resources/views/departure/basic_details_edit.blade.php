@extends('layouts.app')
@section('tagSection')
    <title>Edit Departure | Departure Cloud</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/timepicker.css')}}">

@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Edit Basic Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Departure</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box formCard">
                        @include('layouts/itinerary_menu')
                        <form role="form" id="DeparturForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                    <h4 class="m-0">{{$departures->departure_type}}</h4>
                                    <div class="underline"></div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        @if($departures->departure_type == 'Flight Only')
                                            <label for="title">Sector Name <span class="text-danger">*</span></label>
                                        @else
                                            <label for="title">Departure Name <span class="text-danger">*</span></label>
                                        @endif
                                        <input type="text" class="form-control" name="title" id="title" value="{{$departures->title}}" placeholder="4 Nights 5 Days Pharaohs Nile Cruise Adventure">
                                        <span class="validationError" id="title_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group destinationList">
                                        <label>Destinations Covered <span class="text-danger">*</span></label> <span class="validationError" id="destinations_error"></span>
                                        <input type="hidden" name="destinations" id="destinationName" class="form-control destinationName">
                                        <input type="text" id="destinations" class="form-control destinations" placeholder="Search destinations..">
                                        <div class="autocomplete-items" style="display: none"></div>
                                        <div id="dropdest">
                                        </div>
                                    </div>
                                </div>
                                @if(in_array(3, json_decode($columns)))
                                    <div class="col-md-6 col-lg-6 col-sm-12" id="nights_and_days">
                                        <div class="d-flex">
                                            <div class="form-group">
                                                <label for="noOfnights">Nights<!-- <span class="text-danger">*</span> --></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="nights" id="nights" placeholder="4" value="{{$departures->no_of_nights}}"
                                                           oninput="this.value = (this.value.length > 8) ? this.value.slice(0,8) : this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value) ? this.value : this.value = this.value.slice(0,-1); get_no_of_days(event)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon1">N</span>
                                                    </div>
                                                </div>
                                                <span class="validationError" id="nights_error"></span>
                                            </div>
                                            <h2 class="slash ml-1 mr-1 mb-0" style="margin-top:1.8rem"> / </h2>
                                            <div class="form-group">
                                                <label for="days">Days<!-- <span class="text-danger">*</span> --></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="days" placeholder="4" id="days" value="{{$departures->no_of_days}}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon1">D</span>
                                                    </div>
                                                </div>
                                                <span class="validationError" id="days_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="total_seat">Total Units<!-- <span class="text-danger">*</span> --></label>
                                        <input type="text" class="form-control" name="total_seat" id="total_seat" placeholder="Total no. seat available" value="{{$departures->total_seat}}">
                                        <span class="validationError" id="total_seat_error"></span>
                                    </div>
                                </div>
                                <!--  <div class="col-md-2 col-lg-2 col-sm-12">
                                     <div class="form-group">
                                         <label for="total_seat">Total Room</label> -->
                                <input type="hidden" class="form-control" name="total_room" id="total_room" placeholder="Total rooms available" value="{{$departures->total_room}}">
                                <!-- <span class="validationError" id="total_room_error"></span>
                            </div>
                        </div> -->
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="start_date" id="start_date_lebel">@if($departures_types->departure_type == 2)
                                                Start Date
                                            @elseif($departures_types->departure_type == 4)
                                                Chek-In Date
                                            @else
                                                Departure Date
                                            @endif <span class="text-danger">*</span></label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control pull-right start_date fromdate" name="start_date" id="start_date" placeholder="Tour Start Date" value="{{date('d M Y', strtotime($departures->start_date))}}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                        <span class="validationError" id="start_date_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="return_date" id="return_date_lebel">@if($departures_types->departure_type == 2)
                                                End Date
                                            @elseif($departures_types->departure_type == 4)
                                                Check-Out Date
                                            @else
                                                Return Date
                                            @endif</label>
                                        <div class="input-group date">
                                            @if($departures->end_date != null)
                                                <input type="text" class="form-control pull-right retrun_date todate" name="return_date" id="return_date" autocomplete="off" value="{{date('d M Y', strtotime($departures->end_date))}}"
                                                       placeholder="Return Date">
                                            @else
                                                <input type="text" class="form-control pull-right retrun_date todate" name="return_date" id="return_date" autocomplete="off" placeholder="Return Date">
                                            @endif
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar returnDate" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                        <span class="validationError" id="retrun_date_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        @if($departures_types->departure_type == 2)
                                            <label for="ending_at">Ends At</label>
                                        @else
                                            <label for="ending_at">Departure To<!-- <span class="text-danger">*</span> --></label>
                                        @endif

                                        <select class="form-control  hotel" name="ending_at" id="ending_at">
                                            <option value="{{$departures->ending_at}}" selected>{{$departures->ending_at}}</option>

                                        </select>
                                        <span class="validationError" id="ending_at_error"></span>
                                    </div>
                                </div>
                                @if(in_array(10, json_decode($columns)))
                                    <div class="col-md-3 col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            @if($departures_types->departure_type == 2)
                                                <label for="starting_from">Starts From</label>
                                            @else
                                                <label for="starting_from">Ex</label>
                                            @endif
                                            <select class="form-control hotel" name="starting_from[]" id="starting_from" multiple>
                                                @foreach($departures->end_at as $endingAt)
                                                    <option value="{{$endingAt}}" selected>{{$endingAt}}</option>
                                                @endforeach
                                            </select>
                                            <span class="validationError" id="starting_from_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="return_to">Return To</label>
                                            <select class="form-control hotel" name="return_to[]" id="return_to" multiple>
                                                @foreach($departures->return_to as $returnTo)
                                                    <option value="{{$returnTo}}" selected>{{$returnTo}}</option>
                                                @endforeach
                                            </select>
                                            <span class="validationError" id="return_to_error"></span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="hold_duration">Hold Duration<!-- <span class="text-danger">*</span> --></label>
                                        <select class="form-control hold_duration" name="hold_time" id="hold_duration">
                                            @foreach($holdduration as $row)
                                                <option value="{{$row->hours}}" @if($departures->hold_duration == $row->hours) selected @else @endif>{{$row->hours}} Hours</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="hold_durationDays">Hold till<!-- <span class="text-danger">*</span> --></label>
                                        <select class="form-control holdtill" name="hold_duration" id="holdtill" onclick='showDays()'>

                                            @if(count($holdtill)>0)
                                                @foreach($holdtill as $row)
                                                    <option value="{{$row->days}}" @if($departures->holdDep == $row->days) selected @endif> D-{{$row->days}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="ownner">Contact Person <span class="text-danger"> *</span></label> <span class="validationError" id="ownner_error"></span>
                                        <select class="form-control w-100" name="contact_person" id="contact_person">
                                            @foreach($users as $use)
                                                <option value="{{$use->id}}" @if($use->id == $departures->contact_person_id) selected @endif>{{$use->flname}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" class="form-control" name="ownner" id="ownner" value="{{$departures->company_name}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="ownner">Additional Contact Person <span class="text-danger"> *</span></label> <span class="validationError" id="additional_ownner_error"></span>
                                            <select class="form-control w-100" name="additional_person_id" id="additional_person_id">
                                                <option value="" style="color:#555">Select Additional Person ..</option>
                                                @foreach($users as $use)
                                                <option value="{{$use->id}}" @if($use->id == $departures->additional_person_id) selected @endif>{{$use->flname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Tags
                                        </label>
                                        <select class="form-control tags" name="tags[]" id="tags" multiple='multiple'>
                                            @foreach($tags as $tag)
                                                <option value="{{$tag->name}}" selected>{{$tag->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Description</label> <span class="validationError" id="description_error"></span>
                                        <textarea class="form-control" name="description" id="description" rows="3" palaceholder="">{{$departures->description}}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2 button-submit1 ml-auto">
                                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 15%; visibility: hidden;">
                                    <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Update</button>
                                    <span class="text-success d-block" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                                <div class="col-md-2 button-submit1">
                                    <img src="{{ asset('images/loader.gif') }}" id="gif1" style="width: 15%; visibility: hidden;">
                                    <button class="btn btn-primary active" type="button" id="store_form_update"><i class="fa fa-save"></i> Update & Next</button>
                                    <span class="text-success d-block" id="mesegese1" style="margin-left: 10px"></span>
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
        .navbar-custom .topnav-menu .nav-link {
        padding: 25px 15px;
    }
    </style>
@endsection
@section('footerSection')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $( "#hold_duration" ).change(function() {
            var duration = $("#hold_duration").val();
            var s_date = new Date($("#start_date").val());
            if(duration == 0){
                var today = new Date();
                var yesterday = new Date();

                yesterday.setDate(today.getDate() - 1);
                console.log("Original Date : ",yesterday);

                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var month = today.getMonth();

                //yesterday = yesterday.getDate() + ' ' + monthNames[month] + ', ' + yesterday.getFullYear();
                var yesterday = new Date(yesterday);

                var diff  = new Date(s_date - yesterday);
                var days  = Math.ceil(diff/1000/60/60/24);
                $('#holdtill').html('<option value="'+days+'" selected> D-'+days+'</option>');
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#contact_person").select2();
            $("#additional_person_id").select2();
            $(".tags").select2({
                placeholder: 'Add Tag(s)',
                tags: true,
                ajax: {
                    url: "/tags-list-search",
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
            $('.airport').select2({
                placeholder: 'Departing Airport',
                ajax: {
                    url: "/destination-pois-ajax",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.dest_name,
                                    id: item.dest_name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            $('.airport1').select2({
                placeholder: 'Arriving Airport',
                ajax: {
                    url: "/destination-pois-ajax",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.dest_name,
                                    id: item.dest_name
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            var h = $(".hotel").select2({
                tags: true,
            });
            // var f = $(".flight").select2({
            //  tags: true,
            // });
            var s = $(".start").select2({
                tags: true,
            });
            var e = $(".end").select2({
                tags: true,
            });

            var e = $(".hold_duration").select2({
                tags: true,
            });
            var e = jQuery(".holdtill").select2({
                tags: true,
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.start_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd-M-yy',
                minDate: 0,
            });
            $('.fa-calendar').click(function () {
                $(".start_date").focus();
            });
            $('#return_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd-M-yy',
                minDate: 0,
            });
            $('.returnDate').click(function () {
                $("#return_date").focus();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var destinationName = $('#destinationName').val();
                if (destinationName == "") {
                    $("span#description_error").hide();
                    $("span#destinations_error").html('This field is required!');
                    $("select#destinations").focus();
                    return false;
                }
                var title = $('#title').val();
                if (title == "") {
                    //$("span#title_error").hide();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                } else {
                    $("span#title_error").hide();
                }
                // var nights = $('#nights').val();
                // if (nights == "") {
                //     //$("span#nights_error").hide();
                //     $("span#nights_error").html('This field is required!');
                //     $("input#nights").focus();
                //     return false;
                // }else{
                //     $("span#nights_error").hide();
                // }
                // var days = $('#days').val();
                // if (days == "") {
                //     //$("span#days_error").hide();
                //     $("span#days_error").html('This field is required!');
                //     $("input#days").focus();
                //     return false;
                // }else{
                //     $("span#days_error").hide();
                // }
                // var total_seat = $('#total_seat').val();
                // if (total_seat == "") {
                //     //$("span#total_seat_error").hide();
                //     $("span#total_seat_error").html('This field is required!');
                //     $("input#total_seat").focus();
                //     return false;
                // }else{
                //     $("span#total_seat_error").hide();
                // }
                var start_date = $('#start_date').val();
                if (start_date == "") {
                    //$("span#start_date_error").hide();
                    $("span#start_date_error").html('This field is required!');
                    $("input#start_date").focus();
                    return false;
                } else {
                    $("span#start_date_error").hide();
                }
                // // var starting_from = $('#starting_from').val();
                // // if (starting_from == "") {
                // //     //$("span#starting_from_error").hide();
                // //     $("#starting_from_error").html('This field is required!');
                // //     $("select#starting_from").focus();
                // //     return false;
                // // }else{
                // //     $("span#starting_from_error").hide();
                // // }
                // var ending_at = $('#ending_at').val();
                // if (ending_at == "") {
                //     //$("span#ending_at_error").hide();
                //     $("#ending_at_error").html('This field is required!');
                //     $("select#ending_at").focus();
                //     return false;
                // }else{
                //  $("span#ending_at_error").hide();
                // }
                $('#gif').css('visibility', 'visible');
                $('#store_form').html('Please wait...')
                $('#store_form').prop('disabled', true);
                $('#store_form_update').prop('disabled', true);
                var formDatas = new FormData(document.getElementById('DeparturForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('departure_update',request()->route('id')) }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //window.location = data.url;
                        window.location.reload();
                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
            // Update & next
            $('#store_form_update').click(function (e) {
                e.preventDefault();
                $('#gif1').show();
                var destinationName = $('#destinationName').val();
                if (destinationName == "") {
                    $("span#description_error").hide();
                    $("span#destinations_error").html('This field is required!');
                    $("select#destinations").focus();
                    return false;
                }
                var title = $('#title').val();
                if (title == "") {
                    //$("span#title_error").hide();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                } else {
                    $("span#title_error").hide();
                }
                // var nights = $('#nights').val();
                // if (nights == "") {
                //     //$("span#nights_error").hide();
                //     $("span#nights_error").html('This field is required!');
                //     $("input#nights").focus();
                //     return false;
                // }else{
                //     $("span#nights_error").hide();
                // }
                // var days = $('#days').val();
                // if (days == "") {
                //     //$("span#days_error").hide();
                //     $("span#days_error").html('This field is required!');
                //     $("input#days").focus();
                //     return false;
                // }else{
                //     $("span#days_error").hide();
                // }
                // var total_seat = $('#total_seat').val();
                // if (total_seat == "") {
                //     //$("span#total_seat_error").hide();
                //     $("span#total_seat_error").html('This field is required!');
                //     $("input#total_seat").focus();
                //     return false;
                // }else{
                //     $("span#total_seat_error").hide();
                // }
                var start_date = $('#start_date').val();
                if (start_date == "") {
                    //$("span#start_date_error").hide();
                    $("span#start_date_error").html('This field is required!');
                    $("input#start_date").focus();
                    return false;
                } else {
                    $("span#start_date_error").hide();
                }
                // // var starting_from = $('#starting_from').val();
                // // if (starting_from == "") {
                // //     //$("span#starting_from_error").hide();
                // //     $("#starting_from_error").html('This field is required!');
                // //     $("select#starting_from").focus();
                // //     return false;
                // // }else{
                // //     $("span#starting_from_error").hide();
                // // }
                // var ending_at = $('#ending_at').val();
                // if (ending_at == "") {
                //     //$("span#ending_at_error").hide();
                //     $("#ending_at_error").html('This field is required!');
                //     $("select#ending_at").focus();
                //     return false;
                // }else{
                //  $("span#ending_at_error").hide();
                // }
                $('#gif1').css('visibility', 'visible');
                $('#store_form').html('Please wait...')
                $('#store_form').prop('disabled', true);
                $('#store_form_update').prop('disabled', true);
                var formDatas = new FormData(document.getElementById('DeparturForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('departure_update',request()->route('id')) }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif1').hide();
                        $('#mesegese1').html("<span class='sussecmsg'>Success!</span>");
                        window.location = data.url;
                        //location.reload();
                    },
                    errors: function () {
                        $('#gif1').hide();
                        $('#mesegese1').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });
    </script>
    <script src="{{asset('js/customJS/basic-details_edit.js')}}"></script>
    <script type="text/javascript">
        function initDestinationAll(Lat, Long, dest_name, actual_names, country, regions, iso2s, iso3s, descriptionz, destId, countLat, countLong, officeName, capital, largeCity, continent, countDesc, subCountinent, countIso2, countIso3, isdCode, internetTld, currency, cointryId, currencySymbol, currencyCode, driveOn, area, areaUnit, population) {

            dest_selected.push(
                {
                    'name': dest_name,
                    'actual_name': actual_names,
                    'country': country,
                    'id': destId,
                    'lat': Lat,
                    'long': Long,
                    'region': regions,
                    'iso2': iso2s,
                    'iso3': iso3s,
                    'description': descriptionz,
                    'country_id': cointryId,
                    'country_lat': countLat,
                    'country_long': countLong,
                    'official_name': officeName,
                    'capital': capital,
                    'largest_city': largeCity,
                    'continent': continent,
                    'sub_continent': subCountinent,
                    'count_description': countDesc,
                    'count_iso2': countIso2,
                    'count_iso3': countIso3,
                    'isd_code': isdCode,
                    'internet_tld': internetTld,
                    'currency': currency,
                    'currency_symbol': currencySymbol,
                    'currency_code': currencyCode,
                    'drive_on': driveOn,
                    'area': area,
                    'area_unit': areaUnit,
                    'population': population,
                }
            );
            $('#destinationName').val(JSON.stringify(dest_selected));
            set_dest_html();
        }
    </script>
    <?php
    if(count($destinations) > 0){
    //print_r($imagePath);
    foreach($destinations as $destination){  ?>
    <script type="text/javascript">
        var dest_name = '<?php  echo $destination->name; ?>';
        var actual_names = '<?php  echo $destination->actualname; ?>';
        var country = '<?php  echo $destination->country; ?>';
        var destId = '<?php  echo $destination->id; ?>';
        var Lat = '<?php  echo $destination->lat; ?>';
        var Long = '<?php  echo $destination->long; ?>';
        var regions = '<?php  echo $destination->region; ?>';
        var iso2s = '<?php  echo $destination->iso2; ?>';
        var iso3s = '<?php  echo $destination->iso3; ?>';
        var descriptionz = '<?php  echo $destination->description; ?>';
        //alert(s3name);
        var cointryId = '<?php  echo $destination->count_id; ?>';
        var officeName = '<?php  echo $destination->official_name; ?>';
        var capital = '<?php  echo $destination->capital; ?>';
        var largeCity = '<?php  echo $destination->largest_city; ?>';
        var continent = '<?php  echo $destination->continent; ?>';
        var countDesc = '<?php  echo $destination->count_des; ?>';
        var subCountinent = '<?php  echo $destination->sub_continent; ?>';
        var countIso2 = '<?php  echo $destination->iso_2; ?>';
        var countIso3 = '<?php  echo $destination->iso_3; ?>';
        var isdCode = '<?php  echo $destination->isd_code; ?>';
        var countLat = '<?php  echo $destination->count_lat; ?>';
        var countLong = '<?php  echo $destination->count_long; ?>';
        var internetTld = '<?php  echo $destination->internet_tld; ?>';
        var currency = '<?php  echo $destination->currency; ?>';
        var currencySymbol = '<?php  echo $destination->currency_symbol; ?>';
        var currencyCode = '<?php  echo $destination->currency_code; ?>';
        var driveOn = '<?php  echo $destination->drives_on; ?>';
        var area = '<?php  echo $destination->area; ?>';
        var areaUnit = '<?php  echo $destination->area_unit; ?>';
        var population = '<?php  echo $destination->population; ?>';
        initDestinationAll(Lat, Long, dest_name, actual_names, country, regions, iso2s, iso3s, descriptionz, destId, countLat, countLong, officeName, capital, largeCity, continent, countDesc, subCountinent, countIso2, countIso3, isdCode, internetTld, currency, cointryId, currencySymbol, currencyCode, driveOn, area, areaUnit, population)

    </script>
    <?php }} ?>
    <script>
        $(document).ready(function () {
            $("#start_date12").datepicker();
            //     changeMonth: true,
            //     changeYear: true,
            //     showButtonPanel: true,
            //     dateFormat: 'dd-M-yy',
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
        $(document).ready(function () {
            // Departure From Destinations
            $('#starting_from').select2({
                placeholder: 'Search Destination',
                ajax: {
                    url: "/start_from_destination",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.destination,
                                    id: item.destination
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            // Departure To Destinations
            $('#ending_at').select2({
                placeholder: 'Search Destination',
                ajax: {
                    url: "/start_from_destination",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.destination,
                                    id: item.destination
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Departure To Destinations
            $('#return_to').select2({
                placeholder: 'Search Destination',
                ajax: {
                    url: "/start_from_destination",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.destination,
                                    id: item.destination
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
    <script>
        var default_inr = "<?php echo $inr; ?>"
        $('#price_inr').keyup(function () {
            var price_inr;
            price_inr = parseFloat($('#price_inr').val());
            if (price_inr) {
                var result = Math.round(price_inr / default_inr);
                if ($("#price_usd").val(result)) {
                    $("#price_usd").val(result)
                    $("#price_usd").prop("readonly", true);
                }
            } else {
                $("#price_usd").val('')
                $("#price_usd").prop("readonly", false);
            }
        });
        $('#price_usd').keyup(function () {
            var price_usd;
            price_usd = parseFloat($('#price_usd').val());
            if (price_usd) {
                var result = Math.round(price_usd * default_inr);
                if ($("#price_inr").val(result)) {
                    $("#price_inr").val(result)
                    $("#price_inr").prop("readonly", true);
                }
            } else {
                $("#price_inr").val('')
                $("#price_inr").prop("readonly", false);
            }
        });

        $('#single_price_inr').keyup(function () {
            var price_inr;
            price_inr = parseFloat($('#single_price_inr').val());
            if (price_inr) {
                var result = Math.round(price_inr / default_inr);
                if ($("#single_price_usd").val(result)) {
                    $("#single_price_usd").val(result)
                    $("#single_price_usd").prop("readonly", true);
                }
            } else {
                $("#single_price_usd").val('')
                $("#single_price_usd").prop("readonly", false);
            }
        });
        $('#single_price_usd').keyup(function () {
            var price_usd;
            price_usd = parseFloat($('#single_price_usd').val());
            if (price_usd) {
                var result = Math.round(price_usd * default_inr);
                if ($("#single_price_inr").val(result)) {
                    $("#single_price_inr").val(result)
                    $("#single_price_inr").prop("readonly", true);
                }
            } else {
                $("#single_price_inr").val('')
                $("#single_price_inr").prop("readonly", false);
            }
        });
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

    <script>
        $(function () {
            jQuery('#total_seat').keyup(function () {
                if (jQuery('#total_seat').val()) {
                    var value1 = parseInt(jQuery('#total_seat').val()) || 0;
                    var result = Math.ceil(value1 / 2);
                    jQuery('#total_room').val(result);
                } else {
                    jQuery('#total_room').val('');
                }
            });
        });
        var total_seat = document.querySelector('#total_seat');
        total_seat.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var total_unit = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = total_unit;
        }

        var total_room = document.querySelector('#total_room');
        total_room.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var room = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = room;
        }
    </script>
@endsection
