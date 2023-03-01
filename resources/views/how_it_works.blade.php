@extends('websitelayoute.app')
@section('title', 'How DepartureCloud Works for Travel Suppliers & Buyers')
@section('metas')
<meta name="description" content="Get to know How DepartureCloud works for Travel Agents, Tour Operators, Travel Suppliers and Buyers. How to buy or create departures through DepartureCloud!">
    <meta name="keywords" content="How DepartureCloud Works, How It Works, DepartureCloud Process">
@endsection
@section('content')
    <!--============= Banner Section Starts Here =============-->
    <section class="banner-20 pos-rel oh bg_img" data-background="{{asset('website/images/extra-2/banner/banner-bg-20.jpg')}}">
        <div class="container">
            <div class="row flex-wrap-reverse align-items-center justify-content-lg-between">
                <div class="col-lg-5">
                    <div class="banner-thumb-20 rtl">
                        <img src="{{asset('website/images/feature/faster.png')}}" alt="extra-2/banner" style="transform: scaleX(-1);">
                        <!-- <img src="{{asset('website/images/extra-2/banner/banner-20.png')}}" alt="extra-2/banner">
                        <a href="https://www.youtube.com/watch?v=ObZwFExwzOo" class="video-button popup">
                            <i class="flaticon-play"></i></a> -->
                    </div>
                </div>
                <div class="col-lg-6 p-lg-0">
                    <div class="banner-content-20">
                        <h1 class="title">How It Works?</h1>
                        <p>DepartureCloud works by expanding your business reach and simplifying day-to-day departures management process to boost sales. It’s a one-stop platform for all travel businesses, tour operators, and travel agents to collaborate and fulfil departure-related needs from creations to bookings.</p>
                        <!-- <a href="#0" class="button-4">Get Started</a> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Banner Section Ends Here =============-->

    

    <!--============= Logo Slider Section Starts Here =============-->
    <section class="logo-slider-section section--bg padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center mb-lg-5 mb-4" style="margin-bottom: 24px !important;">
                    <h5 class="title">Trusted by the World’s Tour Operators</h5>
                </div>
            </div>
            <p class="text-center">DepartureCloud is a go-to platform for the tour operators across India and abroad. It is a trusted platform for those who wish to manage travel departures and everything in between in one place.</p>
        </div>
    </section>

    <section class="feature-section padding-top padding-bottom oh">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="section-header mw-100">
                        <h5 class="cate">Process of Working Flow</h5>
                        <h2 class="title">Boost Your Conversion Rate with DepartureCloud.</h2>
                        <p class="mw-500">DepartureCloud is a revolutionary platform that takes your travel business to the next level!</p>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="cola-area">
                    <div class="cola-item">
                        <div class="col-md-6">
                            <div class="cola-content">
                                <h5 class="title">For Supplier:</h5>
                                <p class="title-description">DepartureCloud is an innovative marketplace for departure suppliers in the travel industry that brings departure buyers to their doorstep. It is the only platform in India that caters to the needs of travel businesses and boost sales.</p>
                                <div class="accordion" id="accordionSupplier">
                                    <div class="card">
                                        <div class="card-header p-0" id="supplier-step-1">
                                                <button class="btn btn-link btn-supplier active" type="button" data-toggle="collapse" data-target="#supplierOne" aria-expanded="true" aria-controls="supplierOne">
                                                    Create Departure
                                                </button>
                                        </div>
                                        <div id="supplierOne" class="collapse show" aria-labelledby="supplier-step-1" data-parent="#accordionSupplier">
                                            <div class="card-body">
                                                After registration, a supplier can create and manage unlimited departures. The platform offers the supplier the freedom to customize the departure details as needed.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header p-0" id="supplier-step-2">
                                            <button class="btn btn-link btn-supplier collapsed" type="button" data-toggle="collapse" data-target="#supplierTwo" aria-expanded="false" aria-controls="supplierTwo">
                                                Reach Multiple Buyers
                                            </button>
                                        </div>
                                        <div id="supplierTwo" class="collapse show" aria-labelledby="supplier-step-2" data-parent="#accordionSupplier">
                                            <div class="card-body">
                                                Once published, the supplier can reach and distribute departures to multiple buyers. The platform makes it easy for a travel business by bringing buyers to their doorstep effortlessly leading to enhanced sales.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header p-0" id="supplier-step-3">
                                            <button class="btn btn-link btn-supplier collapsed" type="button" data-toggle="collapse" data-target="#supplierThree" aria-expanded="false" aria-controls="supplierThree">
                                                Real-time Tracking
                                            </button>
                                        </div>
                                        <div id="supplierThree" class="collapse show" aria-labelledby="supplier-step-3" data-parent="#accordionSupplier">
                                            <div class="card-body">
                                                Departure Cloud allows the suppliers to track and monitor the sale of departures in real-time. Once a buyer books the departure, the supplier gets notified via the dashboard and email.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header p-0" id="supplier-step-4">
                                            <button class="btn btn-link btn-supplier collapsed" type="button" data-toggle="collapse" data-target="#supplierFour" aria-expanded="false" aria-controls="supplierFour">
                                                Payment Follow-Up
                                            </button>
                                        </div>
                                        <div id="supplierFour" class="collapse show" aria-labelledby="supplier-step-4" data-parent="#accordionSupplier">
                                            <div class="card-body">
                                                Once the departures are bought, a supplier can do a payment follow-up using the platform itself. It promotes transparency and improves the efficiency of business processing.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cola-thumb">
                                <img src="{{asset('website/images/feature/advance6.png')}}" alt="feature" style="transform: scaleX(-1);">
                            </div>
                        </div>
                    </div>
                    <div class="cola-item">
                        <div class="col-md-6">
                            <div class="cola-content">
                                <h5 class="title">For Buyer:</h5>
                                <p class="title-description">DepartureCloud offers flexibility to the buyers to choose from multiple departure options. It is India’s number #1 platform for buyers to find departures for any destination with ease.</p>
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header p-0" id="step-1">
                                                <button class="btn btn-link btn-buyer" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Access Worldwide Departures
                                                </button>
                                        </div>
                                        <div id="collapseOne" class="collapse show" aria-labelledby="step-1" data-parent="#accordionExample">
                                            <div class="card-body">
                                                Once registered, a buyer can access worldwide departures in one place. They can choose from departures published by multiple suppliers.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header p-0" id="step-2">
                                            <button class="btn btn-link btn-buyer collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Easy Online Booking
                                            </button>
                                        </div>
                                        <div id="collapseTwo" class="collapse show" aria-labelledby="step-2" data-parent="#accordionExample">
                                            <div class="card-body">
                                                The intuitive UI/UX of DepartureCloud is developed to ensure ease of accessing & booking departures online from any device.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header p-0" id="step-3">
                                            <button class="btn btn-link btn-buyer collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Faster Payments
                                            </button>
                                        </div>
                                        <div id="collapseThree" class="collapse show" aria-labelledby="step-3" data-parent="#accordionExample">
                                            <div class="card-body">
                                                Departurecloud offers fast and secure online payment methods and makes the check-out process smooth for buyers.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cola-thumb">
                                <img src="{{asset('website/images/feature/advance6.png')}}" alt="feature">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<style type="text/css">
        .footer-section::before { display: none;}
        .title-description {font-size: 16px;line-height: 24px;color: #cbcbcb;margin:0 0 16px 0;}
        .accordion .card{background-color: #cdc9fb;}
        .accordion .card .card-header button{position: relative;padding-right: 36px;text-align: left;color: #fff;font-size: 18px;line-height: 30px;text-decoration: none;font-weight: 600;letter-spacing: -.24px;background-color: #3b319e;}
        .accordion .card .card-header button.active{background-color: #202342; border-radius: 4px 4px 0 0;}
        .accordion .card .card-header button:hover{text-decoration: none;}
        .accordion .card-body {font-size: 16px;line-height: 24px;color:#cbcbcb;background-color: #202342;padding-top: 0;}
        .accordion .card .card-header button::after {
            content: "";
            position: absolute;
            width: 15px;
            height: 15px;
            display: block;
            top: 50%;
            margin-top: -12px;
            right: 12px;
            transition: all 0.3s ease 0s;
            border: 4px solid #261d76;
            border-left: 0;
            border-top: 0;
            transform: rotate(45deg);
            border-radius: 2px;
        }
        .accordion .card .card-header button.active::after {
            transform: rotate(-135deg);
            border-color: #6056c3;
            top: 50%;
            margin-top: -6px;
        }
        .cola-item .cola-content ul li::before{
            content: '\21d2';
        }
    </style>
    
@endsection
@section('footer')
    <script>
        $(document).ready(function(){
            $('.btn-buyer').click(function(){
                if($(this).hasClass('active')){
                    $('.btn-buyer').removeClass('active');
                } else {
                    $('.btn-buyer').removeClass('active');
                    $(this).addClass('active');
                }
            });
            $('.btn-supplier').click(function(){
                if($(this).hasClass('active')){
                    $('.btn-supplier').removeClass('active');
                } else {
                    $('.btn-supplier').removeClass('active');
                    $(this).addClass('active');
                }
            });
            
            $(".collapse").addClass("show");
            setTimeout(function () {
                $(".collapse").removeClass("show");
                console.log("working");
            }, 1000);
            setTimeout(function () {
                $("#supplierOne").addClass("show");
            }, 1001);
        });
    </script>
@endsection