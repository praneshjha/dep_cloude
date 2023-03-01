<!-- <style type="text/css">
.tab-departure-rmenu{
  /*background-color: #0D6EFD !important;
  border-color: #0D6EFD !important;
  box-shadow: none;*/
}
.tab-departure-rmenu:hover{
  border-bottom: 2px solid black!important;
}
.tab-departure-active{
  border-bottom: 2px solid #076deb!important;
}
</style> -->
<?php
$payment = DB::table('payment_schedules')->where('departure_id', request()->route('id'))->get();
$departure_types = DB::table('departures')->where('id', request()->route('id'))->first();
?>

<ul class="nav nav-tabs nav-bordered">
    @if(request()->route('id'))
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="{{route('departure_edit',request()->route('id'))}}">
                <span class="Dep_basic_details step">Basic Details</span>
            </a>
        </li>
        <li class="nav-item" id="active">
            <a class="nav-link " href="{{route('inclusion',request()->route('id'))}}">
        <span class="inclusions step">Inclusions
        </span>
            </a>
        </li>
        @if($departure_types->departure_type != 5)
            <li class="nav-item" id="active">
                <a class="nav-link " href="{{route('hotel_create',request()->route('id'))}}">
                    <span class="inclusions step">Hotels</span>
                </a>
            </li>
        @endif
        @if($departure_types->departure_type != 4 && $departure_types->departure_type != 2)
            <li id="active" class="nav-item">
                <a class="nav-link" id="activated" href="{{route('flight_create',request()->route('id'))}}">
                    <span class="Dep_basic_details step">Flights</span>
                </a>
            </li>
        @endif
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="{{route('itinerary_create',request()->route('id'))}}">
                <span class="Dep_basic_details step">Day Schedule</span>
            </a>
        </li>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="{{route('pdf_itinerary',request()->route('id'))}}">
                <span class="Dep_basic_details step">Itinerary</span>
            </a>
        </li>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="{{route('pricing_create',request()->route('id'))}}">
                <span class="Dep_basic_details step">Pricing</span>
            </a>
        </li>
        <li class="nav-item" id="active">
            <a class="nav-link " href="{{route('terms_payment_create',request()->route('id'))}}">
                <span class="inclusions step">Payment Schedule</span>
            </a>
        </li>
        @if(count($payment) > 0)
            <li class="nav-item" id="active">
                <a class="nav-link " href="{{route('cancelation_create',request()->route('id'))}}" class="">
                    <span class="inclusions step">Cancelation Schedule</span>
                </a>
            </li>
        @else
            <li class="nav-item check_payment" id="">
                <a class="nav-link " href="#" class="">
                    <span class="inclusions step">Cancelation Schedule</span>
                </a>
            </li>
        @endif
        <li class="nav-item" id="active">
            <a class="nav-link " href="{{route('terms_create',request()->route('id'))}}">
                <span class="inclusions step">Terms</span>
            </a>
        </li>
    @else
        <li class="nav-item tab-departure active" id="active">
            <a class="nav-link tab-departure-rmenu tab-departure-active"
               href="{{route('departure_create',request()->route('departure_type_id'))}}">
                <span class="Dep_basic_details step">Basic Details</span>
            </a>
        </li>
        <li class="nav-item tab-departure" id="active">
            <a class="nav-link tab-departure-rmenu" href="javascript:void(0);">
                <span class="inclusions step">Inclusions</span>
            </a>
        </li>
        @if( request()->route('departure_type_id') != 5)
            <li class="nav-item tab-departure active" id="active">
                <a class="nav-link tab-departure-rmenu tab-departure-active" href="javascript:void(0);">
                    <span class="Dep_basic_details step">Hotel</span>
                </a>
            </li>
        @endif
        @if(request()->route('departure_type_id') != 4 && request()->route('departure_type_id') != 2)
            <li class="nav-item tab-departure active" id="active">
                <a class="nav-link tab-departure-rmenu tab-departure-active" href="javascript:void(0);">
                    <span class="Dep_basic_details step">Flights</span>
                </a>
            </li>
        @endif
        <li class="nav-item tab-departure" id="active">
            <a class="nav-link tab-departure-rmenu" href="javascript:void(0);">
                <span class="itinerarymenu step">Day Schedule</span>
            </a>
        </li>
        <li class="nav-item tab-departure" id="active">
            <a class="nav-link tab-departure-rmenu" href="javascript:void(0);">
                <span class="itinerarymenu step">Itinerary</span>
            </a>
        </li>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="javascript:void(0);">
                <span class="Dep_basic_details step">Pricing</span>
            </a>
        </li>
        <li class="nav-item tab-departure" id="active">
            <a class="nav-link tab-departure-rmenu" href="javascript:void(0);">
                <span class="itinerarymenu step">Payment Schedule</span>
            </a>
        </li>
        <li class="nav-item tab-departure" id="active">
            <a class="nav-link tab-departure-rmenu" href="javascript:void(0);">
                <span class="itinerarymenu step">Cancelation Schedule</span>
            </a>
        </li>
        <li class="nav-item" id="active">
            <a class="nav-link " href="javascript:void(0);">
                <span class="inclusions step">Terms</span>
            </a>
        </li>
    @endif
</ul>

<script type="text/javascript">
    jQuery("#active a ").each(function () {
        //alert(this.href);
        if (this.href == window.location.href) {
            jQuery(this).addClass("active");
            //alert('dddddddddddd');
        } else {
            jQuery(this).addClass("tab-departure-rmenu");
        }
    })

    $(".check_payment").click(function () {
        alert('Please fill the payment schedule section before cancelation schedule.');
    });
</script>