<div class="date-filter" style="position:relative;">
  <button type="button" class="filter-pill {{ request()->anyFilled(['date_from','date_to']) ? 'active' : '' }}" onclick="event.stopPropagation();var d=this.parentElement.querySelector('.date-dropdown');d.style.display='block';document.getElementById('date-backdrop').style.display='block';" style="display:flex;align-items:center;gap:6px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    @if(request()->anyFilled(['date_from','date_to']))
      {{ request('date_from', '') }} → {{ request('date_to', '') }}
    @else
      Date
    @endif
  </button>
  <div class="date-dropdown" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border:1px solid var(--color-border);border-radius:14px;padding:1.5rem 2rem;box-shadow:0 12px 50px rgba(0,0,0,0.18);z-index:10000;text-align:center;">
    <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:center;justify-content:center;">
      <div>
        <label style="font-size:13px;font-weight:600;color:var(--color-dark);display:block;margin-bottom:6px;">Du</label>
        <input type="date" name="date_from" form="{{ $formId ?? 'filter-form' }}" value="{{ request('date_from') }}" class="admin-input" style="padding:8px 14px;font-size:14px;width:160px;">
      </div>
      <span style="color:var(--color-text-muted);margin-top:22px;font-size:18px;">→</span>
      <div>
        <label style="font-size:13px;font-weight:600;color:var(--color-dark);display:block;margin-bottom:6px;">Au</label>
        <input type="date" name="date_to" form="{{ $formId ?? 'filter-form' }}" value="{{ request('date_to') }}" class="admin-input" style="padding:8px 14px;font-size:14px;width:160px;">
      </div>
    </div>
    <div style="display:flex;gap:0.75rem;justify-content:center;margin-top:1.25rem;">
      <button type="submit" form="{{ $formId ?? 'filter-form' }}" class="btn-primary" style="font-size:13px;">✅ Filtrer</button>
      <button type="button" class="action-btn" onclick="closeDatePickers()" style="width:auto;height:auto;padding:8px 14px;font-size:13px;">Fermer</button>
      @if(request()->anyFilled(['date_from','date_to']))
      <a href="?{{ http_build_query(array_merge(request()->except(['date_from','date_to','page']))) }}" class="action-btn" style="width:auto;height:auto;padding:8px 14px;font-size:13px;text-decoration:none;color:var(--color-error);">✕ Effacer</a>
      @endif
    </div>
  </div>
</div>
