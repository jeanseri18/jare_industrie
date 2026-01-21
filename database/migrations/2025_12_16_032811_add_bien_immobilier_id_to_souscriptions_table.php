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
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('bien_immobilier_id')->nullable()->after('programme');
            $table->foreign('bien_immobilier_id')->references('id')->on('bien_immobiliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->dropForeign(['bien_immobilier_id']);
            $table->dropColumn('bien_immobilier_id');
        });
    }
};
