<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceZone extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 
        'coordinates', // Gardé pour compatibilité
        'status',
        // NOUVEAUX CHAMPS IA
        'ai_sector',
        'ai_personality', 
        'ai_specializations',
        'ai_model',
        'api_key',
        'ai_parameters',
    ];

    protected $casts = [
        'coordinates' => 'array', // Ancien champ
        'ai_specializations' => 'array', // Conversion JSON automatique
        'ai_parameters' => 'array', // Conversion JSON automatique
        'status' => 'boolean',
    ];

    // Many-to-Many with users
    public function users()
    {
        return $this->belongsToMany(User::class, 'service_zone_user');
    }

    // Many-to-Many with categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_service_zone');
    }
    
    public function providers()
    {
        return $this->belongsToMany(User::class, 'provider_zone_mappings', 'zone_id', 'provider_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_zone_mappings', 'zone_id', 'service_id');
    }
    
    // NOUVELLES MÉTHODES POUR L'IA
    
    /**
     * Obtenir les spécialisations comme chaîne lisible
     */
    public function getSpecializationsTextAttribute()
    {
        if (!$this->ai_specializations || empty($this->ai_specializations)) {
            return 'Aucune spécialisation';
        }
        
        $specializations = is_array($this->ai_specializations) 
            ? $this->ai_specializations 
            : json_decode($this->ai_specializations, true);
            
        return implode(', ', $specializations);
    }
    
    /**
     * Obtenir les paramètres IA formatés
     */
    public function getFormattedAiParametersAttribute()
    {
        if (!$this->ai_parameters) {
            return [];
        }
        
        return is_array($this->ai_parameters) 
            ? $this->ai_parameters 
            : json_decode($this->ai_parameters, true);
    }
    
    /**
     * Vérifier si l'agent IA est configuré
     */
    public function isAiConfiguredAttribute()
    {
        return !empty($this->ai_sector) && !empty($this->ai_model);
    }
    
    /**
     * Scope pour filtrer par secteur IA
     */
    public function scopeBySector($query, $sector)
    {
        return $query->where('ai_sector', $sector);
    }
    
    /**
     * Scope pour filtrer par modèle IA
     */
    public function scopeByModel($query, $model)
    {
        return $query->where('ai_model', $model);
    }
}
