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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Admin : toujours rediriger vers le dashboard admin
            if (auth()->user()->is_admin) {
                return redirect('/admin');
            }
            return redirect()->intended('/compte');
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
            'loyalty_points' => 100,
        ]);

        Auth::login($user);

        return redirect('/compte');
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
        $request->validate(['email' => 'required|email']);

        // Rate limiting progressif basé sur le nombre de renvois
        $resendCount = session('password_resend_count', 0);
        $cooldownSeconds = $resendCount == 0 ? 0 : min(60 * pow(2, $resendCount - 1), 300); // 0, 60, 120, 240, 300
        $lastAttempt = session('password_resend_time');

        if ($resendCount > 0 && $lastAttempt && now()->diffInSeconds($lastAttempt) < $cooldownSeconds) {
            $remaining = $cooldownSeconds - now()->diffInSeconds($lastAttempt);
            return back()->withErrors([
                'email' => 'Veuillez patienter ' . $remaining . ' secondes avant de renvoyer.',
            ])->with('cooldown', $remaining)->with('email_sent', true)->onlyInput('email');
        }

        // Réinitialiser le compteur si plus de 15 minutes se sont écoulées
        if ($lastAttempt && now()->diffInMinutes($lastAttempt) > 15) {
            session(['password_resend_count' => 0]);
            $resendCount = 0;
        }

        // Bloquer après 5 tentatives
        if ($resendCount >= 5) {
            return back()->withErrors([
                'email' => 'Trop de tentatives. Veuillez réessayer dans 15 minutes.',
            ])->with('cooldown', 900);
        }

        try {
            $status = Password::sendResetLink($request->only('email'));

            // Incrémenter le compteur de renvois
            session(['password_resend_count' => $resendCount + 1]);
            session(['password_resend_time' => now()]);
            $newCooldown = min(60 * pow(2, $resendCount), 300); // 60, 120, 240, 300...

            Log::info('Forgot password attempt', [
                'email' => $request->email,
                'status' => $status,
                'resend_count' => $resendCount + 1,
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset email SENT', ['email' => $request->email]);

                // Récupérer le token RAW stocké par le User model
                $rawToken = cache()->get('pwd_reset_raw_' . $request->email);
                $resetUrl = url('/reinitialisation/' . $rawToken . '?email=' . urlencode($request->email));

                Log::info('Password reset URL: ' . $resetUrl);

                // En développement : afficher le lien directement
                $devLink = '';
                if (app()->environment('local') && $rawToken) {
                    $devLink = '<br><br><a href="' . $resetUrl . '" style="color:var(--color-warm);text-decoration:underline;font-weight:bold;">🔗 Accéder au lien de reset (mode développement)</a><br><small style="color:var(--color-text-muted);">Ce lien apparaît uniquement en local. En production, il est envoyé par email.</small>';
                }

                return back()->with([
                    'success' => 'Un lien de réinitialisation vous a été envoyé par email.' . $devLink,
                    'reset_url' => $resetUrl,
                    'email_sent' => true,
                    'cooldown' => $newCooldown,
                    '__previous_email' => $request->email,
                ]);
            }

            if ($status === Password::RESET_THROTTLED) {
                return back()->withErrors([
                    'email' => 'Trop de demandes. Patientez avant de réessayer.',
                ])->with('email_sent', true)->with('cooldown', $newCooldown);
            }

            // Email non trouvé : envoyer une notification discrète
            if ($status === Password::INVALID_USER) {
                Log::info('Password reset: email not found', ['email' => $request->email]);
                try {
                    Mail::raw(
                        "Bonjour,\n\nQuelqu'un a tenté de réinitialiser le mot de passe d'un compte KATUISCIA avec cette adresse email, mais aucun compte n'y est associé.\n\nSi c'était vous :\n- Vérifiez que vous utilisez l'adresse email avec laquelle vous vous êtes inscrit(e)\n- Créez un compte si vous n'en avez pas encore : " . url('/inscription') . "\n\nSi ce n'était pas vous, ignorez cet email.\n\n---\nCet email a été envoyé automatiquement, merci de ne pas y répondre.\n📧 contact@katuiscia.com\n\nL'équipe KATUISCIA",
                        function ($msg) use ($request) {
                            $msg->to($request->email)
                                ->subject('Tentative de réinitialisation — KATUISCIA');
                        }
                    );
                    Log::info('Unknown-email notification sent', ['email' => $request->email]);
                } catch (\Exception $e) {
                    Log::error('Failed to send unknown-email notification: ' . $e->getMessage());
                }
            }

            // Message générique (sécurité : ne pas révéler si l'email existe)
            return back()->with([
                'success' => 'Si cette adresse est associée à un compte, vous recevrez un email.',
                'email_sent' => true,
                'cooldown' => $newCooldown,
                '__previous_email' => $request->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Forgot password exception: ' . $e->getMessage(), [
                'email' => $request->email,
                'exception' => $e,
            ]);
            return back()->withErrors([
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
}
