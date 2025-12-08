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
        Schema::create('mutuelles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('nom_mutuelle');
            $table->string('registre_commerce')->unique();
            $table->string('nif')->unique();
            $table->text('adresse_siege');
            $table->string('telephone_mutuelle');
            $table->string('email_mutuelle');
            $table->string('president_nom');
            $table->string('president_telephone');
            $table->date('date_creation');
            $table->integer('nombre_membres')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutuelles');
    }
};
