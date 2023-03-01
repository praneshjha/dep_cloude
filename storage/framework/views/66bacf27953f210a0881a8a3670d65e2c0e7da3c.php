Dear <?php echo e($supplier->name); ?> <?php echo e($supplier->last_name); ?>,                                                                              
<p>Following Cancellation request has been submitted to supplier.</p>
<p><b>Deaparture Name: </b><?php echo e($departure->title); ?></p>
<p><b>Departure Date: </b><?php echo e(date('d M, Y', strtotime($departure->start_date))); ?></p>
<p><b>Supplier: </b><?php echo e($departure->departure_ownner); ?></p>
<p><b>No of units: </b><?php echo e($unit); ?></p>
<p><b>Cancellation charges: </b><?php echo e($deduction_amount); ?></p>
<p>To contact supplier pls <a href="https://departurecloud.com/login">Login</a> to Departure Cloud.</p>
<?php /**PATH /var/www/html/departurecloud/resources/views/mail/supplier_cancelation_mail.blade.php ENDPATH**/ ?>