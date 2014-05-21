<?php
/**
 * @copyright (c) Rock A Gogo VPC
 */


return new \Phalcon\Config(array(
    'mongo' => array(
        'host'        => 'localhost',
        'user'    => 'root',
        'password'    => '',
        'database'    => 'hatchingci',
        'port'        => '27017'
    ),

    'environment' => "dev"
));