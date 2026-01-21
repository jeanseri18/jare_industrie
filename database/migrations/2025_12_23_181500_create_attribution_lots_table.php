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
        Schema::create('attribution_lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idProjet');
            $table->unsignedBigInteger('id_souscription')->nullable();
            $table->string('type_logement');
            $table->string('numero_page_guide');
            $table->string('lot');
            $table->string('ilot');
            $table->string('numero_villa');
            $table->text('observations_internes')->nullable();
            $table->timestamps();
            
            $table->foreign('idProjet')->references('id')->on('projets')->onDelete('cascade');
            $table->foreign('id_souscription')->references('id')->on('souscriptions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribution_lots');
    }
};