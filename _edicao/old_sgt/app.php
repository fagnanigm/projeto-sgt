<?php

date_default_timezone_set("Brazil/East");

// Configuração
error_reporting(E_ALL^E_WARNING^E_NOTICE);
define("DIR_FUNCTION","include/library/");
define("DIR_CONFIG","include/config/");
define("DIR_MODEL","include/model/");
define("DIR_UPLOAD",str_replace(array('\\'),array('/'),dirname(__FILE__))."/static/");
define("DIR_TEMPLATE",dirname(__FILE__)."/include/compiled/");
session_start();
$ip = $_SERVER['REMOTE_ADDR'];

//ini_set("display_errors",'1');

//Arquivo de Funções
//Conexão PDO & funções Query

include_once(DIR_CONFIG."db.php");
include_once(DIR_FUNCTION."connection.php");

$connection = ConnectDB();

include_once(DIR_FUNCTION."functions.php");

// Natureza
include_once(DIR_MODEL."natureza/class.natureza.php");

// Motorista 
include_once(DIR_MODEL."motorista/class.motorista.php");

// Veiculo 
include_once(DIR_MODEL."veiculo/class.veiculo.php");

$logged_admin = (isset($_SESSION['Customer']) ? $_SESSION['Customer'] : false);

$business_context = (isset($_SESSION['Context']) ? $_SESSION['Context'] : 'Cruz de Malta');


?>