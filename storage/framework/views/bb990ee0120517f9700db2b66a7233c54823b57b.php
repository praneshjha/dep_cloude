<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title>DepartureCloud Login - Buy or Manage Your Live Departures</title>
        <meta name="description" content="Sign in to your DepartureCloud account to buy, publish or manage all your Departures. Enter the email id and password credentials to Log-In.">
        <meta name="keywords" content="DepartureCloud Login, DepartureCloud Sign In, Login DepartureCloud, Access Departure Cloud">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="UexczE4Qsy278K77C6dmWd5vCCaqDDeNh1CbJNSc">
        <!-- Scripts -->
        <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/bootstrap.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/all.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/animate.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/nice-select.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/owl.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/jquery-ui.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/magnific-popup.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/flaticon.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/css/main.css')); ?>">
    
        <link href="<?php echo e(asset('assets1/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(asset('assets1/css/icons.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(asset('assets1/css/app.min.css')); ?>" rel="stylesheet" type="text/css"/>
    
        <style>
            .showpass svg{position: absolute;bottom: 10px;right: 10px;}
            .mb-5, .my-5 {
                margin-bottom: 1rem!important;
            }
        
            .mt-5, .my-5 {
                margin-top: 1.5rem!important;
            }
            .card-body p-4{
                padding: 1.25rem!important;
            }
            
        .btn-custom{
            background: -webkit-linear-gradient(0deg, #e2906e 0%, #e83a99 100%);
            border: 0;outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            height: auto    ;
        }
        .btn-custom:hover{
            background: -webkit-linear-gradient(0deg, #e83a99 0%,#e2906e 100%);
            border: 0;outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            height: auto    ;
            color: #31377D;
        }
        .register-page{
            position: relative;right: 0;background-color: #31377d;
        }
        @media    only screen and (min-width: 768px) {
            .register-page{
                width: calc(100% - 60%);
            }
        }
        @media    only screen and (max-width: 768px) {
            .register-page{
                width: 100%;position: relative;right: 0;top: 0;padding: 24px 0;
            }
        }
        .register-box{
            min-height: calc(100vh - 71px);overflow-y: auto;
        }
            label{
                color: #ebebeb;
                font-size: 15px;
                margin-bottom: 4px;
            }
    
            .header-section{
                background: #3b319e;
                top: 0;
                height: 71px;
                position: sticky;
            }
            .form-group{
                margin-bottom: 6px;
            }
            .form-control{
                padding: 6px 12px;
                height: auto;
                margin-bottom: 2px;
            }
            #password{
                padding-right: 34px;
            }
            .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
                background-color: #685fc7;
                border: 0;
            }
            .departure-signup{
                color: #fff;
                text-align: center;
                padding-top: 16px;
                margin: 28px auto 0;
                position: relative;
                max-width: 270px;
            }
            .departure-signup:before{
                content: '';
                position: absolute;
                width: 140px;
                outline: 1px solid #ababab;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
            }
            .departure-signup span{
                position: absolute;
                top: -10px;
                left: 50%;
                line-height: 0;
                background-color: #31377d;
                color: #fff;
                width: 23px;
                height: 16px;
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
                transform: translateX(-50%);
            }
            #hidepsd{display: none;}
        </style>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-N2RCC4T');</script>
        <!-- End Google Tag Manager -->

    </head>
    
    <body  class="pb-0" style="background-image: url(<?php echo e(asset('website/images/account-bg.jpg')); ?>);background-size: cover;">
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
                <img src="<?php echo e(asset('favicon.png')); ?>" alt="" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);margin-top: -4px;width: 55px;z-index: 999;">
            </div>
        </div>
        <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
        <div class="overlay"></div>

        <header class="header-section">
            <div class="container">
                <div class="header-wrapper">
                    <div class="logo">
                        <a href="<?php echo e(url('/')); ?>">
                            <img src="<?php echo e(asset('website/images/departure-cloud-logo.png')); ?>" alt="logo">
                        </a>
                    </div>
                    <ul class="menu mb-0">
                       <li>
                            <a href="<?php echo e(url('/')); ?>">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/about-us')); ?>">About</a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/how-it-works')); ?>">How It Works</a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/demo')); ?>">Demo</a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('contact-us')); ?>">Contact</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('login')); ?>">Login</a>
                        </li>
                        <li class="d-sm-none">
                            <a href="<?php echo e(route('register')); ?>" class="m-0 header-button">Register</a>
                        </li>
                    </ul>
                    <div class="header-bar d-lg-none">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="header-right">
                        <a href="<?php echo e(route('register')); ?>" class="header-button d-none d-sm-inline-block">Register</a>
                    </div>
                </div>
            </div>
        </header>

            <div class="d-md-flex  justify-content-between register-box">
                <div class="d-flex align-items-center justify-content-center mx-auto">
                    <div class="py-4">
                        <!-- <div class="text-center">
                            <a href="https://www.departurecloud.com" >
                                <img src="<?php echo e(asset('departure-cloud-logo.png')); ?>" alt="" height="56">
                            </a>
                        </div> -->
                        <div style="max-width: 550px;" class="text-center mx-auto px-3 px-md-0">
                            <img src="<?php echo e(asset('login.png')); ?>" alt="" style="width: 100%;">
                            <!-- <p class="mb-2 mt-3">Enter your email address and password to access admin panel.</p> -->
                            <!-- <p class="" style="color: #681F4A;"><strong>Don't have an account?</strong> <a href="https://www.departurecloud.com/register" class="ml-1" style="color: #0067e9;"><b>Sign Up</b></a></p> -->
                        </div>
                    </div>
                </div>
                <div class="register-page px-2 d-flex align-items-center justify-content-center">
                    <div  class="w-100 p-2">
                        <h1 class="text-center" style="color: #fff;font-family: sans-serif;font-size: 28px;">Login With Us!</h1>
                        <p class="text-center mb-3" style="color: #ebebeb;">Enter your email address and password to get started.</p>
                        <form method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="email"><?php echo e(__('E-Mail Address')); ?></label>
                                <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group mb-2 position-relative showpass">
                                <label for="password"><?php echo e(__('Password')); ?></label>
                                <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">
                                <svg id="showpsd" xmlns="http://www.w3.org/2000/svg" style="position:absolute;right:10px;bottom:11px" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye" onclick="myFunction()"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg id="hidepsd" x="1104" y="144" viewBox="0 0 24 24" fit="" height="18" width="18" preserveAspectRatio="xMidYMid meet" focusable="false" style="position:absolute;right:10px;bottom:11px" onclick="myFunction()">
                                    <path fill="none" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor" d="M17.94 17.94A9.995 9.995 0 0112.009 20H12c-7 0-11-8-11-8a18.605 18.605 0 015.017-5.908l.043-.032M9.9 4.24A8.865 8.865 0 0111.979 4h.023-.001c7 0 11 8 11 8a18.62 18.62 0 01-2.182 3.217l.022-.027m-6.721-1.07a3 3 0 11-4.242-4.238l.002-.002M1 1l22 22"></path>
                                </svg>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group mb-3 d-flex justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                    <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                </div>
                                <p> <a href="<?php echo e(route('password.request')); ?>" class="ml-1">Forgot your password?</a></p>
                            </div>
                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="btn btn-block btn-custom w-auto mx-auto" value="" style="min-width: 130px;">Log In</button>
                            </div>
                        </form>
                        <p class="departure-signup"><strong>Don't have an account?</strong> <a href="<?php echo e(route('register')); ?>" class="ml-1" style="color: #f1bd86;"><b>Sign Up</b></a><span>or</span></p>
                    </div>
                </div>
            </div>
    
    
    <script src="<?php echo e(asset('website/js/jquery-3.3.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/modernizr-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/plugins.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/magnific-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/wow.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/waypoints.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/nice-select.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/owl.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/counterup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/paroller.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/main.js')); ?>"></script>
    <script src="<?php echo e(asset('assets1/js/vendor.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets1/js/app.min.js')); ?>"></script>
    <script>
        function myFunction() {
            var x = document.getElementById("password");
            var a = document.getElementById("showpsd");
            var b = document.getElementById("hidepsd");
            if (x.type === "password") {
                x.type = "text";
                a.style.display="none";
                b.style.display="inline-block";
            } else {
                x.type = "password";
                a.style.display="block";
                b.style.display="none";
            }
        }
    </script>
    </body>
    
    </html>
    <?php /**PATH /var/www/departure/resources/views/auth/login.blade.php ENDPATH**/ ?>