<title><?php echo e($departure_details->title); ?> | <?php echo e($departure_details->company_name); ?></title>
<meta property="og:title" content="<?php echo e($departure_details->title); ?>">
<meta property="og:type" content="DepartureCloud"/>
<meta property="og:url" content="<?php echo e(URL::current()); ?>">
<meta property="og:description" content="<?php echo e(strip_tags(htmlspecialchars_decode(Str::limit($departure_details->description, 200, ' ...')))); ?>">
<meta property="og:image" content="<?php echo e($departure_details->logo_image); ?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:creator" content="@Departure-Cloud">
<meta name="twitter:url" content="<?php echo e(URL::current()); ?>">
<meta name="twitter:title" content="<?php echo e($departure_details->title); ?>">
<meta name="twitter:description" content="<?php echo e(strip_tags(Str::limit($departure_details->description, 200, ' ...'))); ?>">
<meta name="twitter:image" content="<?php echo e($departure_details->logo_image); ?>"><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/layouts/og_tags.blade.php ENDPATH**/ ?>