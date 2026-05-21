/* ============================================
   KATUISCIA — Admin CRUD
   Fonctions partagées pour la gestion des
   produits, catégories, etc.
   ============================================ */


// =============== CATÉGORIES ===============

async function loadCategories(containerSelector) {
  try {
    const result = await KatuisciaAuth.apiFetch('/categories');
    if (!result.success) throw new Error(result.message);
    
    const container = document.querySelector(containerSelector);
    if (!container) return result.data;

    // Mettre à jour le compteur
    document.querySelectorAll('.category-count').forEach(el => {
      el.textContent = result.data.length + ' catégorie' + (result.data.length > 1 ? 's' : '');
    });

    container.innerHTML = '';
    result.data.forEach(cat => {
      container.innerHTML += createCategoryCard(cat);
    });
    return result.data;
  } catch (e) {
    console.error('Erreur chargement catégories:', e);
    return [];
  }
}

function createCategoryCard(cat) {
  return `
    <div class="card" style="position:relative; overflow:hidden;" data-id="${cat.id}">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:var(--space-md);">
        <div>
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:4px;">
            ${cat.name}
          </h3>
          <span style="font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:var(--tracking-wider);">
            ${cat.slug} • ${cat.products_count || 0} produit${cat.products_count > 1 ? 's' : ''}
          </span>
        </div>
        <div style="display:flex; gap:8px;">
          <button class="action-btn" title="Modifier" onclick="openEditCategory(${cat.id})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="action-btn action-btn--danger" title="Supprimer" onclick="deleteCategory(${cat.id})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </button>
        </div>
      </div>
      <p style="font-size:var(--text-sm); color:var(--color-text-light); line-height:1.6; margin-bottom:var(--space-md);">
        ${cat.description || 'Aucune description.'}
      </p>
    </div>
  `;
}

async function createCategory(data) {
  const result = await KatuisciaAuth.apiFetch('/categories', {
    method: 'POST',
    body: JSON.stringify(data),
  });
  return result;
}

async function updateCategory(id, data) {
  const result = await KatuisciaAuth.apiFetch('/categories/' + id, {
    method: 'PUT',
    body: JSON.stringify(data),
  });
  return result;
}

async function deleteCategory(id) {
  if (!confirm('Supprimer cette catégorie ? Les produits ne seront pas supprimés mais ne seront plus associés.')) return;
  const result = await KatuisciaAuth.apiFetch('/categories/' + id, {
    method: 'DELETE',
  });
  if (result.success) {
    document.querySelector(`.card[data-id="${id}"]`)?.remove();
    updateCategoryCounter();
  } else {
    alert(result.message || 'Erreur lors de la suppression.');
  }
}

function updateCategoryCounter() {
  const cards = document.querySelectorAll('.card[data-id]').length;
  document.querySelectorAll('.category-count').forEach(el => {
    el.textContent = cards + ' catégorie' + (cards > 1 ? 's' : '');
  });
}

// =============== PRODUITS ===============

async function loadProducts(containerSelector) {
  try {
    const result = await KatuisciaAuth.apiFetch('/products?per_page=100');
    if (!result.success) throw new Error(result.message);

    const tbody = document.querySelector(containerSelector);
    if (!tbody) return result.data;

    const products = result.data || [];
    const meta = result.meta || {};

    // Mettre à jour les stats
    document.querySelectorAll('.total-products').forEach(el => { el.textContent = meta.total || products.length; });
    const inStock = products.filter(p => p.stock > 10).length;
    document.querySelectorAll('.in-stock-count').forEach(el => { el.textContent = inStock; });
    document.querySelectorAll('.in-stock-pct').forEach(el => { el.textContent = meta.total ? Math.round(inStock / meta.total * 100) + '%' : '0%'; });
    const lowStock = products.filter(p => p.stock > 0 && p.stock <= 10).length;
    document.querySelectorAll('.low-stock-count').forEach(el => { el.textContent = lowStock; });

    tbody.innerHTML = '';
    products.forEach(p => {
      tbody.innerHTML += createProductRow(p);
    });
    return products;
  } catch (e) {
    console.error('Erreur chargement produits:', e);
    return [];
  }
}

function createProductRow(p) {
  const stockClass = p.stock <= 0 ? 'status-badge--danger' : (p.stock <= 10 ? 'status-badge--warning' : 'status-badge--success');
  const stockLabel = p.stock <= 0 ? 'Rupture' : (p.stock <= 10 ? 'Stock faible' : 'En stock');
  const categories = p.categories ? p.categories.map(c => c.name).join(', ') : '';
  const img = p.image_primary || 'assets/images/product-1a.png';

  return `
    <tr data-id="${p.id}">
      <td><input type="checkbox"></td>
      <td>
        <div style="display:flex; align-items:center; gap:12px;">
          <img src="${img}" style="width:44px; height:44px; border-radius:8px; object-fit:cover;" onerror="this.src='assets/images/K%20ICONE.png'">
          <div>
            <strong>${p.name}</strong><br>
            <small style="color:var(--color-text-muted);">${p.description || ''} ${p.size ? '• ' + p.size : ''}</small>
          </div>
        </div>
      </td>
      <td style="color:var(--color-text-muted);">${p.sku || '—'}</td>
      <td>${categories || '—'}</td>
      <td><strong>${p.display_price || p.price + ' €'}</strong></td>
      <td style="${p.stock <= 10 ? 'color:var(--color-error);font-weight:600;' : ''}">${p.stock}</td>
      <td><span class="status-badge ${stockClass}">${stockLabel}</span></td>
      <td>
        <div style="display:flex; gap:6px;">
          <button class="action-btn" title="Modifier">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="action-btn action-btn--danger" title="Supprimer" onclick="deleteProduct(${p.id})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </button>
        </div>
      </td>
    </tr>
  `;
}

async function createProduct(data) {
  const result = await KatuisciaAuth.apiFetch('/products', {
    method: 'POST',
    body: JSON.stringify(data),
  });
  return result;
}

async function deleteProduct(id) {
  if (!confirm('Supprimer ce produit définitivement ?')) return;
  const result = await KatuisciaAuth.apiFetch('/products/' + id, {
    method: 'DELETE',
  });
  if (result.success) {
    document.querySelector(`tr[data-id="${id}"]`)?.remove();
  } else {
    alert(result.message || 'Erreur lors de la suppression.');
  }
}

// =============== FILTRE CATÉGORIES ===============

async function populateCategoryFilter(selectSelector) {
  const result = await KatuisciaAuth.apiFetch('/categories');
  if (!result.success) return;
  const selects = document.querySelectorAll(selectSelector);
  selects.forEach(select => {
    // Garder la première option
    const firstOption = select.options[0];
    select.innerHTML = '';
    if (firstOption) select.appendChild(firstOption);
    result.data.forEach(cat => {
      const opt = document.createElement('option');
      opt.value = cat.id;
      opt.textContent = cat.name;
      select.appendChild(opt);
    });
  });
}

// =============== CATEGORY MODAL ===============

function openEditCategory(id) {
  // Surchargé dans la page
  console.log('openEditCategory', id);
}
