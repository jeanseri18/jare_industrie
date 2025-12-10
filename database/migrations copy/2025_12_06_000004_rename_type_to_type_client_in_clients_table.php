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
        Schema::table('clients', function (Blueprint $table) {
            // Vérifier si la colonne 'type' existe avant de la renommer
            if (Schema::hasColumn('clients', 'type')) {
                $table->renameColumn('type', 'type_client');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Vérifier si la colonne 'type_client' existe avant de la renommer
            if (Schema::hasColumn('clients', 'type_client')) {
                $table->renameColumn('type_client', 'type');
            }
        });
    }
};