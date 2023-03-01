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
                <a href="<?php echo e(url('/pricing')); ?>">Pricing</a>
            </li>
            <li>
                <a href="<?php echo e(url('/demo')); ?>">Demo</a>
            </li>
            <li>
                <a href="<?php echo e(url('contact-us')); ?>">Contact</a>
            </li>    
             <?php if(Route::has('login')): ?>
                <?php if(auth()->guard()->check()): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img src="<?php if(isset(auth::user()->logo)): ?><?php echo e(asset('companyLogo/' . Auth::user()->logo)); ?> <?php else: ?> <?php echo e(asset('images/no-image.png')); ?> <?php endif; ?>" style="width:32px; height:32px; border-radius:50%;">
                  <?php echo e(auth::user()->name); ?>

                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <?php if(Auth::user()->main_user_type == 2): ?>
                        <?php else: ?>
                        <div class="dropdown-header noti-title">      
                            <small> Welcome !
                                   <?php if(Auth::user()->main_user_type == 1): ?> (Supplier) <?php elseif(Auth::user()->main_user_type == 0): ?> (Buyer) <?php endif; ?>
                            </small>
                        </div>
                                
                        <?php endif; ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="dropdown-item">
                            <i class="fe-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="fe-log-out"></i>
                            <span> Logout</span>
                        </a>
                        <form id="frm-logout" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                </div>
              </li>
                <?php else: ?>  
                <li>
                    <a href="<?php echo e(route('login')); ?>">Login</a>
                </li>
                <li class="d-sm-none">
                <a href="<?php echo e(route('register')); ?>" class="m-0 header-button">Register</a>
            </li>
                <?php endif; ?>
            <?php endif; ?>

            
        </ul>
        <div class="header-bar d-lg-none">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <?php if(Route::has('login')): ?>
            <?php if(auth()->guard()->check()): ?>
                <?php else: ?>
        <div class="header-right">
            <a href="<?php echo e(route('register')); ?>" class="header-button d-none d-sm-inline-block">Register</a>
        </div>
          <?php endif; ?>
        <?php endif; ?>
    </div>
</div><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/websitelayoute/header.blade.php ENDPATH**/ ?>