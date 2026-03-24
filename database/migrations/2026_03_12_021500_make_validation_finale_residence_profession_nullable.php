<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE validation_finale MODIFY COLUMN lieu_residence VARCHAR(255) NULL");
        DB::statement("ALTER TABLE validation_finale MODIFY COLUMN profession VARCHAR(255) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE validation_finale MODIFY COLUMN lieu_residence VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE validation_finale MODIFY COLUMN profession VARCHAR(255) NOT NULL");
    }
};

