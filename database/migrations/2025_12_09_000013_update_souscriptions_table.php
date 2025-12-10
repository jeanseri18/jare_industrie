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
        Schema::table('souscriptions', function (Blueprint $table) {
            // Supprimer les clés étrangères existantes (avec vérification SQL)
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Vérifier et supprimer les clés étrangères
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'souscriptions' 
                AND CONSTRAINT_TYPE = 'FOREIGN_KEY'
            ");
            
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE souscriptions DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }
            
            // Supprimer les colonnes existantes (on garde type_logement et mode_paiement)
            $columnsToDrop = [];
            
            if (Schema::hasColumn('souscriptions', 'numero')) {
                $columnsToDrop[] = 'numero';
            }
            if (Schema::hasColumn('souscriptions', 'client_id')) {
                $columnsToDrop[] = 'client_id';
            }
            if (Schema::hasColumn('souscriptions', 'projet_id')) {
                $columnsToDrop[] = 'projet_id';
            }
            if (Schema::hasColumn('souscriptions', 'nb_pieces')) {
                $columnsToDrop[] = 'nb_pieces';
            }
            if (Schema::hasColumn('souscriptions', 'prix_logement')) {
                $columnsToDrop[] = 'prix_logement';
            }
            if (Schema::hasColumn('souscriptions', 'apport_initial')) {
                $columnsToDrop[] = 'apport_initial';
            }
            if (Schema::hasColumn('souscriptions', 'frais_dossier')) {
                $columnsToDrop[] = 'frais_dossier';
            }
            if (Schema::hasColumn('souscriptions', 'reste_a_payer')) {
                $columnsToDrop[] = 'reste_a_payer';
            }
            if (Schema::hasColumn('souscriptions', 'statut')) {
                $columnsToDrop[] = 'statut';
            }
            if (Schema::hasColumn('souscriptions', 'operateur_id')) {
                $columnsToDrop[] = 'operateur_id';
            }
            if (Schema::hasColumn('souscriptions', 'soumis_at')) {
                $columnsToDrop[] = 'soumis_at';
            }
            if (Schema::hasColumn('souscriptions', 'cree_at')) {
                $columnsToDrop[] = 'cree_at';
            }
            if (Schema::hasColumn('souscriptions', 'date_debut_contrat')) {
                $columnsToDrop[] = 'date_debut_contrat';
            }
            if (Schema::hasColumn('souscriptions', 'date_fin_contrat')) {
                $columnsToDrop[] = 'date_fin_contrat';
            }
            if (Schema::hasColumn('souscriptions', 'responsable_commercial_id')) {
                $columnsToDrop[] = 'responsable_commercial_id';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
            
            // Ajouter les nouvelles colonnes selon la structure SQL (avec vérification)
            if (!Schema::hasColumn('souscriptions', 'categorie_client')) {
                $table->enum('categorie_client', ['individuel', 'association', 'syndicat', 'diaspora', 'mutuelle'])->after('id');
            }
            if (!Schema::hasColumn('souscriptions', 'nom_prenom')) {
                $table->string('nom_prenom')->after('categorie_client');
            }
            if (!Schema::hasColumn('souscriptions', 'date_naissance')) {
                $table->date('date_naissance')->after('nom_prenom');
            }
            if (!Schema::hasColumn('souscriptions', 'lieu_naissance')) {
                $table->string('lieu_naissance')->after('date_naissance');
            }
            if (!Schema::hasColumn('souscriptions', 'nationalite')) {
                $table->string('nationalite')->after('lieu_naissance');
            }
            if (!Schema::hasColumn('souscriptions', 'nombre_enfants')) {
                $table->integer('nombre_enfants')->default(0)->after('nationalite');
            }
            if (!Schema::hasColumn('souscriptions', 'ayant_droit')) {
                $table->text('ayant_droit')->nullable()->after('nombre_enfants');
            }
            if (!Schema::hasColumn('souscriptions', 'email')) {
                $table->string('email')->nullable()->after('ayant_droit');
            }
            if (!Schema::hasColumn('souscriptions', 'salaire_mensuel')) {
                $table->bigInteger('salaire_mensuel')->nullable()->after('email');
            }
            if (!Schema::hasColumn('souscriptions', 'situation_matrimoniale')) {
                $table->enum('situation_matrimoniale', ['celibataire', 'marie', 'divorce', 'veuf'])->after('salaire_mensuel');
            }
            
            // Étape 2 : Identité & Programme
            if (!Schema::hasColumn('souscriptions', 'nature_piece')) {
                $table->enum('nature_piece', ['cni', 'passeport', 'carte_consulaire', 'id'])->after('situation_matrimoniale');
            }
            if (!Schema::hasColumn('souscriptions', 'numero_piece')) {
                $table->string('numero_piece')->after('nature_piece');
            }
            if (!Schema::hasColumn('souscriptions', 'fichier_piece')) {
                $table->string('fichier_piece')->nullable()->after('numero_piece');
            }
            if (!Schema::hasColumn('souscriptions', 'programme')) {
                $table->string('programme')->after('fichier_piece');
            }
            if (!Schema::hasColumn('souscriptions', 'duree_contrat_mois')) {
                $table->integer('duree_contrat_mois')->after('programme');
            }
            if (!Schema::hasColumn('souscriptions', 'date_debut')) {
                $table->date('date_debut')->after('duree_contrat_mois');
            }
            if (!Schema::hasColumn('souscriptions', 'date_fin')) {
                $table->date('date_fin')->after('date_debut');
            }
            
            // Résumé financier (on ne touche pas à type_logement et mode_paiement qui existent déjà)
            $afterColumn = Schema::hasColumn('souscriptions', 'mode_paiement') ? 'mode_paiement' : 'date_fin';
            
            if (!Schema::hasColumn('souscriptions', 'valeur_souscription')) {
                $table->bigInteger('valeur_souscription')->default(30000000)->after($afterColumn);
            }
            if (!Schema::hasColumn('souscriptions', 'apport_initial')) {
                $table->bigInteger('apport_initial')->default(3000000)->after('valeur_souscription');
            }
            if (!Schema::hasColumn('souscriptions', 'frais_souscription')) {
                $table->bigInteger('frais_souscription')->default(500000)->after('apport_initial');
            }
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('souscriptions', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropColumn([
                'categorie_client', 'nom_prenom', 'date_naissance', 'lieu_naissance', 
                'nationalite', 'nombre_enfants', 'ayant_droit', 'email', 'salaire_mensuel',
                'situation_matrimoniale', 'nature_piece', 'numero_piece', 'fichier_piece',
                'programme', 'duree_contrat_mois', 'date_debut', 'date_fin', 'valeur_souscription',
                'apport_initial', 'frais_souscription'
            ]);
            
            // Restaurer les anciennes colonnes
            $table->string('numero', 20)->unique()->comment('SDB-2025-XXXXXX');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('projet_id');
            $table->string('type_logement', 50)->comment('Villa basse, Duplex');
            $table->tinyInteger('nb_pieces')->nullable();
            $table->decimal('prix_logement', 12, 2);
            $table->decimal('apport_initial', 12, 2);
            $table->decimal('frais_dossier', 10, 2);
            $table->decimal('reste_a_payer', 12, 2);
            $table->enum('statut', ['NOUVEAU', 'FRAIS_OK', 'APPORT_OK', 'RESERVE', 'SOLD', 'ATTRIBUE', 'CLOTURE'])->default('NOUVEAU');
            $table->unsignedBigInteger('operateur_id')->comment('saisie opérateur');
            $table->unsignedBigInteger('responsable_commercial_id')->nullable();
            $table->dateTime('soumis_at')->nullable();
            $table->dateTime('cree_at')->nullable();
            $table->date('date_debut_contrat')->nullable()->comment('date de début de contrat');
            $table->date('date_fin_contrat')->nullable()->comment('date de fin de contrat');
            $table->enum('mode_paiement', ['ESPECES', 'VIREMENT', 'MOBILE_MONEY', 'TEMPERAMENT', 'CREDIT_BANCAIRE', 'CHEQUE', 'prelevement', 'transfer'])->nullable()->comment('mode de paiement préféré');
            
            // Restaurer les clés étrangères
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('projet_id')->references('id')->on('projets');
            $table->foreign('operateur_id')->references('id')->on('users');
            $table->foreign('responsable_commercial_id')->references('id')->on('users');
        });
    }
};