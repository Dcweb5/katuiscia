<?php

namespace App\Modules\Auth\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterFullRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'email'    => $request->email,
            'password' => $request->password,
            'name'     => explode('@', $request->email)[0],
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return ApiResponse::success(
            data: [
                'user'  => $user,
                'token' => $token,
            ],
            message: 'Inscription réussie.',
            code: 201,
            extra: ['token_type' => 'Bearer']
        );
    }

    public function registerFull(RegisterFullRequest $request): JsonResponse
    {
        $user = User::create([
            'firstname'   => $request->firstname,
            'lastname'    => $request->lastname,
            'name'        => $request->firstname . ' ' . $request->lastname,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'city'        => $request->city,
            'postal_code' => $request->postal_code,
            'country'     => $request->country ?? 'FR',
            'password'    => $request->password,
            'newsletter'  => $request->boolean('newsletter'),
            'loyalty_points' => 100, // Bonus de bienvenue
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return ApiResponse::success(
            data: [
                'user'  => $user,
                'token' => $token,
            ],
            message: 'Inscription complète réussie. Bienvenue chez KATUISCIA !',
            code: 201,
            extra: ['token_type' => 'Bearer']
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error(
                message: 'Identifiants incorrects.',
                code: 401
            );
        }

        if (!$user->is_active) {
            return ApiResponse::error(
                message: 'Votre compte est désactivé. Contactez le support.',
                code: 403
            );
        }

        // Revoke old tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return ApiResponse::success(
            data: [
                'user'  => $user,
                'token' => $token,
            ],
            message: 'Connexion réussie.',
            extra: ['token_type' => 'Bearer']
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(
            message: 'Déconnexion réussie.'
        );
    }

    public function user(Request $request): JsonResponse
    {
        return ApiResponse::success(
            data: $request->user()
        );
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(
                message: 'Un lien de réinitialisation vous a été envoyé par email.'
            );
        }

        return ApiResponse::error(
            message: 'Impossible d\'envoyer le lien. Vérifiez votre adresse email.',
            code: 400
        );
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return ApiResponse::success(
                message: 'Mot de passe réinitialisé avec succès.'
            );
        }

        return ApiResponse::error(
            message: 'Le token de réinitialisation est invalide ou a expiré.',
            code: 400
        );
    }
}
