<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Souscriptions mode_paiement:\n";
print_r(Illuminate\Support\Facades\DB::table('souscriptions')->distinct()->pluck('mode_paiement')->toArray());

echo "\nPaiements mode:\n";
print_r(Illuminate\Support\Facades\DB::table('paiements')->distinct()->pluck('mode')->toArray());
