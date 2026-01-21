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
        Schema::table('projets', function (Blueprint $table) {
            $table->dropColumn([
                'isduplex',
                'isterrains',
                'isvillabase',
                'isappartement',
                'prix_terrains',
                'prix_duplex',
                'prix_villa',
                'prix_appartement',
                'pourcentage_apport',
                'frais_souscription'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->boolean('isduplex')->default(false);
            $table->boolean('isterrains')->default(false);
            $table->boolean('isvillabase')->default(false);
            $table->boolean('isappartement')->default(false);
            $table->decimal('prix_terrains', 10, 2)->nullable();
            $table->decimal('prix_duplex', 10, 2)->nullable();
            $table->decimal('prix_villa', 10, 2)->nullable();
            $table->decimal('prix_appartement', 10, 2)->nullable();
            $table->decimal('pourcentage_apport', 5, 2)->nullable();
            $table->integer('frais_souscription')->nullable();
        });
    }
};
