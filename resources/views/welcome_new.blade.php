<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>DepartureCloud</title>

    <link rel="stylesheet" href="{{ asset('theam/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/owl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('theam/css/main.css') }}">

    <link rel="shortcut icon" href="https://www.departurecloud.com/favicon.png" type="image/x-icon">
</head>

<body>
    <!--============= ScrollToTop Section Starts Here =============-->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
    <div class="overlay"></div>
    <!--============= ScrollToTop Section Ends Here =============-->


    <!--============= Header Section Starts Here =============-->
    <header class="header-section header-cl-black">
        <div class="container">
            <div class="header-wrapper">
                <div class="logo">
                    <a href="{{url('/')}}">
                        <img src="{{ asset('departure-cloud-logo.png') }}" alt="logo">
                    </a>
                </div>
                <ul class="menu">
                    <li>
                        <a href="{{url('/')}}">Home</a>
                    </li>
                    <li>
                        <a href="{{url('/how-it-works')}}">How It Works</a>
                    </li>
                 @if (Route::has('login'))
                    @auth
                        <li>
                            <a href="{{ url('/home') }}" >Dashboard</a>
                        </li>
                       
                    @else
                         <li>
                            <a href="{{ route('login') }}" >Login</a>
                        </li>

                      @if (Route::has('register'))
                        <li class="d-sm-none">
                             <a href="{{ route('register') }}" class="m-0 header-button">Register</a>
                        </li>
                      @endif
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
                       <div class="header-right">
                             <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="header-button d-none d-sm-inline-block">
                              Logout
                            </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        </div>vv
                    @else  
                      @if (Route::has('register'))
                        <div class="header-right">
                            <a href="{{route('register')}}" class="header-button d-none d-sm-inline-block">Register</a>
                        </div>
                      @endif
                    @endauth
                 @endif
                
            </div>
        </div>
    </header>
    
    <!--============= To Access Section Starts Here =============-->
    <section class="to-access-section padding-top padding-bottom bg_img mb-lg-5" data-background="{{ asset('theam/images/feature/to-access-bg.png') }}" id="feature">
        <div class="container">
            <div class="section-header">
                <h5 class="cate">Amazing features to convince you</h5>
                <h2 class="title">To Use Our Application</h2>
                <p>In the process of making a app, the satisfaction of users is the most 
                    important and the focus is on usability and completeness</p>
            </div>
            <div class="row mb-30 justify-content-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{ asset('theam/images/icon/access1.png') }}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Productivity</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item active">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{ asset('theam/images/icon/access2.png') }}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Optimization</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{ asset('theam/images/icon/access3.png') }}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Safety</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{ asset('theam/images/icon/access4.png') }}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Sustainability</h5>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="#0" class="get-button">Explore Features</a>
            </div>
        </div>
    </section>
    <!--============= To Access Section Ends Here =============-->


    <!--============= How It Works Section Starts Here =============-->
    <section class="work-section padding-bottom bg_img mb-md-95 pb-md-0" data-background="{{ asset('theam/images/work/work-bg.jpg') }}" id="how">
        <div class="oh padding-top pos-rel">
            <div class="top-shape d-none d-lg-block">
                <img src="{{ asset('theam/css/img/work-shape.png') }}" alt="css">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xl-7">
                        <div class="section-header left-style cl-white">
                            <h5 class="cate">Describe Your App</h5>
                            <h2 class="title">Let’s See How It Work</h2>
                            <p>It's easier than you think.Follow the simple easy steps</p>
                        </div>
                    </div>
                </div>
                <div class="work-slider owl-carousel owl-theme" data-slider-id="2">
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{ asset('theam/images/work/work1.png') }}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Register</h3>
                            <p>First, you need to register a user account on our website before
                                configuring and using it on a regular basis.</p>
                            <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                        </div>
                    </div>
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{ asset('theam/images/work/work1.png') }}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Configure</h3>
                            <p>First, you need to register a user account on our website before
                                configuring and using it on a regular basis.</p>
                            <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                        </div>
                    </div>
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{ asset('theam/images/work/work1.png') }}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Integrate</h3>
                            <p>First, you need to register a user account on our website before
                                configuring and using it on a regular basis.</p>
                            <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                        </div>
                    </div>
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{ asset('theam/images/work/work1.png') }}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Yay! Done</h3>
                            <p>First, you need to register a user account on our website before
                                configuring and using it on a regular basis.</p>
                            <a href="#0" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="count-slider owl-thumbs" data-slider-id="2">
                <div class="count-item">
                    <span class="serial">01</span>
                    <h5 class="title">Register</h5>
                </div>
                <div class="count-item">
                    <span class="serial">02</span>
                    <h5 class="title">Configure</h5>
                </div>
                <div class="count-item">
                    <span class="serial">03</span>
                    <h5 class="title">Integrade</h5>
                </div>
                <div class="count-item">
                    <span class="serial">04</span>
                    <h5 class="title">Yay! Done</h5>
                </div>
            </div>
        </div>
    </section>
    <!--============= How It Works Section Ends Here =============-->


    <!--============= Access Section Starts Here =============-->
    <section class="access-section padding-bottom padding-top oh">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-4 col-xl-3 rtl d-none d-lg-block">
                    <img src="{{ asset('theam/images/access/access1.png') }}" alt="access">
                </div>
                <div class="col-lg-8">
                    <div class="access-content">
                        <div class="section-header left-style mb-olpo">
                            <h5 class="cate">All in One Access</h5>
                            <h2 class="title">Everything your business needs in one central place</h2>
                            <p>Our innovative, easy-to-use tools live in just one platform, saving you time and 
                                streamlining your work. Make admin tasks more efficient, get paid faster, and collaborate more seamlessly.</p>
                        </div>
                        <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Access Section Ends Here =============-->


    <!--============= Feature Video Section Starts Here =============-->
    <section class="feature-video-section padding-top ash-gradient-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="section-header mw-100">
                        <h5 class="cate">Amazing features to convince you</h5>
                        <h2 class="title">Watch our video presentation</h2>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <p>In the process of making a app, the satisfaction of users is the most 
                                    important and the focus is on usability and completeness</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="pos-rel mw-100">
                        <img class="w-100" src="{{ asset('theam/images/feature/feature-video.png') }}" alt="bg">
                        <a href="https://www.youtube.com/watch?v=ObZwFExwzOo" class="video-button-2 popup">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <i class="flaticon-play"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Feature Video Section Ends Here =============-->


    <!--============= Advance Feature Section Starts Here =============-->
    <section class="advance-feature-section padding-top-2 padding-bottom-2">
        <div class="container">
            <div class="advance-feature-item padding-top-2 padding-bottom-2">
                <div class="advance-feature-thumb">
                    <img src="{{ asset('theam/images/feature/advance1.png') }}" alt="feature">
                </div>
                <div class="advance-feature-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">Advance features give you full control.</h5>
                        <h2 class="title">See what you can achieve!</h2>
                        <p>The simple, intuitive user interface is designed to help you see exactly what you need to focus on.As a team member you can focus on your work and as a team leader, you can easily manage your team.</p>
                    </div>
                    <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
                </div>
            </div>
            <div class="advance-feature-item padding-top-2 padding-bottom-2">
                <div class="advance-feature-thumb">
                    <img src="{{ asset('theam/images/feature/advance2.png') }}" alt="feature">
                </div>
                <div class="advance-feature-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">Powerful and Flexible</h5>
                        <h2 class="title">Collaboration for team work</h2>
                        <p>The simple, intuitive user interface is designed to help you see exactly what you need to focus on.As a team member you can focus on your work and as a team leader, you can easily manage your team.</p>
                    </div>
                    <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
                </div>
            </div>
            <div class="advance-feature-item padding-top-2 padding-bottom-2">
                <div class="advance-feature-thumb">
                    <img src="{{ asset('theam/images/feature/advance3.png') }}" alt="feature">
                </div>
                <div class="advance-feature-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">Discover powerful tool for your customers.</h5>
                        <h2 class="title">Great template for your Web app</h2>
                        <p>The simple, intuitive user interface is designed to help you see exactly what you need to focus on.As a team member you can focus on your work and as a team leader, you can easily manage your team.</p>
                    </div>
                    <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!--============= Advance Feature Section Ends Here =============-->


    <!--============= Pricing Section Starts Here =============-->
    <section class="pricing-section padding-top oh padding-bottom pb-lg-half bg_img pos-rel" data-background="{{ asset('theam/images/bg/pricing-bg.jpg') }}" id="pricing">
        <div class="top-shape d-none d-md-block">
            <img src="{{ asset('theam/css/img/top-shape.png') }}" alt="css">
        </div>
        <div class="bottom-shape d-none d-md-block mw-0">
            <img src="{{ asset('theam/css/img/bottom-shape.png') }}" alt="css">
        </div>
        <div class="ball-2" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{ asset('theam/images/balls/1.png') }}" alt="balls">
        </div>
        <div class="ball-3" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{ asset('theam/images/balls/2.png') }}" alt="balls">
        </div>
        <div class="ball-4" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{ asset('theam/images/balls/3.png') }}" alt="balls">
        </div>
        <div class="ball-5" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="vertical">
            <img src="{{ asset('theam/images/balls/4.png') }}" alt="balls">
        </div>
        <div class="ball-6" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{ asset('theam/images/balls/5.png') }}" alt="balls">
        </div>
        <div class="ball-7" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="vertical">
            <img src="{{ asset('theam/images/balls/6.png') }}" alt="balls">
        </div>
        <div class="container">
            <div class="section-header cl-white">
                <h5 class="cate">Choose a plan that's right for you</h5>
                <h2 class="title">Simple Pricing Plans</h2>
                <p>
                    Mosto has plans, from free to paid, that scale with your needs. Subscribe to a plan that fits the size of your business.
                </p>
            </div>
            <div class="tab-up">
                <ul class="tab-menu pricing-menu">
                    <li class="active">Monthly</li>
                    <li>Yearly</li>
                </ul>
                <div class="tab-area">
                    <div class="tab-item active">
                        <div class="pricing-slider-wrapper">
                            <div class="pricing-slider owl-theme owl-carousel">
                                <div class="pricing-item-2">
                                    <h5 class="cate">Startup</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing1.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>5.0</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>3 Users 10 GB Storage</li>
                                        <li>Monthly Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Standard</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing2.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>10</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>5 Users 20 GB Storage </li>
                                        <li>Weekly Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Business</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing3.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>20</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>10 Users 40 GB Storage </li>
                                        <li>Daily Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Premium</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing4.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>30</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>15 Users 60 GB Storage </li>
                                        <li>Free Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-item">
                        <div class="pricing-slider-wrapper">
                            <div class="pricing-slider owl-theme owl-carousel">
                                <div class="pricing-item-2">
                                    <h5 class="cate">Startup</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing1.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>60</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>3 Users 10 GB Storage</li>
                                        <li>Monthly Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Standard</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing2.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>120</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>5 Users 20 GB Storage </li>
                                        <li>Weekly Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Business</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing3.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>180</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>10 Users 40 GB Storage </li>
                                        <li>Daily Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                    <h5 class="cate">Premium</h5>
                                    <div class="thumb">
                                        <img src="{{ asset('theam/images/pricing/pricing4.png') }}" alt="pricing">
                                    </div>
                                    <h2 class="title"><sup>$</sup>270</h2>
                                    <span class="info">Per Month</span>
                                    <ul class="pricing-content-3">
                                        <li>15 Users 60 GB Storage </li>
                                        <li>Free Updates</li>
                                        <li>eCommerce Integration</li>
                                        <li>Interface Customization</li>
                                        <li>24/7 Support</li>
                                    </ul>
                                    <a href="#0" class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Pricing Section Ends Here =============-->


    <!--============= Testimonial Section Starts Here =============-->
    <section class="testimonial-section padding-top pt-lg-half" id="client">
        <div class="container">
            <div class="section-header">
                <h5 class="cate">Testimonials</h5>
                <h2 class="title">5000+ happy clients all around the world</h2>
            </div>
            <div class="testimonial-wrapper">
                <a href="#0" class="testi-next trigger">
                    <img src="{{ asset('theam/images/client/left.png') }}" alt="client">
                </a>
                <a href="#0" class="testi-prev trigger">
                    <img src="{{ asset('theam/images/client/right.png') }}" alt="client">
                </a>
                <div class="testimonial-area testimonial-slider owl-carousel owl-theme">
                    <div class="testimonial-item">
                        <div class="testimonial-thumb">
                            <div class="thumb">
                                <img src="{{ asset('theam/images/client/client1.jpg') }}" alt="client">
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="ratings">
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                            </div>
                            <p>
                                Awesome product. The guys have put huge effort into this app and focused on simplicity and ease of use.
                            </p>
                            <h5 class="title"><a href="#0">Bela Bose</a></h5>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="testimonial-thumb">
                            <div class="thumb">
                                <img src="{{ asset('theam/images/client/client1.jpg') }}" alt="client">
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="ratings">
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                            </div>
                            <p>
                                Awesome product. The guys have put huge effort into this app and focused on simplicity and ease of use.
                            </p>
                            <h5 class="title"><a href="#0">Raihan Rafuj</a></h5>
                        </div>
                    </div>
                </div>
                <div class="button-area">
                    <a href="#0" class="button-2">Leave a review <i class="flaticon-right"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!--============= Testimonial Section Ends Here =============-->


    <!--============= Faq Section Starts Here =============-->
    <section class="faq-section padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="faq-header">
                        <div class="cate">
                            <img src="{{ asset('theam/images/cate.png') }}" alt="cate">
                        </div>
                        <h2 class="title">Frequently Asked Questions</h2>
                        <a href="#0">More Questions <i class="flaticon-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-wrapper mb--38">
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">Is the Web App Secure?</h4>
                                <p>
                                    Web application security is the process of protecting websites and online services against different security threats that exploit vulnerabilities in an application’s code.
                                </p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">What features does the Web App have?</h4>
                                <p>
                                    Both the Mobile Apps and the Web App give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.
                                </p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">How do I get the Mobile App for my phone?</h4>
                                <p>
                                    Both the Mobile Apps and the Web App give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.
                                </p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">How does Mosto differ from usual apps? </h4>
                                <p>
                                    Both the Mobile Apps and the Web App give you the ability to you to access your account information, view news releases, report an outage, and contact us via email or phone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Faq Section Ends Here =============-->


    <!--============= Trial Section Starts Here =============-->
    <section class="trial-section padding-bottom padding-top">
        <div class="container">
            <div class="trial-wrapper padding-top padding-bottom pr">
                <div class="ball-1">
                    <img src="{{ asset('theam/images/balls/balls.png') }}" alt="balls">
                </div>
                <div class="trial-content cl-white">
                    <h3 class="title">Start your 30 days free trials today!</h3>
                    <p>
                        We have provided 30 Days Money Back <br> Guarantee in case of dissatisfaction with our product.
                    </p>
                </div>
                <div class="trial-button">
                    <a href="#0" class="transparent-button">Get Free Trial <i class="flaticon-right ml-xl-2"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!--============= Trial Section Ends Here =============-->


    <!--============= Footer Section Starts Here =============-->
    <footer class="footer-section bg_img" data-background="{{ asset('theam/images/footer/footer-bg.jpg') }}">
        <div class="container">
            <div class="footer-top padding-top padding-bottom py-5">
                <div class="logo">
                    <a href="#0">
                        <img src="{{ asset('theam/images/departure-cloud-logo.png') }}" alt="logo">
                    </a>
                </div>
                <ul class="social-icons d-none">
                    <li>
                        <a href="#0"><i class="fab fa-facebook-f"></i></a>
                    </li>
                    <li>
                        <a href="#0" class="active"><i class="fab fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-pinterest-p"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-google-plus-g"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
            <div class="footer-bottom">
                <ul class="footer-link">
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a href="how-it-works.html">How It Works</a>
                    </li>
                    <li>
                        <a href="https://www.departurecloud.com/login">Sign In</a>
                    </li>
                    <li>
                        <a href="https://www.departurecloud.com/register">Register</a>
                    </li>
                </ul>
            </div>
            <div class="copyright">
                <p>
                    Copyright &copy; 2021. All Rights Reserved By <a href="index.html">DepartureCloud</a>
                </p>
            </div>
        </div>
    </footer>
    <!--============= Footer Section Ends Here =============-->

    <script src="{{ asset('theam/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('theam/js/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('theam/js/plugins.js') }}"></script>
    <script src="{{ asset('theam/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theam/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('theam/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('theam/js/wow.min.js') }}"></script>
    <script src="{{ asset('theam/js/waypoints.js') }}"></script>
    <script src="{{ asset('theam/js/nice-select.js') }}"></script>
    <script src="{{ asset('theam/js/owl.min.js') }}"></script>
    <script src="{{ asset('theam/js/counterup.min.js') }}"></script>
    <script src="{{ asset('theam/js/paroller.js') }}"></script>
    <script src="{{ asset('theam/js/main.js') }}"></script>
    <style>
        .footer-section::before { display: none;}
    </style>
</body>

</html>