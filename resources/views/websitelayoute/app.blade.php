<!DOCTYPE html>
<html lang="en">
<head>
    @include('websitelayoute.head')
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
        @include('websitelayoute.header')
    </header>
    
    @section('content')
    @show
    @include('websitelayoute.footer')
</body>

</html>