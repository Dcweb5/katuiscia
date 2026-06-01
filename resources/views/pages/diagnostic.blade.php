@extends('layouts.public')

@section('title', 'Diagnostic de Peau IA — KATUISCIA')
@section('description', 'Analysez la texture et les besoins de votre épiderme grâce à notre diagnostic IA et recevez une routine botanique sur-mesure.')

@section('head')
<style>
  /* Layout padding for header offset */
  .diagnostic-page-main {
    padding-top: calc(var(--header-height) + 2rem);
    padding-bottom: 4rem;
  }
  @media (min-width: 1024px) {
    .diagnostic-page-main {
      padding-top: calc(var(--header-height) + 4rem);
      padding-bottom: 6rem;
    }
  }

  /* Base layout */
  .diagnostic-container {
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem var(--space-xl);
  }

  /* Desktop block (Scan QR Code) */
  .desktop-only-wrapper {
    background: #fff;
    border: 1px solid #ede4db;
    border-radius: 20px;
    padding: 3rem 2rem;
    max-width: 500px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(61,43,43,0.05);
  }

  .qr-frame {
    display: inline-block;
    padding: 1rem;
    background: var(--color-cream);
    border: 2px solid var(--color-warm);
    border-radius: 12px;
    margin-bottom: 2rem;
  }

  /* Mobile wizard wrapper */
  .wizard-card {
    background: #fff;
    border: 1px solid #ede4db;
    border-radius: 20px;
    padding: 2rem 1.5rem;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 10px 30px rgba(61,43,43,0.04);
  }

  /* Progress bar indicator */
  .wizard-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
  }

  .wizard-progress::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--color-border);
    transform: translateY(-50%);
    z-index: 1;
  }

  .progress-bar-fill {
    position: absolute;
    top: 50%;
    left: 0;
    height: 2px;
    background: var(--color-warm);
    transform: translateY(-50%);
    z-index: 2;
    transition: width 0.3s ease;
    width: 0%;
  }

  .progress-step {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid var(--color-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-muted);
    position: relative;
    z-index: 3;
    transition: all 0.3s ease;
  }

  .progress-step.active {
    border-color: var(--color-warm);
    background: var(--color-warm);
    color: #fff;
  }

  .progress-step.completed {
    border-color: var(--color-warm);
    background: var(--color-warm);
    color: #fff;
  }

  /* Custom radio cards */
  .radio-card-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
  }

  .radio-card {
    border: 1px solid var(--color-border);
    border-radius: 12px;
    padding: 1rem;
    cursor: pointer;
    text-align: center;
    transition: all 0.25s ease;
    background: #fff;
  }

  .radio-card:hover {
    border-color: var(--color-warm);
    background: rgba(196,150,122,0.03);
  }

  .radio-card input {
    display: none;
  }

  .radio-card.selected {
    border-color: var(--color-warm);
    background: rgba(196,150,122,0.06);
    box-shadow: 0 0 0 1px var(--color-warm);
  }

  .radio-card__title {
    font-weight: 600;
    font-size: 13px;
    color: var(--color-dark);
  }

  .radio-card__desc {
    font-size: 11px;
    color: var(--color-text-light);
    margin-top: 3px;
  }

  /* Selfie Capture Styles */
  .selfie-scan-area {
    width: 100%;
    aspect-ratio: 1;
    border: 2px dashed var(--color-border);
    border-radius: 16px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--color-bg);
    overflow: hidden;
    margin-bottom: 1.5rem;
  }

  .selfie-face-outline {
    position: absolute;
    width: 60%;
    height: 75%;
    border: 2px solid rgba(196,150,122,0.4);
    border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
    pointer-events: none;
    z-index: 10;
  }

  /* Scan line animation */
  .scan-line {
    position: absolute;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(to bottom, rgba(196,150,122,0), rgba(196,150,122,0.8), rgba(196,150,122,0));
    z-index: 15;
    pointer-events: none;
    top: 0;
    animation: scanAnimation 2s infinite linear;
    display: none;
  }

  @keyframes scanAnimation {
    0% { top: 0%; }
    50% { top: 100%; }
    100% { top: 0%; }
  }

  .selfie-preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* Responsive show/hide */
  .desktop-only-view {
    display: flex;
  }

  .mobile-only-view {
    display: none;
  }

  @media (max-width: 900px) {
    .desktop-only-view {
      display: none !important;
    }
    .mobile-only-view {
      display: block !important;
    }
  }
