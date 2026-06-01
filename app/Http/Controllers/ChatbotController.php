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
            return response()->json(['reply' => 'Bonjour, je suis Sophie de KATUISCIA. Notre service de messagerie instantanée rencontre des difficultés techniques actuellement. N\'hésitez pas à m\'écrire par e-mail à contact@katuiscia.com.']);
        }

        // Fetch active products and collections to inject into context
        $productsContext = "";
        try {
            $products = \App\Modules\Product\Models\Product::where('is_active', true)->get(['name', 'price', 'description']);
            foreach ($products as $p) {
                $productsContext .= "- Produit: {$p->name} | Prix: " . number_format($p->price, 2, ',', ' ') . " € | Description: " . strip_tags($p->description ?? '') . "\n";
            }
            $collections = \App\Models\Collection::active()->get(['name', 'price', 'description']);
            foreach ($collections as $c) {
                $productsContext .= "- Pack/Collection: {$c->name} | Prix: " . number_format($c->price, 2, ',', ' ') . " € | Description: " . strip_tags($c->description ?? '') . "\n";
            }
        } catch (\Exception $e) {
            \Log::error("Error loading products/collections for Chatbot context: " . $e->getMessage());
        }

        // Fetch active shipping zones with covered countries
        $shippingContext = "";
        try {
            $shippingZones = \App\Models\ShippingZone::where('is_active', true)->get();
            $zoneCountries = [
                'ile_de_france' => ['Paris', 'Île-de-France (région parisienne)'],
                'france_other' => ['France Métropolitaine (hors Île-de-France)'],
                'eu' => ['Allemagne', 'Autriche', 'Belgique', 'Bulgarie', 'Chypre', 'Croatie', 'Danemark', 'Espagne', 'Estonie', 'Finlande', 'Grèce', 'Hongrie', 'Irlande', 'Italie', 'Lettonie', 'Lituanie', 'Luxembourg', 'Malte', 'Pays-Bas', 'Pologne', 'Portugal', 'République Tchèque', 'Roumanie', 'Slovaquie', 'Slovénie', 'Suède'],
                'europe_non_eu' => ['Royaume-Uni', 'Suisse', 'Norvège', 'Islande', 'Liechtenstein', 'Ukraine', 'Biélorussie', 'Moldavie', 'Albanie', 'Monténégro', 'Serbie', 'Macédoine du Nord', 'Bosnie-Herzégovine', 'Andorre', 'Monaco', 'Saint-Marin', 'Vatican'],
                'americas' => ['États-Unis', 'Canada', 'Mexique', 'Brésil', 'Argentine', 'Colombie', 'Chili', 'Pérou', 'Venezuela', 'Équateur', 'Bolivie', 'Paraguay', 'Uruguay', 'Panama', 'Costa Rica', 'Jamaïque', 'Porto Rico', 'Haïti', 'République Dominicaine', 'Guatemala', 'Honduras', 'Salvador', 'Nicaragua'],
                'africa' => ['Algérie', 'Maroc', 'Tunisie', 'Égypte', 'Afrique du Sud', 'Nigeria', 'Kenya', 'Sénégal', 'Côte d\'Ivoire', 'Cameroun', 'RD Congo', 'Madagascar', 'Ghana', 'Angola', 'Mozambique', 'Ouganda', 'Soudan', 'Libye', 'Mauritanie', 'Mali', 'Niger', 'Tchad', 'Burkina Faso', 'Guinée', 'Liberia', 'Sierra Leone', 'Togo', 'Bénin', 'Gabon', 'Congo', 'Burundi', 'Rwanda', 'Tanzanie', 'Zambie', 'Zimbabwe', 'Namibie', 'Botswana', 'Eswatini', 'Lesotho', 'Malawi', 'Somalie', 'Éthiopie', 'Djibouti', 'Érythrée'],
                'asia' => ['Chine', 'Japon', 'Inde', 'Australie', 'Nouvelle-Zélande', 'Singapour', 'Corée du Sud', 'Thaïlande', 'Vietnam', 'Indonésie', 'Malaisie', 'Philippines', 'Pakistan', 'Bangladesh', 'Sri Lanka', 'Népal', 'Birmanie', 'Cambodge', 'Laos', 'Taïwan', 'Hong Kong', 'Macao', 'Israël', 'Arabie Saoudite', 'Émirats Arabes Unis', 'Turquie', 'Iran', 'Irak', 'Jordanie', 'Liban', 'Syrie', 'Yémen', 'Oman', 'Qatar', 'Bahreïn', 'Koweït', 'Kazakhstan', 'Ouzbékistan', 'Turkménistan', 'Kirghizistan', 'Tadjikistan', 'Afghanistan', 'Fidji', 'Papouasie-Nouvelle-Guinée', 'Îles Salomon', 'Vanuatu']
            ];
            foreach ($shippingZones as $zone) {
                $countriesList = isset($zoneCountries[$zone->code]) ? implode(', ', $zoneCountries[$zone->code]) : 'Non spécifié';
                $shippingContext .= "- Zone: {$zone->name} (code: {$zone->code}) | Frais de port: " . ($zone->price == 0 ? 'Gratuit' : number_format($zone->price, 2, ',', ' ') . " €") . " | Délai de livraison: {$zone->delivery_time} | Pays/Territoires couverts: {$countriesList}\n";
            }
        } catch (\Exception $e) {
            \Log::error("Error loading shipping zones for Chatbot context: " . $e->getMessage());
        }

        $systemInstruction = "Tu es Sophie, conseillère clientèle humaine et experte en cosmétique chez KATUISCIA (une marque française haut de gamme de soins botaniques artisanaux).\n"
            . "Les clients doivent avoir l'impression de discuter avec un être humain chaleureux, bienveillant, et professionnel. Ne dis JAMAIS que tu es une intelligence artificielle ou un chatbot, et ne te présente pas comme un modèle linguistique.\n"
            . "Tu devez STRICTEMENT limiter tes réponses aux produits, collections, et services de KATUISCIA. Ne réponds à aucun sujet hors de ce cadre (ex: questions d'actualités, mathématiques, programmation, autres marques). Si on te pose une question non liée à KATUISCIA, réponds poliment que tu es uniquement formée pour aider avec les soins et services de KATUISCIA.\n\n"
            . "Voici les informations sur les produits et packs réels actuellement disponibles en boutique :\n"
            . $productsContext . "\n"
            . "Informations sur les zones de livraison configurées en base de données (utilise ces informations précises pour indiquer si la livraison est possible dans un pays/région donné et à quel tarif/délai) :\n"
            . $shippingContext . "\n"
            . "Informations clés sur les services :\n"
            . "- Livraison offerte en France métropolitaine dès 80 € d'achat (sauf paiement à la livraison, disponible uniquement pour Paris avec livraison gratuite).\n"
            . "- Émail de contact : contact@katuiscia.com\n"
            . "- Diagnostic de peau en ligne disponible gratuitement sur le site.\n"
            . "- Retours sous 14 jours.\n\n"
            . "Règles de style :\n"
            . "- Reste concise (2 à 4 phrases maximum).\n"
            . "- Exprime-toi d'un ton chaleureux, humain, naturel et digne d'une marque de luxe.\n"
            . "- Ne cite jamais d'identifiants techniques ou de codes de base de données.";

        // Chat History (multi-turn)
        try {
            $chatHistory = \App\Models\ChatMessage::where('session_id', session()->getId())
                ->orderBy('created_at', 'asc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            $chatHistory = collect();
        }

        $contents = [];
        foreach ($chatHistory as $msg) {
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $msg->message]]
            ];
            $contents[] = [
                'role' => 'model',
                'parts' => [['text' => $msg->reply]]
            ];
        }

        // Add current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $request->message]]
        ];

        try {
            $response = Http::timeout(15)
                ->withOptions(['verify' => !app()->isLocal()])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => $contents,
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 2048,
                        'temperature' => 0.7,
                    ]
                ]);

            if (!$response->successful()) {
                \Log::error('Gemini API error: ' . $response->status() . ' - ' . $response->body());
                return response()->json(['reply' => 'Bonjour, je rencontre un petit contretemps pour accéder à vos informations. N\'hésitez pas à retaper votre message ou à m\'envoyer un e-mail à contact@katuiscia.com.']);
            }

            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $reply = trim($reply);

            if (empty($reply)) {
                $reply = 'Désolée, je n\'ai pas bien compris. Pouvez-vous reformuler votre question sur nos soins ?';
            }

            // Save to database
            try {
                \App\Models\ChatMessage::create([
                    'session_id' => session()->getId(),
                    'user_id' => auth()->id(),
                    'message' => $request->message,
                    'reply' => $reply,
                ]);
            } catch (\Exception $e) {
                \Log::error('Chatbot db error: ' . $e->getMessage());
            }

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            \Log::error('Chatbot exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Bonjour, je rencontre un souci technique de mon côté. Veuillez réessayer dans un court instant ou m\'écrire par e-mail.']);
        }
    }
}
