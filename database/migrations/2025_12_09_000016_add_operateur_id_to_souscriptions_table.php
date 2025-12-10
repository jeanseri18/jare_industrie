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
            if (!Schema::hasColumn('souscriptions', 'operateur_id')) {
                $table->unsignedBigInteger('operateur_id')->nullable()->after('id');
                $table->foreign('operateur_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('souscriptions', 'operateur_id')) {
                $table->dropForeign(['operateur_id']);
                $table->dropColumn('operateur_id');
            }
        });
    }
};