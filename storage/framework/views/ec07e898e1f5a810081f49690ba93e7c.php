<?php $__env->startSection('title', 'Blog — KATUISCIA | Le Journal Beauté'); ?>
<?php $__env->startSection('content'); ?>
<main id="main-content" class="bg-light pb-24">

    <section class="pt-[calc(160px+3rem)] pb-12 px-4 text-center">
      <div class="reveal-k-k">
        <h1 class="font-heading text-4xl lg:text-5xl font-light text-primary mb-4">Le Journal Beauté</h1>
        <p class="text-lg text-text-light max-w-2xl mx-auto">Explorez l'art du soin botanique, des rituels et des conseils d'experts pour révéler votre beauté authentique.</p>
      </div>
    </section>

    <section class="max-w-[1200px] mx-auto px-4 lg:px-8">
      <div class="flex justify-center gap-2 flex-wrap mb-12 reveal-k-k delay-1">
        <a href="<?php echo e(route('blog')); ?>" class="px-6 py-2 rounded-full border <?php echo e(!request('category') ? 'border-primary bg-primary text-white' : 'border-gray-300 hover:border-primary hover:bg-primary hover:text-white'); ?> text-sm uppercase tracking-wide transition-colors" style="text-decoration:none;">Tous</a>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('blog', ['category' => $cat])); ?>" class="px-6 py-2 rounded-full border <?php echo e(request('category') === $cat ? 'border-primary bg-primary text-white' : 'border-gray-300 hover:border-primary hover:bg-primary hover:text-white'); ?> text-sm uppercase tracking-wide transition-colors" style="text-decoration:none;"><?php echo e($cat); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__empty_2 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="article-card reveal-k-k">
          <div class="article-card__img-wrapper">
            <span class="article-card__tag"><?php echo e($post->category); ?></span>
            <?php if($post->image_url): ?>
            <img src="<?php echo e($post->image_url); ?>" alt="<?php echo e($post->title); ?>" class="article-card__img">
            <?php else: ?>
            <div style="height:240px;background:var(--color-peach);display:flex;align-items:center;justify-content:center;font-size:3rem;">📝</div>
            <?php endif; ?>
          </div>
          <div class="article-card__content">
            <span class="article-card__date"><?php echo e($post->created_at->locale('fr')->isoFormat('DD MMMM YYYY')); ?></span>
            <h3 class="article-card__title"><?php echo e($post->title); ?></h3>
            <p class="article-card__excerpt"><?php echo e(Str::limit($post->excerpt, 120)); ?></p>
            <span class="article-card__read-more">Lire l'article <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <div class="col-span-3 text-center py-16">
          <p style="font-size:3rem;">📝</p>
          <p class="text-text-muted">Aucun article pour le moment. Revenez bientôt !</p>
        </div>
        <?php endif; ?>
      </div>

      <?php if($posts->hasPages()): ?>
      <div class="mt-12 flex justify-center">
        <?php echo e($posts->appends(request()->query())->links()); ?>

      </div>
      <?php endif; ?>
    </section>

  </main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="module" src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\blog.blade.php ENDPATH**/ ?>