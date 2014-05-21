<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Hatching\Model;

use CliStart\TestCommand;
use Hatching\Model;
use Hatching\ProjectConfiguration;
use Phalcon\DI;

class Project extends Model {

    public $directory;
    public $name;
    public $creationDate;

    function __construct($directory = null){
        if($directory)
            $this->directory = rtrim($directory,"/");
    }

    /**
     * @return ProjectConfiguration|null
     */
    public function getProjectConfiguration($trueProjectRoot){

        $fileName = $trueProjectRoot . "/hatching.yml";

        if(!file_exists($fileName))
            return null;

        $data = \Spyc::YAMLLoad($fileName);

        if(!$data)
            return null;
        else
            return new ProjectConfiguration($data);

    }


    /**
     * @param \MongoDB $mongo
     * @param $name
     * @return bool|Project
     */
    public static function getByName(\MongoDB $mongo , $name){

        $set = $mongo->project->find(array(
            "name"=>$name
        ));
        if($set->count()>0)
            return Project::revive($set->getNext());
        return false;
    }

    /**
     * @param \MongoDB $mongo
     * @param $id
     * @return bool|Project
     */
    public static  function getById(\MongoDB $mongo , $id){
        $set = $mongo->project->find(array(
            "_id"=>$id
        ));
        if($set->count()>0)
            return Project::revive($set->getNext());
        return false;
    }


    public static function getSynopsis(DI $di){
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

        usort($projects,function($a,$b){

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

        return $projects;

    }

} 