<?php
// DIC configuration
use Classes\Authenticate;

define("AUTHENTICATE_KEY", "RKypTy0HJeyklSAxQm30amSeGZ8OCDR2znNdU4Dm723ykqSaMVigYqCvc/f9A8HXFSkkbYwDlJYxPY2K2+lTLuddlTEof9ggu/pVsTRjlXGZh3JkVAGpmGpjhuuSgPLJh8vjOJahOfFjWP2dIQPS16evgL+z38COWVUF64003Uo=");

$container = $app->getContainer();

// PDO Connection
$container['db'] = function ($c) {

	define('DB_HOST'        , "192.168.1.39");
	define('DB_USER'        , "SA");
	define('DB_PASSWORD'    , "Easy010239tag//");
	define('DB_NAME'        , "db_sgt");

	/// Não alterar

	$dsn = "sqlsrv:". "Server=" . DB_HOST . ";";
    $dsn .= "Database=".DB_NAME.";";
	$usr = DB_USER;
	$pwd = DB_PASSWORD;

	$pdo = new \Slim\PDO\Database($dsn, $usr, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));

    return $pdo;
};

$container['auth'] = function ($c){
	$Authenticate = new Authenticate($c['db']);	
	return $Authenticate;
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
