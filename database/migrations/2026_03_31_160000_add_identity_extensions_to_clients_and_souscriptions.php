<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'profession')) {
                $table->string('profession', 255)->nullable();
            }
            if (! Schema::hasColumn('clients', 'entreprise')) {
                $table->string('entreprise', 255)->nullable();
            }
            if (! Schema::hasColumn('clients', 'lieu_residence')) {
                $table->string('lieu_residence', 255)->nullable();
            }
            if (! Schema::hasColumn('clients', 'ville')) {
                $table->string('ville', 120)->nullable();
            }
            if (! Schema::hasColumn('clients', 'pays')) {
                $table->string('pays', 120)->nullable();
            }
            if (! Schema::hasColumn('clients', 'date_delivrance_piece')) {
                $table->date('date_delivrance_piece')->nullable();
            }
            if (! Schema::hasColumn('clients', 'date_expiration_piece')) {
                $table->date('date_expiration_piece')->nullable();
            }
        });

        Schema::table('souscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('souscriptions', 'profession')) {
                $table->string('profession', 255)->nullable();
            }
            if (! Schema::hasColumn('souscriptions', 'entreprise')) {
                $table->string('entreprise', 255)->nullable();
            }
            if (! Schema::hasColumn('souscriptions', 'lieu_residence')) {
                $table->string('lieu_residence', 255)->nullable();
            }
            if (! Schema::hasColumn('souscriptions', 'ville')) {
                $table->string('ville', 120)->nullable();
            }
            if (! Schema::hasColumn('souscriptions', 'pays')) {
                $table->string('pays', 120)->nullable();
            }
            if (! Schema::hasColumn('souscriptions', 'date_delivrance_piece')) {
                $table->date('date_delivrance_piece')->nullable();
            }
            if (! Schema::hasColumn('souscriptions', 'date_expiration_piece')) {
                $table->date('date_expiration_piece')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            $cols = ['profession', 'entreprise', 'lieu_residence', 'ville', 'pays', 'date_delivrance_piece', 'date_expiration_piece'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('souscriptions', $c)) {
                    $table->dropColumn($c);
                }
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            $cols = ['profession', 'entreprise', 'lieu_residence', 'ville', 'pays', 'date_delivrance_piece', 'date_expiration_piece'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('clients', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
