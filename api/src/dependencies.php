<?php
// DIC configuration

$container = $app->getContainer();

// PDO Connection
$container['db'] = function ($c) {

	define('DB_HOST'        , "DESKTOP-JS575OC");
	define('DB_USER'        , NULL);
	define('DB_PASSWORD'    , NULL);
	define('DB_NAME'        , "NecBrasil_Emissor_CTE");

	/// Não alterar

	$dsn = "sqlsrv:". "Server=" . DB_HOST . ";";
    $dsn .= "Database=".DB_NAME.";";
	$usr = DB_USER;
	$pwd = DB_PASSWORD;

	$pdo = new \Slim\PDO\Database($dsn, $usr, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));

	// $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  

    return $pdo;
};

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
