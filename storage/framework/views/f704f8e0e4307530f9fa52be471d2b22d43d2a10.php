Dear <b><?php echo e(auth()->user()->name); ?> <?php echo e(auth()->user()->last_name); ?></b>
<p><?php echo e($book_seat); ?> units are booked for you in Departure.</p>
<p><b>Deaparture Name: </b> <?php echo e($departure->title); ?></p>
<p><b>Departure Date: </b> <?php echo e(date('d M, Y', strtotime($departure->start_date))); ?></p>
<p><b>Supplier: </b> <?php echo e($fname); ?> <?php echo e($lname); ?></p>
<p><b>No of units booked: </b> <?php echo e($book_seat); ?></p>
<b>Booking Date & Time: </b> <?php echo e(date('d M, Y h:i A', strtotime("+5 hours +30 minutes"))); ?>

<p><b>Booking Details: </b></p>
<table border="1">
    <tr>
        <?php if(in_array(32, json_decode($columns))): ?>
            <th>Room Sharing</th>
        <?php endif; ?>
        <?php if(in_array(33, json_decode($columns))): ?>
            <th>Flight Class</th>
            <th>Age Bracket</th>
        <?php endif; ?>
        <?php if(in_array(35, json_decode($columns))): ?>
            <th>Hotel Name</th>
            <th>Hotel Category</th>
        <?php endif; ?>
        <?php if(in_array(36, json_decode($columns))): ?>
            <th>Transport Type</th>
        <?php endif; ?>
            <th>Airport Transfers</th>
        <?php if(in_array(38, json_decode($columns))): ?>
            <th>Meal Plan</th>
        <?php endif; ?>
        <th>Price</th>
    </tr>
    <?php $__currentLoopData = $book_details_mail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <?php if(in_array(32, json_decode($columns))): ?>
                <td><?php echo e($row->sairing); ?></td>
            <?php endif; ?>
            <?php if(in_array(33, json_decode($columns))): ?>
                <td><?php echo e($row->flight_class); ?></td>
                <td><?php echo e($row->passenger); ?></td>
            <?php endif; ?>
            <?php if(in_array(35, json_decode($columns))): ?>
                <td><?php echo e($row->hotel_name); ?></td>
                <td><?php echo e($row->hotel_type); ?></td>
            <?php endif; ?>
            <?php if(in_array(36, json_decode($columns))): ?>
                <td><?php echo e($row->transport_type); ?></td>
            <?php endif; ?>
            <td><?php echo e($row->airport_transfers); ?></td>
            <?php if(in_array(38, json_decode($columns))): ?>
                <td><?php echo e($row->meal_plan); ?></td>
            <?php endif; ?>
            <td><?php echo e($row->currency_symbol); ?> <?php echo e(($row->price)); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>

<p><b>Payment Schedule: </b></p>
	<table border="1">
        <?php $count = 0;?>
        <?php $__currentLoopData = $payment_schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php  
                    if(date('Y-m-d') > $row->date)
                    {
                    $count = $count + $row->percentage;
                    }
            ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php
            echo '<th>Minimum Booking Amount</th><td>'.$currency .'-' .number_format(($price * $count)/100).'</td>';
        ?>
    <?php $__currentLoopData = $payment_schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($key == 0): ?>
            <?php if(date('Y-m-d') < $row->date): ?>
             <th>Minimum Booking Amount</th> : <td><?php echo e($currency); ?> - <?php echo e(number_format(($price * $row->percentage)/100)); ?></td>
            <?php endif; ?>  
        <?php endif; ?>
        <?php if($key > 0): ?>

        <?php if($key == 0): ?>
        <?php endif; ?>

        <?php if(date('Y-m-d') > $row->date): ?>
           <tr>
                <td><?php echo e(date('d M, Y', strtotime($row->date))); ?></td>
                <td><?php echo e($currency); ?> <?php echo e(number_format(($price * $row->percentage)/100)); ?></td>
           </tr>
        <?php else: ?>
            <tr>
            	<td><?php echo e(date('d M, Y', strtotime($row->date))); ?></td>
            	<td><?php echo e($currency); ?> <?php echo e(number_format(($price * $row->percentage)/100)); ?></td>
            </tr>
        <?php endif; ?>
           
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>

<p><b>Cancellation Schedule: </b></p>
	<table border="1">
        <?php $count = 0;?>
        <?php $__currentLoopData = $cancel_schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cancel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php  
                    if(date('Y-m-d') > $cancel->date)
                    {
                    $count = $count + $cancel->percentage;
                    }
            ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php
            echo '<th>Minimum Cancelation Charge</th><td>'.$currency .'-' .number_format(($price * $count)/100).'</td>';
        ?>
    <?php $__currentLoopData = $cancel_schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$cancel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($key == 0): ?>
            <?php if(date('Y-m-d') < $cancel->date): ?>
             <th>Minimum Cancelation Charge</th> : <td><?php echo e($currency); ?> - <?php echo e(number_format(($price * $cancel->percentage)/100)); ?></td>
            <?php endif; ?>  
        <?php endif; ?>
        <?php if($key > 0): ?>

        <?php if($key == 0): ?>
        <?php endif; ?>

        <?php if(date('Y-m-d') > $cancel->date): ?>
           <tr>
                <td><?php echo e(date('d M, Y', strtotime($cancel->date))); ?></td>
                <td><?php echo e($currency); ?> <?php echo e(number_format(($price * $cancel->percentage)/100)); ?></td>
           </tr>
        <?php else: ?>
            <tr>
            	<td><?php echo e(date('d M, Y', strtotime($cancel->date))); ?></td>
            	<td><?php echo e($currency); ?> <?php echo e(number_format(($price * $cancel->percentage)/100)); ?></td>
            </tr>
        <?php endif; ?>
           
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<p><b>For More details pls click here: </b> <a href="http://departurecloud.com/login">Login Now</a></p>
<?php /**PATH /var/www/html/departurecloud/resources/views/mail/buyer_book_departure_mail_new.blade.php ENDPATH**/ ?>