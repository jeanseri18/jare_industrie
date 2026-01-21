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
        Schema::create('validation_finale', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idsouscription');
            $table->string('nom_client');
            $table->string('prenom_client');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->string('numero_telephone');
            $table->string('email');
            $table->string('lieu_residence');
            $table->string('profession');
            $table->unsignedBigInteger('idProjet');
            $table->decimal('montant_total_paye', 15, 2);
            $table->date('date_dernier_paiement');
            $table->string('lot');
            $table->string('ilot_attribue');
            $table->timestamps();

            $table->foreign('idsouscription')->references('id')->on('souscriptions')->onDelete('cascade');
            $table->foreign('idProjet')->references('id')->on('projets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_finale');
    }
};