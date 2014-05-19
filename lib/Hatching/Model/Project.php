<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Hatching\Model;

use Hatching\Model;
use Hatching\ProjectConfiguration;
use Phalcon\DI;

class Project extends Model {

    public $id;
    public $directory;
    public $name;

    function __construct($directory = null){
        if($directory)
            $this->directory = rtrim($directory,"/");
    }

    /**
     * @return ProjectConfiguration|null
     */
    public function getProjectConfiguration(){

        $fileName = $this->directory . "/hatching.yml";

        if(!file_exists($fileName))
            return null;

        $data = \Spyc::YAMLLoad($fileName);

        if(!$data)
            return null;
        else
            return new ProjectConfiguration($data);

    }



    public static function getByName(\MongoDB $mongo , $name){

        $set = $mongo->project->find(array(
            "name"=>$name
        ));
        if($set->count()>0)
            return Project::revive($set->getNext());
        return false;
    }

    public static  function getById(\MongoDB $mongo , $id){
        $set = $mongo->project->find(array(
            "_id"=>$id
        ));
        if($set->count()>0)
            return Project::revive($set->getNext());
        return false;
    }
} 