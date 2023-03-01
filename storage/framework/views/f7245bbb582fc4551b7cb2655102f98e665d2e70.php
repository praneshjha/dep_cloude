<?php if(Auth::user()->email_verified_at == true && Auth::user()->verified == 0): ?>
    <?php echo $__env->make('layouts.approval', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 
    </head>
    <body class="loading" data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N2RCC4T"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
            <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php if(Request::route()->getName()!='chat_room' && Request::route()->getName()!='chat_room_details' ): ?>
            <div id="app">
                <div id="chat_compon" style="display: none;">
                     <dep-chat-component  :auth-user="<?php echo e(auth()->user()); ?>"></dep-chat-component>
                </div>
            </div>
             <?php endif; ?>
              
            <div class="main-container" id="container">
                <div id="content" class="main-content">
                    <?php $__env->startSection('content'); ?>
                    <?php echo $__env->yieldSection(); ?>

                </div>
                <!--  END CONTENT AREA  -->
            </div>
        
 
    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>

    </html>
<?php endif; ?><?php /**PATH /var/www/html/departurecloud/resources/views/layouts/app.blade.php ENDPATH**/ ?>