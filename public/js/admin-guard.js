/* ============================================
   KATUISCIA — Admin Guard + CRUD
   Protège les pages admin et gère le CRUD
   ============================================ */

(function() {
  var token = localStorage.getItem('katuiscia_token');
  var currentPath = window.location.pathname;

  // Si pas de token → connexion
  if (!token) {
    window.location.href = '/connexion.html?redirect=' + encodeURIComponent(currentPath);
    return;
  }

  // Vérifier le token ET le rôle admin
  fetch('http://localhost:8000/api/user', {
    headers: { 'Authorization': 'Bearer ' + token }
  })
  .then(function(res) {
    if (!res.ok) throw new Error('Token invalide');
    return res.json();
  })
  .then(function(data) {
    if (data.success && data.data) {
      localStorage.setItem('katuiscia_user', JSON.stringify(data.data));
      window.katuisciaUser = data.data;

      // Vérifier si l'utilisateur est admin
      if (!data.data.is_admin) {
        window.location.href = '/compte.html';
        return;
      }

      // Stocker l'utilisateur admin pour usage dans la page
      window.adminData = data.data;

      // Déclencher DOMContentLoaded si déjà prêt
      if (document.readyState !== 'loading') {
        document.dispatchEvent(new CustomEvent('admin:ready', { detail: data.data }));
      }
      // Sinon, DOMContentLoaded le déclenchera via l'écouteur déjà en place
      // L'écouteur DOMContentLoaded est dans la page, il vérifiera si adminData existe
    } else {
      throw new Error('Réponse invalide');
    }
  })
  .catch(function() {
    localStorage.removeItem('katuiscia_token');
    localStorage.removeItem('katuiscia_user');
    window.location.href = '/connexion.html?redirect=' + encodeURIComponent(currentPath);
  });
})();
