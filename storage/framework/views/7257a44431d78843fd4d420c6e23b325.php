<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title'); ?> — KATUISCIA</title>
  <meta name="description" content="<?php echo $__env->yieldContent('description'); ?>">
  <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/K ICONE.png')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('dist/tailwind.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/variables.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/global.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
  <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="auth-page">
  <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\layouts\auth.blade.php ENDPATH**/ ?>