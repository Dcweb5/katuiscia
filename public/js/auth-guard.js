/* ============================================
   KATUISCIA — Auth Guard
   Protège les pages qui nécessitent une connexion
   Met window.katuisciaUser à disposition
   ============================================ */

(function() {
  var token = localStorage.getItem('katuiscia_token');
  var currentPath = window.location.pathname;

  // Si pas de token → rediriger vers connexion
  if (!token) {
    window.location.href = '/connexion.html?redirect=' + encodeURIComponent(currentPath);
    return;
  }

  // Vérifier que le token est toujours valide
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
