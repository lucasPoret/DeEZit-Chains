<?php
require 'config.php';

echo "=== Configuration MySQL ===\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_PASSWORD: " . (DB_PASSWORD ? 'SET (length: ' . strlen(DB_PASSWORD) . ')' : 'NOT SET') . "\n";
echo "DB_NAME: " . DB_NAME . "\n";

echo "\n=== Test de connexion ===\n";
// Tentative de connexion avec retry (jusqu'à 3 tentatives)
$conn = false;
$max_attempts = 3;
$attempt = 0;

while (!$conn && $attempt < $max_attempts) {
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        $attempt++;
        if ($attempt < $max_attempts) {
            usleep(500000); // Attendre 0.5 seconde entre les tentatives
        }
    }
}

if ($conn) {
    echo "✓ Connexion réussie!\n";
    mysqli_close($conn);
} else {
    echo "✗ Erreur: " . mysqli_connect_error() . "\n";
}
?>

