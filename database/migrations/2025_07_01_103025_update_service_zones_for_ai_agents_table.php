<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_zones', function (Blueprint $table) {
            // 1. Renommer 'coordinates' en 'ai_parameters'
            $table->renameColumn('coordinates', 'ai_parameters');

            // 2. Changer le type de la colonne 'ai_parameters' si nécessaire
            // Pour l'instant, 'longtext' est bien pour du JSON ou du texte long de paramètres.
            // Si on veut être plus spécifique, on pourrait utiliser 'jsonb' pour PostgreSQL ou 'json' pour MySQL 5.7+
            // Pour MySQL, 'longtext' est souvent suffisant pour stocker du JSON si vous n'utilisez pas les fonctions JSON natives de MySQL.

            // 3. Ajouter de nouvelles colonnes spécifiques aux agents IA
            $table->string('ai_sector')->nullable()->after('name'); // Ex: 'Restaurant', 'Santé', 'Beauté'
            $table->string('ai_personality')->nullable()->after('ai_sector'); // Ex: 'Amicable', 'Formel', 'Direct'
            $table->json('ai_specializations')->nullable()->after('ai_personality'); // Ex: ['Réservations', 'Commandes']
            $table->string('ai_model')->default('gpt-4o')->after('ai_specializations'); // Modèle IA utilisé
            $table->text('api_key')->nullable()->after('ai_model'); // Clé API spécifique à l'agent si nécessaire, ou null si global

            // Optionnel : Mettre à jour des valeurs par défaut si vous avez des données existantes
            // Si vous avez des 'coordinates' existantes qui ne sont pas des paramètres IA, elles seront inchangées
            // Vous pourriez vouloir les vider ou les définir à une valeur par défaut.
            // Par exemple, si vous voulez que les anciennes zones aient un secteur par défaut :
            // DB::table('service_zones')->update(['ai_sector' => 'General']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_zones', function (Blueprint $table) {
            // Revenir en arrière en cas de rollback
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'ai_sector',
                'ai_personality',
                'ai_specializations',
                'ai_model',
                'api_key',
            ]);

            // Renommer 'ai_parameters' en 'coordinates'
            $table->renameColumn('ai_parameters', 'coordinates');
        });
    }
};
