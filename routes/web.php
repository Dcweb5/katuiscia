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
Route::get('/recherche', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

Route::get('/sitemap.xml', function() {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    // Main pages
    $urls = [
        '',
        '/boutique',
        '/blog',
        '/maison',
        '/contact',
        '/diagnostic',
        '/valeurs-beaute-responsable',
        '/mentions-legales',
        '/politique-confidentialite',
    ];
    
    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . url($url) . '</loc>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>' . ($url === '' ? '1.0' : '0.8') . '</priority>';
        $xml .= '</url>';
    }
    
    // Products
    try {
        $products = \App\Modules\Product\Models\Product::where('is_active', true)->get();
        foreach ($products as $p) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('produit', $p->slug) . '</loc>';
            $xml .= '<lastmod>' . ($p->updated_at ? $p->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }
    } catch (\Exception $e) {}
    
    // Blog posts
    try {
        $posts = \App\Models\BlogPost::where('status', 'published')->get();
        foreach ($posts as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('blog.show', $post->slug) . '</loc>';
            $xml .= '<lastmod>' . ($post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString()) . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }
    } catch (\Exception $e) {}
    
    $xml .= '</urlset>';
    
    return response($xml, 200)->header('Content-Type', 'text/xml');
})->name('sitemap');

// ===== ACTIONS PUBLIQUES =====
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handle']);
Route::get('/facture/{token}', [App\Http\Controllers\InvoiceController::class, 'downloadByToken'])->name('invoice.public');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::post('/rendez-vous', [App\Http\Controllers\AppointmentController::class, 'store'])->name('appointment.store');
Route::post('/api/chat', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/api/shipping/estimate', [App\Http\Controllers\ShippingController::class, 'estimate'])->name('shipping.estimate');
Route::get('/collection/{slug}', [App\Http\Controllers\PageController::class, 'collectionShow'])->name('collection.show');
Route::get('/collections', [App\Http\Controllers\PageController::class, 'collections'])->name('collections');
Route::post('/diagnostic', [App\Http\Controllers\DiagnosticController::class, 'store'])->name('diagnostic.store');
Route::get('/newsletter/desabonnement/{email}', function($email) {
    \App\Models\NewsletterSubscriber::where('email', urldecode($email))->update(['is_active' => false]);
    return view('pages.unsubscribe-success', ['email' => urldecode($email)]);
})->name('newsletter.unsubscribe');

// ===== QUIZ BEAUTÉ (LANDING PAGE) =====
Route::get('/quiz-beaute', [App\Http\Controllers\LandingController::class, 'quiz'])->name('quiz');
Route::post('/quiz-beaute', [App\Http\Controllers\LandingController::class, 'storeQuiz'])->name('quiz.store');
Route::get('/suivi-commande', [App\Http\Controllers\TrackOrderController::class, 'index'])->name('track');
Route::post('/suivi-commande', [App\Http\Controllers\TrackOrderController::class, 'track'])->name('track.find');

// ===== AUTH (GUEST — non connecté) =====
Route::get('/auth/google', function () {
    $client = new \GuzzleHttp\Client(['verify' => !app()->isLocal()]);
    return \Laravel\Socialite\Facades\Socialite::driver('google')
        ->setHttpClient($client)
        ->redirect();
});
Route::get('/auth/google/callback', function () {
    try {
        $client = new \GuzzleHttp\Client(['verify' => !app()->isLocal()]);
        $socialUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()
            ->setHttpClient($client)
            ->user();
    } catch (\Exception $e) {
        \Log::error('Google auth callback error: ' . $e->getMessage());
        return redirect('/connexion')->with('error', 'Erreur lors de l\'authentification Google : ' . $e->getMessage());
    }

    $user = \App\Models\User::where('email', $socialUser->getEmail())->first();

    if ($user) {
        $updates = [];
        if (!$user->avatar && $socialUser->getAvatar()) {
            $updates['avatar'] = $socialUser->getAvatar();
        }
        if (!$user->email_verified_at) {
            $updates['email_verified_at'] = now();
        }
        if (!empty($updates)) {
            $user->update($updates);
        }
    } else {
        $nameParts = explode(' ', $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur', 2);
        $user = \App\Models\User::create([
            'firstname' => $nameParts[0] ?? null,
            'lastname'  => $nameParts[1] ?? null,
            'name'      => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur',
            'email'     => $socialUser->getEmail(),
            'password'  => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
            'avatar'    => $socialUser->getAvatar(),
            'loyalty_points' => 100,
            'email_verified_at' => now(),
        ]);
    }

    auth()->login($user, true);
    session()->save();
    return redirect($user->is_admin ? '/admin' : '/compte');
});
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [WebAuthController::class, 'login']);
    Route::get('/inscription', [WebAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/inscription', [WebAuthController::class, 'register']);
    Route::get('/mot-de-passe-oublie', [WebAuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [WebAuthController::class, 'forgotPassword'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reinitialisation/{token}', [WebAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reinitialisation', [WebAuthController::class, 'resetPassword'])->name('password.update');
    
    // Réinitialisation de mot de passe par Code
    Route::get('/mot-de-passe-oublie/code', [WebAuthController::class, 'showForgotCodeForm'])->name('password.request-code');
    Route::post('/mot-de-passe-oublie/code', [WebAuthController::class, 'verifyForgotCode'])->name('password.verify-code');
    Route::get('/reinitialisation-mot-de-passe', [WebAuthController::class, 'showResetPasswordCodeForm'])->name('password.reset-code');
    Route::post('/reinitialisation-mot-de-passe', [WebAuthController::class, 'resetPasswordCode'])->name('password.update-code');
});

// ===== AUTH & VERIFIED (PROTÉGÉ — connecté & vérifié) =====
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/compte', [App\Http\Controllers\UserController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/compte/commandes', [App\Http\Controllers\UserController::class, 'orders'])->name('account.orders');
    Route::get('/compte/recompenses', [App\Http\Controllers\UserController::class, 'rewards'])->name('account.rewards');
    Route::post('/compte/recompenses/echanger', [App\Http\Controllers\UserController::class, 'exchangeReward'])->name('account.rewards.exchange');
    Route::get('/compte/avis', [App\Http\Controllers\UserController::class, 'reviews'])->name('account.reviews');
    // Retours
    Route::get('/compte/retours', [App\Http\Controllers\ReturnController::class, 'index'])->name('account.returns');
    Route::post('/compte/retours', [App\Http\Controllers\ReturnController::class, 'store'])->name('returns.store');
    Route::put('/compte/retours/{returnRequest}/cancel', [App\Http\Controllers\ReturnController::class, 'cancel'])->name('returns.cancel');

    // Avis
    Route::post('/produits/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/compte/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/compte/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Facture
    Route::get('/compte/facture/{invoice}', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
});

// ===== AUTH ONLY (PROTÉGÉ — connecté seulement) =====
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [WebAuthController::class, 'logout'])->name('logout');

    // Verification d'email
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/compte')->with('success', 'Votre adresse e-mail a été vérifiée !');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        
        $code = sprintf("%06d", mt_rand(100000, 999999));
        cache()->put('email_verify_code_' . $user->id, $code, 3600);
        
        $user->sendEmailVerificationNotification();
        return back()->with('success', 'Un nouvel e-mail de validation (contenant le lien et le code) vous a été envoyé !');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::post('/email/verify-code', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
        
        $user = auth()->user();
        $storedCode = cache()->get('email_verify_code_' . $user->id);
        
        if ($storedCode && $storedCode === $request->code) {
            $user->markEmailAsVerified();
            cache()->forget('email_verify_code_' . $user->id);
            return redirect('/compte')->with('success', 'Votre adresse e-mail a été vérifiée !');
        }
        
        return back()->withErrors(['code' => 'Code de vérification incorrect ou expiré.']);
    })->name('verification.verify-code');
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
    Route::get('/shipping-zones', [App\Http\Controllers\Admin\ShippingZoneController::class, 'index'])->name('shipping-zones.index');
    Route::put('/shipping-zones/{zone}', [App\Http\Controllers\Admin\ShippingZoneController::class, 'update'])->name('shipping-zones.update');
    Route::put('/shipping-zones/{zone}/toggle', [App\Http\Controllers\Admin\ShippingZoneController::class, 'toggle'])->name('shipping-zones.toggle');

    // Utilisateurs
    Route::get('/utilisateurs', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('users.show');
    Route::get('/utilisateurs/{user}/editer', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/utilisateurs/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::put('/utilisateurs/{user}/toggle-active', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
    Route::put('/utilisateurs/{user}/toggle-admin', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::put('/utilisateurs/{user}/unblock', [App\Http\Controllers\Admin\UserManagementController::class, 'unblock'])->name('users.unblock');
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

    // Newsletter Admin
    Route::get('/newsletter', [App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
    Route::post('/newsletter/subscribers', [App\Http\Controllers\Admin\NewsletterController::class, 'storeSubscriber'])->name('newsletter.subscribers.store');
    Route::put('/newsletter/subscribers/{subscriber}/toggle', [App\Http\Controllers\Admin\NewsletterController::class, 'toggleSubscriber'])->name('newsletter.subscribers.toggle');
    Route::delete('/newsletter/subscribers/{subscriber}', [App\Http\Controllers\Admin\NewsletterController::class, 'destroySubscriber'])->name('newsletter.subscribers.destroy');
    Route::get('/newsletter/campagne/nouvelle', [App\Http\Controllers\Admin\NewsletterController::class, 'createCampaign'])->name('newsletter.campaign.create');
    Route::post('/newsletter/campagne', [App\Http\Controllers\Admin\NewsletterController::class, 'sendCampaign'])->name('newsletter.campaign.send');

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
    // Funnels / Marketing
    Route::get('/funnels', [App\Http\Controllers\Admin\FunnelsController::class, 'index'])->name('funnels.index');
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
    // Retours
    Route::get('/retours', [App\Http\Controllers\Admin\ReturnController::class, 'index'])->name('returns.index');
    Route::put('/retours/{return}', [App\Http\Controllers\Admin\ReturnController::class, 'update'])->name('returns.update');
    // Diagnostics IA
    Route::get('/diagnostics', [App\Http\Controllers\DiagnosticController::class, 'adminIndex'])->name('diagnostics.index');
    Route::delete('/diagnostics/{diagnostic}', [App\Http\Controllers\DiagnosticController::class, 'adminDestroy'])->name('diagnostics.destroy');

    // Récompenses / Fidélité
    Route::get('/rewards', [App\Http\Controllers\Admin\RewardsController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/settings', [App\Http\Controllers\Admin\RewardsController::class, 'updateSettings'])->name('rewards.update-settings');
    Route::post('/rewards/rules', [App\Http\Controllers\Admin\RewardsController::class, 'addRule'])->name('rewards.add-rule');
    Route::delete('/rewards/rules/{id}', [App\Http\Controllers\Admin\RewardsController::class, 'deleteRule'])->name('rewards.delete-rule');
});
