@extends('layouts.app')
@section('tagSection')
    <title>Payment Schedule | Departure Cloud</title>
@endsection

@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <style>
        .ui-datepicker {
            z-index: 9999999 !important;;
        }

        .ui-datepicker-buttonpane {
            display: none;
        }
        #notificationDropdownAlert{
            padding: 28 15px !important;
        } 
    </style>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Edit Payment Schedule</h4>
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
                        <form action="" id="paymensechdule">
                            @csrf
                            <input type="hidden" name="old_price" value="{{$price}}">
                            <input type="hidden" name="dep_id" value="{{$termsPayment->id}}">
                            <div class="row ">
                                <div class="col-md-6 d-flex justify-content-between">
                                    <h5>Payment Schedule</h5>
                                    <a href="javascript:void(0);" class="add_button btn btn-primary add-btn-style1" title="Add field"><i class="fas fa-plus"></i> Add More</a>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                @foreach($payment as $key=>$row)
                                    @if($key == 0)
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3 mt-3 ">
                                                    <div class="form-group ">
                                                        <label for="">Minimum Booking Price</label>
                                                        <input type="hidden" name="date[]">
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Percentage(%)</label>
                                                        <input type="text" class="form-control percentage" id="percentage{{$key}}" name="percentage[]" value="{{$row->percentage}}">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-md-12 wrappers">

                                    @foreach($payment as $key=>$row)
                                        @if($key >0)
                                            <div class="row ">
                                                <div class="col-md-3 " id="remove_date{{$key}}">
                                                    <div class="form-group">
                                                        <label for="" class="">Until</label>
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control pull-right start_date fromdate" name="date[]" id="start_date{{$key}}" autocomplete="off" value="{{date('d M, Y', strtotime($row->date))}}">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true" id="fa-calendar{{$key}}"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id="remove_percentage{{$key}}">
                                                    <div class="form-group">
                                                        <label for="">Percentage(%)</label>
                                                        <input type="text" class="form-control percentage" id="percentage{{$key}}" name="percentage[]" value="{{$row->percentage}}">
                                                    </div>
                                                </div>
                                                <div class="" style="margin-top: 1.8rem!important; margin-left: 5px;" id="remove_buttons{{$key}}">
                                                    <div class="form-group">
                                                        <a class="btn btn-outline-danger text-danger" id="remove_input{{$key}}"><i class="fas fa-minus"> Remove</i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach


                                </div>
                            </div>
                            <div class="row">
                                <span class="text-success" id="error" style="margin-left: 10px"></span>
                                <!-- <div class="col-md-1">
                                </div>
                                   <div class="col-md-3">
                                      <div class="form-group">
                                          <div class="d-flex">
                                              <div class="input-group-prepend ">
                                                <label for="" class="input-group-text">Total Percentage</label>
                                              </div>
                                              <input type="text" class="form-control"  id="total" readonly>
                                          </div>
                                      </div>
                                  </div> -->
                            </div>
                            <div class="col-md-12 text-right mt-3">
                                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
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
                @foreach($payment as $key=>$row)
                $('#remove_input{{$key}}').click(function () {
                    //Check maximum number of input fields
                    $('#remove_date{{$key}}').remove();
                    $('#remove_percentage{{$key}}').remove();
                    $('#remove_buttons{{$key}}').remove();
                });
                $(document).ready(function () {
                    $("#start_date{{$key}}").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'dd-M-yy',
                        //maxDate: new Date('{{$termsPayment->start_date}}'),
                        minDate: 0,

                    });

                    $('#fa-calendar{{$key}}').click(function () {
                        $("#start_date{{$key}}").focus();
                    });
                });
                $('#percentage{{$key}}').keyup(function () {
                    if ($(this).val() > 100) {
                        $(this).val('100');
                    } else {
                        $('#percentage{{$key}}').text('');
                    }
                });
                $('#percentage{{$key}}').ready(function () {
                    add_percentage();
                });
                @endforeach

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

                    $(addButtons).click(function () {

                        var currentPerceid = '"percentage' + x + '"';
                        var currentPrice = '"price' + x + '"';
                        var percentage_id = 'percent_' + x;
                        var fieldHTMLs = '<div class="col-md-12 " id="rowes"><div class="row"><div class="col-md-3" style=""><label>Until</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right start_date1 fromdate" value="" name="date[]" id="" autocomplete="off" required> <div class="input-group-prepend" > <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span></div></div></div></div><div class="col-md-3"><label>Percentage(%)</label><div class="form-group"><input type="text" name="percentage[]"  class="form-control percentage2" id="' + percentage_id + '" required></div></div><div class="col-md-3" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button btn btn-outline-danger"><i class="fas fa-minus"></i> Remove</a></div></div></div></div>';
                        if (x < maxFields) {

                            x++;
                            $(wrappers).append(fieldHTMLs);

                            $('.start_date1').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                showButtonPanel: true,
                                dateFormat: 'dd-M-yy',
                                //maxDate: new Date('{{$termsPayment->start_date}}'),
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
                                if (total_check > 100) {
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
            <style type="text/css">
                @media only screen and (min-width: 768px) {
                    .add-btn-style {
                        position: absolute;
                        right: -20px;
                    }
                }


            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#store_form').click(function (e) {
                        e.preventDefault();
                        //alert('hello');
                        jQuery('#gif').show();

                        jQuery('#gif').css('visibility', 'visible');
                        var formDatas = new FormData(document.getElementById('paymensechdule'));
                        jQuery.ajax({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{url('/terms/payment/update/'.$id)}}",
                            data: formDatas,
                            contentType: false,
                            processData: false,

                            success: function (data) {
                                if (data.url) {
                                    window.location = data.url;
                                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                    jQuery('#store_form').html('Please wait...')
                                    jQuery('#store_form').prop('disabled', true);
                                } else {
                                    $('#error').html("<span class='sussecmsg text-danger'>Please make sure total percentage entry 100% !</span>");
                                    $('#gif').hide();
                                }
                                $('#gif').hide();
                            },
                            errors: function () {
                                jQuery('#gif').hide();
                                jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                            }

                        });
                    });
                });
            </script>
@endsection