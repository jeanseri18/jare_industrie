<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attribution_lots', function (Blueprint $table) {
            $table->decimal('surface_batie', 10, 2)->nullable()->after('superficie');
        });
    }

    public function down(): void
    {
        Schema::table('attribution_lots', function (Blueprint $table) {
            $table->dropColumn('surface_batie');
        });
    }
};
