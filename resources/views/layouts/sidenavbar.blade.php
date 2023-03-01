<div class="sidebar-wrapper sidebar-theme">
            
    <nav id="sidebar">
        <div class="shadow-bottom"></div>

        <ul class="list-unstyled menu-categories" id="accordionExample">
        @if(Auth::user()->main_user_type == 0)
            <li class="menu">
                <a href="{{route('all_departure')}}" @if(Route::currentRouteName() == 'all_departure')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>Departures</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('my_booking')}}" @if(Route::currentRouteName() == 'my_booking')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>My Bookings</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('edit_profile')}}" @if(Route::currentRouteName() == 'edit_profile')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Company Profile</span>
                    </div>
                </a>
            </li>
        @elseif(Auth::user()->main_user_type == 1)
             <li class="menu">
                <a href="{{route('all_departure')}}" @if(Route::currentRouteName() == 'all_departure')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>All Departures</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('departure')}}" @if(Route::currentRouteName() == 'departure')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                        <span>My Departures</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('my_booking')}}" @if(Route::currentRouteName() == 'my_booking')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>My Bookings</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('edit_profile')}}"  @if(Route::currentRouteName() == 'edit_profile')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Company Profile</span>
                    </div>
                </a>
            </li>
        @elseif(Auth::user()->main_user_type == 2)
           <li class="menu">
                <a href="{{route('all_departure')}}" @if(Route::currentRouteName() == 'all_departure')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>Active Departures</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('unapproved_departure')}}" @if(Route::currentRouteName() == 'unapproved_departure')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>Pending For Approval</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{route('inactive_depature')}}" @if(Route::currentRouteName() == 'inactive_departure')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>Departed</span>
                    </div>
                </a>
            </li>
           <!--  <li class="menu">
                <a href="{{route('departure')}}" aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg>
                        <span>My Departures</span>
                    </div>
                </a>
            </li> -->
            <li class="menu">
                <a href="{{route('user_list')}}" @if(Route::currentRouteName() == 'user_list')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Buyer List</span>
                    </div>
                </a>
            </li>
            
            <li class="menu">
                <a href="{{route('suplier_list')}}" @if(Route::currentRouteName() == 'suplier_list')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Supplier List</span>
                    </div>
                </a>
            </li>
            
            <li class="menu">
                <a href="{{route('all_departure_booking_history')}}" @if(Route::currentRouteName() == 'all_departure_booking_history')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Booking History</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('currency_converion')}}" @if(Route::currentRouteName() == 'currency_converion')data-active="true"@endif aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Currency Conversion</span>
                    </div>
                </a>
            </li>
                        <!-- <li class="menu">
                <a href="{{route('hold_history')}}" aria-expanded="false" class="dropdown-toggle" style=" text-decoration: none; ">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Hold History</span>
                    </div>
                </a>
            </li> -->
        @endif    
        </ul>
        
    </nav>

</div>
        <!--  END SIDEBAR  -->