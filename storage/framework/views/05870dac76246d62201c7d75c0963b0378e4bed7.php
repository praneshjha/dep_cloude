<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $__env->make('websitelayoute.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            <img src="<?php echo e(asset('favicon.png')); ?>" alt="" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);margin-top: -4px;width: 55px;z-index: 999;">
        </div>
    </div>
    <a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
    <div class="overlay"></div>
    <!--============= ScrollToTop Section Ends Here =============-->


    <!--============= Header Section Starts Here =============-->
    <header class="header-section">
        <?php echo $__env->make('websitelayoute.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </header>
    
    <?php $__env->startSection('content'); ?>
    <?php echo $__env->yieldSection(); ?>
    <?php echo $__env->make('websitelayoute.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html><?php /**PATH /var/www/html/departurecloud/resources/views/websitelayoute/app.blade.php ENDPATH**/ ?>