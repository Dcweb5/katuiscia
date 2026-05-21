/* ============================================
   KATUISCIA — Script Auth Central
   API_BASE = http://localhost:8000/api
   ============================================ */

const API_BASE = 'http://localhost:8000/api';

/** Stocker le token + utilisateur dans localStorage */
function setAuth(token, user) {
  localStorage.setItem('katuiscia_token', token);
  localStorage.setItem('katuiscia_user', JSON.stringify(user));
}

/** Supprimer le token et l'utilisateur */
function clearAuth() {
  localStorage.removeItem('katuiscia_token');
  localStorage.removeItem('katuiscia_user');
}

/** Récupérer le token */
function getToken() {
  return localStorage.getItem('katuiscia_token');
}

/** Récupérer l'utilisateur stocké */
function getUser() {
  try {
    const raw = localStorage.getItem('katuiscia_user');
    return raw ? JSON.parse(raw) : null;
  } catch (e) {
    return null;
  }
}

/** Appel API générique avec token automatique */
async function apiFetch(endpoint, options = {}) {
  const token = getToken();
  const headers = { 'Content-Type': 'application/json', ...(options.headers || {}) };
  if (token) {
    headers['Authorization'] = 'Bearer ' + token;
  }

  const response = await fetch(API_BASE + endpoint, {
    ...options,
    headers: headers,
  });

  const data = await response.json();

  // Si 401 non autorisé → déconnecter l'utilisateur
  if (!response.ok && response.status === 401) {
    clearAuth();
    if (!window.location.pathname.includes('connexion.html') && !window.location.pathname.includes('inscription.html')) {
      window.location.href = '/connexion.html?redirect=' + encodeURIComponent(window.location.pathname);
    }
  }

  return data;
}

/** Connexion */
async function login(email, password) {
  const data = await apiFetch('/login', {
    method: 'POST',
    body: JSON.stringify({ email: email, password: password }),
  });
  if (data.success && data.data) {
    setAuth(data.data.token, data.data.user);
  }
  return data;
}

/** Inscription minimale (email + password) */
async function register(email, password, passwordConfirmation) {
  const data = await apiFetch('/register', {
    method: 'POST',
    body: JSON.stringify({
      email: email,
      password: password,
      password_confirmation: passwordConfirmation,
    }),
  });
  if (data.success && data.data) {
    setAuth(data.data.token, data.data.user);
  }
  return data;
}

/** Inscription complète (tous les champs) */
async function registerFull(fields) {
  const data = await apiFetch('/register/full', {
    method: 'POST',
    body: JSON.stringify(fields),
  });
  if (data.success && data.data) {
    setAuth(data.data.token, data.data.user);
  }
  return data;
}

/** Déconnexion */
async function logout() {
  await apiFetch('/logout', { method: 'POST' });
  clearAuth();
  window.location.href = '/';
}

/** Mot de passe oublié */
async function forgotPassword(email) {
  return await apiFetch('/forgot-password', {
    method: 'POST',
    body: JSON.stringify({ email: email }),
  });
}

/** Réinitialisation mot de passe */
async function resetPassword(token, email, password, passwordConfirmation) {
  return await apiFetch('/reset-password', {
    method: 'POST',
    body: JSON.stringify({
      token: token,
      email: email,
      password: password,
      password_confirmation: passwordConfirmation,
    }),
  });
}

/** Mettre à jour le profil */
async function updateProfile(data) {
  const result = await apiFetch('/user/profile', {
    method: 'PUT',
    body: JSON.stringify(data),
  });
  if (result.success && result.data) {
    // Mettre à jour l'user stocké
    const currentUser = getUser();
    if (currentUser) {
      setAuth(getToken(), { ...currentUser, ...result.data });
    }
  }
  return result;
}

/** Vérifier si l'utilisateur est admin */
function isAdmin() {
  const user = getUser();
  return user && user.is_admin === true;
}

/** Mettre à jour le header avec l'état de connexion */
function updateHeaderAuth() {
  const token = getToken();
  const user = getUser();

  // Afficher/cacher les éléments selon connexion
  document.querySelectorAll('.auth-show').forEach(function(el) {
    el.style.display = token ? '' : 'none';
  });
  document.querySelectorAll('.auth-hide').forEach(function(el) {
    el.style.display = token ? 'none' : '';
  });

  // Insérer le prénom/nom
  if (user) {
    document.querySelectorAll('.auth-name').forEach(function(el) {
      el.textContent = user.firstname || user.name || 'Utilisateur';
    });
    document.querySelectorAll('.auth-points').forEach(function(el) {
      el.textContent = (user.loyalty_points || 0).toLocaleString('fr-FR');
    });
  }

  // Gérer le bouton déconnexion
  document.querySelectorAll('.auth-logout').forEach(function(el) {
    el.addEventListener('click', function(e) {
      e.preventDefault();
      logout();
    });
  });
}

// Auto-update header au chargement
document.addEventListener('DOMContentLoaded', updateHeaderAuth);

// Exporter toutes les fonctions
window.KatuisciaAuth = {
  login: login,
  register: register,
  registerFull: registerFull,
  logout: logout,
  forgotPassword: forgotPassword,
  resetPassword: resetPassword,
  updateProfile: updateProfile,
  apiFetch: apiFetch,
  getToken: getToken,
  getUser: getUser,
  isAdmin: isAdmin,
  clearAuth: clearAuth,
  updateHeaderAuth: updateHeaderAuth,
};
