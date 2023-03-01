@extends('websitelayoute.app')
@section('title', 'Departure Cloud Pricing Plans For Suppliers & Buyers')
@section('metas')
<meta name="description" content="Departure Cloud offers simple pricing plans to meet the needs of all travel suppliers and buyers. Choose your plan today and try for Free!">
    <meta name="keywords" content="Departure Cloud Pricing, Departure Cloud Pricing Plan, Departure Cloud Costing, Departure Cloud Premium">
@endsection
@section('content')
<!--============= About Section Starts Here =============-->
	<section class="page-header single-header bg_img oh" data-background="./assets1/images/page-header.png" style="background-image: url({{asset('assets1/images/page-header.png')}});">
        <div class="bottom-shape d-none d-md-block">
            <img src="{{asset('website/css/img/page-header2.png')}}" alt="css">
        </div>
    </section>
    <section class="pricing-section oh padding-bottom-2 single-pricing mb-5">
        <div class="container">
            <div class="section-header cl-white mw-100 mb-4">
                <h2 class="title mt-0">Simple Pricing Plan</h2>
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
            <!-- <div class="tab-up">
                <ul class="tab-menu pricing-menu">
                    <li class="active">Monthly</li>
                    <li class="">Yearly</li>
                </ul>
                <div class="tab-area">
                	<div class="tab-item active">
                		<div class="pricing-slider-wrapper">
                			<div class="pricing-slider owl-theme owl-carousel owl-loaded owl-drag">
                				<div class="pricing-item-2">
                                    <h5 class="cate">Buyer</h5>
		                            <div class="thumb">
		                                <img src="{{asset('website/images/pricing/pricing1.png')}}" alt="pricing">
		                            </div>
		                            <h2 class="title"><sup>&#8377;</sup>1500<span>Per Month/User</span></h2>
		                            <span class="info">Billed Quarterly</span>
		                            <ul class="pricing-content-3">
		                                <li>3 Users 10 GB Storage</li>
		                                <li>Monthly Updates</li>
		                                <li>eCommerce Integration</li>
		                                <li>Interface Customization</li>
		                                <li>24/7 Support</li>
		                            </ul>
		                            <a class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                                <div class="pricing-item-2">
                                	<h5 class="cate">Supplier</h5>
		                            <div class="thumb">
		                                <img src="{{asset('website/images/pricing/pricing2.png')}}" alt="pricing">
		                            </div>
		                            <h2 class="title"><sup>&#8377;</sup>3500<span>Per Month/User</span></h2>
		                            <span class="info">Billed Quarterly</span>
		                            <ul class="pricing-content-3">
		                                <li>5 Users 20 GB Storage </li>
		                                <li>Weekly Updates</li>
		                                <li>eCommerce Integration</li>
		                                <li>Interface Customization</li>
		                                <li>24/7 Support</li>
		                            </ul>
		                            <a class="get-button">Select Plan<i class="flaticon-right"></i></a>
                                </div>
                			</div>
                		</div>
                	</div>
                </div>
                
            </div>
            <div class="text-center mt-70">
                <a href="#0" class="show-feature">Show all features</a>
            </div> -->
        </div>
    </section>
@endsection
@section('footer')
@endsection