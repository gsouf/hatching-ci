#!/usr/bin/php
<?php

if(php_sapi_name() != "cli")
    die("This scriptis only CLI callable");


//**********************
//  INIT THE APPLICATION
//**********************

// define root path to the application
define("APP_ROOT" , __DIR__ . "/..");

// init autoloader
include APP_ROOT . "/vendor/autoload.php";


$config = include APP_ROOT . "/app/config.php";
$di     = include APP_ROOT . "/app/services.php";


// init daemon
$daemon = new \CliStart\Daemon();
$daemon->initialize();

// parse args
$daemon->parseInputArgs($_SERVER['argv']);


$cli = new \CliStart\Cli();
$cli->di = $di;

// registering daemon
$cli->daemon($daemon);

// config the base application
include APP_ROOT . "/app/commands.php";


// configure the io
$dataAdapter = new \CliStart\DataAdapter\JsonFile();
$dataAdapter->setRunDir(APP_ROOT . "/cli/cs-data/run");
$cli->setDataAdapter($dataAdapter);


// configure logger
$cli->runLog = APP_ROOT . "/cli/cs-data/log/run.log";
$cli->errorLog = APP_ROOT . "/cli/cs-data/log/error.log";



//*******************************************************
//  BEFORE DAEMONIZING WE CHECK IF ALL REQUIREMENT ARE OK
//*******************************************************

try{
    if(!$cli->checkRequirement()){
        die("error");
    }
}catch (\Exception $e){
    die($e->getMessage());
}



//**************************************************
// all basical requirements are ok, now DAEMON JOB !
//**************************************************

try{
    $cli->start();
}catch (\Exception $e){
    die($e->getMessage());
}


