<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Command;


use CliStart\Command;
use Phalcon\DI;

class CommandBase extends Command {

    public function getDi(){
        return $this->getCli()->di;
    }


    /**
     * @return \MongoDB
     */
    public function getMongo(){
        return $this->getDi()->get("mongo");
    }

}