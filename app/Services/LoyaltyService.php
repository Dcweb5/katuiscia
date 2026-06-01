<?php

namespace App\Services;

use App\Models\User;
use App\Models\Setting;
use App\Models\LoyaltyTransaction;

class LoyaltyService
{
    /**
     * Check if the points and rewards system is enabled globally.
     */
    public static function isEnabled(): bool
    {
        return (bool) Setting::get('loyalty_enabled', true);
    }

    /**
     * Credit loyalty points to a user.
     */
    public static function addPoints(User $user, int $points, string $type, string $description, ?int $couponId = null)
    {
        if (!self::isEnabled()) {
            return null;
        }

        if ($points <= 0) {
            return null;
        }

        // Increment user's balance
        $user->increment('loyalty_points', $points);

        // Record transaction
        return LoyaltyTransaction::create([
            'user_id' => $user->id,
            'points' => $points,
            'type' => $type,
            'description' => $description,
            'coupon_id' => $couponId,
        ]);
    }

    /**
     * Debit loyalty points from a user.
     */
    public static function deductPoints(User $user, int $points, string $type, string $description, ?int $couponId = null)
    {
        if (!self::isEnabled()) {
            return null;
        }

        if ($points <= 0) {
            return null;
        }

        // Decrement user's balance (clamp to 0 minimum)
        $currentPoints = $user->loyalty_points;
        $pointsToDeduct = min($points, $currentPoints);
        
        $user->decrement('loyalty_points', $pointsToDeduct);

        // Record transaction (as negative points)
        return LoyaltyTransaction::create([
            'user_id' => $user->id,
            'points' => -$pointsToDeduct,
            'type' => $type,
            'description' => $description,
            'coupon_id' => $couponId,
        ]);
    }
}
