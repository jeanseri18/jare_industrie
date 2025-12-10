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
        Schema::create('lettre_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('logement_id')->constrained()->onDelete('cascade');
            $table->string('numero_attribution')->unique();
            $table->date('date_attribution');
            $table->date('date_signature')->nullable();
            $table->string('fichier');
            $table->foreignId('signe_par')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lettre_attributions');
    }
};
