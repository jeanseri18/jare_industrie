<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Projet;
use App\Models\Souscription;
use App\Models\Paiement;
use App\Models\ActivityLog;
use App\Models\DatabaseBackup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        $this->command->info('Clearing existing data...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \App\Models\DatabaseBackup::truncate();
        \App\Models\ActivityLog::truncate();
        \App\Models\Projet::truncate();
        \App\Models\Client::truncate();
        \App\Models\User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Database cleared successfully!');
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrateur Système',
            'email' => 'admin@jareindustrie.ht',
            'password' => Hash::make('admin123'),
            'role' => 'Admin Technique'
        ]);
        $admin->update(['telephone' => '509-3712-3456', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create DG User
        $dg = User::create([
            'name' => 'Directeur Général',
            'email' => 'dg@jareindustrie.ht',
            'password' => Hash::make('dg123456'),
            'role' => 'DG'
        ]);
        $dg->update(['telephone' => '509-3723-4567', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create Chef Commercial
        $chefCommercial = User::create([
            'name' => 'Chef Commercial',
            'email' => 'commercial@jareindustrie.ht',
            'password' => Hash::make('commercial123'),
            'role' => 'Chef Commercial'
        ]);
        $chefCommercial->update(['telephone' => '509-3734-5678', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create Comptable
        $comptable = User::create([
            'name' => 'Comptable',
            'email' => 'comptable@jareindustrie.ht',
            'password' => Hash::make('comptable123'),
            'role' => 'Comptable'
        ]);
        $comptable->update(['telephone' => '509-3745-6789', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create Opérateur de saisie
        $operateur = User::create([
            'name' => 'Opérateur de saisie',
            'email' => 'operateur@jareindustrie.ht',
            'password' => Hash::make('operateur123'),
            'role' => 'Operateur'
        ]);
        $operateur->update(['telephone' => '509-3756-7890', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create some test clients
        $clients = [
            [
                'categorie' => 'Individuel',
                'nom' => 'Jean',
                'prenom' => 'Pierre',
                'email' => 'jean.pierre@email.com',
                'telephone' => '509-3401-2345',
                'adresse' => 'Delmas 33, Port-au-Prince',
                'date_naissance' => '1985-03-15',
                'nationalite' => 'Haïtienne',
                'situation_familiale' => 'Marié(e)',
                'nombre_enfants' => 2,
                'nature_piece_identite' => 'CNI',
                'numero_piece_identite' => 'CI-1985-0315'
            ],
            [
                'categorie' => 'Mutuelle',
                'nom' => 'Mutuelle des Enseignants',
                'prenom' => 'du Primaire',
                'email' => 'mutuelle.enseignants@email.com',
                'telephone' => '509-3402-3456',
                'adresse' => 'Route de Delmas, Port-au-Prince',
                'nationalite' => 'Haïtienne',
                'nature_piece_identite' => 'Passeport',
                'numero_piece_identite' => 'PA-2023-001'
            ]
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        // Create some test projets
        $projets = [
            [
                'nom' => 'Résidence Belle-Vue',
                'localisation' => 'Delmas 75, Pétion-Ville',
                'superficie' => 5000,
                'isduplex' => true,
                'isterrains' => false,
                'isvillabase' => true,
                'isappartement' => true,
                'prix_duplex' => 2500000,
                'prix_villa' => 3500000,
                'prix_appartement' => 1800000,
                'nb_logements' => 50,
                'pourcentage_apport' => 10,
                'frais_souscription' => 50000,
                'est_actif' => true,
                'est_mutuelle' => false,
                'cree_par' => $admin->id
            ],
            [
                'nom' => 'Complexe Commercial Carrefour',
                'localisation' => 'Route de Carrefour',
                'superficie' => 3000,
                'isduplex' => false,
                'isterrains' => true,
                'isvillabase' => false,
                'isappartement' => false,
                'prix_terrains' => 1500000,
                'nb_logements' => 30,
                'pourcentage_apport' => 15,
                'frais_souscription' => 75000,
                'est_actif' => true,
                'est_mutuelle' => false,
                'cree_par' => $dg->id
            ]
        ];

        foreach ($projets as $projetData) {
            Projet::create($projetData);
        }

        // Create some activity logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'description' => 'Connexion réussie de l\'administrateur',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);

        ActivityLog::create([
            'user_id' => $dg->id,
            'action' => 'created',
            'description' => 'Création d\'un nouveau client : Jean Pierre',
            'model_type' => 'App\Models\Client',
            'model_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);

        // Create some database backup records
        DatabaseBackup::create([
            'user_id' => $admin->id,
            'filename' => 'backup_full_2024-12-06_10-30-00.sql.gz',
            'type' => 'full',
            'size' => 5242880,
            'path' => 'backups/backup_full_2024-12-06_10-30-00.sql.gz',
            'tables' => 'all',
            'compressed' => true,
            'status' => 'completed',
            'started_at' => now()->subDays(2),
            'completed_at' => now()->subDays(2)->addMinutes(5)
        ]);

        $this->command->info('Base de données initialisée avec succès!');
        $this->command->info('Utilisateurs de test créés :');
        $this->command->info('- Admin: admin@jareindustrie.ht / mot de passe: admin123');
        $this->command->info('- DG: dg@jareindustrie.ht / mot de passe: dg123456');
        $this->command->info('- Chef Commercial: commercial@jareindustrie.ht / mot de passe: commercial123');
        $this->command->info('- Comptable: comptable@jareindustrie.ht / mot de passe: comptable123');
        $this->command->info('- Opérateur: operateur@jareindustrie.ht / mot de passe: operateur123');
    }
}
