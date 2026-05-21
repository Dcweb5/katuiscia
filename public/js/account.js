/* ============================================
   KATUISCIA — Compte Utilisateur
   Charge les données depuis l'API sur les pages compte
   ============================================ */

(function() {
  var page = window.location.pathname;
  var user = null;

  // Attendre que l'utilisateur soit prêt (auth-guard l'a déjà chargé)
  function init() {
    try { user = JSON.parse(localStorage.getItem('katuiscia_user') || '{}'); } catch(e) {}

    // Admin sidebar: mettre à jour les infos admin
    updateAdminInfo();

    if (page.includes('compte.html') && !page.includes('compte-')) {
      loadDashboard();
    } else if (page.includes('compte-commandes')) {
      loadOrders();
    } else if (page.includes('compte-avis')) {
      loadReviews();
    } else if (page.includes('compte-recompenses')) {
      loadRewards();
    } else if (page.includes('compte-retours')) {
      loadReturns();
    }

    // Mettre à jour le nom/prénom dans la sidebar et header
    updateUserInfo();
  }

  function updateUserInfo() {
    if (!user) return;

    // Sidebar: nom + initiales
    document.querySelectorAll('.sidebar-admin-name').forEach(function(el) {
      el.textContent = user.firstname + ' ' + user.lastname;
    });
    document.querySelectorAll('.sidebar-admin-avatar').forEach(function(el) {
      el.textContent = (user.firstname ? user.firstname.charAt(0) : '') + (user.lastname ? user.lastname.charAt(0) : '');
    });
    document.querySelectorAll('.sidebar-admin-role').forEach(function(el) {
      el.textContent = (user.loyalty_points || 0).toLocaleString('fr-FR') + ' pts';
    });

    // Initiales dans le header du dashboard
    document.querySelectorAll('.header-actions .header-btn + div').forEach(function(el) {
      if (el.style && el.textContent.length <= 3) {
        el.textContent = (user.firstname ? user.firstname.charAt(0) : '') + (user.lastname ? user.lastname.charAt(0) : '');
      }
    });
  }

  function loadDashboard() {
    // Titre "Bonjour, Prénom"
    document.querySelectorAll('.page-title').forEach(function(el) {
      if (el.textContent.includes('Bonjour')) {
        el.textContent = 'Bonjour, ' + (user.firstname || user.name || '');
      }
    });

    // Points fidélité
    document.querySelectorAll('.stat-card .stat-value').forEach(function(el) {
      if (el.closest('.stat-card')) {
        var label = el.closest('.stat-card').querySelector('.stat-title');
        if (label && label.textContent.includes('Points')) {
          el.textContent = (user.loyalty_points || 0).toLocaleString('fr-FR');
        }
      }
    });

    // Charger le nombre de commandes et les dernières commandes
    KatuisciaAuth.apiFetch('/orders').then(function(result) {
      if (!result.success) return;
      var orders = result.data || [];

      // Mettre à jour "Commandes en cours"
      document.querySelectorAll('.stat-card .stat-value').forEach(function(el) {
        var label = el.closest('.stat-card').querySelector('.stat-title');
        if (label && label.textContent.includes('Commandes')) {
          var activeOrders = orders.filter(function(o) { return o.status !== 'delivered' && o.status !== 'cancelled'; });
          el.textContent = activeOrders.length;
        }
      });

      // Remplir la liste des commandes récentes dans le dashboard
      var list = document.querySelector('.admin-list');
      if (list && orders.length > 0) {
        list.innerHTML = '';
        orders.slice(0, 3).forEach(function(order) {
          var li = document.createElement('li');
          li.style.cursor = 'pointer';
          li.onclick = function() { window.location.href = '/compte-commandes.html'; };
          li.innerHTML = '' +
            '<div style="display:flex;align-items:center;gap:12px;flex:1;">' +
              '<div style="width:50px;height:50px;border-radius:8px;overflow:hidden;flex-shrink:0;background:var(--color-gray-medium);display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--color-text-muted);">#' + order.id + '</div>' +
              '<div class="admin-list-content">' +
                '<div class="admin-list-title">Commande #' + order.order_number + '</div>' +
                '<div class="admin-list-subtitle">' + (order.status === 'delivered' ? 'Livré' : order.status === 'shipped' ? 'En transit' : order.status === 'preparing' ? 'En préparation' : 'Confirmée') + '</div>' +
              '</div>' +
            '</div>';
          list.appendChild(li);
        });
      }
    });
  }

  function loadOrders() {
    KatuisciaAuth.apiFetch('/orders').then(function(result) {
      if (!result.success || !result.data) return;
      // La page commandes sera remplie dans une phase ultérieure
      console.log('Commandes chargées:', result.data.length);
    });
  }

  function loadReviews() {
    KatuisciaAuth.apiFetch('/account/reviews').then(function(result) {
      // Phase ultérieure
    });
  }

  function loadRewards() {
    KatuisciaAuth.apiFetch('/loyalty/points').then(function(result) {
      // Phase ultérieure
    });
    KatuisciaAuth.apiFetch('/loyalty/rewards').then(function(result) {
      // Phase ultérieure
    });
  }

  function loadReturns() {
    KatuisciaAuth.apiFetch('/returns').then(function(result) {
      // Phase ultérieure
    });
  }

  function updateAdminInfo() {
    if (!user) return;
    // Admin sidebar: nom + initiales
    var initial = (user.firstname ? user.firstname.charAt(0) : '') + (user.lastname ? user.lastname.charAt(0) : '');
    document.querySelectorAll('.auth-name-initial').forEach(function(el) {
      el.textContent = initial || 'AD';
    });
    document.querySelectorAll('.auth-name-admin').forEach(function(el) {
      el.textContent = user.firstname + ' ' + user.lastname;
    });
  }

  // Attendre que katuisciaUser soit disponible
  if (window.katuisciaUser) {
    user = window.katuisciaUser;
    init();
  } else {
    // Vérifier périodiquement (auth-guard.js met la variable)
    var checkInterval = setInterval(function() {
      if (window.katuisciaUser) {
        clearInterval(checkInterval);
        user = window.katuisciaUser;
        init();
      }
    }, 100);
    // Timeout après 10 secondes
    setTimeout(function() { clearInterval(checkInterval); }, 10000);
  }
})();
