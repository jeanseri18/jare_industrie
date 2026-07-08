<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subdomain')->unique();
            $table->boolean('is_active')->default(true);
            $table->enum('plan', ['starter', 'pro', 'enterprise'])->default('pro');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_branding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('logo_path')->nullable();
            $table->string('watermark_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('primary_color', 7)->default('#ff7200');
            $table->string('secondary_color', 7)->default('#1e3a8a');
            $table->string('accent_color', 7)->default('#4CAF50');
            $table->string('legal_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('director_name')->nullable();
            $table->string('director_title')->nullable();
            $table->string('rccm')->nullable();
            $table->string('cc')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Côte d\'Ivoire');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('footer_text')->nullable();
            $table->longText('contract_clauses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_branding');
        Schema::dropIfExists('organizations');
    }
};
