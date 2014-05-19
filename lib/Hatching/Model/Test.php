<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Hatching\Model;

use Hatching\Model;
use Hatching\ProjectConfiguration;
use Phalcon\DI;

class Test extends Model {

    public $projectId;

    public $dateBegin;
    public $dateEnd;

    /**
     * null : pending
     * 0 : success
     * other : failled
     */
    public $status;
    public $executionString;




} 