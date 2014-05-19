<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Hatching;

function getPublicObjectVars($obj) {
    return get_object_vars($obj);
}

class Model {



    public function getStorable(){
        $vars = getPublicObjectVars($this);
        return $vars;
    }

    /**
     * @param $data
     * @return Project
     */
    public static function revive($data){
        $className = get_called_class();
        $p = new $className();
        foreach($data as $k=>$v){
            $p->$k = $v;
        }
        return $p;
    }


} 