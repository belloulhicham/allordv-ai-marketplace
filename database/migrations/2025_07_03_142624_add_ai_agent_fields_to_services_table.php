<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_ai_agent')->default(false);
            $table->string('ai_speciality')->nullable();
            $table->text('system_prompt')->nullable();
            $table->decimal('price_per_session_mad', 8, 2)->nullable();
            $table->json('languages')->default('["fr","ar"]');
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['is_ai_agent', 'ai_speciality', 'system_prompt', 'price_per_session_mad', 'languages']);
        });
    }
};
