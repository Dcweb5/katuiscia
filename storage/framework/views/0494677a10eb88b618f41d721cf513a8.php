<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo $__env->yieldContent('title'); ?> — KATUISCIA Admin</title>
  <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/K ICONE.png')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('dist/tailwind.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/variables.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/global.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
  <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="dashboard-layout admin-dashboard">
  <?php echo $__env->make('components.admin-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->make('components.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <main class="dashboard-main">
    <?php echo $__env->yieldContent('content'); ?>
  </main>
  <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\layouts\admin.blade.php ENDPATH**/ ?>