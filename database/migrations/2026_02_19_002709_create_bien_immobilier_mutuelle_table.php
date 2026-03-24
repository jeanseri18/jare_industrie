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
        Schema::create('bien_immobilier_mutuelle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutuelle_id')->constrained()->onDelete('cascade');
            $table->foreignId('bien_immobilier_id')->constrained()->onDelete('cascade');
            $table->decimal('prix_special', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bien_immobilier_mutuelle');
    }
};
