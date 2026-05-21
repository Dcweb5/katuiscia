<?php $__env->startSection('title', 'Diagnostic IA — KATUISCIA'); ?>

<?php $__env->startSection('head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main id="main-content">
    
    <section class="pt-[calc(160px+3rem)] pb-12 px-4 text-center bg-[#1a1a1a] text-white relative overflow-hidden">
      <!-- AI Scanner effect in background -->
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.05)_0%,transparent_70%)] z-0"></div>
      
      <div class="relative z-10 max-w-3xl mx-auto reveal-k-k">
        <h1 class="font-heading text-4xl lg:text-5xl font-light mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white to-accent">L'Expertise Botanique Renforcée par l'IA</h1>
        <p class="text-lg opacity-80 mb-12">Notre intelligence artificielle analyse votre peau pour vous conseiller une prescription beauté faite pour vous et des conseils professionnels sur-mesure.</p>
        
        <div class="scanner-ui">
          <div class="scanner-line"></div>
          <div class="scanner-overlay">
            <p>Analyse faciale en cours...</p>
          </div>
        </div>

        <button class="btn-glow">Démarrer l'analyse</button>
        <p class="mt-4 text-xs opacity-60">Pour de meilleurs résultats, utilisez votre smartphone.</p>
      </div>
    </section>

    <section class="max-w-[1200px] mx-auto px-4 py-24">
      <h2 class="text-center font-heading text-3xl text-dark mb-16 reveal-k-k">Votre diagnostic en 4 étapes</h2>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
        <div class="step-card bg-white p-8 rounded-2xl shadow-sm reveal-k-k delay-1">
          <div class="step-card__number border-cream">1</div>
          <h3 class="font-heading text-xl text-primary mt-4 mb-2">Prenez un Selfie</h3>
          <p class="text-sm text-text-light leading-relaxed">Prenez une photo claire de votre visage pour démarrer l'analyse visuelle de votre épiderme par notre IA.</p>
        </div>
        <div class="step-card bg-white p-8 rounded-2xl shadow-sm reveal-k-k delay-2">
          <div class="step-card__number border-cream">2</div>
          <h3 class="font-heading text-xl text-primary mt-4 mb-2">Questionnaire</h3>
          <p class="text-sm text-text-light leading-relaxed">Aidez-nous à comprendre votre environnement et votre mode de vie en répondant à quelques questions rapides.</p>
        </div>
        <div class="step-card bg-white p-8 rounded-2xl shadow-sm reveal-k-k delay-3">
          <div class="step-card__number border-cream">3</div>
          <h3 class="font-heading text-xl text-primary mt-4 mb-2">Profil Beauté</h3>
          <p class="text-sm text-text-light leading-relaxed">Découvrez votre type de peau, ses besoins spécifiques et ses vulnérabilités face à la pollution ou au stress.</p>
        </div>
        <div class="step-card bg-white p-8 rounded-2xl shadow-sm reveal-k-k delay-4">
          <div class="step-card__number border-cream">4</div>
          <h3 class="font-heading text-xl text-primary mt-4 mb-2">Routine Sur-Mesure</h3>
          <p class="text-sm text-text-light leading-relaxed">Obtenez votre prescription personnalisée de soins KATUISCIA pour atteindre vos objectifs beauté.</p>
        </div>
      </div>

      <div class="text-center mt-24 reveal-k-k">
        <p class="text-text-light max-w-2xl mx-auto">Avec des décennies de développement de formules révolutionnaires à base de plantes, KATUISCIA a perfectionné les méthodes les plus efficaces pour traiter tous les types de problèmes de peau.</p>
      </div>
    </section>

  </main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="module" src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\diagnostic.blade.php ENDPATH**/ ?>