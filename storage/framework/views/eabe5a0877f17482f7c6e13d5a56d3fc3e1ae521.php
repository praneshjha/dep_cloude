<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<p>
<b>Name :</b> <?php echo e($name); ?></p>
<p>
<b>Company Name:</b> <?php echo e($company_name); ?></p>
<p><b>Phone:</b> <?php echo e($phone); ?></p>
<p><b>Email:</b> <?php echo e($email); ?></p>
<?php if(isset($issue)): ?>
	<p><b>Issue:</b> <?php echo e($issue); ?></p>
<?php endif; ?>


<p><b>Url : </b><?php echo e($url); ?></p>
</body>
</html><?php /**PATH D:\xampp\htdocs\departurecloud\resources\views/mail/demo.blade.php ENDPATH**/ ?>