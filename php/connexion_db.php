<?php
	// Charger la configuration
	require_once __DIR__ . '/config.php';
	
	// Utiliser les constantes définies dans config.php
	$hostname = DB_HOST;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$dbname = DB_NAME;

	// Tentative de connexion avec retry (jusqu'à 5 tentatives) et options spécifiques
	$connexion = false;
	$max_attempts = 5;
	$attempt = 0;
	
	while (!$connexion && $attempt < $max_attempts) {
		// Créer une connexion avec options pour forcer l'authentification correcte
		$connexion = @mysqli_init();
		if ($connexion) {
			// Définir les options de connexion
			mysqli_options($connexion, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
			mysqli_options($connexion, MYSQLI_INIT_COMMAND, "SET sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
			
			// Établir la connexion
			$connected = @mysqli_real_connect($connexion, $hostname, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_FOUND_ROWS);
			
			if (!$connected) {
				@mysqli_close($connexion);
				$connexion = false;
			}
		}
		
		if (!$connexion) {
			$attempt++;
			if ($attempt < $max_attempts) {
				usleep(300000); // Attendre 0.3 seconde entre les tentatives
			}
		}
	}
	
	// Vérifier la connexion et logger les erreurs
	if (!$connexion) {
		error_log("Erreur de connexion MySQL après $max_attempts tentatives - Host: $hostname, User: $username, DB: $dbname - " . (isset($connected) ? mysqli_connect_error() : 'Erreur d\'initialisation'));
	}


?>
