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
            $table->boolean('cour_avant')->default(false);
            $table->boolean('cour_arriere')->default(false);
            $table->boolean('terrasse_carrelee')->default(false);
            $table->boolean('grand_sejour_carrele')->default(false);
            $table->boolean('chambre_principale_carrelee')->default(false);
            $table->boolean('chambre_principale_salle_eau')->default(false);
            $table->boolean('chambre_principale_placards')->default(false);
            $table->boolean('chambre_2_carrelee')->default(false);
            $table->boolean('chambre_2_salle_eau')->default(false);
            $table->boolean('chambre_2_placard')->default(false);
            $table->boolean('wc_visiteur_carrele')->default(false);
            $table->boolean('grande_cuisine_carrelee')->default(false);
            $table->boolean('buanderie')->default(false);
            $table->boolean('installation_chauffe_eau')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->dropColumn([
                'cour_avant', 'cour_arriere', 'terrasse_carrelee',
                'grand_sejour_carrele', 'chambre_principale_carrelee',
                'chambre_principale_salle_eau', 'chambre_principale_placards',
                'chambre_2_carrelee', 'chambre_2_salle_eau', 'chambre_2_placard',
                'wc_visiteur_carrele', 'grande_cuisine_carrelee',
                'buanderie', 'installation_chauffe_eau'
            ]);
        });
    }
};
