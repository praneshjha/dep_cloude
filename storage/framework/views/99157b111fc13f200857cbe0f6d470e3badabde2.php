
<?php $__env->startSection('title', 'Departure Cloud Demo - Schedule a FREE Demo'); ?>
<?php $__env->startSection('metas'); ?>
<meta name="description" content="Departure Cloud Demo - Fill the details to Schedule a FREE Demo for Departure Cloud platform. Or Contact us at +91-7303559121 for Product Demo!">
    <meta name="keywords" content="Departure Cloud Demo, Contact for Demo, Free Departure Cloud Demo">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!--============= About Section Starts Here =============-->
    <section class="about-section padding-top padding-bottom oh">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-7">
                    <div class="about-thumb rtl pr-xl-15 mx-auto mx-lx-0">
                        <img src="https://tlakapp.com/assets/images/timing.png" alt="" style="max-width: 864px;">
                    </div>
                </div>
                <div class="col-xl-5 pl-xl-0">
                    <div class="about-content">
                        <div style="width:100%;max-width: 426px;" class="ml-lg-auto mx-auto">
                            <div class="demo-form">
                                <form method="POST" id="demoForm">
                                    <?php echo csrf_field(); ?>
                                    <h1 class="text-center" style="color: #fff;font-family: sans-serif;font-size: 28px;">Request a Demo</h1>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input id="name" type="text" class="form-control " name="name" autofocus autocomplete="off" />
                                        <input type="hidden" id="tfc_campaign_name" name="tfc_campaign_name" value="tfc_campaign_name">
                                        <span class="validationError" id="name_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="cname">Company Name</label>
                                        <input id="comapany_name" type="text" class="form-control " name="company_name" autocomplete="off" />
                                        <span class="validationError" id="company_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control " name="email" autocomplete="off" />
                                        <span class="validationError" id="emailID_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input id="phone" type="text" class="form-control " name="phone" autocomplete="off" />
                                        <span class="validationError" id="phDigit"></span>
                                    </div>
                                    
                                    <div class="form-group mb-0 text-center">
                                        <button type="submit" class="btn btn-block btn-custom w-auto mx-auto inlinecode" id="store_form" style="min-width: 130px;">submit</button>
                                        <img class="inlinecode" src="<?php echo e(asset('assets1/images/loading.gif')); ?>" id="gif" style="width: 7%; visibility: hidden;">
                                        <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
    .inlinecode {
        display: inline;
    }
        .demo-form{
            padding: 24px 16px;
            background-color: #31377d;
            border-radius: 6px;
        }
        .btn-custom{
            background: -webkit-linear-gradient(0deg, #e2906e 0%, #e83a99 100%);
            border: 0;outline: 0;
            box-shadow: 2.419px 9.703px 12.48px 0.52px rgb(232 58 153 / 50%);
            border-radius: 3px;
            color: #fff;
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
        label{
            color: #ebebeb;
            font-size: 15px;
            margin-bottom: 4px;
        }
        .form-group{
            margin-bottom: 6px;
        }
        .form-control{
            padding: 6px 12px;
            height: auto;
            margin-bottom: 2px;
        }
        .validationError {
            font-size: 16px;
            color: #c33636;
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
        var formDatas = new FormData(document.getElementById('demoForm'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "<?php echo e(route('demo_store')); ?>",
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#gif').hide();
                $('#mesegese').html("<span class='sussecmsg'>Demo sent Successfully!</span>");
                window.location = data.url;
                //window.location.reload();
            },
            errors: function () {

            }
        });
     });
</script>
    <script>
    
        window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            toolTip: {
                shared: true
            },
            legend: {
                fontSize: 0
            },
            backgroundColor: "transparent",
            color: "#ffffff",
            data: [{
                type: "splineArea",
                showInLegend: true,
                name: "Income",
                yValueFormatString: "$#,#000",
                xValueFormatString: "YYYY",
                dataPoints: [
                    { x: new Date(2015, 1), y: 0 },
                    { x: new Date(2016, 1), y: 1000 },
                    { x: new Date(2017, 1), y: 700 },
                    { x: new Date(2018, 1), y: 1400 },
                    { x: new Date(2019, 1), y: 1500 },
                    { x: new Date(2021, 1), y: 1000 },
                ]
            },
            {
                type: "splineArea", 
                showInLegend: true,
                yValueFormatString: "#k",      
                name: "Active Members",
                dataPoints: [
                    { x: new Date(2015, 1), y: 0 },
                    { x: new Date(2016, 1), y: 400 },
                    { x: new Date(2017, 1), y: 1000 },
                    { x: new Date(2018, 1), y: 1000 },
                    { x: new Date(2019, 1), y: 2000 },
                    { x: new Date(2021, 1), y: 2200 },
                ]
            }]
        });
        chart.render();

        }
      
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('websitelayoute.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/demo.blade.php ENDPATH**/ ?>