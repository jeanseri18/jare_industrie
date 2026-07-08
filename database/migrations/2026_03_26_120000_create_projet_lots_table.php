<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projet_lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_projet');
            $table->unsignedBigInteger('bien_immobilier_id')->nullable();
            $table->string('type_logement', 100)->nullable()->comment('Si renseigné, réservé à ce type (ex. Villa)');
            $table->string('ilot', 50);
            $table->string('lot', 50);
            $table->string('numero_page_guide', 50)->nullable();
            $table->decimal('superficie', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_projet')->references('id')->on('projets')->onDelete('cascade');
            $table->foreign('bien_immobilier_id')->references('id')->on('bien_immobiliers')->onDelete('set null');
            $table->unique(['id_projet', 'ilot', 'lot'], 'projet_lots_projet_ilot_lot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projet_lots');
    }
};
