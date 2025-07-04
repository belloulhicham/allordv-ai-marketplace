<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🤖 Marketplace Agents IA - AllOrDv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8" x-data="aiAgentsApp()">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                🤖 Marketplace Agents IA
            </h1>
            <p class="text-gray-600">Discutez avec nos experts IA spécialisés</p>
        </div>
        
        <!-- Liste des agents -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <template x-for="agent in agents" :key="agent.id">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <span x-text="agent.name.charAt(0)"></span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xl font-semibold text-gray-800" x-text="agent.name"></h3>
                            <p class="text-blue-600 text-sm" x-text="agent.ai_speciality"></p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4 text-sm" x-text="agent.description"></p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-green-600" x-text="agent.price_per_session_mad + ' MAD/session'"></span>
                        <button @click="selectAgent(agent)" 
                                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105">
                            💬 Discuter
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Chat interface -->
        <div x-show="selectedAgent" x-transition class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6 pb-4 border-b">
                <button @click="selectedAgent = null; messages = []" class="mr-3 text-gray-500 hover:text-gray-700">
                    ← Retour
                </button>
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                    <span x-text="selectedAgent?.name.charAt(0)"></span>
                </div>
                <div class="ml-3">
                    <h2 class="text-xl font-semibold text-gray-800" x-text="'Chat avec ' + selectedAgent?.name"></h2>
                    <p class="text-sm text-gray-500" x-text="selectedAgent?.ai_speciality"></p>
                </div>
                <div class="ml-auto">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">🟢 En ligne</span>
                </div>
            </div>
            
            <div id="chatContainer" class="h-80 overflow-y-auto border rounded-lg p-4 mb-4 bg-gray-50 space-y-3">
                <template x-for="message in messages" :key="message.id">
                    <div>
                        <div x-show="message.type === 'user'" class="flex justify-end">
                            <div class="bg-blue-500 text-white px-4 py-2 rounded-lg max-w-xs lg:max-w-md">
                                <p x-text="message.content"></p>
                                <span class="text-xs opacity-75" x-text="formatTime(message.timestamp)"></span>
                            </div>
                        </div>
                        <div x-show="message.type === 'agent'" class="flex justify-start">
                            <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-xs lg:max-w-md">
                                <p x-text="message.content" class="whitespace-pre-line"></p>
                                <span class="text-xs opacity-75" x-text="formatTime(message.timestamp)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex gap-2">
                <input x-model="newMessage" 
                       @keyup.enter="sendMessage()"
                       type="text" 
                       placeholder="Tapez votre message..."
                       class="flex-1 border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:outline-none">
                <button @click="sendMessage()" 
                        :disabled="loading || !newMessage.trim()"
                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300">
                    <span x-show="!loading">📤 Envoyer</span>
                    <span x-show="loading">⏳ Envoi...</span>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>🚀 Powered by <strong>AllOrDv</strong> - Marketplace d'Agents IA Intelligents</p>
        </div>
    </div>

    <script>
        function aiAgentsApp() {
            return {
                agents: [],
                selectedAgent: null,
                messages: [],
                newMessage: '',
                loading: false,

                async init() {
                    await this.loadAgents();
                },

                async loadAgents() {
                    try {
                        const response = await fetch('/api/ai-agents');
                        const data = await response.json();
                        this.agents = data.data;
                    } catch (error) {
                        console.error('Erreur:', error);
                    }
                },

                selectAgent(agent) {
                    this.selectedAgent = agent;
                    this.messages = [{
                        id: Date.now(),
                        type: 'agent',
                        content: `Bonjour ! Je suis ${agent.name}, spécialisé(e) en ${agent.ai_speciality}. Comment puis-je vous aider aujourd'hui ?`,
                        timestamp: new Date()
                    }];
                    this.scrollToBottom();
                },

                async sendMessage() {
                    if (!this.newMessage.trim() || this.loading) return;

                    const userMessage = this.newMessage;
                    this.messages.push({
                        id: Date.now(),
                        type: 'user',
                        content: userMessage,
                        timestamp: new Date()
                    });
                    this.newMessage = '';
                    this.loading = true;
                    this.scrollToBottom();

                    try {
                        const response = await fetch('/api/ai-agents/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                agent_id: this.selectedAgent.id,
                                message: userMessage
                            })
                        });

                        const data = await response.json();
                        
                        this.messages.push({
                            id: Date.now() + 1,
                            type: 'agent',
                            content: data.response,
                            timestamp: new Date()
                        });
                        this.scrollToBottom();
                    } catch (error) {
                        this.messages.push({
                            id: Date.now() + 1,
                            type: 'agent',
                            content: 'Désolé, une erreur est survenue. Veuillez réessayer.',
                            timestamp: new Date()
                        });
                    } finally {
                        this.loading = false;
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('chatContainer');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                formatTime(date) {
                    return new Date(date).toLocaleTimeString('fr-FR', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            };
        }
    </script>
</body>
</html>
