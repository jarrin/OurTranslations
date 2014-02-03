<?php

require "helpers/functions.php";

$pstart = calcStart();

/*
Defines
*/

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

define("BASE", dirname(__FILE__) . "/");


function __autoload($class_name) 
{
    include(BASE . "classes/" . $class_name . ".class.php");
}



require "variables/config.php";


//Make DB connection

$db = new db("mysql:host=". $GLOBALS['c']['db']['host'] .";port=". $GLOBALS['c']['db']['port'] .";dbname=". $GLOBALS['c']['db']['db-name'] ."", $GLOBALS['c']['db']['user'], $GLOBALS['c']['db']['password']);
$db->setErrorCallbackFunction("myErrorHandler");
//Switching controller
if(!isset($_GET['controller'])) $_GET['controller'] = "main";

$controller = "";

switch($_GET['controller'])
{
	case "ajax":
		$controller = "ajax";
	break;

	case "main":
		$controller = "main";
	break;
}

$controller_file = BASE . "controllers/" . $controller . ".php";

unset($_GET['controller']); //not needed anymore.

if(file_exists($controller_file))
{
	require $controller_file;
	$controller = new Controller($_GET, $_POST, $db);
}
else
{
}
calcStop($pstart);
$controller->out();