<div class="container">
    <div class="header-wrapper">
        <div class="logo">
            <a href="{{url('/')}}">
                <img src="{{asset('website/images/departure-cloud-logo.png')}}" alt="logo">
            </a>
        </div>
        <ul class="menu mb-0">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/about-us')}}">About</a>
            </li>
            <li>
                <a href="{{url('/how-it-works')}}">How It Works</a>
            </li>
            <li>
                <a href="{{url('/pricing')}}">Pricing</a>
            </li>
            <li>
                <a href="{{url('/demo')}}">Demo</a>
            </li>
            <li>
                <a href="{{url('contact-us')}}">Contact</a>
            </li>    
             @if (Route::has('login'))
                @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img src="@if(isset(auth::user()->logo)){{ asset('companyLogo/' . Auth::user()->logo) }} @else {{asset('images/no-image.png')}} @endif" style="width:32px; height:32px; border-radius:50%;">
                  {{auth::user()->name}}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  @if(Auth::user()->main_user_type == 2)
                        @else
                        <div class="dropdown-header noti-title">      
                            <small> Welcome !
                                   @if(Auth::user()->main_user_type == 1) (Supplier) @elseif(Auth::user()->main_user_type == 0) (Buyer) @endif
                            </small>
                        </div>
                                
                        @endif
                        <a href="{{route('dashboard')}}" class="dropdown-item">
                            <i class="fe-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="fe-log-out"></i>
                            <span> Logout</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                </div>
              </li>
                @else  
                <li>
                    <a href="{{route('login')}}">Login</a>
                </li>
                <li class="d-sm-none">
                <a href="{{route('register')}}" class="m-0 header-button">Register</a>
            </li>
                @endauth
            @endif

            
        </ul>
        <div class="header-bar d-lg-none">
            <span></span>
            <span></span>
            <span></span>
        </div>
        @if (Route::has('login'))
            @auth
                @else
        <div class="header-right">
            <a href="{{route('register')}}" class="header-button d-none d-sm-inline-block">Register</a>
        </div>
          @endauth
        @endif
    </div>
</div>