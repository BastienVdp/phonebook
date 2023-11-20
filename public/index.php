<?php
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Permettre l'accès depuis n'importe quelle origine
header("Access-Control-Allow-Origin: *");

// Autoriser les méthodes de requête spécifiques
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");

// Permettre les cookies en réponse
header("Access-Control-Allow-Credentials: true");

if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header("Access-Control-Allow-Headers: Content-Type, *");
	exit;
}

ini_set('display_errors', 1);

require '../vendor/autoload.php';

$config = require '../app/config.php';

$app = new App\Core\Application(
	dirname(__DIR__),
	$config
);

require '../routes/api.php';

require '../routes/web.php';

$app->run();
