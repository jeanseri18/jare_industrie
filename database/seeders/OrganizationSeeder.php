<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationBranding;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        if (Organization::withoutGlobalScopes()->where('slug', 'jare-industries')->exists()) {
            return;
        }

        $org = Organization::withoutGlobalScopes()->create([
            'name' => 'JARE Industries Côte d\'Ivoire',
            'slug' => 'jare-industries',
            'subdomain' => 'jare',
            'is_active' => true,
            'plan' => 'enterprise',
            'settings' => [],
        ]);

        OrganizationBranding::create([
            'organization_id' => $org->id,
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
        ]);
    }
}
