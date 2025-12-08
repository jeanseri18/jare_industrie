<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

try {
    // Test data
    $testData = [
        'type_client' => 'individuel',
        'nom' => 'Test',
        'prenom' => 'User',
        'email' => 'test@example.com',
        'telephone' => '1234567890',
        'adresse' => 'Test Address',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];
    
    echo "Testing registration...\n";
    
    // Check if email already exists
    $existingUser = User::where('email', $testData['email'])->first();
    if ($existingUser) {
        echo "User already exists, deleting...\n";
        $existingUser->client()->delete();
        $existingUser->delete();
    }
    
    // Create user
    echo "Creating user...\n";
    $user = User::create([
        'name' => $testData['nom'] . ' ' . $testData['prenom'],
        'email' => $testData['email'],
        'password' => Hash::make($testData['password']),
        'role' => 'client',
        'telephone' => $testData['telephone'],
        'adresse' => $testData['adresse'],
    ]);
    
    echo "User created with ID: " . $user->id . "\n";
    
    // Create client
    echo "Creating client...\n";
    try {
        $client = Client::create([
            'user_id' => $user->id,
            'type_client' => $testData['type_client'],
            'nom' => $testData['nom'],
            'prenom' => $testData['prenom'],
            'telephone' => $testData['telephone'],
            'adresse' => $testData['adresse'],
            'ville' => 'Port-au-Prince',
            'code_postal' => '1234',
            'email' => $testData['email'],
            'statut' => 'actif',
            'date_adhesion' => now(),
        ]);
        echo "Client created successfully!\n";
    } catch (Exception $clientException) {
        echo "Client creation failed: " . $clientException->getMessage() . "\n";
        
        // Get the full SQL error if it's a QueryException
        if (method_exists($clientException, 'getSql')) {
            echo "SQL: " . $clientException->getSql() . "\n";
            echo "Bindings: " . json_encode($clientException->getBindings()) . "\n";
        }
        
        // Get previous exception
        if ($clientException->getPrevious()) {
            echo "Previous: " . $clientException->getPrevious()->getMessage() . "\n";
        }
        
        // Clean up user
        $user->delete();
        echo "Test failed!\n";
        exit(1);
    }
    
    echo "Client created with ID: " . $client->id . "\n";
    echo "Registration test successful!\n";
    
    // Cleanup
    echo "Cleaning up...\n";
    $client->delete();
    $user->delete();
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}