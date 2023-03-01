@extends('layouts.app')
@section('tagSection')
<title>Booking History Details | Departure Cloud</title>
@endsection
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">

<style>
         #ui-datepicker-div{
            z-index: 999999999999999999 !important;
        }
        .ui-datepicker-buttonpane.ui-widget-content {
            display: none;
        }
        .allDetail-card {
            position: relative;
            border-radius: 6px;
            box-shadow: 0 0 4px rgb(0 0 0 / 23%);
        }

        .departure-card, .booking-card, .price-card, .payment-card {
            padding: 16px 8px;
            margin-bottom: 18px;
            /* height: calc(100% - 18px); */
        }

        .booking-card {
            padding: 16px 8px 10px;
        }

        .allDetail-card .card-name {
            position: absolute;
            right: 11px;
            top: -6px;
        }

        .allDetail-card .card-name span {
            font-size: 13px;
            font-weight: 600;
            padding: 2px 4px;
            box-shadow: 1px 1px 2px 0 rgb(0 0 0 / 70%);
        }

        .departure-card .card-name span {
            background-color: #076deb;
            color: #fff;
        }

        .booking-card .card-name span {
            background-color: #1ABC9C;
            color: #fff;
        }

        .price-card .card-name span {
            background-color: #4A81D4;
            color: #fff;
        }

        .payment-card .card-name span {
            background-color: #df3599;
            color: #fff;
        }

        .allDetail-card .card-name span:after {
            content: "";
            position: absolute;
            z-index: 1;
            border-top: 3px solid transparent;
            border-right: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-left: 3px solid transparent;
        }

        .allDetail-card .card-name span:after {
            right: -6px;
            top: 0;
        }

        .departure-card .card-name span:after {
            border-left: 3px solid #3827c1;
            border-bottom: 3px solid #3827c1;
        }

        .booking-card .card-name span:after {
            border-left: 3px solid #148f77;
            border-bottom: 3px solid #148f77;
        }

        .price-card .card-name span:after {
            border-left: 3px solid #2d67be;
            border-bottom: 3px solid #2d67be;
        }

        .price-card .card-name img {
            width: 10px;
        }

        .payment-card .card-name span:after {
            border-left: 3px solid #9f206a;
            border-bottom: 3px solid #9f206a;
        }

        .booking-card .unit, .booking-card .price, .payment-card .price {
            font-size: 18px;
            font-weight: 700;
            line-height: 20px;
        }

        .payment-card .price span {
            font-size: 16px;
            font-weight: 600;
        }

        .payment-card .inst-amt {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            font-weight: 700;
            color: #a1a1a1;
        }

        .payment-card .inst-amt span {
            font-size: 14px;
            font-weight: 600;
        }

        .payment-card .inst-amt span small {
            font-weight: 600;
        }

        .payment-card .inst-amt.end-period {
            color: #E8A84C;
        }

        .booking-card .unit span, .booking-card .price span {
            font-size: 13px;
            line-height: 13px;
            font-weight: 500;
            display: block;
        }

        .booking-card .date-time {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            background-color: #F9F9F9;
            padding: 10px 18px;
            margin-top: 6px;
            border: 2px dashed #7fb9ad;
            border-radius: 6px;
        }

        .departure-card .title {
            font-size: 16px;
            margin: 0;
            font-weight: 600;
            color: #444655;
        }

        .departure-card .id {
            font-size: 13px;
            margin: 0;
            font-weight: 600;
            color: #348bf7;
        }

        .departure-card .dept-from-text, .departure-card .dept-to-text {
            max-width: 120px;
            text-transform: uppercase;
            font-size: 14px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .departure-card .dept-from-to {
            margin: 2px 16px 8px;
            position: relative;
            top: 10px;
            border-top: 1px solid #8f8f8f;
            width: calc(100% - 39px);
        }

        .departure-card .dept-from-to:before, .departure-card .dept-from-to:after {
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

        .departure-card .dept-from-to:after {
            left: unset;
            right: -12px;
        }

        .departure-card .no-of-flights {
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
        }

        .departure-card .destination-name p {
            margin: 0 0 0 16px;
            font-size: 13px;
            line-height: 14px;
            font-weight: 700;
            color: #939393;
            position: relative;
            display: inline-block;
        }

        .departure-card .destination-name p:first-child {
            margin-left: 6px;
        }

        .departure-card .destination-name p:not(:first-child):before {
            content: "";
            width: 10px;
            height: 10px;
            position: absolute;
            background-color: white;
            border: 3px solid #b1b1b1;
            border-radius: 50%;
            left: -12px;
        }
</style>

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Booking Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                   <div class="col-md-12">
                                <h2>Departure Details</h2>
                                <div class="d-flex">
                                    <picture class="w-30" style="height: 420px;overflow: hidden;">

                                    @if(isset($itinerary->description))
                                     <img src="{{ asset('ScreenShot') . '/' . $itinerary->description }}" alt="Departure-Img" style="width:100%" class="img-fluid">
                                    @endif
                                    </picture>
                                    <div class="packageDetails w-70 pl-md-4">
                                        <h3 class="title">{{ucfirst($departure->title)}}
                                        @foreach($destination as $row)
                                            <small class="destination-name text-blue">{{$row->dest_name}} ({{$row->country_name}})</small>
                                        @endforeach
                                        </h3>
                                        <p class="id mb-1"><a href="javascript:void(0);" class="departureID font-16">ID {{ $departure->dep_id }}</a></p>
                                        @if(Auth::user()->id != $departure->user_id)
                                        <div class="">
                                            Supplier <a href="{{$url}}">{{$departure->departure_ownner}}</a>
                                        </div>
                                        @endif
                                        <div class="destinations d-flex align-items-center text-uppercase mb-2 font-weight-bold">
                                            <img src="{{asset('/assets1/images/destination.png')}}" alt="map-icon" width="17" class="mr-1">{{$departure->from }} - {{$departure->ending_at }}
                                        </div>
                                        <p class="duration mb-1"><strong>Duration: -</strong> {{$departure->no_of_nights}}N</p>
                                        <div class="mt-2 allDetail-card booking-card">
                                            <div class="card-name" style="right: auto;">
                                                <span>Booking Details</span>
                                            </div>
                                            <div class="d-flex justify-content-between" style="margin: 12px 0 6px;">
                                                <div class="unit">

                                                    <span><a href="{{route('buyer_profile',$user->tenant_id)}}"> {{$user->company_name}}</a></span>
                                                </div>
                                                <div class="unit">
                                                    <span><a href="{{route('buyer_profile',$user->tenant_id)}}"> {{$user->full_name}}</a></span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between pt-2">
                                                <div class="unit">
                                                    {{$booking_unit}}
                                                    <span>Total Units</span>
                                                </div>
                                                <div class="price text-right">
                                                    {{$booking_details->currency_symbol}} {{$booking_price}}
                                                    <span>Total Price</span>
                                                </div>
                                            </div>
                                            <div class="date-time">
                                                <span class="mr-1">Booked Date</span>
                                                <strong>{{date('d-M-Y h:ia', strtotime($booking_details->created_at."+5 hours +30 minutes"))}}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 


<!-- start -->
                        <div class="col-md-12 mt-3">
                            <h4>Payment Schedule</h4>
                        </div>

                        <div class="col-md-12">
                                <?php $count = 0;?>
                                    @foreach($departure_prices as $key=>$price)
                                    <?php
                                    if ($booking_details->date > $price->date) {
                                        $count = $count + $price->percentage;
                                    } ?> 
                                    @endforeach
                                <div class="payment-card-1 bg-danger text-white">
                                    Minimum Booking Price
                                    <strong class="p-amount text-white">
                                        <?php echo $booking_details->currency_symbol . number_format(($booking_price * $count) / 100); ?>
                                    </strong>
                                </div>
                                @foreach($departure_prices as $key=>$price)
                                    @if($key == 0)
                                        @if($booking_details->date < $price->date)
                                            <div class="payment-card-1 bg-danger text-white">
                                                Minimum Booking Price
                                                <strong class="p-amount text-white"> {{$booking_details->currency_symbol}} {{number_format(($booking_price * $price->percentage)/100)}}
                                                </strong>
                                            </div>
                                        @endif
                                    @endif
                                    @if($key > 0)
                                        @if($key == 0)
                                        @endif
                                        @if($booking_details->date > $price->date)
                                            <div class="payment-card-1">
                                                <div style="border-bottom: 1px solid #fb8181;">
                                                {{date('d M, Y', strtotime($price->date))}} |
                                                <strong class="p-amount" >{{$booking_details->currency_symbol}} {{number_format(($booking_price * $price->percentage)/100)}} <i class="fa fa-times" aria-hidden="true" style="color:#f1556c"></i></strong>
                                                </div>
                                            </div>
                                        @else
                                            <div class="payment-card-1">
                                                {{date('d M, Y', strtotime($price->date))}} |
                                                <strong class="p-amount"> {{$booking_details->currency_symbol}} {{number_format(($booking_price * $price->percentage)/100)}}</strong>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                @if($tenant_id == Auth::user()->tenant_id)
                                    <div class="" style="margin: 12px 0 4px;border-top: 2px dashed #ccc;padding: 12px;">
                                        <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target=".bd-example-modal-sm">Update Price</button>
                                    </div>
                                    @if(count($update_price)>0)
                                    @foreach($update_price as $update)
                                    
                                        <div class="payment-card-1 bg-success text-white" style="min-width:140px">
                                            <span>{{date('d M, Y', strtotime($update->date))}}</span>
                                            <strong class="p-amount text-white">
                                                {{$booking_details->currency_symbol}}  {{$update->price}}
                                            </strong> 
                                        </div>
                                    
                                    @endforeach
                                    
                                    @endif
                                @else
                                <div class="justify-content-between" style="text-align: center; margin: 12px 0 4px;border-top: 2px dashed #ccc;padding: 12px;">
                                    <div class="payment-card-1 bg-success text-white" style="min-width:140px">
                                        <span>Paid </span>
                                        <strong class="p-amount text-white">
                                            {{$booking_details->currency_symbol}} {{$update_price_sum}}
                                        </strong> 
                                    </div>
                                    <div class="payment-card-1 bg-danger text-white">
                                        <span>Balance </span>
                                        <strong class="p-amount text-white">
                                            {{$booking_details->currency_symbol}} {{$booking_price - $update_price_sum}}
                                        </strong> 
                                    </div>
                                </div>
                                @endif
                    </div> 
<!-- end -->
                         <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="card-body p-0">
                                    <div class="user-info">
                                        <h5 class="card-user_name"><b>Price Details</b></h5>
                                        <div class="table-responsive">
                                            <table id="" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Sharing</th>
                                                    <th>Transport Type</th>
                                                    <th>Hotel Name</th>
                                                    <th>Hotel Type</th>
                                                    <th>Meal Plan</th>
                                                    <th>Minimum Pax</th>
                                                    <th>Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $total = 0; ?>
                                                @foreach($price_details as $row)
                                                    <tr>
                                                        <?php $total = $total + $row->price / $row->booked_seat * $row->booked_seat;?>
                                                        <td>
                                                            {{ucfirst($row->sairing)}}
                                                        </td>
                                                        <td>
                                                            {{$row->transport_type}}
                                                        </td>
                                                        <td>
                                                            {{$row->hotel_name}}
                                                        </td>
                                                        <td>
                                                            {{$row->hotel_type}}
                                                        </td>
                                                        <td>
                                                            {{$row->meal_plan}}
                                                        </td>
                                                        <td>
                                                            {{$row->group_size}}
                                                        </td>
                                                        <td>
                                                            {{$booking_details->currency_symbol}} {{$row->price/$row->booked_seat}} * {{$row->booked_seat}} = {{$booking_details->currency_symbol}} {{number_format($row->price/$row->booked_seat * $row->booked_seat)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-right"><strong class="font20">Total Price :</strong></td>
                                                    <td style="border-top:2px solid #333;">
                                                        <strong class="font20"><?php echo $booking_details->currency_symbol . number_format($total)?></strong>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<!---- Model----->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-mb " role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title text-white" id="mySmallModalLabel">Update Price</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
      </div>
      <form role="form" id="BookDepartureForm">
        @csrf
        <input type="hidden" name="booking_unique_id" value="{{$id}}" id="booking_unique_id">
        <input type="hidden" name="departure_id" value="{{$departure->id}}" id="user_id">
        <input type="hidden" name="user_id" value="{{$user->id}}" id="departure_id">
        <div class="modal-body"> 
          <div class="row"> 
            <div class="col-md-12">
              <div class="form-group">
                <label for="exampleFormControlInput1">Price<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="price" name="price" value="" autocomplete="off">
                <span id="price_error" class="text-danger"></span>
              </div> 
            </div>
           
            <div class="col-md-12">
                <div class="form-group">
                    <label for="start_date">Departure Date<span class="text-danger">*</span></label>
                    <div class="input-group date">
                        <input type="text" class="form-control pull-right start_date fromdate" value="" name="date" id="date" autocomplete="off">
                        <div class="input-group-prepend" >
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <span class="validationError text-danger" id="date_error"></span>
                </div>
            </div>
          </div> 
        </div>
        <div class="modal-footer" >
          <div class="col-md-12 text-right">
              <img src="{{ asset('images/loader.gif') }}" id="gif_book" style="width: 8%;  visibility: hidden;">
              <span class="text-success" id="mesegese_book" style="margin-left: 10px"></span>
              <button class="btn btn-primary active " type="button" id="store_form_book"><i class="fa fa-save"></i>Update</button>
              <button class="btn btn-secondary" data-dismiss="modal" id=""><i class="flaticon-cancel-12"></i> Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!----End model --->
@endsection
@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function () {
   $('#store_form_book').click(function (e) {
       e.preventDefault();
       $('#gif_book').show();
       $('#gif_book').css('visibility', 'visible');
       var price = $('#price').val();
           if (price == "") {
              //$("span#title_error").hide();
               $("span#price_error").html('This field is required!');
               $("input#price").focus();
               return false;
           }else{
               $("span#price_error").hide();
           }
       var date = $('#date').val();
           if (date == "") {
              //$("span#title_error").hide();
               $("span#date_error").html('This field is required!');
               $("input#date").focus();
               return false;
           }else{
               $("span#date_error").hide();
           }
       var formDatas = new FormData(document.getElementById('BookDepartureForm'));
       $.ajax({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           method: 'POST',
           url: "{{route('booking_price_update')}}",
           data: formDatas,
           contentType: false,
           processData: false,
           success: function (data) {
            $('#gif_book').hide();  
            $('#mesegese_book').html("<span class='sussecmsg'>success</span>");
              window.location.reload();
            },
           errors: function () {
             $('#gif_book').hide();
             $('#mesegese_book').html("<span class='sussecmsg'>Something went wrong!</span>");
           }
       });
   });
});
$("#price").on("keypress keyup blur",function (event) {    
   $(this).val($(this).val().replace(/[^\d].+/, ""));
    if ((event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});
</script>
<script>
$( document ).ready(function() {
  $('#date').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd-M-yy',
    //minDate: 0,
  });
  $('.fa-calendar').click(function() {
        $(".start_date").focus();
    });
});

</script>
<style>
.modal-dialog {
   max-width: 350px;
   width: 950px;
   margin: 0 auto;
}
.modal {
    left: unset;
    padding-right: 0 !important;
}
.modal.fade .modal-dialog {
    -webkit-transform: translate(25%,0);
    transform: translate(25%,0);
}
.modal.show .modal-dialog {
    -webkit-transform: translate(0,0);
    transform: translate(0,0);
}
.modal-content {
    background-color: #fdfdfd;
    border-radius: 0;
    min-height: 100vh;
}
.modal-backdrop.show {
    opacity: .7;
}
  .card-title{
    line-height:24px;
    margin-bottom: 0;
    text-overflow: ellipsis;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
  }
  .bg-dept{
    background-color: #E3F7F5;
    margin: 0 -1rem;
    padding: 8px 12px;
    border-radius: 6px;
  }
  .bg-per-pax{
    background-color: #F9F9F9;
    padding: 16px 12px;
    position: absolute;
    width: calc(100% - 16px);
    bottom:  10px;
  }
  .dept-from-to{
    margin: 2px 16px 8px;
    position: relative;
    top: 8px;
    border-top: 1px solid #8f8f8f;
    width: calc(100% - 39px);

  }
  .dept-from-to:before,.dept-from-to:after{
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
  .dept-from-to:after{
    left: unset;
    right: -12px;
  }
  .dept-from-text, .dept-from-text-{
    text-transform: uppercase;
    font-size: 14px;    
    margin-bottom: 0;
    font-weight: 600;
  }
  .dept-from-text{
    max-width:120px;
  }
  .dept-from-text-{
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 6px
  }
  .dept-from-text- span{
    font-weight: 600;
    margin-left: 6px;
  }
  .price-set, .unit-set li{
    font-size: 18px;
    line-height: 24px;
    font-weight: 900;
    color: #000;
    text-align: right;
    margin-bottom: 0;
  }
  .price-set span, .unit-set li span{
    color: #9b9b9b;
    font-size: 11px;
    line-height: 11px;
    display: block;
    font-weight: 500;
  }
  .unit-set{
    list-style: none;
    display: flex;
    padding: 0;
    margin: 0;
  }
  .unit-set li{
    font-size: 16px;
    font-weight: 700;
    text-align: center;
    margin-right: 16px;
  }
  .dep-model-action-btn a{
    padding: 1px 2px;
    display: inline-block;
    position: relative;
  }
  .dep-model-action-btn a:hover:after{
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
  }
  .dep-model-action-btn a:first-child:hover:after{
      width: 108px;
      left: -42px;
  }
  .dep-model-action-btn a:hover:after{
    width: 85px;
    left: -40px;
  } 
  .dep-model-action-btn a:hover:before{
    border: solid;
    border-color: #444 transparent;
    border-width: 8px 4px 0 4px;
    content: "";
    left: 6px;
    top: -6px;
    position: absolute;
    z-index: 99;
  }
  .ribbon-box .ribbon{
    padding: 4px 8px;
    line-height: 13px;
  }
  .ribbon-style{
    position:absolute;top: 7px;px;right:24px;
  }
  .card-box{
    height:  calc(100% - 24px);
    padding-bottom: 100px;
  }
  .form-control:disabled, .form-control[readonly] {
    background-color: #fff;
  }
  .modal-header{
    height: 61px;
    align-items: center;
    background-color: #093E8E;
    margin-top: 70px;
    border-radius: 0;
  }
  .hold, form, .form-group, label{
    line-height: 1.2;
  }
</style>
@endsection