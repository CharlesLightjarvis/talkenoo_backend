<?php

use App\Enums\ContentType;
use App\Enums\LanguePreference;
use App\Enums\StatusInvitation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des pays
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code_pays');
            $table->string('devise');
            $table->string('langue_defaut')->default(LanguePreference::FRANCAIS->value);
            $table->string('timezone');
            $table->timestamps();
        });

        // Table des villes
        Schema::create('villes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pays_id')->constrained('pays');
            $table->string('nom');
            $table->timestamps();
        });

        // Table des centres
        Schema::create('centres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ville_id')->constrained('villes');
            $table->string('nom');
            $table->text('adresse');
            $table->string('telephone');
            $table->string('email');
            $table->timestamps();
        });

        // Table des niveaux
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('ordre');
            $table->timestamps();
        });

        // Table des cours
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('niveau_id')->constrained('niveaux');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre');
            $table->boolean('statut')->default(true);
            $table->timestamps();
        });

        // Table des sections de cours
        Schema::create('sections_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre');
            $table->timestamps();
        });

        // Table des contenus de section
        Schema::create('contenus_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections_cours');
            $table->string('type')->default(ContentType::TEXTE->value);
            $table->text('contenu');
            $table->integer('ordre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenus_sections');
        Schema::dropIfExists('sections_cours');
        Schema::dropIfExists('cours');
        Schema::dropIfExists('niveaux');
        Schema::dropIfExists('centres');
        Schema::dropIfExists('villes');
        Schema::dropIfExists('pays');
    }
};
