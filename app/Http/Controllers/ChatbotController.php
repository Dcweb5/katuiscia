<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $apiKey = env('DEEPSEEK_API_KEY');
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
                ->withToken($apiKey)
                ->post('https://api.deepseek.com/v1/chat/completions', [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        ['role' => 'system', 'content' => $context],
                        ['role' => 'user', 'content' => $request->message],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

            if (!$response->successful()) {
                \Log::error('DeepSeek API error: ' . $response->status() . ' - ' . $response->body());
                return response()->json(['reply' => 'Service momentanément indisponible. Veuillez réessayer.']);
            }

            $data = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'Désolé, je n\'ai pas compris. Pouvez-vous reformuler ?';
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
