@extends('layouts.public')

@section('title', 'Grossiste — Devenez Partenaire')

@section('head')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
<link rel="stylesheet" href="{{ asset('css/booking.css') }}">
@endsection

@section('content')
<main id="main-content">

    <!-- ============ HERO ============ -->
    <section class="page-header page-header--with-bg">
      <img src="{{ asset('assets/images/hero-slide-2.jpg') }}" alt="" class="page-header__bg" aria-hidden="true">
      <div class="container reveal-k">
        <span class="landing-hero__badge" style="margin-bottom: 15px;">PROGRAMME GROSSISTE</span>
        <h1 class="page-header__title">Devenez Partenaire</h1>
        <p class="page-header__description">Rejoignez notre réseau de revendeurs et accédez à nos produits botaniques à tarif professionnel. Bénéficiez d'un accompagnement sur-mesure et développez votre activité avec KATUISCIA.</p>
        <div style="margin-top: 30px; display: flex; gap: 15px;">
          <a href="#booking" class="btn" style="background:var(--color-cream); color:var(--color-dark); border-color:var(--color-cream);"><span>Prendre rendez-vous</span></a>
          <a href="#video" class="btn" style="background:transparent; color:var(--color-cream); border-color:rgba(255,255,255,0.4);"><span>Présentation</span></a>
        </div>
      </div>
    </section>

    <!-- ============ STATS ============ -->
    <div class="landing-stats">
      <div class="landing-stat">
        <span class="landing-stat__number">120+</span>
        <span class="landing-stat__label">Revendeurs actifs</span>
      </div>
      <div class="landing-stat">
        <span class="landing-stat__number">45%</span>
        <span class="landing-stat__label">Marge moyenne</span>
      </div>
      <div class="landing-stat">
        <span class="landing-stat__number">9</span>
        <span class="landing-stat__label">Gammes disponibles</span>
      </div>
    </div>

    <!-- ============ FEATURES ============ -->
    <section class="landing-section">
      <div class="landing-section__header">
        <p class="landing-section__eyebrow">Pourquoi nous rejoindre</p>
        <h2 class="landing-section__title">Un Partenariat Taillé pour Votre Réussite</h2>
        <p class="landing-section__desc">Que vous soyez boutique, institut de beauté, salon de coiffure ou e-commerçant, nous avons une offre adaptée à votre activité de revente.</p>
      </div>

      <div class="landing-features">
        <div class="landing-feature">
          <div class="landing-feature__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          </div>
          <h3 class="landing-feature__title">Tarifs Grossiste</h3>
          <p class="landing-feature__desc">Accédez à notre grille tarifaire professionnelle avec des marges allant jusqu'à 50% sur le prix public.</p>
        </div>
        <div class="landing-feature">
          <div class="landing-feature__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3 class="landing-feature__title">Formation Équipe</h3>
          <p class="landing-feature__desc">Vos équipes sont formées gratuitement à la philosophie et aux protocoles de vente KATUISCIA.</p>
        </div>
        <div class="landing-feature">
          <div class="landing-feature__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          </div>
          <h3 class="landing-feature__title">Kit Marketing</h3>
          <p class="landing-feature__desc">PLV, échantillons, visuels réseaux sociaux et supports de communication fournis gratuitement.</p>
        </div>
      </div>
    </section>

    <!-- ============ VIDEO ============ -->
    <section class="landing-section landing-section--alt" id="video">
      <div class="landing-section__header">
        <p class="landing-section__eyebrow">Présentation du programme</p>
        <h2 class="landing-section__title">Découvrez Comment Devenir Revendeur</h2>
      </div>

      <div class="landing-video">
        <div class="landing-video__wrapper" id="video-container">
          <img src="{{ asset('assets/images/hero-slide-2.jpg') }}" alt="Présentation du programme grossiste KATUISCIA" class="landing-video__placeholder">
          <div class="landing-video__play">
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <p class="landing-video__caption">Le programme grossiste KATUISCIA en détail — 3:15 min</p>
      </div>
    </section>

    <!-- ============ PROCESS ============ -->
    <section class="landing-section">
      <div class="landing-section__header">
        <p class="landing-section__eyebrow">Comment démarrer</p>
        <h2 class="landing-section__title">3 Étapes pour Rejoindre le Réseau</h2>
      </div>

      <div class="landing-steps">
        <div class="landing-step">
          <h3 class="landing-step__title">Prenez rendez-vous</h3>
          <p class="landing-step__desc">Réservez un créneau avec notre équipe commerciale pour discuter de votre projet de revente.</p>
        </div>
        <div class="landing-step">
          <h3 class="landing-step__title">Recevez votre offre</h3>
          <p class="landing-step__desc">Nous étudions votre profil et vous proposons une grille tarifaire et un package de démarrage adapté.</p>
        </div>
        <div class="landing-step">
          <h3 class="landing-step__title">Commencez à vendre</h3>
          <p class="landing-step__desc">Passez votre première commande en gros, recevez votre kit et démarrez votre activité de revente.</p>
        </div>
      </div>
    </section>

    <!-- ============ ACCOMPAGNEMENT ============ -->
    <section class="landing-section landing-section--alt" id="accompagnement">
      <div class="landing-section__header">
        <p class="landing-section__eyebrow">Services exclusifs</p>
        <h2 class="landing-section__title">Un Accompagnement Complet</h2>
        <p class="landing-section__desc">Au-delà de la distribution, nous vous aidons à bâtir une marque forte et une présence digitale performante.</p>
      </div>

      <div class="landing-accompagnement-grid">
        <div style="background:#fff;border:1px solid #ede4db;border-radius:14px;padding:1.75rem 1.5rem;text-align:center;transition:all 0.25s;">
          <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg, #ede4db, #d4c5b8);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6b5d53" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
          </div>
          <h3 style="font-family:var(--font-heading);font-size:1rem;font-weight:400;color:#2d2117;margin-bottom:0.5rem;">Branding</h3>
          <p style="font-size:13px;color:#8b7b6e;line-height:1.55;">Identité visuelle, logo, packaging — une image de marque forte et mémorable.</p>
        </div>
        <div style="background:#fff;border:1px solid #ede4db;border-radius:14px;padding:1.75rem 1.5rem;text-align:center;transition:all 0.25s;">
          <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg, #faf7f2, #e8e0d5);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8b6f5a" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </div>
          <h3 style="font-family:var(--font-heading);font-size:1rem;font-weight:400;color:#2d2117;margin-bottom:0.5rem;">Contenu</h3>
          <p style="font-size:13px;color:#8b7b6e;line-height:1.55;">Posts, photos, vidéos — des contenus engageants pour tous vos canaux.</p>
        </div>
        <div style="background:#fff;border:1px solid #ede4db;border-radius:14px;padding:1.75rem 1.5rem;text-align:center;transition:all 0.25s;">
          <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg, #e8f0ea, #c8ddd3);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5a7d6b" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <h3 style="font-family:var(--font-heading);font-size:1rem;font-weight:400;color:#2d2117;margin-bottom:0.5rem;">Communication</h3>
          <p style="font-size:13px;color:#8b7b6e;line-height:1.55;">Réseaux sociaux, publicités, emailing — une stratégie digitale qui convertit.</p>
        </div>
        <div style="background:#fff;border:1px solid #ede4db;border-radius:14px;padding:1.75rem 1.5rem;text-align:center;transition:all 0.25s;">
          <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg, #e0d8ff, #c4b5e5);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5a4f7d" stroke-width="1.5"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
          </div>
          <h3 style="font-family:var(--font-heading);font-size:1rem;font-weight:400;color:#2d2117;margin-bottom:0.5rem;">Site Internet</h3>
          <p style="font-size:13px;color:#8b7b6e;line-height:1.55;">Vitrine ou e-commerce sur mesure, optimisé SEO, responsive et rapide.</p>
        </div>
      </div>

      <div style="text-align:center;margin-top:2.5rem;">
        <a href="#booking" class="landing-cta landing-cta--primary" style="margin:0 auto;">
          <span>Demander un accompagnement</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </section>

    <!-- ============ BOOKING ============ -->
    <section class="landing-section landing-section--alt" id="booking">
      <div class="landing-section__header">
        <p class="landing-section__eyebrow">Rendez-vous commercial</p>
        <h2 class="landing-section__title">Prenez Rendez-vous avec Notre Équipe</h2>
        <p class="landing-section__desc">Un entretien de 30 minutes pour étudier ensemble votre projet de distribution et vous présenter nos conditions.</p>
      </div>

      <div class="landing-booking">
        <!-- Calendar -->
        <div class="calendar-wrapper">
          <div class="calendar">
            <div class="calendar__header">
              <button class="calendar__nav-btn">&larr;</button>
              <div class="calendar__month">Mai 2026</div>
              <button class="calendar__nav-btn">&rarr;</button>
            </div>
            <div class="calendar__grid">
              <div class="calendar__day-name">Lu</div><div class="calendar__day-name">Ma</div><div class="calendar__day-name">Me</div><div class="calendar__day-name">Je</div><div class="calendar__day-name">Ve</div><div class="calendar__day-name">Sa</div><div class="calendar__day-name">Di</div>
              <div class="calendar__day calendar__day--disabled">27</div><div class="calendar__day calendar__day--disabled">28</div><div class="calendar__day calendar__day--disabled">29</div><div class="calendar__day calendar__day--disabled">30</div>
              <div class="calendar__day">1</div><div class="calendar__day calendar__day--disabled">2</div><div class="calendar__day calendar__day--disabled">3</div>
              <div class="calendar__day">4</div><div class="calendar__day">5</div><div class="calendar__day">6</div><div class="calendar__day">7</div><div class="calendar__day">8</div><div class="calendar__day calendar__day--disabled">9</div><div class="calendar__day calendar__day--disabled">10</div>
              <div class="calendar__day">11</div><div class="calendar__day calendar__day--active">12</div><div class="calendar__day">13</div><div class="calendar__day">14</div><div class="calendar__day">15</div><div class="calendar__day calendar__day--disabled">16</div><div class="calendar__day calendar__day--disabled">17</div>
              <div class="calendar__day">18</div><div class="calendar__day">19</div><div class="calendar__day">20</div><div class="calendar__day">21</div><div class="calendar__day">22</div><div class="calendar__day calendar__day--disabled">23</div><div class="calendar__day calendar__day--disabled">24</div>
              <div class="calendar__day">25</div><div class="calendar__day">26</div><div class="calendar__day">27</div><div class="calendar__day">28</div><div class="calendar__day">29</div><div class="calendar__day calendar__day--disabled">30</div><div class="calendar__day calendar__day--disabled">31</div>
            </div>
          </div>
          <div class="time-slots">
            <h3 class="time-slots__title">Créneaux disponibles le 12 Mai</h3>
            <div class="time-slots__grid">
              <button class="time-slot">10:00</button>
              <button class="time-slot">11:30</button>
              <button class="time-slot time-slot--active">14:00</button>
              <button class="time-slot">16:00</button>
            </div>
          </div>
        </div>

        <!-- Form -->
        <div class="form-wrapper">
          @if(session('success'))
            <div class="booking-success" style="background:#e8f5e9;color:#2e7d32;padding:1.5rem;border-radius:12px;margin-bottom:1rem;font-weight:500;">{{ session('success') }}</div>
          @endif
          <form method="POST" action="{{ route('appointment.store') }}" class="booking-form">
            @csrf
            <input type="hidden" name="source" value="{{ $source ?? 'grossiste' }}">
            <input type="hidden" name="preferred_date" id="grossiste-date" value="2026-05-12">
            <input type="hidden" name="preferred_time" id="grossiste-time" value="14:00">
            <div class="booking-selected">
              Rendez-vous : <span id="grossiste-display">Mardi 12 Mai à 14:00</span>
            </div>

            <div class="form-group">
              <label class="form-label">Vous êtes</label>
              <div style="display:flex;gap:1rem;margin-bottom:0.5rem;">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                  <input type="radio" name="type" value="individu" checked onchange="toggleSiret()"> Particulier
                </label>
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                  <input type="radio" name="type" value="entreprise" onchange="toggleSiret()"> Entreprise
                </label>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Nom complet / Contact principal *</label>
              <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
              @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div id="siret-fields" style="display:none;">
              <div class="form-group">
                <label class="form-label">Nom de l'entreprise *</label>
                <input type="text" name="company_name" class="form-input" value="{{ old('company_name') }}">
                @error('company_name') <span class="form-error">{{ $message }}</span> @enderror
              </div>
              <div class="form-group">
                <label class="form-label">N° SIRET *</label>
                <input type="text" name="siret" class="form-input" placeholder="14 chiffres" value="{{ old('siret') }}">
                @error('siret') <span class="form-error">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Adresse E-mail *</label>
              <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
              @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Téléphone</label>
              <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}">
              @error('phone') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Votre activité de revente / Message</label>
              <textarea name="message" class="form-textarea" placeholder="Boutique physique, institut, e-commerce, salon...">{{ old('message') }}</textarea>
              @error('message') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="landing-cta landing-cta--primary" style="width:100%; justify-content:center;">
              <span>Demander un rendez-vous</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12h14M12 5l7 7-7 7"/></svg>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- ============ FINAL CTA ============ -->
    <section class="landing-cta-banner">
      <h2 class="landing-cta-banner__title">Prêt(e) à développer votre activité ?</h2>
      <p class="landing-cta-banner__desc">Rejoignez les 120+ revendeurs qui génèrent des revenus complémentaires avec les produits KATUISCIA.</p>
      <a href="#booking" class="landing-cta landing-cta--primary">
        <span>Rejoindre le réseau</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </section>

  </main>

  <!-- FOOTER -->
  <script>
    function toggleSiret() {
      const isEntreprise = document.querySelector('input[name="type"]:checked').value === 'entreprise';
      document.getElementById('siret-fields').style.display = isEntreprise ? '' : 'none';
    }
    document.querySelectorAll('.calendar__day:not(.calendar__day--disabled)').forEach(day => {
      day.addEventListener('click', e => {
        document.querySelectorAll('.calendar__day').forEach(d => d.classList.remove('calendar__day--active'));
        e.target.classList.add('calendar__day--active');
        document.querySelector('.time-slots__title').textContent = `Créneaux disponibles le ${e.target.textContent} Mai`;
        const dayVal = e.target.textContent.padStart(2, '0');
        document.getElementById('grossiste-date').value = '2026-05-' + dayVal;
        updateDisplay();
      });
    });
    document.querySelectorAll('.time-slot').forEach(slot => {
      slot.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('time-slot--active'));
        e.target.classList.add('time-slot--active');
        document.getElementById('grossiste-time').value = e.target.textContent;
        updateDisplay();
      });
    });
    function updateDisplay() {
      const d = document.getElementById('grossiste-date').value;
      const t = document.getElementById('grossiste-time').value;
      document.getElementById('grossiste-display').textContent = d + ' à ' + t;
    }
  </script>
