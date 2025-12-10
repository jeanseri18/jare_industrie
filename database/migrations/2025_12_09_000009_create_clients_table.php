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
            $table->string('nom', 150);
            $table->string('prenom', 150);
            $table->string('telephone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('nationalite', 50)->nullable();
            $table->string('ayant_droit', 150)->nullable();
            $table->tinyInteger('nombre_enfants')->nullable()->comment('nombre d\'enfants');
            $table->enum('situation_familiale', ['Célibataire', 'Divorcé', 'Marié(e)', 'En couple'])->nullable()->comment('situation familiale');
            $table->string('adresse', 255)->nullable();
            $table->enum('categorie', ['Individuel', 'Diaspora', 'Association', 'Mutuelle', 'Banque']);
            $table->enum('nature_piece_identite', ['Passeport', 'CNI', 'carte consulat', 'Permis de conduire', 'Carte d\'\'identité'])->nullable()->comment('nécessaire si catégorie est Individuel ou Diaspora');
            $table->string('numero_piece_identite', 50)->nullable()->comment('numéro de la pièce d\'identité');
            $table->unsignedBigInteger('mutuelle_id')->nullable();
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