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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['individuel', 'mutuelle', 'individuel-banque']);
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('email');
            $table->string('telephone');
            $table->text('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('pays')->default('Haïti');
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('profession')->nullable();
            $table->decimal('salaire_mensuel', 10, 2)->nullable();
            $table->string('situation_familiale')->nullable();
            $table->integer('nombre_enfants')->default(0);
            $table->string('nom_mutuelle')->nullable();
            $table->string('registre_commerce')->nullable();
            $table->string('nif')->nullable();
            $table->string('statut')->default('actif');
            $table->date('date_adhesion');
            $table->decimal('montant_total_souscription', 10, 2)->default(0);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('montant_restant', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
