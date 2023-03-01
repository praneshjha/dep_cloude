<?php $__env->startSection('title', 'Contact Us - Departure Cloud'); ?>
<?php $__env->startSection('metas'); ?>
<meta name="description" content="Have queries or suggestions about Departure Cloud? Please fill up the enquiry form and we will get back to you shortly Or reach us at contact@watconsultingservices.com.">
    <meta name="keywords" content="Contact Us, Contact Departure Cloud, Contact DepartureCloud">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!--============= Header Section Ends Here =============-->
    <section class="page-header single-header bg_img oh" data-background="<?php echo e(asset('website/images/page-header.png')); ?>">
        <div class="bottom-shape d-none d-md-block">
            <img src="<?php echo e(asset('website/css/img/page-header.png')); ?>" alt="css">
        </div>
    </section>
    <!--============= Header Section Ends Here =============-->


    
    <!--============= Contact Section Starts Here =============-->
    <section class="contact-section padding-top padding-bottom">
        <div class="container">
            <div class="section-header mw-100 cl-white">
                <h2 class="title">Contact Us</h2>
                <p style="max-width: 600px;" class="mx-auto">Want to know more about DepartureCloud and how it can improve your travel business? Reach out to us.</p>
            </div>
            <div class="row justify-content-center justify-content-lg-between">
                <div class="col-lg-7">
                    <div class="contact-wrapper">
                        <h4 class="title text-center mb-30">Get in Touch</h4>
                        <form class="contact-form" id="contactForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="company_name">Your Company Name</label>
                                <input type="text" placeholder="Enter Your Company Name" id="company_name"  name="company_name" autocomplete="off">
                                <span class="validationError" id="company_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Your Full Name</label>
                                <input type="text" placeholder="Enter Your Full Name" id="name" name="name" autocomplete="off">
                                <span class="validationError" id="name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" placeholder="Enter Your Phone Number " id="phone" name="phone" autocomplete="off">
                                <span class="validationError" id="phDigit"></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Your Email </label>
                                <input type="email" placeholder="Enter Your Email " id="email" name="email" autocomplete="off">
                                <span class="validationError" id="emailID_error"></span>
                            </div>
                            <div class="form-group mb-0">
                                <div class="form-check">
                                    <input type="checkbox" id="check" checked required=""><label for="check">I agree to receive emails, newsletters and promotional messages</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-center"  style="margin-top: -45px;">
                                <button type="submit" class="btn btn-block btn-custom w-auto mx-auto inlinecode" id="store_form" style="min-width: 130px;">Send Message</button>
                                <img class="inlinecode" src="<?php echo e(asset('assets1/images/loading.gif')); ?>" id="gif" style="width: 7%; visibility: hidden;">
                                <span class="text-success" id="mesegese" style="text-align: center;"></span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-content">
                        <div class="man d-lg-block d-none">
                            <img src="<?php echo e(asset('website/images/contact/man.png')); ?>" alt="bg">
                        </div>
                        <div class="section-header left-style">
                            <h3 class="title">Have questions?</h3>
                            <p>Have questions about payments or price plans? Let’s connect</p>
                        </div>
                        <div class="contact-area">
                            <div class="contact-item">
                                <div class="contact-thumb">
                                    <img src="<?php echo e(asset('website/images/contact/contact1.png')); ?>" alt="contact">
                                </div>
                                <div class="contact-contact">
                                    <h5 class="subtitle">Email Us</h5>
                                    <a href="Mailto:contact@watconsultingservices.com">contact@watconsultingservices.com</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-thumb">
                                    <img src="<?php echo e(asset('website/images/contact/contact2.png')); ?>" alt="contact">
                                </div>
                                <div class="contact-contact">
                                    <h5 class="subtitle">Call Us</h5>
                                    <a href="Tel:+917303559121">+91 (730) 355-91-21</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-thumb">
                                    <img src="<?php echo e(asset('website/images/contact/contact3.png')); ?>" alt="contact">
                                </div>
                                <div class="contact-contact">
                                    <h5 class="subtitle">Visit Us</h5>
                                    <p>1005 10th Floor Kanchenjunga Building, 18, Barakhamba Rd, Connaught Place, New Delhi, Delhi 110001</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style type="text/css">
        .inlinecode {
        display: inline;
    }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
<script type="text/javascript">
    $('#store_form').click(function (e) {
        e.preventDefault();
        $('#gif').show();
        var name = $('#name').val();
        if (name == "") {
          $("span#name_error").html('This field is required!');
          $("input#name").focus();
          return false;
        }
        var company = $('#comapany_name').val();
        if (company == "") {
          $("span#name_error").hide();
          $("span#company_error").html('This field is required!');
          $("input#comapany_name").focus();
          return false;
        }
        var emailID = $('#email').val();
        if (emailID == "") {
            $("span#name_error").hide();
            $("span#company_error").hide();
            $("span#emailID_error").html('This field is required!');
            $("input#email").focus();
            return false;
        }
        var phNumber = $('#phone').val();
        if (phNumber == "") {
          $("span#emailID_error").hide();
          $("span#name_error").hide();
          $("span#company_error").hide();
          $("span#phDigit").html('This field is required!');
          $("input#phone").focus();
          return false;
        }
        $('#gif').css('visibility', 'visible');
        var formDatas = new FormData(document.getElementById('contactForm'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "<?php echo e(route('contact_store')); ?>",
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#gif').hide();
                $('#mesegese').html("<span class='sussecmsg'>Mail sent Successfully!</span>");
                window.location = data.url;
                //window.location.reload();
            },
            errors: function () {

            }
        });
     });
</script>
   <script src="https://maps.google.com/maps/api/js?key=AIzaSyCo_pcAdFNbTDCAvMwAD19oRTuEmb9M50c"></script>
   <script src="<?php echo e(asset('website/js/map.js')); ?>"></script>
    <script src="<?php echo e(asset('website/js/contact.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('websitelayoute.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/departure/resources/views/contact_us.blade.php ENDPATH**/ ?>