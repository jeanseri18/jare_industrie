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
            if (!Schema::hasColumn('souscriptions', 'statut')) {
                $table->enum('statut', ['en_attente', 'valide', 'a_corriger', 'refusee', 'annulee'])
                      ->default('en_attente')
                      ->after('frais_souscription');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('souscriptions', 'statut')) {
                $table->dropColumn('statut');
            }
        });
    }
};