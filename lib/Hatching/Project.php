<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Hatching;


class Project {

    protected $directory;

    function __construct($directory){
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


}