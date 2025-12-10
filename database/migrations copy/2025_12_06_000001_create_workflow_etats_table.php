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
        Schema::create('workflow_etats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscription_id')->constrained()->onDelete('cascade');
            $table->string('phase'); // frais_dossier, apport_initial, attribution, paiement_projet
            $table->enum('statut', ['en_attente', 'valide', 'rejete', 'annule'])->default('en_attente');
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date_traitement')->nullable();
            $table->text('observations')->nullable();
            $table->decimal('montant_requis', 12, 2)->nullable();
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->boolean('accepte_tempérament')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_etats');
    }
};