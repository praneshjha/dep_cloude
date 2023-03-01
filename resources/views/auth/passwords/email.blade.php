<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>DepartureCloud - Reset Password</title>

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
<link href="{{asset('assets1/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon">
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P0T5QRLC3W"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-P0T5QRLC3W');
</script>
</head>
<body>
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
<!-- <header class="header-section">
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
</header> -->
<section class="work-section padding-bottom bg_img mb-md-95 pb-md-0 mb-0" data-background="{{asset('website/images/work/work-bg.jpg')}}" id="how" style="background-image: url({{asset('website/images/work/work-bg.jpg')}});padding: 45px 0 !important;">
        <div class="container">
            <div class="thankyou">
                <div class="row align-items-center">
                    <div class="col-md-12 mt-4 mt-md-0 text-center">
                        <a href="javascript:void(0);">
                            <span><img class="mb-4" src="{{ asset('departure-cloud-logo.png') }}" alt="" height="50"></span>
                        </a>
                        <h3 class="m-0 mb-2">{{ __('Forgot Password') }}</h3>
                        <p style="color: #d9d9d9;line-height: 26px;font-size: 18px;max-width: 520px;margin:0 auto 16px">Enter your E-mail address and we'll send you an email with instructions to reset your password.</p>
                        <div class="form-group mb-0 text-center mt-3">
                         @if (session('status'))
                            <div class="alert alert-success" style="max-width: 520px;margin:0 auto 16px;" role="alert">
                            Password recovery link has been mailed to you on registered email, Please check.

                            </div>
                        @endif
                            <form method="POST" action="{{ route('password.email') }}" style="max-width: 391px;margin:0 auto;">
                                @csrf
                                <div class="form-group mb-3 input-group mb-2 w-auto">
                                    <!-- <label for="emailaddress">{{ __('E-Mail Address') }}</label> -->
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fe-mail"></i></div>
                                    </div>
                                    <input id="emailaddress" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="off" autofocus placeholder="Enter your E-mail address">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button type="submit" class="btn btn-verify w-auto mb-3">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>

                            </form>
                        </div>
                        <a class="btn btn-custom w-auto" href="{{route('login')}}">Back to Login</a>
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