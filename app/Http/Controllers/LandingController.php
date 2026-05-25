<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function quiz()
    {
        return view('pages.quiz-beaute');
    }

    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'skin_type' => 'nullable|string',
            'concern' => 'nullable|string',
            'budget' => 'nullable|string',
            'routine' => 'nullable|string',
            'opted_in' => 'required|accepted',
        ]);

        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'firstname' => $validated['firstname'],
                'quiz_responses' => [
                    'skin_type' => $validated['skin_type'] ?? null,
                    'concern' => $validated['concern'] ?? null,
                    'budget' => $validated['budget'] ?? null,
                    'routine' => $validated['routine'] ?? null,
                ],
                'utm_source' => session('utm_source'),
                'utm_medium' => session('utm_medium'),
                'utm_campaign' => session('utm_campaign'),
                'opted_in' => true,
                'consent_date' => now(),
            ]
        );

        // Produits recommandés basés sur le type de peau
        $skinType = $validated['skin_type'] ?? null;
        $need = match ($skinType) {
            'seche' => 'hydratation',
            'grasse' => 'eclat',
            'sensible' => 'restauration',
            default => null,
        };

        $query = \App\Modules\Product\Models\Product::active()->ordered();
        if ($need) {
            $recommendedProducts = $query->where('need', $need)->take(2)->get();
        }
        if (empty($recommendedProducts) || $recommendedProducts->count() < 2) {
            $recommendedProducts = $query->take(2)->get();
        }

        return view('pages.merci-quiz', compact('lead', 'recommendedProducts'));
    }
}
