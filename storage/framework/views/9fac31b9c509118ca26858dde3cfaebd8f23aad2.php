<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
            <?php echo $__env->make('public_layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 
    </head>
    <body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
        
            <?php echo $__env->make('public_layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              
            <div class="main-container" id="container">
                <div id="content" class="main-content">
                    <?php $__env->startSection('content'); ?>
                    <?php echo $__env->yieldSection(); ?>

                </div>
                <!--  END CONTENT AREA  -->
            </div>
        
 
    <?php echo $__env->make('public_layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>

    </html>
<?php /**PATH /var/www/html/departurecloud/resources/views/public_layouts/app.blade.php ENDPATH**/ ?>