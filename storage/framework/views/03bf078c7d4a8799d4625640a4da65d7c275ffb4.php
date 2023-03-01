<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>"/>
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <?php echo $__env->yieldContent('metas'); ?>
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/animate.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/nice-select.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/owl.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/jquery-ui.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/magnific-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/flaticon.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('website/css/dc_frontstyle.css')); ?>">
    <link href="<?php echo e(asset('assets1/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.png')); ?>" type="image/x-icon">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-N2RCC4T');</script>
    <!-- End Google Tag Manager -->
    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '576326336869868');
      fbq('track', 'PageView');
    </script>
    <noscript>
      <img height="1" width="1" style="display:none" 
           src="https://www.facebook.com/tr?id=576326336869868&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
   
    <?php $__env->startSection('headEvent'); ?>

    <?php echo $__env->yieldSection(); ?>
    
    <?php /**PATH /var/www/departure/resources/views/websitelayoute/head.blade.php ENDPATH**/ ?>