<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Verification - Departure Cloud</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="d7p8Jbza8WCPOmfSptSHgkqcsJF8vNQzhZ0LCNHi">
        <!-- Scripts -->
        <link rel="shortcut icon" href="https://departurecloud.com/favicon.png">

        <link href="https://departurecloud.com/assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://departurecloud.com/assets1/css/icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://departurecloud.com/assets1/css/app.min.css" rel="stylesheet" type="text/css"/>

    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">

                            <div class="card-body p-4">
                                <div class="text-center w-75 m-auto">
                                    <a href="javascript:void(0);">
                                        <span><img src="https://departurecloud.com/departure-cloud-logo.png" alt="" height="50"></span>
                                    </a>
                                </div>
                                <div class="text-center">
                                    <h4 class="text-thankyou">E-mail Verified Successfully</h4>
                                    <p style="font-weight:bold;">You will get a notification once your account is approved from  our end!</p>
                                    <div class="form-group mb-0 text-center mt-2">
                                        <!-- <button class="btn btn-primary btn-block">OK</button>
                                    </div> -->
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="btn btn-primary btn-block">
                                          OK
                                        </a>
                                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <footer class="footer footer-alt">
            <script>document.write(new Date().getFullYear())</script> © <span class="text-white">www.departurecloud.com</span>
        </footer>
        <script src="https://departurecloud.com/assets1/js/vendor.min.js"></script>
        <script src="https://departurecloud.com/assets1/js/app.min.js"></script>
        <style>
            .text-thankyou{
                color: #1f81fd;
                font-size: 26px;
                font-weight: 700;
                margin: 36px 0 12px;
            }
            .account-pages{
                min-height: calc(100vh - 204px);
                display: flex;
                align-items: center;
            }
        </style>
        
    </body>
</html>