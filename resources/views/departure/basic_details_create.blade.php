@extends('layouts.app')
@section('tagSection')
<title>Create Basic Details | Departure Cloud</title>
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
                        <h4 class="page-title">Create Basic Details</h4>
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
                                <div class="row mt-2">
                                    <div class="col-md-12 col-lg-12 col-sm-12 mb-2">
                                        <h4 class="m-0">{{$dep_type_name}}</h4>
                                        <div class="underline"></div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            @if(request()->route('departure_type_id') == 5)
                                            <label for="title">Sector Name <span class="text-danger">*</span></label>
                                            @else
                                            <label for="title">Departure Name <span class="text-danger">*</span></label>
                                            @endif
                                            <input type="text" class="form-control" name="title" id="title" placeholder="4 Nights 5 Days Pharaohs Nile Cruise Adventure">
                                            <span class="validationError" id="title_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group destinationList">
                                            <label for="destinationName">Destinations Covered <span class="text-danger">*</span></label>
                                            <span class="validationError" id="destinations_error"></span>
                                            <input type="hidden" name="destinations" id="destinationName" class="form-control destinationName">
                                            <input type="text" id="destinations" class="form-control destinations" placeholder="Search destinations..">
                                            <div class="autocomplete-items" style="display: none"></div>
                                            <div id="dropdest">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12" id="nights_and_days">
                                        <div class="d-flex">
                                            <div class="form-group">
                                                <label for="noOfnights">Nights<!-- <span class="text-danger">*</span> --></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="nights" id="nights" placeholder="4">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon1">N</span>
                                                    </div>
                                                </div>
                                                <span class="validationError" id="nights_error"></span>
                                            </div>
                                            <h2 class="slash ml-1 mr-1 mb-0" style="margin-top:1.3rem"> / </h2>
                                            <div class="form-group">
                                                <label for="days">Days<!-- <span class="text-danger">*</span> --></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="days" id="days" placeholder="5">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon1">D</span>
                                                    </div>
                                                </div>
                                                <span class="validationError" id="days_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            <label for="total_seat">Total Units<!-- <span class="text-danger">*</span> --></label>
                                            <input type="text" class="form-control" name="total_seat" id="total_seat" placeholder="Total units">
                                            <span class="validationError" id="total_seat_error"></span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            <label for="total_seat">Total Room</label> -->
                                            <input type="hidden" class="form-control" name="total_room" id="total_room" placeholder="Total rooms available">
                                            <!-- <span class="validationError" id="total_room_error"></span>
                                        </div>
                                    </div> -->
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_date" id="start_date_lebel">Departure Date<span class="text-danger">*</span></label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control pull-right start_date fromdate" value="" name="start_date" id="start_date" autocomplete="off" placeholder="Tour Start Date">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                </div>
                                            </div>
                                            <span class="validationError" id="start_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            <label for="return_date" id="return_date_lebel">Return Date</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control pull-right retrun_date todate" value="" name="return_date" id="return_date" autocomplete="off" placeholder="Tour End Date">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar returnDate" aria-hidden="true"></i></span>
                                                </div>
                                            </div>
                                            <span class="validationError" id="retrun_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            @if(request()->route('departure_type_id') == 2)
                                            <label for="ending_at">Ends At</label>
                                            @else
                                            <label for="ending_at">Departure To</label>
                                            @endif
                                            <select class="form-control  hotel" name="ending_at" id="ending_at">
                                            </select>
                                            <span class="validationError" id="ending_at_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-lg-3 col-sm-12 ex_labels" id="ex_label">
                                        <div class="form-group">
                                            @if(request()->route('departure_type_id') == 2)<label for="starting_from">Starts From</label> @else
                                            <label for="starting_from">Ex</label>
                                            @endif
                                            <select class="form-control starting_from" name="starting_from[]" id="starting_from" multiple>
                                            </select>
                                            <span class="validationError" id="starting_from_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12 ex_labels" id="ex_labels">
                                        <div class="form-group">
                                            <label for="starting_from">Return To</label>
                                            <select class="form-control return_to" name="return_to[]" id="return_to" multiple>
                                            </select>
                                            <span class="validationError" id="return_to_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            <label for="hold_duration">Hold Duration<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control hold_duration" name="hold_time" id="hold_duration">
                                                <!-- <option selected="selected">Choose</option> -->
                                                @foreach($holdduration as $row)
                                                    <option value="{{$row->hours}}" @if($row->hours == 12) selected @endif>{{$row->hours}} Hours</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <div class="form-group">
                                            <label>Hold till Date</label>
                                            <select class="form-control holdtill" name="hold_duration" id="holdtill" onclick='showDays()'>
                                              @foreach($holdtill as $row)
                                                    <option value="{{$row->days}}" @if($row->days == 14) selected @endif> D-{{$row->days}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <?php //dd($holdtill); ?>

                                    <div class="col-md-3 col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="ownner">Contact Person <span class="text-danger"> *</span></label> <span class="validationError" id="ownner_error"></span>
                                            <select class="form-control w-100" name="contact_person" id="contact_person">
                                                @foreach($users as $use)
                                                <option value="{{$use->id}}" @if($use->id == $contact_p->contact_person_id) selected @endif>{{$use->flname}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="form-control" name="ownner" id="ownner" value="{{Auth::user()->company_name}}" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="ownner">Additional Contact Person <span class="text-danger"> *</span></label> <span class="validationError" id="additional_ownner_error"></span>
                                            <select class="form-control w-100" name="additional_person_id" id="additional_person_id">
                                                <option value="" style="color:#555">Select Additional Person ..</option>
                                                @foreach($users as $use)
                                                <option value="{{$use->id}}">{{$use->flname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Tags</label>
                                            <select class="form-control tags" name="tags[]" id="tags" multiple='multiple'>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Description</label> <span class="validationError" id="description_error"></span>
                                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <img src="{{ asset('images/loader.gif') }}" class="gif" style="width:35px; visibility: hidden;">
                                        <div class="mr-2">
                                            <button class="btn btn-primary active" type="button" id="store_form_save"><i class="fa fa-save"></i> Save</button>
                                            <span class="text-success d-block" id="mesegese1" style="margin-left: 10px"></span>
                                        </div>
                                        <div class="">
<!--                                            <img src="{{ asset('images/loader.gif') }}" class="gif" style="width:35px; visibility: hidden;">-->
                                            <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save & Next</button>
                                            <span class="text-success d-block" id="mesegese" style="margin-left: 10px"></span>
                                        </div>
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
            <!-- <script src="{{asset('js/select2.full.min.js')}}"></script> -->
            <script src="{{asset('js/customJS/basic-details.js')}}"></script>
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
                // $( "#hold_duration" ).change(function() {
                //   var hold_duration = $('#hold_duration').val();
                //   if(hold_duration == 0){
                //     $('#holdtill').append('<option value="0" selected> D-0</option>');
                //   }
                // });
                //  var hold_durations = $('#hold_duration').val();
                //   if(hold_durations == 0){
                //     $('#holdtill').append('<option value="0" selected> D-0</option>');
                //   }
            </script>
            <script>

                $(document).ready(function () {
                        $("#contact_person").select2();
                        $("#additional_person_id").select2();
                        // Departure From Destinations
                        jQuery(".tags").select2({
                            placeholder: 'Add Tag(s)',
                            tags: true,
                            ajax: {
                                url: "/tags-list-search",
                                dataType: 'json',
                                delay: 250,
                                processResults: function (data) {
                                    return {
                                        results: jQuery.map(data, function (item) {
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
                    });

            </script>
            <script>
                $(function () {
                    jQuery('#nights').keyup(function () {
                        if (jQuery('#nights').val()) {
                            var value1 = parseInt(jQuery('#nights').val()) || 0;
                            jQuery('#days').val(value1 + 1);
                        } else {
                            jQuery('#days').val('');
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
                function restrictNumber (e) {
                    var total_unit = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                    this.value = total_unit;
                }
                var total_room = document.querySelector('#total_room');
                total_room.addEventListener('input', restrictNumber);
                function restrictNumber (e) {
                    var room = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                    this.value = room;
                }
            </script>
            <script>
                $(document).ready(function () {
                    $('.start_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd M, yy',
                        minDate: 0,
                    });
                    $('.fa-calendar').click(function () {
                        $(".start_date").focus();
                    });

                    $('#return_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd M, yy',
                        minDate: 0,
                    });
                    $('.returnDate').click(function () {
                        $("#return_date").focus();
                    });

                });
            </script>
            <script>
                var default_inr = "<?php echo $inr; ?>";
                jQuery('#price_inr').keyup(function () {
                    var price_inr;
                    //alert(default_inr);
                    price_inr = parseFloat(jQuery('#price_inr').val());
                    if (price_inr) {
                        var result = Math.round(price_inr / default_inr);
                        if (jQuery("#price_usd").val(result)) {
                            jQuery("#price_usd").val(result)
                            jQuery("#price_usd").prop("readonly", true);
                        }
                    } else {
                        jQuery("#price_usd").val('')
                        jQuery("#price_usd").prop("readonly", false);
                    }
                });
                jQuery('#price_usd').keyup(function () {
                    var price_usd;
                    price_usd = parseFloat(jQuery('#price_usd').val());
                    if (price_usd) {
                        var result = Math.round(price_usd * default_inr);
                        if (jQuery("#price_inr").val(result)) {
                            jQuery("#price_inr").val(result)
                            jQuery("#price_inr").prop("readonly", true);
                        }
                    } else {
                        jQuery("#price_inr").val('')
                        jQuery("#price_inr").prop("readonly", false);
                    }
                });

                jQuery('#single_price_inr').keyup(function () {
                    var price_inr;
                    price_inr = parseFloat(jQuery('#single_price_inr').val());
                    if (price_inr) {
                        var result = Math.round(price_inr / default_inr);
                        if (jQuery("#single_price_usd").val(result)) {
                            jQuery("#single_price_usd").val(result)
                            jQuery("#single_price_usd").prop("readonly", true);
                        }
                    } else {
                        jQuery("#single_price_usd").val('')
                        jQuery("#single_price_usd").prop("readonly", false);
                    }
                });
                jQuery('#single_price_usd').keyup(function () {
                    var price_usd;
                    price_usd = parseFloat(jQuery('#single_price_usd').val());
                    if (price_usd) {
                        var result = Math.round(price_usd * default_inr);
                        if (jQuery("#single_price_inr").val(result)) {
                            jQuery("#single_price_inr").val(result)
                            jQuery("#single_price_inr").prop("readonly", true);
                        }
                    } else {
                        jQuery("#single_price_inr").val('')
                        jQuery("#single_price_inr").prop("readonly", false);
                    }
                });
            </script>
            <script>
                function showDays() {

                    var end = jQuery('.fromdate').val();
                    var start = new Date();

                    var startDay = new Date(start);
                    var endDay = new Date(end);
                    var millisecondsPerDay = 1000 * 60 * 60 * 24;

                    var millisBetween = endDay.getTime() - startDay.getTime();
                    var days = millisBetween / millisecondsPerDay;
                    // Round down.
                    // alert( Math.floor(days));
                    if (Math.floor(days) < 21) {
                        document.getElementById("demo").innerHTML = Math.floor(days);
                    }
                }

            </script>
            <script>
                var h = jQuery(".hotel").select2({
                    tags: true,
                });
                // var f = jQuery(".flight").select2({
                //     tags: true,
                // });
                var s = jQuery(".start").select2({
                    tags: true,
                });
                var e = jQuery(".end").select2({
                    tags: true,
                });
                var e = jQuery(".holdtill").select2({
                    tags: true,
                });
                var e = jQuery(".hold_duration").select2({
                    tags: true,
                });
            </script>
            <script>
                jQuery('#meal_plan').select2({
                    placeholder: 'Select Meals Plan',
                });
                jQuery('#transport_type').select2({
                    placeholder: 'Select Transport Type',
                });

            </script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#store_form').click(function (e) {
                        e.preventDefault();
                        jQuery('.gif').show();

                        var title = jQuery('#title').val();
                        if (title == "") {
                            jQuery("span#title_error").html('This field is required!');
                            jQuery("input#title").focus();
                            return false;
                        } else {
                            jQuery("span#title_error").hide();
                        }
                        var destinationName = jQuery('#destinationName').val();
                        if (destinationName == "") {
                            jQuery("span#description_error").hide();
                            jQuery("span#destinations_error").html('This field is required!');
                            jQuery("input#destinations").focus();
                            return false;
                        }
                        // var nights = jQuery('#nights').val();
                        // if (nights == "") {
                        //     //jQuery("span#nights_error").hide();
                        //     jQuery("span#nights_error").html('This field is required!');
                        //     jQuery("input#nights").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#nights_error").hide();
                        // }
                        // var days = jQuery('#days').val();
                        // if (days == "") {
                        //     //jQuery("span#days_error").hide();
                        //     jQuery("span#days_error").html('This field is required!');
                        //     jQuery("input#days").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#days_error").hide();
                        // }
                        // var total_seat = jQuery('#total_seat').val();
                        // if (total_seat == "") {
                        //     //jQuery("span#total_seat_error").hide();
                        //     jQuery("span#total_seat_error").html('This field is required!');
                        //     jQuery("input#total_seat").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#total_seat_error").hide();
                        // }
                        // var total_room = jQuery('#total_room').val();
                        // if (total_room == "") {
                        //     //jQuery("span#total_seat_error").hide();
                        //     jQuery("span#total_room_error").html('This field is required!');
                        //     jQuery("input#total_room").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#total_room_error").hide();
                        // }
                        var start_date = jQuery('#start_date').val();
                        if (start_date == "") {
                            //jQuery("span#start_date_error").hide();
                            jQuery("span#start_date_error").html('This field is required!');
                            jQuery("input#start_date").focus();
                            return false;
                        } else {
                            jQuery("span#start_date_error").hide();
                        }
                        // var starting_from = jQuery('.starting_from').val();
                        // if (starting_from) {
                        //     //$("span#starting_from_error").hide();
                        //     jQuery("span#starting_from_error").hide();
                        // } else {
                        //     jQuery("#starting_from_error").html('This field is required!');
                        //     jQuery("select#starting_from").focus();
                        //     return false;
                        // }
                        // var ending_at = jQuery('#ending_at').val();
                        // if (ending_at) {
                        //     jQuery("span#ending_at_error").hide();
                        // } else {
                        //     jQuery("#ending_at_error").html('This field is required!');
                        //     jQuery("select#ending_at").focus();
                        //     return false;
                        // }


                        jQuery('.gif').css('visibility', 'visible');

                        jQuery('#store_form').html('Please wait...')
                        jQuery('#store_form').prop('disabled', true);
                        jQuery('#store_form_save').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('DeparturForm'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('departure_store',request()->route('departure_type_id')) }}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('.gif').hide();
                                $('#store_form').prop('disabled', true);
                                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                window.location = data.url;
                                //location.reload();
                            },
                            errors: function () {
                                jQuery('.gif').hide();
                                jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                            }

                        });
                    });

                jQuery('#store_form_save').click(function (e) {
                        e.preventDefault();
                        jQuery('.gif1').show();

                        var title = jQuery('#title').val();
                        if (title == "") {
                            jQuery("span#title_error").html('This field is required!');
                            jQuery("input#title").focus();
                            return false;
                        } else {
                            jQuery("span#title_error").hide();
                        }
                        var destinationName = jQuery('#destinationName').val();
                        if (destinationName == "") {
                            jQuery("span#description_error").hide();
                            jQuery("span#destinations_error").html('This field is required!');
                            jQuery("input#destinations").focus();
                            return false;
                        }
                        // var nights = jQuery('#nights').val();
                        // if (nights == "") {
                        //     //jQuery("span#nights_error").hide();
                        //     jQuery("span#nights_error").html('This field is required!');
                        //     jQuery("input#nights").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#nights_error").hide();
                        // }
                        // var days = jQuery('#days').val();
                        // if (days == "") {
                        //     //jQuery("span#days_error").hide();
                        //     jQuery("span#days_error").html('This field is required!');
                        //     jQuery("input#days").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#days_error").hide();
                        // }
                        // var total_seat = jQuery('#total_seat').val();
                        // if (total_seat == "") {
                        //     //jQuery("span#total_seat_error").hide();
                        //     jQuery("span#total_seat_error").html('This field is required!');
                        //     jQuery("input#total_seat").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#total_seat_error").hide();
                        // }
                        // var total_room = jQuery('#total_room').val();
                        // if (total_room == "") {
                        //     //jQuery("span#total_seat_error").hide();
                        //     jQuery("span#total_room_error").html('This field is required!');
                        //     jQuery("input#total_room").focus();
                        //     return false;
                        // } else {
                        //     jQuery("span#total_room_error").hide();
                        // }
                        var start_date = jQuery('#start_date').val();
                        if (start_date == "") {
                            //jQuery("span#start_date_error").hide();
                            jQuery("span#start_date_error").html('This field is required!');
                            jQuery("input#start_date").focus();
                            return false;
                        } else {
                            jQuery("span#start_date_error").hide();
                        }
                        // var starting_from = jQuery('.starting_from').val();
                        // if (starting_from) {
                        //     //$("span#starting_from_error").hide();
                        //     jQuery("span#starting_from_error").hide();
                        // } else {
                        //     jQuery("#starting_from_error").html('This field is required!');
                        //     jQuery("select#starting_from").focus();
                        //     return false;
                        // }
                        // var ending_at = jQuery('#ending_at').val();
                        // if (ending_at) {
                        //     jQuery("span#ending_at_error").hide();
                        // } else {
                        //     jQuery("#ending_at_error").html('This field is required!');
                        //     jQuery("select#ending_at").focus();
                        //     return false;
                        // }


                        jQuery('.gif1').css('visibility', 'visible');

                        jQuery('#store_form_save').html('Please wait...')
                        jQuery('#store_form_save').prop('disabled', true);
                        jQuery('#store_form').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('DeparturForm'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('departure_store',request()->route('departure_type_id')) }}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('.gif1').hide();
                                $('#store_form_save').prop('disabled', true);
                                $('#mesegese1').html("<span class='sussecmsg'>Success!</span>");
                                //window.location = data.url;
                                window.location.reload();
                            },
                            errors: function () {
                                jQuery('.gif1').hide();
                                jQuery('#mesegese1').html("<span class='sussecmsg'>Something went wrong!</span>");
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
                jQuery('.start-calendar').click(function () {
                    jQuery("#start_date").focus();
                });

                jQuery("li a").each(function () {
                    //alert(this.href);
                    if (this.href == window.location.href) {
                        jQuery(this).addClass("active");
                    }
                });

            </script>
            <script>
                // Departure From Destinations
                jQuery('#starting_from').select2({
                    placeholder: 'Search Destination',
                    ajax: {
                        url: "/start_from_destination",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: jQuery.map(data, function (item) {
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

                // Departure Return to Destinations
                jQuery('#return_to').select2({
                    placeholder: 'Search Destination',
                    ajax: {
                        url: "/start_from_destination",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: jQuery.map(data, function (item) {
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

                // Departure From To Destinations
                jQuery('#ending_at').select2({
                    placeholder: 'Search Destination',
                    ajax: {
                        url: "/start_from_destination",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: jQuery.map(data, function (item) {
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

            </script>

            <script type="text/javascript">
                var type_id = "<?php echo request()->route('departure_type_id'); ?>";
                $(function() {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        url : "{{ route('get_columns') }}",
                        type : "POST",
                        data: { id: type_id },
                        success : function(response) {
                            var departure_ids = response.map( element => element.column_name_id);
                            if(departure_ids.indexOf(3) == -1){
                                $('#nights_and_days').css('display','none');
                            }
                            else{
                                $('#nights_and_days').css('display','block');
                            }
                            // if(type_id == 4){
                            //     $('#ex_label').css('display','none');
                            // }
                            // else{
                            //     $('#ex_label').css('display','block');
                            // }
                            if(type_id == 4){
                                $('#start_date_lebel').text("Chek-In Date");
                                $('#return_date_lebel').text("Check-Out Date");
                                $('.ex_labels').css('display','none');
                            }
                            else if(type_id == 2){
                                $('#start_date_lebel').text("Start Date");
                                $('#return_date_lebel').text("End Date");
                                $('.ex_labels').css('display','block');
                            }
                            else{
                                $('#start_date_lebel').text("Departure Date");
                                $('#return_date_lebel').text("Return Date");
                                $('.ex_labels').css('display','block');
                            }
                        }
                    });
                });
            </script>

@endsection
