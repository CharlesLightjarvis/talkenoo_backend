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
        Schema::table('contenus_sections', function (Blueprint $table) {
            // Ajout des colonnes manquantes si elles n'existent pas déjà
            if (!Schema::hasColumn('contenus_sections', 'titre')) {
                $table->string('titre')->after('section_id');
            }
            if (!Schema::hasColumn('contenus_sections', 'description')) {
                $table->text('description')->nullable()->after('titre');
            }
            if (!Schema::hasColumn('contenus_sections', 'url_contenu')) {
                $table->string('url_contenu')->nullable()->after('contenu');
            }
            if (!Schema::hasColumn('contenus_sections', 'duree')) {
                $table->integer('duree')->default(0)->after('url_contenu');
            }
            if (!Schema::hasColumn('contenus_sections', 'statut')) {
                $table->boolean('statut')->default(true)->after('ordre');
            }
            
            // Renommer la colonne type en type_contenu si elle existe
            if (Schema::hasColumn('contenus_sections', 'type') && !Schema::hasColumn('contenus_sections', 'type_contenu')) {
                $table->renameColumn('type', 'type_contenu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contenus_sections', function (Blueprint $table) {
            $table->dropColumn(['titre', 'description', 'url_contenu', 'duree', 'statut']);
            if (Schema::hasColumn('contenus_sections', 'type_contenu')) {
                $table->renameColumn('type_contenu', 'type');
            }
        });
    }
};
