<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class AIAgentController extends Controller
{
    public function index()
    {
        $agents = Service::where('is_ai_agent', true)
                        ->where('status', 1)
                        ->select('id', 'name', 'ai_speciality', 'description', 'price_per_session_mad')
                        ->get();
        return response()->json(['success' => true, 'data' => $agents]);
    }
    public function chat(Request $request)
    {
        $agent = Service::where('id', $request->agent_id)->where('is_ai_agent', true)->first();
        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Agent non trouvé'], 404);
        }
        $userMessage = $request->input('message', 'Bonjour');
        $response = $this->generateGeminiResponse($agent->system_prompt, $userMessage);
        return response()->json([
            'success' => true,
            'response' => $response,
            'agent_name' => $agent->name
        ]);
    }
    private function generateGeminiResponse($systemPrompt, $userMessage)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return "Mode test : " . $systemPrompt . " | Réponse à: " . $userMessage;
        }
        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [ # LIGNE MODIFIÉE ICI
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
            Log::error('Gemini API Error', ['response' => $response->body()]);
            return 'Désolé, je rencontre des difficultés techniques avec l\'IA. Pouvez-vous réessayer ?';
        } catch (\Exception $e) {
            Log::error('Gemini Service Error', ['error' => $e->getMessage()]);
            return 'Service IA temporairement indisponible. Veuillez réessayer plus tard.';
        }
    }
}
