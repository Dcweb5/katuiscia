/* ============================================
   KATUISCIA — Boutique JavaScript
   Filtrage, tri, gestion des produits
   ============================================ */

// Données produits : d'abord depuis les données injectées par Blade (PRODUCTS_FROM_API),
// puis depuis l'API (si disponible), sinon tableau vide
const PRODUCTS = window.PRODUCTS_FROM_API || [];

document.addEventListener('DOMContentLoaded', () => {
  initBoutique();
});

function initBoutique() {
  const grid = document.querySelector('.boutique__products');
  if (!grid) return;

  let activeFilters = {
    categories: [],
    needs: [],
    priceMin: 0,
    priceMax: Infinity
  };

  renderProducts(PRODUCTS, grid);
  initFilters(activeFilters, grid);
  initSort(grid);
  initMobileFilterToggle();
}

function renderProducts(products, grid) {
  // Keep load-more button
  const loadMoreWrapper = grid.querySelector('.boutique__load-more');
  
  // Clear existing cards
  grid.querySelectorAll('.product-card').forEach(c => c.remove());

  if (products.length === 0) {
    const empty = document.createElement('div');
    empty.className = 'boutique__empty';
    empty.style.cssText = 'grid-column: 1/-1; text-align:center; padding: 4rem 1rem;';
    empty.innerHTML = `
      <h3 style="font-family: var(--font-heading); margin-bottom: 0.5rem;">Aucun produit trouvé</h3>
      <p style="color: var(--color-text-muted);">Essayez de modifier vos filtres.</p>
    `;
    if (loadMoreWrapper) grid.insertBefore(empty, loadMoreWrapper);
    else grid.appendChild(empty);
    return;
  }

  products.forEach((product, index) => {
    const card = createProductCard(product, index);
    if (loadMoreWrapper) grid.insertBefore(card, loadMoreWrapper);
    else grid.appendChild(card);
  });
}

function createProductCard(product, index) {
  const card = document.createElement('article');
  card.className = 'product-card reveal';
  card.style.transitionDelay = `${(index % 6) * 80}ms`;
  
  card.innerHTML = `
    <div class="product-card__image-wrapper">
      <a href="produit/${product.slug || product.id}" style="display:block; width:100%; height:100%;">
        <img src="${product.image1}" alt="${product.name}" class="product-card__image product-card__image--primary" loading="lazy" width="400" height="533">
        <img src="${product.image2}" alt="${product.name} - vue alternative" class="product-card__image product-card__image--secondary" loading="lazy" width="400" height="533">
      </a>
      ${product.badge ? `<span class="product-card__badge">${product.badge}</span>` : ''}
      <button class="product-card__quick-view" aria-label="Aperçu rapide de ${product.name}">Aperçu rapide</button>
      <button class="product-card__add-cart cart-add-btn" data-product-id="${product.id}" data-quantity="1" aria-label="Ajouter au panier">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      </button>
    </div>
    <div class="product-card__info">
      <a href="produit/${product.slug || product.id}" style="text-decoration:none; color:inherit; flex-grow:1;">
        <span class="product-card__category">${getCategoryLabel(product.category)}</span>
        <h3 class="product-card__name">${product.name}</h3>
        <p class="product-card__description">${product.description}</p>
        <span class="product-card__price">${product.price} €</span>
      </a>
    </div>
  `;

  // Trigger reveal after a frame
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      card.classList.add('revealed');
    });
  });

  return card;
}

function getCategoryLabel(cat) {
  const labels = {
    'soin-peau': 'Soin de la peau',
    'parfum': 'Parfum',
    'corps': 'Corps',
    'outils': 'Outils de Rituel'
  };
  return labels[cat] || cat;
}

function initFilters(activeFilters, grid) {
  // Category checkboxes
  document.querySelectorAll('[data-filter-category]').forEach(cb => {
    cb.addEventListener('change', () => {
      const val = cb.dataset.filterCategory;
      if (cb.checked) {
        activeFilters.categories.push(val);
      } else {
        activeFilters.categories = activeFilters.categories.filter(c => c !== val);
      }
      applyFilters(activeFilters, grid);
      updateFilterTags(activeFilters);
    });
  });

  // Need checkboxes
  document.querySelectorAll('[data-filter-need]').forEach(cb => {
    cb.addEventListener('change', () => {
      const val = cb.dataset.filterNeed;
      if (cb.checked) {
        activeFilters.needs.push(val);
      } else {
        activeFilters.needs = activeFilters.needs.filter(n => n !== val);
      }
      applyFilters(activeFilters, grid);
      updateFilterTags(activeFilters);
    });
  });

  // Price inputs
  const minInput = document.querySelector('[data-price-min]');
  const maxInput = document.querySelector('[data-price-max]');
  
  if (minInput) {
    minInput.addEventListener('input', debounce(() => {
      activeFilters.priceMin = parseFloat(minInput.value) || 0;
      applyFilters(activeFilters, grid);
    }, 400));
  }
  
  if (maxInput) {
    maxInput.addEventListener('input', debounce(() => {
      activeFilters.priceMax = parseFloat(maxInput.value) || Infinity;
      applyFilters(activeFilters, grid);
    }, 400));
  }
}