</style>
@endsection

@section('content')
<div class="diagnostic-page-main bg-cream">

  <!-- DESKTOP SCREEN (REDIRECT TO MOBILE) -->
  <div class="diagnostic-container desktop-only-view" style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1.2fr 0.8fr; gap:4rem; align-items:center;">
    
    <!-- Left Column: Explanation of the page and step-by-step process -->
    <div style="text-align:left;">
      <span class="landing-hero__badge" style="margin-bottom:15px; display:inline-block; font-size:10px; letter-spacing:0.25em; text-transform:uppercase; padding:6px 16px; border:1px solid var(--color-warm); border-radius:100px; color:var(--color-warm); background:rgba(196,150,122,0.06);">Diagnostic de Peau IA</span>
      <h1 class="font-heading text-4xl font-light mb-4 text-dark" style="line-height:1.15;">L'Expertise Botanique & l'IA pour Votre Peau</h1>
      <p class="text-sm text-text-light mb-8" style="line-height:1.6;">
        Découvrez les besoins réels de votre épiderme grâce à notre diagnostic de peau intelligent. En couplant l'analyse visuelle par IA et vos habitudes de vie, nous déterminons votre type de peau, identifions les imperfections (acné, ridules, rougeurs, taches) et vous conseillons une routine de soins sur-mesure basée sur nos formulations botaniques actives.
      </p>

      <h3 style="font-family:var(--font-heading); font-size:16px; font-weight:600; color:var(--color-dark); margin-bottom:1.25rem;">Le processus en 4 étapes simples :</h3>
      <div style="display:flex; flex-direction:column; gap:1.25rem;">
        <div style="display:flex; gap:1rem; align-items:start;">
          <div style="width:24px; height:24px; border-radius:50%; background:var(--color-warm); color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; margin-top:2px;">1</div>
          <div>
            <h4 style="font-size:13px; font-weight:600; color:var(--color-dark); margin:0 0 2px;">Scannez le QR Code</h4>
            <p style="font-size:12px; color:var(--color-text-light); line-height:1.45; margin:0;">Utilisez votre smartphone pour flasher le QR Code ci-contre et ouvrir l'expérience de diagnostic mobile.</p>
          </div>
        </div>
        <div style="display:flex; gap:1rem; align-items:start;">
          <div style="width:24px; height:24px; border-radius:50%; background:var(--color-warm); color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; margin-top:2px;">2</div>
          <div>
            <h4 style="font-size:13px; font-weight:600; color:var(--color-dark); margin:0 0 2px;">Complétez le questionnaire</h4>
            <p style="font-size:12px; color:var(--color-text-light); line-height:1.45; margin:0;">Répondez à quelques questions rapides sur vos préoccupations cutanées et vos produits actuels.</p>
          </div>
        </div>
        <div style="display:flex; gap:1rem; align-items:start;">
          <div style="width:24px; height:24px; border-radius:50%; background:var(--color-warm); color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; margin-top:2px;">3</div>
          <div>
            <h4 style="font-size:13px; font-weight:600; color:var(--color-dark); margin:0 0 2px;">Prenez un Selfie</h4>
            <p style="font-size:12px; color:var(--color-text-light); line-height:1.45; margin:0;">Prenez une photo nette de votre visage sans maquillage pour lancer l'analyse de votre texture de peau.</p>
          </div>
        </div>
        <div style="display:flex; gap:1rem; align-items:start;">
          <div style="width:24px; height:24px; border-radius:50%; background:var(--color-warm); color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; margin-top:2px;">4</div>
          <div>
            <h4 style="font-size:13px; font-weight:600; color:var(--color-dark); margin:0 0 2px;">Découvrez votre routine</h4>
            <p style="font-size:12px; color:var(--color-text-light); line-height:1.45; margin:0;">L'IA vous prescrit votre routine de soins botaniques KATUISCIA personnalisée et des conseils d'application.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: QR Code scanning container -->
    <div style="display:flex; justify-content:center;">
      <div class="desktop-only-wrapper" style="width:100%; max-width:380px;">
        <h3 class="font-heading text-lg font-light mb-4 text-dark">Démarrer l'analyse</h3>
        <div class="qr-frame" style="margin-bottom:1.5rem;">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(request()->fullUrl()) }}" alt="QR Code" style="display:block;width:180px;height:180px;">
        </div>
        <p class="text-xs text-text-light" style="line-height:1.5;">Scannez ce QR Code avec l'appareil photo de votre smartphone pour continuer l'analyse sur mobile.</p>
      </div>
    </div>

  </div>

  <!-- MOBILE SCREEN (ONBOARDING FLOW) -->
  <div class="diagnostic-container mobile-only-view" style="padding-top:1rem;padding-bottom:3rem;">
    <div class="wizard-card" id="wizard-card-root">
      
      <!-- Progress Bar -->
      <div class="wizard-progress">
        <div class="progress-bar-fill" id="progress-bar-fill"></div>
        <div class="progress-step active" data-step="1">1</div>
        <div class="progress-step" data-step="2">2</div>
        <div class="progress-step" data-step="3">3</div>
        <div class="progress-step" data-step="4">4</div>
      </div>

      <!-- STEP 1: CONTACT INFO -->
      <div class="wizard-step-pane" id="step-pane-1">
        <h2 class="font-heading text-xl font-light mb-2 text-dark text-center">Vos Coordonnées</h2>
        <p class="text-xs text-text-light mb-6 text-center">Renseignez vos coordonnées pour recevoir votre ordonnance beauté.</p>
        
        <div class="form-group mb-4">
          <label class="form-label" style="font-size:11px;font-weight:600;margin-bottom:4px;display:block;">Nom complet *</label>
          <input type="text" id="diag-name" class="form-input" placeholder="Ex: Marie Dupont" style="width:100%;" required>
        </div>
        <div class="form-group mb-4">
          <label class="form-label" style="font-size:11px;font-weight:600;margin-bottom:4px;display:block;">Adresse E-mail *</label>
          <input type="email" id="diag-email" class="form-input" placeholder="Ex: marie@exemple.com" style="width:100%;" required>
        </div>
        <div class="form-group mb-6">
          <label class="form-label" style="font-size:11px;font-weight:600;margin-bottom:4px;display:block;">Téléphone</label>
          <input type="tel" id="diag-phone" class="form-input" placeholder="Ex: +33 6 12 34 56 78" style="width:100%;">
        </div>

        <button onclick="goToStep(2)" class="btn-katuiscia-filled" style="width:100%;justify-content:center;height:44px;font-size:11px;">Continuer</button>
      </div>

      <!-- STEP 2: SKIN TYPE & CONCERNS -->
      <div class="wizard-step-pane" id="step-pane-2" style="display:none;">
        <h2 class="font-heading text-xl font-light mb-1 text-dark text-center">Votre Peau & Habitudes</h2>
        <p class="text-xs text-text-light mb-6 text-center">Ces informations aideront l'IA à affiner ses recommandations.</p>
        
        <!-- Skin Type selection -->
        <label class="form-label" style="font-size:11px;font-weight:600;margin-bottom:6px;display:block;">Quel est votre type de peau ? *</label>
        <div class="radio-card-grid">
          <div class="radio-card" onclick="selectRadioCard('skin_type', this)">
            <input type="radio" name="skin_type" value="Dry">
            <span class="radio-card__title">Sèche</span>
            <p class="radio-card__desc">Tiraille, pèle, rugueuse</p>
          </div>
          <div class="radio-card" onclick="selectRadioCard('skin_type', this)">
            <input type="radio" name="skin_type" value="Oily">
            <span class="radio-card__title">Grasse</span>
            <p class="radio-card__desc">Brillances, pores dilatés</p>
          </div>
          <div class="radio-card" onclick="selectRadioCard('skin_type', this)">
            <input type="radio" name="skin_type" value="Combination">
            <span class="radio-card__title">Mixte</span>
            <p class="radio-card__desc">Zone T grasse, joues sèches</p>
          </div>
          <div class="radio-card" onclick="selectRadioCard('skin_type', this)">
            <input type="radio" name="skin_type" value="Sensitive">
            <span class="radio-card__title">Sensible</span>
            <p class="radio-card__desc">Rougit, pique, réactive</p>
          </div>
        </div>

        <!-- Skin Concern selection -->
        <label class="form-label" style="font-size:11px;font-weight:600;margin-bottom:6px;display:block;">Préoccupation majeure *</label>
        <div class="radio-card-grid">
          <div class="radio-card" onclick="selectRadioCard('concern', this)">
            <input type="radio" name="concern" value="Acne/Imperfections">
            <span class="radio-card__title">Boutons / Acné</span>
          </div>
          <div class="radio-card" onclick="selectRadioCard('concern', this)">
            <input type="radio" name="concern" value="Wrinkles/Aging">
            <span class="radio-card__title">Rides / Fermeté</span>
          </div>
          <div class="radio-card" onclick="selectRadioCard('concern', this)">
            <input type="radio" name="concern" value="Redness/Rosacea">
            <span class="radio-card__title">Rougeurs</span>
          </div>
          <div class="radio-card" onclick="selectRadioCard('concern', this)">
            <input type="radio" name="concern" value="Dark Spots">
            <span class="radio-card__title">Taches Brunes</span>
          </div>
        </div>

        <div class="form-group mb-6">
          <label class="form-label" style="font-size:11px;font-weight:600;margin-bottom:4px;display:block;">Quels produits utilisez-vous actuellement ?</label>
          <textarea id="diag-current-products" class="form-textarea" placeholder="Ex: Eau micellaire X, crème de jour hydratante Y..." rows="2" style="width:100%;font-size:12px;"></textarea>
        </div>

        <div style="display:flex;gap:0.75rem;">
          <button onclick="goToStep(1)" class="btn-katuiscia" style="flex:1;justify-content:center;height:44px;font-size:11px;padding:0;">Retour</button>
          <button onclick="goToStep(3)" class="btn-katuiscia-filled" style="flex:2;justify-content:center;height:44px;font-size:11px;">Suivant</button>
        </div>
      </div>

      <!-- STEP 3: SELFIE UPLOAD -->
      <div class="wizard-step-pane" id="step-pane-3" style="display:none;">
        <h2 class="font-heading text-xl font-light mb-1 text-dark text-center">Votre Photo / Selfie</h2>
        <p class="text-xs text-text-light mb-6 text-center">Prenez un selfie bien éclairé sans maquillage ni lunettes.</p>
        
        <div class="selfie-scan-area" id="selfie-scan-container">
          <div class="selfie-face-outline" id="face-outline"></div>
          <div class="scan-line" id="scanner-line-widget"></div>
          <div id="selfie-placeholder-content" style="text-align:center;padding:1.5rem;z-index:5;">
            <div style="font-size:32px;margin-bottom:10px;">📷</div>
            <p style="font-size:12px;font-weight:500;color:var(--color-dark);">Prenez ou importez une photo</p>
            <p style="font-size:10px;color:var(--color-text-light);margin-top:2px;">Votre photo ne sera utilisée que pour le diagnostic.</p>
          </div>
          <img id="selfie-preview" class="selfie-preview-img" style="display:none;">
        </div>

        <input type="file" id="selfie-file" accept="image/*" capture="user" style="display:none;" onchange="handleSelfieSelected(this.files)">

        <button onclick="document.getElementById('selfie-file').click()" class="btn-katuiscia" style="width:100%;justify-content:center;height:44px;font-size:11px;margin-bottom:1.25rem;">
          <span>Prendre mon selfie</span>
        </button>

        <div style="display:flex;gap:0.75rem;">
          <button onclick="goToStep(2)" class="btn-katuiscia" style="flex:1;justify-content:center;height:44px;font-size:11px;padding:0;">Retour</button>
          <button id="btn-trigger-analysis" onclick="submitDiagnostic()" class="btn-katuiscia-filled" style="flex:2;justify-content:center;height:44px;font-size:11px;" disabled>Lancer l'analyse</button>
        </div>
      </div>

      <!-- STEP 4: ANALYSIS LOADER -->
      <div class="wizard-step-pane" id="step-pane-4" style="display:none;text-align:center;padding:2rem 0;">
        <div style="width:80px;height:80px;margin:0 auto 2rem;position:relative;">
          <div style="box-sizing:border-box;display:block;position:absolute;width:64px;height:64px;margin:8px;border:4px solid var(--color-warm);border-radius:50%;animation:lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;border-color:var(--color-warm) transparent transparent transparent;"></div>
        </div>
        <style>
          @keyframes lds-ring { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        </style>
        <h3 class="font-heading text-lg text-dark mb-2" id="loader-title">Analyse en cours...</h3>
        <p class="text-xs text-text-light" id="loader-message">Initialisation de l'analyse visuelle...</p>
      </div>

      <!-- STEP 5: RESULTS SCREEN (DYNAMICAL) -->
      <div class="wizard-step-pane" id="step-pane-5" style="display:none;">
        <div style="text-align:center;margin-bottom:1.5rem;">
          <div style="font-size:2rem;margin-bottom:6px;">✨</div>
          <h2 class="font-heading text-xl font-light text-dark">Votre Ordonnance Beauté</h2>
          <p class="text-xs text-text-light">Diagnostic formulé par notre intelligence artificielle.</p>
        </div>

        <div class="card" style="padding:1rem;background:var(--color-cream);border-radius:12px;margin-bottom:1.5rem;border:1px solid #ede4db;">
          <h3 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.05em;">🔍 Imperfections détectées</h3>
          <div id="result-badges" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;"></div>

          <h3 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">📋 Analyse de votre peau</h3>
          <p id="result-details" style="font-size:12px;color:var(--color-text-light);line-height:1.55;"></p>
        </div>

        <div id="result-warning" class="card" style="padding:1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;margin-bottom:1.5rem;display:none;">
          <p style="font-size:11px;color:#991b1b;line-height:1.5;font-weight:500;">
            ⚠️ <strong>Avertissement :</strong> Les imperfections de votre peau présentent des signes inflammatoires ou sévères. Nous vous conseillons vivement de consulter un dermatologue pour un examen clinique.
          </p>
        </div>

        <h3 style="font-family:var(--font-heading);font-size:14px;color:var(--color-dark);margin-bottom:1rem;border-bottom:1px solid var(--color-border);padding-bottom:6px;">🌿 Votre Routine Botanique Conseillée</h3>
        <div id="result-products" style="display:flex;flex-direction:column;gap:1rem;margin-bottom:1.5rem;"></div>

        <h3 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.05em;">💡 Conseils de rituels</h3>
        <div id="result-advice" style="font-size:12px;color:var(--color-text-light);line-height:1.55;background:var(--color-bg);padding:1rem;border-radius:8px;white-space:pre-line;margin-bottom:1.5rem;"></div>

        <button onclick="resetWizard()" class="btn-katuiscia" style="width:100%;justify-content:center;height:44px;font-size:11px;">Faire un nouveau diagnostic</button>
      </div>

    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  let currentStep = 1;
  let selectedFile = null;

  // On step loading messages
  const loaderMessages = [
    { title: "Analyse de la texture cutanée...", msg: "Détection des pores et ridules de surface." },
    { title: "Évaluation épidermique...", msg: "Identification des zones sensibles et rougeurs." },
    { title: "Consultation du catalogue...", msg: "Sélection des actifs botaniques les plus adaptés." },
    { title: "Formulation du rituel...", msg: "Création de votre ordonnance beauté sur-mesure." }
  ];

  function selectRadioCard(inputName, cardEl) {
    // Deselect other cards in same group
    const group = cardEl.parentElement;
    group.querySelectorAll('.radio-card').forEach(card => card.classList.remove('selected'));
    
    // Select this card
    cardEl.classList.add('selected');
    cardEl.querySelector('input').checked = true;
  }

  function goToStep(step) {
    // Validation before changing step
    if (step === 2) {
      const name = document.getElementById('diag-name').value.trim();
      const email = document.getElementById('diag-email').value.trim();
      if (!name || !email) {
        alert("Veuillez renseigner votre nom et votre adresse e-mail pour continuer.");
        return;
      }
    }
    if (step === 3) {
      const skinType = document.querySelector('input[name="skin_type"]:checked');
      const concern = document.querySelector('input[name="concern"]:checked');
      if (!skinType || !concern) {
        alert("Veuillez sélectionner votre type de peau et votre préoccupation majeure.");
        return;
      }
    }

    // Hide all step panes
    document.querySelectorAll('.wizard-step-pane').forEach(pane => pane.style.display = 'none');
    
    // Show target step pane
    document.getElementById('step-pane-' + step).style.display = 'block';

    // Update progress steps active state
    document.querySelectorAll('.progress-step').forEach(stepEl => {
      const s = parseInt(stepEl.getAttribute('data-step'));
      if (s <= step) {
        stepEl.classList.add('active');
      } else {
        stepEl.classList.remove('active');
      }
    });

    // Update progress line width
    const fillWidth = ((step - 1) / 3) * 100;
    document.getElementById('progress-bar-fill').style.width = fillWidth + '%';

    currentStep = step;
  }

  function handleSelfieSelected(files) {
    if (files.length === 0) return;
    selectedFile = files[0];
    
    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('selfie-preview');
      preview.src = e.target.result;
      preview.style.display = 'block';
      document.getElementById('selfie-placeholder-content').style.display = 'none';
      document.getElementById('face-outline').style.display = 'none';
      document.getElementById('btn-trigger-analysis').removeAttribute('disabled');
    }
    reader.readAsDataURL(selectedFile);
  }

  function submitDiagnostic() {
    goToStep(4);

    // Start cycling loader messages
    let msgIdx = 0;
    const loaderInterval = setInterval(() => {
      if (currentStep !== 4) {
        clearInterval(loaderInterval);
        return;
      }
      document.getElementById('loader-title').textContent = loaderMessages[msgIdx].title;
      document.getElementById('loader-message').textContent = loaderMessages[msgIdx].msg;
      msgIdx = (msgIdx + 1) % loaderMessages.length;
    }, 1500);

    // Prepare FormData
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('name', document.getElementById('diag-name').value.trim());
    formData.append('email', document.getElementById('diag-email').value.trim());
    formData.append('phone', document.getElementById('diag-phone').value.trim());
    formData.append('skin_type', document.querySelector('input[name="skin_type"]:checked').value);
    formData.append('concern', document.querySelector('input[name="concern"]:checked').value);
    formData.append('current_products', document.getElementById('diag-current-products').value.trim());
    formData.append('selfie', selectedFile);

    // Trigger visual scanner lines
    document.getElementById('scanner-line-widget').style.display = 'block';

    fetch('{{ route("diagnostic.store") }}', {
      method: 'POST',
      body: formData
    })
    .then(r => {
      if (!r.ok) {
        throw new Error("L'analyse a échoué. Veuillez réessayer.");
      }
      return r.json();
    })
    .then(data => {
      clearInterval(loaderInterval);
      if (data.success) {
        displayResults(data.result);
      } else {
        alert(data.message || "Une erreur est survenue lors de l'analyse.");
        goToStep(3);
      }
    })
    .catch(err => {
      clearInterval(loaderInterval);
      alert(err.message || "Impossible de joindre le serveur pour analyser l'image.");
      goToStep(3);
    });
  }

  function displayResults(result) {
    // Badges
    const badgeContainer = document.getElementById('result-badges');
    badgeContainer.innerHTML = '';
    result.imperfections.forEach(imp => {
      badgeContainer.innerHTML += `<span style="font-size:10px;font-weight:600;color:var(--color-warm);background:rgba(196,150,122,0.1);padding:4px 10px;border-radius:100px;text-transform:capitalize;">${imp}</span>`;
    });

    // Details & Advice
    document.getElementById('result-details').textContent = result.analysis_details;
    document.getElementById('result-advice').textContent = result.general_advice;

    // Severity warning
    const warningEl = document.getElementById('result-warning');
    if (result.consult_specialist || result.severity === 'high') {
      warningEl.style.display = 'block';
    } else {
      warningEl.style.display = 'none';
    }

    // Recommended Products
    const productsContainer = document.getElementById('result-products');
    productsContainer.innerHTML = '';
    result.recommended_products.forEach(prod => {
      let imgPath = prod.image || '/assets/images/product-placeholder.jpg';
      let priceTag = prod.price ? `<span style="font-size:11px;font-weight:600;color:var(--color-warm);margin-left:8px;">${prod.price}</span>` : '';
      let productUrl = prod.slug ? '{{ url("/produit") }}/' + prod.slug : 'javascript:void(0)';
      let targetAttr = prod.slug ? 'target="_blank"' : '';

      productsContainer.innerHTML += `
        <div style="display:flex;gap:12px;background:#fff;border:1px solid #ede4db;border-radius:12px;padding:0.75rem;align-items:center;">
          <a href="${productUrl}" ${targetAttr} style="flex-shrink:0;display:block;">
            <img src="${imgPath}" alt="${prod.product_name}" style="width:64px;height:64px;border-radius:8px;object-fit:cover;background:#faf7f2;display:block;">
          </a>
          <div style="flex:1;">
            <h4 style="font-family:var(--font-heading);font-size:13px;font-weight:500;margin:0 0 2px;">
              <a href="${productUrl}" ${targetAttr} style="color:var(--color-dark);text-decoration:none;">
                ${prod.product_name}
              </a>
              ${priceTag}
            </h4>
            <p style="font-size:11px;color:var(--color-text-light);line-height:1.4;margin-bottom:6px;">${prod.reason}</p>
            <button class="cart-add-btn btn-katuiscia-filled" data-product-id="${prod.product_id}" data-quantity="1" style="height:28px;padding:0 12px;font-size:9px;border-radius:4px;">
              <span>Ajouter au panier</span>
            </button>
          </div>
        </div>
      `;
    });

    // Hide progress bar and show step 5 pane
    document.querySelector('.wizard-progress').style.display = 'none';
    document.querySelectorAll('.wizard-step-pane').forEach(pane => pane.style.display = 'none');
    document.getElementById('step-pane-5').style.display = 'block';
  }

  function resetWizard() {
    selectedFile = null;
    document.getElementById('selfie-preview').style.display = 'none';
    document.getElementById('selfie-placeholder-content').style.display = 'block';
    document.getElementById('face-outline').style.display = 'block';
    document.getElementById('scanner-line-widget').style.display = 'none';
    document.getElementById('btn-trigger-analysis').setAttribute('disabled', 'true');
    document.getElementById('selfie-file').value = '';
    
    // Reset forms
    document.querySelectorAll('.radio-card').forEach(card => card.classList.remove('selected'));
    document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
    document.getElementById('diag-current-products').value = '';

    // Show progress bar again
    document.querySelector('.wizard-progress').style.display = 'flex';
    goToStep(1);
  }
</script>
@endsection
