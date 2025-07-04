<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nService
{
    private $baseUrl;
    private $apiKey;
    
    public function __construct()
    {
        $this->baseUrl = 'https://n8n.allordv.com';
        $this->apiKey = env('N8N_API_KEY');
    }
    
    /**
     * Envoyer un message à un agent via n8n webhook
     */
    public function sendMessageToAgent($agentId, $message, $userId)
    {
        $webhookUrl = "{$this->baseUrl}/webhook/agent-{$agentId}";
        
        $payload = [
            'message' => $message,
            'user_id' => $userId,
            'timestamp' => now()->toISOString(),
            'agent_id' => $agentId
        ];
        
        try {
            $response = Http::timeout(30)->post($webhookUrl, $payload);
            
            Log::info('N8N Request', [
                'url' => $webhookUrl,
                'payload' => $payload,
                'status' => $response->status()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'response' => $data['response'] ?? $data['message'] ?? 'Réponse reçue',
                    'agent_id' => $agentId
                ];
            } else {
                Log::error('N8N Agent Error', [
                    'agent_id' => $agentId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Agent temporairement indisponible (Status: ' . $response->status() . ')'
                ];
            }
        } catch (\Exception $e) {
            Log::error('N8N Connection Error', [
                'agent_id' => $agentId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'Erreur de connexion avec l\'agent'
            ];
        }
    }
    
    /**
     * Test de connectivité avec n8n
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(10)->get($this->baseUrl . '/healthz');
            
            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful() ? 'Connexion réussie' : 'Connexion échouée'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Créer un workflow simple pour un agent
     */
    public function createAgentWorkflow($agentData)
    {
        // Pour l'instant, on assume que les workflows sont créés manuellement
        // Cette méthode peut être étendue pour créer automatiquement via API
        
        Log::info('Agent workflow creation requested', [
            'agent_id' => $agentData['id'],
            'agent_name' => $agentData['name']
        ]);
        
        return [
            'success' => true,
            'webhook_url' => "{$this->baseUrl}/webhook/agent-{$agentData['id']}",
            'message' => 'Workflow créé manuellement dans n8n'
        ];
    }
    
    /**
     * Déclencher l'automatisation après achat
     */
    public function triggerPurchaseAutomation($purchaseData)
    {
        $webhookUrl = "{$this->baseUrl}/webhook/purchase-completed";
        
        try {
            $response = Http::post($webhookUrl, $purchaseData);
            
            return [
                'success' => $response->successful(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Purchase automation error', [
                'error' => $e->getMessage(),
                'data' => $purchaseData
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
