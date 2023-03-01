
<?php $__env->startSection('title', 'Demo - Thankyou'); ?>
<?php $__env->startSection('metas'); ?>
<meta name="description" content="Departure Cloud is India’s first Departure Management Platform for travel suppliers, tour operators, and buyers. Manage all the Departures at one place!">
    <meta name="keywords" content="About Departure Cloud, About DepartureCloud, Departure Management Platform">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('headEvent'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<section class="work-section padding-bottom bg_img mb-md-95 pb-md-0 mb-0" data-background="<?php echo e(asset('website/images/work/work-bg.jpg')); ?>" id="how" style="background-image: url(<?php echo e(asset('website/images/work/work-bg.jpg')); ?>);padding: 60px 0 !important;">
        <div class="container">
            <div class="thankyou">
                <div class="row align-items-center">
                    <div class="col-md-6 mt-4 mt-md-0 text-center">
                        <h2 class="mt-0 text-center"><i class="mdi mdi-check-all" style="color: #d54a9d;"></i></h2>
                        <h2 class="m-0 mb-2">Thank You !</h2>
                        <p style="color: #d9d9d9;line-height: 26px;font-size: 18px;">Your demo request has been received successfully. One of our team members will contact you shortly.</p>
                       
                        <a class="btn btn-custom w-auto" href="<?php echo e(route('login')); ?>">Back to Login</a>
                    </div>
                    <div class="col-md-6 order-first order-md-last">
                        <img src="<?php echo e(asset('website/images/extra/thankyou.png')); ?>" alt="thankyou" class="thankyou-img">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style type="text/css">
        .work-section{
            min-height: calc(100vh - 154px);
        }
        .thankyou{
            box-shadow: 0 0 16px rgba(0 104 231 / 70%);
            border-radius: 36px;
            padding: 0px 34px;
            background-color: rgb(40 151 240 / 70%);
        }
        .thankyou-img{
            width: 100%;
        }
        .btn-custom {
            background: -webkit-linear-gradient(0deg, #e2906e 0%, #e83a99 100%);
            border: 0;
            outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            color: #fff;
            height: auto;
        }
        .btn-custom:hover {
            background: -webkit-linear-gradient(0deg, #e83a99 0%,#e2906e 100%);
            border: 0;
            outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            height: auto;
            color: #31377D;
        }
        .header-section {
            width: 100%;
            position: sticky;
            z-index: 999;
            top: 0;
            -webkit-transition: all ease 0.3s;
            -moz-transition: all ease 0.3s;
            transition: all ease 0.3s;
            padding: 15 px 0;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('websitelayoute.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/demo_thankyou.blade.php ENDPATH**/ ?>