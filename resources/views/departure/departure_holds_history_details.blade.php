@extends('layouts.app')
@section('tagSection')
<title>Hold History Details | Departure Cloud</title>
@endsection
@section('content')
<style>
    .allDetail-card{
        position: relative;
        border-radius: 6px;
        box-shadow: 0 0 4px rgb(0 0 0 / 23%);
    }
    .departure-card, .booking-card, .price-card, .payment-card{
        padding: 16px 8px;
        margin-bottom: 18px;
        /* height: calc(100% - 18px); */
    }
    .booking-card{
        padding: 16px 8px 10px;
    }
    .allDetail-card .card-name{
        position: absolute;
        right: 11px;
        top: -6px;
    }
    .allDetail-card .card-name span{
        font-size: 13px;
        font-weight: 600;
        padding: 2px 4px;
        box-shadow: 1px 1px 2px 0 rgb(0 0 0 / 70%);
    }
    .departure-card .card-name span{
        background-color: #076deb;
        color: #fff;
    }
    .booking-card .card-name span{
        background-color: #1ABC9C;
        color: #fff;
    }
    .price-card .card-name span{
        background-color: #4A81D4;
        color: #fff;
    }
    .payment-card .card-name span{
        background-color: #df3599;
        color: #fff;
    }
    .allDetail-card .card-name span:after{
        content: "";
        position: absolute;
        z-index: 1;
        border-top: 3px solid transparent;
        border-right: 3px solid transparent;
        border-bottom: 3px solid transparent;
        border-left: 3px solid transparent;
    }
    .allDetail-card .card-name span:after{
        right: -6px;
        top: 0;
    }
    .departure-card .card-name span:after{
        border-left: 3px solid #3827c1;
        border-bottom: 3px solid #3827c1;
    }
    .booking-card .card-name span:after{
        border-left: 3px solid #148f77;
        border-bottom: 3px solid #148f77;
    }
    .price-card .card-name span:after{
        border-left: 3px solid #2d67be;
        border-bottom: 3px solid #2d67be;
    }
    .price-card .card-name img{
        width: 10px;
    }
    .payment-card .card-name span:after{
        border-left: 3px solid #9f206a;
        border-bottom: 3px solid #9f206a;
    }
    .booking-card .unit, .booking-card .price, .payment-card .price{
        font-size: 18px;
        font-weight: 700;
        line-height: 20px;
    }
    .payment-card .price span{
        font-size: 16px;
        font-weight: 600;
    }
    .payment-card .inst-amt{
        display: flex;
        justify-content: space-between;
        font-size: 15px;
        font-weight: 700;
        color: #a1a1a1;
    }
    .payment-card .inst-amt span{
        font-size: 14px;
        font-weight: 600;
    }
    .payment-card .inst-amt span small{font-weight: 600;}
    .payment-card .inst-amt.end-period{
        color: #E8A84C;
    }
    .booking-card .unit span, .booking-card .price span{
        font-size: 13px;
        line-height: 13px;
        font-weight: 500;
        display: block;
    }
    .booking-card .date-time{
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        background-color: #F9F9F9;
        padding: 10px 18px;
        margin-top: 6px;
        border: 2px dashed #7fb9ad;
        border-radius: 6px;
    }
    .booking-card .date-till{
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        background-color: #F9F9F9;
        padding: 10px 18px;
        margin-top: 6px;
        border: 2px dashed red;
        border-radius: 6px;
    }
    .departure-card .title{
        font-size: 16px;
        margin: 0;
        font-weight: 600;
        color: #444655;
    }
    .departure-card .id{
        font-size: 13px;
        margin: 0;
        font-weight: 600;
        color: #348bf7;
    }
    .departure-card .dept-from-text, .departure-card .dept-to-text{
        max-width: 120px;
        text-transform: uppercase;
        font-size: 14px;
        margin-bottom: 0;
        font-weight: 600;
    }
    .departure-card .dept-from-to{
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
    .departure-card .no-of-flights{
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
    }
    .departure-card .destination-name p{
        margin: 0 0 0 16px;
        font-size: 13px;
        line-height: 14px;
        font-weight: 700;
        color: #939393;
        position: relative;
        display: inline-block;
    }
    .departure-card .destination-name p:first-child{
        margin-left: 6px;
    }
    .departure-card .destination-name p:not(:first-child):before{
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
                        <h4 class="page-title">Hold Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Hold</li>
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
                                    <div class="col-md-4">
                                        <div class="allDetail-card departure-card">
                                            <div class="card-name">
                                                <span>Departure Details</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex flex-column">
                                                    <h4 class="title">
                                                    {{ucfirst($departure->title)}}
                                                    </h4>
                                                    <div class="id">
                                                        ID {{ $departure->dep_id }}
                                                    </div>
                                                    @if(Auth::user()->id != $departure->user_id)
                                                    <div class="">
                                                        Supplier <a href="">{{$departure->departure_ownner}}</a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between position-relative my-1">
                                                <div>
                                                    <p class="dept-from-text">{{$departure->from }}</p>
                                                </div>
                                                <div class="position-relative" style="width: 34%;">
                                                    <div class="dept-from-to"></div>
                                                    <div class="no-of-flights">
                                                        <i class="mdi mdi-airplane-takeoff"></i>
                                                        <span>{{$departure->no_of_nights}}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="dept-to-text">{{$departure->ending_at }}</p>
                                                </div>
                                            </div>
                                            <div class="d-flex ">
                                                <i class="fas fa-map-pin" style="font-size: 18px;color: #2985f7;"></i>
                                                    <div class="destination-name">
                                                        @foreach($destination as $row)
                                                        <p>{{$row->dest_name}}({{$row->country_name}})</p>
                                                        @endforeach
                                                    </div>
                                            </div>
                                            <div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="allDetail-card booking-card">
                                            <div class="card-name">
                                                <span>Hold Details</span>
                                            </div>
                                            @if(Auth::user()->id == $departure->user_id)
                                            <div class="d-flex justify-content-between" style="margin: 12px 0 6px;">
                                                <div class="unit">

                                                    <span><a href="{{route('buyer_profile',$user->tenant_id)}}"> {{$user->company_name}}</a></span>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="d-flex justify-content-between" style="margin: 12px 0 6px;">
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
                                                <span class="mr-1">Hold Date & Time</span>
                                                <strong>{{date('d-M-Y h:ia', strtotime($booking_details->created_at."+5 hours +30 minutes"))}}</strong>
                                            </div>
                                            <div class="date-till">
                                                <span class="mr-1">Hold Till</span>
                                                <strong>{{date('d-M-Y h:ia', strtotime($booking_details->auto_release."+2 minutes"))}}</strong>
                                            </div>
                                        </div>      
                                    </div>
                                    <div class="col-md-4">
                                        <div class="allDetail-card payment-card">
                                            <div class="card-name">
                                                    <span>Payment Schedule</span>
                                                </div>
                                                <div class="price d-flex justify-content-between" style="margin: 12px 0 4px;border-bottom: 2px dashed #ccc;padding-bottom: 4px;">
                                                    <?php $count = 0;?>
                                                    @foreach($departure_prices as $key=>$price)
                                                        <?php  
                                                                if($booking_details->date > $price->date)
                                                                {
                                                                $count = $count + $price->percentage;
                                                                }
                                                        ?>
                                                    @endforeach

                                                    <?php
                                                        echo '<span>Minimum Booking Amount</span>'.$booking_details->currency_symbol .number_format(($booking_price * $count)/100);
                                                    ?>
                                                </div>
                                                @foreach($departure_prices as $key=>$price)
                                                    @if($key == 0)
                                                        @if($booking_details->date < $price->date)
                                                            <li class="list-group-item bg-secondary text-light"><b>Minimum Booking Amount</b> : {{$booking_details->currency_symbol}} {{number_format(($booking_price * $price->percentage)/100)}}</li>
                                                        @endif  
                                                    @endif
                                                    @if($key > 0)

                                                    @if($key == 0)
                                                    @endif

                                                    @if($booking_details->date > $price->date)
                                                        <div class="inst-amt text-danger">
                                                            <span> <small>{{date('d M, Y', strtotime($price->date))}}</small></span>{{$booking_details->currency_symbol}} {{number_format(($booking_price * $price->percentage)/100)}}
                                                        </div>
                                                    @else
                                                        <div class="inst-amt">
                                                            <span><small>{{date('d M, Y', strtotime($price->date))}}</small></span>{{$booking_details->currency_symbol}} {{number_format(($booking_price * $price->percentage)/100)}}
                                                        </div>
                                                    @endif
                                                       
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="card-body p-0">
                                    <div class="user-info">
                                        <h5 class="card-user_name"><b>Price Details</b></h5>
                                        <div class="table-responsive">
                                        <table id="" class="table table-bordered table-striped">
                                            <thead >
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
                                             <?php $total = $total + $row->price/$row->hold_seat * $row->hold_seat;?>
                                                <tr>
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
                                                        {{$booking_details->currency_symbol}} {{$row->price/$row->hold_seat}} * {{$row->hold_seat}} = {{$booking_details->currency_symbol}} {{number_format($row->price/$row->hold_seat * $row->hold_seat)}}
                                                    </td>
                                                </tr>
                                           @endforeach
                                                <tr>
                                                    <td colspan="5">
                                                    </td>
                                                    <td>
                                                       <?php  echo 'Total Price</b> : '.$booking_details->currency_symbol .number_format($total);
?>
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

@endsection
@section('footerSection')

@endsection