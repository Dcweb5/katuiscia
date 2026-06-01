@extends('layouts.admin')
@section('title', 'Gestion Newsletter')
@section('content')
<div class="dashboard-content" style="font-family: Inter, system-ui, sans-serif;">
  
  {{-- Header section --}}
  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:var(--space-xl); flex-wrap:wrap; gap:1rem;">
    <div>
      <h1 class="page-title" style="font-family: var(--font-display), serif; font-size: 2rem; font-weight: 300; color: var(--color-dark); margin: 0;">Newsletter</h1>
      <p class="page-subtitle" style="color: var(--color-text-muted); font-size: 14px; margin-top: 4px;">Gérez la liste de diffusion et envoyez des campagnes promotionnelles.</p>
    </div>
    <div style="display:flex; gap:1rem;">
      <a href="{{ route('admin.newsletter.campaign.create') }}" class="btn-primary" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; padding:10px 18px; background:var(--color-warm); color:#fff; border-radius:8px; font-weight:500; font-size:14px; border:none; cursor:pointer;">
        ✉️ Nouvelle Campagne
      </a>
    </div>
  </div>

  {{-- Stats cards row --}}
  <div style="display:grid; grid-template-cols: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="padding: 1.5rem; background:#fff; border: 1px solid #ede4db; border-radius: 12px; display:flex; flex-direction:column; gap:4px;">
      <span style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; tracking: 1px;">Abonnés Actifs</span>
      <span style="font-size: 2rem; font-weight: 600; color: var(--color-dark);">{{ $activeCount }}</span>
    </div>
    <div class="card" style="padding: 1.5rem; background:#fff; border: 1px solid #ede4db; border-radius: 12px; display:flex; flex-direction:column; gap:4px;">
      <span style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; tracking: 1px;">Désabonnés</span>
      <span style="font-size: 2rem; font-weight: 600; color: var(--color-text-muted);">{{ $inactiveCount }}</span>
    </div>
    <div class="card" style="padding: 1.5rem; background:#fff; border: 1px solid #ede4db; border-radius: 12px; display:flex; flex-direction:column; gap:4px;">
      <span style="font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; tracking: 1px;">Total Historique</span>
      <span style="font-size: 2rem; font-weight: 600; color: var(--color-dark);">{{ $activeCount + $inactiveCount }}</span>
    </div>
  </div>

  {{-- Tabs selector --}}
  <div style="display:flex; border-bottom: 2px solid #ede4db; margin-bottom: 2rem; gap: 1.5rem;">
    <button onclick="switchTab('subscribers')" id="tab-btn-subscribers" style="background:none; border:none; padding: 10px 5px; font-weight: 600; font-size: 15px; cursor: pointer; color: var(--color-warm); border-bottom: 3px solid var(--color-warm); margin-bottom:-2px; transition: 0.2s;">
      👥 Liste d'Abonnés ({{ $subscribers->total() }})
    </button>
    <button onclick="switchTab('campaigns')" id="tab-btn-campaigns" style="background:none; border:none; padding: 10px 5px; font-weight: 500; font-size: 15px; cursor: pointer; color: var(--color-text-muted); border-bottom: 3px solid transparent; margin-bottom:-2px; transition: 0.2s;">
      📋 Historique des Envois ({{ $campaigns->total() }})
    </button>
  </div>

  @if(session('success'))
  <div style="padding:16px; background:rgba(90,143,110,0.08); border:1px solid var(--color-success); border-radius:8px; color:var(--color-success); margin-bottom:1.5rem; font-size:14px;">
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div style="padding:16px; background:rgba(199,80,80,0.08); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:1.5rem; font-size:14px;">
    {{ session('error') }}
  </div>
  @endif

  {{-- Section Tab 1: Subscribers list --}}
  <div id="tab-subscribers" style="display:block;">
    <div style="display:grid; grid-template-cols: 1fr; lg:grid-template-cols: 3fr 1.5fr; gap: 2rem; align-items: flex-start;">
      
      {{-- Subscribers Table --}}
      <div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:10px;">
          <h3 style="margin:0; font-size:16px; font-weight:600; color:var(--color-dark);">Abonnés</h3>
          
          <form method="GET" action="{{ route('admin.newsletter.index') }}" style="display:flex; gap:8px;">
            <div class="search-bar" style="width:240px; position:relative; display:flex; align-items:center; border: 1px solid #d1d5db; border-radius: 8px; overflow:hidden; background:#fff; padding:4px 8px;">
              <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par e-mail..." style="border:none; outline:none; font-size:13px; width:100%;">
            </div>
            <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 13px; background:var(--color-dark); color:#fff; border:none; border-radius:8px; cursor:pointer;">Filtrer</button>
            @if($search)
            <a href="{{ route('admin.newsletter.index') }}" style="padding: 6px 12px; font-size: 13px; border:1px solid #d1d5db; color:var(--color-dark); border-radius:8px; text-decoration:none; display:flex; align-items:center;">Effacer</a>
            @endif
          </form>
        </div>

        <div class="card" style="padding:0; overflow:hidden; border: 1px solid #ede4db; border-radius:12px; background:#fff;">
          <table class="admin-table" style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
              <tr style="background:#fcf9f5; border-bottom:1px solid #ede4db;">
                <th style="padding:14px 18px; font-weight:600;">E-mail</th>
                <th style="padding:14px 18px; font-weight:600;">Nom</th>
                <th style="padding:14px 18px; font-weight:600; text-align:center;">Statut</th>
                <th style="padding:14px 18px; font-weight:600;">Inscrit le</th>
                <th style="padding:14px 18px; font-weight:600; text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subscribers as $sub)
              <tr style="border-bottom:1px solid #ede4db;">
                <td style="padding:14px 18px; font-weight:500;">{{ $sub->email }}</td>
                <td style="padding:14px 18px; color:var(--color-text-light);">{{ $sub->name ?? '—' }}</td>
                <td style="padding:14px 18px; text-align:center;">
                  @if($sub->is_active)
                    <span style="padding:3px 8px; background:rgba(90,143,110,0.1); color:var(--color-success); border-radius:12px; font-size:11px; font-weight:600;">Abonné</span>
                  @else
                    <span style="padding:3px 8px; background:rgba(199,80,80,0.1); color:var(--color-error); border-radius:12px; font-size:11px; font-weight:600;">Désabonné</span>
                  @endif
                </td>
                <td style="padding:14px 18px; color:var(--color-text-muted); font-size:12px;">{{ $sub->created_at->format('d/m/Y H:i') }}</td>
                <td style="padding:14px 18px; text-align:right;">
                  <div style="display:inline-flex; gap:6px;">
                    <form method="POST" action="{{ route('admin.newsletter.subscribers.toggle', $sub) }}" style="display:inline;">
                      @csrf @method('PUT')
                      <button type="submit" class="action-btn" title="{{ $sub->is_active ? 'Désactiver' : 'Activer' }}" style="background:none; border: 1px solid #ede4db; padding:6px; border-radius:6px; cursor:pointer; display:flex; align-items:center;">
                        @if($sub->is_active)
                          ❌
                        @else
                          ✅
                        @endif
                      </button>
                    </form>
                    <form method="POST" action="{{ route('admin.newsletter.subscribers.destroy', $sub) }}" style="display:inline;" onsubmit="event.preventDefault(); if(confirm('Supprimer cet abonné ?')) this.submit();">
                      @csrf @method('DELETE')
                      <button type="submit" class="action-btn" title="Supprimer" style="background:none; border: 1px solid #ede4db; padding:6px; border-radius:6px; cursor:pointer; display:flex; align-items:center;">
                        🗑️
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" style="padding:3rem; text-align:center; color:var(--color-text-muted);">Aucun abonné enregistré.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div style="margin-top:1.5rem;">
          {{ $subscribers->links() }}
        </div>
      </div>

      {{-- Add Subscriber Form --}}
      <div class="card" style="padding: 1.5rem; background:#fff; border: 1px solid #ede4db; border-radius: 12px;">
        <h3 style="margin-top:0; font-size:15px; font-weight:600; color:var(--color-dark); margin-bottom:1.5rem;">Abonner manuellement</h3>
        <form method="POST" action="{{ route('admin.newsletter.subscribers.store') }}">
          @csrf
          <div class="admin-form-group" style="margin-bottom:1rem; display:flex; flex-direction:column; gap:4px;">
            <label class="admin-label" style="font-size:12px; font-weight:600; color:var(--color-dark);">Adresse e-mail *</label>
            <input type="email" name="email" class="admin-input" placeholder="client@email.com" required style="padding:8px 12px; border: 1px solid #d1d5db; border-radius:8px; outline:none; font-size:14px;">
          </div>
          <div class="admin-form-group" style="margin-bottom:1.5rem; display:flex; flex-direction:column; gap:4px;">
            <label class="admin-label" style="font-size:12px; font-weight:600; color:var(--color-dark);">Nom (optionnel)</label>
            <input type="text" name="name" class="admin-input" placeholder="Jean Dupont" style="padding:8px 12px; border: 1px solid #d1d5db; border-radius:8px; outline:none; font-size:14px;">
          </div>
          <button type="submit" class="btn-primary" style="width:100%; padding:10px; background:var(--color-dark); color:#fff; border:none; border-radius:8px; font-weight:500; font-size:14px; cursor:pointer;">
            ➕ Ajouter à la liste
          </button>
        </form>
      </div>
      
    </div>
  </div>

  {{-- Section Tab 2: Campaign history --}}
  <div id="tab-campaigns" style="display:none;">
    <div class="card" style="padding:0; overflow:hidden; border: 1px solid #ede4db; border-radius:12px; background:#fff;">
      <table class="admin-table" style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
        <thead>
          <tr style="background:#fcf9f5; border-bottom:1px solid #ede4db;">
            <th style="padding:14px 18px; font-weight:600;">Campagne (Objet)</th>
            <th style="padding:14px 18px; font-weight:600;">Envoyée le</th>
            <th style="padding:14px 18px; font-weight:600; text-align:right;">Date de Création</th>
          </tr>
        </thead>
        <tbody>
          @forelse($campaigns as $camp)
          <tr style="border-bottom:1px solid #ede4db;">
            <td style="padding:14px 18px; font-weight:500; font-size:14px; max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
              {{ $camp->subject }}
            </td>
            <td style="padding:14px 18px; color:var(--color-success); font-weight:500;">
              ✔️ {{ $camp->sent_at ? $camp->sent_at->format('d/m/Y H:i') : 'En attente' }}
            </td>
            <td style="padding:14px 18px; text-align:right; color:var(--color-text-muted); font-size:12px;">
              {{ $camp->created_at->format('d/m/Y H:i') }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" style="padding:3rem; text-align:center; color:var(--color-text-muted);">Aucune newsletter envoyée dans l'historique.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div style="margin-top:1.5rem;">
      {{ $campaigns->links() }}
    </div>
  </div>

</div>

<script>
function switchTab(tabName) {
  var subscribersSection = document.getElementById('tab-subscribers');
  var campaignsSection = document.getElementById('tab-campaigns');
  var subscribersBtn = document.getElementById('tab-btn-subscribers');
  var campaignsBtn = document.getElementById('tab-btn-campaigns');

  if (tabName === 'subscribers') {
    subscribersSection.style.display = 'block';
    campaignsSection.style.display = 'none';
    
    subscribersBtn.style.color = 'var(--color-warm)';
    subscribersBtn.style.borderBottom = '3px solid var(--color-warm)';
    subscribersBtn.style.fontWeight = '600';

    campaignsBtn.style.color = 'var(--color-text-muted)';
    campaignsBtn.style.borderBottom = '3px solid transparent';
    campaignsBtn.style.fontWeight = '500';
  } else {
    subscribersSection.style.display = 'none';
    campaignsSection.style.display = 'block';

    campaignsBtn.style.color = 'var(--color-warm)';
    campaignsBtn.style.borderBottom = '3px solid var(--color-warm)';
    campaignsBtn.style.fontWeight = '600';

    subscribersBtn.style.color = 'var(--color-text-muted)';
    subscribersBtn.style.borderBottom = '3px solid transparent';
    subscribersBtn.style.fontWeight = '500';
  }
}

// Preserve active tab on pagination or search reload
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('campaigns_page')) {
  switchTab('campaigns');
}
</script>
@endsection
