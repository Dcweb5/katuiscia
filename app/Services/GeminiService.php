<?php

namespace App\Services;

use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Analyze a skin image and questionnaire data.
     *
     * @param string $imagePath Absolute path to the selfie image
     * @param array $answers User survey answers (name, skin_type, concern, current_products)
     * @return array The structured analysis results
     */
    public function analyze(string $imagePath, array $answers): array
    {
        $apiKey = env('GEMINI_API_KEY');

        // Fetch active products to provide context for recommendations
        $productsContext = "";
        try {
            $products = Product::where('is_active', true)->get(['id', 'name', 'description', 'key_ingredients']);
            foreach ($products as $p) {
                $productsContext .= "ID: {$p->id} | Nom: {$p->name} | Description: " . strip_tags($p->description ?? '') . " | Ingrédients clés: " . strip_tags($p->key_ingredients ?? '') . "\n";
            }
        } catch (\Exception $e) {
            Log::error("Error loading products for Gemini context: " . $e->getMessage());
        }

        if (empty($apiKey) || str_starts_with($apiKey, 'MOCK_') || strlen($apiKey) < 10) {
            Log::info("Gemini API Key missing or invalid. Falling back to mock skin analysis.");
            return $this->getMockAnalysis($answers);
        }

        try {
            if (!file_exists($imagePath)) {
                throw new \Exception("Selfie image file not found.");
            }

            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath) ?: 'image/jpeg';

            $prompt = "Tu es un dermatologue et expert en cosmétique botanique pour la marque de luxe KATUISCIA.
Analyse cette image de peau et les réponses au questionnaire d'onboarding de l'utilisateur :
- Nom complet : {$answers['name']}
- Type de peau : {$answers['skin_type']}
- Préoccupation principale : {$answers['concern']}
- Produits utilisés actuellement : {$answers['current_products']}

Voici la liste des produits réels vendus par KATUISCIA. Tu dois UNIQUEMENT piocher tes recommandations parmi ces produits exacts :
{$productsContext}

Instructions d'analyse :
1. Détecte les imperfections de la peau visibles sur l'image (points noirs, acné, rougeurs, teint terne, taches, sécheresse, ridules, etc.).
2. Évalue la gravité/sévérité (low, medium ou high). Si la gravité est 'high', définis 'consult_specialist' à true et ajoute des avertissements clairs de consulter un médecin/dermatologue dans 'analysis_details' et 'general_advice'.
3. Propose une sélection de 1 à 3 produits Katuiscia adaptés parmi la liste fournie, avec une explication précise de pourquoi ce produit aidera à résoudre son problème.
4. Reste professionnel, rassurant et luxueux dans ton ton.

Renvoie UNIQUEMENT un objet JSON valide, sans balise de code markdown, contenant exactement la structure suivante :
{
  \"imperfections\": [\"acné\", \"rougeurs\"],
  \"severity\": \"low\" (ou \"medium\" ou \"high\"),
  \"consult_specialist\": false (ou true si sévère),
  \"analysis_details\": \"Ton analyse dermatologique détaillée en français...\",
  \"recommended_products\": [
     {
       \"product_id\": 1,
       \"product_name\": \"Nom du produit\",
       \"reason\": \"Explication personnalisée sur l'application de ce produit pour sa peau...\"
     }
  ],
  \"general_advice\": \"Conseils généraux d'hygiène et rituel de soin en français...\"
}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception("Gemini API request failed: " . $response->body());
            }

            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Clean markdown response if any
            $text = trim($text);
            if (str_starts_with($text, '```json')) {
                $text = substr($text, 7);
            }
            if (str_ends_with($text, '```')) {
                $text = substr($text, 0, -3);
            }
            $text = trim($text);

            $decoded = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['analysis_details'])) {
                throw new \Exception("Invalid JSON response from Gemini API: " . $text);
            }

            return $decoded;

        } catch (\Exception $e) {
            Log::error("Gemini analysis error: " . $e->getMessage());
            return $this->getMockAnalysis($answers);
        }
    }

    /**
     * Fallback mock analysis if API key is missing or fails.
     */
    private function getMockAnalysis(array $answers): array
    {
        $skinType = strtolower($answers['skin_type']);
        $concern = strtolower($answers['concern']);
        $recommendations = [];

        // Recherche dynamique des produits réels en base de données
        if (str_contains($concern, 'acn') || str_contains($concern, 'imperf')) {
            $names = ['Lotion Purifiante', 'Nectar Lumineux'];
        } elseif (str_contains($concern, 'rid') || str_contains($concern, 'age')) {
            $names = ['Botanique de Minuit', 'Émulsion Soyeuse'];
        } elseif (str_contains($concern, 'roug') || str_contains($concern, 'sensib')) {
            $names = ['Crème Douce', 'Baume Aura'];
        } elseif (str_contains($concern, 'tach') || str_contains($concern, 'pigment')) {
            $names = ['Sérum Éclat', 'Gommage Terracotta'];
        } else { // Terne / Hydratation
            $names = ['Sérum Éclat', 'Lait Céleste'];
        }

        foreach ($names as $name) {
            $product = Product::where('name', 'like', "%{$name}%")->where('is_active', true)->first();
            if ($product) {
                $recommendations[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'reason' => "Idéal pour rééquilibrer votre peau et cibler spécifiquement les imperfections ou besoins liés à votre préoccupation : " . $answers['concern'] . "."
                ];
            }
        }

        // Repli sur deux produits actifs de la base de données si aucun produit spécifique n'a été trouvé
        if (empty($recommendations)) {
            $products = Product::where('is_active', true)->take(2)->get();
            foreach ($products as $product) {
                $recommendations[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'reason' => "Soin recommandé pour équilibrer, apaiser et magnifier votre teint au quotidien."
                ];
            }
        }

        // Détection de la sévérité
        $isSevere = str_contains(strtolower($answers['current_products'] . ' ' . $concern), 'sévère') || str_contains(strtolower($answers['current_products'] . ' ' . $concern), 'severe') || str_contains(strtolower($answers['current_products'] . ' ' . $concern), 'kyste') || str_contains(strtolower($answers['current_products'] . ' ' . $concern), 'maladie');

        $imperfections = [];
        if (str_contains($concern, 'acn')) $imperfections[] = 'microkystes et comédons';
        if (str_contains($concern, 'rid')) $imperfections[] = 'ridules de déshydratation';
        if (str_contains($concern, 'roug')) $imperfections[] = 'érythème léger/sensibilité';
        if (str_contains($concern, 'tach')) $imperfections[] = 'hyper-pigmentation localisée';
        if (empty($imperfections)) $imperfections[] = 'perte d\'éclat et texture irrégulière';

        return [
            'imperfections' => $imperfections,
            'severity' => $isSevere ? 'high' : 'low',
            'consult_specialist' => $isSevere,
            'analysis_details' => $isSevere 
                ? "L'analyse visuelle indique des lésions cutanées plus profondes et potentiellement inflammatoires. Un diagnostic clinique en face-à-face est vivement recommandé pour écarter toute affection sous-jacente."
                : "Votre peau présente des signes légers de préoccupation liés à votre profil. Les pores peuvent être légèrement obstrués ou déshydratés en surface. Une routine botanique ciblée aidera à rééquilibrer le pH de votre épiderme.",
            'recommended_products' => $recommendations,
            'general_advice' => $isSevere
                ? "1. Consultez un dermatologue pour obtenir un avis clinique.\n2. Évitez les gommages abrasifs physiques qui agressent l'épiderme.\n3. Utilisez uniquement des nettoyants physiologiques doux pour ne pas irriter davantage."
                : "1. Nettoyez votre visage matin et soir avec un produit doux.\n2. Hydratez quotidiennement avec des formulations botaniques exemptes d'huiles minérales occlusives.\n3. Protégez votre barrière cutanée des agressions extérieures avec un soin antioxydant."
        ];
    }
}
