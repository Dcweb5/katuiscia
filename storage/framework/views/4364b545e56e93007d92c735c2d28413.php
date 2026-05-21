<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'KATUISCIA — Beauté Botanique de Luxe'); ?></title>
  <meta name="description" content="<?php echo $__env->yieldContent('description', 'Découvrez KATUISCIA, des formulations botaniques soignées pour élever votre rituel quotidien.'); ?>">
  <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/K ICONE.png')); ?>">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('dist/tailwind.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/variables.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/global.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
  <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="bg-cream text-dark font-body antialiased">

  <?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <main id="main-content">
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <script type="module" src="<?php echo e(asset('js/main.js')); ?>"></script>
  <script src="<?php echo e(asset('js/cart-ajax.js')); ?>"></script>
  <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\layouts\public.blade.php ENDPATH**/ ?>