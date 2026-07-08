<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projet_ilots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_projet');
            $table->string('ilot', 50);
            $table->decimal('superficie_totale', 12, 2)->nullable()->comment('Surface totale de l’îlot (référence) — les lots ont chacun leur superficie');
            $table->timestamps();

            $table->foreign('id_projet')->references('id')->on('projets')->onDelete('cascade');
            $table->unique(['id_projet', 'ilot'], 'projet_ilots_projet_ilot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projet_ilots');
    }
};
