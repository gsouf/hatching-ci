<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */

// define root path to the application
define("APP_ROOT" , __DIR__ . "/..");

// init autoloader
include APP_ROOT . "/vendor/autoload.php";

$app = new Phalcon\Mvc\Micro();

// defines the routes
include APP_ROOT . "/app/routes.php";

$app->handle();