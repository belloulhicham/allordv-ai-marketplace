<?php

namespace App\Http\Controllers;

use App\Models\ServiceZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        // Récupérer les agents IA disponibles
        $agents = ServiceZone::where('status', 1)
                            ->whereNotNull('ai_sector')
                            ->get();
        
        $pageTitle = 'Chat avec les Agents IA';
        
        return view('chat.index', compact('agents', 'pageTitle'));
    }
    
    /**
     * Chat démo public (sans authentification)
     */
    public function demoChat(Request $request)
    {
        try {
            $agent = ServiceZone::findOrFail($request->agent_id);
            
            // URL webhook n8n
            $webhookUrl = "https://n8n.allordv.com/webhook-test/agent-{$agent->id}";
            
            $response = Http::timeout(30)->post($webhookUrl, [
                'message' => $request->message,
                'user_id' => 'demo_user_' . session()->getId(),
                'agent_id' => $agent->id
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'message' => $data['response'] ?? 'Réponse reçue',
                    'agent_name' => $agent->name,
                    'agent_sector' => $agent->ai_sector,
                    'timestamp' => now()->format('H:i'),
                    'success' => true
                ]);
            }
            
            return response()->json([
                'error' => 'Agent temporairement indisponible'
            ], 503);
            
        } catch (\Exception $e) {
            Log::error('Demo Chat Error', [
                'agent_id' => $request->agent_id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Erreur de communication avec l\'agent'
            ], 500);
        }
    }
    
    /**
     * Récupérer un agent spécifique
     */
    public function getAgent($id)
    {
        try {
            $agent = ServiceZone::findOrFail($id);
            
            return response()->json([
                'id' => $agent->id,
                'name' => $agent->name,
                'sector' => $agent->ai_sector,
                'personality' => $agent->ai_personality,
                'specializations' => $agent->ai_specializations,
                'model' => 'n8n + Gemini',
                'status' => $agent->status ? 'active' : 'inactive'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Agent non trouvé'
            ], 404);
        }
    }
    
    /**
     * Test de connexion simple
     */
    public function testN8nConnection()
    {
        try {
            $response = Http::timeout(10)->get('https://n8n.allordv.com/healthz');
            
            return response()->json([
                'status' => $response->successful() ? 'OK' : 'ERROR',
                'n8n_url' => 'https://n8n.allordv.com',
                'timestamp' => now()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'error' => $e->getMessage()
            ]);
        }
    }
}
