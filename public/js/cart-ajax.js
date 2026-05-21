/* ============================================
   KATUISCIA — AJAX Panier (add, update, remove)
   ============================================ */

(function() {
  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  async function apiFetch(url, options) {
    options = options || {};
    options.headers = Object.assign({ 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, options.headers || {});
    var res = await fetch(url, options);
    return await res.json();
  }

  // Badge panier
  async function refreshBadge() {
    try {
      var data = await apiFetch('/panier/count');
      var badge = document.getElementById('cart-badge');
      if (badge) {
        badge.textContent = data.count || 0;
        badge.style.display = data.count > 0 ? '' : 'none';
      }
    } catch(e) {}
  }

  // Toast
  function toast(msg) {
    var t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:var(--color-dark);color:white;padding:10px 24px;border-radius:var(--radius-full);font-size:14px;z-index:9999;pointer-events:none;';
    document.body.appendChild(t);
    setTimeout(function(){ t.style.opacity='0'; t.style.transition='opacity 0.3s'; }, 1500);
    setTimeout(function(){ t.remove(); }, 2000);
  }

  // Ajouter au panier
  async function addToCart(productId, quantity) {
    var data = await apiFetch('/panier/ajouter', {
      method: 'POST', headers: {'Content-Type':'application/json'},
      body: JSON.stringify({product_id: productId, quantity: quantity || 1})
    });
    if (data.success) { refreshBadge(); toast('Ajouté ✓'); }
  }

  // Mettre à jour quantité (AJAX)
  async function updateQty(itemId, newQty) {
    var data = await apiFetch('/panier/' + itemId, {
      method: 'PUT', headers: {'Content-Type':'application/json'},
      body: JSON.stringify({quantity: newQty})
    });
    if (data.success) {
      // Mise à jour du DOM
      var row = document.querySelector('[data-item-id="' + itemId + '"]');
      if (row) {
        var subtotalEl = row.querySelector('.item-subtotal');
        if (subtotalEl) subtotalEl.textContent = data.item_subtotal;
      }
      updateTotalDisplay();
      refreshBadge();
    }
  }

  // Supprimer du panier (AJAX)
  async function removeItem(itemId) {
    var data = await apiFetch('/panier/' + itemId, { method: 'DELETE' });
    if (data.success) {
      var row = document.querySelector('[data-item-id="' + itemId + '"]');
      if (row) row.remove();
      updateTotalDisplay();
      refreshBadge();
      // Vérifier si panier vide
      if (!document.querySelector('[data-item-id]')) location.reload();
    }
  }

  // Recalculer et afficher le total
  function updateTotalDisplay() {
    apiFetch('/panier/count').then(function(data) {
      var subEl = document.getElementById('cart-subtotal-display');
      if (subEl) subEl.textContent = data.total;
      var totalEl = document.getElementById('cart-total-display');
      if (!totalEl) return;
      var rawTotal = (parseFloat(data.total) || 0);
      var disInput = document.getElementById('coupon-discount-input');
      var discount = (disInput && disInput.value) ? parseFloat(disInput.value) : 0;
      var final = Math.max(0, rawTotal - discount);
      totalEl.textContent = final.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' €';
      var countEl = document.getElementById('cart-count-display');
      if (countEl) countEl.textContent = data.count + ' article(s)';
    });
  }

  // Appliquer un coupon (AJAX)
  async function applyCoupon(code) {
    var data = await apiFetch('/panier/coupon', {
      method: 'POST', headers: {'Content-Type':'application/json'},
      body: JSON.stringify({code: code})
    });
    var msg = document.getElementById('coupon-message');
    if (msg) {
      msg.style.display = 'block';
      if (data.valid) {
        msg.style.color = 'var(--color-success)'; msg.textContent = '✅ ' + data.message;
        var discountEl = document.getElementById('cart-discount');
        if (discountEl) { discountEl.style.display = ''; discountEl.textContent = 'Réduction : -' + data.discount; }
        var disInput = document.getElementById('coupon-discount-input');
        if (disInput) disInput.value = data.discount_raw || 0;
        var codeInput = document.getElementById('coupon-code-input');
        if (codeInput) codeInput.value = code;
      } else {
        msg.style.color = 'var(--color-error)'; msg.textContent = data.message;
      }
    }
    updateTotalDisplay();
  }

  // Clics sur les boutons panier
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.cart-add-btn');
    if (btn) {
      e.preventDefault(); e.stopPropagation();
      var pid = parseInt(btn.dataset.productId);
      var qty = parseInt(btn.dataset.quantity) || 1;
      addToCart(pid, qty);
      return;
    }

    // ± quantité dans le panier
    var qtyBtn = e.target.closest('.qty-btn');
    if (qtyBtn) {
      e.preventDefault(); e.stopPropagation();
      var delta = parseInt(qtyBtn.dataset.delta);
      var row = qtyBtn.closest('[data-item-id]');
      if (!row) return;
      var itemId = parseInt(row.dataset.itemId);
      var input = row.querySelector('.qty-input');
      var newQty = (parseInt(input.value) || 1) + delta;
      if (newQty < 1 || newQty > 10) return;
      input.value = newQty;
      updateQty(itemId, newQty);
      return;
    }

    // Supprimer du panier
    var delBtn = e.target.closest('.cart-remove-btn');
    if (delBtn) {
      e.preventDefault(); e.stopPropagation();
      var row = delBtn.closest('[data-item-id]');
      if (!row) return;
      if (!confirm('Retirer ce produit ?')) return;
      var itemId = parseInt(row.dataset.itemId);
      removeItem(itemId);
      return;
    }

    // Appliquer coupon (panier + checkout)
    var couponBtn = e.target.closest('#apply-coupon');
    if (couponBtn) {
      e.preventDefault();
      var codeInput = document.getElementById('coupon-code');
      var code = codeInput ? codeInput.value.trim() : '';
      if (code) applyCoupon(code);
      return;
    }
  });

  refreshBadge();
})();
