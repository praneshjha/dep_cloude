@extends('websitelayoute.app')
@section('title', 'Worldwide Travel & Fixed Departures Management - DepartureCloud')
@section('metas')
<meta name="description" content="Buy or Publish Fixed Departures for any destination at DepartureCloud. Only Travel Departures Management Platform for Travel Suppliers & Buyers!">
    <meta name="keywords" content="Fixed Departures, Fixed Deaprtures Management, Travel Departures, Travel Departures Management, Departure Management, Fixed Departure Tour, B2B Fixed Departure, Fixed Departure Tour Packages">
@endsection
@section('content')

    <!--============= Banner Section Starts Here =============-->
    <section class="banner-7 bg_img oh bottom_right" data-background="{{asset('website/images/banner/banner-bg-7.jpg')}}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner-content-7">
                        <h1 class="title">Enhance Your Business with DepartureCloud</h1>
                        <p>India’s only travel departures management tool to manage and promote fixed departures among worldwide buyers.</p>
                        <div class="banner-button-group">   
                            <a href="{{route('register')}}" class="button-4">Register Now</a>
                            <a href="{{route('login')}}" class="button-4 active">Log In</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-block d-none">
                    <img src="{{asset('website/images/banner/banner-7.png')}}" alt="banner">
                </div>
                <div class="col-12">
                    <div class="counter-wrapper-3">
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="{{asset('website/images/icon/counter1.png')}}" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">200</span><span>+</span></h2>
                                <span class="name">Suppliers</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="{{asset('website/images/icon/counter2.png')}}" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">10000</span><span>+</span></h2>
                                <span class="name">Buyers</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="{{asset('website/images/icon/counter5.png')}}" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="counter">1000</span><span>+</span></h2>
                                <span class="name">Departures</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Banner Section Ends Here =============-->


    <!--============= To Access Section Starts Here =============-->
    <section class="to-access-section padding-top padding-bottom bg_img mb-lg-5" data-background="{{asset('website/images/feature/to-access-bg.png')}}" id="feature">
        <div class="container">
            <div class="section-header">
                <h5 class="cate">Some amazing features that can</h5>
                <h2 class="title"> Transform Your Travel Business</h2>
                <p>DepartureCloud is an all-in-one platform that allows travel businesses, travel agents, and tour operators to create, manage, and track travel departures in real-time.</p>
            </div>
            <div class="row mb-30 justify-content-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{asset('website/images/icon/access1.png')}}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Easy-To-Use Platform</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item active">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{asset('website/images/icon/access2.png')}}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Access Worldwide Departures</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{asset('website/images/icon/access3.png')}}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Detailed Price Management</h5>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="to-access-item">
                        <div class="to-access-thumb">
                            <span class="anime"></span>
                            <div class="thumb">
                                <img src="{{asset('website/images/icon/access4.png')}}" alt="access">
                            </div>
                        </div>
                        <h5 class="title">Fast and Secure Payments</h5>
                    </div>
                </div>
            </div>
            <!-- <div class="text-center">
                <a href="#0" class="get-button">Explore Features</a>
            </div> -->
        </div>
    </section>
    <!--============= To Access Section Ends Here =============-->


    <!--============= How It Works Section Starts Here =============-->
    <section class="work-section padding-bottom bg_img mb-md-95 pb-md-0" data-background="{{asset('website/images/work/work-bg.jpg')}}" id="how">
        <div class="oh padding-top pos-rel">
            <div class="top-shape d-none d-lg-block">
                <img src="{{asset('website/css/img/work-shape.png')}}" alt="css">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xl-7">
                        <div class="section-header left-style cl-white">
                            <!-- <h5 class="cate">Describe Your App</h5> -->
                            <h2 class="title"> Here’s How to Get Started</h2>
                            <p>Follow these quick and simple steps</p>
                        </div>
                    </div>
                </div>
                <div class="work-slider owl-carousel owl-theme" data-slider-id="2">
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{asset('website/images/work/work1.png')}}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Register</h3>
                            <p>To get started with DepartureCloud, you need to register a user account as a supplier/buyer.</p>
                            <a href="{{route('register')}}" class="get-button white light">Get Started <i class="flaticon-right"></i></a>
                        </div>
                    </div>
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{asset('website/images/work/work1.png')}}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Create/Purchase Departures</h3>
                            <p>Suppliers can create & manage multiple departures, while the buyers can purchase from the worldwide departures enlisted.</p>
                        </div>
                    </div>
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{asset('website/images/work/work1.png')}}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Process Payments</h3>
                            <p>Buyers can make secure online payments for the chosen departures. While suppliers can do payment follow-ups.</p>
                        </div>
                    </div>
                    <div class="work-item">
                        <div class="work-thumb">
                            <img src="{{asset('website/images/work/work1.png')}}" alt="work">
                        </div>
                        <div class="work-content cl-white">
                            <h3 class="title">Real-time Analytics</h3>
                            <p>Both buyers and suppliers can do real-time tracking of departures. Detailed graphical representation of accommodations based on occupancy.</p>
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
                    <h5 class="title">Create/Purchase Departures</h5>
                </div>
                <div class="count-item">
                    <span class="serial">03</span>
                    <h5 class="title">Process Payments</h5>
                </div>
                <div class="count-item">
                    <span class="serial">04</span>
                    <h5 class="title">Real-time Analytics</h5>
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
                    <img src="{{asset('website/images/access/access1.png')}}" alt="access">
                </div>
                <div class="col-lg-8">
                    <div class="access-content">
                        <div class="section-header left-style mb-olpo">
                            <h5 class="cate">All in One Platform</h5>
                            <h2 class="title">Single Place for all Departures Management</h2>
                            <p>DepartureCloud is an innovative marketplace for both suppliers and buyers to streamline the departures management process. It gives the user ability to deal in travel departures effortlessly and collaborate with a vast network of travel agents, tour operators, and OTAs.</p>
                        </div>
                        <a href="{{route('register')}}" class="get-button">get started <i class="flaticon-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Access Section Ends Here =============-->


    <!--============= Feature Video Section Starts Here =============-->
    <section class="feature-video-section padding-top dark-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="section-header mw-100">
                        <h5 class="cate">Go-to Collaboration Tool for</h5>
                        <h2 class="title">Worldwide Buyers and Suppliers</h2>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <p>With DepartureCloud, you don't need to go elsewhere looking for business when it comes directly to your doorstep.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="pos-rel mw-100">
                        <img class="w-100" src="{{asset('website/images/feature/feature-video-1.png')}}" alt="bg">
                        <a href="https://www.youtube.com/watch?v=ObZwFExwzOo" class="video-button-2 popup d-none">
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
                    <img src="{{asset('website/images/feature/advance1.png')}}" alt="feature">
                </div>
                <div class="advance-feature-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">Advanced features that give you full control.</h5>
                        <h2 class="title">Here’s what you can do!</h2>
                        <p>With DepartureCloud, a supplier can easily create and publish unlimited travel departures. The suppliers don’t need to go look for buyers as the platform brings them to you, enhancing sales. A buyer, on the other hand, can choose from worldwide travel departures and make transactions within seconds.</p>
                    </div>
                    <a href="{{route('register')}}" class="get-button">get started now <i class="flaticon-right"></i></a>
                </div>
            </div>
            <div class="advance-feature-item padding-top-2 padding-bottom-2">
                <div class="advance-feature-thumb">
                    <img src="{{asset('website/images/feature/advance2.png')}}" alt="feature">
                </div>
                <div class="advance-feature-content">
                    <div class="section-header left-style mb-olpo">
                        <h5 class="cate">Software that gives your more flexibility</h5>
                        <h2 class="title">Collaborate with ease</h2>
                        <p>The interface of DepartureCloud is designed to offer better flexibility to travel agents, and tour operators for seamless networking and collaborations for faster sales of departures inventory. It helps promote timely departures delivery and improve day-to-day business processing.</p>
                    </div>
                    <!-- <a href="#0" class="get-button">get free  trial <i class="flaticon-right"></i></a> -->
                </div>
            </div>
        </div>
    </section>
    <!--============= Advance Feature Section Ends Here =============-->


    <!--============= Pricing Section Starts Here =============-->
    <section class="pricing-section padding-top oh padding-bottom pb-lg-half bg_img pos-rel" data-background="{{asset('website/images/bg/pricing-bg.jpg')}}" id="pricing">
        <div class="top-shape d-none d-md-block">
            <img src="{{asset('website/css/img/top-shape.png')}}" alt="css">
        </div>
        <div class="bottom-shape d-none d-md-block mw-0">
            <img src="{{asset('website/css/img/bottom-shape.png')}}" alt="css">
        </div>
        <div class="ball-2" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{asset('website/images/balls/1.png')}}" alt="balls">
        </div>
        <div class="ball-3" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{asset('website/images/balls/2.png')}}" alt="balls">
        </div>
        <div class="ball-4" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{asset('website/images/balls/3.png')}}" alt="balls">
        </div>
        <div class="ball-5" data-paroller-factor="0.30" data-paroller-factor-lg="-0.30"
        data-paroller-type="foreground" data-paroller-direction="vertical">
            <img src="{{asset('website/images/balls/4.png')}}" alt="balls">
        </div>
        <div class="ball-6" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="horizontal">
            <img src="{{asset('website/images/balls/5.png')}}" alt="balls">
        </div>
        <div class="ball-7" data-paroller-factor="-0.30" data-paroller-factor-lg="0.60"
        data-paroller-type="foreground" data-paroller-direction="vertical">
            <img src="{{asset('website/images/balls/6.png')}}" alt="balls">
        </div>
        <div class="container">
            <div class="section-header cl-white">
                <h5 class="cate">Choose a plan that's right for you</h5>
                <h2 class="title">Simple Pricing Plan</h2>
                <p>
                    DepartureCloud has plan that cater to one user to additional users as per your needs.
                </p>
            </div>
            <div class="pricing-slider-wrapper mt-0">
                <div class="d-md-flex justify-content-center">
                    <div class="pricing-item-2 col-lg-3 mb-3 mb-md-0">
                        <h5 class="cate">Buyer</h5>
                        <div class="thumb">
                            <img src="{{asset('website/images/buyer.png')}}" alt="pricing">
                        </div>
                        <h2 class="title"><sup>&#8377;</sup>1500<span>/month billed quarterly</span></h2>
                        <ul class="pricing-content-3">
                            <li class="list_bullets">Includes 1 user</li>
                            <li class="list_bullets">Get additional user at <sup>&#8377;</sup>500/month</li>
                             <!-- <li>Buy & hold worldwide departures</li>
                            <li>View your bookings</li>
                            <li>Edit & update your profile</li> -->
                            <li class="list_bullets">View unlimited departures</li>
                            <li class="list_bullets">Buy & hold departures</li>
                            <li class="list_bullets">Manage your bookings</li>
                            <li class="list_bullets">View suppliers' profile</li>
                            <li class="list_bullets">Live chat with suppliers</li>
                        </ul>
                        <a class="get-button">Free Sign Up<i class="flaticon-right"></i></a>
                    </div>
                    <div class="pricing-item-2 col-lg-3 mb-3 mb-md-0">
                        <h5 class="cate">Supplier</h5>
                        <div class="thumb">
                            <img src="{{asset('website/images/supplier.png')}}" alt="pricing">
                        </div>
                        <h2 class="title"><sup>&#8377;</sup>3500<span>/month billed quarterly</span></h2>
                        <ul class="pricing-content-3">
                            <li class="list_bullets">Includes 1 user</li>
                            <li class="list_bullets">Get additional user at <sup>&#8377;</sup>500/month</li>
                           <!--  <li>Buy & hold worldwide departures</li>
                            <li>Upload unlimited departures</li>
                            <li>Manage your departures</li>
                            <li>Edit & update your profile</li> -->

                            <li class="list_bullets">View unlimited departures</li>
                            <li class="list_bullets">Buy & hold departures</li>
                            <li class="list_bullets">Manage your bookings</li>
                            <li class="list_bullets">View buyers' profile</li>
                            <li class="list_bullets">Live chat with buyers</li>
                            <li class="list_bullets">Manage your departures & series</li>
                            <li class="list_bullets">Publish departures to network</li>
                            <li class="list_bullets">Detailed analytics</li>
                        </ul>
                        <a class="get-button">Free Sign Up<i class="flaticon-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Pricing Section Ends Here =============-->


    <!--============= Testimonial Section Starts Here =============-->
    <!-- <section class="testimonial-section padding-top pt-lg-half" id="client">
        <div class="container">
            <div class="section-header">
                <h5 class="cate">Testimonials</h5>
                <h2 class="title">5000+ happy clients all around the world</h2>
            </div>
            <div class="testimonial-wrapper">
                <a href="#0" class="testi-next trigger">
                    <img src="{{asset('website/images/client/left.png')}}" alt="client">
                </a>
                <a href="#0" class="testi-prev trigger">
                    <img src="{{asset('website/images/client/right.png')}}" alt="client">
                </a>
                <div class="testimonial-area testimonial-slider owl-carousel owl-theme">
                    <div class="testimonial-item">
                        <div class="testimonial-thumb">
                            <div class="thumb">
                                <img src="{{asset('website/images/client/client1.jpg')}}" alt="client">
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
                                <img src="{{asset('website/images/client/client1.jpg')}}" alt="client">
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
    </section> -->
    <!--============= Testimonial Section Ends Here =============-->


    <!--============= Faq Section Starts Here =============-->
    <section class="faq-section padding-top pb-5" id="faq-departure">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-6">
                    <div class="faq-header">
                        <div class="cate">
                            <img src="{{asset('website/images/cate.png')}}" alt="cate">
                        </div>
                        <h2 class="title">Frequently Asked Questions</h2>
                        <!-- <a href="#0">More Questions <i class="flaticon-right"></i></a> -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-wrapper mb--38">
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">Is the DepartureCloud Secure?</h4>
                                <p>DepartureCloud is a safe platform for both buyers and suppliers that provides complete security to its users. It protects user information and makes it easy to perform business.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">Can I use the software on my phone?</h4>
                                <p>Yes, buyers can access DepartureCloud by phone. They can purchase inventories and make easy payments via phone themselves.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">Is there a limit to the number of Departures one can create?</h4>
                                <p>A supplier can create an unlimited number of travel departures for any destination using DepartureCloud.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-thumb">
                                <i class="flaticon-pdf"></i>
                            </div>
                            <div class="faq-content">
                                <h4 class="title">How Departurecloud can help suppliers in improving business processes?</h4>
                                <p>DepartureCloud can help suppliers connect with multiple buyers worldwide through one platform, thus boosting sales.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Faq Section Ends Here =============-->


    <!--============= Trial Section Starts Here =============-->
    <!-- <section class="trial-section padding-bottom padding-top">
        <div class="container">
            <div class="trial-wrapper padding-top padding-bottom pr">
                <div class="ball-1">
                    <img src="{{asset('website/images/balls/balls.png" alt="balls">
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
    </section> -->
    <!--============= Trial Section Ends Here =============-->
@endsection