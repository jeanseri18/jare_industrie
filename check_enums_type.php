<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Souscriptions mode_paiement type:\n";
print_r(Illuminate\Support\Facades\DB::select("DESCRIBE souscriptions mode_paiement"));

echo "\nPaiements mode type:\n";
print_r(Illuminate\Support\Facades\DB::select("DESCRIBE paiements mode"));
