@extends('layouts.public')

@section('title', 'Contact — KATUISCIA')
@section('description', 'Contactez l\'équipe KATUISCIA. Formulaire de contact, adresse, horaires et réseaux sociaux.')

@section('head')
<style>
.contact-toast {
  position:fixed;top:20px;right:20px;z-index:99999;
  padding:16px 24px;border-radius:10px;font-size:14px;font-weight:500;
  box-shadow:0 8px 30px rgba(0,0,0,0.18);max-width:420px;
  transform:translateX(0);transition:transform 0.35s cubic-bezier(0.4,0,0.2,1);
}
.custom-select-container {
  position: relative;
  width: 100%;
}
.custom-select-trigger {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #d1d5db;
  border-radius: 10px;
  font-size: 15px;
  font-family: inherit;
  background: #fff;
  cursor: pointer;
  transition: border-color 0.15s, box-shadow 0.15s;
  outline: none;
  box-sizing: border-box;
}
.custom-select-trigger::after {
  content: '';
  width: 8px;
  height: 8px;
  border-right: 2px solid #C4967A;
  border-bottom: 2px solid #C4967A;
  transform: translateY(-2px) rotate(45deg);
  transition: transform 0.2s ease;
  margin-left: 10px;
  flex-shrink: 0;
}
.custom-select-container.open .custom-select-trigger::after {
  transform: translateY(2px) rotate(-135deg);
}
.custom-select-container.open .custom-select-trigger {
  border-color: var(--color-warm);
  box-shadow: 0 0 0 3px rgba(196,150,122,0.12);
}
.custom-select-options {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  right: 0;
  background: #fff;
  border: 2px solid var(--color-warm);
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(61,43,43,0.1);
  z-index: 100;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
  max-height: 250px;
  overflow-y: auto;
}
.custom-select-container.open .custom-select-options {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}
.custom-select-option {
  padding: 12px 16px;
  font-size: 14px;
  color: var(--color-dark);
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}
.custom-select-option:hover {
  background-color: var(--color-warm) !important;
  color: #fff !important;
}
.custom-select-option.selected {
  background-color: var(--color-warm);
  color: #fff;
}
</style>
@endsection

@section('content')
<div class="pt-[calc(190px+3rem)] pb-8 max-w-site mx-auto px-4 lg:px-8 reveal-k-k">
  <h1 class="font-heading text-4xl font-normal text-dark mb-4">Contactez-nous</h1>
  <p class="text-md text-text-light max-w-lg leading-relaxed">Nous serions ravis d'echanger avec vous. Envoyez-nous un message et notre equipe vous repondra dans les plus brefs delais.</p>
</div>

