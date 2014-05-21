<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


/* @var $di \Phalcon\DI */
/* @var $config \Phalcon\Config */
/* @var $app \Phalcon\Mvc\Micro */


use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Hatching\Model\Project;
use Hatching\Model\Test;

$app->get('/', function () use ($di) {
    $projects = Project::getSynopsis($di);
    echo twig("index.twig",array("projects"=>$projects));
});


$app->get('/project/{name}', function ($name) use ($di) {
    $project = Project::getByName($di->get("mongo"),$name);

    if($project){
        $tests = Test::getByProjectId($di->get("mongo"),$project->_id);
        $tests = array_reverse($tests);

        $ansiConverter = new AnsiToHtmlConverter(new \SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme());

        echo twig("project.twig",array("project"=>$project,"tests"=>$tests,"ansi_converter"=>$ansiConverter));
    }else{
        echo "TODO 404";
    }
});



$app->post('/build/{name}',function($name) use ($di){
    $di->get('cli-caller')->launchBackground("runtest",array("project-name"=>$name));

});
