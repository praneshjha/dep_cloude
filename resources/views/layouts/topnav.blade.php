<style>
    .top{top:10px;}
    .top:hover{
        background:#A9A9A9;
        padding:3px;
        border-radius:5px;
    }
    .dep-cloud{padding: 6px 12px;color: #000;} 
    .dep-cloud:hover, .dep-cloud-active{background: #bfc9d4;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%), 0 1px 2px 0 rgb(0 0 0 / 6%);
    border-radius: 6px;color:#000;text-decoration:none;}
</style>
@if(Auth::user()->main_user_type == 2)

<div class="d-none d-md-flex justify-content-end pr-5 w-100">
    <div class="ml-1"><a href="{{route('all_departure')}}" class="dep-cloud  @if(Route::currentRouteName() == 'all_departure')dep-cloud-active @endif">Active Departures</a></div>
    <div class="ml-1"><a href="{{route('unapproved_departure')}}" class="dep-cloud  @if(Route::currentRouteName() == 'unapproved_departure')dep-cloud-active @endif" >Pending For Approval</a></div>
    <div class="ml-1"><a href="{{route('inactive_depature')}}" class="dep-cloud  @if(Route::currentRouteName() == 'inactive_depature')dep-cloud-active @endif" >Inactive Departures</a></div>
    <div class="ml-1"><a href="{{route('suplier_list')}}" class="dep-cloud  @if(Route::currentRouteName() == 'suplier_list')dep-cloud-active @endif" >Supplier List</a></div>
    <div class="ml-1"><a href="{{route('user_list')}}" class="dep-cloud  @if(Route::currentRouteName() == 'user_list')dep-cloud-active @endif" >Buyer List</a></div>
</div>
@elseif(Auth::user()->main_user_type == 1)

<div class="d-none d-md-flex justify-content-end pr-5 w-100">
    <div class="ml-1"><a href="{{route('all_departure')}}" class="dep-cloud @if(Route::currentRouteName() == 'all_departure')dep-cloud-active @endif">All Departures</a></div>
    <div class="ml-1"><a href="{{route('departure')}}" class="dep-cloud @if(Route::currentRouteName() == 'departure')dep-cloud-active @endif" >My Departures</a></div>
    <div class="ml-1"><a href="{{route('my_booking')}}" class="dep-cloud @if(Route::currentRouteName() == 'my_booking')dep-cloud-active @endif" >My Bookings</a></div>
    <div class="ml-1"><a href="{{route('edit_profile')}}" class="dep-cloud @if(Route::currentRouteName() == 'edit_profile')dep-cloud-active @endif" >Company Profile</a></div>
    
</div>
@else 
<div class="d-none d-md-flex justify-content-end pr-5 w-100">
    <div class="ml-1"><a href="{{route('all_departure')}}" class="dep-cloud @if(Route::currentRouteName() == 'all_departure')dep-cloud-active @endif">Departures</a></div>
    <div class="ml-1"><a href="{{route('my_booking')}}" class="dep-cloud @if(Route::currentRouteName() == 'my_booking')dep-cloud-active @endif" >My Bookings</a></div>
    <div class="ml-1"><a href="{{route('edit_profile')}}" class="dep-cloud @if(Route::currentRouteName() == 'edit_profile')dep-cloud-active @endif" >Company Profile</a></div>
</div>
@endif