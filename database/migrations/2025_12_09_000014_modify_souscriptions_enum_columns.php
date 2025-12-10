<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier et modifier la colonne type_logement avec les nouvelles valeurs enum
        if (Schema::hasColumn('souscriptions', 'type_logement')) {
            DB::statement("ALTER TABLE souscriptions MODIFY COLUMN type_logement ENUM('villa_basse_3p', 'villa_duplex_4p', 'appartement_3p', 'appartement_4p') NOT NULL");
        }
        
        // Vérifier et modifier la colonne mode_paiement avec les nouvelles valeurs enum
        if (Schema::hasColumn('souscriptions', 'mode_paiement')) {
            DB::statement("ALTER TABLE souscriptions MODIFY COLUMN mode_paiement ENUM('credit_bancaire', 'temperament', 'cash', 'prelevement_source', 'virement') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux anciennes valeurs enum
        if (Schema::hasColumn('souscriptions', 'type_logement')) {
            DB::statement("ALTER TABLE souscriptions MODIFY COLUMN type_logement VARCHAR(50) NOT NULL");
        }
        if (Schema::hasColumn('souscriptions', 'mode_paiement')) {
            DB::statement("ALTER TABLE souscriptions MODIFY COLUMN mode_paiement VARCHAR(50) NOT NULL");
        }
    }
};