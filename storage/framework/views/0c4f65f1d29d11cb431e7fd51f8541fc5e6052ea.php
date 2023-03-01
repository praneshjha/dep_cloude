<head>
        <meta charset="utf-8">
        <title>Page not Found</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>">

        <!-- App css -->
        <link href="<?php echo e(asset('404/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('404/css/icons.min.css')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('404/css/app.min.css')); ?>" rel="stylesheet" type="text/css">

    </head>
<body>
    <div class="BodyClass">
        <!-- <div class="content-page"> -->
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">
                
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-xl-4 mb-4" style="margin-top:100px">
                        <div class="error-text-box">
                            <svg viewBox="0 0 600 200">
                                <!-- Symbol-->
                                <symbol id="s-text">
                                    <text text-anchor="middle" x="50%" y="50%" dy=".35em">404!</text>
                                </symbol>
                                <!-- Duplicate symbols-->
                                <use class="text" xlink:href="#s-text"></use>
                                <use class="text" xlink:href="#s-text"></use>
                                <use class="text" xlink:href="#s-text"></use>
                                <use class="text" xlink:href="#s-text"></use>
                                <use class="text" xlink:href="#s-text"></use>
                            </svg>
                        </div>
                        <div class="text-center">
                            <h3 class="mt-0 mb-2">Whoops! Page not found</h3>
                            <p class="text-muted mb-3">Sorry, Your request could not be processed. It's looking like you may have taken a wrong turn.</p>
                           
                            <a href="<?php echo e(route('all_departure')); ?>" type="button" class="btn btn-success waves-effect waves-light">Back</a>
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                
            </div> <!-- container -->

        </div> <!-- content -->  
    </div>

<!-- Vendor js -->
<script src="<?php echo e(asset('404/js/vendor.min.js')); ?>"></script>

<!-- App js -->
<script src="<?php echo e(asset('404/js/app.min.js')); ?>"></script>
<style type="text/css">
   /* body{
        background-image: url(../images/Travel.jpg);
        background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center;
    }*/
</style>
</body>
<?php /**PATH /var/www/html/departurecloud/resources/views/404.blade.php ENDPATH**/ ?>