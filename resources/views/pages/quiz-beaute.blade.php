@extends('layouts.public')
@section('title', 'Quiz Beauté — Découvrez votre routine idéale')

@section('head')
<style>
.quiz-hero { min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg, #faf7f2 0%, #fff 100%);padding:6rem 1.5rem; }
.quiz-card { max-width:560px;width:100%;background:#fff;border-radius:24px;padding:3rem 2rem;box-shadow:0 4px 40px rgba(61,43,43,0.06);border:1px solid #ede4db; }
.quiz-progress { height:4px;background:#ede4db;border-radius:2px;margin-bottom:2rem;position:relative; }
.quiz-progress-fill { height:100%;background:var(--color-warm);border-radius:2px;transition:width 0.4s ease; }
.quiz-step-num { font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#c4967a;margin-bottom:0.5rem; }
.quiz-question { font-family:var(--font-heading);font-size:1.5rem;font-weight:400;color:#2d2117;margin-bottom:1.5rem; }
.quiz-option { display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border:2px solid #d1d5db;border-radius:12px;cursor:pointer;transition:all 0.2s;margin-bottom:0.75rem;font-size:15px;color:#374151; }
.quiz-option:hover { border-color:var(--color-warm); }
.quiz-option.selected { border-color:var(--color-warm);background:rgba(196,150,122,0.06); }
.quiz-option-icon { width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
.quiz-btn { display:inline-flex;align-items:center;gap:0.5rem;padding:12px 28px;background:var(--color-dark);color:#fff;border:none;border-radius:99px;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.2s; }
.quiz-btn:hover { background:var(--color-warm);transform:translateY(-1px); }
.quiz-btn-outline { background:transparent;color:var(--color-dark);border:2px solid #d1d5db; }
.quiz-btn-outline:hover { border-color:var(--color-dark);background:transparent;color:var(--color-dark); }
.quiz-input { display:block;width:100%;padding:12px 16px;border:2px solid #d1d5db;border-radius:12px;font-size:15px;background:#fff;outline:none;transition:border-color 0.2s; }
.quiz-input:focus { border-color:var(--color-warm);box-shadow:0 0 0 3px rgba(196,150,122,0.1); }
.quiz-check { display:flex;align-items:flex-start;gap:0.75rem;font-size:13px;color:#8b7b6e;line-height:1.5;margin-top:1rem; }
.quiz-check input { width:20px;height:20px;accent-color:var(--color-warm);margin-top:2px;flex-shrink:0; }
.quiz-check a { color:var(--color-warm);text-decoration:underline; }
@media (max-width:600px) { .quiz-card { padding:2rem 1.25rem; } .quiz-question { font-size:1.25rem; } }
</style>
@endsection

@section('content')
<main class="quiz-hero">
  <div class="quiz-card reveal-k-k">
    <div style="text-align:center;margin-bottom:1.5rem;">
      <span style="font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:#c4967a;font-weight:600;">KATUISCIA</span>
    </div>
    <div class="quiz-progress"><div class="quiz-progress-fill" id="progress-bar" style="width:0%;"></div></div>

    <form method="POST" action="{{ route('quiz.store') }}" id="quiz-form">
      @csrf

      {{-- STEP 1 --}}
      <div class="quiz-step" data-step="1">
        <div class="quiz-step-num">Étape 1/4</div>
        <div class="quiz-question">Quel est votre type de peau ?</div>
        @foreach(['seche'=>'🌵 Sèche','mixte'=>'⚖️ Mixte','grasse'=>'💧 Grasse','sensible'=>'🌸 Sensible','normale'=>'✨ Normale'] as $v=>$l)
        <div class="quiz-option" onclick="selectOption(this,'skin_type','{{ $v }}')"><span class="quiz-option-icon" style="background:#faf7f2;">{{ explode(' ',$l)[0] }}</span>{{ explode(' ',$l,2)[1] }}</div>
        @endforeach
        <input type="hidden" name="skin_type" id="input-skin_type" value="">
      </div>

      {{-- STEP 2 --}}
      <div class="quiz-step" data-step="2" style="display:none;">
        <div class="quiz-step-num">Étape 2/4</div>
        <div class="quiz-question">Quelle est votre préoccupation principale ?</div>
        @foreach(['eclat'=>'✨ Éclat','anti_age'=>'⏳ Anti-âge','hydratation'=>'💦 Hydratation','pores'=>'🔍 Pores','taches'=>'☀️ Taches'] as $v=>$l)
        <div class="quiz-option" onclick="selectOption(this,'concern','{{ $v }}')"><span class="quiz-option-icon" style="background:#faf7f2;">{{ explode(' ',$l)[0] }}</span>{{ explode(' ',$l,2)[1] }}</div>
        @endforeach
        <input type="hidden" name="concern" id="input-concern" value="">
      </div>

      {{-- STEP 3 --}}
      <div class="quiz-step" data-step="3" style="display:none;">
        <div class="quiz-step-num">Étape 3/4</div>
        <div class="quiz-question">Quel est votre budget mensuel beauté ?</div>
        @foreach(['low'=>'Moins de 30€','mid'=>'30€ — 60€','high'=>'60€ — 100€','premium'=>'+100€'] as $v=>$l)
        <div class="quiz-option" onclick="selectOption(this,'budget','{{ $v }}')"><span class="quiz-option-icon" style="background:#faf7f2;">{{ Str::substr($l,0,1)=='+' ? '💎' : '💰' }}</span>{{ $l }}</div>
        @endforeach
        <input type="hidden" name="budget" id="input-budget" value="">
      </div>

      {{-- STEP 4 --}}
      <div class="quiz-step" data-step="4" style="display:none;">
        <div class="quiz-step-num">Étape 4/4</div>
        <div class="quiz-question">Avez-vous une routine beauté actuelle ?</div>
        @foreach(['complete'=>'✅ Oui, complète','basic'=>'📝 Oui, basique','none'=>'🌱 Non, je débute'] as $v=>$l)
        <div class="quiz-option" onclick="selectOption(this,'routine','{{ $v }}')"><span class="quiz-option-icon" style="background:#faf7f2;">{{ explode(' ',$l)[0] }}</span>{{ explode(' ',$l,2)[1] }}</div>
        @endforeach
        <input type="hidden" name="routine" id="input-routine" value="">
      </div>

      {{-- FINAL --}}
      <div class="quiz-step" data-step="5" style="display:none;">
        <div class="quiz-step-num">Dernière étape</div>
        <div class="quiz-question">Où devons-nous envoyer vos résultats ?</div>
        <div style="display:flex;flex-direction:column;gap:1rem;">
          <input type="text" name="firstname" class="quiz-input" placeholder="Votre prénom" required>
          <input type="email" name="email" class="quiz-input" placeholder="votre@email.com" required>
          <label class="quiz-check">
            <input type="checkbox" name="opted_in" required>
            <span>J'accepte de recevoir mes résultats et des conseils beauté par email. <a href="{{ url('politique-confidentialite') }}" target="_blank">Politique de confidentialité</a></span>
          </label>
          <button type="submit" class="quiz-btn" style="width:100%;justify-content:center;">Recevoir mes recommandations personnalisées →</button>
        </div>
      </div>
    </form>

    <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:1.5rem;">
      <button class="quiz-btn quiz-btn-outline" onclick="prevStep()" id="btn-prev" style="display:none;">← Retour</button>
    </div>
  </div>
</main>
@endsection

@section('scripts')
<script>
var currentStep = 1;
var totalSteps = 5;

function selectOption(el, field, value) {
  el.parentElement.querySelectorAll('.quiz-option').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('input-'+field).value = value;
  setTimeout(nextStep, 300);
}

function nextStep() {
  if (currentStep >= totalSteps) return;
  document.querySelector('[data-step="'+currentStep+'"]').style.display = 'none';
  currentStep++;
  document.querySelector('[data-step="'+currentStep+'"]').style.display = '';
  updateUI();
  if (currentStep === totalSteps) document.querySelector('.quiz-step[data-step="5"] input[name="firstname"]').focus();
}

function prevStep() {
  if (currentStep <= 1) return;
  document.querySelector('[data-step="'+currentStep+'"]').style.display = 'none';
  currentStep--;
  document.querySelector('[data-step="'+currentStep+'"]').style.display = '';
  updateUI();
}

function updateUI() {
  document.getElementById('progress-bar').style.width = ((currentStep-1)/(totalSteps-1)*100)+'%';
  document.getElementById('btn-prev').style.display = currentStep > 1 && currentStep < totalSteps ? '' : 'none';
}

document.getElementById('quiz-form').addEventListener('submit', function(e){
  if (this.querySelector('[name="firstname"]').value.trim() === '') {
    e.preventDefault();
    return false;
  }
});
</script>
@endsection
