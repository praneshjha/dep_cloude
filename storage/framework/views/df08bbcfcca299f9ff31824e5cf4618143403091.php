<?php $__env->startSection('tagSection'); ?>
<title>Booking History | Departure Cloud</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Booking History</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Booking History</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <nav class="position-relative">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="<?php echo e(route('departure_booking_history',$id)); ?>" class="nav-link active"> Confirmed</a></li>
                                        <li class="nav-item"><a href="<?php echo e(route('departure_hold_history',$id)); ?>" class="nav-link "> Hold </a></li>
                                    </ul>
                                    <a href="<?php echo e(route('all_departure_booking_history')); ?>" class="btn btn-info btn-sm" style="position:absolute;top: 0;right: 0;">All Booking</a>
                                </nav>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead >
                                              <tr>
                                              <th>Sl.</th>
                                              <th>Dep. Name</th>
                                              <th>Buyer/Booker Name</th>
                                              <th>Booking Date & time</th>
                                              <th>Booking Units</th>
                                              <th>Total Price</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                              </tr>
                                         </thead>
                                          <tbody>
                                          
                                          <?php $__currentLoopData = $book_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                              <td><?php echo e($key+1); ?></th>
                                                <td><a href="<?php echo e(route('departure_details',$id)); ?>"><?php echo e($row->departure_name); ?></td>
                                              <td><?php echo e($row->company_name); ?>/<?php echo e($row->name); ?></td>
                                              <td><?php echo e(date('d-M-Y h:i a', strtotime($row->booked_value->created_at."+5 hours +30 minutes"))); ?></td>
                                              <td><?php echo e($row->booked_seat); ?>

                                              </td>
                                              <td><?php echo e($row->currency->currency_symbol); ?>   <?php echo e($row->price); ?>

                                              </td>
                                              <td>
                                                  <?php if($row->booked_value->status == 1): ?>
                                                   Confirm
                                                  <?php else: ?>
                                                    Cancel
                                                  <?php endif; ?>
                                              </td>
                                              <td>
                                                <a href="<?php echo e(url('/departures-booking-history-details/'.$row->unique_id)); ?>" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                <?php //dd($row->booked_value->departure_id); ?>
                                                <?php if($row->booked_value->status == 1): ?>
                                                    <a  class="cancel text-danger display_loader" title="Cancel Booking" data-unique_id="<?php echo e($row->booked_value->unique_id); ?>" data-departure_id="<?php echo e($row->booked_value->departure_id); ?>" ><i class="fa fa-window-close"></i></a>
                                                <?php endif; ?>
                                              </td>
                                            </tr>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          
                                          </tbody>
                                      </table>
                                      <?php echo e($book_date->links()); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(".cancel").click(function () {
        if (confirm("Are you sure you want to cancel this booking?"))
        var unique_id = $(this).data("unique_id");
        var departure_id = $(this).data("departure_id");
        var token = "<?php echo e(csrf_token()); ?>";
        if (unique_id) {
            $.ajax({
                url: '/booking-cancel-buyer/'+unique_id,
                type: 'POST',
                data: {
                    "unique_id": unique_id,
                    "departure_id":departure_id,
                    "_token": token,
                },
                success: function (data) {
                    window.location.reload();

                   // window.location.href = "<?php echo e(route('all_departure')); ?>";
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footerSection'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/departurecloud/resources/views/departure/departure_booking_history.blade.php ENDPATH**/ ?>