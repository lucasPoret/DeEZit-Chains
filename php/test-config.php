<?php
require 'config.php';

echo "=== Configuration MySQL ===\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_PASSWORD: " . (DB_PASSWORD ? 'SET (length: ' . strlen(DB_PASSWORD) . ')' : 'NOT SET') . "\n";
echo "DB_NAME: " . DB_NAME . "\n";

echo "\n=== Test de connexion ===\n";
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn) {
    echo "✓ Connexion réussie!\n";
    mysqli_close($conn);
} else {
    echo "✗ Erreur: " . mysqli_connect_error() . "\n";
}
?>