</body>
</html>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
<script>
    function toggleSiret() {
      const isEntreprise = document.querySelector('input[name="type"]:checked')?.value === 'entreprise';
      var el = document.getElementById('siret-fields'); if (el) el.style.display = isEntreprise ? '' : 'none';
    }
    document.querySelectorAll('.calendar__day:not(.calendar__day--disabled)').forEach(day => {
      day.addEventListener('click', e => {
        document.querySelectorAll('.calendar__day').forEach(d => d.classList.remove('calendar__day--active'));
        e.target.classList.add('calendar__day--active');
        document.querySelector('.time-slots__title').textContent = `Créneaux disponibles le ${e.target.textContent} Mai`;
        const dayVal = e.target.textContent.padStart(2, '0');
        document.getElementById('grossiste-date').value = '2026-05-' + dayVal;
        updateDisplay();
      });
    });
    document.querySelectorAll('.time-slot').forEach(slot => {
      slot.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('time-slot--active'));
        e.target.classList.add('time-slot--active');
        document.getElementById('grossiste-time').value = e.target.textContent;
        updateDisplay();
      });
    });
    function updateDisplay() {
      var d = document.getElementById('grossiste-date').value;
      var t = document.getElementById('grossiste-time').value;
      document.getElementById('grossiste-display').textContent = d + ' à ' + t;
    }
  </script>
@endsection
