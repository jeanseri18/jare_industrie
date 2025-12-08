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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('souscription_id')->constrained()->onDelete('cascade');
            $table->string('numero_paiement')->unique();
            $table->enum('type', ['frais_souscription', 'contribution_initiale', 'echeance', 'final', 'tempérament']);
            $table->decimal('montant', 12, 2);
            $table->date('date_paiement')->nullable();
            $table->date('date_echeance')->nullable();
            $table->enum('statut', ['en_attente', 'paye', 'annule', 'rembourse'])->default('en_attente');
            $table->string('methode_paiement')->nullable();
            $table->string('reference_paiement')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('est_tempérament')->default(false);
            $table->decimal('montant_total_tempérament', 12, 2)->nullable();
            $table->integer('nombre_tranches')->default(1);
            $table->decimal('montant_tranche', 12, 2)->nullable();
            $table->enum('statut_tempérament', ['en_cours', 'soldé', 'annulé'])->nullable();
            $table->string('recu_paiement')->nullable();
            $table->timestamps();
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
