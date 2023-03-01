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
    <?php if(request()->route('id')): ?>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="<?php echo e(route('departure_edit',request()->route('id'))); ?>">
                <span class="Dep_basic_details step">Basic Details</span>
            </a>
        </li>
        <li class="nav-item" id="active">
            <a class="nav-link " href="<?php echo e(route('inclusion',request()->route('id'))); ?>">
        <span class="inclusions step">Inclusions
        </span>
            </a>
        </li>
        <?php if($departure_types->departure_type != 5): ?>
            <li class="nav-item" id="active">
                <a class="nav-link " href="<?php echo e(route('hotel_create',request()->route('id'))); ?>">
                    <span class="inclusions step">Hotels</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if($departure_types->departure_type != 4 && $departure_types->departure_type != 2): ?>
            <li id="active" class="nav-item">
                <a class="nav-link" id="activated" href="<?php echo e(route('flight_create',request()->route('id'))); ?>">
                    <span class="Dep_basic_details step">Flights</span>
                </a>
            </li>
        <?php endif; ?>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="<?php echo e(route('itinerary_create',request()->route('id'))); ?>">
                <span class="Dep_basic_details step">Day Schedule</span>
            </a>
        </li>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="<?php echo e(route('pdf_itinerary',request()->route('id'))); ?>">
                <span class="Dep_basic_details step">Itinerary</span>
            </a>
        </li>
        <li id="active" class="nav-item">
            <a class="nav-link" id="activated" href="<?php echo e(route('pricing_create',request()->route('id'))); ?>">
                <span class="Dep_basic_details step">Pricing</span>
            </a>
        </li>
        <li class="nav-item" id="active">
            <a class="nav-link " href="<?php echo e(route('terms_payment_create',request()->route('id'))); ?>">
                <span class="inclusions step">Payment Schedule</span>
            </a>
        </li>
        <?php if(count($payment) > 0): ?>
            <li class="nav-item" id="active">
                <a class="nav-link " href="<?php echo e(route('cancelation_create',request()->route('id'))); ?>" class="">
                    <span class="inclusions step">Cancelation Schedule</span>
                </a>
            </li>
        <?php else: ?>
            <li class="nav-item check_payment" id="">
                <a class="nav-link " href="#" class="">
                    <span class="inclusions step">Cancelation Schedule</span>
                </a>
            </li>
        <?php endif; ?>
        <li class="nav-item" id="active">
            <a class="nav-link " href="<?php echo e(route('terms_create',request()->route('id'))); ?>">
                <span class="inclusions step">Terms</span>
            </a>
        </li>
    <?php else: ?>
        <li class="nav-item tab-departure active" id="active">
            <a class="nav-link tab-departure-rmenu tab-departure-active"
               href="<?php echo e(route('departure_create',request()->route('departure_type_id'))); ?>">
                <span class="Dep_basic_details step">Basic Details</span>
            </a>
        </li>
        <li class="nav-item tab-departure" id="active">
            <a class="nav-link tab-departure-rmenu" href="javascript:void(0);">
                <span class="inclusions step">Inclusions</span>
            </a>
        </li>
        <?php if( request()->route('departure_type_id') != 5): ?>
            <li class="nav-item tab-departure active" id="active">
                <a class="nav-link tab-departure-rmenu tab-departure-active" href="javascript:void(0);">
                    <span class="Dep_basic_details step">Hotel</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(request()->route('departure_type_id') != 4 && request()->route('departure_type_id') != 2): ?>
            <li class="nav-item tab-departure active" id="active">
                <a class="nav-link tab-departure-rmenu tab-departure-active" href="javascript:void(0);">
                    <span class="Dep_basic_details step">Flights</span>
                </a>
            </li>
        <?php endif; ?>
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
    <?php endif; ?>
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
</script><?php /**PATH /var/www/html/departurecloud/resources/views/layouts/itinerary_menu.blade.php ENDPATH**/ ?>