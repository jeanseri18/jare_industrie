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
            // Renommer les colonnes temporaires vers les bons noms finaux
            if (Schema::hasColumn('bien_immobiliers', 'terrasse_carrelee_temp')) {
                $table->renameColumn('terrasse_carrelee_temp', 'terrasse_carrelee');
            }
            if (Schema::hasColumn('bien_immobiliers', 'grand_sejour_carrele_temp')) {
                $table->renameColumn('grand_sejour_carrele_temp', 'grand_sejour_carrele');
            }
            if (Schema::hasColumn('bien_immobiliers', 'chambre_principale_placards_temp')) {
                $table->renameColumn('chambre_principale_placards_temp', 'chambre_principale_placards');
            }
            if (Schema::hasColumn('bien_immobiliers', 'chambre_2_placard_temp')) {
                $table->renameColumn('chambre_2_placard_temp', 'chambre_2_placard');
            }
            if (Schema::hasColumn('bien_immobiliers', 'wc_visiteur_carrele_temp')) {
                $table->renameColumn('wc_visiteur_carrele_temp', 'wc_visiteur_carrele');
            }
            if (Schema::hasColumn('bien_immobiliers', 'grande_cuisine_carrelee_temp')) {
                $table->renameColumn('grande_cuisine_carrelee_temp', 'grande_cuisine_carrelee');
            }
            if (Schema::hasColumn('bien_immobiliers', 'buanderie_temp')) {
                $table->renameColumn('buanderie_temp', 'buanderie');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            // Ne rien faire dans le down - on garde les nouveaux noms
        });
    }
};