function applyFilters(filters, grid) {
  let filtered = [...PRODUCTS];

  if (filters.categories.length > 0) {
    filtered = filtered.filter(p => filters.categories.includes(p.category));
  }

  if (filters.needs.length > 0) {
    filtered = filtered.filter(p => p.need && filters.needs.includes(p.need));
  }

  filtered = filtered.filter(p => p.price >= filters.priceMin && p.price <= filters.priceMax);

  renderProducts(filtered, grid);
}

function updateFilterTags(filters) {
  const container = document.querySelector('.filter-tags');
  if (!container) return;

  container.innerHTML = '';

  filters.categories.forEach(cat => {
    const tag = document.createElement('span');
    tag.className = 'filter-tag';
    tag.innerHTML = `${getCategoryLabel(cat)} <svg class="filter-tag__close" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 6L6 18M6 6l12 12" stroke-width="2" stroke-linecap="round"/></svg>`;
    tag.addEventListener('click', () => {
      filters.categories = filters.categories.filter(c => c !== cat);
      const cb = document.querySelector(`[data-filter-category="${cat}"]`);
      if (cb) cb.checked = false;
      applyFilters(filters, document.querySelector('.boutique__products'));
      updateFilterTags(filters);
    });
    container.appendChild(tag);
  });

  filters.needs.forEach(need => {
    const tag = document.createElement('span');
    tag.className = 'filter-tag';
    const labels = { hydratation: 'Hydratation', eclat: 'Éclat', restauration: 'Restauration' };
    tag.innerHTML = `${labels[need] || need} <svg class="filter-tag__close" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 6L6 18M6 6l12 12" stroke-width="2" stroke-linecap="round"/></svg>`;
    tag.addEventListener('click', () => {
      filters.needs = filters.needs.filter(n => n !== need);
      const cb = document.querySelector(`[data-filter-need="${need}"]`);
      if (cb) cb.checked = false;
      applyFilters(filters, document.querySelector('.boutique__products'));
      updateFilterTags(filters);
    });
    container.appendChild(tag);
  });

  if (filters.categories.length > 0 || filters.needs.length > 0) {
    const clearTag = document.createElement('span');
    clearTag.className = 'filter-tag filter-tag--clear';
    clearTag.textContent = 'Tout effacer';
    clearTag.addEventListener('click', () => {
      filters.categories = [];
      filters.needs = [];
      document.querySelectorAll('[data-filter-category], [data-filter-need]').forEach(cb => cb.checked = false);
      applyFilters(filters, document.querySelector('.boutique__products'));
      updateFilterTags(filters);
    });
    container.appendChild(clearTag);
  }
}

function initSort(grid) {
  const sortSelect = document.querySelector('.sort-dropdown__select');
  if (!sortSelect) return;

  sortSelect.addEventListener('change', () => {
    const cards = Array.from(grid.querySelectorAll('.product-card'));
    // Simple sort by re-rendering
    const currentProducts = getCurrentVisibleProducts();
    
    switch (sortSelect.value) {
      case 'price-asc':
        currentProducts.sort((a, b) => a.price - b.price);
        break;
      case 'price-desc':
        currentProducts.sort((a, b) => b.price - a.price);
        break;
      case 'name':
        currentProducts.sort((a, b) => a.name.localeCompare(b.name));
        break;
      default:
        break;
    }
    
    renderProducts(currentProducts, grid);
  });
}

function getCurrentVisibleProducts() {
  // Get products that match current filters
  return [...PRODUCTS]; // Simplified — in production, track filtered state
}

function initMobileFilterToggle() {
  const toggle = document.querySelector('.filters__mobile-toggle');
  const filters = document.querySelector('.filters');
  
  if (!toggle || !filters) return;

  toggle.addEventListener('click', () => {
    filters.classList.toggle('open');
    toggle.textContent = filters.classList.contains('open') ? '✕ Fermer les filtres' : '☰ Filtres';
  });
}

function debounce(fn, delay) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}
