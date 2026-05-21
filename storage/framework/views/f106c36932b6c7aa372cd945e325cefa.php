<!--
============================================
KATUISCIA — Footer Component (Blade)
============================================ -->
<footer class="bg-white border-t border-border-k py-24 pb-6">
  <div class="max-w-site mx-auto px-4 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] gap-12 lg:gap-16">

      <!-- Brand -->
      <div>
        <div class="mb-4 relative w-[366px] h-[60px] overflow-hidden max-w-full">
          <a href="<?php echo e(url('/')); ?>" class="block w-full h-full">
            <img src="<?php echo e(asset('assets/images/K LOGO.png')); ?>" alt="KATUISCIA"
                 class="!absolute !w-[366px] !h-[367px] !top-1/2 !left-1/2 !-translate-x-1/2 !-translate-y-1/2 object-contain !max-w-none">
          </a>
        </div>
        <p class="text-sm text-text-light leading-relaxed max-w-[280px]">
          Élever le quotidien grâce à des rituels botaniques soignés et des formulations conscientes.
        </p>
      </div>

      <!-- Explorer -->
      <div>
        <h4 class="font-body text-xs font-semibold tracking-[0.2em] uppercase text-dark mb-6">Explorer</h4>
        <div class="flex flex-col gap-3">
          <a href="<?php echo e(url('boutique')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Boutique</a>
          <a href="<?php echo e(url('maison')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">À propos</a>
          <a href="<?php echo e(url('contact')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Contact</a>
        </div>
      </div>

      <!-- Informations -->
      <div>
        <h4 class="font-body text-xs font-semibold tracking-[0.2em] uppercase text-dark mb-6">Informations</h4>
        <div class="flex flex-col gap-3">
          <a href="<?php echo e(url('valeurs-beaute-responsable')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Durabilité</a>
          <a href="<?php echo e(url('politique-expedition')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Expédition</a>
          <a href="<?php echo e(url('politique-remboursement')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Retours</a>
          <a href="<?php echo e(url('suivi-commande')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Suivi de commande</a>
        </div>
      </div>

      <!-- Légal -->
      <div>
        <h4 class="font-body text-xs font-semibold tracking-[0.2em] uppercase text-dark mb-6">Légal</h4>
        <div class="flex flex-col gap-3">
          <a href="<?php echo e(url('mentions-legales')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Mentions légales</a>
          <a href="<?php echo e(url('politique-confidentialite')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Confidentialité</a>
          <a href="<?php echo e(url('contact')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Contact</a>
        </div>
      </div>
    </div>

    <!-- Bottom -->
    <div class="mt-16 pt-6 border-t border-border-k flex justify-center">
      <p class="text-xs text-text-muted tracking-wide uppercase">© 2026 KATUISCIA. Tous droits réservés.</p>
    </div>
  </div>
</footer>

<!-- Cookie Popup -->
<div class="cookie-popup-k fixed bottom-0 left-0 right-0 bg-white border-t border-border-k px-8 py-6 z-[400] flex items-center justify-between gap-8 shadow-[0_-4px_20px_rgba(61,43,43,0.08)] translate-y-full transition-transform duration-500 ease-out-expo" id="cookie-popup">
  <p class="text-sm text-text-light max-w-[600px]">Nous utilisons des cookies pour améliorer votre expérience. En continuant, vous acceptez notre politique de confidentialité.</p>
  <div class="flex gap-2 flex-shrink-0">
    <button class="btn-katuiscia !px-5 !py-2 !text-[10px]" data-cookie-accept>Accepter</button>
    <button class="btn-katuiscia-warm !px-5 !py-2 !text-[10px]" data-cookie-decline>Refuser</button>
  </div>
</div>
<?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views/components/footer.blade.php ENDPATH**/ ?>