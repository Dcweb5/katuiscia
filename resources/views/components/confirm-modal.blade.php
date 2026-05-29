<div id="confirm-modal" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;">
  <div style="position:fixed;inset:0;background:rgba(0,0,0,0.3);" onclick="closeConfirm()"></div>
  <div style="position:relative;background:#fff;border-radius:14px;padding:1.5rem 2rem;max-width:400px;width:90%;box-shadow:0 12px 50px rgba(0,0,0,0.18);text-align:center;">
    <div style="font-size:2rem;margin-bottom:0.5rem;">⚠️</div>
    <p id="confirm-message" style="font-size:15px;color:var(--color-dark);margin-bottom:1.25rem;line-height:1.5;">Confirmer cette action ?</p>
    <div style="display:flex;gap:0.75rem;justify-content:center;">
      <button class="action-btn" onclick="closeConfirm()" style="width:auto;height:auto;padding:8px 20px;font-size:13px;">Annuler</button>
      <button class="btn-primary" id="confirm-ok" style="font-size:13px;background:var(--color-error);border-color:var(--color-error);">Confirmer</button>
    </div>
  </div>
</div>

<script>
var confirmCallback = null;
function showConfirm(message, callback) {
  document.getElementById('confirm-message').textContent = message;
  document.getElementById('confirm-modal').style.display = 'flex';
  confirmCallback = callback;
}
function closeConfirm() {
  document.getElementById('confirm-modal').style.display = 'none';
  confirmCallback = null;
}
document.getElementById('confirm-ok').addEventListener('click', function() {
  if (confirmCallback) { confirmCallback(); closeConfirm(); }
});
</script>
