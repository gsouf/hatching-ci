<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */

/* @var $config \Phalcon\Config */


$di = new \Phalcon\DI();


$di->setShared("config",$config);

$di->setShared("mongo",function() use($config){


    $host = $config->get("mongo")["host"];
    $user = $config->get("mongo")["user"];
    $pswd = $config->get("mongo")["password"];
    $port = $config->get("mongo")["port"];
    $db   = $config->get("mongo")["database"];

    $connectionStr = "mongodb://" . ($user ? $user . ($pswd ? ":$pswd":'') . "@" : "") . "$host:$port";


    $mongo = new MongoClient($connectionStr);

    return $mongo->$db;

});


$di->setShared('twig',function() use($config){

    $twigOptions = array();

    if($config->get("environment") === 'prod'){
        $twigOptions["cache"] = APP_ROOT . '/data/cache/view';
    }

    $loader = new Twig_Loader_Filesystem(APP_ROOT . '/app/view');
    $twig   = new Twig_Environment( $loader, $twigOptions);
    return $twig;
});

return $di;