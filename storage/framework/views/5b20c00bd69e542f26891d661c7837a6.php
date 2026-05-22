<!--
============================================
KATUISCIA — Header Component (Blade)
============================================ -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[999] focus:bg-dark focus:text-cream focus:px-4 focus:py-2 focus:rounded-lg">Aller au contenu principal</a>

<header class="header-k fixed top-0 left-0 right-0 z-[310] bg-cream transition-all duration-300 ease-out-expo">
  <div class="max-w-site mx-auto px-4 lg:px-8 flex flex-col justify-end h-[190px] pb-5">

    <!-- Top Row: Search | Logo | Actions -->
    <div class="grid grid-cols-[1fr_auto_1fr] items-center w-full flex-1 mb-[30px]">

      <!-- Search -->
      <form action="<?php echo e(url('recherche')); ?>" method="GET" class="hidden lg:flex items-center border border-dark/80 rounded-[10px] overflow-hidden h-12 w-[339px]">
        <input type="text" name="q" placeholder="Rechercher" aria-label="Rechercher"
               class="bg-transparent px-4 font-body text-sm text-dark w-full outline-none placeholder:text-text-muted" value="<?php echo e(request('q', '')); ?>">
        <button type="submit" aria-label="Rechercher" class="bg-peach border-l border-dark/80 w-12 h-full flex items-center justify-center text-dark hover:bg-[#ecdccf] transition-colors">
          <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </button>
      </form>

      <!-- Logo -->
      <div class="flex items-center justify-center relative z-10">
        <a href="<?php echo e(url('/')); ?>" class="flex items-center justify-center">
          <img src="<?php echo e(asset('assets/images/K LOGO.png')); ?>" alt="KATUISCIA" class="h-11 w-auto object-contain hidden lg:block">
          <img src="<?php echo e(asset('assets/images/K ICONE.png')); ?>" alt="KATUISCIA" class="h-16 w-auto object-contain lg:hidden">
        </a>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-6">
        <?php if(auth()->guard()->guest()): ?>
        <a href="<?php echo e(url('connexion')); ?>" class="flex items-center justify-center w-11 h-11 text-dark rounded-full hover:text-warm transition-colors relative" aria-label="Se connecter">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </a>
        <?php else: ?>
        <a href="<?php echo e(url('compte')); ?>" class="flex items-center justify-center w-11 h-11 text-dark rounded-full hover:text-warm transition-colors relative" aria-label="Mon compte">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </a>
        <?php endif; ?>
        <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->is_admin): ?>
        <a href="<?php echo e(url('admin/chat')); ?>" class="flex items-center justify-center w-11 h-11 text-dark rounded-full hover:text-warm transition-colors relative" aria-label="Messages">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          <?php $unreadCount = \App\Models\ChatMessage::where('is_read', false)->count(); ?>
          <?php if($unreadCount > 0): ?><span style="position:absolute;top:-2px;right:-2px;background:var(--color-error);color:#fff;font-size:10px;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;"><?php echo e($unreadCount); ?></span><?php endif; ?>
        </a>
        <?php endif; ?>
        <?php endif; ?>
        <a href="<?php echo e(url('panier')); ?>" class="flex items-center justify-center w-11 h-11 text-dark rounded-full hover:text-warm transition-colors relative" aria-label="Panier">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-dark text-cream text-[10px] font-semibold flex items-center justify-center rounded-full" id="cart-badge">0</span>
        </a>

        <!-- Hamburger (Mobile) -->
        <button class="hamburger-k flex lg:hidden flex-col justify-center items-center w-11 h-11 gap-1.5 cursor-pointer z-[311]" aria-label="Menu" aria-expanded="false">
          <span class="block w-6 h-[1.5px] bg-dark transition-all duration-300 ease-out-expo origin-center"></span>
          <span class="block w-6 h-[1.5px] bg-dark transition-all duration-300 ease-out-expo origin-center"></span>
          <span class="block w-6 h-[1.5px] bg-dark transition-all duration-300 ease-out-expo origin-center"></span>
        </button>
      </div>
    </div>

    <!-- Bottom Row: Navigation -->
    <div class="hidden lg:flex items-center justify-between w-full pb-1 border-b border-border-k">

      <!-- Left Nav -->
      <nav class="flex items-center gap-10 flex-1 pr-[150px]" aria-label="Navigation gauche">
        <a href="<?php echo e(url('boutique')); ?>" class="nav-link-k">SOINS</a>
        <a href="<?php echo e(url('maison')); ?>" class="nav-link-k">MAISON</a>
        <div class="dropdown-k group relative">
          <a href="#" class="nav-link-k">CONSEILS</a>
          <div class="dropdown-panel-k">
            <a href="<?php echo e(url('blog')); ?>">Blog</a>
            <a href="<?php echo e(url('diagnostic')); ?>">Diagnostic</a>
          </div>
        </div>
        <a href="<?php echo e(url('contact')); ?>" class="nav-link-k">CONTACT</a>
      </nav>

      <!-- Right Nav -->
      <nav class="flex items-center gap-10 flex-1 justify-end pl-[150px]" aria-label="Navigation droite">
        <div class="dropdown-k group relative">
          <a href="#" class="nav-link-k">SERVICES &amp; BOUTIQUES</a>
          <div class="dropdown-panel-k">
            <a href="<?php echo e(url('formation')); ?>">Formation</a>
            <a href="<?php echo e(url('grossiste')); ?>">Devenir Grossiste</a>
            <a href="<?php echo e(url('avantage-en-ligne')); ?>">Avantage en ligne</a>
            <a href="<?php echo e(url('contact')); ?>">Où nous trouver ?</a>
          </div>
        </div>
        <div class="dropdown-k group relative">
          <a href="#" class="nav-link-k">NOS VALEURS</a>
          <div class="dropdown-panel-k min-w-[360px]">
            <span class="dropdown-heading-k">Nos Engagements</span>
            <a href="<?php echo e(url('valeurs-beaute-responsable')); ?>">À propos de la beauté responsable</a>
            <a href="<?php echo e(url('valeurs-emballage-durable')); ?>">À propos de l'emballage durable</a>
            <a href="<?php echo e(url('valeurs-personne-biodiversite')); ?>">À propos de la personne &amp; la biodiversité</a>
            <div class="h-px bg-gradient-to-r from-transparent via-border-k to-transparent mx-8 my-2"></div>
            <span class="dropdown-heading-k">Notre Vision</span>
            <a href="<?php echo e(url('maison')); ?>">Un avenir plus beau</a>
          </div>
        </div>
      </nav>
    </div>
  </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu-k fixed top-0 right-full w-full h-screen bg-cream z-[300] flex flex-col items-start justify-start pt-[120px] px-12 pb-24 gap-8 overflow-y-auto transition-all duration-500 ease-out-expo">
  <!-- Search -->
  <form action="<?php echo e(url('recherche')); ?>" method="GET" class="flex items-center border border-dark/80 rounded-[10px] overflow-hidden h-12 w-full mb-4">
    <input type="text" name="q" placeholder="Rechercher" aria-label="Rechercher"
           class="bg-transparent px-4 font-body text-sm text-dark w-full outline-none" value="<?php echo e(request('q', '')); ?>">
    <button type="submit" aria-label="Rechercher" class="bg-peach border-l border-dark/80 w-12 h-full flex items-center justify-center text-dark">
      <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
    </button>
  </form>

  <a href="<?php echo e(url('/')); ?>" class="mobile-link-k">Accueil</a>
  <a href="<?php echo e(url('boutique')); ?>" class="mobile-link-k">Soins</a>
  <a href="<?php echo e(url('maison')); ?>" class="mobile-link-k">Maison</a>

  <div class="w-full">
    <span class="mobile-link-k cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden')">Conseils</span>
    <div class="hidden ml-4 mt-4 flex flex-col gap-3">
      <a href="<?php echo e(url('blog')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Blog</a>
      <a href="<?php echo e(url('diagnostic')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Diagnostic</a>
    </div>
  </div>

  <div class="w-full">
    <span class="mobile-link-k cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden')">Nos Valeurs</span>
    <div class="hidden ml-4 mt-4 flex flex-col gap-3">
      <span class="text-md font-bold text-warm uppercase tracking-wider">Nos Engagements</span>
      <a href="<?php echo e(url('valeurs-beaute-responsable')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Beauté responsable</a>
      <a href="<?php echo e(url('valeurs-emballage-durable')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Emballage durable</a>
      <a href="<?php echo e(url('valeurs-personne-biodiversite')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Personne &amp; biodiversité</a>
      <span class="text-md font-bold text-warm uppercase tracking-wider mt-2">Notre Vision</span>
      <a href="<?php echo e(url('maison')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Un avenir plus beau</a>
    </div>
  </div>

  <a href="<?php echo e(url('contact')); ?>" class="mobile-link-k">Contact</a>

  <?php if(auth()->guard()->guest()): ?>
  <a href="<?php echo e(url('connexion')); ?>" class="mobile-link-k">Connexion</a>
  <a href="<?php echo e(url('inscription')); ?>" class="mobile-link-k">Inscription</a>
  <?php else: ?>
  <a href="<?php echo e(url('compte')); ?>" class="mobile-link-k">Mon Compte</a>
  <a href="<?php echo e(url('admin')); ?>" class="mobile-link-k" style="color:var(--color-warm);">Administration</a>
  <form method="POST" action="<?php echo e(url('deconnexion')); ?>" style="display:inline;">
    <?php echo csrf_field(); ?>
    <button type="submit" class="mobile-link-k" style="color:var(--color-error);background:none;border:none;cursor:pointer;font-family:inherit;padding:0;">Déconnexion</button>
  </form>
  <?php endif; ?>

  <div class="w-full">
    <span class="mobile-link-k cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden')">Services &amp; Boutiques</span>
    <div class="hidden ml-4 mt-4 flex flex-col gap-3">
      <a href="<?php echo e(url('formation')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Formation</a>
      <a href="<?php echo e(url('grossiste')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Devenir Grossiste</a>
      <a href="<?php echo e(url('avantage-en-ligne')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Avantage en ligne</a>
      <a href="<?php echo e(url('contact')); ?>" class="text-sm text-text-light hover:text-dark transition-colors">Où nous trouver ?</a>
    </div>
  </div>
</div>
<?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\components\header.blade.php ENDPATH**/ ?>