#!/usr/bin/php
<?php

if(php_sapi_name() != "cli")
    die("This scriptis only CLI callable");

include __DIR__ . "/common-bootstrap.php";
/* @var $di \Phalcon\DI */
/* @var $config \Phalcon\Config */



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
$dataAdapter->setRunDir(APP_ROOT . "/cli/run");
$cli->setDataAdapter($dataAdapter);


// configure logger
$cli->runLog = APP_ROOT . "/cli/log/run.log";
$cli->errorLog = APP_ROOT . "/cli/log/error.log";



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