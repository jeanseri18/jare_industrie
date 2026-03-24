<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM for clients
        DB::statement("ALTER TABLE clients MODIFY COLUMN situation_matrimoniale ENUM('celibataire', 'marie', 'divorce', 'veuf', 'concubinage') NULL");
        
        // Update ENUM for souscriptions
        DB::statement("ALTER TABLE souscriptions MODIFY COLUMN situation_matrimoniale ENUM('celibataire', 'marie', 'divorce', 'veuf', 'concubinage') NULL");
    
        Schema::table('clients', function (Blueprint $table) {
            $table->string('nom_conjoint')->nullable()->after('situation_matrimoniale');
            $table->string('telephone_conjoint')->nullable()->after('nom_conjoint');
        });
    
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->string('nom_conjoint')->nullable()->after('situation_matrimoniale');
            $table->string('telephone_conjoint')->nullable()->after('nom_conjoint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $table->dropColumn(['nom_conjoint', 'telephone_conjoint']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['nom_conjoint', 'telephone_conjoint']);
        });

        // Revert ENUMs (WARNING: This might fail if there are 'concubinage' values)
        // We generally avoid reverting ENUM changes that lose data in down() unless necessary.
        // But for completeness:
        // DB::statement("ALTER TABLE clients MODIFY COLUMN situation_matrimoniale ENUM('celibataire', 'marie', 'divorce', 'veuf') NULL");
        // DB::statement("ALTER TABLE souscriptions MODIFY COLUMN situation_matrimoniale ENUM('celibataire', 'marie', 'divorce', 'veuf') NULL");
    }
};
