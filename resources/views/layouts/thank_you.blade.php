<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Thankyou - Departure Cloud</title>

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{asset('website/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('website/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('website/css/animate.css')}}">
<link rel="stylesheet" href="{{asset('website/css/nice-select.css')}}">
<link rel="stylesheet" href="{{asset('website/css/owl.min.css')}}">
<link rel="stylesheet" href="{{asset('website/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{asset('website/css/magnific-popup.css')}}">
<link rel="stylesheet" href="{{asset('website/css/flaticon.css')}}">
<link rel="stylesheet" href="{{asset('website/css/main.css')}}">

<link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon">
<!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-N2RCC4T');</script>
    <!-- End Google Tag Manager -->
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '576326336869868');
  fbq('track', 'PageView');
  fbq('track', 'CompleteRegistration');
</script>
<noscript>
  <img height="1" width="1" style="display:none" 
       src="https://www.facebook.com/tr?id=576326336869868&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N2RCC4T"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
            <img src="{{asset('favicon.png')}}" alt="" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);margin-top: -4px;width: 55px;z-index: 999;">
        </div>
    </div>
    <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
    <div class="overlay"></div>
    <!--============= ScrollToTop Section Ends Here =============-->


    <!--============= Header Section Starts Here =============-->
    <header class="header-section">
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
                <a href="{{url('/demo')}}">Demo</a>
            </li>
            <li>
                <a href="{{url('contact-us')}}">Contact</a>
            </li>
            <li>
                <a href="{{route('login')}}">Login</a>
            </li>
            <li class="d-sm-none">
                <a href="{{route('register')}}" class="m-0 header-button">Register</a>
            </li>
        </ul>
        <div class="header-bar d-lg-none">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="header-right">
            <a href="{{route('register')}}" class="header-button d-none d-sm-inline-block">Register</a>
        </div>
    </div>
</div>
</header>
<section class="work-section padding-bottom bg_img mb-md-95 pb-md-0 mb-0" data-background="{{asset('website/images/work/work-bg.jpg')}}" id="how" style="background-image: url({{asset('website/images/work/work-bg.jpg')}});padding: 60px 0 !important;">
        <div class="container">
            <div class="thankyou">
                <div class="row align-items-center">
                    <div class="col-md-12 mt-4 mt-md-0 text-center">
                        <h5>Thank You!</h5>
                        <h3 class="m-0 mb-2">You Are Almost Ready!</h3>
                        <!-- <p style="color: #d9d9d9;line-height: 26px;font-size: 18px;">You've been successfully registered.</p>
                        <p style="color: #d9d9d9;line-height: 26px;font-size: 18px;">You can now proceed to log in and start your journey with DepartureCloud.</p> -->
                        <p style="color: #d9d9d9;line-height: 26px;font-size: 18px;">To get started, please check your email for a verification link.</p>
                        <div class="form-group mb-0 text-center mt-3">
                             @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            <form class="" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-verify w-auto mb-3">{{ __('Click to Resend Verification Email') }}</button>
                            </form>
                        </div>
                        <a class="btn btn-custom w-auto" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Back to Login</a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <!-- <div class="col-md-7 order-first order-md-last">
                        <img src="{{asset('website/images/extra/thankyou.png')}}" alt="thankyou" class="thankyou-img">
                    </div> -->
                </div>
            </div>
        </div>
    </section>
    <style type="text/css">
        .work-section{
            min-height: calc(100vh - 154px);
        }
        .thankyou{
            box-shadow: 0 0 16px rgba(0 104 231 / 70%);
            border-radius: 36px;
            padding: 56px 34px;
            background-color: rgb(40 151 240 / 70%);
        }
        .thankyou-img{
            width: 100%;
        }
        .btn-custom, .btn-verify {
            background: -webkit-linear-gradient(0deg, #e2906e 0%, #e83a99 100%);
            border: 0;
            outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            color: #fff;
            height: auto;
        }
        .btn-verify {
            background: -webkit-linear-gradient(0deg, #e2906e 0%, #33c73ec7 100%);
        }
        .btn-custom:hover,.btn-verify:hover {
            background: -webkit-linear-gradient(0deg, #e83a99 0%,#e2906e 100%);
            border: 0;
            outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            height: auto;
            color: #31377D;
        }
        .btn-verify:hover {
            background: -webkit-linear-gradient(0deg, #33c73ec7 0%,#e2906e 100%);
        }
        .header-section {
            width: 100%;
            position: sticky;
            z-index: 999;
            top: 0;
            -webkit-transition: all ease 0.3s;
            -moz-transition: all ease 0.3s;
            transition: all ease 0.3s;
            padding: 15 px 0;
        }
        .alert-success {
            color: #155724;
            background-color: transparent;
            border-color: transparent;
        }
    </style>
<!--============= Footer Section Starts Here =============-->
    <footer class="footer-section bg_img" data-background="{{asset('website/images/footer/footer-bg.jpg')}}">
        <div class="container">
            <div class="copyright" style="border: 0;">
                <p>
                    <script>document.write(new Date().getFullYear())</script> &copy; All Rights Reserved By <a href="{{url('/')}}">DepartureCloud</a>
                </p>
            </div>
        </div>
    </footer>
    <!--============= Footer Section Ends Here =============-->
    
    <style>
        .footer-section::before { display: none;}
    </style>

    <script src="{{asset('website/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('website/js/modernizr-3.6.0.min.js')}}"></script>
    <script src="{{asset('website/js/plugins.js')}}"></script>
    <script src="{{asset('website/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('website/js/magnific-popup.min.js')}}"></script>
    <script src="{{asset('website/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('website/js/wow.min.js')}}"></script>
    <script src="{{asset('website/js/waypoints.js')}}"></script>
    <script src="{{asset('website/js/nice-select.js')}}"></script>
    <script src="{{asset('website/js/owl.min.js')}}"></script>
    <script src="{{asset('website/js/counterup.min.js')}}"></script>
    <script src="{{asset('website/js/paroller.js')}}"></script>
    <script src="{{asset('website/js/main.js')}}"></script>
</body>

</html>