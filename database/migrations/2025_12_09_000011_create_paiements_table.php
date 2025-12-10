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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_id')->comment('lien vers la souscription');
            $table->enum('type', ['FRAIS_DOSSIER', 'APPORT', 'PROJET']);
            $table->decimal('montant', 10, 2);
            $table->enum('mode', ['ESPECES', 'VIREMENT', 'MOBILE_MONEY', 'TEMPERAMENT', 'CREDIT_BANCAIRE']);
            $table->string('reference', 100)->nullable()->comment('numéro reçu, ref banque, ID MM...');
            $table->date('date_paiement');
            $table->unsignedBigInteger('comptable_id')->comment('utilisateur Comptabilité ayant saisi');
            $table->dateTime('valide_at')->nullable();
            $table->dateTime('cree_at')->nullable();
            $table->timestamps();
            
            $table->foreign('dossier_id')->references('id')->on('souscriptions');
            $table->foreign('comptable_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};