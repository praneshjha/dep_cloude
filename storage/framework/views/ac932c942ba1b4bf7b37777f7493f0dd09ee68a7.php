<div class="card-box">
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th style="width: 5%;">#</th>
          <th style="width: 5%;">Day</th>
          <th>Heading</th>
          <th>Destination(s)</th>
          <th style="width: 10%;">Status</th>
          <th style="width: 5%;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if(count($itineraries) > 0): ?>
        <?php $__currentLoopData = $itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td><?php echo e($loop->index +1); ?></td>
            <td><?php echo e($itinerary->day_number); ?></td>
            <td><?php echo e($itinerary->day_heading); ?></td>
            <td><?php echo e($itinerary->destNames); ?></td>
            
            <td>
              <?php if($itinerary->status == '1'): ?>
                <a class="disableItinerary" data-id="<?php echo e($itinerary->id); ?>" data-status="<?php echo e($itinerary->status); ?>" style="cursor: pointer; color: #2f8263;">
                  Active
                </a>
              <?php else: ?>
                <a class="disableItinerary" data-id="<?php echo e($itinerary->id); ?>" data-status="<?php echo e($itinerary->status); ?>" style="cursor: pointer; color: #F9423C;">
                  Inactive
                </a>
              <?php endif; ?>
            </td>
            <td>
              <a href="javascript:void(0);" data-toggle="modal" data-toggle="modal" class="editItinerary" data-id="<?php echo e($itinerary->id); ?>" data-daynumber="<?php echo e($itinerary->day_number); ?>" data-dayheading="<?php echo e($itinerary->day_heading); ?>" data-description="<?php echo e($itinerary->description); ?>" data-destId="<?php echo e(JSON_encode($itinerary->destinations_id)); ?>" data-destName="<?php echo e(JSON_encode($itinerary->destinations_name)); ?>" title="Edit details" style="cursor: pointer;"><i class="fa fa-edit"></i></a> | 
              <form id="posts-form<?php echo e($itinerary->id); ?>" method="post" action="<?php echo e(route('itinerary_delete',$itinerary->id)); ?>" style="display: none;">
                <?php echo csrf_field(); ?>
                <?php echo e(method_field('POST')); ?> <!-- posts query -->
              </form>
                <a href="" onclick="
                if (confirm('Are you sure, You want to delete?'))
                  {
                    event.preventDefault();
                    document.getElementById('posts-form<?php echo e($itinerary->id); ?>').submit();
                  }
                  else
                  {
                    event.preventDefault();
                  }
                " style="cursor: pointer;" title="Delete">
                    <i class="fa fa-trash" style="color: #69204b;cursor: pointer;"></i></a>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="box-footer clearfix">
  <ul class="pagination pagination-sm no-margin pull-right">
  </ul>
</div><?php /**PATH /var/www/html/departurecloud/resources/views/departure/itinerary_list.blade.php ENDPATH**/ ?>