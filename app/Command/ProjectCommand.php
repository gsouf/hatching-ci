<?php

/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


namespace Command;


use Hatching\Model\Project;

class ProjectCommand extends  CommandBase {

    public function createProject(){

        $projectName = $this->getArgValue("project-name");
        $projectDir  = $this->getArgValue("project-location");

        $project = Project::getByName($this->getMongo(),$projectName);

        if($project){
            echo "project $projectName already exists";
            return;
        }

        $p = new Project($projectDir);
        $p->name = $projectName;

        $arrayInsert = $p->getStorable();

        $this->getMongo()->project->insert($arrayInsert);





    }


} 