<div class="max-w-site mx-auto px-4 lg:px-8 pb-24 grid grid-cols-1 lg:grid-cols-[1.2fr_1fr] gap-16">

  <!-- FORMULAIRE -->
  <div class="reveal-k-k">
    <form method="POST" action="{{ route('contact.store') }}" class="bg-white rounded-2xl p-8 shadow-card" style="border:1px solid #ede4db;">
      @csrf

      {{-- Error banner --}}
      @if($errors->any())
      <div style="padding:14px 18px;background:#fff5f5;border:2px solid #ef9a9a;border-radius:10px;color:#c62828;margin-bottom:1.5rem;font-size:13px;line-height:1.5;">
        @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
      </div>
      @endif

      <div class="grid grid-cols-1 md:grid-cols-2" style="gap: 1.5rem;">
        <div>
          <label class="k-label">Nom complet *</label>
          <input type="text" name="name" value="{{ old('name') }}" class="k-input" placeholder="Votre nom" required>
        </div>
        <div>
          <label class="k-label">Email *</label>
          <input type="email" name="email" value="{{ old('email') }}" class="k-input" placeholder="votre@email.com" required>
        </div>
      </div>

      <div style="margin:1.25rem 0;">
        <label class="k-label">Sujet</label>
        <div class="custom-select-container">
          <div class="custom-select-trigger">Selectionner un sujet</div>
          <div class="custom-select-options">
            <div class="custom-select-option" data-value="">Selectionner un sujet</div>
            <div class="custom-select-option" data-value="commande">Question sur une commande</div>
            <div class="custom-select-option" data-value="produit">Information produit</div>
            <div class="custom-select-option" data-value="retour">Retour / Remboursement</div>
            <div class="custom-select-option" data-value="partenariat">Partenariat / Grossiste</div>
            <div class="custom-select-option" data-value="formation">Formation</div>
            <div class="custom-select-option" data-value="presse">Presse</div>
            <div class="custom-select-option" data-value="autre">Autre</div>
          </div>
          <input type="hidden" name="subject" id="subject-hidden-input" value="{{ old('subject') }}">
        </div>
      </div>

      <div style="margin-bottom:1.5rem;">
        <label class="k-label">Message *</label>
        <textarea name="message" rows="5" class="k-input" style="resize:vertical;" placeholder="Votre message..." required>{{ old('message') }}</textarea>
      </div>

      <button type="submit" class="btn-katuiscia-filled w-full py-4 text-sm tracking-widest uppercase font-semibold rounded-xl">
        Envoyer le message
      </button>
    </form>
  </div>

  <!-- INFO -->
  <div class="bg-white p-8 md:p-12 rounded-2xl shadow-lg border border-border-k/30 space-y-8 reveal-k-k delay-1 h-fit">
    <h2 class="font-heading text-2xl font-medium text-dark">Nos Coordonnees</h2>

    <div class="flex items-start gap-4">
      <div class="w-10 h-10 rounded-full bg-peach flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <div>
        <p class="text-xs font-semibold tracking-wider uppercase text-dark mb-1">Adresse</p>
        <p class="text-sm text-text-light leading-relaxed">9 bis route de Corbeil<br>91360 Villemoisson-sur-Orge<br>France</p>
      </div>
    </div>

    <div class="flex items-start gap-4">
      <div class="w-10 h-10 rounded-full bg-peach flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
      </div>
      <div><p class="text-xs font-semibold tracking-wider uppercase text-dark mb-1">Email</p><p class="text-sm text-text-light">contact@katuiscia.com</p></div>
    </div>

    <div class="flex items-start gap-4">
      <div class="w-10 h-10 rounded-full bg-peach flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div><p class="text-xs font-semibold tracking-wider uppercase text-dark mb-1">Horaires</p><p class="text-sm text-text-light leading-relaxed">Lun — Ven : 9h — 18h<br>Sam : 10h — 16h</p></div>
    </div>

    <div class="rounded-xl overflow-hidden shadow-md h-[250px]">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2634.5!2d2.35!3d48.68!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDjCsDQwJzQ4LjAiTiAywrAyMScwMC4wIkU!5e0!3m2!1sfr!2sfr!4v1" class="w-full h-full border-0" allowfullscreen="" loading="lazy" title="Localisation KATUISCIA"></iframe>
    </div>

    <div class="flex gap-4">
      <a href="https://www.instagram.com/katuiscia_business_innovation?igsh=bndrYzc1emtibzRz&utm_source=qr" target="_blank" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="Instagram">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/></svg>
      </a>
      <a href="https://www.facebook.com/profile.php?id=61589520655219" target="_blank" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="Facebook">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
      </a>
      <a href="https://www.tiktok.com/@katuiscia3?_r=1&_t=ZN-96qrPmEc9Mk" target="_blank" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="TikTok">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
      </a>
      <a href="https://wa.me/33667650850" target="_blank" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="WhatsApp">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
      </a>
    </div>
  </div>

</div>

{{-- Toast notification --}}
@if(session('success'))
<div id="contact-toast" class="contact-toast" style="background:#2e7d32;color:#fff;"></div>
<script>
  document.getElementById('contact-toast').textContent = @json(session('success'));
  setTimeout(function(){ document.getElementById('contact-toast').style.transform = 'translateX(120%)'; }, 4000);
</script>
@endif
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('.custom-select-container');
  if (!container) return;
  const trigger = container.querySelector('.custom-select-trigger');
  const options = container.querySelectorAll('.custom-select-option');
  const hiddenInput = container.querySelector('#subject-hidden-input');

  trigger.addEventListener('click', function (e) {
    e.stopPropagation();
    container.classList.toggle('open');
  });

  options.forEach(opt => {
    opt.addEventListener('click', function (e) {
      e.stopPropagation();
      const value = this.getAttribute('data-value');
      const text = this.textContent;

      options.forEach(o => o.classList.remove('selected'));
      this.classList.add('selected');

      trigger.textContent = text;
      hiddenInput.value = value;
      container.classList.remove('open');
    });
  });

  document.addEventListener('click', function () {
    container.classList.remove('open');
  });

  // Repopulate if old value is present
  const defaultValue = hiddenInput.value;
  if (defaultValue) {
    const activeOpt = [...options].find(o => o.getAttribute('data-value') === defaultValue);
    if (activeOpt) {
      options.forEach(o => o.classList.remove('selected'));
      activeOpt.classList.add('selected');
      trigger.textContent = activeOpt.textContent;
    }
  }
});
</script>
@endsection
