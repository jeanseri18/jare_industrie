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
        Schema::create('bien_immobiliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idprojet');
            $table->string('titre');
            $table->integer('nbre_piece')->nullable();
            $table->integer('nbre_salon')->nullable();
            $table->integer('nbre_douche')->nullable();
            $table->integer('nbre_cuisine')->nullable();
            $table->integer('nbre_placegarage')->nullable();
            $table->decimal('surface_habitable', 10, 2)->nullable();
            $table->integer('nbre_salle_bain')->nullable();
            $table->integer('nbre_etage')->nullable();
            $table->enum('type', ['etage', 'duplex', 'villa basse', 'appartement', 'terrain', 'villa +R1', 'autre']);
            $table->decimal('prix', 15, 2);
            $table->integer('frais_souscription')->nullable();
            $table->decimal('pourcentage_apport', 5, 2)->nullable();
            $table->boolean('garage')->default(false);
            $table->boolean('piscine')->default(false);
            $table->boolean('terasse')->default(false);
            $table->boolean('jardin')->default(false);
            $table->boolean('dependance')->default(false);
            $table->timestamps();
            
            $table->foreign('idprojet')->references('id')->on('projets')->onDelete('cascade');
            $table->index('idprojet');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bien_immobiliers');
    }
};
