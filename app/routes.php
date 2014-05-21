<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


/* @var $di \Phalcon\DI */
/* @var $config \Phalcon\Config */
/* @var $app \Phalcon\Mvc\Micro */


$app->get('/', function () use ($di) {

    $projectsRaw = $di->get("mongo")->project->find();
    $projects = array();

    while($projectRaw = $projectsRaw->getNext()){
        $project = \Hatching\Model\Project::revive($projectRaw);

        $testCursor = $di->get("mongo")->test
            ->find(array(
                "projectId" => $project->_id
            ))->sort( array(
                "_id"=> -1
            ))->limit(1);

        if($testCursor->count() > 0){
            $test = \Hatching\Model\Test::revive($testCursor->getNext());
        }else{
            $test = null;
        }

        $projects[] = array("project"=> $project , "test"=>$test);

    }

    $success = usort($projects,function($a,$b){

        if($a["test"] && $b["test"]){
            $val = $a["test"]->dateBegin - $b["test"]->dateBegin;
        }else{

            if($a["test"]){
                $val =  $a["test"]->dateBegin - $b["project"]->creationDate;
            }else if($b["test"]){
                $val =  $a["project"]->creationDate - $b["test"]->dateBegin;
            }else{
                $val =  $a["project"]->creationDate - $b["project"]->creationDate;
            }
        }

        return $val * -1;

    });



    echo twig("index.twig",array("projects"=>$projects));
});
