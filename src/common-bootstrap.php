<?php


//**********************
//  INIT THE APPLICATION
//**********************

// define root path to the application
define("APP_ROOT" , __DIR__ . "/..");

// init autoloader
include APP_ROOT . "/vendor/autoload.php";


$config = include APP_ROOT . "/app/config.php";
$di     = include APP_ROOT . "/app/services.php";