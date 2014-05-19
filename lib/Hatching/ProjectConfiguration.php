<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Hatching;


class ProjectConfiguration {

    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getSetup(){
        return $this->data["setup"];
    }

    public function getRun(){
        return $this->data["run"];
    }



}