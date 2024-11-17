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
        Schema::table('sections_cours', function (Blueprint $table) {
            $table->boolean('statut')->default(true)->after('ordre');
            $table->integer('duree_estimee')->default(0)->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections_cours', function (Blueprint $table) {
            $table->dropColumn(['statut', 'duree_estimee']);
        });
    }
};
