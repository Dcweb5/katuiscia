<?php

namespace App\Modules\Auth\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider): JsonResponse
    {
        $providers = ['google', 'facebook'];

        if (!in_array($provider, $providers)) {
            return ApiResponse::error(
                message: 'Le fournisseur "' . $provider . '" n\'est pas supporté.',
                code: 400
            );
        }

        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return ApiResponse::success(
            data: ['redirect_url' => $url],
            message: 'Redirection vers ' . $provider
        );
    }

    public function callback(string $provider): JsonResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: 'Erreur lors de l\'authentification avec ' . $provider . '.',
                code: 400
            );
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // User exists: update avatar if not set
            if (!$user->avatar && $socialUser->getAvatar()) {
                $user->update(['avatar' => $socialUser->getAvatar()]);
            }
        } else {
            // Create new user from social data
            $nameParts = explode(' ', $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur', 2);
            $firstname = $nameParts[0] ?? null;
            $lastname  = $nameParts[1] ?? null;

            $user = User::create([
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'name'      => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur',
                'email'     => $socialUser->getEmail(),
                'password'  => Hash::make(Str::random(32)),
                'avatar'    => $socialUser->getAvatar(),
                'loyalty_points' => 100,
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return ApiResponse::success(
            data: [
                'user'  => $user,
                'token' => $token,
            ],
            message: 'Authentification via ' . $provider . ' réussie.',
            extra: ['token_type' => 'Bearer']
        );
    }
}
