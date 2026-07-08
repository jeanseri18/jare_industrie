<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Projet;
use App\Models\Organization;
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
        $this->call(OrganizationSeeder::class);
        $orgId = Organization::withoutGlobalScopes()->where('slug', 'jare-industries')->value('id');

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
            'organization_id' => $orgId,
            'name' => 'Administrateur Système',
            'email' => 'admin@jareindustrie.ht',
            'password' => Hash::make('admin123'),
            'role' => 'admin_technique'
        ]);
        $admin->update(['telephone' => '509-3712-3456', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create DG User
        $dg = User::create([
            'organization_id' => $orgId,
            'name' => 'Directeur Général',
            'email' => 'dg@jareindustrie.ht',
            'password' => Hash::make('dg123456'),
            'role' => 'dg'
        ]);
        $dg->update(['telephone' => '509-3723-4567', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create Chef Commercial
        $chefCommercial = User::create([
            'organization_id' => $orgId,
            'name' => 'Chef Commercial',
            'email' => 'commercial@jareindustrie.ht',
            'password' => Hash::make('commercial123'),
            'role' => 'chef_commercial'
        ]);
        $chefCommercial->update(['telephone' => '509-3734-5678', 'adresse' => 'Port-au-Prince, Haïti']);
        
        // Create Directeur Commercial (Dir Com)
        $dirCom = User::create([
            'organization_id' => $orgId,
            'name' => 'Directeur Commercial',
            'email' => 'dircom@jareindustrie.ht',
            'password' => Hash::make('dircom123'),
            'role' => 'chef_commercial'
        ]);
        $dirCom->update(['telephone' => '509-3734-8888', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create Comptable
        $comptable = User::create([
            'organization_id' => $orgId,
            'name' => 'Comptable',
            'email' => 'comptable@jareindustrie.ht',
            'password' => Hash::make('comptable123'),
            'role' => 'comptable'
        ]);
        $comptable->update(['telephone' => '509-3745-6789', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create Opérateur de saisie
        $operateur = User::create([
            'organization_id' => $orgId,
            'name' => 'Opérateur de saisie',
            'email' => 'operateur@jareindustrie.ht',
            'password' => Hash::make('operateur123'),
            'role' => 'operateur'
        ]);
        $operateur->update(['telephone' => '509-3756-7890', 'adresse' => 'Port-au-Prince, Haïti']);

        // Create some test clients
        $clients = [
            [
                'organization_id' => $orgId,
                'ref_client' => 'CLI-000001',
                'categorie_client' => 'individuel',
                'nom_prenom' => 'Jean Pierre',
                'email' => 'client@jareindustrie.ht',
                'telephone' => '509-3401-2345',
                'date_naissance' => '1985-03-15',
                'nationalite' => 'Haïtienne',
                'situation_matrimoniale' => 'marie',
                'nombre_enfants' => 2,
                'nature_piece' => 'cni',
                'numero_piece' => 'CI-1985-0315'
            ],
            [
                'organization_id' => $orgId,
                'ref_client' => 'CLI-000002',
                'categorie_client' => 'mutuelle',
                'nom_prenom' => 'Mutuelle des Enseignants du Primaire',
                'email' => 'mutuelle.enseignants@email.com',
                'telephone' => '509-3402-3456',
                'date_naissance' => '2000-01-01',
                'nationalite' => 'Haïtienne',
                'nature_piece' => 'passeport',
                'numero_piece' => 'PA-2023-001'
            ]
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        // Compte portail client de test
        User::create([
            'organization_id' => $orgId,
            'name' => 'Jean Pierre',
            'email' => 'client@jareindustrie.ht',
            'password' => Hash::make('client123456'),
            'role' => User::ROLE_CLIENT,
            'telephone' => '509-3401-2345',
            'email_verified_at' => now(),
        ]);

        // Create some test projets
        $projets = [
            [
                'organization_id' => $orgId,
                'nom' => 'Résidence Belle-Vue',
                'localisation' => 'Delmas 75, Pétion-Ville',
                'superficie' => 5000,
                'nb_logements' => 50,
                'est_actif' => true,
                'est_mutuelle' => false,
                'cree_par' => $admin->id
            ],
            [
                'organization_id' => $orgId,
                'nom' => 'Complexe Commercial Carrefour',
                'localisation' => 'Route de Carrefour',
                'superficie' => 3000,
                'nb_logements' => 30,
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
        $this->command->info('- Client: client@jareindustrie.ht / mot de passe: client123456');
    }
}
