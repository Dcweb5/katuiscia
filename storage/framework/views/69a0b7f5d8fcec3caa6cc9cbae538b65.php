@section('title', 'Politique d'Expédition — KATUISCIA')

@section('head')

@endsection

<?php $__env->startSection('content'); ?>
<main id="main-content">
    <div class="pt-[calc(160px+3rem)] pb-12 px-4 text-center reveal-k-k">
      <h1 class="font-heading text-4xl font-normal text-dark mb-4">Politique d'Expédition</h1>
      <p class="text-sm text-text-muted">Dernière mise à jour : 28 février 2026</p>
    </div>

    <section class="max-w-3xl mx-auto px-4 pb-24 reveal-k-k delay-1">
      <div class="space-y-8">
        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">1. Délais d’expédition</h2>
          <p class="text-text-light leading-relaxed">Les commandes sont expédiées sous 24 à 72h ouvrées.</p>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">2. Transporteurs</h2>
          <p class="text-text-light leading-relaxed mb-4">Nous livrons avec :</p>
          <ul class="list-disc pl-6 space-y-2 text-text-light">
            <li>Colissimo</li>
            <li>La Poste</li>
            <li>Mondial Relay</li>
          </ul>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">3. Délais de livraison estimés</h2>
          <ul class="list-disc pl-6 space-y-2 text-text-light">
            <li><strong>France :</strong> 2 à 4 jours ouvrés</li>
            <li><strong>Europe :</strong> 4 à 7 jours ouvrés</li>
            <li><strong>International :</strong> 7 à 15 jours ouvrés</li>
          </ul>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">4. Suivi</h2>
          <p class="text-text-light leading-relaxed">Chaque commande possède un numéro de suivi envoyé par email.</p>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">5. Colis non récupéré / adresse incorrecte</h2>
          <p class="text-text-light leading-relaxed">Si le colis revient à l’expéditeur, les frais de réexpédition sont à votre charge.</p>
        </div>
      </div>
    </section>
  </main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="module" src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\politique-expedition.blade.php ENDPATH**/ ?>