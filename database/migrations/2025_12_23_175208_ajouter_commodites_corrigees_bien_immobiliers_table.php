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
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            // Ajouter les colonnes avec les bons noms
            if (!Schema::hasColumn('bien_immobiliers', 'terrasse_carrelee')) {
                $table->boolean('terrasse_carrelee')->default(false)->after('dependance');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'grand_sejour_carrele')) {
                $table->boolean('grand_sejour_carrele')->default(false)->after('terrasse_carrelee');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'chambre_principale_placards')) {
                $table->boolean('chambre_principale_placards')->default(false)->after('grand_sejour_carrele');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'chambre_2_placard')) {
                $table->boolean('chambre_2_placard')->default(false)->after('chambre_principale_placards');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'wc_visiteur_carrele')) {
                $table->boolean('wc_visiteur_carrele')->default(false)->after('chambre_2_placard');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'grande_cuisine_carrelee')) {
                $table->boolean('grande_cuisine_carrelee')->default(false)->after('wc_visiteur_carrele');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'buanderie')) {
                $table->boolean('buanderie')->default(false)->after('grande_cuisine_carrelee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'terrasse_carrelee',
                'grand_sejour_carrele',
                'chambre_principale_placards',
                'chambre_2_placard',
                'wc_visiteur_carrele',
                'grande_cuisine_carrelee',
                'buanderie'
            ]);
        });
    }
};
