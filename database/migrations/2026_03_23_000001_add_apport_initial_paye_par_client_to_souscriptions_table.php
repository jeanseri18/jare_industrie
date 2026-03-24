<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->boolean('apport_initial_paye_par_client')->default(true)->after('apport_initial');
        });
    }

    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->dropColumn('apport_initial_paye_par_client');
        });
    }
};
