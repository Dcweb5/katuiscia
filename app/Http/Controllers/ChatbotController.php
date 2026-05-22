<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['reply' => 'Chatbot non configuré.']);
        }

        $context = "Tu es l'assistant virtuel de KATUISCIA, une marque française de cosmétiques botaniques artisanaux. "
            . "Tu réponds en français, de manière chaleureuse et professionnelle. "
            . "Tu aides les clients sur : les produits (soins visage, corps, cheveux, aromathérapie), "
            . "les commandes, la livraison (2-5 jours, offerte dès 80€), les retours, "
            . "les formations beauté, le programme grossiste, et le programme de fidélité. "
            . "Le site est katuiscia.com. L'email de contact est contact@katuiscia.com. "
            . "Sois concis (2-4 phrases maximum). Si tu ne connais pas la réponse, propose de contacter le service client.";

        try {
            $response = Http::timeout(15)
                ->withOptions(['verify' => !app()->isLocal()])
                ->post('https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
                    'contents' => [[
                        'parts' => [
                            ['text' => $context . "\n\nClient: " . $request->message]
                        ]
                    ]]
                ]);

            if (!$response->successful()) {
                \Log::error('Gemini API error: ' . $response->status() . ' - ' . $response->body());
                return response()->json(['reply' => 'Service momentanément indisponible. Veuillez réessayer.']);
            }

            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Désolé, je n\'ai pas compris. Pouvez-vous reformuler ?';
            $reply = trim($reply);

            \App\Models\ChatMessage::create([
                'session_id' => session()->getId(),
                'user_id' => auth()->id(),
                'message' => $request->message,
                'reply' => $reply,
            ]);

            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            \Log::error('Chatbot exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Je rencontre un problème technique. Veuillez réessayer dans un instant.']);
        }
    }
}
