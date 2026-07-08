<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->string('titre_foncier', 150)->nullable()->after('localisation');
            $table->string('circonscription_fonciere', 150)->nullable()->after('titre_foncier');
        });
    }

    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropColumn(['titre_foncier', 'circonscription_fonciere']);
        });
    }
};

