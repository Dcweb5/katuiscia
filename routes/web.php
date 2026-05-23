<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

// ===== PAGES PUBLIQUES =====
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/boutique', [PageController::class, 'boutique'])->name('boutique');
Route::get('/produit/{slug?}', [PageController::class, 'produit'])->name('produit');
Route::get('/panier', [App\Http\Controllers\CartController::class, 'index'])->name('panier');
Route::post('/panier/ajouter', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::put('/panier/{item}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{item}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/panier/count', [App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
Route::post('/panier/coupon', [App\Http\Controllers\CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::get('/paiement', [App\Http\Controllers\CheckoutController::class, 'index'])->name('paiement');
Route::post('/paiement', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/maison', [PageController::class, 'maison'])->name('maison');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogShow'])->name('blog.show');
Route::get('/diagnostic', [PageController::class, 'diagnostic'])->name('diagnostic');
Route::get('/formation', [PageController::class, 'formation'])->name('formation');
Route::get('/grossiste', [PageController::class, 'grossiste'])->name('grossiste');
Route::get('/avantage-en-ligne', [PageController::class, 'avantageEnLigne'])->name('avantage-en-ligne');
Route::get('/valeurs-beaute-responsable', [PageController::class, 'valeursBeauteResponsable'])->name('valeurs-beaute-responsable');
Route::get('/valeurs-emballage-durable', [PageController::class, 'valeursEmballageDurable'])->name('valeurs-emballage-durable');
Route::get('/valeurs-personne-biodiversite', [PageController::class, 'valeursPersonneBiodiversite'])->name('valeurs-personne-biodiversite');
Route::get('/mentions-legales', [PageController::class, 'mentionsLegales'])->name('mentions-legales');
Route::get('/politique-confidentialite', [PageController::class, 'politiqueConfidentialite'])->name('politique-confidentialite');
Route::get('/politique-expedition', [PageController::class, 'politiqueExpedition'])->name('politique-expedition');
Route::get('/politique-remboursement', [PageController::class, 'politiqueRemboursement'])->name('politique-remboursement');

// ===== ACTIONS PUBLIQUES =====
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handle']);
Route::get('/facture/{token}', [App\Http\Controllers\InvoiceController::class, 'downloadByToken'])->name('invoice.public');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::post('/rendez-vous', [App\Http\Controllers\AppointmentController::class, 'store'])->name('appointment.store');
Route::post('/api/chat', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/collection/{slug}', [App\Http\Controllers\PageController::class, 'collectionShow'])->name('collection.show');

// ===== SUIVI DE COMMANDE (PUBLIC) =====
Route::get('/suivi-commande', [App\Http\Controllers\TrackOrderController::class, 'index'])->name('track');
Route::post('/suivi-commande', [App\Http\Controllers\TrackOrderController::class, 'track'])->name('track.find');

// ===== AUTH (GUEST — non connecté) =====
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [WebAuthController::class, 'login']);
    Route::get('/inscription', [WebAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/inscription', [WebAuthController::class, 'register']);
    Route::get('/mot-de-passe-oublie', [WebAuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [WebAuthController::class, 'forgotPassword'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reinitialisation/{token}', [WebAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reinitialisation', [WebAuthController::class, 'resetPassword'])->name('password.update');
});

// ===== AUTH (PROTÉGÉ — connecté) =====
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [WebAuthController::class, 'logout'])->name('logout');
    Route::get('/compte', [App\Http\Controllers\UserController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/compte/commandes', [App\Http\Controllers\UserController::class, 'orders'])->name('account.orders');
    Route::get('/compte/recompenses', [App\Http\Controllers\UserController::class, 'rewards'])->name('account.rewards');
    Route::get('/compte/avis', [App\Http\Controllers\UserController::class, 'reviews'])->name('account.reviews');
    Route::get('/compte/retours', [App\Http\Controllers\UserController::class, 'returns'])->name('account.returns');

    // Avis
    Route::post('/produits/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/compte/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/compte/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Facture
    Route::get('/compte/facture/{invoice}', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
});

// ===== ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/produits', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/produits/creer', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/produits', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/produits/{product}/editer', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/produits/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/produits/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/produits/{product}/images', [App\Http\Controllers\Admin\ProductController::class, 'uploadImages'])->name('products.images.upload');
    Route::put('/produits/{product}/images/{image}/primary', [App\Http\Controllers\Admin\ProductController::class, 'setPrimary'])->name('products.images.setPrimary');
    Route::post('/produits/{product}/images/reorder', [App\Http\Controllers\Admin\ProductController::class, 'reorder'])->name('products.images.reorder');
    Route::put('/produits/{product}/images/{image}/up', [App\Http\Controllers\Admin\ProductController::class, 'moveUp'])->name('products.images.moveUp');
    Route::put('/produits/{product}/images/{image}/down', [App\Http\Controllers\Admin\ProductController::class, 'moveDown'])->name('products.images.moveDown');
    Route::delete('/produits/{product}/images/{image}', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');

    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);

    // Utilisateurs
    Route::get('/utilisateurs', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('users.show');
    Route::get('/utilisateurs/{user}/editer', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/utilisateurs/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::put('/utilisateurs/{user}/toggle-active', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
    Route::put('/utilisateurs/{user}/toggle-admin', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/utilisateurs/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');

    // Coupons
    Route::get('/coupons/validate', [App\Http\Controllers\Admin\CouponController::class, 'validate'])->name('coupons.validate');
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class)->except(['show']);

    // Avis
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::put('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Contact
    Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::put('/contacts/{message}/read', [App\Http\Controllers\Admin\ContactController::class, 'markRead'])->name('contacts.read');
    Route::delete('/contacts/{message}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/bulk-delete', [App\Http\Controllers\Admin\ContactController::class, 'bulkDestroy'])->name('contacts.bulk-destroy');
    Route::post('/contacts/{message}/reply', [App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contacts.reply');

    // Commandes admin
    Route::get('/commandes', [App\Http\Controllers\Admin\OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/commandes/{order}', [App\Http\Controllers\Admin\OrderManagementController::class, 'show'])->name('orders.show');
    Route::put('/commandes/{order}/status', [App\Http\Controllers\Admin\OrderManagementController::class, 'updateStatus'])->name('orders.update-status');

    // Chatbot
    Route::get('/chat', [App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
    Route::put('/chat/{message}/read', [App\Http\Controllers\Admin\ChatController::class, 'markRead'])->name('chat.read');
    // Blog
    Route::get('/blog', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blog.index');
    Route::post('/blog', [App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blog.store');
    Route::put('/blog/{post}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blog.destroy');
    Route::post('/blog/upload-image', [App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])->name('blog.upload-image');
    // Rendez-vous
    Route::get('/rendezvous', [App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/rendezvous/{appointment}', [App\Http\Controllers\Admin\AppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/rendezvous/{appointment}', [App\Http\Controllers\Admin\AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/rendezvous/{appointment}', [App\Http\Controllers\Admin\AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::post('/rendezvous/bulk-delete', [App\Http\Controllers\Admin\AppointmentController::class, 'bulkDestroy'])->name('appointments.bulk-destroy');
    Route::get('/funnels', fn() => view('admin.funnels.index'))->name('funnels');
    Route::get('/finances', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finances');
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    // Collections
    Route::get('/collections', [App\Http\Controllers\Admin\CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [App\Http\Controllers\Admin\CollectionController::class, 'store'])->name('collections.store');
    Route::put('/collections/{collection}', [App\Http\Controllers\Admin\CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{collection}', [App\Http\Controllers\Admin\CollectionController::class, 'destroy'])->name('collections.destroy');
    Route::put('/collections/{collection}/toggle', [App\Http\Controllers\Admin\CollectionController::class, 'toggle'])->name('collections.toggle');
    Route::get('/sections', [App\Http\Controllers\Admin\SectionController::class, 'index'])->name('sections.index');
    Route::post('/sections', [App\Http\Controllers\Admin\SectionController::class, 'store'])->name('sections.store');
    Route::get('/recompenses', fn() => view('admin.rewards.index'))->name('rewards');
});
