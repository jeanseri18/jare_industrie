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
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('localisation', 255)->nullable();
            $table->decimal('superficie', 10, 2)->nullable()->comment('en m2');
            $table->boolean('isduplex')->default(false)->comment('est-ce un duplex ?');
            $table->boolean('isterrains')->default(false)->comment('est-ce un terrain ?');
            $table->boolean('isvillabase')->default(false)->comment('est-ce une villa ?');
            $table->boolean('isappartement')->default(false)->comment('est-ce un appartement ?');
            $table->decimal('prix_terrains', 12, 2)->nullable()->comment('prix terrain');
            $table->decimal('prix_duplex', 12, 2)->nullable()->comment('prix duplex');
            $table->decimal('prix_villa', 12, 2)->nullable()->comment('prix villa');
            $table->decimal('prix_appartement', 12, 2)->nullable()->comment('prix appartement');
            $table->integer('nb_logements')->nullable();
            $table->tinyInteger('pourcentage_apport')->comment('ex: 10');
            $table->integer('frais_souscription')->comment('FCFA');
            $table->boolean('est_actif')->default(true);
            $table->boolean('est_mutuelle')->default(false)->comment('projet dédié mutuelle ?');
            $table->unsignedBigInteger('mutuelle_id')->nullable();
            $table->unsignedBigInteger('cree_par');
            $table->timestamps();
            
            $table->foreign('cree_par')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};