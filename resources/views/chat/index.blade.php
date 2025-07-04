@extends('layouts.public')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle }}</h5>
                            <a href="{{route('servicezone.index')}}" class="float-end btn btn-sm btn-primary">
                                <i class="fa fa-cog"></i> Gérer les Agents
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sélection Agent -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Choisir un Agent IA</h5>
                    </div>
                    <div class="card-body">
                        @if($agents->count() > 0)
                            @foreach($agents as $agent)
                                <div class="agent-card mb-3 p-3 border rounded cursor-pointer" data-agent-id="{{ $agent->id }}" 
                                     data-agent-name="{{ $agent->name }}" data-agent-sector="{{ $agent->ai_sector }}"
                                     data-agent-personality="{{ $agent->ai_personality }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $agent->name }}</h6>
                                            <span class="badge bg-primary mb-2">{{ $agent->ai_sector }}</span>
                                            @if($agent->ai_personality)
                                                <p class="text-muted small mb-1">{{ $agent->ai_personality }}</p>
                                            @endif
                                            @if($agent->ai_specializations)
                                                <div class="specializations">
                                                    @php
                                                        $specs = is_string($agent->ai_specializations) 
                                                            ? json_decode($agent->ai_specializations, true) 
                                                            : $agent->ai_specializations;
                                                    @endphp
                                                    @if(is_array($specs))
                                                        @foreach(array_slice($specs, 0, 2) as $spec)
                                                            <span class="badge bg-light text-dark small">{{ $spec }}</span>
                                                        @endforeach
                                                        @if(count($specs) > 2)
                                                            <span class="badge bg-light text-dark small">+{{ count($specs) - 2 }}</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <span class="badge bg-success">{{ $agent->ai_model }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center p-4">
                                <i class="fa fa-robot fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun agent IA configuré</p>
                                <a href="{{route('servicezone.create')}}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Créer un Agent
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chat Interface -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0" id="chat-title">Sélectionnez un agent pour commencer</h5>
                                <small class="text-muted" id="chat-subtitle"></small>
                            </div>
                            <span class="badge bg-secondary" id="chat-model">Aucun modèle</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Zone de Chat -->
                        <div id="chat-messages" style="height: 400px; overflow-y: auto; border: 1px solid #eee; padding: 15px; background-color: #f8f9fa;">
                            <div class="text-center text-muted">
                                <i class="fa fa-comments fa-2x mb-2"></i>
                                <p>Choisissez un agent IA pour commencer la conversation</p>
                            </div>
                        </div>

                        <!-- Formulaire de Message -->
                        <form id="chat-form" class="mt-3" style="display: none;">
                            <div class="input-group">
                                <input type="hidden" id="selected-agent" value="">
                                <input type="text" id="message-input" class="form-control" 
                                       placeholder="Tapez votre message..." maxlength="1000" disabled>
                                <button type="submit" class="btn btn-primary" id="send-button" disabled>
                                    <i class="fa fa-paper-plane"></i> Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')
    <script>
        $(document).ready(function() {
            let selectedAgent = null;

            // Sélection d'un agent
            $('.agent-card').on('click', function() {
                // Retirer la sélection précédente
                $('.agent-card').removeClass('border-primary bg-light');
                
                // Ajouter la sélection actuelle
                $(this).addClass('border-primary bg-light');
                
                // Récupérer les données de l'agent
                selectedAgent = {
                    id: $(this).data('agent-id'),
                    name: $(this).data('agent-name'),
                    sector: $(this).data('agent-sector'),
                    personality: $(this).data('agent-personality')
                };
                
                // Mettre à jour l'interface
                $('#chat-title').text('Chat avec ' + selectedAgent.name);
                $('#chat-subtitle').text(selectedAgent.sector + ' • ' + (selectedAgent.personality || 'Assistant IA'));
                $('#chat-model').text($(this).find('.badge.bg-success').text());
                $('#selected-agent').val(selectedAgent.id);
                
                // Vider les messages et afficher message de bienvenue
                $('#chat-messages').html(`
                    <div class="message ai-message mb-3">
                        <div class="d-flex">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                <i class="fa fa-robot"></i>
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <strong>${selectedAgent.name}</strong>
                                    <small class="text-muted">maintenant</small>
                                </div>
                                <div class="message-text">
                                    Bonjour ! Je suis ${selectedAgent.name}, votre assistant IA spécialisé en ${selectedAgent.sector}. Comment puis-je vous aider aujourd'hui ?
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                
                // Activer le formulaire
                $('#chat-form').show();
                $('#message-input, #send-button').prop('disabled', false);
                $('#message-input').focus();
            });

            // Envoi de message
            $('#chat-form').on('submit', function(e) {
                e.preventDefault();
                
                const message = $('#message-input').val().trim();
                if (!message || !selectedAgent) return;
                
                // Ajouter le message utilisateur
                addMessage('user', message, 'Vous');
                
                // Vider l'input et désactiver le bouton
                $('#message-input').val('').prop('disabled', true);
                $('#send-button').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Envoi...');
                
                // Envoyer à l'API
                $.ajax({
                    url: '{{ route("chat.send") }}',
                    method: 'POST',
                    data: {
                        agent_id: selectedAgent.id,
                        message: message,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            addMessage('ai', response.response, response.agent_name);
                        } else {
                            addMessage('error', response.message || 'Erreur inconnue', 'Erreur');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Erreur de communication';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        addMessage('error', errorMessage, 'Erreur');
                    },
                    complete: function() {
                        // Réactiver l'interface
                        $('#message-input').prop('disabled', false).focus();
                        $('#send-button').prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Envoyer');
                    }
                });
            });

            // Fonction pour ajouter un message
            function addMessage(type, content, sender) {
                const timestamp = new Date().toLocaleTimeString();
                let messageHtml = '';
                
                if (type === 'user') {
                    messageHtml = `
                        <div class="message user-message mb-3">
                            <div class="d-flex justify-content-end">
                                <div class="message-content">
                                    <div class="message-header text-end">
                                        <strong>${sender}</strong>
                                        <small class="text-muted">${timestamp}</small>
                                    </div>
                                    <div class="message-text bg-primary text-white p-2 rounded">
                                        ${content}
                                    </div>
                                </div>
                                <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center ms-2" style="width: 40px; height: 40px;">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (type === 'ai') {
                    messageHtml = `
                        <div class="message ai-message mb-3">
                            <div class="d-flex">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                    <i class="fa fa-robot"></i>
                                </div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <strong>${sender}</strong>
                                        <small class="text-muted">${timestamp}</small>
                                    </div>
                                    <div class="message-text bg-white border p-2 rounded">
                                        ${content}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (type === 'error') {
                    messageHtml = `
                        <div class="message error-message mb-3">
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i> <strong>${sender}:</strong> ${content}
                            </div>
                        </div>
                    `;
                }
                
                $('#chat-messages').append(messageHtml);
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            }

            // Enter pour envoyer
            $('#message-input').on('keypress', function(e) {
                if (e.which === 13 && !$(this).prop('disabled')) {
                    $('#chat-form').submit();
                }
            });
        });
    </script>
    @endsection
