<div class="date-filter" style="position:relative;">
  <button type="button" class="filter-pill {{ request()->anyFilled(['date_from','date_to']) ? 'active' : '' }}" onclick="var d=this.parentElement.querySelector('.date-dropdown');d.classList.toggle('open');event.stopPropagation();" style="display:flex;align-items:center;gap:6px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    @if(request()->anyFilled(['date_from','date_to']))
      {{ request('date_from', '') }} → {{ request('date_to', '') }}
    @else
      Date
    @endif
  </button>
  <div class="date-dropdown" style="display:none;position:fixed;top:auto;right:auto;margin-top:6px;background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:1rem;box-shadow:0 8px 30px rgba(0,0,0,0.15);z-index:1000;max-width:calc(100vw - 2rem);">
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;">
      <div>
        <label style="font-size:11px;color:var(--color-text-muted);display:block;margin-bottom:4px;">Du</label>
        <input type="date" name="date_from" form="{{ $formId ?? 'filter-form' }}" value="{{ request('date_from') }}" class="admin-input" style="padding:6px 10px;font-size:13px;width:140px;max-width:100%;">
      </div>
      <span style="color:var(--color-text-muted);margin-top:18px;">→</span>
      <div>
        <label style="font-size:11px;color:var(--color-text-muted);display:block;margin-bottom:4px;">Au</label>
        <input type="date" name="date_to" form="{{ $formId ?? 'filter-form' }}" value="{{ request('date_to') }}" class="admin-input" style="padding:6px 10px;font-size:13px;width:140px;max-width:100%;">
      </div>
      <button type="submit" form="{{ $formId ?? 'filter-form' }}" class="action-btn" style="width:auto;height:auto;padding:6px 12px;margin-top:18px;font-size:12px;">OK</button>
      @if(request()->anyFilled(['date_from','date_to']))
      <a href="?{{ http_build_query(array_merge(request()->except(['date_from','date_to','page']))) }}" class="action-btn" style="width:auto;height:auto;padding:6px 10px;margin-top:18px;font-size:12px;text-decoration:none;">✕</a>
      @endif
    </div>
  </div>
</div>

<script>
(function(){
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.date-filter')) {
      document.querySelectorAll('.date-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
    }
  });
  // Position the dropdown on open
  document.querySelectorAll('.date-filter').forEach(function(f){
    f.querySelector('.filter-pill').addEventListener('click', function(){
      var dd = f.querySelector('.date-dropdown');
      if (!dd.classList.contains('open')) return;
      // Position under the button
      var rect = this.getBoundingClientRect();
      dd.style.top = (rect.bottom + 4) + 'px';
      dd.style.left = Math.min(rect.left, window.innerWidth - dd.offsetWidth - 16) + 'px';
    });
  });
})();
</script>
