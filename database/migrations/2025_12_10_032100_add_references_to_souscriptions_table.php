<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->string('ref_souscription')->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->dropColumn('ref_souscription');
        });
    }
};