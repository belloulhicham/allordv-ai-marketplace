<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{__('messages.service_zone_configuration')}}</h5>
                            <a href="{{route('servicezone.index')}}" class="float-end btn btn-sm btn-primary">
                                <i class="fa fa-angle-double-left"></i> {{__('messages.back')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions pour Agents IA -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Instructions pour créer un agent IA</h5>
                <p><strong class="text-primary">Étape 1:</strong> Définissez le secteur d'activité de votre agent IA (Restaurant, Santé, Beauté, etc.)</p>
                <p><strong class="text-primary">Étape 2:</strong> Configurez la personnalité et les spécialisations de l'agent</p>
                <p><strong class="text-primary">Étape 3:</strong> Choisissez le modèle IA et ajoutez votre clé API</p>
            </div>
        </div>

        <!-- Formulaire Agents IA -->
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h2 class="mb-4">{{ $pageTitle }}</h2>

                    <form method="POST" action="{{ route('servicezone.store') }}" id="servicezone">
                        @csrf
                        @if(isset($servicezone->id))
                            <input type="hidden" name="id" value="{{ $servicezone->id }}">
                        @endif

                        <div class="row">
                            <!-- Nom du Secteur IA -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="name" class="form-control-label">Nom du secteur IA</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Saisissez le nom du secteur IA" value="{{ $servicezone->name ?? old('name') }}" required>
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <!-- Secteur IA -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="ai_sector" class="form-control-label">Secteur IA</label>
                                <select name="ai_sector" id="ai_sector" class="form-control select2js" required>
                                    <option value="">Sélectionnez un secteur IA</option>
                                    <option value="Restaurant" {{ (isset($servicezone) && $servicezone->ai_sector == 'Restaurant') ? 'selected' : '' }}>Restaurant</option>
                                    <option value="Sante" {{ (isset($servicezone) && $servicezone->ai_sector == 'Sante') ? 'selected' : '' }}>Santé</option>
                                    <option value="Beaute" {{ (isset($servicezone) && $servicezone->ai_sector == 'Beaute') ? 'selected' : '' }}>Beauté</option>
                                    <option value="Sport" {{ (isset($servicezone) && $servicezone->ai_sector == 'Sport') ? 'selected' : '' }}>Sport</option>
                                    <option value="General" {{ (isset($servicezone) && $servicezone->ai_sector == 'General') ? 'selected' : '' }}>Général</option>
                                </select>
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <!-- Personnalité IA -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="ai_personality" class="form-control-label">Personnalité IA</label>
                                <input type="text" name="ai_personality" id="ai_personality" class="form-control" placeholder="Saisissez la personnalité de l'IA (ex: amical, formel)" value="{{ $servicezone->ai_personality ?? old('ai_personality') }}">
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <!-- Modèle IA -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="ai_model" class="form-control-label">Modèle IA</label>
                                <select name="ai_model" id="ai_model" class="form-control select2js" required>
                                    <option value="">Sélectionnez un modèle IA</option>
                                    <option value="gpt-4o" {{ (isset($servicezone) && $servicezone->ai_model == 'gpt-4o') ? 'selected' : '' }}>GPT-4o (OpenAI)</option>
                                    <option value="gpt-3.5-turbo" {{ (isset($servicezone) && $servicezone->ai_model == 'gpt-3.5-turbo') ? 'selected' : '' }}>GPT-3.5 Turbo (OpenAI)</option>
                                    <option value="gemini-1.5-flash" {{ (isset($servicezone) && $servicezone->ai_model == 'gemini-1.5-flash') ? 'selected' : '' }}>Gemini 1.5 Flash (Google)</option>
                                    <option value="gemini-1.5-pro" {{ (isset($servicezone) && $servicezone->ai_model == 'gemini-1.5-pro') ? 'selected' : '' }}>Gemini 1.5 Pro (Google)</option>
                                </select>
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <!-- Spécialisations IA -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="ai_specializations" class="form-control-label">Spécialisations IA</label>
                                <select name="ai_specializations[]" id="ai_specializations" class="form-control select2js" multiple data-placeholder="Sélectionnez les spécialisations IA">
                                    <option value="Reservation" {{ (isset($servicezone) && in_array('Reservation', $servicezone->ai_specializations ?? [])) ? 'selected' : '' }}>Gestion de Réservations</option>
                                    <option value="Commande" {{ (isset($servicezone) && in_array('Commande', $servicezone->ai_specializations ?? [])) ? 'selected' : '' }}>Prise de Commandes</option>
                                    <option value="SupportClient" {{ (isset($servicezone) && in_array('SupportClient', $servicezone->ai_specializations ?? [])) ? 'selected' : '' }}>Support Client</option>
                                    <option value="InformationProduit" {{ (isset($servicezone) && in_array('InformationProduit', $servicezone->ai_specializations ?? [])) ? 'selected' : '' }}>Information Produit</option>
                                    <option value="Marketing" {{ (isset($servicezone) && in_array('Marketing', $servicezone->ai_specializations ?? [])) ? 'selected' : '' }}>Campagnes Marketing</option>
                                </select>
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <!-- Clé API -->
                            <div class="form-group col-md-6 mb-3">
                                <label for="api_key" class="form-control-label">Clé API</label>
                                <input type="text" name="api_key" id="api_key" class="form-control" placeholder="Saisissez la clé API pour l'agent IA" value="{{ $servicezone->api_key ?? old('api_key') }}">
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <!-- Paramètres IA Avancés -->
                            <div class="form-group col-md-12 mb-3">
                                <label for="ai_parameters" class="form-control-label">Paramètres IA (JSON)</label>
                                <textarea name="ai_parameters" id="ai_parameters" class="form-control" rows="5" placeholder="Saisissez les paramètres IA au format JSON">{{ isset($servicezone->ai_parameters) ? (is_array($servicezone->ai_parameters) ? json_encode($servicezone->ai_parameters, JSON_PRETTY_PRINT) : $servicezone->ai_parameters) : old('ai_parameters') }}</textarea>
                                <small class="help-block with-errors text-danger"></small>
                                <p class="text-muted"><small>Saisissez les paramètres avancés de l'IA au format JSON (ex: {"temperature": 0.7, "max_tokens": 150}).</small></p>
                            </div>

                            <!-- Champ hidden pour le statut -->
                            <input type="hidden" name="status" id="servicezone_status" value="{{ isset($servicezone) ? $servicezone->status : 1 }}">
                        </div>

                        <!-- Switch Status -->
                        <div class="row">
                            <div class="form-group col-md-12 mb-3">
                                <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                                    <div class="custom-switch-inner">
                                        <input type="checkbox" class="custom-control-input bg-primary" id="status" name="status_checkbox" {{ (isset($servicezone) && $servicezone->status == 1) ? 'checked' : 'checked' }}>
                                        <label class="custom-control-label" for="status" data-on-label="Actif" data-off-label="Inactif"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        @if(auth()->user()->can('service zone edit'))
                            <button type="submit" class="btn btn-md btn-primary float-end">{{__('messages.save')}}</button>
                        @endif
                        <a href="{{ route('servicezone.index') }}" class="btn btn-dark">{{__('messages.back')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')
    <script>
        $(document).ready(function() {
            // Initialisation des select2js
            $('.select2js').select2();

            // Gérer le cas où ai_parameters existe et doit être affiché comme JSON formaté
            @if(isset($servicezone) && $servicezone->ai_parameters)
                var aiParametersData = {!! json_encode($servicezone->ai_parameters) !!};
                if (typeof aiParametersData === 'object' && aiParametersData !== null) {
                    $('textarea[name="ai_parameters"]').val(JSON.stringify(aiParametersData, null, 2));
                } else {
                    $('textarea[name="ai_parameters"]').val(aiParametersData);
                }
            @endif

            // Gestion de la soumission du formulaire
            $('#servicezone').on('submit', function(e) {
                const aiParametersInput = $('textarea[name="ai_parameters"]');
                
                // Validation JSON pour ai_parameters
                if (aiParametersInput.length && aiParametersInput.val()) {
                    try {
                        JSON.parse(aiParametersInput.val());
                    } catch (err) {
                        alert('Le champ Paramètres IA doit contenir un JSON valide.');
                        e.preventDefault();
                        return false;
                    }
                }

                // Synchroniser le statut avec le champ caché
                $('#servicezone_status').val($('#status').is(':checked') ? 1 : 0);

                // Afficher un message de chargement simple
                $('button[type="submit"]').html('Enregistrement...').prop('disabled', true);
            });

            // Initialiser le statut du switch
            var servicezoneStatus = {{ isset($servicezone) && $servicezone->status !== null ? $servicezone->status : 1 }};
            $('#status').prop('checked', servicezoneStatus == 1);
            $('#servicezone_status').val(servicezoneStatus);

            $('#status').change(function() {
                if ($(this).prop('checked')) {
                    $('#servicezone_status').val(1);
                } else {
                    $('#servicezone_status').val(0);
                }
            });
        });
    </script>
    @endsection

</x-master-layout>
