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
        Schema::create('apports_initiaux', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_souscription');
            $table->unsignedBigInteger('id_projet');
            $table->decimal('montant', 10, 2);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('montant_reste', 10, 2)->default(0);
            $table->unsignedBigInteger('id_comptable');
            $table->timestamps();

            $table->foreign('id_souscription')->references('id')->on('souscriptions')->onDelete('cascade');
            $table->foreign('id_projet')->references('id')->on('projets')->onDelete('cascade');
            $table->foreign('id_comptable')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apports_initiaux');
    }
};