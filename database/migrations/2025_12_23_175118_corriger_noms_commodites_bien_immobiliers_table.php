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
            // Renommer les colonnes qui ont les mauvais noms
            if (Schema::hasColumn('bien_immobiliers', 'terrasse_non_carrelee')) {
                $table->renameColumn('terrasse_non_carrelee', 'terrasse_carrelee_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'grand_sejour_non_carrele')) {
                $table->renameColumn('grand_sejour_non_carrele', 'grand_sejour_carrele_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'chambre_principale_deux_placards')) {
                $table->renameColumn('chambre_principale_deux_placards', 'chambre_principale_placards_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'chambre_2_un_placard')) {
                $table->renameColumn('chambre_2_un_placard', 'chambre_2_placard_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'wc_visiteur_non_carrele')) {
                $table->renameColumn('wc_visiteur_non_carrele', 'wc_visiteur_carrele_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'grande_cuisine_non_carrelee')) {
                $table->renameColumn('grande_cuisine_non_carrelee', 'grande_cuisine_carrelee_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'buanderie_debarras')) {
                $table->renameColumn('buanderie_debarras', 'buanderie_temp');
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
