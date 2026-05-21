<div id="k-toast" style="display:none;position:fixed;top:20px;right:20px;z-index:99999;padding:14px 24px;border-radius:10px;font-size:14px;font-weight:500;box-shadow:0 8px 30px rgba(0,0,0,0.18);max-width:400px;transform:translateX(120%);transition:transform 0.35s cubic-bezier(0.4,0,0.2,1);"></div>
<script>
(function(){
  var t = document.getElementById('k-toast');
  function show(msg, type) {
    t.textContent = msg;
    t.style.background = type === 'error' ? '#c62828' : '#2e7d32';
    t.style.color = '#fff';
    t.style.transform = 'translateX(0)';
    t.style.display = '';
    clearTimeout(t._timer);
    t._timer = setTimeout(function(){ t.style.transform = 'translateX(120%)'; }, 4000);
  }
  window.showToast = show;
  @if(session('success'))
    show(@json(session('success')), 'success');
  @endif
  @if(session('error'))
    show(@json(session('error')), 'error');
  @endif
})();
</script>
