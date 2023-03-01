@extends('layouts.app')
@section('tagSection')
    <title>Payment Schedule | Departure Cloud</title>
@endsection

@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <style>
        .ui-datepicker {
            z-index: 9999999 !important;;
        }#notificationDropdownAlert{
            padding: 28 15px !important;
        } 
    </style>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Add Payment Schedule</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Terms of Payment</li>
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
                        <form action="" method="post" id="paymensechdule">
                            @csrf
                            <div class="row wrappers">
                                <div class="col-md-6 d-flex justify-content-between">
                                    <h5>Payment Schedule</h5>
                                    <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field"><i class="fas fa-plus" disable></i> Add More</a>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-3 pt-3">
                                    <div class="form-group">
                                        <label for="">Minimum Booking Price</label>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control pull-right start_date fromdate" value="" name="date[]" id="start_date" autocomplete="off">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Percentage(%)</label>
                                        <input type="text" class="form-control percentage" id="percentage" name="percentage[]" required>
                                        <span class="text-danger" id="percentage_error"></span>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="text-success" id="error" style="margin-left: 10px"></span>
                                </div>
                                <div class="col-md-12 text-right mt-2">
                                    <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-primary active" type="button" id="store_form_next"><i class="fa fa-save"></i> Save & Next</button>
                                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                    <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('footerSection')
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script type="text/javascript">
                $('#percentage').keyup(function () {
                    if ($(this).val() > 100) {
                        $(this).val('');
                    } else {
                        $('#percentage').text('');
                    }
                });
                var percentage = document.querySelector('#percentage');
                percentage.addEventListener('input', restrictNumber);

                function restrictNumber(e) {
                    var percent = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                    this.value = percent;
                }
            </script>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#store_form').click(function (e) {
                        e.preventDefault();
                        //alert('hello');percentage
                        <?php $count = 0; ?>
                        var percentage = $('#percentage').val();
                        if (percentage == "") {
                            $("span#percentage_error").html('This field is required!');
                            $("select#percentage").focus();
                            return false;
                        } else {
                            $("span#percentage_error").hide();
                        }
                        @for( $count = 1; $count < 11; $count++)
                        // var calender_{{$count}} = $('.calender_{{$count}}').val();
                        //     if (calender_{{$count}} == "") {
                        //        $("span#calender_error_{{$count}}").html('This field is required!');
                        //        $("select#calender_{{$count}}").focus();
                        //        return false;
                        //     }else{
                        //        $("span#calender_error_{{$count}}").hide();
                        //     }
                        var percentage_{{$count}} = $('#percent_{{$count}}').val();
                        if (percentage_{{$count}} == "") {
                            $("span#percent_error_{{$count}}").html('This field is required!');
                            $("select#percent_{{$count}}").focus();
                            return false;
                        } else {
                            $("span#percent_error_{{$count}}").hide();
                        }
                        @endfor
                        jQuery('#gif').show();

                        jQuery('#gif').css('visibility', 'visible');
                        var formDatas = new FormData(document.getElementById('paymensechdule'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{route('terms_payment_store',$id)}}",
                            data: formDatas,
                            contentType: false,
                            processData: false,

                            success: function (data) {
                                if (data.url) {
                                    window.location = data.url;
                                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                    $('#store_form').html('Please wait...')
                                    $('#store_form').prop('disabled', true);
                                } else {
                                    $('#error').html("<span class='sussecmsg text-danger'>Please make sure total percentage entry 100% !</span>");
                                    $('#gif').hide();
                                }
                                $('#gif').hide();
                            },
                            errors: function () {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                            }

                        });
                    });
                });

                jQuery(document).ready(function () {
                    jQuery('#store_form_next').click(function (e) {
                        e.preventDefault();
                        //alert('hello');percentage
                        <?php $count = 0; ?>
                        var percentage = $('#percentage').val();
                        if (percentage == "") {
                            $("span#percentage_error").html('This field is required!');
                            $("select#percentage").focus();
                            return false;
                        } else {
                            $("span#percentage_error").hide();
                        }
                        @for( $count = 1; $count < 11; $count++)
                        // var calender_{{$count}} = $('.calender_{{$count}}').val();
                        //     if (calender_{{$count}} == "") {
                        //        $("span#calender_error_{{$count}}").html('This field is required!');
                        //        $("select#calender_{{$count}}").focus();
                        //        return false;
                        //     }else{
                        //        $("span#calender_error_{{$count}}").hide();
                        //     }
                        var percentage_{{$count}} = $('#percent_{{$count}}').val();
                        if (percentage_{{$count}} == "") {
                            $("span#percent_error_{{$count}}").html('This field is required!');
                            $("select#percent_{{$count}}").focus();
                            return false;
                        } else {
                            $("span#percent_error_{{$count}}").hide();
                        }
                        @endfor
                        jQuery('#gif').show();

                        jQuery('#gif').css('visibility', 'visible');
                        var formDatas = new FormData(document.getElementById('paymensechdule'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{route('terms_payment_store',$id)}}",
                            data: formDatas,
                            contentType: false,
                            processData: false,

                            success: function (data) {
                                if (data.url) {
                                    window.location = data.url;
                                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                    $('#store_form_next').html('Please wait...')
                                    $('#store_form_next').prop('disabled', true);
                                } else {
                                    $('#error').html("<span class='sussecmsg text-danger'>Please make sure total percentage entry 100% !</span>");
                                    $('#gif').hide();
                                }
                                $('#gif').hide();
                            },
                            errors: function () {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                            }

                        });
                    });
                });
            </script>
            <script type="text/javascript">

                $(document).ready(function () {
                    var k = 0;
                    var maxFields = 20; //Input fields increment limitation
                    var addButtons = $('.add_button'); //Add button selector
                    var wrappers = $('.wrappers'); //Input field wrapper
                    //var fieldHTMLs = '';
                    var x = 1;
                    var total = 0;
                    $('#percentage').keyup(function () {
                        add_percentage();
                    });
                    $(addButtons).click(function () {

                        var calender = '"calender_' + x + '"';
                        var calender_error = '"calender_error_' + x + '"';

                        var percentage_id = 'percent_' + x;
                        var percentage__error = 'percent_error_' + x;
                        var fieldHTMLs = '<div class="col-md-12" id=#rowes"><div class="row"><div class="col-md-3" style=""><label>Until</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right start_date1 fromdate ' + calender + '" name="date[]"   autocomplete="off" required> <div class="input-group-prepend" > <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span></div><span class="text-danger"  id="' + calender_error + '"></span></div></div></div><div class="col-md-3"><label>Percentage(%)</label><div class="form-group"><input type="text" name="percentage[]"  class="form-control percentage2" id="' + percentage_id + '"><span class="text-danger" id="' + percentage__error + '"></span></div></div><div class="col-md-4" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button btn btn-outline-danger"><i class="fas fa-minus"></i> Remove</a></div></div></div></div>';
                        if (x < maxFields) {

                            x++;
                            $(wrappers).append(fieldHTMLs);

                            $('.start_date1').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                dateFormat: 'dd-M-yy',
                                // maxDate: new Date('{{$termsPayment->start_date}}'),
                                minDate: 0,
                            });
                            var percentage2 = document.querySelector('.percentage2');
                            percentage2.addEventListener('input', restrictNumber);

                            function restrictNumber(e) {
                                var percent2 = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                                this.value = percent2;
                            }

                            $('.fa-calendar').click(function () {
                                $(".start_date1").focus();
                            });
                            $('.percentage2').keyup(function () {
                                add_percentage();
                                var total_check = $('#total').val();
                                if (total_check > 10) {
                                    $(this).val(0);
                                } else {
                                    $('.percentage2').text('');
                                }
                            });

                        }
                    });

                    function add_percentage() {

                        var p_total = $('#percentage').val();

                        if (p_total) {
                            p_total = parseInt(p_total);
                        } else {
                            p_total = 0;
                        }
                        for (let i = 1; i < x; i++) {
                            v_perc = $('#percent_' + i).val();
                            if (v_perc) {
                                v_perc = parseInt(v_perc);
                            } else {
                                v_perc = 0;
                            }
                            p_total = p_total + v_perc;
                            console.log(p_total, 'jhgj');
                        }

                        var dis = $('#total').val(p_total);
                        if (dis == 100) {
                            $('.formlabelmargin').prop('disabled', true);
                        }
                    }


                    $(wrappers).on('click', '.remove_button', function (e) {
                        e.preventDefault();
                        $(".col-md-12").last().remove();

                        x--;
                    });
                });
            </script>

@endsection