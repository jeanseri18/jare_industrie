<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('users')->where('email', 'superadmin@platform.local')->exists()) {
            return;
        }

        DB::table('users')->insert([
            'organization_id' => null,
            'name' => 'Super Administrateur',
            'email' => 'superadmin@platform.local',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'requires_dg_validation' => false,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'superadmin@platform.local')->delete();
    }
};
