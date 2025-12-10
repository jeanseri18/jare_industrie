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
        Schema::table('clients', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            if (Schema::hasColumn('clients', 'nom')) {
                $table->dropColumn('nom');
            }
            if (Schema::hasColumn('clients', 'prenom')) {
                $table->dropColumn('prenom');
            }
            if (Schema::hasColumn('clients', 'adresse')) {
                $table->dropColumn('adresse');
            }
            if (Schema::hasColumn('clients', 'situation_familiale')) {
                $table->dropColumn('situation_familiale');
            }
            if (Schema::hasColumn('clients', 'categorie')) {
                $table->dropColumn('categorie');
            }
            if (Schema::hasColumn('clients', 'nature_piece_identite')) {
                $table->dropColumn('nature_piece_identite');
            }
            if (Schema::hasColumn('clients', 'numero_piece_identite')) {
                $table->dropColumn('numero_piece_identite');
            }

            // Ajouter les nouvelles colonnes
            if (!Schema::hasColumn('clients', 'nom_prenom')) {
                $table->string('nom_prenom', 255)->after('id');
            }
            if (!Schema::hasColumn('clients', 'lieu_naissance')) {
                $table->string('lieu_naissance', 255)->after('date_naissance')->nullable();
            }
            if (!Schema::hasColumn('clients', 'categorie_client')) {
                $table->enum('categorie_client', ['individuel', 'association', 'syndicat', 'diaspora', 'mutuelle'])->after('nationalite');
            }
            if (!Schema::hasColumn('clients', 'situation_matrimoniale')) {
                $table->enum('situation_matrimoniale', ['celibataire', 'marie', 'divorce', 'veuf'])->after('nombre_enfants');
            }
            if (!Schema::hasColumn('clients', 'salaire_mensuel')) {
                $table->bigInteger('salaire_mensuel')->after('telephone')->nullable();
            }
            if (!Schema::hasColumn('clients', 'nature_piece')) {
                $table->enum('nature_piece', ['cni', 'passeport', 'carte_consulaire', 'id'])->after('salaire_mensuel')->nullable();
            }
            if (!Schema::hasColumn('clients', 'numero_piece')) {
                $table->string('numero_piece', 100)->after('nature_piece')->nullable();
            }
            if (!Schema::hasColumn('clients', 'fichier_piece')) {
                $table->string('fichier_piece', 255)->after('numero_piece')->nullable();
            }

            // Modifier les colonnes existantes
            if (Schema::hasColumn('clients', 'date_naissance')) {
                // D'abord rendre la colonne nullable temporairement
                $table->date('date_naissance')->nullable()->change();
                
                // Mettre à jour les valeurs invalides en plusieurs étapes
                DB::statement("UPDATE clients SET date_naissance = NULL WHERE TRIM(date_naissance) = ''");
                DB::statement("UPDATE clients SET date_naissance = NULL WHERE date_naissance = '0000-00-00'");
                DB::statement("UPDATE clients SET date_naissance = '2000-01-01' WHERE date_naissance IS NULL");
                
                // Ensuite rendre la colonne non nullable
                $table->date('date_naissance')->nullable(false)->change();
            }
            if (Schema::hasColumn('clients', 'nationalite')) {
                $table->string('nationalite', 100)->nullable(false)->change();
            }
            if (Schema::hasColumn('clients', 'telephone')) {
                $table->string('telephone', 50)->nullable()->change();
            }
            if (Schema::hasColumn('clients', 'email')) {
                $table->string('email', 255)->nullable()->change();
            }
            if (Schema::hasColumn('clients', 'nombre_enfants')) {
                $table->integer('nombre_enfants')->default(0)->change();
            }
            if (Schema::hasColumn('clients', 'ayant_droit')) {
                $table->text('ayant_droit')->nullable()->change();
            }
        });

        // Ajouter la contrainte de clé étrangère dans la table souscriptions
        Schema::table('souscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('souscriptions', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
                $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('souscriptions', 'client_id')) {
                $table->dropForeign(['client_id']);
                $table->dropColumn('client_id');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            if (Schema::hasColumn('clients', 'nom_prenom')) {
                $table->dropColumn('nom_prenom');
            }
            if (Schema::hasColumn('clients', 'lieu_naissance')) {
                $table->dropColumn('lieu_naissance');
            }
            if (Schema::hasColumn('clients', 'categorie_client')) {
                $table->dropColumn('categorie_client');
            }
            if (Schema::hasColumn('clients', 'situation_matrimoniale')) {
                $table->dropColumn('situation_matrimoniale');
            }
            if (Schema::hasColumn('clients', 'salaire_mensuel')) {
                $table->dropColumn('salaire_mensuel');
            }
            if (Schema::hasColumn('clients', 'nature_piece')) {
                $table->dropColumn('nature_piece');
            }
            if (Schema::hasColumn('clients', 'numero_piece')) {
                $table->dropColumn('numero_piece');
            }
            if (Schema::hasColumn('clients', 'fichier_piece')) {
                $table->dropColumn('fichier_piece');
            }

            // Restaurer les anciennes colonnes
            if (!Schema::hasColumn('clients', 'nom')) {
                $table->string('nom', 150)->nullable();
            }
            if (!Schema::hasColumn('clients', 'prenom')) {
                $table->string('prenom', 150)->nullable();
            }
            if (!Schema::hasColumn('clients', 'adresse')) {
                $table->string('adresse', 255)->nullable();
            }
            if (!Schema::hasColumn('clients', 'categorie')) {
                $table->enum('categorie', ['Individuel', 'Diaspora', 'Association', 'Mutuelle', 'Banque']);
            }
            if (!Schema::hasColumn('clients', 'situation_familiale')) {
                $table->enum('situation_familiale', ['Célibataire', 'Divorcé', 'Marié(e)', 'En couple'])->nullable();
            }
            if (!Schema::hasColumn('clients', 'nature_piece_identite')) {
                $table->enum('nature_piece_identite', ['Passeport', 'CNI', 'carte consulat', 'Permis de conduire', 'Carte d\'identité'])->nullable();
            }
            if (!Schema::hasColumn('clients', 'numero_piece_identite')) {
                $table->string('numero_piece_identite', 50)->nullable();
            }
        });
    }
};