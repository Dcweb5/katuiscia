<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check if user is locked
            if ($user->locked_until && $user->locked_until->isFuture()) {
                $diff = $user->locked_until->diffForHumans();
                return back()->withErrors([
                    'email' => 'Votre compte est temporairement bloqué en raison de trop nombreuses tentatives de connexion incorrectes. Veuillez réessayer dans : ' . $diff,
                ])->onlyInput('email');
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($user) {
                $user->update([
                    'login_attempts' => 0,
                    'locked_until' => null,
                ]);
            }

            // Admin : toujours rediriger vers le dashboard admin
            if (auth()->user()->is_admin) {
                return redirect('/admin');
            }
            return redirect()->intended('/compte');
        }

        if ($user) {
            $attempts = $user->login_attempts + 1;
            $updates = ['login_attempts' => $attempts];

            if ($attempts >= 5) {
                $updates['locked_until'] = now()->addHours(24);
                try {
                    $user->notify(new \App\Notifications\AccountLockedNotification($user, 24));
                } catch (\Exception $e) {
                    \Log::error('Failed to send account lockout email: ' . $e->getMessage());
                }
            }

            $user->update($updates);

            if ($attempts >= 5) {
                return back()->withErrors([
                    'email' => 'Votre compte a été bloqué pour une période de 24 heures en raison de plus de 5 tentatives de mot de passe incorrectes. Un email de sécurité vous a été envoyé.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'city'      => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country'   => 'nullable|string|max:100',
            'password'  => 'required|string|min:8|confirmed',
            'newsletter'=> 'nullable|boolean',
            'terms'     => 'required|accepted',
        ]);

        $signupPoints = \App\Services\LoyaltyService::isEnabled() ? \App\Models\Setting::get('loyalty_points_for_signup', 100) : 0;

        $user = User::create([
            'firstname'   => $validated['firstname'],
            'lastname'    => $validated['lastname'],
            'name'        => $validated['firstname'] . ' ' . $validated['lastname'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'city'        => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country'     => $validated['country'] ?? 'FR',
            'password'    => $validated['password'],
            'newsletter'  => $request->boolean('newsletter'),
            'loyalty_points' => 0,
        ]);

        if ($signupPoints > 0) {
            \App\Services\LoyaltyService::addPoints($user, $signupPoints, 'signup', 'Points de bienvenue pour votre inscription');
        }

        // Sync to newsletter subscribers table if checked
        if ($request->boolean('newsletter')) {
            \App\Models\NewsletterSubscriber::updateOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'is_active' => true]
            );
        }

        // Toujours générer le code de validation à 6 chiffres et le mettre en cache
        $code = sprintf("%06d", mt_rand(100000, 999999));
        cache()->put('email_verify_code_' . $user->id, $code, 3600);

        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Rate limiting progressif basé sur le nombre de renvois
        $resendCount = session('password_resend_count', 0);
        $cooldownSeconds = (int) ($resendCount == 0 ? 0 : min(60 * pow(2, $resendCount - 1), 300)); // 0, 60, 120, 240, 300
        $lastAttempt = session('password_resend_time');

        if ($resendCount > 0 && $lastAttempt && now()->diffInSeconds($lastAttempt) < $cooldownSeconds) {
            $remaining = (int) ceil($cooldownSeconds - now()->diffInSeconds($lastAttempt));
            return redirect()->route('password.request-code', ['email' => $email])->withErrors([
                'email' => 'Veuillez patienter ' . $remaining . ' secondes avant de renvoyer.',
            ])->with('cooldown', $remaining)->with('email_sent', true);
        }

        // Réinitialiser le compteur si plus de 15 minutes se sont écoulées
        if ($lastAttempt && now()->diffInMinutes($lastAttempt) > 15) {
            session(['password_resend_count' => 0]);
            $resendCount = 0;
        }

        // Bloquer après 5 tentatives
        if ($resendCount >= 5) {
            return redirect()->route('password.request-code', ['email' => $email])->withErrors([
                'email' => 'Trop de tentatives. Veuillez réessayer dans 15 minutes.',
            ])->with('cooldown', 900);
        }

        // Incrémenter le compteur de renvois
        session(['password_resend_count' => $resendCount + 1]);
        session(['password_resend_time' => now()]);
        $newCooldown = (int) min(60 * pow(2, $resendCount), 300); // 60, 120, 240, 300...

        // Toujours générer le code à 6 chiffres et le mettre en cache avant de déclencher l'e-mail
        $code = sprintf("%06d", mt_rand(100000, 999999));
        cache()->put('pwd_reset_code_' . $email, $code, 3600);

        try {
            $status = Password::sendResetLink($request->only('email'));

            Log::info('Forgot password attempt', [
                'email' => $email,
                'status' => $status,
                'resend_count' => $resendCount + 1,
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset email SENT', ['email' => $email]);

                // Récupérer le token RAW stocké par le User model
                $rawToken = cache()->get('pwd_reset_raw_' . $email);
                $resetUrl = url('/reinitialisation/' . $rawToken . '?email=' . urlencode($email));

                Log::info('Password reset URL: ' . $resetUrl);

                // En développement : afficher le lien directement
                $devLink = '';
                if (app()->environment('local') && $rawToken) {
                    $devLink = '<br><br><a href="' . $resetUrl . '" style="color:var(--color-warm);text-decoration:underline;font-weight:bold;">🔗 Accéder au lien de reset (mode développement)</a><br><small style="color:var(--color-text-muted);">Ce lien apparaît uniquement en local. En production, il est envoyé par email.</small>';
                }

                return redirect()->route('password.request-code', ['email' => $email])->with([
                    'success' => 'Un email de réinitialisation contenant un lien et un code vous a été envoyé.' . $devLink,
                    'reset_url' => $resetUrl,
                    'email_sent' => true,
                    'cooldown' => $newCooldown,
                ]);
            }

            if ($status === Password::RESET_THROTTLED) {
                return redirect()->route('password.request-code', ['email' => $email])->withErrors([
                    'email' => 'Trop de demandes. Patientez avant de réessayer.',
                ])->with('email_sent', true)->with('cooldown', $newCooldown);
            }

            // Email non trouvé : envoyer une notification discrète
            if ($status === Password::INVALID_USER) {
                Log::info('Password reset: email not found', ['email' => $email]);
                try {
                    Mail::raw(
                        "Bonjour,\n\nQuelqu'un a tenté de réinitialiser le mot de passe d'un compte KATUISCIA avec cette adresse email, mais aucun compte n'y est associé.\n\nSi c'était vous :\n- Vérifiez que vous utilisez l'adresse email avec laquelle vous vous êtes inscrit(e)\n- Créez un compte si vous n'en avez pas encore : " . url('/inscription') . "\n\nSi ce n'était pas vous, ignorez cet email.\n\n---\nCet email a été envoyé automatiquement, merci de ne pas y répondre.\n📧 contact@katuiscia.com\n\nL'équipe KATUISCIA",
                        function ($msg) use ($email) {
                            $msg->to($email)->subject('Tentative de réinitialisation — KATUISCIA');
                        }
                    );
                    Log::info('Unknown-email notification sent', ['email' => $email]);
                } catch (\Exception $e) {
                    Log::error('Failed to send unknown-email notification: ' . $e->getMessage());
                }
            }

            // Message générique (sécurité : ne pas révéler si l'email existe)
            return redirect()->route('password.request-code', ['email' => $email])->with([
                'success' => 'Si cette adresse est associée à un compte, vous recevrez un email.',
                'email_sent' => true,
                'cooldown' => $newCooldown,
            ]);

        } catch (\Exception $e) {
            Log::error('Forgot password exception: ' . $e->getMessage(), [
                'email' => $email,
                'exception' => $e,
            ]);
            return redirect('/mot-de-passe-oublie')->withErrors([
                'email' => 'Une erreur est survenue. Veuillez réessayer ou nous contacter à contact@katuiscia.com.',
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/connexion')->with('success', 'Mot de passe réinitialisé. Connectez-vous.');
        }

        return back()->withErrors(['email' => 'Le lien de réinitialisation est invalide ou a expiré.']);
    }

    public function showForgotCodeForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.forgot-password-code', compact('email'));
    }

    public function verifyForgotCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $storedCode = cache()->get('pwd_reset_code_' . $request->email);

        if ($storedCode && $storedCode === $request->code) {
            session(['pwd_reset_code_verified' => $request->email]);
            cache()->forget('pwd_reset_code_' . $request->email);
            return redirect()->route('password.reset-code', ['email' => $request->email]);
        }

        return back()->withErrors(['code' => 'Code de vérification incorrect ou expiré.'])->onlyInput('email');
    }

    public function showResetPasswordCodeForm(Request $request)
    {
        $email = $request->query('email');
        if (session('pwd_reset_code_verified') !== $email) {
            return redirect('/mot-de-passe-oublie')->withErrors(['email' => 'Veuillez d\'abord vérifier votre email.']);
        }
        return view('auth.reset-password-code', compact('email'));
    }

    public function resetPasswordCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (session('pwd_reset_code_verified') !== $request->email) {
            return redirect('/mot-de-passe-oublie')->withErrors(['email' => 'Session expirée ou invalide.']);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
                'login_attempts' => 0,
                'locked_until' => null,
            ]);

            session()->forget('pwd_reset_code_verified');
            return redirect('/connexion')->with('success', 'Votre mot de passe a été réinitialisé avec succès.');
        }

        return redirect('/mot-de-passe-oublie')->withErrors(['email' => 'Utilisateur introuvable.']);
    }
}
