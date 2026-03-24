<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('souscriptions', 'statut_correction')) {
                if (Schema::hasColumn('souscriptions', 'statut')) {
                    $table->string('statut_correction', 20)->default('pas_corrige')->after('statut');
                } else {
                    $table->string('statut_correction', 20)->default('pas_corrige');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('souscriptions', 'statut_correction')) {
                $table->dropColumn('statut_correction');
            }
        });
    }
};

