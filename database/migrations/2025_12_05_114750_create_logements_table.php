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
        Schema::create('logements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained()->onDelete('cascade');
            $table->string('numero')->unique();
            $table->string('type');
            $table->decimal('superficie', 8, 2);
            $table->integer('nombre_pieces');
            $table->integer('nombre_chambres');
            $table->integer('nombre_salles_bain');
            $table->decimal('prix', 12, 2);
            $table->text('description')->nullable();
            $table->enum('statut', ['disponible', 'reserve', 'attribue', 'paye'])->default('disponible');
            $table->integer('etage')->default(0);
            $table->string('orientation')->nullable();
            $table->boolean('balcon')->default(false);
            $table->boolean('terrasse')->default(false);
            $table->boolean('parking')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logements');
    }
};
