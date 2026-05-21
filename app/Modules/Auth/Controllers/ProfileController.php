<?php

namespace App\Modules\Auth\Controllers;

use App\Helpers\ApiResponse;
use App\Modules\Auth\Requests\UpdateProfileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validated();

        if (!empty($data['firstname']) || !empty($data['lastname'])) {
            $data['name'] = trim(
                ($data['firstname'] ?? $user->firstname) . ' ' .
                ($data['lastname'] ?? $user->lastname)
            );
        }

        $user->update($data);

        return ApiResponse::success(
            data: $user->fresh(),
            message: 'Profil mis à jour avec succès.'
        );
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return ApiResponse::error(
                message: 'Le mot de passe actuel est incorrect.',
                code: 403
            );
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Revoke all tokens except current
        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return ApiResponse::success(
            message: 'Mot de passe changé avec succès.'
        );
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return ApiResponse::error(
                message: 'Mot de passe incorrect.',
                code: 403
            );
        }

        $user->tokens()->delete();
        $user->update(['is_active' => false]);
        $user->delete();

        return ApiResponse::success(
            message: 'Votre compte a été supprimé.'
        );
    }
}
