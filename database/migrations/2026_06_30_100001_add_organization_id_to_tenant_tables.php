<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tenantTables = [
        'users',
        'projets',
        'clients',
        'mutuelles',
        'souscriptions',
        'activity_logs',
    ];

    public function up(): void
    {
        foreach ($this->tenantTables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'organization_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->foreignId('organization_id')->nullable()->after('id')->constrained()->nullOnDelete();
                });
            }
        }

        $orgId = DB::table('organizations')->insertGetId([
            'name' => 'JARE Industries Côte d\'Ivoire',
            'slug' => 'jare-industries',
            'subdomain' => 'jare',
            'is_active' => true,
            'plan' => 'enterprise',
            'settings' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('organization_branding')->insert([
            'organization_id' => $orgId,
            'primary_color' => '#ff7200',
            'secondary_color' => '#1e3a8a',
            'accent_color' => '#4CAF50',
            'legal_name' => 'JARE INDUSTRIES CÔTE D\'IVOIRE',
            'tagline' => 'PROMOTEUR IMMOBILIER AGRÉÉ',
            'director_name' => 'Roland KOUAKOU',
            'director_title' => 'Directeur Général',
            'rccm' => 'CI-ABJ-03-2023-M-11022',
            'cc' => '1517590M',
            'address' => 'Abidjan Cocody, 2 Plateaux Macaci, Villa 280/150 Lot 53',
            'city' => 'Abidjan',
            'country' => 'Côte d\'Ivoire',
            'phone' => '+225 21 20 80 54 20',
            'whatsapp' => '+225 07 03 94 03 14',
            'email' => 'emmanuela.kore@jare-industries.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($this->tenantTables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->whereNull('organization_id')->update(['organization_id' => $orgId]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->unique(['organization_id', 'email']);
        });

        if (Schema::hasTable('mutuelles')) {
            Schema::table('mutuelles', function (Blueprint $table) {
                $table->dropUnique(['code']);
                $table->unique(['organization_id', 'code']);
            });
        }

        if (Schema::hasTable('souscriptions') && Schema::hasColumn('souscriptions', 'ref_souscription')) {
            Schema::table('souscriptions', function (Blueprint $table) {
                $table->dropUnique(['ref_souscription']);
                $table->unique(['organization_id', 'ref_souscription']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('souscriptions') && Schema::hasColumn('souscriptions', 'ref_souscription')) {
            Schema::table('souscriptions', function (Blueprint $table) {
                $table->dropUnique(['organization_id', 'ref_souscription']);
                $table->unique(['ref_souscription']);
            });
        }

        if (Schema::hasTable('mutuelles')) {
            Schema::table('mutuelles', function (Blueprint $table) {
                $table->dropUnique(['organization_id', 'code']);
                $table->unique(['code']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['organization_id', 'email']);
            $table->unique(['email']);
        });

        foreach (array_reverse($this->tenantTables) as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'organization_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropConstrainedForeignId('organization_id');
                });
            }
        }
    }
};
