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
            $table->string('nom', 100);
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->decimal('valeur_du_bien', 12, 2)->comment('ex: 25000000 FCFA');
            $table->decimal('taux_reduction', 5, 2)->comment('ex: 5.00');
            $table->decimal('apport_initial', 12, 2)->comment('ex: 5000000 FCFA');
            $table->boolean('est_active')->default(true);
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
        Schema::dropIfExists('mutuelles');
    }
};