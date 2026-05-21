# Collections — Spécification

**Date :** 2026-05-20  
**Auteur :** KEV Dev / opencode  
**Contexte :** Plateforme e-commerce KATUISCIA (Laravel 13, Blade, SQLite)

---

## Objectif

Créer un système de **Collections** : packs groupés de produits vendus à prix bundle, avec gestion admin complète et affichage boutique.

---

## Base de données

### Table `collections`

| Colonne | Type | Contrainte | Description |
|---------|------|-----------|-------------|
| id | bigint | PK, auto | Identifiant |
| name | string(255) | required | Nom de la collection |
| slug | string(255) | unique | URL slug |
| description | text | nullable | Description |
| price | decimal(10,2) | required | Prix du pack |
| original_price | decimal(10,2) | nullable | Prix barré (avant réduction) |
| image | string(255) | nullable | Image principale |
| category_id | foreignId | nullable, FK→categories | Catégorie pour filtrage boutique |
| is_active | boolean | default true | Actif / Brouillon |
| order | integer | default 0 | Ordre d'affichage |
| timestamps | | | created_at, updated_at |

### Table pivot `collection_product`

| Colonne | Type | Contrainte |
|---------|------|-----------|
| id | bigint | PK |
| collection_id | foreignId | FK→collections, cascade |
| product_id | foreignId | FK→products, cascade |
| quantity | integer | default 1 |

---

## Admin : `/admin/collections`

### Page index
- Table listant toutes les collections : image, nom, nb produits, prix, statut, date
- Filtres par statut (actif/brouillon)
- Stats : nb collections actives, nb brouillons, nb produits total
- Bouton "+ Nouvelle Collection"
- Actions par ligne : ✏️ modifier, 🗑 supprimer, toggle actif/inactif

### Création / Modification (modal)
- Nom *
- Description
- Prix *
- Prix barré (original_price)
- Image (upload)
- Catégorie (select des categories existantes)
- Sélection de produits : checkboxes avec nom + image miniature
- Statut : Actif / Brouillon
- Boutons : Annuler | Sauvegarder brouillon | Publier

### Suppression
- Confirmation avant suppression
- Cascade : supprime les entrées pivot

### Slug unique
- Génération auto depuis le nom, incrémentation si doublon

---

## Boutique publique `/boutique`

### Section "Nos Collections" (en haut de la page boutique)
- Cards : image, nom, description tronquée, prix barré → prix pack, bouton "Découvrir"
- Affiche uniquement les collections `is_active = true`
- Trié par `order`

### Filtrage par catégorie
- Les collections ayant `category_id` défini apparaissent quand le filtre de cette catégorie est actif
- Pas mélangées avec les produits : section séparée

### Page détail `/collection/{slug}`
- Hero : image + nom + description
- Liste des produits inclus (image miniature, nom, quantité)
- Prix original barré → prix du pack (en vert/rouge)
- Économie affichée (différence prix original - prix)
- Bouton CTA : "Ajouter le pack au panier" → ajoute tous les produits au panier

---

## Rendez-vous (rappel — déjà implémenté)

- Confirmation par l'admin du statut `confirme` → email automatique à l'utilisateur
- Email envoyé depuis `contact@katuiscia.com`
- Contient : date, horaire, type de RDV, instructions
- Testé et fonctionnel depuis la session précédente

---

## Routes

### Admin (`/admin` préfixe, middleware auth+admin)
| Méthode | URI | Nom | Action |
|---------|-----|-----|--------|
| GET | /collections | admin.collections.index | Liste |
| POST | /collections | admin.collections.store | Créer |
| PUT | /collections/{collection} | admin.collections.update | Modifier |
| DELETE | /collections/{collection} | admin.collections.destroy | Supprimer |
| PUT | /collections/{collection}/toggle | admin.collections.toggle | Activer/Désactiver |

### Publique
| Méthode | URI | Nom | Action |
|---------|-----|-----|--------|
| GET | /collection/{slug} | collection.show | Page détail |

La section "Nos Collections" est intégrée dans la page `/boutique` existante (pas de route séparée).

---

## Fichiers à créer/modifier

| Fichier | Action |
|---------|--------|
| database/migrations/...create_collections_table.php | Créer |
| database/migrations/...create_collection_product_table.php | Créer |
| app/Models/Collection.php | Créer |
| app/Http/Controllers/Admin/CollectionController.php | Créer |
| app/Http/Controllers/CollectionController.php (public) | Créer |
| resources/views/admin/collections/index.blade.php | Réécrire |
| resources/views/pages/collection-show.blade.php | Créer |
| resources/views/pages/boutique.blade.php | Modifier (section collections) |
| resources/views/admin/dashboard.blade.php | Modifier (stats) |
| routes/web.php | Modifier |
