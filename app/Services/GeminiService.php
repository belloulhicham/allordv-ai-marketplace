<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Garder pour les logs futures

class GeminiService
{
    private $apiKey;
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generateResponse($systemPrompt, $userMessage)
    {
        if (!$this->apiKey) {
            return "Configuration Gemini manquante. Mode test activé : " . $systemPrompt . " Réponse à: " . $userMessage;
        }

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nUtilisateur: " . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Désolé, je n\'ai pas pu générer une réponse.';
            }

            // <<<<<<<<<<<<<<<< C'EST LA LIGNE CLÉ ! >>>>>>>>>>>>>>>>>>
            // Affiche le code d'état HTTP, le corps de la réponse brut et la version JSON de la réponse.
            dd($response->status(), $response->body(), $response->json()); 

            // Le code ci-dessous ne sera plus exécuté pour le moment
            // Log::error('Gemini API Error', ['response' => $response->body()]);
            // return 'Désolé, je rencontre des difficultés techniques. Pouvez-vous réessayer ?';

        } catch (\Exception $e) {
            Log::error('Gemini Service Error', ['error' => $e->getMessage()]);
            return 'Je ne peux pas répondre pour le moment. Veuillez réessayer plus tard.';
        }
    }
}
