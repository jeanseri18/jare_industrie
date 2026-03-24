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
        Schema::table('attribution_lots', function (Blueprint $table) {
            $table->decimal('superficie', 8, 2)->nullable()->after('numero_villa');
            // Ensure uniqueness for ilot + lot + numero_villa (villa number usually equals lot, but just in case)
            // If numero_villa = Lot, then ilot + lot must be unique.
            // Let's add a unique constraint on ilot + lot if not exists
            // Since the user says "Numéro = Lot", it implies Lot is the unique identifier within an Ilot.
            // We will add a unique index on (idProjet, ilot, lot) to prevent double attribution in the same project.
             $table->unique(['idProjet', 'ilot', 'lot'], 'unique_attribution_projet_ilot_lot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribution_lots', function (Blueprint $table) {
            $table->dropColumn('superficie');
            $table->dropUnique('unique_attribution_projet_ilot_lot');
        });
    }
};
