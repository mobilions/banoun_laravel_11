<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('layouts.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<body>
<div class="account-pages my-5 pt-sm-5">
<div class="container">
<div class="row justify-content-center">
<?php echo $__env->yieldContent('PageContent'); ?>
</div>
</div>
</div>
<?php echo $__env->make('layouts.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\all_project\banoun_laravel_11\resources\views/layouts/plain.blade.php ENDPATH**/ ?>