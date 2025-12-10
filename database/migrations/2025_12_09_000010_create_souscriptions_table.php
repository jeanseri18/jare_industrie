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
        Schema::create('souscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique()->comment('SDB-2025-XXXXXX');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('projet_id');
            $table->string('type_logement', 50)->comment('Villa basse, Duplex...');
            $table->tinyInteger('nb_pieces')->nullable();
            $table->decimal('prix_logement', 12, 2);
            $table->decimal('apport_initial', 12, 2);
            $table->decimal('frais_dossier', 10, 2);
            $table->decimal('reste_a_payer', 12, 2);
            $table->enum('statut', ['NOUVEAU', 'FRAIS_OK', 'APPORT_OK', 'RESERVE', 'SOLD', 'ATTRIBUE', 'CLOTURE'])->default('NOUVEAU');
            $table->unsignedBigInteger('operateur_id')->comment('saisie opérateur');
            $table->unsignedBigInteger('responsable_commercial_id')->nullable();
            $table->dateTime('soumis_at')->nullable();
            $table->dateTime('cree_at')->nullable();
            $table->date('date_debut_contrat')->nullable()->comment('date de début de contrat');
            $table->date('date_fin_contrat')->nullable()->comment('date de fin de contrat');
            $table->enum('mode_paiement', ['ESPECES', 'VIREMENT', 'MOBILE_MONEY', 'TEMPERAMENT', 'CREDIT_BANCAIRE', 'CHEQUE', 'prelevement', 'transfer'])->nullable()->comment('mode de paiement préféré');
            $table->timestamps();
            
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('projet_id')->references('id')->on('projets');
            $table->foreign('operateur_id')->references('id')->on('users');
            $table->foreign('responsable_commercial_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('souscriptions');
    }
};