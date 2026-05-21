@extends('layouts.public')

@section('title', 'Contact — KATUISCIA')
@section('description', 'Contactez l\'équipe KATUISCIA. Formulaire de contact, adresse, horaires et réseaux sociaux.')

@section('head')
<style>
.contact-input {
  display:block;width:100%;padding:12px 16px;
  border:2px solid #d1d5db;border-radius:10px;
  font-size:15px;font-family:inherit;background:#fff;
  transition:border-color 0.15s,box-shadow 0.15s;
  outline:none;box-sizing:border-box;
}
.contact-input:focus { border-color:var(--color-warm);box-shadow:0 0 0 3px rgba(196,150,122,0.12); }
.contact-label {
  display:block;font-size:12px;font-weight:600;color:#374151;
  margin-bottom:6px;text-transform:uppercase;letter-spacing:0.06em;
}
.contact-toast {
  position:fixed;top:20px;right:20px;z-index:99999;
  padding:16px 24px;border-radius:10px;font-size:14px;font-weight:500;
  box-shadow:0 8px 30px rgba(0,0,0,0.18);max-width:420px;
  transform:translateX(0);transition:transform 0.35s cubic-bezier(0.4,0,0.2,1);
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

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="contact-label">Nom complet *</label>
          <input type="text" name="name" value="{{ old('name') }}" class="contact-input" placeholder="Votre nom" required>
        </div>
        <div>
          <label class="contact-label">Email *</label>
          <input type="email" name="email" value="{{ old('email') }}" class="contact-input" placeholder="votre@email.com" required>
        </div>
      </div>

      <div style="margin:1.25rem 0;">
        <label class="contact-label">Sujet</label>
        <select name="subject" class="contact-input" style="appearance:auto;">
          <option value="">Selectionner un sujet</option>
          <option value="commande" @selected(old('subject')=='commande')>Question sur une commande</option>
          <option value="produit" @selected(old('subject')=='produit')>Information produit</option>
          <option value="retour" @selected(old('subject')=='retour')>Retour / Remboursement</option>
          <option value="partenariat" @selected(old('subject')=='partenariat')>Partenariat / Grossiste</option>
          <option value="formation" @selected(old('subject')=='formation')>Formation</option>
          <option value="presse" @selected(old('subject')=='presse')>Presse</option>
          <option value="autre" @selected(old('subject')=='autre')>Autre</option>
        </select>
      </div>

      <div style="margin-bottom:1.5rem;">
        <label class="contact-label">Message *</label>
        <textarea name="message" rows="5" class="contact-input" style="resize:vertical;" placeholder="Votre message..." required>{{ old('message') }}</textarea>
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
      <a href="#" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="Instagram">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/></svg>
      </a>
      <a href="#" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="Facebook">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
      </a>
      <a href="#" class="w-11 h-11 rounded-full border border-border-k flex items-center justify-center text-dark hover:bg-dark hover:text-cream hover:border-dark transition-all" aria-label="TikTok">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>
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
@endsection
