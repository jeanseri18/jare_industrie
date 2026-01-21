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
        Schema::table('mutuelles', function (Blueprint $table) {
            // Add new contact fields
            $table->string('site_web')->nullable()->after('description');
            $table->string('nom_contact')->nullable()->after('site_web');
            $table->string('telephone_contact')->nullable()->after('nom_contact');
            $table->string('email_contact')->nullable()->after('telephone_contact');
            
            // Remove old fields
            $table->dropColumn(['valeur_du_bien', 'apport_initial']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutuelles', function (Blueprint $table) {
            // Add back the old fields
            $table->decimal('valeur_du_bien', 12, 2)->comment('ex: 25000000 FCFA')->after('description');
            $table->decimal('apport_initial', 12, 2)->comment('ex: 5000000 FCFA')->after('taux_reduction');
            
            // Remove the new contact fields
            $table->dropColumn(['site_web', 'nom_contact', 'telephone_contact', 'email_contact']);
        });
    }
};