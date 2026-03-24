<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update paiements table only, as souscriptions is VARCHAR and has mixed data
        // We add PRELEVEMENT_SOURCE to the allowed enum values and remove MOBILE_MONEY
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode ENUM('ESPECES', 'VIREMENT', 'TEMPERAMENT', 'CREDIT_BANCAIRE', 'PRELEVEMENT_SOURCE')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement("ALTER TABLE paiements MODIFY COLUMN mode ENUM('ESPECES', 'VIREMENT', 'MOBILE_MONEY', 'TEMPERAMENT', 'CREDIT_BANCAIRE')");
    }
};
