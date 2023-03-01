<?php //dd($departures); ?> 
    <?php if(count($departures)> 0): ?>
        <?php $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                if(is_null($dates))
                {
                    $dates = 0;
                }
                    $diff=Date('Y-m-d', strtotime('+'.$dates.' days'));
                //dd($diff.'/'.$departure->start_date);
            ?>
            <?php echo $__env->make('dashboard.data', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <div class="col-xl-4 col-lg-5 col-md-6 mx-auto text-center" style="text-align:center;">
        <div class="card">
            <h3>Departure not Found</h3>
        </div>
    </div>
<?php endif; ?>
<script>
function openBookingModal(departure) {
    console.log(departure,'book');
        $.ajax({
        headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "<?php echo e(url('/get_booking_modal')); ?>",
        data:departure,
        success: function (res) { 
            $("#booking_modal_form").html(res);
            }
        });
    }
function openHoldModal(departure) {
    console.log(departure,'hold');
    $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url: "<?php echo e(url('/get_hold_modal')); ?>",
    data:departure,
    success: function (res) { 
        $("#hold_modal_form").html(res);
            }
        });
    }
</script><?php /**PATH E:\xampp\htdocs\departurecloud\resources\views/dashboard/card.blade.php ENDPATH**/ ?>