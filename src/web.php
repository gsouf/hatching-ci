<?php

include __DIR__ . "/common-bootstrap.php";
/* @var $di \Phalcon\DI */
/* @var $config \Phalcon\Config */


$debug = new \Phalcon\Debug();
$debug->listen();

function twig($template,$data = array()){
    global $di;
    return $di->get("twig")->render($template, $data);
}

$app = new Phalcon\Mvc\Micro();

// defines the routes
include APP_ROOT . "/app/routes.php";

$app->handle();