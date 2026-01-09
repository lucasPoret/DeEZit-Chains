<?php
// Ce fichier garantit que les variables d'environnement sont disponibles
// Lecture depuis plusieurs sources pour compatibilité maximale
if (!defined('ENV_INIT_LOADED')) {
    define('ENV_INIT_LOADED', true);
    
    // Essayer de lire depuis getenv() d'abord (variables du conteneur Docker)
    $db_host = getenv('DB_HOST');
    $db_user = getenv('DB_USER');
    $db_password = getenv('DB_PASSWORD');
    $db_name = getenv('DB_NAME');
    
    // Si pas trouvé, essayer $_SERVER (Apache PassEnv)
    if (!$db_host && isset($_SERVER['DB_HOST'])) {
        $db_host = $_SERVER['DB_HOST'];
        putenv('DB_HOST=' . $db_host);
    }
    if (!$db_user && isset($_SERVER['DB_USER'])) {
        $db_user = $_SERVER['DB_USER'];
        putenv('DB_USER=' . $db_user);
    }
    if (!$db_password && isset($_SERVER['DB_PASSWORD'])) {
        $db_password = $_SERVER['DB_PASSWORD'];
        putenv('DB_PASSWORD=' . $db_password);
    }
    if (!$db_name && isset($_SERVER['DB_NAME'])) {
        $db_name = $_SERVER['DB_NAME'];
        putenv('DB_NAME=' . $db_name);
    }
    
    // Mettre aussi dans $_ENV pour compatibilité
    if ($db_host && !isset($_ENV['DB_HOST'])) {
        $_ENV['DB_HOST'] = $db_host;
    }
    if ($db_user && !isset($_ENV['DB_USER'])) {
        $_ENV['DB_USER'] = $db_user;
    }
    if ($db_password && !isset($_ENV['DB_PASSWORD'])) {
        $_ENV['DB_PASSWORD'] = $db_password;
    }
    if ($db_name && !isset($_ENV['DB_NAME'])) {
        $_ENV['DB_NAME'] = $db_name;
    }
}
?>

