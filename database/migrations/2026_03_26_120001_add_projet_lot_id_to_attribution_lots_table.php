<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attribution_lots', function (Blueprint $table) {
            $table->unsignedBigInteger('projet_lot_id')->nullable()->after('id_souscription');
            $table->foreign('projet_lot_id')->references('id')->on('projet_lots')->onDelete('set null');
            $table->unique('projet_lot_id', 'attribution_lots_projet_lot_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('attribution_lots', function (Blueprint $table) {
            $table->dropForeign(['projet_lot_id']);
            $table->dropUnique('attribution_lots_projet_lot_id_unique');
            $table->dropColumn('projet_lot_id');
        });
    }
};
