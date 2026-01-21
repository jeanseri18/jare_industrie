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
            // Supprimer les colonnes temporaires
            if (Schema::hasColumn('bien_immobiliers', 'terrasse_carrelee_temp')) {
                $table->dropColumn('terrasse_carrelee_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'grand_sejour_carrele_temp')) {
                $table->dropColumn('grand_sejour_carrele_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'chambre_principale_placards_temp')) {
                $table->dropColumn('chambre_principale_placards_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'chambre_2_placard_temp')) {
                $table->dropColumn('chambre_2_placard_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'wc_visiteur_carrele_temp')) {
                $table->dropColumn('wc_visiteur_carrele_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'grande_cuisine_carrelee_temp')) {
                $table->dropColumn('grande_cuisine_carrelee_temp');
            }
            if (Schema::hasColumn('bien_immobiliers', 'buanderie_temp')) {
                $table->dropColumn('buanderie_temp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            // Ne rien faire dans le down
        });
    }
};
