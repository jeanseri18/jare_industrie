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
        Schema::table('souscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('souscriptions', 'mode_paiement')) {
                $table->string('mode_paiement')->after('type_logement');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('souscriptions', 'mode_paiement')) {
                $table->dropColumn('mode_paiement');
            }
        });
    }
};
